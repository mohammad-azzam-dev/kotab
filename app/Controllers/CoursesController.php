<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Academic\CourseModel;
use App\Models\Academic\MajorModel;
use App\Models\Academic\LessonModel;
use App\Models\Academic\G07\SectionModel;

class CoursesController extends ResourceController
{

    protected $courseModel = 'App\Models\CourseModel';
    protected $format    = 'json';

    // index
    public function index()
    {
        $data['page_title'] = 'المواد';
        $data['page_path'] = 'dashboard/courses/index';
        $data['modals_path'] = 'dashboard/courses/modals';
        $data['scripts_path'] = 'dashboard/courses/scripts';

        $data['majors'] = $this->getMajors();

        return view('backend/index', $data);
    }

    // Create
    public function create()
    {
        if( TRUE)
        {
            $courseModel = new CourseModel();
            
            $valid = $this->validate([
                'course_name'       => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $courseModel->save([
                    'name' => $this->request->getVar('course_name'),
                    'code_number' => $this->request->getVar('course_code_number'),
                    'majors_id' => $this->request->getVar('course_majors_id'),
                    'description' => $this->request->getVar('course_description'),
                    'number_of_credits' => $this->request->getVar('course_number_of_credits'),
                ]);
                return json_encode(['success' => 'Data added successfully.']);
            }
        }
        else {
            return redirect()->to(base_url('CoursesController'));
        }
    }

    // Update
    public function update($id = null)
    {
        if( TRUE)
        {
            $courseModel = new CourseModel();
            
            $valid = $this->validate([
                'course_name'       => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $courseModel->update($id, [
                    'name' => $this->request->getVar('course_name'),
                    'code_number' => $this->request->getVar('course_code_number'),
                    'majors_id' => $this->request->getVar('course_majors_id'),
                    'description' => $this->request->getVar('course_description'),
                    'number_of_credits' => $this->request->getVar('course_number_of_credits'),
                ]);
                
                return json_encode(['success' => 'Data updated successfully.']);
            }
        }
        else {
            return redirect()->to(base_url('CoursesController'));
        }
    }

    // Show
    public function show($id = null)
    {
        $data['page_title'] = 'دروس المادة';
        $data['page_path'] = 'dashboard/courses/show';
        $data['modals_path'] = 'dashboard/courses/show_modals';
        $data['scripts_path'] = 'dashboard/courses/show_scripts';

        $data['course_id'] = $id;

        // Get all course's sections
        $sectionModel = new SectionModel();
        $sections = $sectionModel->where('course_id', $id)->orderBy('order', 'ASC')->findAll();
        $data['sections'] = $sections;

        // Get all lessons corrsponding to each section
        $lessonModel = new LessonModel();
        $lessons = array();
        foreach ($sections as $section)
        {
            $lessons[$section['id']] = $lessonModel->where('section_id', $section['id'])->findAll();
        }
        $data['lessons'] = $lessons;

        return view('backend/index', $data);
    }

    // Delete
    public function delete($id = null)
    {
        $courseModel = new CourseModel();
        $courseModel->delete($id);
        
        $message = array(
            'success' => 'لقد تمت العملية بنجاح'
        ); 
        session()->setFlashdata($message);
        return redirect()->to(base_url('CoursesController'));
    }

    // Get Course Data
    public function getCourse($id)
    {
        $courseModel = new CourseModel();
        $course = $courseModel->find($id);

        return json_encode(['result' => $course]);
    }

    // DataTable
    public function coursesDataTable()
    {
        // Datatables Variables
        $draw = intval($this->request->getVar("draw"));
        $start = intval($this->request->getVar("start"));
        $length = intval($this->request->getVar("length"));

        $majors = $this->getMajors();



        $courseModel = new CourseModel();
        $courses = $courseModel->findAll();

        $data = array();

        foreach($courses as $r) {

            // Major
            $r['majors_name'] = '';
            foreach ($majors as $major)
            {
                if ($major['id'] == $r['majors_id'])
                {
                    $r['majors_name'] = $major['name'];
                    break;
                }
            }


            // Update Button
            $actions  = '<button type="button" name="update" id="'.$r["id"].'" class="update-course btn btn-primary btn-sm btn-just-icon" data-toggle="modal" data-target="#create-update-course-modal"><i class="material-icons">edit</i></button>';
            $actions .= '&nbsp;&nbsp;&nbsp;';
            // Course Lessons Button
            $actions .= '<a href="' .base_url("CoursesController/show/".$r["id"]) .'" class="btn btn-warning btn-sm btn-just-icon"><i class="material-icons">format_list_bulleted</i></a>';
            $actions .= '&nbsp;&nbsp;&nbsp;';
            // Delete Button
            $actions .= '<button type="button" name="delete" url="'.base_url("CoursesController/delete").'/'.$r["id"].'" id="'.$r["id"].'" class="delete btn btn-danger btn-sm btn-just-icon" data-toggle="modal" data-target="#confirmModal"><i class="material-icons">delete</i></button>';
            
            $data[] = array(
                '', // Just for the table index
                $r['name'],
                $r['code_number'],
                $r['description'],
                $r['majors_name'],
                /*$r['number_of_credits'],*/
                $actions
            );
        }



        $output = array(
            "draw" => $draw,
                "recordsTotal" => count($courses),
                "recordsFiltered" => count($courses),
                "data" => $data
            );
        echo json_encode($output);
        exit();
    }
    
/* -------------------------------------------------- */

    // Get Class Majors List
    public function getMajors()
    {
        $majorModel = new MajorModel();
        $majors = $majorModel->findAll();

        return $majors;
    }

    // Get Class Sections List
    public function get_course_sections($course_id = null, $format = 'array')
    {
        $sectionModel = new SectionModel();
        $sections = $sectionModel->where('course_id', $course_id)->orderBy('order', 'ASC')->findAll();

        if ($format == 'array')
        {
            return $sections;
        }
        elseif ($format == 'json')
        {
            return json_encode(['result' => $sections]);
        }
    }

    // Sort Sections
    public function sort_sections()
    {
        print_r($_POST["section_id_array"]);
        for ($i = 0; $i < count($_POST["section_id_array"]); $i++)
        {
            $sectionModel = new SectionModel();
            $sectionModel->update($_POST["section_id_array"][$i], [
                'order' => ($i+1)
            ]);
        }
    }
}