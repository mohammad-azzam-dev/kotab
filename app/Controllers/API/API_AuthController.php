<?php namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserRolesModel;
use App\Models\APITokenModel;

class API_AuthController extends BaseController
{
	// API Login
	public function login()
	{
        // Check If Correct Form Is Submitted
        if (!isset($_POST['login'])) return json_encode(["error" => "No Form Was Submitted."]);

        $userModel = new UserModel();

        // Form Input Validation
        $valid = $this->validate([
            'email'     => 'required|trim',
            'password'  => 'required|min_length[8]',
        ]);

        if (!$valid)
        {
            $errors = array();
            if (!empty($this->validator->getErrors()))
            {
                foreach ($this->validator->getErrors() as $error)
                {
                    $errors[] = $error;
                }
                return json_encode(["error" => $errors]);
            }
            return json_encode(["error" => array("خطأ غير معروف، يرجى معاود المحاولة لاحقا")], JSON_UNESCAPED_UNICODE);
        }
        
        
        

        // Data Validation
        $user = $userModel->where('email', $this->request->getVar('email'))->first();

        // Exit If Email Address Not Found
        if (empty($user)) return json_encode(["error" => "No valid data with this email!"]);

        // Check Password
        if (!password_verify($this->request->getVar('password'), $user['password']))
            return json_encode(["error" => "Password is incorrect :("]);
        

        // Create Token
        $token = md5(uniqid(rand(), true));
        $apiTokenModel = new APITokenModel();
        $apiTokenModel->save([
            'user_id'         => $user['id'],
            'token'           => $token,
            'expiration_date' => date("Y-m-d h:i:s")
        ]);


        // Send Data If Email & Password Are Correct
        $data = array(
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
            'id'         => $user['id'],
            'logged_in'  => TRUE,
            'password_changed_at' => $user['password_changed_at'],
            'token' => $token,
        );
        return json_encode($data, JSON_UNESCAPED_UNICODE);



        /*// Raw Data Input (Not form-data)
        $postdata = file_get_contents("php://input");
        $_POST = json_decode($postdata, true);
        
        // Check If Correct Form Is Submitted
        if (!isset($_POST['login'])) return json_encode(["error" => "No Form Was Submitted."]);
        
        $userModel = new UserModel();

        // Form Input Validation
        /*$valid = $this->validate([
            'email'     => 'required|trim',
            'password'  => 'required|min_length[8]',
        ]);

        if (!$valid) return json_encode($this->validator->getErrors());*/
        /*
        // Data Validation
        $user = $userModel->where('email', $_POST['email'])->first();

        // Exit If Email Address Not Found
        if (empty($user)) return json_encode(["error" => "No valid data with this email!"]);

        // Check Password
        if (!password_verify($_POST['password'], $user['password']))
            return json_encode(["error" => "Password is incorrect : ("]);
        
        // Send Data If Email & Password Are Correct
        $data = array(
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
            'id'         => $user['id'],
            'logged_in'  => TRUE,
            'password_changed_at' => $user['password_changed_at'],
        );
        return json_encode($data, JSON_UNESCAPED_UNICODE);*/
    }

	// Change Password
	public function change_password()
	{
        // Check If Correct Form Is Submitted
        if (!isset($_POST['change_password'])) return json_encode(["error" => "No Form Was Submitted."]);
        
        $userModel = new UserModel();

        // Form Input Validation
        $valid = $this->validate([
            'user_id'              => 'required|greater_than[0]',
            'current_password'     => 'required|min_length[8]',
            'new_password'         => 'required|min_length[8]',
            'confirm_new_password' => 'required|min_length[8]|matches[new_password]',
        ]);

        if (!$valid) return json_encode($this->validator->getErrors());

        // Get User Data
        $user_id = $this->request->getVar('user_id');
        $user = $userModel->find($user_id);
        
        // Check If User Exist
        if (empty($user))  return json_encode(["error" => "No valid data for this user!"]);
        
        // Check Current Password
        if (!password_verify($this->request->getVar('current_password'), $user['password']))
            return json_encode(["error" => "Current Password is incorrect : ("]);

        // Check If Current & New Passwords Are Different
        if ($this->request->getVar('current_password') == $this->request->getVar('new_password'))
            return json_encode(["error" => "Current & New Passwords Must Be Different."]);

        // Update Password
        $userModel->update($user_id, [
            'password'            => password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT),
            'password_changed_at' => date("Y-m-d h:i:s"),
        ]);

        return json_encode(['password_changed_at' => date("Y-m-d h:i:s")]);
    }
}
