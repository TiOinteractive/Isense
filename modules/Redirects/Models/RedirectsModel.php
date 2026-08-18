<?php

namespace Modules\Redirects\Models;  
use CodeIgniter\Model;

class RedirectsModel extends Model{

    protected $table = 'redirects';
    
    protected $allowedFields = [
        'from',
        'to',
        'type',
        'publish',
        'short',
        'group',
        'edited_at',
        'created_at',
    ];
    
    
    public function getRedirectById($id) 
    {
        $redirect = $this->where('id', $id)->first();
        return $redirect;
    }
    
    public function checkRedirectExist($id, $post) 
    {
        $errors = array();
        if($post['from'] == $post['to']) {
            $errors[] = lang('Redirects.errors.Loop');
            return $errors;
        }
        $this->select('id')->where('from', $post['from'])->where('to', $post['to']);
        if($id) {
            $this->where('id!=', $id);
        }
        $is = $this->first();
        if(!empty($is)) {
            $errors[] = lang('Redirects.errors.Exists');
            return $errors;
        }
        $loop = $this->select('id')->where('to', $post['from'])->where('from', $post['to'])->first();
        if(!empty($loop)){
            $errors[] = lang('Redirects.errors.Loop');
            return $errors;
        }
        return $errors;
    }
    
    public function saveRedirect($id, $post) 
    {
        if(empty($post)) return false;
        $data = array(
            'from' => $post['from'],
            'to' => $post['to'],
            'type' => $post['type'],
            'group' => $post['group'],
            'short' => !empty($post['short']) ? $post['short'] : 0,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
        );
        $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function deleteRedirect($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function checkLinkFrom($link) 
    {
        return $this->select('id')->where('from', $link)->first();
    }
}