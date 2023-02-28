<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Academic\ClassModel;
use App\Models\Academic\MajorModel;
use App\Models\Academic\CourseModel;
use App\Models\Academic\PlaceModel;
use App\Models\Academic\DateModel;
use App\Models\Academic\TimeModel;
use App\Models\UserModel;
use App\Models\Academic\EnrollementModel;
use App\Models\Academic\G03\ClassStatusHistoryModel;
use App\Models\Academic\G10\RemoveStudentRequestsModel;
use App\Models\Academic\G10\AddStudentRequestsModel;
use App\Models\Academic\AttendanceModel;
use App\Models\Roles\ParentModel;
use App\Models\Academic\LessonModel;
use App\Models\Academic\CompletedLessonModel;
use App\Models\Academic\ReportModel;

class ClassesController extends ResourceController
{

    protected $classModel = 'App\Models\ClassModel';
    protected $format    = 'json';

    // Classes List
    public function classesList($parm = "myClasses")
    {
        $data['majors'] = getMajors();
        $data['courses'] = getCourses();
        $data['places'] = getPlaces();
        $data['dates'] = getDates();
        $data['times'] = getTimes();
        $data['instructors'] = get_users_by_role('instructor');

        $data['page_path'] = 'dashboard/classes/classesList';
        $data['modals_path'] = 'dashboard/classes/modals';
        $data['scripts_path'] = 'dashboard/classes/scripts';

        $data['dataTable_parm'] = $parm;
            
        if ($parm == "all")
        {
            if (!isRole($this->session->get('id'), 'admin'))
            return 'You do not have permission for this action.';

            $data['page_title'] = 'كل الصفوف';

        }
        elseif ($parm == "myClasses")
        {
            $data['page_title'] = 'صفوفي';
        }
        elseif ($parm == "instructorClasses")
        {
            $data['page_title'] = 'الصفوف التي أدرسها';
        }
        elseif ($parm == "myChildrenClasses")
        {
            $data['page_title'] = 'صفوف أولادي';
        }

        return view('backend/index', $data);
    }

    // Create
    public function create()
    {
        if (!isRole($this->session->get('id'), 'admin'))
        return 'You do not have permission for this action.';

        $status = $this->request->getVar('status');
        if ($this->request->getVar('status_change_date') == '')
        {
            $status_change_date = date("Y-m-d");
        }
        else
        {
            $status_change_date = $this->request->getVar('status_change_date');
        }

        if( TRUE)
        {
            $classModel = new ClassModel();
            
            $valid = true;
            if ($status != 'draft') {
                $valid = $this->validate([
                    'class_name' => 'required|trim',
                    /*'major'      => 'required|trim',
                    'course'     => 'required|trim',
                    'instructor' => 'required|trim',
                    'place'      => 'required|trim',
                    'date'       => 'required|trim',
                    'time'       => 'required|trim',*/
                ]);
            }
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $classModel->save([
                    'name'          => $this->request->getVar('class_name'),
                    'major_id'      => $this->request->getVar('major'),
                    'course_id'     => $this->request->getVar('course'),
                    'instructor_id' => $this->request->getVar('instructor'),
                    'place_id'      => $this->request->getVar('place'),
                    'date_id'       => $this->request->getVar('date'),
                    'time'          => $this->request->getVar('time'),
                    'status'        => $status
                ]);

                // Get Last Inserted ID
                $db = \Config\Database::connect();
                $db_ar = (array)$db;
                $last_inserted_id = $db_ar['mysqli']->insert_id;

                // Save Status Change in History
                $classStatusHistory = new ClassStatusHistoryModel();
                $classStatusHistory->save([
                    'class_id'   => $last_inserted_id,
                    'status'     => $status,
                    'changed_at' => $status_change_date,
                ]);

                return json_encode(['success' => 'Data added successfully.']);
            }
        }
        else {
            return redirect()->to(base_url('ClassesController/classesList/myClasses'));
        }
    }

    // Update
    public function update($class_id = null)
    {
        if (!isRole($this->session->get('id'), 'admin'))
        return 'You do not have permission for this action.';

        $status = $this->request->getVar('status');
        if ($this->request->getVar('status_change_date') == '')
        {
            $status_change_date = date("Y-m-d");
        }
        else
        {
            $status_change_date = $this->request->getVar('status_change_date');
        }

        if( TRUE)
        {
            $classModel = new ClassModel();
            
            $valid = true;
            if ($status != 'draft')
            {
                $valid = $this->validate([
                    'class_name' => 'required|trim',
                    /*'major'      => 'required|trim',
                    'course'     => 'required|trim',
                    'instructor' => 'required|trim',
                    'place'      => 'required|trim',
                    'date'       => 'required|trim',
                    'time'       => 'required|trim',*/
                ]);
            }
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                // Get Class Data, Because We want To check If The Status Was Changed
                $class_data = $classModel->find($class_id);

                // Update Data
                $classModel->update($class_id, [
                    'name'          => $this->request->getVar('class_name'),
                    'major_id'      => $this->request->getVar('major'),
                    'course_id'     => $this->request->getVar('course'),
                    'instructor_id' => $this->request->getVar('instructor'),
                    'place_id'      => $this->request->getVar('place'),
                    'date_id'       => $this->request->getVar('date'),
                    'time'          => $this->request->getVar('time'),
                    'status'        => $status
                ]);

                // Save Status Change in History
                if ($class_data['status'] != $status)
                {
                    $classStatusHistory = new ClassStatusHistoryModel();
                    $classStatusHistory->save([
                        'class_id'   => $class_id,
                        'status'     => $status,
                        'changed_at' => $status_change_date,
                    ]);
                }
                return json_encode(['success' => 'Data updated successfully.']);
            }
        }
        else {
            return redirect()->to(base_url('ClassesController/classesList/myClasses'));
        }
    }

    // Delete
    public function delete($id = null)
    {
        if (!isRole($this->session->get('id'), 'admin'))
        return 'You do not have permission for this action.';
        
        $classModel = new ClassModel();
        $classModel->delete($id);
        
        $message = array(
            'success' => 'لقد تمت العملية بنجاح'
        ); 
        session()->setFlashdata($message);
        return redirect()->to(base_url('ClassesController/classesList/all'));
    }

    // Get Data To Update It Later
    public function getClass($id, $dataType = "json")
    {
        $classModel = new ClassModel();
        $classes = $classModel->find($id);

        if ($dataType == "json")
        {
            return json_encode(['result' => $classes]);
        }
        elseif ($dataType == "array")
        {
            return $classes;
        }
    }

    // Get Classes Data Of User By ID
    public function getUserClasses($user_id)
    {
        $classModel = new ClassModel();
        $enrollementModel = new EnrollementModel();

        $enrollements = $enrollementModel->where('user_id', $user_id)->where('leave_at', null)->findAll();

        if (!empty($enrollements))
        {
            $classes_ids = array();
            foreach ($enrollements as $enrollement)
            {
                $classes_ids[] = $enrollement['class_id'];
            }
            $classes = $this->getClass($classes_ids, 'array');

            return $classes;
        }
        else
        {
            return array();
        }
    }

    // Get Classes Data Of Instructor By ID
    public function getInstructorClasses($instructor_id)
    {
        $classModel = new ClassModel();
        $classes = $classModel->where('instructor_id', $instructor_id)->findAll();

        return $classes;
    }

    // DataTable
    public function classesDataTable($parm)
    {
        // Datatables Variables
        $draw = intval($this->request->getVar("draw"));
        $start = intval($this->request->getVar("start"));
        $length = intval($this->request->getVar("length"));


        $majors = getMajors();
        $courses = getCourses();
        $places = getPlaces();
        $dates = getDates();
        $times = getTimes();
        $instructors = getUsers();


        if ($parm == "all")
        {
            if (!isRole($this->session->get('id'), 'admin'))
            return 'You do not have permission for this action.';

            $classModel = new ClassModel();
            $classes = $classModel->findAll();
        }
        elseif ($parm == "myClasses")
        {
            if (!isRole($this->session->get('id'), 'student'))
            return 'You do not have permission for this action.';

            $classes = $this->getUserClasses($this->session->get('id'));
        }
        elseif ($parm == "instructorClasses")
        {
            if (!isRole($this->session->get('id'), 'instructor'))
            return 'You do not have permission for this action.';

            $classes = $this->getInstructorClasses($this->session->get('id'));
        }
        elseif ($parm == "myChildrenClasses")
        {
            if (!isRole($this->session->get('id'), 'parent'))
            return 'You do not have permission for this action.';

            $childrenId = getChildrenId($this->session->get('id'));

            $classes = array();
            foreach ($childrenId as $childId)
            {
                $classes[] = $this->getUserClasses($childId);
            }
        }
        else
        {
            die("Error 002");
        }


        $data = array();

        foreach($classes as $r)
        {
            // Major
            $r['major_name'] = '';
            foreach ($majors as $major)
            {
                if ($major['id'] == $r['major_id'])
                {
                    $r['major_name'] = $major['name'];
                    break;
                }
            }

            // Course
            $r['course_name'] = '';
            foreach ($courses as $course)
            {
                if ($course['id'] == $r['course_id'])
                {
                    $r['course_name'] = $course['name'];
                    break;
                }
            }

            // Instructor
            $r['instructor_name'] = '';
            foreach ($instructors as $instructor)
            {
                if ($instructor['id'] == $r['instructor_id'])
                {
                    $r['instructor_name'] = $instructor['first_name'].' '.$instructor['last_name'];
                    break;
                }
            }

            // Place
            $r['place_name'] = '';
            foreach ($places as $place)
            {
                if ($place['id'] == $r['place_id'])
                {
                    $r['place_name'] = $place['name'];
                    break;
                }
            }

            // Date
            $r['date_name'] = '';
            foreach ($dates as $date)
            {
                if ($date['id'] == $r['date_id'])
                {
                    $r['date_name'] = $date['name'];
                    break;
                }
            }

            // Time
            $r['time_name'] = '';
            foreach ($times as $time)
            {
                if (('time_id_'.$time['id']) == $r['time'])
                {
                    $r['time_name'] = $time['name'];
                    break;
                }
            }
            if ($r['time_name'] == '')
            {
                $r['time_name'] = $r['time'];
            }


            // Count Classes Reports
            $reportModel = new ReportModel();
            $reports = $reportModel->where("class_id", $r['id'])->findAll();


            // Actions
            $actions = '';
            if (isRole($this->session->get('id'), 'admin'))
            {
                // Update Button
                $actions .= '<button type="button" name="update" id="'.$r["id"].'" class="update-class btn btn-primary btn-sm btn-just-icon" data-toggle="modal" data-target="#create-update-class-modal"><i class="material-icons">edit</i></button>';
                $actions .= '&nbsp;&nbsp;&nbsp;';
            }
            if (is_instructor_of($r['id']))
            {
                // Class Create Report Button
                $actions .= '<a href="' .base_url("Academic/ReportsController/new/".$r["id"]) .'" class="btn btn-success btn-sm btn-just-icon"><i class="material-icons">assignment</i></a>';
                $actions .= '&nbsp;&nbsp;&nbsp;';
            }
            if (is_instructor_of($r['id']) || isRole($this->session->get('id'), 'admin'))
            {
                // Class Show Report Button
                $actions .= '<a href="' .base_url("Academic/ReportsController/show_summary/".$r["id"]) .'" class="btn btn-dark btn-sm btn-just-icon"><i class="material-icons">bar_chart</i></a>';
                $actions .= '&nbsp;&nbsp;&nbsp;';
                // Class Students Button
                $actions .= '<a href="' .base_url("ClassesController/showClassStudents/".$r["id"]) .'" class="btn btn-warning btn-sm btn-just-icon"><i class="material-icons">people_alt</i></a>';
                $actions .= '&nbsp;&nbsp;&nbsp;';
            }
            if (isRole($this->session->get('id'), 'admin'))
            {
                // Delete Button
                $actions .= '<button type="button" name="delete" url="'.base_url("ClassesController/delete").'/'.$r["id"].'" id="'.$r["id"].'" class="delete btn btn-danger btn-sm btn-just-icon" data-toggle="modal" data-target="#confirmModal"><i class="material-icons">delete</i></button>';
            }

            $data[] = array(
                '<input type="checkbox" class="dataTable-check-item" value="class-' .$r['id'] .'">',
                '', // Just for the table index
                $r['name'],
                $r['major_name'],
                $r['course_name'],
                $r['instructor_name'],
                $r['place_name'],
                $r['date_name'],
                $r['time_name'],
                count($reports),
                $actions
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($classes),
            "recordsFiltered" => count($classes),
            "data" => $data
        );
        return json_encode($output);
    }

    /* START: Enrollement */
        // START: Enroll Students View
        public function showClassStudents($class_id) {
            $data['page_title'] = "التلاميذ";
            $data['page_path']    = 'dashboard/classes/students';
            $data['modals_path'] = 'dashboard/classes/students_modals';
            $data['scripts_path'] = 'dashboard/classes/scripts';

            $data['students'] = getStudents($class_id);
            $data['users'] = getUsers();
            $data['class_id'] = $class_id;

            $data['add_students_requests'] = $this->get_add_students_requests($class_id);

            return view('backend/index', $data);
        }
        // END: Enroll Students View

        // Get "Add Students Requests"
        public function get_add_students_requests($class_id)
        {
            $addStudentRequestsModel = new AddStudentRequestsModel();
            $requests = $addStudentRequestsModel->where("class_id", $class_id)->findAll();

            return $requests;
        }

        // Enroll students to class (from class page)
        public function storeEnrollement($class_id) {
            // Get Old Students Ids
            $old_students = getStudents($class_id);
            $old_students_id = array();
            if (count($old_students) > 0) {
                foreach ($old_students as $student) {
                    if ($student['leave_at'] == null) {
                        array_push($old_students_id, $student['user_id']);
                    }
                }
            }

            // Get Current (After Submit) Students Ids
            $new_students_id = $this->request->getVar("students_id");
            // Remove Duplicated Ids
            if ($new_students_id != null) {
                $new_students_id = array_unique($this->request->getVar("students_id"));
            }
            else {
                $new_students_id = array();
            }

            // // // // //
            $enrollementModel = new EnrollementModel();

            if (count($old_students) > 0) {
                // Update New Students After Removing The Old Ones From The Array
                $i = 0;
                $temp_new_students_id = $new_students_id;
                foreach ($new_students_id as $student_id) {
                    if (in_array($student_id, $old_students_id)) {
                        unset($temp_new_students_id[$i]);
                    }
                    $i++;
                }
                $new_students_id = array_values($temp_new_students_id);
            }

            // Store New Students
            if (count($new_students_id) > 0) {
                foreach ($new_students_id as $student_id) {
                    if ($student_id != '') {
                        $enrollementModel->insert(['class_id' => $class_id, 'user_id' => $student_id]);
                    }
                }
            }

            return redirect()->to(base_url('ClassesController/showClassStudents').'/'.$class_id);
        }


    /* END: Enrollement */


    /* -------------------------------------------------- */




    
}