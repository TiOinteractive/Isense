<?php

namespace Modules\Advertisement\Models;

use CodeIgniter\Model;

class AdvertisementModel extends Model {

    protected $table = 'advertisement';
    protected $allowedFields = [
        'type',
        'source',
        'external_id',
        'date_start',
        'date_end',
        'url',
        'hash',
        'publish',
        'edited_at',
        'created_at',
    ];

    public function getAdvertisementById($id, $id_lang) {
        $advertisement = $this->where('id', $id)->first();
        if (!empty($advertisement)) {
            if (!empty($advertisement['date_start']) && !empty($advertisement['date_end'])) {
                $advertisement['date'] = date('d.m.Y', strtotime($advertisement['date_start'])) . ' - ' . date('d.m.Y', strtotime($advertisement['date_end']));
            } else {
                $advertisement['date'] = '';
            }
            $advertisement['lang'] = $this->getAdvertisementLang($id);
            if (!empty($advertisement['lang']) && !empty($advertisement['lang'][$id_lang]) && !empty($advertisement['lang'][$id_lang]['name'])) {
                $advertisement['name'] = $advertisement['lang'][$id_lang]['name'];
            } else {
                $advertisement['name'] = '';
            }
        }
        return $advertisement;
    }

    private function getAdvertisementLang($id_advertisement) {
        $langs = array();
        $data = $this->db->table('advertisement_lang')->where('id_advertisement', $id_advertisement)->orderBy('id_lang')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                if (!empty($d['id_photo'])) {
                    $d['photo'] = $this->db->table('tio_files')->where('id', $d['id_photo'])->limit(1)->get()->getRowArray();
                }
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveAdvertisement($id, $post) {
        
        helper(['file', 'text']);
        if (empty($post))
            return false;
        if (!empty($post['date'])) {
            $tmp = explode('-', $post['date']);
            $post['date_start'] = !empty($tmp) && !empty($tmp[0]) ? date('Y-m-d', strtotime($tmp[0])) : '';
            $post['date_end'] = !empty($tmp) && !empty($tmp[1]) ? date('Y-m-d', strtotime($tmp[1])) : '';
        }
        $data = array(
            'date' => date('Y-m-d', strtotime($post['date'])),
            'type' => $post['type'],
            'source' => $post['source'],
            'external_id' => !empty($post['external_id']) ? $post['external_id'] : null,
            'date_start' => !empty($post['date_start']) ? $post['date_start'] : null,
            'date_end' => !empty($post['date_end']) ? $post['date_end'] : null,
            'url' => !empty($post['url']) ? $post['url'] : null,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
        );
        $this->db->transStart();
        if ($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $hash = random_string('sha1');
            $count = 1;
            do {
                $is = $this->db->table('advertisement')->select('id')->where('hash', $hash)->get()->getRowArray();
                if(!empty($is)) {
                    $hash = random_string('sha1');
                }
                ++$count;
            } while(!empty($is) && $count<=1000);
            $data['hash'] = $hash;
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }

        $this->saveAdvertisementLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    private function saveAdvertisementLang($id_advertisement, $lang_data) {
        if (!empty($lang_data)) {
            foreach ($lang_data as $id_lang => $lang) {
                $data = array(
                    'id_advertisement' => $id_advertisement,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                    'id_photo' => !empty($lang['photo']) && !empty($lang['photo']['id']) ? $lang['photo']['id'] : 0,
                    'code' => $lang['code'],
                );
                $lang = $this->db->table('advertisement_lang')->select('id')->where('id_advertisement', $id_advertisement)->where('id_lang', $id_lang)->get()->getRowArray();
                if (!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('advertisement_lang')->set($data)->where('id_advertisement', $id_advertisement)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('advertisement_lang')->insert($data);
                }
            }
        }
    }
    
    public function deleteAdvertisement($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->db->table('advertisement_lang')->where('id_advertisement', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getAdvertisement($id, $id_lang) 
    {
        $advertisement = $this->db->table('advertisement a')
                ->join('advertisement_lang al', 'a.id=al.id_advertisement')
                ->select('a.id,a.type,a.source,a.external_id,a.date_start,a.date_end,a.url,a.hash,al.name,al.id_photo,al.code,al.id as al_id')
                ->where('a.id', $id)
                ->where('al.id_lang', $id_lang)
                ->groupStart()
                    ->where('a.date_start <=', date('Y-m-d'))
                    ->orWhere('a.date_start', null)
                ->groupEnd()
                ->groupStart()
                    ->where('a.date_end >=', date('Y-m-d'))
                    ->orWhere('a.date_end', null)
                ->groupEnd()
                ->where('a.publish', 1)
                ->get()->getRowArray();
        if(!empty($advertisement)) {
            $this->db->table('advertisement_lang')->where('id', $advertisement['al_id'])->set('views', 'views+1', false)->update();
            switch($advertisement['source']) {
                case 'revive': 
                    if(empty($advertisement['external_id'])) {
                        return null;
                    }
                    switch($advertisement['type']) {
                        case 'background': $advertisement['template'] = 'revive_background.php';
                            break;
                        case 'before_enter': $advertisement['template'] = 'revive_before_enter.php';
                            break;
                        case 'popup': $advertisement['template'] = 'revive_popup.php';
                            break;
                        default: $advertisement['template'] = 'revive_default.php';
                            break;
                    }
                    break;
                default: 
                    if(empty($advertisement['code']) && empty($advertisement['id_photo'])) {
                        return null;
                    }
                    if(!empty($advertisement['id_photo'])) {
                        $advertisement['photo'] = $this->db->table('tio_files')->where('id', $advertisement['id_photo'])->limit(1)->get()->getRowArray();
                    }
                    switch($advertisement['type']) {
                        case 'background': $advertisement['template'] = 'default_background.php';
                            break;
                        case 'before_enter': $advertisement['template'] = 'default_before_enter.php';
                            break;
                        case 'popup': $advertisement['template'] = 'default_popup.php';
                            break;
                        default: $advertisement['template'] = 'default.php';
                            break;
                    }
                    break;
            }
        }
        return $advertisement;
    }

}
