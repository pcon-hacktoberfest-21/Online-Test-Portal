<?php namespace App\Models;
use CodeIgniter\Model;

class TestModal extends Model
{
    protected $table      = 'test';
    protected $primaryKey = 'sl';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['admin', 'subject', 'isPublic', 'test_id', 'test_name', 'sdatetime', 'edatetime', 'test_duration', 'attempts', 'show_result', 'created', 'isActive', 'test_for', 'solution', 'nitOnly', 'password'];
    protected $useTimestamps = false;
    protected $createdField  = 'created';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}