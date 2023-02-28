<?php namespace App\Models\Academic;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table      = 'ac_courses';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['name', 'code_number', 'majors_id', 'description', 'number_of_credits'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}