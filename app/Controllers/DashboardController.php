<?php namespace App\Controllers;

use App\Models\Academic\EnrollementModel;
use App\Models\Academic\ClassModel;

class DashboardController extends BaseController
{
	// Index
	public function index()
	{
		if (!isRole($this->session->get('id'), 'admin'))
		{
			if (isRole($this->session->get('id'), 'student'))
			{
				return redirect()->to(base_url('/dashboard/classes/my-classes'));
			}
			elseif (isRole($this->session->get('id'), 'instructor'))
			{
				return redirect()->to(base_url('/dashboard/classes/instructor-classes'));
			}
			elseif (isRole($this->session->get('id'), 'parent'))
			{
				return redirect()->to(base_url('/dashboard/classes/my-children-classes'));
			}
			else
			{
				echo 'You have no permission.'; exit();
			}
		}


		$data['page_title'] = 'لوحة التحكم';
		$data['page_path']  = 'dashboard/index';
		

		// Classes Count
		$classModel = new ClassModel();
			// Active CLasses
			$active_classes = $classModel->where('status', 'active')->findAll();
			$data['active_classes_count'] = count($active_classes);
			// Inctive CLasses
			$inactive_classes = $classModel->where('status', 'inactive')->findAll();
			$data['inactive_classes_count'] = count($inactive_classes);
			// Canceled CLasses
			$canceled_classes = $classModel->where('status', 'canceled')->findAll();
			$data['canceled_classes_count'] = count($canceled_classes);
			// Draft CLasses
			$draft_classes = $classModel->where('status', 'draft')->findAll();
			$data['draft_classes_count'] = count($draft_classes);

		// Get Classes Ids
		$active_classes_ids = array();
		foreach ($active_classes as $class)
		{
			$active_classes_ids[] = $class['id'];
		}


		// Students Count
		$enrollementModel = new EnrollementModel();
		$enrollements = $enrollementModel->where('leave_at', NULL)->findAll();

		$data['cur_students_count'] = array();
		$students_ids = array();
		foreach ($enrollements as $enrol)
		{
			if (!in_array($enrol['user_id'], $students_ids) && in_array($enrol['class_id'], $active_classes_ids))
			{
				$students_ids[] = $enrol['user_id'];
			}
		}
		$data['cur_students_count'] = count($students_ids);


		return view('backend/index', $data);
	}

	//--------------------------------------------------------------------

}
