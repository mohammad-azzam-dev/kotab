<?php namespace App\Controllers\Academic;

use CodeIgniter\RESTful\ResourceController;
// Achievements
use App\Models\Academic\AchievementModel;
use App\Models\Academic\AchievementCategoryModel;
use App\Models\Academic\CompletedAchievementModel;
use App\Models\Academic\AchievementHeaderModel;

class AchievementsController extends ResourceController
{
    protected $format    = 'json';

    // index
    public function achievements_list($category_id)
    {
        $data['page_title'] = 'الإنجازات';
        $data['page_path'] = 'dashboard/achievements/index';
        $data['modals_path'] = 'dashboard/achievements/modals';
        $data['scripts_path'] = 'dashboard/achievements/scripts';

        $data['category_id'] = $category_id;

        $data['achievements_data'] = $this->get_achievements_by_category($category_id);
        

        return view('backend/index', $data);
    }

    // Create Achievement
    public function create($parent_category_id = null, $sub_category_id = null)
    {
        if(TRUE)
        {
            $achievementModel = new AchievementModel();
            $completedAchievementModel = new CompletedAchievementModel();
            
            $valid = $this->validate([
                //'achievement_name'       => 'required|trim',
            ]);
        
            $valid = true;

            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $achievementHeaderModel    = new AchievementHeaderModel();

                // Get Headers
                $cat_headers = $achievementHeaderModel->where('category_id', $sub_category_id)->orderBy('order', 'ASC')->findAll();
                // Row Code
                $row_code = strtotime(date("Y-m-d h:i:s"));

                foreach ($cat_headers as $header)
                {
                    $achievementModel->save([
                        'name'            => $this->request->getVar('achievement_header_'.$header['id']),
                        'category_id'     => $sub_category_id,
                        'header_field_id' => $header['id'],
                        'row_code'        => $row_code,
                    ]);
                }
            }
        }
        return redirect()->to(base_url('academic/AchievementsController/achievements_list/'.$parent_category_id));
    }

    // Update Course
    public function update($parent_category_id = null, $sub_category_id = null, $row_code = null)
    {
        if(TRUE)
        {
            $achievementModel = new AchievementModel();
            $completedAchievementModel = new CompletedAchievementModel();
            
            $valid = $this->validate([
                //'achievement_name'       => 'required|trim',
            ]);
        
            $valid = true;

            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $achievements = $achievementModel->where('row_code', $row_code)->findAll();

                // Get Headers
                $achievementHeaderModel = new AchievementHeaderModel();
                $cat_headers = $achievementHeaderModel->where('category_id', $sub_category_id)->orderBy('order', 'ASC')->findAll();

                
                foreach ($achievements as $achievement)
                {
                    foreach ($cat_headers as $header)
                    {
                        if ($achievement['header_field_id'] == $header['id'])
                        {
                            $achievementModel->update($achievement['id'], [
                                'name' => $this->request->getVar('achievement_header_'.$header['id']),
                            ]);
                        }
                    }
                }
            }
        }
        return redirect()->to(base_url('academic/AchievementsController/achievements_list/'.$parent_category_id));
    }

    // Delete
    public function delete($row_code = null, $parent_category_id = null)
    {
        $achievementModel = new AchievementModel();
        $achievements = $achievementModel->where('row_code', $row_code)->findAll();
        foreach ($achievements as $achievement)
        {
            $achievementModel->delete($achievement['id']);
        }

        return redirect()->to(base_url('Academic/AchievementsController/achievements_list/'.$parent_category_id));
    }

    // Get Data
    public function edit($row_code = null)
    {
        $achievementModel = new AchievementModel();
        $achievements = $achievementModel->where('row_code', $row_code)->findAll();

        return json_encode(['result' => $achievements]);
    }

    // Get Achievements By Category
    public function get_achievements_by_category($category_id)
    {
        $achievementModel          = new AchievementModel();
        $achievementCategoryModel  = new AchievementCategoryModel();
        $achievementHeaderModel    = new AchievementHeaderModel();
        $completedAchievementModel = new CompletedAchievementModel();

        $category_data = array();
        
        // Category Data
        $category_data['category'] = $achievementCategoryModel->find($category_id);
               
        // Sub Categories
        $sub_categories = $achievementCategoryModel->where('parent_category_id', $category_id)->findAll();
        $category_data['sub_categories'] = $sub_categories;

        foreach ($sub_categories as $sub_category)
        {
            // Get Headers
            $cat_headers = $achievementHeaderModel->where('category_id', $sub_category['id'])->orderBy('order', 'ASC')->findAll();
            // Get headers ids by order
            $category_data[$sub_category['id']]['headers'] = $cat_headers;
    
    
            $row_code = 0;
            $achievements_temp = $achievementModel->where('category_id', $sub_category['id'])->orderBy('row_code', 'ASC')->findAll();
    
            foreach ($achievements_temp as $achievement)
            {
                if ($row_code != $achievement['row_code'])
                {
                    $row_code = $achievement['row_code'];
                }
                $category_data[$sub_category['id']]['achievements'][$row_code][] = $achievement;
            }
        }

        // cat name
        // headers
        // achiev by headers orders
        // achiev by orders  

        return $category_data;

        /*$data['role_achievements']
        $data['user_achievements']*/
    }
}