<?php

namespace Modules\Event\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class EventPlaceModel extends Model{

    protected $table = 'event_place';
    
    protected $allowedFields = [
        'id_page_cont',
        'id_type',
        'id_city',
        'street',
        'building_no',
        'postcode',
        'www',
        'email',
        'phone',
        'home',
        'publish',
        'template',
        'views',
        'comment',
        'edited_at',
        'created_at',
    ];
    
    public function getEventPlaceById($id, $id_lang) 
    {
        $place = $this->where('id', $id)->first();
        if(!empty($place)) {
            $place['lang'] = $this->getEventPlaceLang($id);
            if(!empty($place['lang']) && !empty($place['lang'][$id_lang]) && !empty($place['lang'][$id_lang]['name'])) {
                $place['name'] = $place['lang'][$id_lang]['name'];
            } else {
                $place['name'] = '';
            }
            $place['meta']['lang'] = $this->getEventPlaceMetaLang($id);
            $place['photo'] = $this->getEventPlaceFile($id, 'place_photo');
            $place['photos'] = $this->getEventPlaceFiles($id, 'place_photos');
            $place['audio'] = $this->getEventPlaceFiles($id, 'place_audio');
            $place['video'] = $this->getEventPlaceFiles($id, 'place_video');
        }
        return $place;
    }
    
    private function getEventPlaceLang($id_place) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('event_place_lang')->where('id_place', $id_place)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getEventPlaceMetaLang($id_place) 
    {
        $langs = array();
        $data = $this->db->table('event_meta_lang')->where('id_event', $id_place)->where('slug', 'place')->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    private function getEventPlaceFile($id_place, $field='') 
    {
        $file = $this->db->table('event_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_event', $id_place)->where('field', $field)->orderBy('order', 'ASC')->get()->getRowArray();
        if(!empty($file)) {
            $file['lang'] = $this->getEventPlaceFileLang($file['id']);
        }
        return $file;
    }
    
    private function getEventPlaceFiles($id_place, $field='') 
    {
        $files = $this->db->table('event_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_event', $id_place)->where('field', $field)->orderBy('order', 'ASC')->get()->getResultArray();
        if(!empty($files)) {
            foreach($files as $k=>$file) {
                $files[$k]['lang'] = $this->getEventPlaceFileLang($file['id']);
            }
        }
        return $files;
    }
    
    private function getEventPlaceFileLang($id_file) 
    {
        $langs = array();
        $data = $this->db->table('event_files_lang')->where('id_file', $id_file)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveEventPlace($id, $id_content, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;
        $data = array(
            'id_page_cont' => $id_content,
            'id_type' => !empty($post['id_type']) ? $post['id_type'] : 0,
            'id_city' => !empty($post['id_city']) ? $post['id_city'] : 0,
            // Ulica bywa pusta - adresy wiejskie to sama miejscowosc i numer (np. 36-002 Jasionka 953).
            'street' => !empty($post['street']) ? $post['street'] : '',
            'building_no' => !empty($post['building_no']) ? $post['building_no'] : '',
            'postcode' => !empty($post['postcode']) ? $post['postcode'] : '',
            'www' => $post['www'],
            'email' => $post['email'],
            'phone' => $post['phone'],
            'template' => $post['template'],
            'home' => !empty($post['home']) ? $post['home'] : 0,
            'comment' => !empty($post['comment']) ? $post['comment'] : 0,
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
        
        $this->saveEventPlaceLang($this->id, $post['lang']);
        $this->saveEventPlaceMetaLang($this->id, $post['meta']['lang']);
        $this->saveEventPlaceFile($this->id, !empty($post['photo']) ? $post['photo'] : array(), 'place_photo', true);
        $this->saveEventPlaceFiles($this->id, !empty($post['photos']) ? $post['photos'] : array(), 'place_photos');
        $this->saveEventPlaceFiles($this->id, !empty($post['audio']) ? $post['audio'] : array(), 'place_audio');
        $this->saveEventPlaceFiles($this->id, !empty($post['video']) ? $post['video'] : array(), 'place_video');
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveEventPlaceLang($id_place, $lang_data) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Event')->get()->getRowArray();
            $linkClass = new Link();
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_place' => $id_place,
                    'id_lang' => $id_lang,
                    'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0, false, null, 'place'),
                    'name' => $lang['name'],
                    'content' => $lang['content'],
                    'address' => $lang['address'],
                    'working_hours' => $lang['working_hours'],
                    'repertoire' => $lang['repertoire'],
                );
                $lang = $this->db->table('event_place_lang')->select('id')->where('id_place', $id_place)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('event_place_lang')->set($data)->where('id_place', $id_place)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('event_place_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveEventPlaceMetaLang($id_place, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_event' => $id_place,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                    'slug' => 'place',
                );
                $lang = $this->db->table('event_meta_lang')->select('id')->where('id_event', $id_place)->where('slug', 'place')->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('event_meta_lang')->set($data)->where('id_event', $id_place)->where('slug', 'place')->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('event_meta_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveEventPlaceFiles($id_place, $files, $field='') 
    {
        $ids = array();
        if(!empty($files)) {
            foreach($files as $file) {
                $ids[] = $this->saveEventPlaceFile($id_place, $file, $field);
            }
        }
        $query = $this->db->table('event_files')->select('id,path')->where('id_event', $id_place)->where('field', $field);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $files_list = $query->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeEventPlaceFile($f);
            }
        }
    }
    
    private function saveEventPlaceFile($id_place, $file, $field='', $remove=false) 
    {
        if(!empty($file)) {
            if(!empty($file['id']) && !empty($this->db->table('event_files')->select('id')->where('id', $file['id'])->get()->getRowArray())) {
                $data = array(
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('event_files')->set($data)->where('id', $file['id'])->update();
                $id_file = $file['id'];
            } else {
                $file_obj = new \CodeIgniter\Files\File(WRITEPATH . 'uploads/' . $file['path']);
                if(!is_dir(WRITEPATH . 'uploads/event')) {
                    mkdir(WRITEPATH . 'uploads/event');
                }
                if(!is_dir(WRITEPATH . 'uploads/event/' . date('Ymd'))) {
                    mkdir(WRITEPATH . 'uploads/event/' . date('Ymd'));
                }
                $r = $file_obj->move(WRITEPATH . 'uploads/event/' . date('Ymd') , $file['basename']);
                $file_path = 'event/' . date('Ymd') . '/' . $r->getFilename();
                $file_info = pathinfo(WRITEPATH . 'uploads/' . $file_path);
                $data = array(
                    'id_event' => $id_place,
                    'field' => $field,
                    'name' => $file['name'],
                    'basename' => $file['basename'],
                    'path' => $file_path,
                    'mime' => $r->getMimeType(),
                    'type' => file_type($r->getMimeType()),
                    'ext' => $file_info['extension'],
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('event_files')->insert($data);
                $id_file = $this->db->insertID();
            }
            $this->saveEventPlaceFileLang($id_file, $file['lang']);
        }
        if($remove) {
            $query = $this->db->table('event_files')->select('id,path')->where('id_event', $id_place)->where('field', $field);
            if(!empty($id_file)) {
                $query->where('id !=', $id_file);
            }
            $files_list = $query->get()->getResultArray();
            if(!empty($files_list)) {
                foreach($files_list as $f) {
                    $this->removeEventPlaceFile($f);
                }
            }
        }
        return !empty($id_file) ? $id_file : 0;
    }
    
    private function saveEventPlaceFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => $lang['caption'],
                    'author' => $lang['author'],
                );
                $lang = $this->db->table('event_files_lang')->select('id')->where('id_file', $id_file)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('event_files_lang')->set($data)->where('id_file', $id_file)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('event_files_lang')->insert($data);
                }
            }
        }
    }
    
    private function removeEventPlaceFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('event_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('event_files')->where('id', $file['id'])->delete();
    }
    
    public function deleteEventPlace($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('event_place_lang')->select('id_link')->where('id_place', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $files_list = $this->db->table('event_files')->select('id,path')->where('id_event', $id)->whereIn('field', array('place_photo', 'place_photos', 'place_audio', 'place_video'))->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeEventPlaceFile($f);
            }
        }
        $r = $this->db->table('event_place_lang')->where('id_place', $id)->delete();
        $r = $this->db->table('event_meta_lang')->where('id_event', $id)->where('slug', 'place')->delete();
        $r = $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getPlacesForList($id_lang) {
        $places = $this->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->select('ep.id,epl.name')->where('ep.publish', 1)->where('epl.id_lang', $id_lang)->orderBy('epl.name ASC')->get()->getResultArray();
        return $places;
    }

    public function assignExternalPlaces($source, $assign) {
        $this->db->transStart();
        if(!empty($assign) && !empty($source)) {
            foreach($assign as $external=>$id_place) {
                if(!empty($id_place)) {
                    $is = $this->db->table('event_place_external')->select('id')->where('external', $external)->where('source', $source)->get()->getRowArray();
                    $data = array(
                        'id_place' => $id_place,
                        'source' => $source,
                        'external' => $external,
                    );
                    if(!empty($is)) {
                        if($id_place == 'del') {
                            $this->db->table('event_place_external')->where('id', $is['id'])->delete();
                        } else {
                            $this->db->table('event_place_external')->where('id', $is['id'])->update($data);
                        }
                    } else {
                        $this->db->table('event_place_external')->insert($data);
                    }
                }
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getExternalPlacesForList($source) {
        $places = array();
        if(!empty($source)) {
            $list = $this->db->table('event_place_external')->select('id,id_place,external')->where('source', $source)->get()->getResultArray();
            if(!empty($list)) {
                foreach($list as $l) {
                    $places[$l['external']] = $l['id_place'];
                }
            }
        }
        return $places;
    }
}