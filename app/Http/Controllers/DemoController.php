<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoController extends Controller
{
    public function gen_ratings(){

        exit;
        $courseIds = Course::all()->pluck('id')->toArray();

        $students = [
            ['Arjun','Kumar'],['Rahul','Sharma'],['Priya','Nair'],['Sneha','Reddy'],['Karthik','Raj'],
            ['Meera','Iyer'],['Vignesh','Kumar'],['Ananya','Sharma'],['Harish','Kumar'],['Aishwarya','Menon'],
            ['Rohit','Verma'],['Pooja','Singh'],['Sanjay','Patel'],['Divya','Krishnan'],['Naveen','Rao'],
            ['Akash','Gupta'],['Keerthana','S'],['Praveen','Kumar'],['Nithya','Raman'],['Vishal','Agarwal'],
            ['Abhishek','Mishra'],['Deepak','Yadav'],['Lakshmi','Priya'],['Varun','Krishna'],['Monika','Jain'],
            ['Ashwin','R'],['Rakesh','Babu'],['Shalini','Menon'],['Manoj','Kumar'],['Bhavya','Reddy'],
            ['Suresh','Patel'],['Gayathri','Nair'],['Ajay','Singh'],['Neha','Verma'],['Aravind','K'],
            ['Preethi','R'],['Sathish','Kumar'],['Nandhini','M'],['Vivek','Sharma'],['Swetha','Rao'],
            ['Mohan','Raj'],['Janani','K'],['Bharath','S'],['Lavanya','Priya'],['Yogesh','Kumar'],
            ['Kavya','R'],['Ranjith','K'],['Sowmya','Nair'],['Dinesh','Kumar'],['Shruthi','R'],
            ['Vikram','Patel'],['Reshma','Joseph'],['Gokul','R'],['Pavithra','S'],['Arun','Prakash'],
            ['Madhumitha','R'],['Senthil','Kumar'],['Harini','V'],['Naveena','S'],['Ganesh','R'],
            ['Hemalatha','K'],['Prakash','Raj'],['Mithun','Kumar'],['Nirmala','Devi'],['Sridhar','R'],
            ['Jennifer','Mary'],['Saravanan','K'],['Aparna','Nair'],['Kishore','Babu'],['Rekha','S'],
            ['Balaji','R'],['Anitha','Kumar'],['Sakthivel','R'],['Krithika','M'],['Vasanth','K'],
            ['Deepika','R'],['Surya','Prakash'],['Mahalakshmi','S'],['Hariharan','R'],['Vaishnavi','K'],
            ['Lokesh','Kumar'],['Sharmila','R'],['Vinoth','Kumar'],['Jeevitha','S'],['Ashok','R'],
            ['Nivetha','Priya'],['Pradeep','Kumar'],['Ramya','S'],['Siva','Kumar'],['Akhila','R']
        ];

        $reviews = [
            'Excellent course with practical learning opportunities.',
            'The trainers were knowledgeable and supportive throughout.',
            'Airport exposure visits helped me understand the industry better.',
            'A very good learning experience for aviation aspirants.',
            'Interview preparation sessions were extremely useful.',
            'The course content was easy to understand and well structured.',
            'I gained confidence and improved my communication skills.',
            'Highly recommended for students interested in aviation careers.',
            'The practical sessions were the highlight of the course.',
            'Faculty members explained concepts clearly.',
            'The learning environment was professional and friendly.',
            'I learned a lot about airline and airport operations.',
            'Excellent guidance from trainers and staff.',
            'The course exceeded my expectations.',
            'Very useful program for career development.',
            'I enjoyed every session and activity.',
            'The airport visit was an unforgettable experience.',
            'Good balance between theory and practical training.',
            'The grooming sessions helped improve my confidence.',
            'Career guidance sessions were very informative.',
            'Well organized course with excellent support.',
            'I would definitely recommend this academy.',
            'The trainers shared valuable industry insights.',
            'The placement guidance was very helpful.',
            'A great place to start an aviation career.',
            'The communication training improved my presentation skills.',
            'Very professional faculty and management.',
            'The practical activities made learning enjoyable.',
            'The course was informative and engaging.',
            'I gained industry knowledge and practical skills.',
            'The academy provided excellent career support.',
            'Every module was relevant and useful.',
            'The instructors were patient and encouraging.',
            'I appreciated the hands-on learning approach.',
            'A wonderful experience with excellent trainers.',
            'The course helped me become more confident.',
            'Very good exposure to aviation industry practices.',
            'The learning materials were easy to understand.',
            'The sessions were interactive and informative.',
            'Excellent support from the academy team.',
            'The course prepared me well for interviews.',
            'Practical learning made a big difference.',
            'The faculty was experienced and approachable.',
            'A valuable course for aviation students.',
            'I enjoyed the airport familiarization program.',
            'The academy focuses on real-world skills.',
            'Helpful trainers and well-designed curriculum.',
            'The interview tips were extremely useful.',
            'I gained practical aviation knowledge.',
            'The learning journey was enjoyable and rewarding.',
            'Professional training with great support.',
            'The course gave me clarity about aviation careers.',
            'Very well conducted sessions.',
            'Excellent balance of theory and practical exposure.',
            'The trainers motivated us throughout the course.',
            'A great experience from start to finish.',
            'The academy helped improve my soft skills.',
            'Industry exposure sessions were highly beneficial.',
            'I learned a lot from the practical demonstrations.',
            'The curriculum was comprehensive and relevant.',
            'The faculty shared real industry experiences.',
            'Very satisfied with the training quality.',
            'Aviation concepts were explained clearly.',
            'The course improved my confidence significantly.',
            'Excellent environment for learning and growth.',
            'The trainers always encouraged participation.',
            'Good career-oriented training program.',
            'The course helped me prepare for aviation jobs.',
            'I enjoyed the practical training sessions.',
            'Professional approach to teaching and mentoring.',
            'The academy provided excellent learning resources.',
            'Very supportive and experienced trainers.',
            'I would recommend this course to others.',
            'The placement support was encouraging.',
            'The course offered excellent industry exposure.',
            'Great learning experience overall.',
            'The academy helped build my professional skills.',
            'Useful course with practical applications.',
            'The trainers were always available to help.',
            'I gained valuable knowledge and confidence.',
            'The practical exercises were very effective.',
            'Excellent aviation training program.',
            'The course content was relevant and up-to-date.',
            'The academy exceeded my expectations.',
            'I learned many useful skills for my career.',
            'Very engaging and informative sessions.',
            'The trainers were highly professional.',
            'The airport visit added real value to learning.',
            'A fantastic opportunity to learn aviation concepts.',
            'I am happy with the overall training experience.'
        ];

        $reviewIndex = 0;
        $studentIndex = 0;

        foreach ($courseIds as $courseId) {

            for ($i = 1; $i <= 15; $i++) {

                $student = $students[$studentIndex];

                $user = User::create([
                    'first_name' => $student[0],
                    'last_name' => $student[1],
                    'email' => str()->slug($student[0].'.'.$student[1]).'.'.$studentIndex.'@example.com',
                    'mobile' => '9'.rand(100000000,999999999),
                    'phone' => '',
                    'role' => 'student',
                    'status' => 1,
                    'organization_id' => lms_organization_id(),
                    'password' => Hash::make('password123'),
                ]);

                Rating::create([
                    'user_id' => $user->id,
                    'type' => 'course',
                    'type_id' => $courseId,
                    'rating' => $i <= 12 ? 5 : 4,
                    'message' => $reviews[$reviewIndex],
                    'status' => 1,
                ]);

                $studentIndex++;
                $reviewIndex++;
            }
        }
    }
}
