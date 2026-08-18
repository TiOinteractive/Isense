<?php

namespace App\Libraries;

use App\Models\MenuModel;

class Menu
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->menuModel = new MenuModel();
    }
    
    public function index($content, $id_lang, $slug='') 
    {
        if(!empty($content['id_element'])) {
            $id_menu = $content['id_element'];
            $check_menu = $this->pageModel->db->table('menu')->select('id')->where('id', $id_menu)->where('publish', 1)->get()->getRowArray();
            if(!empty($check_menu)) {
                $url = substr(uri_string(), 1);
                if($this->locale) {
                    $url = substr($url, strlen($this->locale) + 1);
                }
                $active_ids = array(
                    'menu' => array(),
                    'page' => array(),
                );
                $current = $this->menuModel->db->table('links l')->select('l.id,l.link,m.slug as module')->join('module m', 'l.id_module=m.id', 'left')->where('l.link', $url)->where('l.id_lang', $id_lang)->get()->getRowArray();
                if(!empty($current)) {
                    switch(strtolower($current['module'])) {
                        case 'news': 
                            $element = $this->menuModel->db->table('news_lang nl')->join('menu_item mi', 'mi.id_target=nl.id_news')->select('mi.id,mi.id_parent,mi.id_target')->where('nl.id_link', $current['id'])->where('nl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'news')->get()->getRowArray();
                            $page = $this->menuModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('page_content pc', 'pc.id=n.id_page_cont')->select('pc.id_page')->where('nl.id_link', $current['id'])->where('nl.id_lang', $id_lang)->get()->getRowArray();
                            break;
                        case 'category': $element = $this->menuModel->db->table('category_lang cl')->join('menu_item mi', 'mi.id_target=cl.id_category')->select('mi.id,mi.id_parent,mi.id_target')->where('cl.id_link', $current['id'])->where('cl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'category')->get()->getRowArray();
                            break;
                        case 'gallery': 
                            $element = $this->menuModel->db->table('gallery_lang gl')->join('menu_item mi', 'mi.id_target=gl.id_gallery')->select('mi.id,mi.id_parent,mi.id_target')->where('gl.id_link', $current['id'])->where('gl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'gallery')->get()->getRowArray();
                            $page = $this->menuModel->db->table('gallery g')->join('gallery_lang gl', 'g.id=gl.id_gallery')->join('page_content pc', 'pc.id=g.id_page_cont')->select('pc.id_page')->where('gl.id_link', $current['id'])->where('gl.id_lang', $id_lang)->get()->getRowArray();
                            break;
                        default: 
                            $element = $this->menuModel->db->table('page_lang pl')->join('menu_item mi', 'mi.id_target=pl.id_page')->select('mi.id,mi.id_parent,mi.id_target,pl.id_page')->where('pl.id_link', $current['id'])->where('pl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'page')->get()->getRowArray();
                            $page = $this->menuModel->db->table('page_lang pl')->select('pl.id_page')->where('pl.id_link', $current['id'])->where('pl.id_lang', $id_lang)->get()->getRowArray();
                            break;
                    }
                }
                if(!empty($element)) {
                    $active_ids['menu'] = array($element['id']);
                    $active_ids['menu'] = array_merge($active_ids['menu'], $this->getMenuParentsId($id_menu, $element['id_parent']));

                } elseif(!empty($page)) {
                    $active_ids['page'] = $this->getPageParentsIds($page['id_page']);
                }
                $menu = $this->getMenuElements($id_menu, $id_lang, $this->locale, 0, $active_ids);
                return $menu;
            }
        }
        return null;
    }
    
    private function getPageParentsIds($re_id) 
    {
        $ids = array();
        $page = $this->menuModel->db->table('page')->select('id,re_id')->where('id', $re_id)->get()->getRowArray();
        if(!empty($page)) {
            $ids[] = $page['id'];
            if(!empty($page['re_id'])) {
                $ids = array_merge($ids, $this->getPageParentsIds($page['re_id']));
            }
        }
        return $ids;
    }
    
    
    private function getMenuElements($id_menu, $id_lang, $lang_slug, $id_parent=0, $active_ids=array()) 
    {
        $items = $this->menuModel->db->table('menu_item mi')->join('menu_item_lang mil', 'mi.id=mil.id_menu_item')->select('mi.id,mi.target,mi.id_photo,mi.id_target,mi.type,mi.svg,mil.name,mil.url,mil.title')->where('mil.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.id_parent', $id_parent)->orderBy('mi.order', 'ASC')->get()->getResultArray();
        if(!empty($items)) {
            foreach($items as $k=>$item) {
                if(!empty($item['id_photo'])) {	
                    $items[$k]['photo'] = $this->menuModel->db->table('tio_files')->where('id', $item['id_photo'])->limit(1)->get()->getRowArray();
                }	
                if($item['type'] != 'own' && $item['id_target']) {
                    if($item['id'] && !empty($active_ids['menu']) && in_array($item['id'], $active_ids['menu'])) {
                        $items[$k]['active'] = true;
                    } else {
                        $items[$k]['active'] = false;
                    }
                    if($item['type'] == 'page' && $item['id_target'] && !empty($active_ids['page']) && in_array($item['id_target'], $active_ids['page'])) {
                        $items[$k]['active'] = true;
                        switch($item['type']) {
                            case 'event_type':
                                $settings = $this->menuModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_calendar')->where('sl.id_lang', $id_lang)->get()->getRowArray();
                                $lang_data = $this->menuModel->db->table('event_type_lang etl')->select('etl.id_lang,etl.name,etl.slug as url')->where('etl.id_type', $item['id_target'])->where('etl.id_lang', $id_lang)->get()->getRowArray();
                                if(!empty($lang_data)) {
                                    $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . (!empty($settings) ? $settings['value'] : '') . '?type=' . $lang_data['url'];
                                }
                                break;
                            case 'page': 
                                $url = $this->menuModel->db->table('links l')->join('page_lang pl', 'pl.id_link=l.id')->join('page p', 'p.id=pl.id_page')->select('l.link')->where('p.id', $item['id_target'])->where('pl.id_lang', $id_lang)->where('p.publish', 1)->get()->getRowArray();
                                if(!empty($url)) {
                                    $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . $url['link'];
                                }
                                break;
                            case 'news': 
                                $url = $this->menuModel->db->table('links l')->join('news_lang nl', 'nl.id_link=l.id')->join('news n', 'n.id=nl.id_news')->select('l.link')->where('n.id', $item['id_target'])->where('nl.id_lang', $id_lang)->where('n.publish', 1)->get()->getRowArray();
                                if(!empty($url)) {
                                    $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . $url['link'];
                                }
                                break;
                            case 'gallery': 
                                $url = $this->menuModel->db->table('links l')->join('gallery_lang gl', 'gl.id_link=l.id')->join('gallery g', 'g.id=gl.id_gallery')->select('l.link')->where('g.id', $item['id_target'])->where('gl.id_lang', $id_lang)->where('g.publish', 1)->get()->getRowArray();
                                if(!empty($url)) {
                                    $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . $url['link'];
                                }
                                break;
                        }
                    }
                    $items[$k]['submenu'] = $this->getMenuElements($id_menu, $id_lang, $lang_slug, $item['id'], $active_ids);
                }
            }
        }
        return $items;
    }
}