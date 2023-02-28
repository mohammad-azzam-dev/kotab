<?php namespace App\Controllers;

use App\Models\RoleModel;

class Home extends BaseController
{
	public function index()
	{
		$data['page_title'] = 'الصفحة الرئيسية';
		return view('frontend/index', $data);
	}

	//--------------------------------------------------------------------

	public function test_api()
	{
        $roleModel = new RoleModel();
        $roles = $roleModel->findAll();

		return json_encode($roles);
    }

}
