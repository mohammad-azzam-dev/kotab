<?php namespace App\Models\Academic\G03;

use CodeIgniter\Model;

class ClassStatusHistoryModel extends Model
{
    protected $table      = 'g03_class_status_history';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['class_id', 'status', 'changed_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}