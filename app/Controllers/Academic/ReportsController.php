<?php namespace App\Controllers\Academic;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Academic\ClassModel;
use App\Models\Academic\MajorModel;
use App\Models\Academic\CourseModel;
use App\Models\Academic\PlaceModel;
use App\Models\Academic\DateModel;
use App\Models\Academic\TimeModel;
use App\Models\UserModel;
use App\Models\Academic\EnrollementModel;
use App\Models\Academic\AttendanceModel;
use App\Models\Roles\ParentModel;
use App\Models\Academic\LessonModel;
use App\Models\Academic\CompletedLessonModel;
use App\Models\Academic\ReportModel;
use App\Models\Academic\G07\SectionModel;
use App\Models\Academic\G09\SectionNotesModel;

class ReportsController extends ResourceController
{
    protected $format    = 'json';

    // Index
    public function index()
    { 
    }

    // New
    public function new($class_id = null, $outputType = 'array')
    {
        $data['page_title']   = 'إضافة تقرير';
        $data['page_path']    = 'dashboard/class_reports/new';
        $data['scripts_path'] = 'dashboard/class_reports/scripts';

        $data['students'] = getStudents($class_id);
        $data['times'] = getTimes();
        $data['places'] = getPlaces();
        $data['class_id'] = $class_id;

        // Get Class Data
        $classModel = new ClassModel();
        $class_data = $classModel->find($class_id);
        $data['class_data'] = $class_data;

        // Get all course's sections
        $sectionModel = new SectionModel();
        $sections = $sectionModel->where('course_id', $class_data['course_id'])->findAll();
        $data['sections'] = $sections;

        // Get all lessons corrsponding to each section
        $lessonModel = new LessonModel();
        $lessons = array();
        foreach ($sections as $section)
        {
            $lessons[$section['id']] = $lessonModel->where('section_id', $section['id'])->findAll();
        }

        // Get Completed Lessons
        $completedLessonModel = new CompletedLessonModel();
        $completedLessons = $completedLessonModel->where('class_id', $class_id)->findAll();

        // Get Completed Lessons IDs In Array
        $completedLessons_ids = array();
        foreach ($completedLessons as $compl_lesson)
        {
            $completedLessons_ids[] = $compl_lesson['lesson_id'];
        }

        $lessons_temp = array();
        foreach ($lessons as $section_id => $section_lessons)
        {
            $lessons_temp[$section_id] = array();
            foreach ($section_lessons as $lesson)
            {
                if (in_array($lesson['id'], $completedLessons_ids))
                {
                    $lesson['status'] = 1;
                }
                else
                {
                    $lesson['status'] = 0;
                }
                array_push($lessons_temp[$section_id], $lesson);
            }
        }
        $data['lessons'] = $lessons_temp;

        // Return JSON String For API Usage
        if ($outputType == 'json')
        return json_encode($data, JSON_UNESCAPED_UNICODE);

        // Return View
        return view('backend/index', $data);
    }

    // Create
    public function create($class_id = null)
    {
        // Check If Correct Form Is Submitted
        if (!isset($_POST['create_report']))
            return json_encode(["error" => "No Form Was Submitted."]);

        // Save Class Report
        $class_date = date('Y-m-d', strtotime($this->request->getVar("class_date")));
        $class_time = $this->request->getVar("class_time");
        $did_class_hold = $this->request->getVar("did_class_hold_button");

        if ($did_class_hold)
        {
            $valid = $this->validate([
                'class_date' => 'required|trim',
                'class_time' => 'required|trim',
                'class_not_hold_reason' =>'required|trim',
            ]);
        }
        else
        {
            $valid = $this->validate([
                'class_date' => 'required|trim',
                'class_time' => 'required|trim',
            ]);
        }
    
        if (!$valid)
        {
            $validation = $this->validator;
            $message = array(
                'error' => $validation->listErrors()
            ); 
            session()->setFlashdata($message);
            return redirect()->to(base_url('Academic/ReportsController/new'));
        }
        else
        {
            $report_data = [
                'class_id'        => $class_id,
                "class_time"      => $class_time,
                "class_date"      => $class_date,
                'class_place'     => $this->request->getVar("class_place"),
                'notes'           => $this->request->getVar("notes"),
                'did_not_hold'    => $did_class_hold,
                'not_hold_reason' => $this->request->getVar("class_not_hold_reason"),
            ];
            $reportModel = new ReportModel();
            $reportModel->save($report_data);
    
            // Get Last Inserted Report ID
            $db = \Config\Database::connect();
            $db_ar = (array)$db;
            $report_id = $db_ar['mysqli']->insert_id;
    
    
            // Save Some Data If Class Was Hold Only
            if (!$did_class_hold)
            {
                // Save Attendance
                $students_id = $this->request->getVar("students_id");
                foreach ($students_id as $student_id) {
                    $status = 'attendance_status_'.$student_id;
        
                    $data = [
                        "class_id"   => $class_id,
                        "user_id"    => $student_id,
                        "report_id" => $report_id,
                        "attendance" => $this->request->getVar($status)
                    ];
                    
                    $attendanceModel = new AttendanceModel();
                    $attendanceModel->save($data);
                }
                
                // Save Completed Lessons
                $completedLessonModel = new CompletedLessonModel();
                if (isset($_POST['lessons']))
                {
                    foreach ($_POST['lessons'] as $compl_lesson)
                    {
                        $data = [
                            'class_id'  => $class_id,
                            'lesson_id' => $compl_lesson,
                            'report_id' => $report_id,
                        ];
                        $completedLessonModel->save($data);
                    }
                }
        
                // Save Sections Notes
                $sectionNotesModel = new SectionNotesModel();
                if (isset($_POST['sections_id']))
                {
                    foreach ($_POST['sections_id'] as $section_id)
                    {
                        if ($this->request->getVar("section_notes_".$section_id) != '')
                        {
                            $data = [
                                'class_id'   => $class_id,
                                'report_id'  => $report_id,
                                'section_id' => $section_id,
                                'notes'      =>$this->request->getVar("section_notes_".$section_id),
                            ];
                            $sectionNotesModel->save($data);
                        }
                    }
                }
            }

            $message = array(
                'success' => 'لقد تمت العملية بنجاح'
            ); 
            session()->setFlashdata($message);
        }

        return redirect()->to(base_url('Academic/ReportsController/show').'/'.$report_id);
    }

    // Update
    public function update($id = null)
    {
    }

    // Show
    public function show($id = null)
    {
        $reportModel = new ReportModel();
        $report_data = $reportModel->find($id);
        // Time
        $times = getTimes();
        $report_data['time_name'] = '';
        foreach ($times as $time)
        {
            if (('time_id_'.$time['id']) == $report_data['class_time'])
            {
                $report_data['time_name'] = $time['name'];
                break;
            }
        }
        if ($report_data['time_name'] == '')
        {
            $report_data['time_name'] = $report_data['class_time'];
        }
        // Place
        $classPlaceModel = new PlaceModel();
        $place = $classPlaceModel->find($report_data['class_place']);
        $report_data['place_name'] = $place['name'];

        $data['report_data'] = $report_data;

        $class_id = $report_data['class_id'];

        // Get Class Data
        $classModel = new ClassModel();
        $data['class_data'] = $classModel->find($class_id);

        $data['students'] = getStudents($class_id, 'all');

        if (!is_instructor_of($class_id) && !isRole($this->session->get('id'), 'admin'))
        {
            echo 'ليس لديك الإذن لإتمام هذه العملية'; exit();
        }
    
        // Get Attendance Data
        $attendanceModel = new AttendanceModel();

        $data['attendance_data'] = array();
        foreach ($data['students'] as $student) {
            $present_count = $attendanceModel->where('class_id', $class_id)
                                             ->where('report_id', $id)
                                             ->where('user_id', $student['user_id'])
                                             ->where('attendance', 'present')
                                             ->findAll();
            $late_count = $attendanceModel->where('class_id', $class_id)
                                          ->where('report_id', $id)
                                          ->where('user_id', $student['user_id'])
                                          ->where('attendance', 'late')
                                          ->findAll();
            $absent_count = $attendanceModel->where('class_id', $class_id)
                                            ->where('report_id', $id)
                                            ->where('user_id', $student['user_id'])
                                            ->where('attendance', 'absent')
                                            ->findAll();
            $att_data = array(
                'present' => count($present_count),
                'late'    => count($late_count),
                'absent'  => count($absent_count));

            $data['attendance_data'][$student['user_id']] = $att_data;
        }


        // Get Class Data
        $classModel = new ClassModel();
        $class_data = $classModel->find($class_id);

        // Lessons Data
        $lessons_data = $this->get_class_lessons_data($class_id, $id);
        $data['sections'] = $lessons_data['sections'];
        $data['sections_notes'] = $lessons_data['sections_notes'];
        $data['lessons'] = $lessons_data['lessons'];


        // Get Instructor Data
        $userModel = new UserModel();
        $data['instructor'] = $userModel->find($data['class_data']['instructor_id']);

        $data['page_title']   = 'تقرير الصف';
        $data['page_path']    = 'dashboard/class_reports/show';
        $data['scripts_path']    = 'dashboard/class_reports/scripts';


        return view('backend/index', $data);
    }

    // Show All Reports Summary
    public function show_summary($class_id = null, $student_id = null)
    {
        $data['students'] = getStudents($class_id, 'all');

        // Check if has pemission
        if ($student_id > 0)
        {
            if (!$this->session->get('id') == $student_id && !is_parent_of($student_id) && !isRole($this->session->get('id'), 'admin'))
            {
                echo 'ليس لديك الإذن لإتمام هذه العملية'; exit();
            }
        
            // Check if student was/is endrolled in this course// قيها شي غلط أكيد
            if (!in_array($student_id, $data['students']))
            {
                echo 'الطالب غير مسجل في هذا الصف'; exit();
            }
            else
            {
                $data['students'] = [$student_id];
            }
        }
        elseif ($student_id == null)
        {
            if (!is_instructor_of($class_id) && !isRole($this->session->get('id'), 'admin'))
            {
                echo 'ليس لديك الإذن لإتمام هذه العملية'; exit();
            }
        }

        // Get Reports Data
        $reportModel = new ReportModel();
        $reports_data = $reportModel->where('class_id', $class_id)->findAll();
        $data['reports_data'] = $reports_data;

    
        // Get Attendance Data
        $attendanceModel = new AttendanceModel();

        $data['attendance_data'] = array();
        foreach ($data['students'] as $student) {
            $present_count = $attendanceModel->where('class_id', $class_id)
                                       ->where('user_id', $student['user_id'])
                                       ->where('attendance', 'present')
                                       ->findAll();
            $late_count = $attendanceModel->where('class_id', $class_id)
                                       ->where('user_id', $student['user_id'])
                                       ->where('attendance', 'late')
                                       ->findAll();
            $absent_count = $attendanceModel->where('class_id', $class_id)
                                       ->where('user_id', $student['user_id'])
                                       ->where('attendance', 'absent')
                                       ->findAll();

            // Check if he was absent the last 3 classes
            $is_dropout = false;
            if (count($reports_data) >= 3)
            {
                $reports_data_reversed = array_reverse($reports_data);
                for ($i = 0; $i < 3; $i++)
                {
                    $is_absent = false;
                    foreach ($absent_count as $abs)
                    {
                        if ($abs['report_id'] == $reports_data_reversed[$i]['id'])
                        {
                            $is_absent = true;
                            break;
                        }
                    }

                    if ($is_absent)
                    {
                        $is_dropout = true;
                    }
                    else // He is not absent is one of the last 3 classes
                    {
                        $is_dropout = false;
                        break;
                    }
                }
            }

            $att_data = array(
                'present'    => count($present_count),
                'late'       => count($late_count),
                'absent'     => count($absent_count),
                'is_dropout' => $is_dropout
            );

            $data['attendance_data'][$student['user_id']] = $att_data;
        }


        // Get Class Data
        $classModel = new ClassModel();
        $class_data = $classModel->find($class_id);

        // Lessons Data
        $lessons_data = $this->get_class_lessons_data($class_id);
        $data['sections'] = $lessons_data['sections'];
        $data['sections_notes'] = $lessons_data['sections_notes'];
        $data['lessons'] = $lessons_data['lessons'];

        // Class Data
        $data['class_data'] = $classModel->find($class_id);

        // Get Instructor Data
        $userModel = new UserModel();
        $data['instructor'] = $userModel->find($data['class_data']['instructor_id']);

        $data['times'] = getTimes();
        $data['dates'] = getDates();
        $data['places'] = getPlaces();
        // $data['users']    = getUsers();
        $data['class_id'] = $class_id;
        $data['page_title']   = 'تقرير الصف';
        $data['page_path']    = 'dashboard/class_reports/show_summary';
        $data['scripts_path'] = 'dashboard/class_reports/scripts';


        return view('backend/index', $data);
    }

    // Delete
    public function delete($id = null)
    {
    }

    // Students Class Report
    public function student_class_report($student_id, $class_id = null)
    {
        // Check if has pemission
        if (!$this->session->get('id') == $student_id && !is_parent($student_id) && !isRole($this->session->get('id'), 'admin'))
        {
            echo 'ليس لديك الإذن لإتمام هذه العملية'; exit();
        }

        // Check if student was/is endrolled in this course
        

        $data['page_title'] = '';
        $data['page_path'] = 'dashboard/reports/student_class_report';
        $data['modals_path'] = '';
        $data['scripts_path'] = '';

        $data['class_id'] = $class_id;

        return view('backend/index', $data);
    }

    // DataTable
    public function dataTable($class_id = null)
    {
        // Datatables Variables
        $draw = intval($this->request->getVar("draw"));
        $start = intval($this->request->getVar("start"));
        $length = intval($this->request->getVar("length"));


        $reportModel = new ReportModel();
        $reports = $reportModel->where('class_id', $class_id)->orderBy('id', 'DESC')->findAll();

        $places = getPlaces();
        $times = getTimes();

        $data = array();

        foreach($reports as $r)
        {
            // Place
            /*$r['place_name'] = '';
            foreach ($places as $place)
            {
                if ($place['id'] == $r['place_id'])
                {
                    $r['place_name'] = $place['name'];
                    break;
                }
            }*/

            // Time
            $r['time_name'] = '';
            foreach ($times as $time)
            {
                if (('time_id_'.$time['id']) == $r['class_time'])
                {
                    $r['time_name'] = $time['name'];
                    break;
                }
            }
            if ($r['time_name'] == '')
            {
                $r['time_name'] = $r['class_time'];
            }




            // Actions
            $actions = '';
            // Show Report
            $actions .= '<a href="' .base_url("Academic/ReportsController/show/".$r["id"]) .'" class="btn btn-info btn-sm btn-just-icon"><i class="material-icons">visibility</i></a>';

            $data[] = array(
                '', // Just for the table index
                $r['created_at'],
                $r['class_date'],
                $r['time_name'],
                'المكان',
                $actions
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($reports),
            "recordsFiltered" => count($reports),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // Export To Excel
    function export_to_excel()
    {

        // Check if has pemission
        /*if ($student_id > 0)
        {
            if (!$this->session->get('id') == $student_id && !is_parent_of($student_id) && !isRole($this->session->get('id'), 'admin'))
            {
                echo 'ليس لديك الإذن لإتمام هذه العملية'; exit();
            }
        
            // Check if student was/is endrolled in this course// قيها شي غلط أكيد
            if (!in_array($student_id, $data['students']))
            {
                echo 'الطالب غير مسجل في هذا الصف'; exit();
            }
            else
            {
                $data['students'] = [$student_id];
            }
        }
        elseif ($student_id == null)
        {
            if (!is_instructor_of($class_id) && !isRole($this->session->get('id'), 'admin'))
            {
                echo 'ليس لديك الإذن لإتمام هذه العملية'; exit();
            }
        }*/


        $ids = $this->request->getVar("id");
        $id_array = explode(',', $ids);

        $from_date = $this->request->getVar("from_date");
        $to_date = $this->request->getVar("to_date");
        
        $dataToExport = array();
        $dataToExport['tables'] = '';

        // Excel Table Styles (xml)
        $dataToExport['styles'] = '<Style ss:ID="Header1_1">' // Heading 1 - 1
                                .   '<Interior ss:Color="#0ac49f" ss:Pattern="Solid"/>'
                                . '</Style>'
                                . '<Style ss:ID="Header2_1">' // Heading 2 - 1
                                .   '<Interior ss:Color="#ffe277" ss:Pattern="Solid"/>'
                                . '</Style>'
                                . '<Style ss:ID="Header3_1">' // Heading 3 - 1
                                .   '<Interior ss:Color="#85C1E9" ss:Pattern="Solid"/>'
                                . '</Style>'
                                . '<Style ss:ID="Header4_1">' // Heading 4 - 1
                                .   '<Interior ss:Color="#E5E7E9" ss:Pattern="Solid"/>'
                                . '</Style>'
                                . '<Style ss:ID="p_1">' // Normal Text (Paragraph) - 1
                                .   '<Interior ss:Color="#0ac49f" ss:Pattern="Solid"/>'
                                . '</Style>'
                                . '<Style ss:ID="completed_lesson">'
                                .   '<Interior ss:Color="#A9DFBF" ss:Pattern="Solid"/>'
                                . '</Style>'
                                . '<Style ss:ID="incompleted_lesson">'
                                .   '<Interior ss:Color="#F5B7B1" ss:Pattern="Solid"/>'
                                . '</Style>';
                                
        $wsnames = array(); // WorSheets Names
        foreach ($id_array as $class_id)
        {
            // Get Reports Data
            $reportModel = new ReportModel();
            $reportModel->where('class_id', $class_id);
            if ($from_date != '')
            {
                $reportModel->where('class_date >=', $from_date);
            }
            if ($to_date != '')
            {
                $reportModel->where('class_date <=', $to_date);
            }
            $reports_data = $reportModel->findAll();

            // Get Report IDs
            $reports_ids = array();
            $reports_ids[] = 0; // Used if no report match the time filter, so we do not get an error in "WhereIn" function
            foreach ($reports_data as $report)
            {
                $reports_ids[] = $report['id'];
            }
            // print_r($reports_ids);

            // Get Class Students
            $students = getStudents($class_id, 'all');

            // Get Attendance Data
            $attendanceModel = new AttendanceModel();
            $attendance_data = array();
            foreach ($students as $student) {
                $present_count = $attendanceModel->where('class_id', $class_id)
                                                 ->whereIn('report_id', $reports_ids)
                                                 ->where('user_id', $student['user_id'])
                                                 ->where('attendance', 'present')
                                                 ->findAll();
                $late_count = $attendanceModel->where('class_id', $class_id)
                                              ->where('user_id', $student['user_id'])
                                              ->whereIn('report_id', $reports_ids)
                                              ->where('attendance', 'late')
                                              ->findAll();
                $absent_count = $attendanceModel->where('class_id', $class_id)
                                                ->where('user_id', $student['user_id'])
                                                ->whereIn('report_id', $reports_ids)
                                                ->where('attendance', 'absent')
                                                ->findAll();
                $att_data = array(
                    'present' => count($present_count),
                    'late'    => count($late_count),
                    'absent'  => count($absent_count));

                $attendance_data[$student['user_id']] = $att_data;
            }

            // Get Class Data
            /*$classModel = new ClassModel();
            $class_data = $classModel->find($class_id);*/

            // Get all course's sections
            /*$sectionModel = new SectionModel();

            $sections = $sectionModel->where('course_id', $class_data['course_id'])->findAll();
            $data['sections'] = $sections;*/

            // Get all lessons corrsponding to each section & Sections Notes
            /*$lessonModel = new LessonModel();
            $sectionNotesModel = new SectionNotesModel();
            $lessons = array();
            $sections_notes = array();
            foreach ($sections as $section)
            {
                // Get Sections Lessons
                $lessons[$section['id']] = $lessonModel->where('section_id', $section['id'])->findAll();

                // Get Sections Notes
                $sections_notes[$section['id']] = $sectionNotesModel->where('class_id', $class_id)->where('section_id', $section['id'])->whereIn('report_id', $reports_ids)->findAll();
            }
            $data['sections_notes'] = $sections_notes;


            // Get Completed Lessons
            $completedLessonModel = new CompletedLessonModel();
            $completedLessons = $completedLessonModel->where('class_id', $class_id)->whereIn('report_id', $reports_ids)->findAll();

            // Get Completed Lessons IDs In Array
            $completedLessons_ids = array();
            foreach ($completedLessons as $compl_lesson)
            {
                $completedLessons_ids[] = $compl_lesson['lesson_id'];
            }

            // Set Lessons Status (Completed or Not)
            $data['lessons'] = array();
            foreach ($sections as $section)
            {
                foreach ($lessons[$section['id']] as $lesson)
                {
                    if (in_array($lesson['id'], $completedLessons_ids))
                    {
                        $lesson['status'] = 1;
                    }
                    else
                    {
                        $lesson['status'] = 0;
                    }

                    if (!array_key_exists($section['id'], $data['lessons']))
                    {
                        $data['lessons'][$section['id']] = array();
                    }
                    array_push($data['lessons'][$section['id']], $lesson);
                }
            }*/

            // Class Data
            $classModel = new ClassModel();
            $class_data = $classModel->find($class_id);

            // Get Instructor Data
            $userModel = new UserModel();
            $instructor = $userModel->find($class_data['instructor_id']);

            // Get Class Time
            $data['times'] = getTimes();
            $class_data['time_name'] = '';
            foreach ($data['times'] as $time)
            {
                if (('time_id_'.$time['id']) == $class_data['time'])
                {
                    $class_data['time_name'] = $time['name'];
                    break;
                }
            }
            if ($class_data['time_name'] == '')
            {
                $class_data['time_name'] = $class_data['time'];
            }

            // Get Class Date
            $data['dates'] = getDates();
            $class_data['date_name'] = '';
            foreach ($data['dates'] as $date)
            {
                if (($date['id']) == $class_data['date_id'])
                {
                    $class_data['date_name'] = $date['name'];
                    break;
                }
            }

            // Get Class Place
            $data['places'] = getPlaces();
            $class_data['place_name'] = '';
            foreach ($data['places'] as $place)
            {
                if (($place['id']) == $class_data['place_id'])
                {
                    $class_data['place_name'] = $place['name'];
                    break;
                }
            }


            // $data['users']    = getUsers();
            $data['class_id'] = $class_id;
            $data['page_title']   = 'تقرير الصف';
            $data['page_path']    = 'dashboard/class_reports/show_summary';
            $data['scripts_path'] = 'dashboard/class_reports/scripts';


            $output = '';
            $output .= "<table id='tbl1' class='selected-classes-reports'>";
            if ($from_date != "" || $to_date != "")
            {
                $output .=   "<tr>";
                $output .=     "<th data-style='Header1_1'>من</th>";
                $output .=     "<th>" .$from_date ."</th>";
                $output .=     "<th data-style='Header1_1'>إلى</th>";
                $output .=     "<th>" .$to_date ."</th>";
                $output .=     "<th></th>";
                $output .=     "<th></th>";
                $output .=   "</tr>";
                $output .=   "<tr>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=   "</tr>";
            }

            $output .=   "<tr>";
            $output .=     "<th data-style='Header1_1'>اسم الحلقة</th>";
            $output .=     "<th data-style='Header1_1'>العريف</th>";
            $output .=     "<th data-style='Header1_1'>المكان</th>";
            $output .=     "<th data-style='Header1_1'>الزمان</th>";
            $output .=     "<th></th>";
            $output .=     "<th></th>";
            $output .=   "</tr>";
            $output .=   "<tr>";
            $output .=     "<td>".(isset($class_data['name'])?$class_data['name']:'')."</td>";
            $output .=     "<td>".(isset($instructor['first_name'])?$instructor['first_name']:'') ." " .(isset($instructor['last_name'])?$instructor['last_name']:'')."</td>";
            $output .=     "<td>".(isset($class_data['place_name'])?$class_data['place_name']:'')."</td>";
            $output .=     "<td>".(isset($class_data['date_name'])?$class_data['date_name']:'')." - ".(isset($class_data['time_name'])?$class_data['time_name']:'')."</td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=   "</tr>";
            
            // Some Space Between Table Sections
            $output .=   "<tr>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=   "</tr>";
            $output .=   "<tr>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=   "</tr>";

            $output .=   "<tr>";
            $output .=     "<th data-style='Header1_1'>الرمز</th>";
            $output .=     "<th data-style='Header1_1'>الإسم</th>";
            $output .=     "<th data-style='Header1_1'>نسبة الحضور</th>";
            $output .=     "<th data-style='Header1_1'>عدد مرات الحضور</th>";
            $output .=     "<th data-style='Header1_1'>عدد مرات التأخر</th>";
            $output .=     "<th data-style='Header1_1'>عدد مرات الغياب</th>";
            $output .=   "</tr>";
            foreach ($students as $student)
            {
                $output .=   "<tr>";
                $output .=     "<td>" ./*$student['data']['id_code'] .*/"</td>";
                $output .=     "<td>" .$student['data']['first_name'] ." " .$student['data']['last_name'] ."</td>";
                $output .=     "<td>".($attendance_data[$student['user_id']]['present'] + $attendance_data[$student['user_id']]['late'] + $attendance_data[$student['user_id']]['absent']) . "/" . ($attendance_data[$student['user_id']]['present'] + $attendance_data[$student['user_id']]['late'])."</td>";
                $output .=     "<td>".$attendance_data[$student['user_id']]['present']."</td>";
                $output .=     "<td>".$attendance_data[$student['user_id']]['late']."</td>";
                $output .=     "<td>".$attendance_data[$student['user_id']]['absent']."</td>";
                $output .=   "</tr>";
            }
            
            // Some Space Between Table Sections
            $output .=   "<tr>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=   "</tr>";
            $output .=   "<tr>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=   "</tr>";

            // Lessons Data
            $lessons_data = $this->get_class_lessons_data($class_id, $reports_ids);
            $sections = $lessons_data['sections'];
            $sections_notes = $lessons_data['sections_notes'];
            $lessons = $lessons_data['lessons'];

            $output .=   "<tr>";
            $output .=     "<th data-style='Header1_1' mergeAcross = '3'>المقررات</th>";
            $output .=     "<th></th>";
            $output .=     "<th></th>";
            $output .=     "<th></th>";
            $output .=     "<th></th>";
            $output .=     "<th></th>";
            $output .=   "</tr>";
            foreach ($sections as $section)
            {
                $output .=   "<tr>";
                $output .=     "<td data-style='Header2_1' mergeAcross = '3'>".$section['name']."</td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=   "</tr>";
                // Lessons
                if (!empty($lessons))
                {
                    $output .=   "<tr>";
                    $output .=     "<td data-style='Header4_1' mergeAcross = '1'>الدرس</td>";
                    $output .=     "<td data-style='Header4_1' mergeAcross = '1'>الحالة</td>";
                    $output .=     "<td></td>";
                    $output .=     "<td></td>";
                    $output .=     "<td></td>";
                    $output .=     "<td></td>";
                    $output .=   "</tr>";
                    if (isset($lessons[$section['id']]))
                    {
                        foreach ($lessons[$section['id']] as $lesson)
                        {
                            if (count($reports_ids) > 1 && $lesson['status']) // If dat filter is applied, just display the completed lessons
                            {
                                $output .=   "<tr>";
                                $output .=     "<td mergeAcross = '1'>".$lesson['name']."</td>";
                                $output .=     "<td data-style='".(($lesson['status']) ? 'completed_lesson' : 'incompleted_lesson')."' mergeAcross = '1'>".(($lesson['status']) ? 'مكتمل' : 'غير مكتمل')."</td>";
                                $output .=     "<td></td>";
                                $output .=     "<td></td>";
                                $output .=     "<td></td>";
                                $output .=     "<td></td>";
                                $output .=   "</tr>";
                            }
                        }
                    }
                }
                // Notes
                $output .=   "<tr>";
                $output .=     "<td data-style='Header4_1' mergeAcross = '3'>الملاحظات</td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=   "</tr>";
                if (!empty($sections_notes[$section['id']]))
                {
                    foreach ($sections_notes[$section['id']] as $section)
                    {
                        $output .=   "<tr>";
                        $output .=     "<td mergeAcross = '3'>".$section['notes']."</td>";
                        $output .=     "<td></td>";
                        $output .=     "<td></td>";
                        $output .=     "<td></td>";
                        $output .=     "<td></td>";
                        $output .=     "<td></td>";
                        $output .=   "</tr>";
                    }
                }
            }

            // Some Space Between Table Sections
            $output .=   "<tr>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=   "</tr>";
            $output .=   "<tr>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=     "<td></td>";
            $output .=   "</tr>";

            // Reports' Notes
                $output .=   "<tr>";
                $output .=     "<td data-style='Header1_1' mergeAcross = '3'>ملاحظات أخرى</td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=     "<td></td>";
                $output .=   "</tr>";
            foreach ($reports_data as $report)
            {
                if ($report['notes'] != '')
                {
                    $output .=   "<tr>";
                    $output .=     "<td mergeAcross = '3'>".$report['notes']."</td>";
                    $output .=     "<td></td>";
                    $output .=     "<td></td>";
                    $output .=     "<td></td>";
                    $output .=     "<td></td>";
                    $output .=     "<td></td>";
                    $output .=   "</tr>";
                }
            }

            $output .= "</table>";




            $dataToExport['tables'] .= $output;
            $wsnames[] = ($class_data['name'] != '')?$class_data['name']:$class_data['id'];
        }

        echo json_encode(['styles' => $dataToExport['styles'], 'tables' => $dataToExport['tables'], 'wsnames' => $wsnames]);
    }

    // Get Classes Lessons Status And Data (Notes For Example)
    function get_class_lessons_data($class_id = null, $reports_ids = null)
    {
        if (!is_array($reports_ids))
            $reports_ids = array($reports_ids);

        // Get Class Data
        $classModel = new ClassModel();
        $class_data = $classModel->find($class_id);

        // Get all course's sections
        $sectionModel = new SectionModel();
        $sections = $sectionModel->where('course_id', $class_data['course_id'])->findAll();
        $data['sections'] = $sections;

        // Get all lessons corrsponding to each section & Sections Notes
        $lessonModel = new LessonModel();
        $sectionNotesModel = new SectionNotesModel();
        $lessons = array();
        $sections_notes = array();
        foreach ($sections as $section)
        {
            // Get Sections Lessons
            $lessons[$section['id']] = $lessonModel->where('section_id', $section['id'])->findAll();

            // Get Sections Notes
            $sections_notes[$section['id']] = $sectionNotesModel->where('class_id', $class_id)->where('section_id', $section['id'])->whereIn('report_id', $reports_ids)->findAll();
        }
        $data['sections_notes'] = $sections_notes;

        // Get Completed Lessons
        $completedLessonModel = new CompletedLessonModel();
        $completedLessons = $completedLessonModel->where('class_id', $class_id)->whereIn('report_id', $reports_ids)->findAll();

        // Get Completed Lessons IDs In Array
        $completedLessons_ids = array();
        foreach ($completedLessons as $compl_lesson)
        {
            $completedLessons_ids[] = $compl_lesson['lesson_id'];
        }

        // Set Lessons Status (Completed or Not)
        $data['lessons'] = array();
        foreach ($sections as $section)
        {
            foreach ($lessons[$section['id']] as $lesson)
            {
                if (in_array($lesson['id'], $completedLessons_ids))
                {
                    $lesson['status'] = 1;
                }
                else
                {
                    $lesson['status'] = 0;
                }

                if (!array_key_exists($section['id'], $data['lessons']))
                {
                    $data['lessons'][$section['id']] = array();
                }
                array_push($data['lessons'][$section['id']], $lesson);
            }
        }

        return $data;
    }

    // Get Absence student during specific time range
    function get_dropouts_students()
    {
        $ids = $this->request->getVar("id");
        $id_array = explode(',', $ids);

        $from_date          = $this->request->getVar("from_date");
        $to_date            = $this->request->getVar("to_date");
        $absence_count_type = $this->request->getVar("absence_count_type");
        $absence_value      = $this->request->getVar("absence_value");
        $absence_type       = $this->request->getVar("absence_type");
        
        $classModel = new ClassModel();
        $classes = $classModel->findAll();
        $data = array();
        foreach ($classes as $class)
        {
            $class_id = $class['id'];
            // Get Reports Data
            $reportModel = new ReportModel();
            $reportModel->where('class_id', $class_id);
            if ($from_date != '')
            {
                $reportModel->where('class_date >=', $from_date);
            }
            if ($to_date != '')
            {
                $reportModel->where('class_date <=', $to_date);
            }
            $reports_data = $reportModel->findAll();

            // Get Report IDs
            $reports_ids = array();
            $reports_ids[] = 0; // Used if no report match the time filter, so we do not get an error in "WhereIn" function
            foreach ($reports_data as $report)
            {
                $reports_ids[] = $report['id'];
            }
            // print_r($reports_ids);

            // Get Class Students
            $students = getStudents($class_id, 'all');

            // Get Attendance Data
            $attendanceModel = new AttendanceModel();
            $dropouts = array();
            foreach ($students as $student) {
                $present_count = $attendanceModel->where('class_id', $class_id)
                                                 ->whereIn('report_id', $reports_ids)
                                                 ->where('user_id', $student['user_id'])
                                                 ->where('attendance', 'present')
                                                 ->findAll();
                $late_count = $attendanceModel->where('class_id', $class_id)
                                              ->where('user_id', $student['user_id'])
                                              ->whereIn('report_id', $reports_ids)
                                              ->where('attendance', 'late')
                                              ->findAll();
                $absent_count = $attendanceModel->where('class_id', $class_id)
                                                ->where('user_id', $student['user_id'])
                                                ->whereIn('report_id', $reports_ids)
                                                ->where('attendance', 'absent')
                                                ->findAll();
                $att_data = array(
                    'present' => count($present_count),
                    'late'    => count($late_count),
                    'absent'  => count($absent_count));

                $att_count = count($present_count) + count($late_count) + count($absent_count);
                if ($att_count > 0)
                {
                    if ($absence_count_type == 'number')
                    {
                        if (count($absent_count) >= $absence_value)
                        {
                            $dropouts[$student['user_id']]['student_data']    = $student;
                            $dropouts[$student['user_id']]['attendance_data'] = $att_data;
                        }
                    }
                    elseif ($absence_count_type == 'percentage')
                    {
                        if ((count($absent_count) * 100 / $att_count) >= $absence_value)
                        {
                            $dropouts[$student['user_id']]['student_data']    = $student;
                            $dropouts[$student['user_id']]['attendance_data'] = $att_data;
                        }
                    }
                }
            }

            // Class Data
            $classModel = new ClassModel();
            $class_data = $classModel->find($class_id);

            // Get Instructor Data
            $userModel = new UserModel();
            $instructor = $userModel->find($class_data['instructor_id']);

            // Get Class Time
            $times = getTimes();
            $class_data['time_name'] = '';
            foreach ($times as $time)
            {
                if (('time_id_'.$time['id']) == $class_data['time'])
                {
                    $class_data['time_name'] = $time['name'];
                    break;
                }
            }
            if ($class_data['time_name'] == '')
            {
                $class_data['time_name'] = $class_data['time'];
            }

            // Get Class Date
            $dates = getDates();
            $class_data['date_name'] = '';
            foreach ($dates as $date)
            {
                if (($date['id']) == $class_data['date_id'])
                {
                    $class_data['date_name'] = $date['name'];
                    break;
                }
            }

            // Get Class Place
            $places = getPlaces();
            $class_data['place_name'] = '';
            foreach ($places as $place)
            {
                if (($place['id']) == $class_data['place_id'])
                {
                    $class_data['place_name'] = $place['name'];
                    break;
                }
            }


            // $data['users']     = getUsers();


            $data['classes'][$class_id] = array(
                'class_data' => $class_data,
                'instructor' => $instructor,
                'dropouts'   => $dropouts,

            );
        }

        $data['page_title']   = 'المتسربين';
        $data['page_path']    = 'dashboard/classes_dropouts/index';
        $data['scripts_path'] = 'dashboard/classes_dropouts/scripts';

        //return json_encode($data, JSON_UNESCAPED_UNICODE);
        return view('backend/index', $data);
    }
}