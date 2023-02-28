<?php namespace App\Models\Academic\G10;

use CodeIgniter\Model;

class RemoveStudentRequestsModel extends Model
{
    protected $table      = 'g10_remove_student_requests';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['class_id', 'requester_id', 'student_id', 'status', 'reason'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}