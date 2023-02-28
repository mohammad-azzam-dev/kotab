<?php namespace App\Models\Academic;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table      = 'ac_report';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['class_id', 'class_date', 'class_time', 'class_place', 'notes', 'did_not_hold', 'not_hold_reason'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}