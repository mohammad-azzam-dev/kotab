<?php namespace App\Controllers;

class GeneralController extends BaseController
{

    // Under Maintenance
    public function under_maintenance()
    {
        return view('under_maintenance/index');
    }
}