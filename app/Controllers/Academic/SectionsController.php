<?php namespace App\Controllers\Academic;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Academic\G07\SectionModel;

class SectionsController extends ResourceController
{
    protected $format    = 'json';

    // index
    public function index($course_id = null)
    {

    }

    // Create Course
    public function create($course_id = null)
    {
        if (TRUE)
        {
            $sectionModel = new SectionModel();
            
            $valid = $this->validate([
                'section_name'       => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $sectionModel->save([
                    'name' => $this->request->getVar('section_name'),
                    'description' => $this->request->getVar('section_description'),
                    'course_id' => $course_id,
                    'order' => 0,

                ]);
                return json_encode(['success' => 'تم إضافة قسم بنجاح']);
            }
        }
        else {
            return redirect()->to(base_url('Academic/LessonsController/index/'.$course_id));
        }
    }

    // Update Course
    public function update($id = null, $course_id = null)
    {
        if (TRUE)
        {
            $sectionModel = new SectionModel();
            
            $valid = $this->validate([
                'section_name'       => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $sectionModel->update($id, [
                    'name' => $this->request->getVar('section_name'),
                    'description' => $this->request->getVar('section_description'),
                    'order' => 0,
                ]);
                
                return json_encode(['success' => 'تم تحديث البيانات بنجاح']);
            }
        }
        else {
            return redirect()->to(base_url('Academic/LessonsController/index/'.$course_id));
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
    public function getData($id)
    {
        $sectionModel = new SectionModel();
        $data = $sectionModel->find($id);

        return json_encode(['result' => $data]);
    }
}