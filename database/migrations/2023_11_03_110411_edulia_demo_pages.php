
<?php

use App\SmGeneralSettings;
use App\Models\FrontResult;
use App\SmHeaderMenuManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

return new class extends Migration
{

    function replace_array_recursive(string $needle, string $replace, array &$haystack)
    {
        array_walk_recursive(
            $haystack,
            function (&$item, $key, $data) {
                $item = str_replace($data['needle'], $data['replace'], $item);
                return $item;
            },
            ['needle' => $needle, 'replace' => $replace]
        );
    }

    public function up(): void
    {
        $filesInFolder = File::files(resource_path('/views/themes/edulia/demo/'));
        foreach ($filesInFolder as $path) {
            $file = pathinfo($path);
            if (file_exists($file['dirname'] . '/' . $file['basename'])) {
                $file_content =  file_get_contents(($file['dirname'] . '/' . $file['basename']));
                $file_data = json_decode($file_content, true);
                $this->replace_array_recursive("[App_url]", (url('/')), $file_data);
                if ($file_data) {
                    $check_exist  = DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->where('school_id', 1)->where('slug', $file_data['slug'])->first();
                    if (!$check_exist) {
                        DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->insert(
                            [
                                'name' => $file_data['name'],
                                'title' => $file_data['title'],
                                'description' => $file_data['description'],
                                'slug' => $file_data['slug'],
                                'settings' => json_encode($file_data['settings']),
                                'home_page' => $file_data['home_page'],
                                'status' => 'published',
                                'is_default' => 1,
                                'school_id' => 1
                            ]
                        );
                    }
                }
            }
        }


        // Header Menu manage Start
        $datas = 
          array (
              array (
                'type' => 'sPages',
                'element_id' => 12,
                'title' => 'Home',
                'link' => '/home',
                'position' => 1,
                'show' => 0,
                'is_newtab' => 0,
                'theme' => 'edulia',
                'school_id' => 1,
                'created_at' => '2024-01-05T07:37:19.000000Z',
                'updated_at' => '2024-01-05T07:40:14.000000Z',
                'childs' => 
                array (
                ),
              ),
            
              array (
                'type' => 'sPages',
                'element_id' => 2,
                'title' => 'About',
                'link' => '/aboutus-page',
                'position' => 2,
                'show' => 0,
                'is_newtab' => 0,
                'theme' => 'edulia',
                'school_id' => 1,
                'created_at' => '2024-01-05T07:37:52.000000Z',
                'updated_at' => '2024-01-05T07:40:14.000000Z',
                'childs' => 
                array (
                ),
              ),
            
              array (
                'type' => 'sPages',
                'element_id' => 5,
                'title' => 'Course',
                'link' => '/course',
                'position' => 3,
                'show' => 0,
                'is_newtab' => 0,
                'theme' => 'edulia',
                'school_id' => 1,
                'created_at' => '2024-01-05T07:38:04.000000Z',
                'updated_at' => '2024-01-05T07:40:14.000000Z',
                'childs' => 
                array (
                ),
              ),

              array (
                'type' => 'customLink',
                'element_id' => NULL,
                'title' => 'Blog',
                'link' => url('/blog-list'),
                'position' => 4,
                'show' => 0,
                'is_newtab' => 0,
                'theme' => 'edulia',
                'school_id' => 1,
                'created_at' => '2024-01-05T07:38:04.000000Z',
                'updated_at' => '2024-01-05T07:40:14.000000Z',
                'childs' => 
                array (
                ),
              ),
            
              array (
                'type' => 'sPages',
                'element_id' => 10,
                'title' => 'Gallery',
                'link' => '/gallery',
                'position' => 5,
                'show' => 0,
                'is_newtab' => 0,
                'theme' => 'edulia',
                'school_id' => 1,
                'created_at' => '2024-01-05T07:38:17.000000Z',
                'updated_at' => '2024-01-05T07:40:14.000000Z',
                'childs' => 
                array (
                ),
              ),
            
              array (
                'type' => 'sPages',
                'element_id' => 15,
                'title' => 'Result',
                'link' => '/result',
                'position' => 6,
                'show' => 0,
                'is_newtab' => 0,
                'theme' => 'edulia',
                'school_id' => 1,
                'created_at' => '2024-01-05T07:38:30.000000Z',
                'updated_at' => '2024-01-05T07:40:14.000000Z',
                'childs' => 
                array (
                ),
              ),
              
              array (
                'type' => 'sPages',
                'element_id' => 22,
                'title' => 'Contact',
                'link' => '/contact-us',
                'position' => 7,
                'show' => 0,
                'is_newtab' => 0,
                'theme' => 'edulia',
                'school_id' => 1,
                'created_at' => '2024-01-05T07:43:22.000000Z',
                'updated_at' => '2024-01-05T07:43:22.000000Z',
                'childs' => 
                array (
                ),
              ),
              array (
                'type' => 'customLink',
                'element_id' => NULL,
                'title' => 'Others',
                'link' => NULL,
                'position' => 8,
                'show' => 0,
                'is_newtab' => 0,
                'theme' => 'edulia',
                'school_id' => 1,
                'created_at' => '2024-01-05T07:38:39.000000Z',
                'updated_at' => '2024-01-05T07:40:14.000000Z',
                'childs' => 
                array (
                  array (
              
                    'type' => 'customLink',
                    'element_id' => NULL,
                    'title' => 'Student',
                    'link' => NULL,
                    'position' => 1,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:55.000000Z',
                    'updated_at' => '2024-01-05T07:40:21.000000Z',
                    'childs' => 
                    array (
                      array (
                  
                        'type' => 'sPages',
                        'element_id' => 16,
                        'title' => 'Student List',
                        'link' => '/student-lists',
              
                        'position' => 1,
                        'show' => 0,
                        'is_newtab' => 0,
                        'theme' => 'edulia',
                        'school_id' => 1,
                        'created_at' => '2024-01-05T07:39:32.000000Z',
                        'updated_at' => '2024-01-05T07:40:27.000000Z',
                        'childs' => 
                        array (
                        ),
                      ),
                    ),
                  ),
                  array (
              
                    'type' => 'customLink',
                    'element_id' => NULL,
                    'title' => 'Teacher',
                    'link' => NULL,
                    'position' => 2,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:40:03.000000Z',
                    'updated_at' => '2024-01-05T07:40:36.000000Z',
                    'childs' => 
                    array ( 
                      array (
                  
                        'type' => 'sPages',
                        'element_id' => 21,
                        'title' => 'Teacher List',
                        'link' => '/teacher-lists',
              
                        'position' => 1,
                        'show' => 0,
                        'is_newtab' => 0,
                        'theme' => 'edulia',
                        'school_id' => 1,
                        'created_at' => '2024-01-05T07:39:32.000000Z',
                        'updated_at' => '2024-01-05T07:40:45.000000Z',
                        'childs' => 
                        array (
                        ),
                      ),
                    ),
                  ),
                  
                  array (
                    'type' => 'sPages',
                    'element_id' => 3,
                    'title' => 'Academic Calendar',
                    'link' => '/academic-calendars',
                    'position' => 4,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:32.000000Z',
                    'updated_at' => '2024-01-05T07:40:53.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                  array (
                    'type' => 'customLink',
                    'element_id' => NULL,
                    'title' => 'Routine',
                    'link' => NULL,
                    'position' => 5,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:47.000000Z',
                    'updated_at' => '2024-01-05T07:41:27.000000Z',
                    'childs' => 
                    array (
                      array (
                        'type' => 'sPages',
                        'element_id' => 4,
                        'title' => 'Class Routine',
                        'link' => '/class-routines',
              
                        'position' => 1,
                        'show' => 0,
                        'is_newtab' => 0,
                        'theme' => 'edulia',
                        'school_id' => 1,
                        'created_at' => '2024-01-05T07:39:32.000000Z',
                        'updated_at' => '2024-01-05T07:41:30.000000Z',
                        'childs' => 
                        array (
                        ),
                      ),
                      array (
                        'type' => 'sPages',
                        'element_id' => 7,
                        'title' => 'Exam Routine',
                        'link' => '/exam-routine',
              
                        'position' => 2,
                        'show' => 0,
                        'is_newtab' => 0,
                        'theme' => 'edulia',
                        'school_id' => 1,
                        'created_at' => '2024-01-05T07:39:32.000000Z',
                        'updated_at' => '2024-01-05T07:41:35.000000Z',
                        'childs' => 
                        array (
                        ),
                      ),
                    ),
                  ),
                  array (
              
                    'type' => 'sPages',
                    'element_id' => 6,
                    'title' => 'Events',
                    'link' => '/events',
                    'position' => 6,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:32.000000Z',
                    'updated_at' => '2024-01-05T07:41:35.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                  array (
              
                    'type' => 'sPages',
                    'element_id' => 8,
                    'title' => 'Facilities',
                    'link' => '/facilities',
                    'position' => 7,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:32.000000Z',
                    'updated_at' => '2024-01-05T07:41:35.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                  array (
              
                    'type' => 'sPages',
                    'element_id' => 13,
                    'title' => 'Individual Result',
                    'link' => '/individual-result',
                    'position' => 8,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:32.000000Z',
                    'updated_at' => '2024-01-05T07:41:35.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                  array (
              
                    'type' => 'sPages',
                    'element_id' => 14,
                    'title' => 'Noticeboard',
                    'link' => '/noticeboard',
                    'position' => 9,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:32.000000Z',
                    'updated_at' => '2024-01-05T07:41:35.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                  array (
              
                    'type' => 'sPages',
                    'element_id' => 17,
                    'title' => 'Tuition Fees',
                    'link' => '/tuition-fees',
                    'position' => 10,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:32.000000Z',
                    'updated_at' => '2024-01-05T07:41:35.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                  array (
                    'type' => 'sPages',
                    'element_id' => 18,
                    'title' => 'Donor List',
                    'link' => '/donor-list',
                    'position' => 11,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:32.000000Z',
                    'updated_at' => '2024-01-05T07:41:35.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                  array (
                    'type' => 'sPages',
                    'element_id' => 19,
                    'title' => 'Book a Visit',
                    'link' => '/book-a-visit',
                    'position' => 12,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:32.000000Z',
                    'updated_at' => '2024-01-05T07:41:35.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                  array (
                    'type' => 'sPages',
                    'element_id' => 20,
                    'title' => 'Form Download',
                    'link' => '/form-download-list',
                    'position' => 13,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:39:32.000000Z',
                    'updated_at' => '2024-01-05T07:41:35.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                  array (
                    'type' => 'customLink',
                    'element_id' => NULL,
                    'title' => 'Archive',
                    'link' => url('/archive-list'),
                    'position' => 14,
                    'show' => 0,
                    'is_newtab' => 0,
                    'theme' => 'edulia',
                    'school_id' => 1,
                    'created_at' => '2024-01-05T07:38:04.000000Z',
                    'updated_at' => '2024-01-05T07:40:14.000000Z',
                    'childs' => 
                    array (
                    ),
                  ),
                ),
              ),
          );
        foreach($datas as $data){
            insertMenuManage($data);
        }

        // Header Menu manage End
        Artisan::call('storage:link');

        DB::table('sm_notice_boards')->insert([
            [
                'notice_title' => 'Academic Counseling Sessions',
                'notice_message' => "Dear Students,
                                    We hope this message finds you well. Infix Edu is pleased to announce upcoming academic counseling sessions to provide guidance on course selection, career paths, and overall academic planning. Please make sure to attend these sessions to make informed decisions about your academic journey.
                                    Date: 12-12-23
                                    Time: 02.30 PM
                                    Venue: Main Campus Audotorium.
                                    Your participation is crucial, and our team is excited to assist you in achieving your academic and career goals. See you there!
                                    Best Regards,
                                    Alexander J. Harrington
                                    Infix Edu Administration",
                'notice_date' => date("Y-m-d", strtotime('2023-12-12')),
                'publish_on' => date("Y-m-d", strtotime('2023-12-12')),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Scholarship Opportunities',
                'notice_message' => "Dear Students, Infix Edu is delighted to inform you about new scholarship 
                                    opportunities available for deserving students. If you meet the criteria, don't 
                                    miss this chance to apply and alleviate the financial burden of your education.",
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Library Closure for Maintenance',
                'notice_message' => 'Dear Students,
                                    Please be advised that the Infix Edu library will be closed for maintenance on 31-12-23. We apologize for any inconvenience this may cause and appreciate your understanding. Normal library hours will resume on 01-01-24.
                                    Thank you for your cooperation.
                                    Best Regards,
                                    Benjamin L. Fitzgerald
                                    Infix Edu Administration',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => "Extracurricular Activity Registration",
                'notice_message' => "Dear Students,
                                Get ready to explore your interests and enhance your skills! Infix Edu is opening registration for various extracurricular activities, including clubs, sports, and cultural events. Don't miss this chance to enrich your college experience.
                                Registration Period: 15-01-24.
                                Venue: Main Campus.
                                For more details, visit [Website/Office]
                                We look forward to your active participation!
                                Warm Regards,
                                Olivia M. Thornton
                                Infix Edu Administration",
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => "Important Exam Information",
                'notice_message' => 'Dear Students,
                                    As the semester comes to a close, please take note of the following important exam information:
                                    
                                    - Exam Schedule: 01-06-24 and 08 AM.
                                    - Exam Venue: Main Campus
                                    
                                    Make sure to prepare adequately and reach the exam venue on time. Wishing you success in your exams!
                                    Best Regards,
                                    Charles E. Donovan
                                    Infix Edu Administration',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
        ]);

        $frontResultDatas = [
            'Science' => 'public/uploads/front_result/sci.jpg', 
            'Arts' => 'public/uploads/front_result/art.jpg', 
            'Commerce' => 'public/uploads/front_result/comm.png'
        ];

        foreach($frontResultDatas as $key => $frontResultData){
            $data = new FrontResult();
            $data->title = $key;
            $data->publish_date = date('Y-m-d');
            $data->result_file = $frontResultData;
            $data->school_id = 1;
            $data->save();
        }

        Artisan::call('optimize:clear');
    }

    function insertMenuManage($menu){
        $menuData = SmHeaderMenuManager::create($menu);
        if(gv($menu, 'childs')){
            foreach(gv($menu, 'childs') as $child){
                $child['parent_id'] = $menuData->id;
                insertMenuManage($child);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
