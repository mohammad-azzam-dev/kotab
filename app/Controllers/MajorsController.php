<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Academic\MajorModel;

class MajorsController extends ResourceController
{

        protected $majorModel = 'App\Models\MajorModel';
        protected $format    = 'json';

        // index
        public function index()
        {
            $data['page_title'] = 'الإختصاصات';
            $data['page_path'] = 'dashboard/majors/index';
            $data['modals_path'] = 'dashboard/majors/modals';
            $data['scripts_path'] = 'dashboard/majors/scripts';

            return view('backend/index', $data);
        }

        // Create Major
        public function create()
        {
            if( TRUE)
            {
                $majorModel = new MajorModel();
			 
                $valid = $this->validate([
                    'major_name' => 'required|trim'
                ]);
         
                if (!$valid)
                {
                    $data['validation'] = $this->validator;
                    return json_encode(['errors' => $data['validation']->listErrors()]);
                }
                else
                {
                    $majorModel->save([
                        'name' => $this->request->getVar('major_name'),
                        'code' => $this->request->getVar('major_code'),
                        'description'  => $this->request->getVar('major_description'),
                        /*'credit_price'  => $this->request->getVar('major_credit_price')*/
                    ]);
                    return json_encode(['success' => 'Data added successfully.']);
                }
            }
            else {
                return redirect()->to(base_url('MajorsController'));
            }
        }

        // Update Major
        public function update($id = null)
        {
            if( TRUE)
            {
                $majorModel = new MajorModel();
			 
                $valid = $this->validate([
                    'major_name' => 'required|trim'
                ]);
         
                if (!$valid)
                {
                    $data['validation'] = $this->validator;
                    return json_encode(['errors' => $data['validation']->listErrors()]);
                }
                else
                {
                    $majorModel->update($id, [
                        'name' => $this->request->getVar('major_name'),
                        'code' => $this->request->getVar('major_code'),
                        'description'  => $this->request->getVar('major_description'),
                        /*'credit_price'  => $this->request->getVar('major_credit_price')*/
                    ]);
                    
                    return json_encode(['success' => 'Data updated successfully.']);
                }
            }
            else {
                return redirect()->to(base_url('MajorsController'));
            }
        }

        // Delete Major
        public function delete($id = null)
        {
            $majorModel = new MajorModel();
            $majorModel->delete($id);
            
            $message = array(
                'success' => 'لقد تمت العملية بنجاح'
            ); 
            session()->setFlashdata($message);
            return redirect()->to(base_url('MajorsController'));
        }

        // Get Major Data
        public function getMajor($id)
        {
            $majorModel = new MajorModel();
            $major = $majorModel->find($id);

            return json_encode(['result' => $major]);
        }

        // DataTable
        public function majorsDataTable()
        {
            // Datatables Variables
            $draw = intval($this->request->getVar("draw"));
            $start = intval($this->request->getVar("start"));
            $length = intval($this->request->getVar("length"));


            $majorModel = new MajorModel();
            $majors = $majorModel->findAll();

            $data = array();

            foreach($majors as $r) {

                // Update Button
                $actions  = '<button type="button" name="update" id="'.$r["id"].'" class="update-major btn btn-primary btn-sm btn-just-icon" data-toggle="modal" data-target="#create-update-major-modal"><i class="material-icons">edit</i></button>';
                // Some Space Between Buttons
                $actions .= '&nbsp;&nbsp;&nbsp;';
                // Delete Button
                $actions .= '<button type="button" name="delete" url="'.base_url("MajorsController/delete").'/'.$r["id"].'" id="'.$r["id"].'" class="delete btn btn-danger btn-sm btn-just-icon" data-toggle="modal" data-target="#confirmModal"><i class="material-icons">delete</i></button>';
                
                $data[] = array(
                    '', // Just for the table index
                    $r['name'],
                    $r['code'],
                    $r['description'],
                    /*$r['credit_price'],*/
                    $actions
                );
            }

            $output = array(
                "draw" => $draw,
                    "recordsTotal" => count($majors),
                    "recordsFiltered" => count($majors),
                    "data" => $data
                );
            echo json_encode($output);
            exit();
        }
}