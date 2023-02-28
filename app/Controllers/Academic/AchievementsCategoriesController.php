<?php namespace App\Controllers\Academic;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Academic\AchievementModel;
use App\Models\Academic\AchievementCategoryModel;
use App\Models\Academic\AchievementHeaderModel;
use App\Models\Academic\ComletedAchievementModel;

class AchievementsCategoriesController extends ResourceController
{
    protected $format    = 'json';

    // index
    public function index()
    {
        $data['page_title'] = 'فئات الإنجازات';
        $data['page_path'] = 'dashboard/achievements_categories/index';
        $data['modals_path'] = 'dashboard/achievements_categories/modals';
        $data['scripts_path'] = 'dashboard/achievements_categories/scripts';

        $achievementCategoryModel = new AchievementCategoryModel();
        $data['achievementsCategories'] = $achievementCategoryModel->where('parent_category_id', 0)->findAll();


        return view('backend/index', $data);
    }

    // Create
    public function create($parent_category_id = null)
    {
        if(TRUE)
        {
            $achievementCategoryModel = new AchievementCategoryModel();
            
            $valid = $this->validate([
                'achievement_category_name' => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $validation = $this->validator;
                $message = array(
                    'error' => $validation->listErrors()
                ); 
                session()->setFlashdata($message);
            }
            else
            {
                $assigned_ids = array(getRoleIdByName('student'));

                if ($parent_category_id == null)
                {
                    $achievementCategoryModel->save([
                        'name'        => $this->request->getVar('achievement_category_name'),
                        'description' => $this->request->getVar('achievement_category_description'),
                        'parent_category_id' => 0,
                        'type' => 'role',
                        'assigned_ids' => json_encode($assigned_ids),
                        'color' => $this->request->getVar('achievement_category_color'),
                        'icon' => 'fas fa-square',
                    ]);

                    $message = array(
                        'success' => 'لقد تمت العملية بنجاح'
                    ); 
                    session()->setFlashdata($message);
                }
                elseif ($parent_category_id > 0)
                {
                    $achievementCategoryModel->save([
                        'name'        => $this->request->getVar('achievement_category_name'),
                        'description' => $this->request->getVar('achievement_category_description'),
                        'parent_category_id' => $parent_category_id,
                        'type' => 'same as parent',
                        'assigned_ids' => 'same as parent',
                        'color' => 'same as parent',
                        'icon' => 'same as parent',
                    ]);

                    $message = array(
                        'success' => 'لقد تمت العملية بنجاح'
                    ); 
                    session()->setFlashdata($message);
                }
                else
                {
                    echo "Hacking :("; exit();
                }
            }
        }
        
        if ($parent_category_id == null)
        {
            return redirect()->to(base_url('Academic/AchievementscategoriesController'));
        }
        elseif ($parent_category_id > 0)
        {
            return redirect()->to(base_url('Academic/AchievementsController/achievements_list/'.$parent_category_id));
        }
    }

    // Update
    public function update($id = null, $parent_category_id = null)
    {
        if(TRUE)
        {
            $achievementCategoryModel = new AchievementCategoryModel();
            
            $valid = $this->validate([
                'achievement_category_name' => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $validation = $this->validator;
                $message = array(
                    'error' => $validation->listErrors()
                ); 
                session()->setFlashdata($message);
            }
            else
            {
                if ($parent_category_id == null)
                {
                    $assigned_ids = array(getRoleIdByName('student'));
                    
                    $achievementCategoryModel->update($id, [
                        'name'        => $this->request->getVar('achievement_category_name'),
                        'description' => $this->request->getVar('achievement_category_description'),
                        'parent_category_id' => 0,
                        'type' => 'role',
                        'assigned_ids' => json_encode($assigned_ids),
                        'color' => $this->request->getVar('achievement_category_color'),
                        'icon' => 'fas fa-square',
                    ]);

                    $message = array(
                        'success' => 'لقد تمت العملية بنجاح'
                    ); 
                    session()->setFlashdata($message);
                }
                elseif ($parent_category_id > 0)
                {
                    $achievementCategoryModel->update($id, [
                        'name'        => $this->request->getVar('achievement_category_name'),
                        'description' => $this->request->getVar('achievement_category_description'),
                    ]);

                    $message = array(
                        'success' => 'لقد تمت العملية بنجاح'
                    ); 
                    session()->setFlashdata($message);
                }
                else
                {
                    echo "Hacking :("; exit();
                }
            }
        }
        
        if ($parent_category_id == null)
        {
            return redirect()->to(base_url('Academic/AchievementscategoriesController'));
        }
        elseif ($parent_category_id > 0)
        {
            return redirect()->to(base_url('Academic/AchievementsController/achievements_list/'.$parent_category_id));
        }
    }

    // Delete
    public function delete($id = null)
    {
        $achievementModel = new AchievementModel();
        $achievementCategoryModel = new AchievementCategoryModel();
        $achievementHeaderModel = new AchievementHeaderModel();

        // Delete All Headers Coresponding To This Category
        $headers_data = $achievementHeaderModel->where('category_id', $id)->findAll();
        if (!empty($headers_data))
        {
            $headers_ids = array();
            foreach ($headers_data as $header)
            {
                $headers_ids[] = $header['id'];
            }
            $achievementHeaderModel->delete($headers_ids);
        }

        // Delete All Achievements Coresponding To This Category
        $achievements_data = $achievementModel->where('category_id', $id)->findAll();
        if (!empty($achievements_data))
        {
            $achievements_ids = array();
            foreach ($achievements_data as $achievement)
            {
                $achievements_ids[] = $achievement['id'];
            }
            $achievementModel->delete($achievements_ids);
        }

        // Check If It Is a Sub-Category Or Not
        $category_data = $achievementCategoryModel->find($id);
        if ($category_data['parent_category_id'] > 0)
        {
            $parent_category_id = $category_data['parent_category_id'];
        }

        // Delete Category
        $achievementCategoryModel->delete($id);
        

        $message = array(
            'success' => 'لقد تمت العملية بنجاح'
        ); 
        session()->setFlashdata($message);

        if ($category_data['parent_category_id'] > 0)
        {
            return redirect()->to(base_url('Academic/AchievementsController/achievements_list/'.$category_data['parent_category_id']));
        }
        else
        {
            return redirect()->to(base_url('Academic/AchievementsCategoriesController'));
        }
    }

    // Get Data
    public function edit($id = null)
    {
        $achievementCategoryModel = new AchievementCategoryModel();
        $achievementsCategory = $achievementCategoryModel->find($id);

        return json_encode(['result' => $achievementsCategory]);
    }

    // Get Header Data
    function get_header_data($sub_category_id)
    {
        $achievementHeaderModel = new AchievementHeaderModel();
        $header_data = $achievementHeaderModel->where('category_id', $sub_category_id)->orderBy('order', 'ASC')->findAll();

        return json_encode(['result' => $header_data]);
    }
}