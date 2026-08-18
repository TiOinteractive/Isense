<?php

namespace App\Libraries;

use App\Models\LinkModel;

class Link
{
    public $redirect_conflict;
    public $url_conflict;

    public function __construct() {
        $this->linkModel = new LinkModel();
        $uri = current_url(true);
    }
    
    public function saveLink($link, $id_lang, $id=0, $id_module=0, $allow_empty=false, $action=null, $module_slug=null, $sync=1, $redirect=0) 
    {
        if(!$allow_empty && empty($link)) return 0;
        $link = $this->checkLink($link, $id_lang, $id);
        $data = array(
            'id_module' => $id_module,
            'module_slug' => $module_slug,
            'id_lang' => $id_lang,
            'link' => $link,
            'action' => $action,
            'sync' => $sync,
            'redirect' => $redirect,
        );
        if(!empty($this->linkModel->where('id', $id)->first())) {
            $this->linkModel->set($data)->where('id', $id)->update();
        } else {
            $this->linkModel->insert($data);
            $id = $this->linkModel->insertID();
        }
        return $id;
    }
    
    public function generateLink($name, $id_lang, $id=0, $id_page=0, $module='page', $direct_link='') 
    {
        $name = str_replace(array('/', ','), '-', $name);
        $link = mb_url_title($name, '-', true);
        if($direct_link && !$id_page) {
            $tmp = explode('/', $direct_link);
            $direct_link = '';
            if(!empty($tmp)) {
                foreach($tmp as $k=>$t) {
                    if($k) $direct_link .= '/';
                    $direct_link .= mb_url_title($t, '-', true);
                }
            }
            $link = $direct_link . '/' . $link;
        }
        if($id_page) {
            switch($module) {
                case 'place_type': $page_link = $this->getLinkByEventPlaceTypeId($id_page, $id_lang);
                    break; 
                //case 'event_place': $page_link = $this->getLinkByEventPlaceTypeId($id_page, $id_lang);
                //    break; 
                //case 'event': $page_link = $this->getLinkByEventTypeId($id_page, $id_lang);
                //    break; 
                case 'page':
                default:
                    $page_link = $this->getLinkByPageId($id_page, $id_lang);
                    break;
            }
            if(!empty($page_link)) {
                $link = $page_link . '/' . $link;
            }
        }
        $link = $this->checkLink($link, $id_lang, $id);
        return $link;
    }
    
    public function checkLink($link, $id_lang, $id=0) 
    {
        if(is_dir(ROOTPATH . 'modules/Redirects') && class_exists('\Modules\Redirects\Libraries\Redirects')) {
            $redirectModel = new \Modules\Redirects\Models\RedirectsModel();
        }
        $this->url_conflict = '';
        $this->redirect_conflict = '';
        $oryginal_link = $link;
        $count = 1;
        do {
            $this->linkModel->where('link', $link)->where('id_lang', $id_lang)->where('redirect', 0);
            if(!empty($id)) {
                $this->linkModel->where('id !=', $id);
            }
            $is = $this->linkModel->first('id');
            if(!empty($is)) {
                $this->url_conflict = $link;
                $link = $oryginal_link . '-' . $count;
                $this->redirect_conflict = '';
            } elseif(is_dir(ROOTPATH . 'modules/Redirects') && class_exists('\Modules\Redirects\Libraries\Redirects')) {
                $is = $redirectModel->checkLinkFrom('/' . $link);
                if(!empty($is)) {
                    $this->url_conflict = '';
                    $this->redirect_conflict = $link;
                    $link = $oryginal_link . '-' . $count;
                }
            }
            ++$count;
        } while(!empty($is) && $count<=1000);
        return $link;
    }
    
    public function getLink($id_link, $id_lang, $array=false) 
    {
        if(empty($id_link)) return '';
        $link = $this->linkModel->select('link,blocked,redirect,sync')->where('id', $id_link)->where('id_lang', $id_lang)->first();
        if(!empty($link)) {
            if($array) {
                return $link;
            } else {
                return $link['link'];
            }
        }
        return null;
    }
    
    public function getLinkByPageId($id_page, $id_lang) 
    {
        if(empty($id_page)) return '';
        $link = $this->linkModel->db->table('links l')->join('page_lang pl', 'pl.id_link=l.id')->select('l.link')->where('pl.id_page', $id_page)->where('pl.id_lang', $id_lang)->get()->getRowArray();
        
        if(empty($link)) return '';
        return $link['link'];
    }
    
    public function getLinkByEventTypeId($id_type, $id_lang) 
    {
        if(empty($id_type)) return '';
        $link = $this->linkModel->db->table('links l')->join('event_type_lang etl', 'etl.id_link=l.id')->select('l.link')->where('etl.id_type', $id_type)->where('etl.id_lang', $id_lang)->get()->getRowArray();
        if(empty($link)) return '';
        return $link['link'];
    }
    
    public function getLinkByEventPlaceTypeId($id_type, $id_lang) 
    {
        if(empty($id_type)) return '';
        $link = $this->linkModel->db->table('links l')->join('event_place_type_lang eptl', 'eptl.id_link=l.id')->select('l.link')->where('eptl.id_type', $id_type)->where('eptl.id_lang', $id_lang)->get()->getRowArray();
        if(empty($link)) return '';
        return $link['link'];
    }
    
    public function getParentLink($id_page, $id_lang) 
    {
        $l = $this->linkModel->db->table('links l')
            ->join('page_lang pl', 'l.id=pl.id_link')
            ->join('page p', 'p.id=pl.id_page')
            ->select('l.link')
            ->where('p.id', $id_page)
            ->where('l.id_lang', $id_lang)
            ->get()
            ->getRowArray();
        if(empty($l)) return '';
        return $l['link'];
    }
    
    public function getLinkByUrl($url, $language) 
    {
        if(!empty($language['slug'])) {
            $url = substr($url, strlen($language['slug']) + 1);
        }
        if(str_contains($url, '/g/')) {
            $get = explode('/', substr($url, strpos($url, '/g/') + 3));
            $url = substr($url, 0, strpos($url, '/g/'));
        }
        $link = $this->linkModel->db->table('links')->select('id,id_module,module_slug,link,id_lang,action,slug')->where('link', $url)->where('id_lang', $language['id'])->where('redirect', 0)->get()->getRowArray();
        if(!empty($link)) {
            if(!empty($get)) {
                $link['get'] = $get;
            }
        }/* elseif(empty($link)) {
            $link = trim($link, '/');
            $tmp = explode('/', $url);
            if(!empty($tmp) && count($tmp) >= 3) {
                $action = $tmp[count($tmp) - 2];
                $hash = $tmp[count($tmp) - 1];
                unset($tmp[count($tmp) - 1]);
                unset($tmp[count($tmp) - 1]);
                $url = implode('/', $tmp);
                $link = $this->linkModel->db->table('links')->select('id,id_module,link,id_lang,action,slug')->where('link', $url)->where('id_lang', $language['id'])->get()->getRowArray();
                if(!empty($link)) {
                    $link['link_action'] = $action;
                    $link['hash'] = $hash;
                }
            }
        }*/
        return $link;
    }
    
    public function getModuleLinks() {
        $links = array();
        $list = $this->linkModel->db->table('links l')->where('slug !=', null)->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                if(empty($links[$l['id_lang']])) {
                    $links[$l['id_lang']] = array();
                }
                $links[$l['id_lang']][$l['slug']] = $l['link'];
            }
        }
        return $links;
    }
    
    public function saveModuleLinks($links) {
        if(!empty($links)) {
            $this->linkModel->db->transStart();
            foreach($links as $module_slug=>$module_links) {
                $module = $this->linkModel->db->table('module')->select('id')->where('slug', ucfirst($module_slug))->get()->getRowArray();
                if(!empty($module_links) && !empty($module) && !empty($module['id'])) {
                    foreach($module_links as $id_lang=>$lang) {
                        if(!empty($lang)) {
                            foreach($lang as $slug=>$link) {
                                if(empty($link)) $link = $slug;
                                $is = $this->linkModel->db->table('links l')->select('id')->where('id_module', $module['id'])->where('slug', $slug)->where('id_lang', $id_lang)->get()->getRowArray();
                                $link = $this->checkLink($link, $id_lang, !empty($is) ? $is['id'] : 0);
                                $data = array(
                                    'id_module' => $module['id'],
                                    'id_lang' => $id_lang,
                                    'link' => $link,
                                    'slug' => $slug,
                                );
                                if(!empty($is)) {
                                    $this->linkModel->set($data)->where('id_module', $module['id'])->where('slug', $slug)->where('id_lang', $id_lang)->update();
                                } else {
                                    $this->linkModel->insert($data);
                                    $id = $this->linkModel->insertID();
                                }
                            }
                        }
                    }
                }
            }
            $this->linkModel->db->transComplete();
            return $this->linkModel->db->transStatus();
        }
    }
    
    public function getGlobalLinks($id_lang, $locale) {
        $links = array();
        $list = $this->linkModel->db->table('links')->select('id_module,link,slug')->where('id_lang', $id_lang)->where('slug!=', NULL)->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $links[$l['slug']] = ($locale ? $locale . '/' : '') . $l['link'];
            }
        }
        return $links;
    }
}