<?php namespace App\Controllers\API\Academic;

use App\Controllers\BaseController;
use App\Models\Academic\ReportModel;
use App\Models\UserModel;
use App\Models\Academic\AttendanceModel;
use App\Models\Academic\ClassModel;
use App\Models\Academic\PlaceModel;
use App\Models\Academic\DateModel;
use App\Models\Academic\TimeModel;
use App\Models\Academic\G07\SectionModel;
use App\Models\Academic\G09\SectionNotesModel;
use App\Models\Academic\LessonModel;
use App\Models\Academic\CompletedLessonModel;

class API_ReportsController extends BaseController
{
    // Create
    public function create($class_id = null)
    {
        // Check If Correct Form Is Submitted
        if (!isset($_POST['create_report']))
            return json_encode(["error" => "No Form Was Submitted."]);


        // Save Class Report
        $class_date = date('Y-m-d', strtotime($this->request->getVar("class_date")));
        $class_time = $this->request->getVar("class_time");
        $did_class_hold = intval($this->request->getVar("did_class_hold_button")); // "intval" is used to transfer "bool" to "integer" (true = 1 ; false = 0)


        // Form Input Validation
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
        if (!$valid) return json_encode($this->validator->getErrors());

        $report_data = [
            'class_id'        => $class_id,
            "class_time"      => $class_time,
            "class_date"      => $class_date,
            "class_place"     => $this->request->getVar("class_place"),
            'notes'           => $this->request->getVar("notes"),
            'did_not_hold'    => $did_class_hold,
            'not_hold_reason' => ($this->request->getVar("class_not_hold_reason")) ? $this->request->getVar("class_not_hold_reason") : "",
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
            $students_id = json_decode($this->request->getVar("students_id"));
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
                foreach (json_decode($_POST['lessons']) as $compl_lesson)
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
                foreach (json_decode($_POST['sections_id']) as $section_id)
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

        return json_encode(['success' => 'لقد تمت العملية بنجاح'], JSON_UNESCAPED_UNICODE);
    }
    
    // Show API
    public function show($id = null)
    {
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

        $reportModel = new ReportModel();
        $report_data = $reportModel->find($id);
        $data['report_data'] = $report_data;

        $class_id = $report_data['class_id'];

        $data['students'] = getStudents($class_id, 'all');

        if (!isRole($user_id, 'admin'))
        {
            if (!is_instructor_of($class_id, $user_id))
                return json_encode(['error' => 'You do not have permission for this action.']);
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
        $data['class_data'] = $class_data;

        // Lessons Data
        $lessons_data = $this->get_class_lessons_data($class_id, $id);
        $data['sections'] = $lessons_data['sections'];
        $data['sections_notes'] = $lessons_data['sections_notes'];
        $data['lessons'] = $lessons_data['lessons'];


        // Time
        $times = getTimes();
        $data['class_data']['time_name'] = '';
        foreach ($times as $time)
        {
            if (('time_id_'.$time['id']) == $data['class_data']['time'])
            {
                $data['class_data']['time_name'] = $time['name'];
                break;
            }
        }
        if ($data['class_data']['time_name'] == '')
        {
            $data['class_data']['time_name'] = $data['class_data']['time'];
        }

        // Date
        $dates = getDates();
        $data['class_data']['date_name'] = '';
        foreach ($dates as $date)
        {
            if ($date['id'] == $data['class_data']['date_id'])
            {
                $data['class_data']['date_name'] = $date['name'];
            }
        }

        // Place
        $places = getPlaces();
        $data['class_data']['place_name'] = '';
        foreach ($places as $place)
        {
            if ($place['id'] == $data['class_data']['place_id'])
            {
                $data['class_data']['place_name'] = $place['name'];
            }
        }
        //$data['users']    = getUsers();


        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Get Classes Lessons Status And Data (Notes For Example) (same in ReportController.php)
    function get_class_lessons_data($class_id = null, $reports_ids = null)
    {
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

    
    // Show All Reports Summary
    public function show_summary($class_id = null)
    {
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

        $data['students'] = getStudents($class_id, 'all');


        if (!isRole($user_id, 'admin'))
        {
            if (!is_instructor_of($class_id, $user_id))
                return json_encode(['error' => 'You do not have permission for this action.']);
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
        $lessons_data = $this->get_class_lessons_data($class_id);
        $data['sections'] = $lessons_data['sections'];
        $data['sections_notes'] = $lessons_data['sections_notes'];
        $data['lessons'] = $lessons_data['lessons'];

        // Class Data
        $data['class_data'] = $classModel->find($class_id);

        // Get Instructor Data
        $userModel = new UserModel();
        $data['instructor'] = $userModel->find($data['class_data']['instructor_id']);

        // Time
        $times = getTimes();
        $data['class_data']['time_name'] = '';
        foreach ($times as $time)
        {
            if (('time_id_'.$time['id']) == $data['class_data']['time'])
            {
                $data['class_data']['time_name'] = $time['name'];
                break;
            }
        }
        if ($data['class_data']['time_name'] == '')
        {
            $data['class_data']['time_name'] = $data['class_data']['time'];
        }

        // Date
        $dates = getDates();
        $data['class_data']['date_name'] = '';
        foreach ($dates as $date)
        {
            if ($date['id'] == $data['class_data']['date_id'])
            {
                $data['class_data']['date_name'] = $date['name'];
            }
        }

        // Place
        $places = getPlaces();
        $data['class_data']['place_name'] = '';
        foreach ($places as $place)
        {
            if ($place['id'] == $data['class_data']['place_id'])
            {
                $data['class_data']['place_name'] = $place['name'];
            }
        }

        // $data['users']    = getUsers();
        $data['class_id'] = $class_id;

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
