<?php

namespace Modules\Maps\Models;  
use CodeIgniter\Model;

class MapsModel extends Model{

    protected $table = 'maps';
    
    protected $allowedFields = [
        'id_page_cont',
        'center',
        'zoom',
        'styles',
        'type',
        'publish',
        'edited_at',
        'created_at'
    ];
    
    public function getMapById($id, $id_lang) 
    {
        $map = $this->where('id', $id)->first();
        if(!empty($map)) {
            $map['lang'] = $this->getMapLang($id);
            if(!empty($map['lang']) && !empty($map['lang'][$id_lang]) && !empty($map['lang'][$id_lang]['name'])) {
                $map['name'] = $map['lang'][$id_lang]['name'];
            } else {
                $map['name'] = '';
            }
            $map['markers'] = $this->getMapMarkers($id);
        }
        return $map;
    }
    
    public function getMapLang($id) 
    {
        $langs = array();
        $data = $this->db->table('maps_lang')->where('id_map', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = array(
                    'name' => $d['name']
                );
            }
        }
        return $langs;
    }
    
    public function getMapMarkers($id) 
    {
        $markers = array();
        $markers = $this->db->table('maps_marker')->where('id_map', $id)->get()->getResultArray();
        if(!empty($markers)) {
            foreach($markers as $k=>$m) {
                if(!empty($m['id_file'])) {
                    $markers[$k]['file'] = $this->db->table('tio_files')->where('id', $m['id_file'])->limit(1)->get()->getRowArray();
                    $markers[$k]['file_radio'] = 'id-file';
                } elseif(!empty($m['svg'])) {
                    $markers[$k]['file_radio'] = 'svg';
                }
                $markers[$k]['lang'] = $this->getMapMarkerLang($m['id']);
            }
        }
        return $markers;
    }
    
    public function getMapMarkerLang($id_marker) 
    {
        $langs = array();
        $data = $this->db->table('maps_marker_lang')->where('id_marker', $id_marker)->orderBy('id_lang', 'ASC')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function saveMap($id, $post) 
    {
        if(empty($post)) return false;
        $data = array(
            'center' => $post['center'],
            'zoom' => $post['zoom'],
            'styles' => $post['styles'],
            'type' => $post['type'],
            'publish' => !empty($post['publish']) ? $post['publish'] : 0
        );
        $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        $this->saveMapLang($this->id, $post['lang']);
        $this->saveMapMarkers($this->id, !empty($post['marker']) ? $post['marker'] : array());
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveMapLang($id_map, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_map' => $id_map,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                );
                $map = $this->db->table('maps_lang')->select('id')->where('id_map', $id_map)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($map) && !empty($map['id'])) {
                    $result = $this->db->table('maps_lang')->set($data)->where('id_map', $id_map)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('maps_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveMapMarkers($id, $markers) 
    {
        $ids = array();
        if(!empty($markers)) {
            foreach($markers as $marker) {
                $data = array(
                    'id_map' => $id,
                    'id_file' => !empty($marker['file']) && !empty($marker['file']['id']) ? $marker['file']['id'] : 0,
                    'cords' => $marker['cords'],
                    'svg' => $marker['svg'],
                    'publish' => !empty($marker['publish']) ? $marker['publish'] : 0,
                );
                if($marker['id'] && !empty($this->db->table('maps_marker')->select('id')->where('id', $marker['id'])->get()->getResultArray())) {
                    $result = $this->db->table('maps_marker')->set($data)->where('id', $marker['id'])->update();
                    $id_marker = $marker['id'];
                } else {
                    $result = $this->db->table('maps_marker')->insert($data);
                    $id_marker = $this->db->insertID();
                }
                $ids[] = $id_marker;
                $this->saveMapMarkerLang($id_marker, $marker['lang']);
            }
        }
        $query = $this->db->table('maps_marker')->select('id')->where('id_map', $id);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $markers_list = $query->get()->getResultArray();
        if(!empty($markers_list)) {
            foreach($markers_list as $marker) {
                $this->db->table('maps_marker_lang')->where('id_marker', $marker['id'])->delete();
                $this->db->table('maps_marker')->where('id', $marker['id'])->delete();
            }
        }
    }
    
    private function saveMapMarkerLang($id_marker, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_marker' => $id_marker,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'caption' => $lang['caption'],
                );
                $marker = $this->db->table('maps_marker_lang')->select('id')->where('id_marker', $id_marker)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($marker) && !empty($marker['id'])) {
                    $result = $this->db->table('maps_marker_lang')->set($data)->where('id_marker', $id_marker)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('maps_marker_lang')->insert($data);
                }
            }
        }
    }
    
    public function deleteMap($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $markers_list = $this->db->table('maps_marker')->select('id')->where('id_map', $id)->get()->getResultArray();
        if(!empty($markers_list)) {
            foreach($markers_list as $marker) {
                $this->db->table('maps_marker_lang')->where('id_marker', $marker['id'])->delete();
                $this->db->table('maps_marker')->where('id', $marker['id'])->delete();
            }
        }
        $this->db->table('maps_lang')->where('id_map', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function saveSettings($post) 
    {
        $ids = array();
        if(!empty($post)) {
            $this->transStart();
            foreach($post as $name=>$value) {
                $settings = $this->db->table('maps_settings')->where('name', $name)->get()->getRowArray();
                if(!empty($settings)) {
                    $this->db->table('maps_settings')->where('name', $name)->set('value', $value)->update();
                    $id_settings = $settings['id'];
                } else {
                    $data = array(
                        'name' => $name,
                        'value' => $value
                    );
                    $this->db->table('maps_settings')->insert($data);
                    $id_settings = $this->db->insertID();
                }
                $ids[] = $id_settings;
            }
            $query = $this->db->table('maps_settings')->select('id');
            if(!empty($ids)) {
                $query->whereNotIn('id', $ids);
            }
            $list = $query->get()->getResultArray();
            if(!empty($list)) {
                foreach($list as $l) {
                    $this->db->table('maps_settings')->where('id', $l['id'])->delete();
                }
            }
            $this->transComplete();
            return $this->transStatus();
        }
        return false;
    }
    
    public function getMapsSettings()
    {
        $settings = array();
        $data = $this->db->table('maps_settings')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $settings[$d['name']] = $d['value'];
            }
        }
        return $settings;
    }
}