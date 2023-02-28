<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserRolesModel;

class AuthController extends BaseController
{
    function __construct()
    {
		helper(['form', 'url']);
    }

	// Login
	public function login()
	{
		$data['page_title'] = 'تسجيل الدخول';
		$data['page_path'] = 'forms/login';

		if (isset($_POST['login']))
		{
			$userModel = new UserModel();

            // Form Input Validation
			$valid = $this->validate([
				'email'     => 'required|trim',
				'password'  => 'required|min_length[8]',
			]);
	
			if (!$valid)
			{
				$data['validation'] = $this->validator;
				return view('auth/index', $data);
			}
			else
			{
				$user = $userModel->where('email', $this->request->getVar('email'))->first();
				if (empty($user))
				{
					$data['error'] = 'No valid data with this email!';
				}
				else if (password_verify($this->request->getVar('password'), $user['password']))
				{
					// Success
					$sessionData = [
						'first_name' => $user['first_name'],
						'last_name'  => $user['last_name'],
						'email'      => $user['email'],
						'id'         => $user['id'],
						'logged_in'  => TRUE,
						'password_changed_at' => $user['password_changed_at'],
					];
					$this->session->set($sessionData);

					$message = array(
						'success' => 'مراحبا بك '.$user['first_name']. ' ' .$user['last_name']
					); 
					session()->setFlashdata($message);
					return redirect()->to(base_url('/dashboard'));
				}
				else
				{
					$data['error'] = 'Password is incorrect : (';
				}
			}
		}
		
		return view('auth/index', $data);
	}

	// Register
	public function register()
	{
	    echo 'No permission!!!'; exit();
		$data['page_title'] = 'تسجيل كعضو جديد';
		$data['page_path'] = 'forms/register';

		if( isset($_POST['register']) )
		{
			$userModel = new UserModel();
			 
			$valid = $this->validate([
				'first_name'       => 'required|trim',
				'last_name'        => 'required|trim',
				'email'            => 'required|trim|is_unique[users.email]',
				'password'         => 'required|min_length[8]|required',
				'confirm_password' => 'required|matches[password]'
			]);
	 
			if (!$valid)
			{
				$data['validation'] = $this->validator;
			}
			else
			{
				$userModel->save([
					'first_name' => $this->request->getVar('first_name'),
					'last_name' => mb_substr($this->request->getVar('last_name'), 0, 2),
					'email'  => $this->request->getVar('email'),
					'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
				]);
				$db = \Config\Database::connect();
				$db_ar = (array)$db;
				$user_id = $db_ar['mysqli']->insert_id;
				
				assignRole($user_id, 'student');

				// Success
				$sessionData = [
					'first_name' => $this->request->getVar('first_name'),
					'last_name'  => mb_substr($this->request->getVar('last_name'), 0, 2),
					'email'      => $this->request->getVar('email'),
					'id'         => $user_id,
					'logged_in'  => TRUE,
					'password_changed_at'  => null,
				];
				$this->session->set($sessionData);
				
				$message = array(
					'success' => 'مراحبا بك '.$this->request->getVar('first_name'). ' ' .mb_substr($this->request->getVar('last_name'), 0, 2)
				); 
				session()->setFlashdata($message);
				return redirect()->to(base_url('/dashboard'));
			}
		}

		return view('auth/index', $data);
	}

	// Logout
	public function logout() {
		$this->session->destroy();

		$message = array(
			'success' => 'لقد تم تسجيل الخروج بنجاح'
		); 
		session()->setFlashdata($message);
		return redirect()->to(base_url('/'));
	}

	// Change Password
	public function change_password()
	{
		$data['page_title'] = 'تغيير كلمة المرور';
		$data['page_path'] = 'forms/change_password';

		if (isset($_POST['change_password']))
		{
			$userModel = new UserModel();

			$valid = $this->validate([
				'current_password'     => 'required|min_length[8]',
				'new_password'         => 'required|min_length[8]',
				'confirm_new_password' => 'required|min_length[8]|matches[new_password]',
			]);
	
			if (!$valid)
			{
				$data['validation'] = $this->validator;
				return view('auth/index', $data);
			}
			else
			{
				$user = $userModel->find($this->session->get('id'));
				if (empty($user))
				{
					$data['error'] = 'No valid data for this user!';
				}
				else if (password_verify($this->request->getVar('current_password'), $user['password']))
				{
					if ($this->request->getVar('current_password') == $this->request->getVar('new_password'))
					{
						$data['error'] = 'Current & New Passwords Must Be Different.';
					}
					else
					{
						$userModel->update($this->session->get('id'), [
							'password'            => password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT),
							'password_changed_at' => date("Y-m-d h:i:s"),
						]);
						$sessionData = [
							'password_changed_at' => date("Y-m-d h:i:s"),
						];
						$this->session->set($sessionData);
	
	
						$message = array(
							'success' => 'لقد تم تغيير كلمة المرور بنجاح'
						); 
						session()->setFlashdata($message);
						return redirect()->to(base_url('/dashboard'));
					}
				}
				else
				{
					$data['error'] = 'Current Password is incorrect : (';
				}
			}
		}
		
		return view('auth/index', $data);
	}
}
