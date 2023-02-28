<?php namespace App\Controllers\Academic;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Academic\LessonModel;
use App\Models\Academic\G07\SectionModel;

class LessonsController extends ResourceController
{
    protected $format    = 'json';

    // index
    public function index($course_id = null)
    {

    }

    // Create Course
    public function create($section_id = null)
    {
        if (TRUE)
        {
            $lessonModel = new lessonModel();
            
            $valid = $this->validate([
                'lesson_name'       => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $lessonModel->save([
                    'name' => $this->request->getVar('lesson_name'),
                    'description' => $this->request->getVar('lesson_description'),
                    'section_id' => $section_id,
                ]);
                return json_encode(['success' => 'تمت إضافة البيانات بنجاح']);
            }
        }
        else {
            return redirect()->to(base_url('Academic/LessonsController/'.$section_id));
        }
    }

    // Update Course
    public function update($id = null, $section_id = null)
    {
        if( TRUE)
        {
            $lessonModel = new LessonModel();
            
            $valid = $this->validate([
                'lesson_name'       => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $lessonModel->update($id, [
                    'name' => $this->request->getVar('lesson_name'),
                    'description' => $this->request->getVar('lesson_description'),
                ]);
                
                return json_encode(['success' => 'تم تحديث البيانات بنجاح']);
            }
        }
        else {
            return redirect()->to(base_url('Academic/LessonsController/'.$section_id));
        }
    }

    // Delete Course
    public function delete($id = null)
    {
        $lessonModel = new LessonModel();
        $lessonModel->delete($id);
        
        $message = array(
            'success' => 'لقد تمت العملية بنجاح'
        ); 
        session()->setFlashdata($message);
        return redirect()->to(base_url('CoursesController'));
    }

    // Get Course Data
    public function getLesson($id)
    {
        $lessonModel = new LessonModel();
        $lesson = $lessonModel->find($id);

        return json_encode(['result' => $lesson]);
    }

    // DataTable
    /*public function lessonsDataTable($section_id = null)
    {
        // Datatables Variables
        $draw = intval($this->request->getVar("draw"));
        $start = intval($this->request->getVar("start"));
        $length = intval($this->request->getVar("length"));

        $lessonModel = new LessonModel();
        $lessons = $lessonModel->where('section_id', $section_id)->findAll();

        $data = array();

        foreach($lessons as $r) {

            // Update Button
            $actions  = '<button type="button" name="update" id="'.$r["id"].'" class="update-lesson btn btn-primary btn-sm btn-just-icon" data-toggle="modal" data-target="#create-update-lesson-modal"><i class="material-icons">edit</i></button>';
            // Some Space Between Buttons
            $actions .= '&nbsp;&nbsp;&nbsp;';
            // Delete Button
            $actions .= '<button type="button" name="delete" url="'.base_url("Academic/LessonsController/delete").'/'.$r["id"].'" id="'.$r["id"].'" class="delete btn btn-danger btn-sm btn-just-icon" data-toggle="modal" data-target="#confirmModal"><i class="material-icons">delete</i></button>';
            
            $data[] = array(
                '', // Just for the table index
                $r['name'],
                $r['description'],
                $actions
            );
        }

        $output = array(
            "draw" => $draw,
                "recordsTotal" => count($lessons),
                "recordsFiltered" => count($lessons),
                "data" => $data
            );
        echo json_encode($output);
        exit();
    }*/
}