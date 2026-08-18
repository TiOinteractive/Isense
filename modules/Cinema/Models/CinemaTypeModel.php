<?php

namespace Modules\Cinema\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class CinemaTypeModel extends Model{

    protected $table = 'cinema_type';
    
    protected $allowedFields = [
        'publish',
        'slugs',
        'edited_at',
        'created_at',
    ];
    
    public function getTypeById($id, $id_lang) 
    {
        $type = $this->where('id', $id)->first();
        if(!empty($type)) {
            $type['lang'] = $this->getTypeLang($id);
            if(!empty($type['lang']) && !empty($type['lang'][$id_lang]) && !empty($type['lang'][$id_lang]['name'])) {
                $type['name'] = $type['lang'][$id_lang]['name'];
            } else {
                $type['name'] = '';
            }
        }
        return $type;
    }
    
    private function getTypeLang($id_type) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('cinema_type_lang')->where('id_type', $id_type)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveType($id, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;
        $data = array(
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
            'slugs' => $post['slugs'],
        );
        $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        
        $this->saveTypeLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveTypeLang($id_type, $lang_data) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Cinema')->get()->getRowArray();
            $linkClass = new Link();
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_type' => $id_type,
                    'id_lang' => $id_lang,
                    'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0),
                    'name' => $lang['name'],
                    'content' => $lang['content'],
                );
                $lang = $this->db->table('cinema_type_lang')->select('id')->where('id_type', $id_type)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('cinema_type_lang')->set($data)->where('id_type', $id_type)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('cinema_type_lang')->insert($data);
                }
            }
        }
    }
    
    public function getTypesForList($id_lang, $simple=false) 
    {
        $types = array();
        $list = $this->db->table('cinema_type ct')->join('cinema_type_lang ctl', 'ct.id=ctl.id_type')->select('ct.id,ct.slugs,ctl.name')->where('ct.publish', 1)->where('ctl.id_lang', $id_lang)->orderBy('ctl.name ASC')->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                if($simple) {
                    $types[$l['id']] = $l['name'];
                } else {
                    $types[$l['id']] = $l;
                }
            }
        }
        return $types;
    }
    
    public function deleteType($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('cinema_type_lang')->select('id_link')->where('id_type', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $this->db->table('cinema_type_lang')->where('id_type', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    
    
}