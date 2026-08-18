<?php

namespace Modules\Event\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class EventTypeModel extends Model{

    protected $table = 'event_type';
    
    protected $allowedFields = [
        'publish',
        'search',
        'svg',
        'edited_at',
        'created_at',
    ];
    
    
    public function getEventTypeById($id, $id_lang) 
    {
        $type = $this->where('id', $id)->first();
        if(!empty($type)) {
            $type['lang'] = $this->getEventTypeLang($id);
            if(!empty($type['lang']) && !empty($type['lang'][$id_lang]) && !empty($type['lang'][$id_lang]['name'])) {
                $type['name'] = $type['lang'][$id_lang]['name'];
            } else {
                $type['name'] = '';
            }
        }
        return $type;
    }
    
    private function getEventTypeLang($id_type) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('event_type_lang')->where('id_type', $id_type)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveEventType($id, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;
        $data = array(
            'search' => !empty($post['search']) ? $post['search'] : 0,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
            'svg' => !empty($post['svg']) ? $post['svg'] : NULL,
        );
        $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        
        $this->saveEventTypeLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveEventTypeLang($id_type, $lang_data) 
    {
        if(!empty($lang_data)) {
            $linkClass = new Link();
            foreach($lang_data as $id_lang=>$lang) {
                $is_lang = $this->db->table('event_type_lang')->select('id')->where('id_type', $id_type)->where('id_lang', $id_lang)->get()->getRowArray();
                $slug = mb_url_title(str_replace(array('/', ','), '-', $lang['name']), '-', true);
                $oryginal_slug = $slug;
                $count = 1;
                do {
                    $query = $this->db->table('event_type_lang')->select('id')->where('slug', $slug)->where('id_lang', $id_lang);
                    if(!empty($is_lang)) {
                        $query->where('id !=', $is_lang['id']);
                    }
                    $is = $query->get()->getRowArray();
                    if(!empty($is)) {
                        $slug = $oryginal_slug . '-' . $count;
                    }
                    ++$count;
                } while(!empty($is) && $count<=1000);
                $data = array(
                    'id_type' => $id_type,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                    'name2' => $lang['name2'],
                    'slug' => $slug,
                    'content' => $lang['content'],
                );
                if(!empty($is_lang) && !empty($is_lang['id'])) {
                    $result = $this->db->table('event_type_lang')->set($data)->where('id_type', $id_type)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('event_type_lang')->insert($data);
                }
            }
        }
    }
    
    public function deleteEventType($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('event_type_lang')->select('id_link')->where('id_type', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $this->db->table('event_type_lang')->where('id_type', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getTypesForList($id_lang) {
        $types = $this->db->table('event_type et')->join('event_type_lang etl', 'et.id=etl.id_type')->select('et.id,etl.name')->where('et.publish', 1)->where('etl.id_lang', $id_lang)->orderBy('etl.name ASC')->get()->getResultArray();
        return $types;
    }
    
    public function getTypesForEventsList($id_lang, $id_page_cont = null) {
        $types = array();
        $list = $this->db->table('event_type et')->join('event_type_lang etl', 'et.id=etl.id_type')->select('et.id,et.svg,etl.name,etl.name2,etl.slug')->where('et.publish', 1)->where('et.search', 1)->where('etl.id_lang', $id_lang)->orderBy('etl.name ASC')->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $count_query = $this->db->table('event e')->join('event_calendar ec', 'e.id=ec.id_event')->where('e.id_type', $l['id'])->where('e.publish', 1)->groupStart()->where('ec.date_start >=', date('Y-m-d'))->orWhere('ec.date_end >=', date('Y-m-d'))->groupEnd();
                if($id_page_cont) {
                    $count_query->whereIn('e.id_page_cont', $id_page_cont);
                }
                $l['count'] = $count_query->countAllResults();
                $types[$l['id']] = $l;
            }
        }
        return $types;
    }
    
    public function getTypesStructure($id_lang, $id_parent = 0, $exclude_ids = array(), $level = 0) {
        $db = $this->db->table('event_type et')
                ->join('event_type_lang etl', 'et.id = etl.id_type')
                ->select('et.id,et.id_parent,et.publish,etl.name')
                ->where('etl.id_lang', $id_lang)
                ->where('et.id_parent', $id_parent);
        if (!empty($exclude_ids)) {
            $db->whereNotIn('et.id', $exclude_ids);
        }
        $types = $db->orderBy('etl.name', 'ASC')->get()->getResultArray();
        if (!empty($types)) {
            foreach ($types as $k => $type) {
                $types[$k]['level'] = $level;
                $types[$k]['list'] = $this->getTypesStructure($id_lang, $type['id'], $exclude_ids, $level + 1);
            }
        }
        return $types;
    }

    public function assignExternalTypes($source, $assign) {
        $this->db->transStart();
        if(!empty($assign) && !empty($source)) {
            foreach($assign as $external=>$id_type) {
                if(!empty($id_type)) {
                    $is = $this->db->table('event_type_external')->select('id')->where('external', $external)->where('source', $source)->get()->getRowArray();
                    $data = array(
                        'id_type' => $id_type,
                        'source' => $source,
                        'external' => $external,
                    );
                    if(!empty($is)) {
                        $this->db->table('event_type_external')->where('id', $is['id'])->update($data);
                    } else {
                        $this->db->table('event_type_external')->insert($data);
                    }
                }
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getExternalTypesForList($source) {
        $types = array();
        if(!empty($source)) {
            $list = $this->db->table('event_type_external')->select('id,id_type,external')->where('source', $source)->get()->getResultArray();
            if(!empty($list)) {
                foreach($list as $l) {
                    $types[$l['external']] = $l['id_type'];
                }
            }
        }
        return $types;
    }
}