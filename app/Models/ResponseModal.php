<?php namespace App\Models;

use CodeIgniter\Model;

class ResponseModal extends Model
{
    protected $table      = 'test_response';
    protected $primaryKey = 'sl';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['test_id', 'user_id', 'question_id', 'response','timestamp','status','marks'];
    protected $useTimestamps = false;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}			