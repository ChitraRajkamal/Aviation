<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportStudent;
use App\Models\Organization;
use App\Models\OrganizationRole;
use App\Models\User;
use App\Traits\ValidationsTrait;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Hash;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Str;
use Validator;
use Illuminate\Database\QueryException;
use Exception;

class UserController extends Controller
{
    use ValidationsTrait;
    public $superAdminId = "";

    public function __construct(){
        $this->superAdminId = config('constants.SUPER_ADMIN_ID');
    }

    public function admin_users(Request $request)
    {
        return $this->user_list($request, 'admin');
    }

    public function organization_users(Request $request)
    {
        return $this->user_list($request, 'organization');
    }

    public function student_users(Request $request)
    {
        return $this->user_list($request, 'student');
    }

    /**
     * Display a listing of the resource.
     */
    private function user_list(Request $request, $role)
    {
        $where = [
            ['id', '!=', $this->superAdminId],
            ['role', $role]
        ];
        $organizationId = $request->id;
        $userList = User::where($where);
        if($request->filter){
            $searchTerm = $request->search;
            $organizationId = $request->organization_id;

            if($searchTerm){
                $userList = $userList->where(function ($query) use($searchTerm){
                    $query->where('first_name', 'LIKE', "%$searchTerm%")
                        ->orWhere('last_name', 'LIKE', "%$searchTerm%")
                        ->orWhereHas('organization', function ($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', "%$searchTerm%");
                        });
                });
            }            
        }
        
        if(lms_is_organization()){
            $organizationId = lms_organization_id();
        }
        if($organizationId){
            $userList = $userList->where('organization_id', $organizationId);
        }
        $userList = $userList->orderBy('first_name')
        ->paginate(lms_setting('admin_pagination_size'))
        ->appends($request->all());

        $organizations = Organization::where('status', 1)->get();        
        return view("admin.users.$role", compact('userList', 'role', 'organizations', 'organizationId'));
    }

    public function create_student(){
        return view('admin.users.create-student');
    }

    public function edit_student($id)
    {
        $user = User::where([
            ['role', 'student'],
            ['id', $id]
        ])->first();
        if(!$user) abort(404);
        return view('admin.users.edit-student', compact('user'));
    }

    public function save_student(Request $request)
    {
        $id = $request->id;
        $data = $request->validate(
            $this->getStudentAddRules($id),
            $this->getStudentAddMessages()
        );
        
        if($id){
            $user = User::where([
                ['role', 'student'],
                ['id', $id],            
            ])->first();
            if(!$user){
                abort(404);
            }
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->mobile = $data['mobile'];
            $user->phone = $data['phone'] ?? '';
            $user->gender = $data['gender'];
            $user->dob = $data['dob'];
            $user->save();
        }else{
            $password = lms_random_password();
            $data['password'] = Hash::make($password);
            $data['role'] = 'student';
            $data['status'] = 1;
            $data['organization_id'] = lms_organization_id();
            $user = User::create($data);

            $appName = config('app.name');
            Mail::html("
                <h2>Hello, {$user->full_name}!</h2>
                <p>Welcome to {$appName}!</p>
                <p>Your username is <b>{$data['email']}</b></p>
                <p>Your password is <b>{$password}</b></p>
            ", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Student Registration');
            });
        }

        return to_route('admin.users.student')->with('alert', generate_alert(__('Student saved')));
    }

    public function import_students()
    {
        return view('admin.users.import-students');
    }

    public function upload_students(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'mimetypes:text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'max:10240'],
        ], [
            'file' => [
                'required' => 'Please upload question file',
                'mimetypes' => 'Only xlsx formats is allowed.',
                'max' => 'The file size should not exceed 10 MB.',
            ]
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = '';
        //$path = 'sample/sample-question-format.docx';
        //$path = 'sample/sample-student-format.xlsx';

        $image = $request->file('file');
        if($image){
            $path = $image->store("import/students", 'public');
        }

        $filePath = "storage/$path";
        if(file_exists($filePath)){
            $data = Excel::toArray([], $filePath);
            $excelHeaders = ['First Name', 'Last Name', 'Email ID', 'Mobile', 'Phone', 'Gender', 'DOB'];
            $students = [];
            foreach ($data[0] as $r_index => &$row) {
                $crow = lms_array_filter(array_map('trim', $row)); // Removes empty elements
                if($r_index == 0){
                    if($crow != $excelHeaders){
                        return to_route('admin.users.student.import')->with('alert', generate_alert(__('Please ensure that you are using correct file format'), 'danger'));
                    }
                    continue;
                }
                $dob = isset($row[6]) && is_numeric($row[6]) 
                        ? Date::excelToDateTimeObject($row[6])->format('Y-m-d')  // Converts to YYYY-MM-DD format
                        : ($row[6] ?? ''); 
                $mobile = $row[3] ?? '';
                $students []= [
                    'first_name' => $row[0] ?? '',
                    'last_name' => $row[1] ?? '',
                    'email' => $row[2] ?? '',
                    'mobile' => $mobile,
                    'phone' => $row[4] ?? '',
                    'gender' => $row[5] ?? '',
                    'dob' => $dob,
                ];
            }
            
            $grouping = Str::random(20);
            $students = array_map(function ($item) use($grouping) {
                $item['grouping'] = $grouping;
                $item['created_at'] = now(); // Add timestamps if needed
                $item['updated_at'] = now();
                return $item;
            }, $students);
            
                ImportStudent::insert($students);
            try{
            } catch (QueryException $e) {
                return back()->with('alert', generate_alert(__('Invalid data found in excel'), 'danger'));
            } catch (Exception $e) {
                return back()->with('alert', generate_alert(__('Error occurred while importing'), 'danger'));
            }
            return to_route('admin.users.student.review', ['grouping' => $grouping])->with('alert', generate_alert(__('Students extracted from document. Please review students details')));
        }
        return  redirect()->back()->with('alert', generate_alert(__('File is missing'), 'danger'));
    }

    public function review_students($grouping)
    {
        $students = ImportStudent::where([
            ['grouping', $grouping],
            ['status', 0]
        ])->get();
        if($students->isEmpty()) return to_route('admin.users.student');
        return view('admin.users.review-students', compact('grouping', 'students'));
    }

    public function save_bulk_students(Request $request, $grouping)
    {
        $students = ImportStudent::where([
            ['grouping', $grouping],
            ['status', 0]
        ])->get();
        if($students->isEmpty()) return to_route('admin.users.student');
        
        $valid_students = [];
        $errors = [];
        foreach ($students as $key => $q) {
            $c_student = [
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $c_student['first_name'] = $q['first_name'];
            $c_student['last_name'] = $q['last_name'];
            $c_student['email'] = $q['email'];
            $c_student['mobile'] = $q['mobile'];
            $c_student['password'] = Hash::make($q['mobile']);
            $c_student['phone'] = $q['phone'];
            $c_student['gender'] = $q['gender'];
            $c_student['dob'] = $q['dob'];
            $c_student['status'] = 1;
            $c_student['organization_id'] = lms_organization_id();
            $valid_students []= $c_student;

            $validator = Validator::make([
                'first_name' => $c_student['first_name'],
                'last_name'  => $c_student['last_name'],
                'email'      => $c_student['email'],
                'mobile'     => $c_student['mobile'],
                'dob'        => $c_student['dob'],
            ], [
                'first_name' => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'email'      => 'required|email|unique:users,email',
                'mobile'     => 'required',
                'dob'        => 'required|date|before:' . today()->subYears(lms_setting('user_min_age'))->format('Y-m-d'),
            ]);
    
            if ($validator->fails()) {
                $errors["error_$key"] = $validator->errors()->all();
                continue;
            }            
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->with('alert', generate_alert(__('Please check the highlighted validation errors'), 'danger'));
        }
        User::insert($valid_students);
        ImportStudent::where([
            ['grouping', $grouping],
            ['status', 0]
        ])->update([
            'status' => 1
        ]);
        return to_route('admin.users.student')->with('alert', generate_alert(__('Students imported successfully')));
    }

    public function activate($userId, $status)
    {
        if($userId == lms_user_id()){
            return redirect()->back()->with('alert', generate_alert(__('You cannot activate or deactivate your own account'), 'danger'));
        }
        $user = User::find($userId);
        if($user->organization && $user->organization->user_id == $userId){
            return redirect()->back()->with('alert', generate_alert(__('Org Admin cannot be deactivated'), 'danger'));
        }
        if($user && $user->id != $this->superAdminId){
            $user->status = $status == 1 ? 1 : 0;
            $user->save();
            return redirect()->back()->with('alert', generate_alert(__('User updated')));
        }else{
            return redirect()->back()->with('alert', generate_alert(__('User not available'), 'danger'));
        }
    }

    public function organizations(Request $request)
    {
        if(lms_is_organization()){
            return to_route('admin.users.organization');
        }
        $where = [
            ['status', 1]
        ];
        if($request->search){
            $searchTerm = $request->search;
            $where[] = ['name', 'LIKE', "%$searchTerm%"];
        }
        $organizationList = Organization::where($where)
            ->orderBy('id', 'desc')
            ->paginate(lms_setting('admin_pagination_size'))
            ->appends($request->all());
        
        return view('admin.users.organizations', compact('organizationList'));
    }

    public function create_organization(){
        if(lms_is_organization()){
            return to_route('admin.users.organization');
        }
        return view('admin.users.create-organization');
    }

    public function edit_organization($id)
    {
        if(lms_is_organization()){
            return to_route('admin.users.organization');
        }
        $user = User::where([
            ['role', 'organization'],
            ['id', $id]
        ])->first();
        if(!$user || $user->organization->user_id != $id) abort(404);
        return view('admin.users.edit-organization', compact('user'));
    }

    public function save_organization(Request $request)
    {
        if(lms_is_organization()){
            return to_route('admin.users.organization');
        }
        $id = $request->id;
        $data = $request->validate(
            $this->getOrganizationAddRules($id),
            $this->getOrganizationAddMessages()
        );
        
        if($id){
            $params = $request->all();
            $user = User::where([
                ['role', 'organization'],
                ['id', $id],            
            ])->first();
            if(!$user){
                abort(404);
            }
            $organization = Organization::find($user->organization_id);
            if(!$organization->user_id){
                abort(404);
            }

            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->mobile = $data['mobile'] ?? '';
            $user->phone = $data['phone'] ?? '';
            $user->gender = $data['gender'] ?? '';
            $user->dob = $data['dob'] ?? null;
            $user->save();
            
            $organization->name = $data['organization_name'];
            $organization->email = $data['organization_email'];
            $organization->phone = $params['organization_phone'];
            $organization->address = $params['organization_address'];
            $organization->city = $params['organization_city'];
            $organization->state = $params['organization_state'];
            $organization->country = $params['organization_country'];
            $organization->postal_code = $params['organization_postal_code'];
            $organization->save();
        }else{
            $organization = Organization::create([
                'name' => $data['organization_name'],
                'status' => 1
            ]);
            if(!$organization){
                return to_route('admin.users.organization.create')->with('alert', generate_alert(__('Organization not saved'), 'danger'));
            }

            $password = lms_random_password();
            $data['password'] = Hash::make($password);
            $data['role'] = 'organization';
            $data['status'] = 1;
            $data['organization_id'] = $organization->id;
            $user = User::create($data);

            $organization->user_id = $user->id;
            $organization->save();

            $appName = config('app.name');
            
            Mail::html("
                <h2>Hello, {$user->full_name}!</h2>
                <p>Welcome to {$appName}!</p>
                <p>Your username is <b>{$data['email']}</b></p>
                <p>Your password is <b>{$password}</b></p>
            ", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Organization Registration');
            });
        }        

        return to_route('admin.users.organizations')->with('alert', generate_alert(__('Organization saved')));
    }

    public function create_organization_staff($organizationId){
        if($organizationId != lms_organization_id()){
            abort(404);
        }
        $roles = OrganizationRole::where('organization_id', $organizationId)->get(); // Need to check
        return view('admin.users.create-organization-staff', compact('organizationId', 'roles'));
    }

    public function edit_organization_staff($organizationId, $id)
    {
        if($organizationId != lms_organization_id()){
            abort(404);
        }
        $user = User::where([
            ['role', 'organization'],
            ['id', $id]
        ])->first();
        if(!$user) abort(404);
        if($user->organization->user_id == $id){
            return to_route('admin.users.organization.edit', ['id' => $id]);
        }
        $roles = OrganizationRole::where('organization_id', $organizationId)->get(); // Need to check
        return view('admin.users.edit-organization-staff', compact('user', 'organizationId', 'roles'));
    }

    public function save_organization_staff(Request $request, $organizationId)
    {
        if($organizationId != lms_organization_id()){
            abort(404);
        }
        $id = $request->id;
        $data = $request->validate(
            $this->getOrganizationAddRules($id, false),
            $this->getOrganizationAddMessages()
        );
        
        if($id){
            $params = $request->all();
            $user = User::where([
                ['role', 'organization'],
                ['id', $id],            
            ])->first();
            if(!$user){
                abort(404);
            }
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->mobile = $data['mobile'] ?? '';
            $user->phone = $data['phone'] ?? '';
            $user->gender = $data['gender'] ?? '';
            $user->dob = $data['dob'] ?? null;
            $user->role_id = $data['role_id'];
            $user->save();
        }else{
            $organization = Organization::find($organizationId);
            if(!$organization){
                return to_route('admin.users.organization.create')->with('alert', generate_alert(__('Organization not found'), 'danger'));
            }

            $password = lms_random_password();
            $data['password'] = Hash::make($password);
            $data['role'] = 'organization';
            $data['status'] = 1;
            $data['organization_id'] = $organization->id;
            $user = User::create($data);

            $appName = config('app.name');
            
            $user->email = 'hussainmh39@gmail.com';
            Mail::html("
                <h2>Hello, {$user->full_name}!</h2>
                <p>Welcome to {$appName}!</p>
                <p>Your username is <b>{$data['email']}</b></p>
                <p>Your password is <b>{$password}</b></p>
            ", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Organization Staff Registration');
            });
        }        

        return to_route('admin.users.organization')->with('alert', generate_alert(__('Organization staff saved')));
    }
}
