<?php namespace App\Models\Academic;

use CodeIgniter\Model;

class CompletedLessonModel extends Model
{
    protected $table      = 'ac_completed_lessons';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['class_id', 'lesson_id', 'report_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}