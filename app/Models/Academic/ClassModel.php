<?php namespace App\Models\Academic;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table      = 'ac_classes';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['name', 'major_id', 'course_id', 'instructor_id', 'place_id', 'date_id', 'time', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}