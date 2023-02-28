<?php namespace App\Controllers\Academic;

use App\Controllers\BaseController;
use App\Models\Academic\ClassModel;
use App\Models\Academic\G10\RemoveStudentRequestsModel;
use App\Models\Academic\G10\AddStudentRequestsModel;
use App\Models\Academic\EnrollementModel;

class ClassesRequestsController extends BaseController
{
    protected $format    = 'json';

    // index
    public function list_requests($status = null, $class_id = null)
    {
        if (($status != 'all' && $status != 'pending' && $status != 'rejected' && $status != 'accepted') || is_numeric($status))
        {
            die ('Wrong Parameter!');
        }

        $data['page_title'] = 'الطلبات';
        $data['page_path'] = 'dashboard/classes_requests/index';
        $data['modals_path'] = 'dashboard/classes_requests/modals';
        $data['scripts_path'] = 'dashboard/classes_requests/scripts';

        $classModel = new ClassModel();
        $removeStudentRequestsModel = new RemoveStudentRequestsModel();
        $addStudentRequestsModel = new AddStudentRequestsModel();

        // For ALl Classes
        if ($class_id == null)
        {
            // Get "Remove Students Requests" Fromm Classes
            if ($status != 'all')
            {
                $remove_students_requests_temp = $removeStudentRequestsModel->where('status', $status)->findAll();
            }
            else
            {
                $remove_students_requests_temp = $removeStudentRequestsModel->findAll();
            }

            // Get All Classes IDs That Have "Remove Students Requests"
            $remove_classes_ids = array();
            foreach ($remove_students_requests_temp as $request)
            {
                if (!in_array($request['class_id'], $remove_classes_ids)) {
                    $remove_classes_ids[] = $request['class_id'];
                }
            }

            // Order "Remove Students Requests" By Classes IDs
            $remove_students_requests = array();
            foreach ($remove_classes_ids as $class_id)
            {
                foreach ($remove_students_requests_temp as $request)
                {
                    if ($request['class_id'] == $class_id)
                    {
                        $remove_students_requests[$class_id][] = $request;
                    }
                }
            }
            $data['remove_students_requests'] = $remove_students_requests;

            /* --------- */

            // Get All "Add Students Requests" Fromm Classes
            if ($status != 'all')
            {
                $add_students_requests_temp = $addStudentRequestsModel->where('status', $status)->findAll();
            }
            else
            {
                $add_students_requests_temp = $addStudentRequestsModel->findAll();
            }

            // Get All Classes IDs That Have "Add Students Requests"
            $add_classes_ids = array();
            foreach ($add_students_requests_temp as $request)
            {
                if (!in_array($request['class_id'], $add_classes_ids)) {
                    $add_classes_ids[] = $request['class_id'];
                }
            }

            // Order "Add Students Requests" By Classes IDs
            $add_students_requests = array();
            foreach ($add_classes_ids as $class_id)
            {
                foreach ($add_students_requests_temp as $request)
                {
                    if ($request['class_id'] == $class_id)
                    {
                        $add_students_requests[$class_id][] = $request;
                    }
                }
            }
            $data['add_students_requests'] = $add_students_requests;
        }

        return view('backend/index', $data);
    }

    // Remove Student Request
    public function remove_student_request($class_id, $student_id)
    {
        if (!is_instructor_of($class_id) && !isRole($this->session->get('id'), 'admin'))
        {
            return json_encode(['error' => 'You do not have permission for this action.']);
        }

        $requester_id = $this->session->get('id');

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
                    $removeStudentRequestsModel->save([
                        'class_id'     => $class_id,
                        'requester_id' => $requester_id,
                        'student_id'   => $student_id,
                        'status'       => 'pending',
                        'reason'       => $this->request->getVar('reason')
                    ]);
                    return json_encode(['success' => 'تم ارسال الطلب للمراجعة']);
                }
            }
            else
            {
                die ('Error');
            }
        }
        else {
            return redirect()->to(base_url('ClassesController/showClassStudents/'.$class_id));
        }
    }

    // Accept "Remove Student Request"
    function accept_remove_student_request($request_id)
    {
        if (!isRole($this->session->get('id'), 'admin'))
        return json_encode(['error' => 'You do not have permission for this action.']);

        $removeStudentRequestsModel = new RemoveStudentRequestsModel();
        $enrollementModel = new EnrollementModel();

        // Get Request Data
        $request_data = $removeStudentRequestsModel->find($request_id);

        // Update "leave_at' In Enrollement Table
        $time = date("Y-m-d H:i:s");
        $enrollementModel->where('class_id', $request_data['class_id'])->where('user_id', $request_data['student_id'])->set('leave_at', $time)->set('leave_reason', $request_data['reason'])->update();

        // Update Request Status
        $removeStudentRequestsModel->update($request_id, [
            'status' => 'accepted'
        ]);
        
        return json_encode(['success' => 'تمت الموافقة على إزالة الطالب من الصف']);
    }

    // Reject "Remove Student Request"
    function reject_remove_student_request($request_id)
    {
        if (!isRole($this->session->get('id'), 'admin'))
        return json_encode(['error' => 'You do not have permission for this action.']);

        $removeStudentRequestsModel = new RemoveStudentRequestsModel();
        $removeStudentRequestsModel->update($request_id, [
            'status' => 'rejected'
        ]);
        
        return json_encode(['success' => 'لم تتم الموافقة على إزالة الطالب من الصف']);
    }


    // Add Student Request
    public function add_student_request($class_id)
    {
        if (!is_instructor_of($class_id))
        {
            return json_encode(['error' => 'You do not have permission for this action.']);
        }

        $requester_id = $this->session->get('id');

        if (TRUE)
        {
            $addStudentRequestsModel = new AddStudentRequestsModel();
            
            $valid = $this->validate([
                'first_name' => 'required|trim',
                'middle_name' => 'required|trim',
                'last_name' => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $addStudentRequestsModel->save([
                    'class_id'     => $class_id,
                    'requester_id' => $requester_id,
                    'first_name'   => $this->request->getVar('first_name'),
                    'middle_name'  => $this->request->getVar('middle_name'),
                    'last_name'    => $this->request->getVar('last_name'),
                    'birth_year'    => $this->request->getVar('birth_year'),
                    'status'       => 'pending'
                ]);
                return json_encode(['success' => 'تم ارسال الطلب للمراجعة']);
            }
        }
        else {
            return redirect()->to(base_url('ClassesController/showClassStudents/'.$class_id));
        }
    }

    // Accept "Add Student Request"
    function accept_add_student_request($request_id)
    {
        if (!isRole($this->session->get('id'), 'admin'))
        return json_encode(['error' => 'You do not have permission for this action.']);

        $addStudentRequestsModel = new addStudentRequestsModel();
        $enrollementModel = new EnrollementModel();

        // Update Request Status (Admin should add the user manually)
        $addStudentRequestsModel->update($request_id, [
            'status' => 'accepted'
        ]);
        
        return json_encode(['success' => 'تمت الموافقة على إضافة الطالب إلى الصف']);
    }

    // Reject "Add Student Request"
    function reject_add_student_request($request_id)
    {
        if (!isRole($this->session->get('id'), 'admin'))
        return json_encode(['error' => 'You do not have permission for this action.']);

        $addStudentRequestsModel = new AddStudentRequestsModel();
        $addStudentRequestsModel->update($request_id, [
            'status' => 'rejected'
        ]);
        
        return json_encode(['success' => 'لم تتم الموافقة على إضافة الطالب إلى الصف']);
    }
}