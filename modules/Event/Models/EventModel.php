<?php

namespace Modules\Event\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use Modules\Tags\Models\TagsModel;

class EventModel extends Model{

    protected $table = 'event';
    
    protected $allowedFields = [
        'id_page_cont',
        'id_type',
        'publish',
        'home',
        'patronage',
        'for_kids',
        'recommended',
        'template',
        'edited_at',
        'created_at',
    ];
    
    public function getEventById($id, $id_lang, $page = array(), $action = '') 
    {
        $event = $this->where('id', $id)->first();
        if(!empty($event)) {
            if(empty($event['date']) || strtotime($event['date']) < 0) {
                $event['date'] = '';
            }
            $event['lang'] = $this->getEventLang($id, $page, $action);
            if(!empty($event['lang']) && !empty($event['lang'][$id_lang]) && !empty($event['lang'][$id_lang]['name'])) {
                $event['name'] = $event['lang'][$id_lang]['name'];
            } else {
                $event['name'] = '';
            }
            $event['repertoire'] = $this->getEventDefaultRepertoire($id);
            $event['meta']['lang'] = $this->getEventMetaLang($id);
            $event['photo'] = $this->getEventFile($id, 'photo', $action);
            $event['photos'] = $this->getEventFiles($id, 'photos', $action);
            $event['audio'] = $this->getEventFiles($id, 'audio', $action);
            $event['video'] = $this->getEventFiles($id, 'video', $action);
            if($action == 'copy') {
                unset($event['id']);
            }
        }
        return $event;
    }
    
    private function getEventLang($id_event, $page = array(), $action = '') 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('event_lang')->where('id_event', $id_event)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                if(!empty($d['tags'])) {
                    $this->tagsModel = new TagsModel();
                    $d['tags']=$this->tagsModel->GetTagsById($d['tags']);
                }	
                if($action == 'copy') {
                    $d['id_link'] = '';
                    $d['name'] .= ' (copy)';
                    $d['link'] = $linkClass->generateLink($d['name'], $d['id_lang'], 0, $page['id_page'], 'event');
                } else {
                    $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                }
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getEventMetaLang($id_event) 
    {
        $langs = array();
        $data = $this->db->table('event_meta_lang')->where('id_event', $id_event)->where('slug', '')->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getEventDefaultRepertoire($id_event) {
        $repertoire = array(
            'places' => array(),
            'date_start' => '',
            'date_end' => '',
            'hours' => '',
            'custom_place' => '',
        );
        $list = $this->db->table('event_calendar')->select('id,id_place,date_start,date_end,hours,custom_place')->where('id_event', $id_event)->where('default', 1)->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $k=>$l) {
                if($k == 0) {
                    $repertoire['date_start'] = $l['date_start'];
                    $repertoire['date_end'] = $l['date_end'];
                    $repertoire['hours'] = json_decode($l['hours'], true);
                }
                if($l['id_place'] == 0) {
                    $repertoire['custom_place'] = $l['custom_place'];
                }
                if(!in_array($l['id_place'], $repertoire['places'])) {
                    $repertoire['places'][] = $l['id_place'];
                }
            }
        }
        return $repertoire;
    }
    
    private function getEventFile($id_event, $field='', $action = '') 
    {
        $file = $this->db->table('event_files')->select('id,name,basename,path,order,type,publish,ext,crop_dimension')->where('id_event', $id_event)->where('field', $field)->orderBy('order', 'ASC')->get()->getRowArray();
        if(!empty($file)) {
            $file['lang'] = $this->getEventFileLang($file['id']);
            if(!empty($file['crop_dimension'])) {
                $file['crop_dimension'] = json_decode($file['crop_dimension']);
            }
            if($action == 'copy') {
                unset($file['id']);
                if(!is_dir(WRITEPATH . 'uploads/event')) {
                    mkdir(WRITEPATH . 'uploads/event');
                }
                if(!is_dir(WRITEPATH . 'uploads/event/' . date('Ymd'))) {
                    mkdir(WRITEPATH . 'uploads/event/' . date('Ymd'));
                }
                $new_name = time() . '_' . bin2hex(random_bytes(10)) . '.' . $file['ext'];
                @copy(WRITEPATH . 'uploads/' . $file['path'], WRITEPATH . 'uploads/tmp/' . date('Ymd') . '/' . $new_name);
                $file['path'] = 'tmp/' . date('Ymd') . '/' . $new_name;
            }
        }
        return $file;
    }
    
    private function getEventFiles($id_event, $field='', $action = '') 
    {
        $files = $this->db->table('event_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_event', $id_event)->where('field', $field)->orderBy('order', 'ASC')->get()->getResultArray();
        if(!empty($files)) {
            foreach($files as $k=>$file) {
                $files[$k]['lang'] = $this->getEventFileLang($file['id']);
                if($action == 'copy') {
                    unset($files[$k]['id']);
                    if(!is_dir(WRITEPATH . 'uploads/event')) {
                        mkdir(WRITEPATH . 'uploads/event');
                    }
                    if(!is_dir(WRITEPATH . 'uploads/event/' . date('Ymd'))) {
                        mkdir(WRITEPATH . 'uploads/event/' . date('Ymd'));
                    }
                    $new_name = time() . '_' . bin2hex(random_bytes(10)) . '.' . $file['ext'];
                    copy(WRITEPATH . 'uploads/' . $file['path'], WRITEPATH . 'uploads/tmp/' . date('Ymd') . '/' . $new_name);
                    $files[$k]['path'] = 'tmp/' . date('Ymd') . '/' . $new_name;
                }
            }
        }
        return $files;
    }
    
    private function getEventFileLang($id_file) 
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
    
    public function saveMassEvents($id_content, $events, $template) {
        helper(['file']);
        if(empty($events)) return false;
        $this->db->transStart();
        foreach($events as $event) {
            $data = array(
                'id_page_cont' => $id_content,
                'id_type' => !empty($event['type']) ? $event['type'] : 0,
                'home' => !empty($event['home']) ? $event['home'] : 0,
                'patronage' => !empty($event['patronage']) ? $event['patronage'] : 0,
                'for_kids' => !empty($event['for_kids']) ? $event['for_kids'] : 0,
                'recommended' => !empty($event['recommended']) ? $event['recommended'] : 0,
                'template' => $template,
                'publish' => 1,
            );
            $result = $this->insert($data);
            if($result) {
                $id = $this->getInsertID();
                $this->saveEventLang($id, $event['lang'], $id_content);
                $this->saveEventFile($id, $event, 'photo', true);
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function saveEvent($id, $id_content, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;
        $data = array(
            'id_page_cont' => $id_content,
            'id_type' => !empty($post['id_type']) ? $post['id_type'] : 0,
            'template' => $post['template'],
            'home' => !empty($post['home']) ? $post['home'] : 0,
            'patronage' => !empty($post['patronage']) ? $post['patronage'] : 0,
            'for_kids' => !empty($post['for_kids']) ? $post['for_kids'] : 0,
            'recommended' => !empty($post['recommended']) ? $post['recommended'] : 0,
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
        $additional_tags = array();
        if(!empty($post['id_type'])) {
            $additional_tags['type'] = $post['id_type'];
        }
        if(!empty($post['place'])) {
            $additional_tags['place'] = array();
            foreach($post['place'] as $p) {
                $additional_tags['place'][] = $p;
            }
        }
        if(!empty($post['custom_place'])) {
            $additional_tags['text'] = $post['custom_place'];
        }
        $this->saveEventLang($this->id, $post['lang'], $id_content, $additional_tags);
        $this->saveEventMetaLang($this->id, $post['meta']['lang']);
        $this->saveEventFile($this->id, !empty($post['photo']) ? $post['photo'] : array(), 'photo', true);
        $this->saveEventFiles($this->id, !empty($post['photos']) ? $post['photos'] : array(), 'photos');
        $this->saveEventFiles($this->id, !empty($post['audio']) ? $post['audio'] : array(), 'audio');
        $this->saveEventFiles($this->id, !empty($post['video']) ? $post['video'] : array(), 'video');
        if(!empty($post['place']) || !empty($post['custom_place'])) {
            $this->saveEventDefaultRepertoire($this->id, array('place' => !empty($post['place']) ? $post['place'] : array(), 'custom_place' => $post['custom_place'], 'date_start' => $post['date_start'], 'date_end' => $post['date_end'], 'hour' => $post['hour']));
            //$this->saveEventCalendar($this->id, array('place' => !empty($post['place']) ? $post['place'] : array(), 'custom_place' => $post['custom_place'], 'date' => $post['date'], 'hour' => $post['hour']));
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveEventLang($id_event, $lang_data, $id_content, $additional_tags = array()) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Event')->get()->getRowArray();
            $linkClass = new Link();
            $page = $this->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
            foreach($lang_data as $id_lang=>$lang) {
                $tags_arr = array();
                if(!empty($lang['tags'])) {
                    $tags_arr = explode(',', $lang['tags']);
                }
                if(!empty($lang['name'])) {
                    $tags_arr[] = $lang['name'];
                }
                if(!empty($additional_tags['text'])) {
                    $tags_arr[] = $additional_tags['text'];
                }
                if(!empty($additional_tags['place'])) {
                    foreach($additional_tags['place'] as $p) {
                        $place = $this->db->table('event_place_lang')->select('id,name')->where('id_lang', $id_lang)->where('id_place', $p)->get()->getRowArray();
                        if(!empty($place)) {
                            $tags_arr[] = $place['name'];
                        }
                    }
                }
                if(!empty($additional_tags['type'])) {
                    $type = $this->db->table('event_type_lang')->select('id,name')->where('id_lang', $id_lang)->where('id_type', $additional_tags['type'])->get()->getRowArray();
                    if(!empty($type)) {
                        $tags_arr[] = $type['name'];
                    }
                }
                $tags_arr = array_unique($tags_arr);
                if(!empty($tags_arr)) {	
                    $this->tagsModel = new TagsModel();
                    $lang['tags']= $this->tagsModel->AddTags(implode(',', $tags_arr),$id_lang, $id_content);
                }
                else {$lang['tags']='';}
				if(empty($lang['link'])) {
                    $lang['link'] = $linkClass->generateLink($lang['name'], $id_lang, 0, $page['id_page']);
                }
                $data = array(
                    'id_event' => $id_event,
                    'id_lang' => $id_lang,
                    'id_link' => $linkClass->saveLink($lang['link'], $id_lang, !empty($lang['id_link']) ? $lang['id_link'] : 0, !empty($module) ? $module['id'] : 0),
                    'name' => !empty($lang['name']) ? $lang['name'] : '',
                    'subname' => !empty($lang['subname']) ? $lang['subname'] : '',
                    'introduction' => !empty($lang['introduction']) ? $lang['introduction'] : '',
                    'content' => !empty($lang['content']) ? $lang['content'] : '',
                    'tickets' => !empty($lang['tickets']) ? $lang['tickets'] : '',
                    'price' => !empty($lang['price']) ? $lang['price'] : '',
                    'comments' => !empty($lang['comments']) ? $lang['comments'] : '',
                    'tags'=>$lang['tags']
                );
                $lang = $this->db->table('event_lang')->select('id')->where('id_event', $id_event)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('event_lang')->set($data)->where('id_event', $id_event)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('event_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveEventMetaLang($id_event, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_event' => $id_event,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                    'keywords' => $lang['keywords'],
                    'slug' => ''
                );
                $lang = $this->db->table('event_meta_lang')->select('id')->where('id_event', $id_event)->where('slug', '')->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('event_meta_lang')->set($data)->where('id_event', $id_event)->where('slug', '')->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('event_meta_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveEventFiles($id_event, $files, $field='') 
    {
        $ids = array();
        if(!empty($files)) {
            foreach($files as $file) {
                $ids[] = $this->saveEventFile($id_event, $file, $field);
            }
        }
        $query = $this->db->table('event_files')->select('id,path')->where('id_event', $id_event)->where('field', $field);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $files_list = $query->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeEventFile($f);
            }
        }
    }
    
    private function saveEventFile($id_event, $file, $field='', $remove=false) 
    {
        if(!empty($file)) {
            if(!empty($file['id']) && !empty($this->db->table('event_files')->select('id')->where('id', $file['id'])->get()->getRowArray())) {
                $data = array(
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
					'crop_dimension' => !empty($file['crop']) ? json_encode($file['crop']) : ''
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
                if(!empty($file) && !empty($file['crop'])) {
                    $file['crop']['path'] = $file_path;
                }
                $data = array(
                    'id_event' => $id_event,
                    'field' => $field,
                    'name' => $file['name'],
                    'basename' => $file['basename'],
                    'path' => $file_path,
                    'mime' => $r->getMimeType(),
                    'type' => file_type($r->getMimeType()),
                    'ext' => $file_info['extension'],
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                    'crop_dimension' => !empty($file['crop']) ? json_encode($file['crop']) : ''
                );
                $result = $this->db->table('event_files')->insert($data);
                $id_file = $this->db->insertID();
            }
            $this->saveEventFileLang($id_file, $file['lang']);
        }
        if($remove) {
            $query = $this->db->table('event_files')->select('id,path')->where('id_event', $id_event)->where('field', $field);
            if(!empty($id_file)) {
                $query->where('id !=', $id_file);
            }
            $files_list = $query->get()->getResultArray();
            if(!empty($files_list)) {
                foreach($files_list as $f) {
                    $this->removeEventFile($f);
                }
            }
        }
        return !empty($id_file) ? $id_file : 0;
    }
    
    private function saveEventFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => !empty($lang['caption']) ? $lang['caption'] : '',
                    'author' => !empty($lang['author']) ? $lang['author'] : '',
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
    
    private function removeEventFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('event_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('event_files')->where('id', $file['id'])->delete();
    }
    
    public function saveEventRepertoire($id_event, $post) 
    {
        $this->db->transStart();
        if(!empty($post['date'])) {
            foreach($post['date'] as $date) {
                if(!empty($post['place']) || !empty($post['custom_place'])) {
                    $this->saveEventCalendar($id_event, array('place' => !empty($post['place']) ? $post['place'] : array(), 'custom_place' => $post['custom_place'], 'date' => date('d.m.Y', strtotime($date)), 'hour' => $post['hour']));
                }
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveEventCalendar($id_event, $calendar) 
    {
        $this->statistics = array(
            'added' => array(),
            'exists' => array(),
        );
        $ids = array();
        if(!empty($calendar)) {
            if (!empty($calendar['date'])) {
                $tmp = explode('-', $calendar['date']);
                $calendar['date_start'] = !empty($tmp) && !empty($tmp[0]) ? date('Y-m-d', strtotime($tmp[0])) : null;
                $calendar['date_end'] = !empty($tmp) && !empty($tmp[1]) ? date('Y-m-d', strtotime($tmp[1])) : null;
            } else {
                $calendar['date_start'] = !empty($calendar) && !empty($calendar['date_start']) ? date('Y-m-d', strtotime($calendar['date_start'])) : null;
                $calendar['date_end'] = !empty($calendar) && !empty($calendar['date_end']) ? date('Y-m-d', strtotime($calendar['date_end'])) : null;
            }
            if(!empty($calendar['hour'])) {
                $tmp = $calendar['hour'];
                sort($tmp);
                $calendar['hour'] = array();
                foreach($tmp as $t) {
                    $calendar['hour'][] = date('H:i', strtotime($t));
                }
            }
            if(empty($calendar['date_end'])) $calendar['date_end'] = null;
            if(!empty($calendar['place'])) {
                foreach($calendar['place'] as $id_place) {
                    $data = array(
                        'id_event' => $id_event,
                        'id_place' => $id_place,
                        'date_start' => !empty($calendar['date_start']) ? $calendar['date_start'] : null,
                        'date_end' => !empty($calendar['date_end']) ? $calendar['date_end'] : null,
                        'hours' => !empty($calendar['hour']) ? json_encode($calendar['hour']) : null,
                        'custom_place' => ''
                    );
                    $exist = $this->db->table('event_calendar')->where($data)->get()->getRowArray();
                    if(empty($exist)) {
                        $this->db->table('event_calendar')->insert($data);
                        $ids[] = $this->db->insertID();
                        $this->statistics['added'][] = $data;
                    } else {
                        $ids[] = $exist['id'];
                        $this->statistics['exists'][] = $data;
                    }
                }
            }
            if(!empty($calendar['custom_place'])) {
                $data = array(
                    'id_event' => $id_event,
                    'id_place' => 0,
                    'date_start' => !empty($calendar['date_start']) ? $calendar['date_start'] : null,
                    'date_end' => !empty($calendar['date_end']) ? $calendar['date_end'] : null,
                    'hours' => !empty($calendar['hour']) ? json_encode($calendar['hour']) : null,
                    'custom_place' => $calendar['custom_place']
                );
                $exist = $this->db->table('event_calendar')->where($data)->get()->getRowArray();
                if(empty($exist)) {
                    $this->db->table('event_calendar')->insert($data);
                    $ids[] = $this->db->insertID();
                    $this->statistics['added'][] = $data;
                } else {
                    $ids[] = $exist['id'];
                    $this->statistics['exists'][] = $data;
                }
            }
        }
    }
    
    private function saveEventDefaultRepertoire($id_event, $calendar) {
        $this->statistics = array(
            'added' => array(),
            'exists' => array(),
        );
        $ids = array();
        if(!empty($calendar)) {
            if (!empty($calendar['date'])) {
                $tmp = explode('-', $calendar['date']);
                $calendar['date_start'] = !empty($tmp) && !empty($tmp[0]) ? date('Y-m-d', strtotime($tmp[0])) : null;
                $calendar['date_end'] = !empty($tmp) && !empty($tmp[1]) ? date('Y-m-d', strtotime($tmp[1])) : null;
            } else {
                $calendar['date_start'] = !empty($calendar) && !empty($calendar['date_start']) ? date('Y-m-d', strtotime($calendar['date_start'])) : null;
                $calendar['date_end'] = !empty($calendar) && !empty($calendar['date_end']) ? date('Y-m-d', strtotime($calendar['date_end'])) : null;
            }
            if(!empty($calendar['hour'])) {
                $tmp = $calendar['hour'];
                sort($tmp);
                $calendar['hour'] = array();
                foreach($tmp as $t) {
                    if(!empty($t)) {
                        $calendar['hour'][] = date('H:i', strtotime($t));
                    }
                }
            }
            if(empty($calendar['date_end'])) $calendar['date_end'] = null;
            if(!empty($calendar['place'])) {
                foreach($calendar['place'] as $id_place) {
                    $data = array(
                        'id_event' => $id_event,
                        'id_place' => $id_place,
                        'date_start' => !empty($calendar['date_start']) ? $calendar['date_start'] : null,
                        'date_end' => !empty($calendar['date_end']) ? $calendar['date_end'] : null,
                        'hours' => !empty($calendar['hour']) ? json_encode($calendar['hour']) : null,
                        'custom_place' => '',
                        'default' => 1,
                    );
                    $exist = $this->db->table('event_calendar')->where('id_event', $id_event)->where('id_place', $id_place)->where('default', 1)->get()->getRowArray();
                    if(empty($exist)) {
                        $this->db->table('event_calendar')->insert($data);
                        $ids[] = $this->db->insertID();
                        $this->statistics['added'][] = $data;
                    } else {
                        $this->db->table('event_calendar')->where('id_event', $id_event)->where('id', $exist['id'])->update($data);
                        $ids[] = $exist['id'];
                        $this->statistics['exists'][] = $data;
                    }
                }
            }
            if(!empty($calendar['custom_place'])) {
                $data = array(
                    'id_event' => $id_event,
                    'id_place' => 0,
                    'date_start' => !empty($calendar['date_start']) ? $calendar['date_start'] : null,
                    'date_end' => !empty($calendar['date_end']) ? $calendar['date_end'] : null,
                    'hours' => !empty($calendar['hour']) ? json_encode($calendar['hour']) : null,
                    'custom_place' => $calendar['custom_place'],
                    'default' => 1,
                );
                $exist = $this->db->table('event_calendar')->where('id_event', $id_event)->where('id_place', 0)->where('default', 1)->get()->getRowArray();
                if(empty($exist)) {
                    $this->db->table('event_calendar')->insert($data);
                    $ids[] = $this->db->insertID();
                    $this->statistics['added'][] = $data;
                } else {
                    $this->db->table('event_calendar')->where('id_event', $id_event)->where('id', $exist['id'])->update($data);
                    $ids[] = $exist['id'];
                    $this->statistics['exists'][] = $data;
                }
            }
        }
        
        $query = $this->db->table('event_calendar')->where('id_event', $id_event)->where('default', 1);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $list = $query->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $this->db->table('event_calendar')->where('id', $l['id'])->delete();
            }
        }
    }
    
    public function deleteEvent($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('event_lang')->select('id_link')->where('id_event', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $files_list = $this->db->table('event_files')->select('id,path')->where('id_event', $id)->whereIn('field', array('photo', 'photos', 'audio', 'video'))->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeEventFile($f);
            }
        }
        $this->db->table('event_lang')->where('id_event', $id)->delete();
        $this->db->table('event_meta_lang')->where('id_event', $id)->where('slug', '')->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getTypesForList($id_lang) {
        $types = $this->db->table('event_type et')->join('event_type_lang etl', 'et.id=etl.id_type')->select('et.id,et.publish,etl.name')->where('et.publish', 1)->where('etl.id_lang', $id_lang)->orderBy('etl.name ASC')->get()->getResultArray();
        return $types;
    }
    
    public function getPlaceTypesForList($id_lang) {
        $types = $this->db->table('event_place_type ept')->join('event_place_type_lang eptl', 'ept.id=eptl.id_type')->select('ept.id,ept.publish,eptl.name')->where('eptl.id_lang', $id_lang)->orderBy('eptl.name ASC')->get()->getResultArray();
        return $types;
    }
    
    public function getMainPlaceTypesWithPlacesList($id_lang, $home=false,$filters=false) {
		
        $query = $this->db->table('event_place_type ept')->join('event_place_type_lang eptl', 'ept.id=eptl.id_type')->join('links l', 'l.id=eptl.id_link')->select('ept.id,eptl.name,l.link')->where('eptl.id_lang', $id_lang)->where('ept.id_parent', 0)->where('ept.publish', 1)->orderBy('ept.order', 'ASC');
		if(!empty($filters['type'])) {
			$query->Where('ept.id',$filters['type']);
		}	
		$types=$query->get()->getResultArray();
        if(!empty($types)) {
            foreach($types as $c=>$type) {
                $ids = $this->getPlaceTypeChilds($type['id']);
                $query = $this->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->join('links l', 'l.id=epl.id_link')->join('event_files ef', 'ef.id_event=ep.id AND ef.field="place_photo"', 'left')->join('event_place_type ept', 'ept.id=ep.id_type')->join('event_place_type_lang eptl', 'ept.id=eptl.id_type')->join('links l2', 'l2.id=eptl.id_link')->select('ep.id,epl.name,epl.address,l.link,ef.path,eptl.name as place_type_name,l2.link as place_type_link')->where('epl.id_lang', $id_lang)->where('eptl.id_lang', $id_lang)->where('ep.publish', 1)->whereIn('ep.id_type', array_merge(array($type['id']), $ids));
                if($home) {
                    $query->where('ep.home', 1);
                }  
				if(!empty($filters['s'])) {
					$query->groupStart();
					$query->like('epl.name',$filters['s']);
					$query->orlike('epl.address',$filters['s']);
					$query->groupEnd();
				}
                $types[$c]['places'] = $query->get()->getResultArray();
            }
        }
        return $types;
    }
    
    public function getPlaceTypeChilds($id_type) {
        $ids = array();
        $types = $this->db->table('event_place_type')->select('id')->where('id_parent', $id_type)->where('publish', 1)->get()->getResultArray();
        if(!empty($types)) {
            foreach($types as $type) {
                $ids[] = $type['id'];
                $ids = array_merge($ids, $this->getPlaceTypeChilds($type['id']));
            }
        }
        return $ids;
    }
    
    public function getPlaceTypeForMenu($id, $id_lang, $languages) 
    {
        $types = $this->db->table('event_place_type ept')
                ->join('event_place_type_lang eptl', 'ept.id = eptl.id_type')
                ->select('ept.id as id_target,ept.publish,eptl.name')
                ->where('eptl.id_lang', $id_lang)
                ->where('ept.id', $id)
                ->get()
                ->getRowArray();
        if(!empty($types)) {
            $types['lang'] = array();
            $lang_data = $this->db->table('event_place_type_lang eptl')->join('links l', 'l.id = eptl.id_link')->select('eptl.id_lang,eptl.name,l.link as url')->where('eptl.id_type', $id)->orderBy('eptl.id_lang', 'ASC')->get()->getResultArray();
            if(!empty($lang_data)) {
                foreach($lang_data as $l) {
                    $l['title'] = $l['name'];
                    if(!empty($l['url'])) $l['url'] = (!empty($languages) && !empty($languages[$l['id_lang']]) && $languages[$l['id_lang']]['slug'] ? '/' . $languages[$l['id_lang']]['slug'] : '') . '/' . $l['url'];
                    $types['lang'][$l['id_lang']] = $l;
                }
            }
        }
        return $types;
    }
    
    public function getEventTypeForMenu($id, $id_lang, $languages=array(), $global_links=array()) 
    {
        $types = $this->db->table('event_type et')
                ->join('event_type_lang etl', 'et.id = etl.id_type')
                ->select('et.id as id_target,et.publish,etl.name')
                ->where('etl.id_lang', $id_lang)
                ->where('et.id', $id)
                ->get()
                ->getRowArray();
        if(!empty($types)) {
            $types['lang'] = array();
            $lang_data = $this->db->table('event_type_lang etl')->select('etl.id_lang,etl.name,etl.slug as url')->where('etl.id_type', $id)->orderBy('etl.id_lang', 'ASC')->get()->getResultArray();
            if(!empty($lang_data)) {
                $settings = array();
                $global = $this->db->table('settings_lang sl')->join('settings s', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_calendar')->get()->getResultArray();
                if(!empty($global)) {
                    foreach($global as $g) {
                        $settings[$g['id_lang']] = $g['value'];
                    }
                }
                foreach($lang_data as $l) {
                    $l['title'] = $l['name'];
                    if(!empty($l['url'])) $l['url'] = (!empty($languages) && !empty($languages[$l['id_lang']]) && $languages[$l['id_lang']]['slug'] ? '/' . $languages[$l['id_lang']]['slug'] : '') . '/' . (!empty($settings) && !empty($settings[$l['id_lang']]) ? $settings[$l['id_lang']] : '') . '?type=' . $l['url'];
                    $types['lang'][$l['id_lang']] = $l;
                }
            }
        }
        return $types;
    }
    
    public function importEvents($source, $id_content, $events) {
        helper(['file']);
        $this->db->transStart();
        $stats = array(
            'result' => false,
            'updated' => array(),
            'created' => array(),
            'removed' => array(),
            'duplicated' => array(),
            // Pominiete, bo wydarzenie nalezy juz do innego bloku. Lista to wpisy kalendarza ORYGINALU (do wylistowania w mailu),
            // ale oryginal moze go nie miec - stad osobny licznik, zeby takie przypadki nie znikaly z podsumowania.
            'skipped' => array(),
            'skipped_count' => 0,
        );
        if(!empty($events)) {
            if(!is_dir(WRITEPATH . 'uploads/event')) {
                mkdir(WRITEPATH . 'uploads/event');
            }
            if(!is_dir(WRITEPATH . 'uploads/event/' . date('Ymd'))) {
                mkdir(WRITEPATH . 'uploads/event/' . date('Ymd'));
            }
            $edited_at = date('Y-m-d H:i:s');
            foreach($events as $event) {
                // Wydarzenie zajete juz przez inny blok pomijamy w calosci, zostawiajac oryginal nietkniety - o pierwszenstwie
                // decyduje kolejnosc feedow w Config\Event::$importUrls, bo pierwszy przebieg zaklada rekord, a kolejne go tu widza.
                // Sprawdzenie przed pobraniem typu, miejsca i zdjecia - oszczedza tez pobranie obrazka przez HTTP.
                $is_other = $this->db->table('event')->select('id')
                        ->where('source', $source)
                        ->where('id_source', $event['Id'])
                        ->where('id_page_cont !=', $id_content)
                        ->get()->getRowArray();
                if(!empty($is_other)) {
                    $stats['skipped_count']++;
                    $orig_calendar = $this->db->table('event_calendar')->select('id')->where('id_event', $is_other['id'])->get()->getRowArray();
                    if(!empty($orig_calendar)) {
                        $stats['skipped'][] = $orig_calendar['id'];
                    }
                    continue;
                }
                $type = $this->db->table('event_type_external')->select('*')->where('external', $event['Category']['Type'])->get()->getRowArray();
                $place = $this->db->table('event_place_external')->select('*')->where('external', $event['Object']['Name'])->get()->getRowArray();
                $data = array(
                    'id_page_cont' => $id_content,
                    'id_type' => !empty($type) ? $type['id_type'] : 0,
                    'publish' => 1,
                    'home' => 0,
                    'patronage' => 0,
                    'for_kids' => 0,
                    'recommended' => 0,
                    'template' => 'tpl.php',
                    'comment' => 0,
                    'source' => $source,
                    'id_source' => $event['Id'],
                );
                $is = $this->db->table('event')->select('id')->where('source', $source)->where('id_source', $event['Id'])->where('id_page_cont', $id_content)->get()->getRowArray();
                if(!empty($is)) {
                    $result = $this->db->table('event')->where('id', $is['id'])->update($data);
                    $id = $is['id'];
                } else {
                    $result = $this->db->table('event')->insert($data);
                    $id = $this->db->insertID();
                }
                if($result) {
                    $additional_tags = array();
                    if(!empty($data['id_type'])) {
                        $additional_tags['type'] = $data['id_type'];
                    }
                    if(!empty($place)) {
                        $additional_tags['place'] = array();
                        $additional_tags['place'][] = $place['id_place'];
                    } elseif(!empty($event['Object']['Name'])) {
                        $additional_tags['text'] = $event['Object']['Name'];
                    }
                    $lang = $this->db->table('event_lang el')->join('links l', 'l.id=el.id_link')->select('el.*,l.link')->where('el.id_lang', 1)->where('el.id_event', $id)->get()->getRowArray();
                    $lang_data = array(
                        1 => array(
                            'name' => $event['Artist']['Name'],
                            'subname' => !empty($lang) && !empty($lang['subname']) ? $lang['subname'] : '',
                            'link' => !empty($lang) && !empty($lang['link']) ? $lang['link'] : '',
                            'id_link' => !empty($lang) && !empty($lang['id_link']) ? $lang['id_link'] : '',
                            'introduction' => !empty($lang) && !empty($lang['introduction']) ? $lang['introduction'] : '',
                            'content' => preg_replace('/(<a\b[^><]*)>/i', '$1 rel="nofollow">', $event['Description']),
                            'tickets' => $event['Link'],
                            'price' => $event['TicketsInfo']['Price'] . ' ' . $event['TicketsInfo']['Currency'],
                            'comments' => '',
                            'tags' => '',
                        )
                    );
                    $this->saveEventLang($id, $lang_data, $id_content);
                    if(!empty($event['Images']['Image'])) {
                        $parse = parse_url($event['Images']['Image']);
                        $tmp = explode('/', $parse['path']);
                        $file_name = $tmp[count($tmp) - 1];
                        $is_file = $this->db->table('event_files')->select('id,path,basename')->where('id_event', $id)->where('field', 'photo')->get()->getRowArray();
                        if(empty($is_file) || $is_file['basename'] != $file_name) {
                            $file = file_get_contents($event['Images']['Image']);
                            $file_path = 'event/' . date('Ymd') . '/' . $file_name;
                            if(!empty($is_file) && file_exists(WRITEPATH . 'uploads/' . $is_file['basename'])) {
                                unlink(WRITEPATH . 'uploads/' . $is_file['basename']);
                            }
                            $write_result = write_file(WRITEPATH . 'uploads/' . $file_path, $file);
                            if($write_result) {
                                $file_info = pathinfo(WRITEPATH . 'uploads/' . $file_path);
                                $mime_type = mime_content_type(WRITEPATH . 'uploads/' . $file_path);
                                $data = array(
                                    'id_event' => $id,
                                    'field' => 'photo',
                                    'name' => $file_info['filename'],
                                    'basename' => $file_info['basename'],
                                    'path' => $file_path,
                                    'mime' => $mime_type,
                                    'type' => file_type($mime_type),
                                    'ext' => $file_info['extension'],
                                    'order' => 0,
                                    'publish' => 1,
                                    'crop_dimension' => ''
                                );
                                if(!empty($is_file)) {
                                    $this->db->table('event_files')->where('id', $is_file['id'])->update($data);
                                } else {
                                    $this->db->table('event_files')->insert($data);
                                }
                            }
                        }
                    }
                    $calendar_data = array(
                        'id_place' => !empty($place) ? $place['id_place'] : 0,
                        'date_start' => !empty($event['Date']) ? date('Y-m-d', strtotime($event['Date'])) : null,
                        'date_end' => null,
                        'hours' => !empty($event['Date']) ? json_encode(array(date('H:i', strtotime($event['Date'])))) : null,
                        'custom_place' => empty($place) ? $event['Object']['Name'] : '',
                    );
                    $is_calendar = $this->db->table('event_calendar')->select('id,id_event')->where('id_event', $id)->get()->getRowArray();
                    if(!empty($is_calendar)) {
                        $calendar_data['id_event'] = $id;
                        $calendar_data['default'] = 1;
                        $calendar_data['edited_at'] = $edited_at;
                        $this->db->table('event_calendar')->where('id', $is_calendar['id'])->update($calendar_data);
                        $stats['updated'][] = $is_calendar['id'];
                    } else {
                        // Za duplikat uznajemy tylko kolizje w obrebie tego samego bloku - inaczej wydarzenie z innego bloku
                        // o tym samym miejscu i terminie zablokowaloby zalozenie wpisu kalendarza, a bez niego wydarzenie
                        // nie pojawia sie na zadnej liscie (zapytania list startuja z event_calendar).
                        $calendar_where = array();
                        foreach($calendar_data as $field => $value) {
                            $calendar_where['ec.' . $field] = $value;
                        }
                        $is_event = $this->db->table('event_calendar ec')
                                ->join('event e', 'e.id=ec.id_event')
                                ->select('ec.id,ec.id_event')
                                ->where($calendar_where)
                                ->where('e.id_page_cont', $id_content)
                                ->get()->getRowArray();
                        if(empty($is_event)) {
                            $calendar_data['id_event'] = $id;
                            $calendar_data['default'] = 1;
                            $calendar_data['edited_at'] = $edited_at;
                            $this->db->table('event_calendar')->insert($calendar_data);
                            $id_calendar = $this->db->insertID();
                            $stats['created'][] = $id_calendar;
                        } else {
                            $stats['duplicated'][] = $is_event['id'];
                        }
                    }
                }
            }
            $to_remove = $this->db->table('event_calendar ec')
                    ->join('event e', 'e.id=ec.id_event')
                    ->select('ec.id,ec.id_event')
                    ->where('e.source', $source)
                    // Bez tego import jednego bloku odpublikowalby wydarzenia pozostalych blokow z tego samego zrodla.
                    ->where('e.id_page_cont', $id_content)
                    ->groupStart()
                    ->where('ec.date_start >=', date('Y-m-d'))
                    ->orWhere('ec.date_end >=', date('Y-m-d'))
                    ->groupEnd()
                    ->groupStart()
                        ->groupStart()
                            ->where('ec.created_at <', $edited_at)
                            ->where('ec.edited_at', null)
                        ->groupEnd()
                        ->orGroupStart()
                            ->where('ec.edited_at !=', null)
                            ->where('ec.edited_at <', $edited_at)
                        ->groupEnd()
                    ->groupEnd()
                    ->get()->getResultArray();
            if(!empty($to_remove)) {
                foreach($to_remove as $rm) {
                    $stats['removed'][] = $rm['id'];
                    $this->db->table('event')->set('publish', 0)->where('id', $rm['id_event'])->update();
                }
            }
        }
        $this->db->transComplete();
        $stats['result'] = $this->db->transStatus();
        return $stats;
    }
}