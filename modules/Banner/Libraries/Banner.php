<?php

namespace Modules\Banner\Libraries;

use Modules\Banner\Models\BannerModel;

class Banner
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->bannerModel = new BannerModel();
    }
    
    public function index($content, $id_lang, $slug='') 
    {
        $zone = $this->bannerModel->db
                ->table('baner_zone bz')
                ->join('baner_zone_lang bzl', 'bz.id=bzl.id_zone')
                ->select('bz.id,bzl.name,bz.order_sort,bz.number_to_show')
                ->where('bz.id', $content['id_element'])
                ->where('bz.publish', 1)
                ->where('bzl.id_lang', $id_lang)
                ->get()
                ->getRowArray();
        if(!empty($zone)) {
            $query = $this->bannerModel
                ->join('baner_baner_lang bbl', 'baner_baner.id=bbl.id_baner')
                ->join('files f1', 'f1.id=bbl.id_file', 'left')
                ->join('files f2', 'f2.id=bbl.id_file_mobile', 'left')
                ->select('baner_baner.id,baner_baner.url_target,baner_baner.url_rel,bbl.name,bbl.caption,bbl.url,f1.path,f2.path as m_path')
                ->where('baner_baner.publish', 1)
				->where('baner_baner.id_zone',$zone['id'])
                ->where('bbl.id_lang', $id_lang);
			if($zone['number_to_show']>0) {
               $query->limit($zone['number_to_show']);
            }					
            switch($zone['order_sort']) {
                case 'random': $query->orderBy('baner_baner.id', 'RANDOM');
                    break;
                case 'date': $query->orderBy('baner_baner.date_start', 'ASC');
                    break;
                case 'order': 
                default: $query->orderBy('baner_baner.order', 'ASC');
                    break;
            }
            if(!empty($zone['number_to_show']) && $zone['number_to_show'] > 0) {
                $query->limit($zone['number_to_show']);
            }
            $zone['banners'] = $query->find();
            if(!empty($zone['banners'])) {
                $ids = array();
                foreach($zone['banners'] as $b) {
                    $ids[] = $b['id'];
                }
                $this->bannerModel->db->table('baner_baner_lang')->whereIn('id_baner', $ids)->where('id_lang', $id_lang)->set('views', 'views+1', false)->update();
            }
        }
        return $zone;
    }
	
	 public function assets($slug='', $template='', $id_banner=0, $data=array()) {
        $assets = array(
            'js' => array(),
            'css' => array()
        );
        switch($slug) {
            default :
                $assets['css'][] = '/assets/css/slick.css';
                $assets['js'][] = '/assets/js/slick.min.js';
                $assets['js'][] = '/assets/js/banner.js';
                $assets['js'][] = '/assets/js/page.js';
                break;
        }
        return $assets;
    }
	

    public function showBanner($id_zone, $id_lang, $locale, $template='tpl') {
        $zone = $this->bannerModel->db
                ->table('baner_zone bz')
                ->join('baner_zone_lang bzl', 'bz.id=bzl.id_zone')
                ->select('bz.id,bzl.name,bz.order_sort,bz.number_to_show')
                ->where('bz.id', $id_zone)
                ->where('bz.publish', 1)
                ->where('bzl.id_lang', $id_lang)
                ->get()
                ->getRowArray();
        if(!empty($zone)) {
            $query = $this->bannerModel
                ->join('baner_baner_lang bbl', 'baner_baner.id=bbl.id_baner')
                ->join('files f1', 'f1.id=bbl.id_file', 'left')
                ->join('files f2', 'f2.id=bbl.id_file_mobile', 'left')
                ->select('baner_baner.id,baner_baner.url_target,baner_baner.url_rel,bbl.name,bbl.caption,bbl.url,f1.path,f2.path as m_path')
                ->where('baner_baner.publish', 1)
				->where('baner_baner.id_zone',$zone['id'])
                ->where('bbl.id_lang', $id_lang);
			if($zone['number_to_show']>0) {
               $query->limit($zone['number_to_show']);
            }					
            switch($zone['order_sort']) {
                case 'random': $query->orderBy('baner_baner.id', 'RANDOM');
                    break;
                case 'date': $query->orderBy('baner_baner.date_start', 'ASC');
                    break;
                case 'order': 
                default: $query->orderBy('baner_baner.order', 'ASC');
                    break;
            }
            if(!empty($zone['number_to_show']) && $zone['number_to_show'] > 0) {
                $query->limit($zone['number_to_show']);
            }
            $zone['banners'] = $query->find();
            return view('Modules\Banner\Views\user\\' . $template, array('data' => $zone, 'title' => ' '));
        } else {
            return '';
        }
    }
    
    public function ajax($post, $id_lang, $module_data, $link) {
        $response = array();
        if(!empty($post['action'])) {
            switch($post['action']) {
                case 'click':
                    if(!empty($post['id'])) {
                        $response['result'] = $this->bannerModel->db->table('baner_baner_lang')->where('id_baner', $post['id'])->where('id_lang', $id_lang)->set('clicks', 'clicks+1', false)->update();
                    }
                    break;
            }
        }
        return $this->response->setJSON($response);
    }
	
}