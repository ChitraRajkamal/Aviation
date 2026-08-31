<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMenu;
use App\Models\OrganizationRole;
use App\Models\User;
use App\Traits\ValidationsTrait;
use Hash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ValidationsTrait;

    public function organizations(Request $request)
    {
        $rolesList = OrganizationRole::where([
            ['organization_id', lms_organization_id()],
            ['status', 1]
        ])->orderBy('name')->paginate(lms_setting('admin_pagination_size'));
        
        return view('admin.roles.organizations', compact('rolesList'));
    }

    public function create_organization_role(){
        $menus = OrganizationMenu::getMenus();
        return view('admin.roles.create-organization-role', compact('menus'));
    }

    public function edit_organization_role($id)
    {
        $role = OrganizationRole::where([
            ['organization_id', lms_organization_id()],
            ['id', $id]
        ])->first();
        if(!$role) abort(404);
        $menus = OrganizationMenu::getMenus();
        return view('admin.roles.edit-organization-role', compact('role', 'menus'));
    }

    public function save_organization_role(Request $request)
    {
        $id = $request->id;
        $data = $request->validate(
            $this->getOrganizationRoleAddRules($id),
            $this->getOrganizationRoleAddMessages()
        );
        $menu_ids = $request->menu_ids ?? [];
        $data['menu_ids'] = json_encode(explode(',', $menu_ids));
        
        $organizationId = lms_organization_id();
        $userId = lms_user_id();
        
        if($id){
            $user = OrganizationRole::where([
                ['organization_id', $organizationId],
                ['id', $id],            
            ])->first();
            if(!$user){
                abort(404);
            }
            $user->name = $data['name'];
            $user->description = $data['description'];
            $user->menu_ids = $data['menu_ids'];
            $user->updated_by_id = $userId;
            $user->save();
            return to_route('admin.roles.organization.edit', ['id' => $id])->with('alert', generate_alert(__('Role saved')));
        }else{
            try{
                $data['organization_id'] = $organizationId;
                $data['created_by_id'] = $userId;
                $data['status'] = 1;
                $user = OrganizationRole::create($data);
            }catch(\Exception $e){
                return back()->withInput()->with('alert', generate_alert('Could not save role', 'danger'));
            }
        }

        return to_route('admin.roles.organization')->with('alert', generate_alert(__('Role saved')));
    }

    public function delete_organization_role($id)
    {
        try {
            $user = User::where([
                ['organization_id', lms_organization_id()],
                ['role_id', $id]
            ])->exists();
            if($user){
                return back()->with('alert', generate_alert(__('Please ensure that the ROLE is not assigned to any staffs.'), 'danger'));
            }
            $organizationRole = OrganizationRole::where([
                ['organization_id', lms_organization_id()],
                ['id', $id]
            ])->first();
            if(!$organizationRole) abort(404);
            $organizationRole->delete();
            return back()->with('alert', generate_alert(__('Role deleted')));
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('alert', generate_alert(__('Please ensure that the ROLE is not assigned to any staffs.'), 'danger'));
            }
            throw $e;
        }
    }
}
