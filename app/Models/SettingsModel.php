<?php

namespace App\Models;  
use CodeIgniter\Model;

class SettingsModel extends Model {

    protected $table = 'settings';
    
    protected $allowedFields = [
        'name',
        'value'
    ];
    
    
    public function saveSettings($post) 
    {
        $ids = array();
        if(!empty($post)) {
            $this->transStart();
            foreach($post as $name=>$value) {
                if(is_array($value)) {
                    if(in_array($name, array('meta_photo', 'logo', 'logo_dark', 'favicon', 'no_photo', 'favicon_flavor', 'logo_flavor', 'no_photo_flavor'))) {
                        $id_settings = $this->saveConf($name, $value['id']);
                    } else {
                        $id_settings = $this->saveConf($name);
                        $this->saveLang($id_settings, $value);
                    }
                } elseif(in_array($name, array('id_meta_photo', 'id_logo', 'id_logo_dark', 'id_favicon', 'id_no_photo', 'id_favicon_flavor', 'id_logo_flavor', 'id_no_photo_flavor'))) {
                    continue;
                } else {
                    $id_settings = $this->saveConf($name, $value);
                }
                $ids[] = $id_settings;
            }
            
            $query = $this->select('id')->notLike('name', 'url_%')->notLike('name', 'special_%');
            if(!empty($ids)) {
                $query->whereNotIn('id', $ids);
            }
            $list = $query->get()->getResultArray();
            if(!empty($list)) {
                foreach($list as $l) {
                    $this->db->table('settings_lang')->where('id_settings', $l['id'])->delete();
                    $this->where('id', $l['id'])->notLike('name', 'url_%')->notLike('name', 'special_%')->delete();
                }
            }
            $this->transComplete();
            return $this->transStatus();
        }
        return false;
    }
    
    private function saveConf($name, $value=null) 
    {
        $settings = $this->where('name', $name)->first();
        if(!empty($settings)) {
            $this->where('name', $name)->set('value', $value)->update();
            $id = $settings['id'];
        } else {
            $data = array(
                'name' => $name,
                'value' => $value
            );
            $this->insert($data);
            $id = $this->getInsertID();
        }
        return $id;
    }
    
    private function saveLang($id_settings, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_settings' => $id_settings,
                    'id_lang' => $id_lang,
                    'value' => $lang
                );
                if($this->db->table('settings_lang')->where('id_settings', $id_settings)->where('id_lang', $id_lang)->get()->getRowArray()) {
                    $this->db->table('settings_lang')->set($data)->where('id_settings', $id_settings)->where('id_lang', $id_lang)->update();
                } else {
                    $this->db->table('settings_lang')->insert($data);
                }
            }
        }
    }
    
    public function getAllSettings() 
    {
        $settings = array();
        $data = $this->findAll();
        if(!empty($data)) {
            foreach($data as $d) {
                if($d['value'] == null) {
                    $settings[$d['name']] = $this->getSettingsLang($d['id']);
                } elseif(in_array($d['name'], array('meta_photo', 'logo', 'logo_dark', 'favicon', 'no_photo', 'logo_flavor', 'favicon_flavor', 'no_photo_flavor'))) {
                    $settings[$d['name']] = $this->db->table('tio_files')->where('id', $d['value'])->limit(1)->get()->getRowArray();
                } else {
                    $settings[$d['name']] = $d['value'];
                }
            }
        }
        return $settings;
    }
    
    private function getSettingsLang($id) 
    {
        $langs = array();
        $data = $this->db->table('settings_lang')->select('value,id_lang')->where('id_settings', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d['value'];
            }
        }
        return $langs;
    }
    
    public function getSettings($id_lang) 
    {
        $settings = array();
        $list = $this->db->table('settings s')
                ->join('settings_lang sl', 's.id=sl.id_settings', 'left')
                ->select('s.name,s.value,sl.value as value2')
                ->groupStart()
                    ->where('sl.id_lang', $id_lang)
                    ->orWhere('sl.id_lang', null)
                ->groupEnd()
                ->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $k=>$l) {
                if(in_array($l['name'], array('meta_photo', 'logo', 'logo_dark', 'favicon', 'no_photo', 'favicon_flavor', 'logo_flavor', 'no_photo_flavor'))) {
                    $settings[$l['name']] = $this->db->table('tio_files')->select('name,basename,path,mime,ext')->where('id', $l['value'])->limit(1)->get()->getRowArray();
                } else {
                    $settings[$l['name']] = !empty($l['value2']) ? $l['value2'] : $l['value'];
                }
            }
        }
        return $settings;
    }
    
    public function getAllDirectLinks() 
    {
        $links = array();
        $data = $this->like('name', 'url_%')->findAll();
        if(!empty($data)) {
            foreach($data as $d) {
                if($d['value'] == null) {
                    $links[substr($d['name'], 4)] = $this->getSettingsLang($d['id']);
                }
            }
        }
        return $links;
    }
    
    public function saveDirectLinks($post) 
    {
        $ids = array();
        if(!empty($post)) {
            $this->transStart();
            foreach($post as $name=>$value) {
                if(is_array($value)) {
                    $id_settings = $this->saveConf('url_' . $name);
                    $this->saveLang($id_settings, $value);
                }
                $ids[] = $id_settings;
            }
            
            $query = $this->select('id')->like('name', 'url_%');
            if(!empty($ids)) {
                $query->whereNotIn('id', $ids);
            }
            $list = $query->get()->getResultArray();
            if(!empty($list)) {
                foreach($list as $l) {
                    $this->db->table('settings_lang')->where('id_settings', $l['id'])->delete();
                    $this->where('id', $l['id'])->like('name', 'url_%')->delete();
                }
            }
            $this->transComplete();
            return $this->transStatus();
        }
        return false;
    }
    
    public function getSpecialWebsites() 
    {
        $specials = array();
        $data = $this->like('name', 'special_%')->findAll();
        if(!empty($data)) {
            foreach($data as $d) {
                $specials[substr($d['name'], 8)] = $d['value'];
            }
        }
        return $specials;
    }
    
    public function saveSpecialWebsites($post) 
    {
        $ids = array();
        if(!empty($post)) {
            $this->transStart();
            foreach($post as $name=>$value) {
                $id_settings = $this->saveConf('special_' . $name, $value);
                $ids[] = $id_settings;
            }
            
            $query = $this->like('name', 'special_%');
            if(!empty($ids)) {
                $query->whereNotIn('id', $ids);
            }
            $query->delete();
            $this->transComplete();
            return $this->transStatus();
        }
        return false;
    }
}

