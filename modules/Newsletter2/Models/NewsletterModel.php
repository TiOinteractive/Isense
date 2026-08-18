<?php

namespace Modules\Newsletter\Models;  
use CodeIgniter\Model;

class NewsletterModel extends Model{

    protected $table = 'newsletter';
    
    protected $allowedFields = [
        'order',
        'publish',
        'edited_at',
        'created_at'
    ];
    
    public function getGroupById($id) 
    {
        $group = $this->db->table('newsletter_group')->where('id', $id)->get()->getRowArray();
        if(!empty($group)) {
            $group['lang'] = $this->getGroupLang($id);
        }
        return $group;
    }
    
    public function getGroupLang($id) 
    {
        $langs = array();
        $data = $this->db->table('newsletter_group_lang')->where('id_group', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = array(
                    'name' => $d['name'],
                    'description' => $d['description'],
                    'success_msg' => $d['success_msg'],
                    'error_msg' => $d['error_msg'],
                );
            }
        }
        return $langs;
    }
    
    public function saveGroup($id, $post) 
    {
        if(empty($post)) return false;
        $data = array(
            'publish' => !empty($post['publish']) ? $post['publish'] : 0
        );
        $this->db->transStart();
        if($id) {
            $result = $this->db->table('newsletter_group')->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->db->table('newsletter_group')->insert($data);
            $this->id = $this->db->insertID();
        }
        $this->saveGroupLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveGroupLang($id_group, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_group' => $id_group,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                    'description' => $lang['description'],
                    'success_msg' => $lang['success_msg'],
                    'error_msg' => $lang['error_msg'],
                );
                $slider = $this->db->table('newsletter_group_lang')->select('id')->where('id_group', $id_group)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($slider) && !empty($slider['id'])) {
                    $result = $this->db->table('newsletter_group_lang')->set($data)->where('id_group', $id_group)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('newsletter_group_lang')->insert($data);
                }
            }
        }
    }
    
    public function deleteGroup($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->db->table('newsletter_group_lang')->where('id_group', $id)->delete();
        $this->db->table('newsletter_group')->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getEmailById($id) 
    {
        $email = $this->db->table('newsletter_email')->where('id', $id)->get()->getRowArray();
        return $email;
    }
    
    public function saveEmail($id, $post) 
    {
         helper('text');
        if(empty($post)) return false;
        $data = array(
            'id_group' => $post['id_group'],
            'email' => $post['email'],
            'name' => $post['name'],
            'surname' => $post['surname'],
            'agreement' => !empty($post['agreement']) ? $post['agreement'] : 0,
            'active' => !empty($post['active']) ? $post['active'] : 0
        );
        $this->db->transStart();
        if($id) {
            $result = $this->db->table('newsletter_email')->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $hash = random_string('sha1');
            $count = 1;
            do {
                $is = $this->db->table('newsletter_email')->select('id')->where('hash', $hash)->get()->getRowArray();
                if(!empty($is)) {
                    $hash = random_string('sha1');
                }
                ++$count;
            } while(!empty($is) && $count<=1000);
            $data['hash'] = $hash;
            $data['hash_valid'] = null;
            $result = $this->db->table('newsletter_email')->insert($data);
            $this->id = $this->db->insertID();
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function deleteEmail($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->db->table('newsletter_email')->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}