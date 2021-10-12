<?php namespace App\Models;

use CodeIgniter\Model;

class AdminModal extends Model
{
    protected $table      = 'admin';
    protected $primaryKey = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['createdOn', 'email', 'name', 'picture'];
    protected $useTimestamps = false;
    protected $createdField  = 'createdOn';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

