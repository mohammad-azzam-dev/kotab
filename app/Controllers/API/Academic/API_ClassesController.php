<?php namespace App\Controllers\API\Academic;

use App\Controllers\BaseController;
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

class API_ClassesController extends BaseController
{
	// Classes List
	public function list($parm = "instructor-classes")
	{
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

        $majors = getMajors();
        $courses = getCourses();
        $places = getPlaces();
        $dates = getDates();
        $times = getTimes();
        $instructors = getUsers();

        if (!($user_id > 0))
            return json_encode(['error' => 'Parse a valid user ID.']);



        if ($parm == "all")
        {
            if (!isRole($user_id, 'admin'))
            return json_encode(['error' => 'You do not have permission for this action.']);

            $classModel = new ClassModel();
            $classes = $classModel->findAll();
        }
        elseif ($parm == "my-classes")
        {
            return json_encode(['error' => 'You do not have permission for this action.']);

            $classes = $this->getUserClasses($user_id);
        }
        elseif ($parm == "instructor-classes")
        {
            if (!isRole($user_id, 'instructor'))
            return json_encode(['error' => 'You do not have permission for this action.']);

            $classes = $this->getInstructorClasses($user_id);
        }
        elseif ($parm == "my-children-classes")
        {
            if (!isRole($user_id, 'parent'))
            return json_encode(['error' => 'You do not have permission for this action.']);

            $childrenId = getChildrenId($user_id);

            $classes = array();
            foreach ($childrenId as $childId)
            {
                $classes[] = $this->getUserClasses($childId);
            }
        }
        else
        {
            return json_encode(['error' => 'error 001']);
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
            if (isRole($user_id, 'admin'))
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
            if (is_instructor_of($r['id']) || isRole($user_id, 'admin'))
            {
                // Class Show Report Button
                $actions .= '<a href="' .base_url("Academic/ReportsController/show_summary/".$r["id"]) .'" class="btn btn-dark btn-sm btn-just-icon"><i class="material-icons">bar_chart</i></a>';
                $actions .= '&nbsp;&nbsp;&nbsp;';
                // Class Students Button
                $actions .= '<a href="' .base_url("ClassesController/showClassStudents/".$r["id"]) .'" class="btn btn-warning btn-sm btn-just-icon"><i class="material-icons">people_alt</i></a>';
                $actions .= '&nbsp;&nbsp;&nbsp;';
            }
            if (isRole($user_id, 'admin'))
            {
                // Delete Button
                $actions .= '<button type="button" name="delete" url="'.base_url("ClassesController/delete").'/'.$r["id"].'" id="'.$r["id"].'" class="delete btn btn-danger btn-sm btn-just-icon" data-toggle="modal" data-target="#confirmModal"><i class="material-icons">delete</i></button>';
            }

            $data[] = array(
                'id'            => $r['id'],
                'name'          => $r['name'],
                'major'         => $r['major_name'],
                'course'        => $r['course_name'],
                'instructor'    => $r['instructor_name'],
                'place'         => $r['place_name'],
                'date'          => $r['date_name'],
                'time'          => $r['time_name'],
                'reports_count' => count($reports),
            );

        }
        
        return json_encode($data, JSON_UNESCAPED_UNICODE);
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




    // Remove Student Request
    public function remove_student_request($class_id, $student_id)
    {
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
        if (!($user_id > 0)) return 'Login first';


        if (!is_instructor_of($class_id, $user_id) && !isRole($user_id, 'admin'))
        {
            return json_encode(['error' => 'You do not have permission for this action.']);
        }

        $requester_id = $user_id;

        if (TRUE)
        {
            if (isRole($requester_id, 'admin'))
            {
                $enrollementModel = new EnrollementModel();

                $time = date("Y-m-d H:i:s");
                $enrollementModel->where('class_id', $class_id)->where('user_id', $student_id)->set('leave_at', $time)->set('leave_reason', $this->request->getVar('reason'))->update();

                return json_encode(['success' => 'تم حذف التلميذ بنجاح']);
            }
            elseif (isRole($requester_id, 'instructor'))
            {
                $removeStudentRequestsModel = new RemoveStudentRequestsModel();
                
                $valid = $this->validate([
                    'reason' => 'required|trim',
                ]);
            
                if (!$valid)
                {
                    $data['validation'] = $this->validator;
                    return json_encode(['errors' => $data['validation']->listErrors()]);
                }
                else
                {
                    /*$last_request = $removeStudentRequestsModel->where('class_id', $class_id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();

                    if ($last_request['status'] == '')
                    {

                    }*/

                    $removeStudentRequestsModel->save([
                        'class_id'     => $class_id,
                        'requester_id' => $requester_id,
                        'student_id'   => $student_id,
                        'status'       => 'pending',
                        'reason'       => $this->request->getVar('reason')
                    ]);
                    return json_encode(['success' => 'تم ارسال الطلب للمراجعة'], JSON_UNESCAPED_UNICODE);
                }
            }
            else
            {
                die ('Error');
            }
        }
    }

    // Get Class Places
    function get_places_list()
    {
        $data['places'] = getPlaces();
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
