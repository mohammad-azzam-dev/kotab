<?php

// Get Class Majors List
function getMajors()
{
    $majorModel = new App\Models\Academic\MajorModel();
    $majors = $majorModel->findAll();

    return $majors;
}

// Get Class Courses List
function getCourses()
{
    $courseModel = new App\Models\Academic\CourseModel();
    $courses = $courseModel->findAll();

    return $courses;
}

// Get Class Places List
function getPlaces()
{
    $placeModel = new App\Models\Academic\PlaceModel();
    $places = $placeModel->findAll();

    // $temp_places = array();
    // foreach ($places as $place)
    // {
    //     $place['id'] = (int)$place['id'];
    //     $temp_places[] = $place;
    // }

    return $places;
}

// Get Class Dates List
function getDates()
{
    $dateModel = new App\Models\Academic\DateModel();
    $dates = $dateModel->findAll();

    return $dates;
}

// Get Class Times List
function getTimes()
{
    $timeModel = new App\Models\Academic\TimeModel();
    $times = $timeModel->findAll();

    return $times;
}

// Get Users List
function getUsers()
{
    $userModel = new App\Models\UserModel();
    $users = $userModel->orderBy('created_at', 'DESC')->findAll();;

    return $users;
}

// Get Class Students List
function getStudents($class_id, $leave_at = null)
{
    // leave_at: All, null

    $userModel = new App\Models\UserModel();
    $enrollementModel = new App\Models\Academic\EnrollementModel();
    $removeStudentRequestsModel = new App\Models\Academic\G10\RemoveStudentRequestsModel();


    $enrollementModel->where('class_id', $class_id);
    if ($leave_at != 'all') // Only Get "Null" Fields
    {
        $enrollementModel->where('leave_at', $leave_at);
    }
                                        
    $enrollements = $enrollementModel->findAll();


    // Remove Duplicated Students IDs
    $enrollements_temp = array();
    $students_ids = array();
    foreach ($enrollements as $enroll)
    {
        if (!in_array($enroll['user_id'], $students_ids))
        {
            $students_ids[] = $enroll['user_id'];
            $enrollements_temp[] = $enroll;
        }
    }
    $enrollements = $enrollements_temp;

    // Set First Name and Last Name for each student
    $enrollements_temp = array();
    foreach ($enrollements as $enroll)
    {
        $enroll['data'] = $userModel->find($enroll['user_id']);
        if ($enroll['data'] == '') // This is a deleted user, so no data to display
            continue;

        $enrollements_temp[] = $enroll;
    }
    $enrollements = $enrollements_temp;


    // Check If There a Request To Remove a Student
    $remove_requests = $removeStudentRequestsModel->where('class_id', $class_id)->findAll();
    $enrollements_temp = array();
    foreach ($enrollements as $enroll)
    {
        $remove_request = '';
        foreach ($remove_requests as $request)
        {
            if ($request['student_id'] == $enroll['user_id'])
            {
                $remove_request = $request['status'];
                // break; // Do Not Break The "foreach" Loop, Because We Want To Get The Last Status Of The Request
            }
        }
        $enroll['remove_request'] = $remove_request;
        $enrollements_temp[] = $enroll;
    }
    $enrollements = $enrollements_temp;

    return $enrollements;
}


// Get Children IDs
function getChildrenId($parent_id)
{
    $parentModel = new App\Models\Roles\ParentModel();
    $data = $parentModel->findAll();

    $childrenId = array();
    foreach ($data as $row)
    {
        $childrenId[] = $row['child_id'];
    }

    return $childrenId;
}

// Check
function is_instructor_of($class_id, $user_id = null)
{
    if (!($class_id > 0)) return 0; // Return FALSE

    if ($user_id == null)
    {
        $session = \Config\Services::session();
        $user_id = $session->get('id');
    }

    $class_data = getClass($class_id, 'array');

    if ($class_data['instructor_id'] === $user_id)
    {
        return TRUE;
    }
    else
    {
        return 0; //"return FALSE" returns empty string
    }

}

// Get Class Data (Same in ClassesController)
function getClass($id, $dataType = "json")
{
    $classModel = new App\Models\Academic\ClassModel();
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

// Get User Data By ID
function get_user_data($user_id)
{
    $userModel = new App\Models\UserModel();
    $user = $userModel->find($user_id);;

    return $user;
}