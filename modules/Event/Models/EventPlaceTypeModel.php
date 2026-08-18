<?php

namespace Modules\Event\Models;

use CodeIgniter\Model;
use App\Libraries\Link;

class EventPlaceTypeModel extends Model {

    protected $table = 'event_place_type';
    protected $allowedFields = [
        'id_parent',
        'cinema',
        'publish',
        'order',
        'edited_at',
        'created_at',
    ];

    public function getEventPlaceTypeById($id, $id_lang) {
        $type = $this->where('id', $id)->first();
        if (!empty($type)) {
            $type['lang'] = $this->getEventPlaceTypeLang($id);
            if (!empty($type['lang']) && !empty($type['lang'][$id_lang]) && !empty($type['lang'][$id_lang]['name'])) {
                $type['name'] = $type['lang'][$id_lang]['name'];
            } else {
                $type['name'] = '';
            }
        }
        return $type;
    }

    private function getEventPlaceTypeLang($id_type) {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('event_place_type_lang')->where('id_type', $id_type)->orderBy('id_lang')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveEventPlaceType($id, $post) {
        helper(['file']);
        if (empty($post))
            return false;
        $data = array(
            'id_parent' => !empty($post['id_parent']) ? $post['id_parent'] : 0,
            'cinema' => !empty($post['cinema']) ? $post['cinema'] : 0,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
        );
        $this->db->transStart();
        if ($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }

        $this->saveEventPlaceTypeLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    private function saveEventPlaceTypeLang($id_type, $lang_data) {
        if (!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Event')->get()->getRowArray();
            $linkClass = new Link();
            foreach ($lang_data as $id_lang => $lang) {
                $data = array(
                    'id_type' => $id_type,
                    'id_lang' => $id_lang,
                    'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0, false, null, 'place_type'),
                    'name' => $lang['name'],
                    'content' => $lang['content'],
                );
                $lang = $this->db->table('event_place_type_lang')->select('id')->where('id_type', $id_type)->where('id_lang', $id_lang)->get()->getRowArray();
                if (!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('event_place_type_lang')->set($data)->where('id_type', $id_type)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('event_place_type_lang')->insert($data);
                }
            }
        }
    }

    public function deletePlaceType($id) {
        if (empty($id))
            return false;
        $this->db->transStart();
        $langs = $this->db->table('event_place_type_lang')->select('id_link')->where('id_type', $id)->get()->getResultArray();
        if (!empty($langs)) {
            foreach ($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $this->db->table('event_place_type_lang')->where('id_type', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function getTypesForList($id_lang) {
        $types = $this->db->table('event_place_type ept')->join('event_place_type_lang eptl', 'ept.id=eptl.id_type')->select('ept.id,eptl.name')->where('ept.publish', 1)->where('eptl.id_lang', $id_lang)->orderBy('eptl.name ASC')->get()->getResultArray();
        return $types;
    }

    public function getTypesStructure($id_lang, $id_parent = 0, $exclude_ids = array(), $level = 0) {
        $db = $this->db->table('event_place_type ept')
                ->join('event_place_type_lang eptl', 'ept.id = eptl.id_type')
                ->select('ept.id,ept.id_parent,ept.publish,eptl.name,order')
                ->where('eptl.id_lang', $id_lang)
                ->where('ept.id_parent', $id_parent);
        if (!empty($exclude_ids)) {
            $db->whereNotIn('ept.id', $exclude_ids);
        }
        $types = $db->orderBy('ept.order', 'ASC')->get()->getResultArray();
        if (!empty($types)) {
            foreach ($types as $k => $type) {
                $types[$k]['level'] = $level;
                $types[$k]['list'] = $this->getTypesStructure($id_lang, $type['id'], $exclude_ids, $level + 1);
            }
        }
        return $types;
    }

    public function savePlaceOrder($data) {
        $this->db->transStart();
        if (!empty($data['order'])) {
            foreach ($data['order'] as $order => $id) {
                $this->db->table('event_place_type')->set('order', $order, FALSE)->where('id', $id)->update();
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
