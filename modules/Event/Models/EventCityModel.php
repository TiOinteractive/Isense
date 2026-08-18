<?php

namespace Modules\Event\Models;

use CodeIgniter\Model;

class EventCityModel extends Model{

    protected $table = 'event_city';

    protected $allowedFields = [
        'publish',
        'edited_at',
        'created_at',
    ];


    public function getEventCityById($id, $id_lang)
    {
        $city = $this->where('id', $id)->first();
        if(!empty($city)) {
            $city['lang'] = $this->getEventCityLang($id);
            if(!empty($city['lang']) && !empty($city['lang'][$id_lang]) && !empty($city['lang'][$id_lang]['name'])) {
                $city['name'] = $city['lang'][$id_lang]['name'];
            } else {
                $city['name'] = '';
            }
        }
        return $city;
    }

    private function getEventCityLang($id_city)
    {
        $langs = array();
        $data = $this->db->table('event_city_lang')->where('id_city', $id_city)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveEventCity($id, $post)
    {
        if(empty($post)) return false;
        $data = array(
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

        $this->saveEventCityLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    private function saveEventCityLang($id_city, $lang_data)
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $is_lang = $this->db->table('event_city_lang')->select('id')->where('id_city', $id_city)->where('id_lang', $id_lang)->get()->getRowArray();
                // Slug sluzy do wyszukiwania, nie do adresow - miasto nie ma wlasnej podstrony, wiec nie zapisujemy linku.
                $slug = mb_url_title(str_replace(array('/', ','), '-', $lang['name']), '-', true);
                $oryginal_slug = $slug;
                $count = 1;
                do {
                    $query = $this->db->table('event_city_lang')->select('id')->where('slug', $slug)->where('id_lang', $id_lang);
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
                    'id_city' => $id_city,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                    'slug' => $slug,
                );
                if(!empty($is_lang) && !empty($is_lang['id'])) {
                    $this->db->table('event_city_lang')->set($data)->where('id_city', $id_city)->where('id_lang', $id_lang)->update();
                } else {
                    $this->db->table('event_city_lang')->insert($data);
                }
            }
        }
    }

    public function deleteEventCity($id)
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->db->table('event_city_lang')->where('id_city', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    /**
     * Domyslnie zwraca WSZYSTKIE miasta - select w adminie musi pokazac takze niepublikowane,
     * inaczej miasto przypisane do lokalu znikneloby z listy i przy zapisie po cichu wyzerowalo id_city.
     * Filtr publikacji wlaczamy tylko tam, gdzie lista trafia do uzytkownika.
     */
    public function getCitiesForList($id_lang, $publish_only = false) {
        $query = $this->db->table('event_city ec')->join('event_city_lang ecl', 'ec.id=ecl.id_city')->select('ec.id,ec.publish,ecl.name')->where('ecl.id_lang', $id_lang);
        if($publish_only) {
            $query->where('ec.publish', 1);
        }
        return $query->orderBy('ecl.name ASC')->get()->getResultArray();
    }
}
