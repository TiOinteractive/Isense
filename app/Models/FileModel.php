<?php

namespace App\Models;  
use CodeIgniter\Model;

class FileModel extends Model{

    protected $table = 'files';
    
    protected $allowedFields = [
        'name',
        'basename',
        'path',
        'mime',
        'type',
        'ext',
        'publish'
    ];
    
    
}