<?php namespace App\Models\Academic;

use CodeIgniter\Model;

class EnrollementModel extends Model
{
    protected $table      = 'ac_enrollement';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['class_id', 'user_id', 'status', 'leave_at', 'leave_reason'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}