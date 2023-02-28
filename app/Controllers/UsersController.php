<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRolesModel;
use App\Models\RoleParentsModel;
// Achievements
use App\Models\Academic\AchievementModel;
use App\Models\Academic\AchievementCategoryModel;
use App\Models\Academic\CompletedAchievementModel;
use App\Models\Academic\AchievementHeaderModel;

class UsersController extends ResourceController
{

    protected $userModel = 'App\Models\UserModel';
    protected $format    = 'json';

    // Users Lists
    public function index()
    {
        $data['page_title'] = 'المستخدمين';
        $data['page_path'] = 'dashboard/users/index';
        $data['modals_path'] = 'dashboard/users/modals';
        $data['scripts_path'] = 'dashboard/users/scripts';

        $roleModel = new RoleModel();
        $data['roles'] = $roleModel->findAll();

        $data['parents'] = $this->get_parents_data();

        return view('backend/index', $data);
    }

    // Create
    public function create()
    {
        if (TRUE)
        {
            $userModel = new UserModel();
            
            $valid = $this->validate([
                'first_name'       => 'required|trim',
                'last_name'        => 'required|trim',
                'roles'            => 'required',
                /*'email'            => 'required|trim|is_unique[users.email]',
                'password'         => 'required|min_length[8]',
                'confirm_password' => 'required|matches[password]'*/
            ]);
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $userModel->save([
                    'id_code'    => strtotime(date("Y-m-d h:i:s")),
                    'first_name' => $this->request->getVar('first_name'),
                    'last_name'  => mb_substr($this->request->getVar('last_name'), 0, 2),
                    'email'      => $this->request->getVar('email'),
                    'password'   => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
                ]);

                $db = \Config\Database::connect();
                $db_ar = (array)$db;
                $user_id = $db_ar['mysqli']->insert_id;
                foreach ($this->request->getVar('roles') as $role)
                {
                    if ($role != '')
                    {
                        $role = (int)$role;
                        assignRole($user_id, $role);
                    }
                }
                
                return json_encode(['success' => 'Data added successfully.']);
            }
        }
        else {
            return redirect()->to(base_url('dashboard/users'));
        }
    }

    // Show
    public function show($id = null)
    {
        $data['page_title'] = 'الملف الشخصي';
        $data['page_path'] = 'dashboard/users/show';
        /*$data['modals_path'] = 'dashboard/users/modals';
        $data['scripts_path'] = 'dashboard/users/scripts';*/

        $userModel = new UserModel();
        $data['user'] = $userModel->find($id);

        $data['role_achievements'] = $this->get_user_achievements($id);

        return view('backend/index', $data);
    }

    // Get Data To Update It Later
    public function edit($id = null)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        
        $userRolesModel = new UserRolesModel();
        $roles = $userRolesModel->where('user_id', $user['id'])->findAll();

        $user['roles'] = $roles;

        return json_encode(['result' => $user]);
    }

    // Update
    public function update($id = null)
    {
        if( TRUE)
        {
            $userModel = new UserModel();
            
            $valid = $this->validate([
                'first_name'       => 'required|trim',
                'last_name'        => 'required|trim',
                'roles'            => 'required',
                //'email'            => 'required|trim',
            ]);
        
            if (!$valid)
            {
                $data['validation'] = $this->validator;
                return json_encode(['errors' => $data['validation']->listErrors()]);
            }
            else
            {
                $userModel->update($id, [
                    'first_name' => $this->request->getVar('first_name'),
                    'last_name' => mb_substr($this->request->getVar('last_name'), 0, 2),
                    'email'  => $this->request->getVar('email'),
                ]);
                if ($this->request->getVar('password') != '') {
                    $userModel->update($id, [
                        'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
                    ]);
                }

                        
                $userRolesModel = new UserRolesModel();
                $roles = $userRolesModel->where('user_id', $id)->findAll();
                foreach ($roles as $role)
                {
                    $userRolesModel->delete($role['id']);
                }
                foreach ($this->request->getVar('roles') as $role)
                {
                    if ($role != '')
                    {
                        $role = (int)$role;
                        assignRole($id, $role);
                    }
                }
                return json_encode(['success' => 'Data updated successfully.']);
            }
        }
        else {
            return redirect()->to(base_url('dashboard/users'));
        }
    }

    // Delete
    public function delete($id = null)
    {
        $userModel = new UserModel();
        $userModel->delete($id);
        
        $message = array(
            'success' => 'لقد تمت العملية بنجاح'
        ); 
        session()->setFlashdata($message);
        return redirect()->to(base_url('dashboard/users'));
    }

    // DataTable
    public function dataTable()
    {
        // Datatables Variables
        $draw = intval($this->request->getVar("draw"));
        $start = intval($this->request->getVar("start"));
        $length = intval($this->request->getVar("length"));



        $userModel = new UserModel();
        $users = $userModel->orderBy('created_at', 'DESC')->findAll();




        $data = array();



        foreach($users as $r) {

            // Update Button
            $actions  = '<button type="button" name="update" id="'.$r["id"].'" class="update-user btn btn-primary btn-sm btn-just-icon" data-toggle="modal" data-target="#create-update-user-modal"><i class="material-icons">edit</i></button>';
            $actions .= '&nbsp;&nbsp;&nbsp;';
            // Assign Parent
            if (isRole($r['id'], 'student'))
            {
                $actions .= '<button type="button" name="assign_parent_btn" id="'.$r["id"].'" class="assign-parent btn btn-warning btn-sm btn-just-icon" data-toggle="modal" data-target="#assign_parent_modal"><i class="material-icons">home</i></button>';
                $actions .= '&nbsp;&nbsp;&nbsp;';
            }
            // Delete Button
            $actions .= '<button type="button" name="delete" url="'.base_url("dashboard/users/delete").'/'.$r["id"].'" id="'.$r["id"].'" class="delete btn btn-danger btn-sm btn-just-icon" data-toggle="modal" data-target="#confirmModal"><i class="material-icons">delete</i></button>';
            
            $data[] = array(
                '', // Just for the table index
                $r['id_code'],
                '<a class="'. (isRole($r['id'], 'instructor') ? "text-danger" : "") .'" href="'.base_url("dashboard/users/show/".$r["id"]).'">'.$r['first_name'].' '.$r["last_name"].'</a>',
                $r['email'],
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                $actions
            );
        }



        $output = array(
            "draw" => $draw,
                "recordsTotal" => count($users),
                "recordsFiltered" => count($users),
                "data" => $data
            );
        echo json_encode($output);
        exit();
    }

    /* START: Get Other Data */

        // Get User Classes
        public function get_user_classes($user_id)
        {

        }

        // Get Parents Data
        public function get_parents_data()
        {
            $userModel = new UserModel();

            $userRolesModel = new UserRolesModel();
            $parentRoles = $userRolesModel->where('role_id', getRoleIdByName('parent'))->findAll();

            $parents_id = array();
            foreach ($parentRoles as $roleData)
            {
                $parents_id[] = $roleData['user_id'];
            }
            foreach ($parents_id as $id)
            {
                $userModel->whereIn('id', $id);
            }
            $parents_data = $userModel->findAll();

            return $parents_data;
        }

        // getUserParents
        public function getUserParents($child_id)
        {
            $roleParentsModel = new RoleParentsModel();
            $roleParentsModel_data = $roleParentsModel->where('child_id', $child_id)->findAll();

            $parents_id = array();
            foreach ($roleParentsModel_data as $row)
            {
                $parents_id[] = $row['parent_id'];
            }

            return json_encode(['parents_id' => $parents_id]);
        }
    /* END: Get Other Data */

    // Assign Parent To Students
    public function assign_parent($student_id)
    {
        if( TRUE)
        {
            $roleParentsModel = new RoleParentsModel();
            $roleParentsModel_data = $roleParentsModel->where('child_id', $student_id)->findAll();
            foreach ($roleParentsModel_data as $row)
            {
                $roleParentsModel->delete($row['id']);
            }
            foreach ($this->request->getVar('parents') as $parent_id)
            {
                if ($parent_id != '')
                {
                    $roleParentsModel->save([
                        'parent_id' => $parent_id,
                        'child_id' => $student_id
                    ]);
                }
            }
            return json_encode(['success' => 'Data updated successfully.']);
        
        }
        else {
            return redirect()->to(base_url('dashboard/users'));
        }
    }


    /* START: Achievements */
        // Get User Achievements
        public function get_user_achievements($user_id)
        {
            $achievementModel          = new AchievementModel();
            $achievementCategoryModel  = new AchievementCategoryModel();
            $achievementHeaderModel    = new AchievementHeaderModel();
            $completedAchievementModel = new CompletedAchievementModel();
            $userRolesModel            = new UserRolesModel();

            // Roles Achievements
            $roles_achievements = array();
            $user_roles = $userRolesModel->where('user_id', $user_id)->findAll();
            $categories_temp = $achievementCategoryModel->where('type', 'role')->findAll();
             
            $achievements = array();
            foreach ($categories_temp as $category)
            {
                $assigned_ids = json_decode($category['assigned_ids']);
                foreach ($assigned_ids as $role_id)
                {
                    foreach ($user_roles as $user_role)
                    {
                        if ($user_role['role_id'] == $role_id)
                        {
                            // Get Headers
                            $cat_headers = $achievementHeaderModel->where('category_id', $category['id'])->orderBy('order', 'ASC')->findAll();
                            // Get headers ids by order
                            $achievements[$category['id']]['headers'] = $cat_headers;


                            $row_code = 0;
                            $achievements_temp = $achievementModel->where('category_id', $category['id'])->orderBy('row_code', 'ASC')->findAll();

                            foreach ($achievements_temp as $achievement)
                            {
                                if ($row_code != $achievement['row_code'])
                                {
                                    $row_code = $achievement['row_code'];
                                }
                                $achievements[$category['id']][$row_code][] = $achievement;
                            }

                            //print_r($achievements); exit();


                            /*$completed_achievement = $completedAchievementModel->where('achievement_id', $category['id'])->where('user_id', $user_id)->findAll();
                            if (!empty($completed_achievement))
                            {
                                $achiev['status'] = 1;
                            }
                            else
                            {
                                $achiev['status'] = 0;
                            }
                            array_push($roles_achievements, $category);*/
                        }
                    }
                }
            }

            return $achievements;

            /*$data['role_achievements']
            $data['user_achievements']*/
        }

        public function store_completed_achievements($user_id)
        {
            // Save Completed Achievements
            $completedAchievementModel = new CompletedAchievementModel();
            if (isset($_POST['achievements']) && isset($_POST['role_achievements']))
            {
                foreach ($_POST['role_achievements'] as $compl_achievement_id)
                {
                    $data = [
                        'achievement_id'  => $compl_achievement_id,
                        'user_id' => $user_id,
                    ];
                    $completedAchievementModel->save($data);
                }
            }
        }
    /* END: Achievements */
}