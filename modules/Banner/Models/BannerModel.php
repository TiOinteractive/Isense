<?php

namespace Modules\Banner\Models;

use CodeIgniter\Model;

class BannerModel extends Model {

    protected $table = 'baner_baner';
    protected $allowedFields = [
        'name',
        'id_zone',
        'order',
        'publish',
        'url_rel',
        'url_target',
        'edited_at',
        'created_at',
        'date_start',
        'date_end'
    ];

    public function getBannerById($id, $id_lang) {
        $baner = $this->where('id', $id)->first();
        if (!empty($baner)) {
            $baner['lang'] = $this->getBannerLang($id);
            if(!empty($baner['lang']) && !empty($baner['lang'][$id_lang]) && !empty($baner['lang'][$id_lang]['name'])) {
                $baner['name'] = $baner['lang'][$id_lang]['name'];
            } else {
                $baner['name'] = '';
            }
            $query = $this->db->table('baner_zone z')
                    ->join('baner_zone_lang nl', 'z.id=nl.id_zone')
                    ->join('language l', 'l.id=nl.id_lang')
                    ->select('z.id,nl.name')
                    ->where(['l.default' => 1, 'nl.id_zone' => $baner['id_zone']]);
            $baner['strefa_info'] = $query->get()->getRowArray();
        }
        return $baner;
    }

    public function getZoneById($id, $id_lang) {
        $zone = $this->db->table('baner_zone')->where('id', $id)->get()->getRowArray();
        if (!empty($zone)) {
            $zone['lang'] = $this->getZoneLang($id);
            if(!empty($zone['lang']) && !empty($zone['lang'][$id_lang]) && !empty($zone['lang'][$id_lang]['name'])) {
                $zone['name'] = $zone['lang'][$id_lang]['name'];
            } else {
                $zone['name'] = '';
            }
        }
        return $zone;
    }

    public function getZoneLang($id) {
        $langs = array();
        $data = $this->db->table('baner_zone_lang')->where('id_zone', $id)->orderBy('id_lang')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $langs[$d['id_lang']] = array(
                    'name' => $d['name']
                );
            }
        }
        return $langs;
    }

    public function getBannerLang($id) {
        $langs = array();
        $data = $this->db->table('baner_baner_lang')->where('id_baner', $id)->orderBy('id_lang')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $langs[$d['id_lang']] = array(
                    'name' => $d['name'],
                    'caption' => $d['caption'],
                    'url' => $d['url'],
                    'id_file' => $d['id_file'],
                    'id_file_mobile' => $d['id_file_mobile'],
                );

                if (!empty($d['id_file'])) {
                    $langs[$d['id_lang']]['file'] = $this->db->table('tio_files')->where('id', $d['id_file'])->limit(1)->get()->getRowArray();
                }
                if (!empty($d['id_file_mobile'])) {
                    $langs[$d['id_lang']]['file_mobile'] = $this->db->table('tio_files')->where('id', $d['id_file_mobile'])->limit(1)->get()->getRowArray();
                }
            }
        }
        return $langs;
    }

    public function getZones($action, $id, $slug) {
        $zones = array();

        $defaultLang = $this->db->table('language')->select('id')->where('slug', $slug)->where('publish', 1)->get()->getRow();
        if ($action == "add-zone") {
            $zones = $this->db->table('baner_zone')->select('id,publish')->where('id', $id)->orderBy('id', 'ASC')->get()->getResultArray();
        } else {
            $zones = $this->db->table('baner_zone')->select('id,publish')->orderBy('id', 'ASC')->get()->getResultArray();
        }
        if (!empty($zones)) {
            foreach ($zones as $k => $s) {
                $zones[$k]['lang'] = $this->db->table('baner_zone_lang')->select('name')->where('id_zone', $s['id'])->where('id_lang', $defaultLang->id)->orderBy('id_lang', 'ASC')->get()->getRow();
            }
        }
        return $zones;
    }

    public function getSliderSlideLang($id_slide) {
        $langs = array();
        $data = $this->db->table('slider_slide_lang')->where('id_slide', $id_slide)->orderBy('id_lang', 'ASC')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveBanner($id, $action, $post) {
        $data = array();
        $data['id_zone'] = $post['banner']['zone'];
        $data['publish'] = !empty($post['banner']['publish']) ? $post['banner']['publish'] : 0;
        if (isset($post['banner']['dates']) and $post['banner']['dates'] == 1 and isset($post['banner']['data']) and!isset($post['banner']['date_single'])) {
            $dat = explode('-', $post['banner']['data']);
            $data['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $dat[0])));
            $data['date_end'] = date('Y-m-d', strtotime(str_replace('/', '-', $dat[1])));
        } elseif (isset($post['banner']['date_single']) and $post['banner']['date_single'] == 1 and $post['banner']['dates'] == 1 and isset($post['banner']['data'])) {
            $data['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['banner']['data'])));
            $data['date_end'] = NULL;
        } else {
            $data['date_start'] = NULL;
            $data['date_end'] = NULL;
        }
        $data['url_target'] = $post['banner']['url_target'];
        $data['url_rel'] = $post['banner']['url_rel'];
        if ($action != 'add-zone' and $id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } elseif (($action == 'add-zone' and $id) or!$id) {
            $this->set('order', '`order`+1', FALSE)->where('id_zone', $data['id_zone'])->update();
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        $this->saveBannerLang($this->id, $post['banner']['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function saveZone($id, $action, $post) {
        $data = array();
        $data['number_to_show'] = $post['number_to_show'];
        $data['publish'] = !empty($post['publish']) ? $post['publish'] : 0;
        $data['order_sort'] = $post['order_sort'];
        if ($id) {
            $result = $this->db->table('baner_zone')->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->db->table('baner_zone')->insert($data);
            $this->id = $this->db->InsertID();
        }
        $this->saveZoneLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    private function saveZoneLang($id_zone, $lang_data) {
        if (!empty($lang_data)) {
            foreach ($lang_data as $id_lang => $lang) {
                $data = array(
                    'id_zone' => $id_zone,
                    'name' => $lang['name'],
                    'id_lang' => $id_lang
                );
                $zone = $this->db->table('baner_zone_lang')->select('id')->where('id_zone', $id_zone)->where('id_lang', $id_lang)->get()->getRowArray();
                if (!empty($zone) && !empty($zone['id'])) {
                    $result = $this->db->table('baner_zone_lang')->set($data)->where('id_zone', $id_zone)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('baner_zone_lang')->insert($data);
                }
            }
        }
    }

    private function saveBannerLang($id_baner, $lang_data) {
        if (!empty($lang_data)) {
            foreach ($lang_data as $id_lang => $lang) {
                $data = array(
                    'id_baner' => $id_baner,
                    'id_lang' => $id_lang,
                    'caption' => $lang['caption'],
                    'name' => $lang['name'],
                    'url' => $lang['url'],
                    'id_file' => !empty($lang['file']['id']) ? $lang['file']['id'] : Null,
                    'id_file_mobile' => !empty($lang['file_mobile']['id']) ? $lang['file_mobile']['id'] : Null
                );
                $baner = $this->db->table('baner_baner_lang')->select('id')->where('id_baner', $id_baner)->where('id_lang', $id_lang)->get()->getRowArray();
                if (!empty($baner) && !empty($baner['id'])) {
                    $result = $this->db->table('baner_baner_lang')->set($data)->where('id_baner', $id_baner)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('baner_baner_lang')->insert($data);
                }
            }
        }
    }

    public function deleteZone($id) {
        if (empty($id))
            return false;
        $banner_list = $this->db->table('baner_baner')->select('id')->where('id_zone', $id)->get()->getResultArray();
        if (!empty($banner_list)) {
            foreach ($banner_list as $ban) {
                $this->db->table('baner_baner_lang')->where('id_baner', $ban['id'])->delete();
                $this->db->table('baner_baner')->where('id', $ban['id'])->delete();
            }
        }
        $this->db->table('baner_zone_lang')->where('id_zone', $id)->delete();
        $this->db->table('baner_zone')->where('id', $id)->delete();
        return true;
    }

    public function getBannersZone($id, $sort) {
        $this->request = \Config\Services::request();
        $get = $this->request->getGet();
        if (empty($get['order'])) {
            if ($sort == "date") {
                $get['order'] = 'date;desc';
            } else {
                $get['order'] = 'order;asc';
            }
            $get['publish'] = 'active';
        }
        $query = $this
                ->join('baner_baner_lang nl', 'baner_baner.id=nl.id_baner')
                ->join('files f', 'f.id=nl.id_file', 'left')
                ->join('language l', 'l.id=nl.id_lang')
                ->select('baner_baner.id,baner_baner.publish,baner_baner.order,baner_baner.date_start,f.path,baner_baner.date_end,nl.name')
                ->where('l.default', 1)
                ->where('id_zone', $id);

        if (isset($get['name']) and $get['name'] != '') {
            $query->like('nl.name', $get['name']);
        }
        if (isset($get['date']) and $get['date'] != '') {
            $date_t = explode(' - ', $get['date']);
            $query->groupStart();
            $query->groupStart();
            $query->Where(array('date_end' => null, 'date_start' => null));
            $query->groupEnd();
            $query->orGroupStart();
            $query->Where(array('date_end' => null, 'date_start>=' => date("Y-m-d", strtotime($date_t[0])), 'date_start<=' => date("Y-m-d", strtotime($date_t[1]))));
            $query->groupEnd();
            $query->orGroupStart();
            $query->Where(array('date_end>=' => date("Y-m-d", strtotime($date_t[0])), 'date_start<=' => date("Y-m-d", strtotime($date_t[0]))));
            $query->groupEnd();
            $query->orGroupStart();
            $query->Where(array('date_end<=' => date("Y-m-d", strtotime($date_t[1])), 'date_end>=' => date("Y-m-d", strtotime($date_t[0]))));
            $query->groupEnd();
            $query->orGroupStart();
            $query->Where(array('date_start>=' => date("Y-m-d", strtotime($date_t[0])), 'date_start<=' => date("Y-m-d", strtotime($date_t[1]))));
            $query->groupEnd();
            $query->groupEnd();
        }

        switch ($get['order']) {
            case 'name;asc': $query->orderBy('nl.name', 'ASC');
                break;
            case 'name;desc': $query->orderBy('nl.name', 'DESC');
                break;
            case 'date;asc': $query->orderBy('baner_baner.date_end', 'ASC');
                break;
            case 'date;desc': $query->orderBy('baner_baner.date_end', 'DESC');
                $query->orderBy('baner_baner.date_start', 'DESC');
                break;
            case 'order;desc': $query->orderBy('baner_baner.order', 'DESC');
                break;
            case 'order;asc':
            default: $query->orderBy('baner_baner.order', 'ASC');
                break;
        }

        switch ($get['publish']) {
            case 0:
                $query->where('baner_baner.publish', 0);
                break;
            case 1:
                $query->where('baner_baner.publish', 1);
                break;
            case 'active':
                $query->groupStart();
                $query->where('baner_baner.publish', 1);
                $query->groupStart();
                $query->Where(array('date_end' => null, 'date_start' => null));
                $query->groupEnd();
                $query->orGroupStart();
                $query->Where(array('date_end' => null, 'date_start<=' => date("Y-m-d")));
                $query->groupEnd();
                $query->orGroupStart();
                $query->Where(array('date_end>=' => date("Y-m-d"), 'date_start<=' => date("Y-m-d")));
                $query->groupEnd();
                $query->groupEnd();
                break;
            case 'notactive':
                $query->groupStart();
                $query->Where(array('date_start>' => date("Y-m-d")));
                $query->orWhere(array('date_end<' => date("Y-m-d")));
                $query->orWhere('baner_baner.publish', 0);
                $query->groupEnd();
                break;
        }

        $list_banners['list'] = $query->paginate(20);
        $list_banners['pager'] = $this->pager;
        $list_banners['filters'] = $get;
        if(!empty($list_banners['list'])) {
            foreach($list_banners['list'] as $k=>$b) {
                $t = $this->db->table('baner_baner_lang')->select('SUM(clicks) as clicks, SUM(views) as views', false)->where('id_baner', $b['id'])->get()->getRowArray();
                $list_banners['list'][$k]['clicks'] = $t['clicks'];
                $list_banners['list'][$k]['views'] = $t['views'];
            }
        }
        return $list_banners;
    }

    public function deleteBaner($id) {
        if (empty($id))
            return false;

        $b_order = $this->select('order')->where('id', $id)->first();
        $this->db->table('baner_baner_lang')->where('id_baner', $id)->delete();
        $this->db->table('baner_baner')->where('id', $id)->delete();
        $this->set('order', '`order`-1', FALSE)->where('order >', $b_order['order'])->update();
        return true;
    }
    
    public function getElementPhotosForSitemap($id) 
    {
        $photos = array();
        $zone = $this->db->table('baner_zone')->select('id')->where('id', $id)->where('publish', 1)->get()->getRowArray();
        if(empty($zone)) return array();
        $banners = $this->db->table('baner_baner bb')->select('bb.id')->where('bb.id_zone', $id)->get()->getResultArray();
        if(!empty($banners)) {
            foreach($banners as $banner) {
                $lang = $this->db->table('baner_baner_lang bbl')->join('files f1', 'f1.id=bbl.id_file', 'left')->join('files f2', 'f2.id=bbl.id_file_mobile', 'left')->select('bbl.name,bbl.caption,f1.path as path1,f2.path as path2')->where('bbl.id_baner', $banner['id'])->get()->getResultArray();
                if(!empty($lang)) {
                    foreach($lang as $l) {
                        if(!empty($l['path1'])) {
                            $photos[] = array(
                                'path' => $l['path1'],
                                'caption' => $l['name'] ? $l['name'] : $l['caption']
                            );
                        }
                        if(!empty($l['path2'])) {
                            $photos[] = array(
                                'path' => $l['path2'],
                                'caption' => $l['name'] ? $l['name'] : $l['caption']
                            );
                        }
                    }
                }
            }
        }
        return $photos;
    }
}
