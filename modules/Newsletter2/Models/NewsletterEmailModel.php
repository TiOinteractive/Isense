<?php

namespace Modules\Newsletter\Models;  
use CodeIgniter\Model;

class NewsletterEmailModel extends Model{

    protected $table = 'newsletter_email';
    
    protected $allowedFields = [
        'id_group',
        'email',
        'name',
        'surname',
        'agreement',
        'hash',
        'hash_valid',
        'active',
        'edited_at',
        'created_at'
    ];
    
}