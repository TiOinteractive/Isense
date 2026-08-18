<?php

namespace Modules\Catalog\Libraries;

use Modules\Catalog\Models\CatalogModel;

class Catalog {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->catalogModel = new CatalogModel();
    }

    public function index($content, $id_lang, $slug = '', $link = array()) {
        $config = new \Config\Pager;
        switch ($slug) {
            case 'selected_category':
                $lists = $this->catalogModel->db->table('page_content pc')
                        ->join('page_content_lang pcl', 'pc.id=pcl.id_page_cont', 'left')
                        ->join('page p', 'p.id=pc.id_page')
                        ->join('page_lang pl', 'pl.id_page=p.id')
                        ->join('links l', 'pl.id_link=l.id', 'left')
                        ->join('files f', 'f.id=p.id_meta_photo', 'left')
                        ->select('pl.id_page,pl.name,pc.id as id_content,p.re_id,pcl.name as content_name,pcl.title,pc.order,l.link,p.id_meta_photo,f.path')
                        ->groupStart()
                        ->where('pcl.id_lang', $id_lang)
                        ->orWhere('pcl.id', null)
                        ->groupEnd()
                        ->where('pl.id_lang', $id_lang)
                        ->groupStart()
                        ->where('l.id_lang', $id_lang)
                        ->orWhere('l.id', null)
                        ->groupEnd()
                        ->where('pc.id_module_element', 23)
                        ->whereIn('pc.id', $content['config']['lists'])
                        ->orderBy('pl.name', 'ASC')
                        ->get()
                        ->getResultArray();

                return array(
                    'views_dir' => 'categories',
                    'list' => $lists
                );
                break;
            case 'list':
            default:
                $query = $this->catalogModel->join('catalog_lang cl', 'catalog.id=cl.id_catalog')
                        ->join('links l', 'cl.id_link=l.id', 'left')
                        ->join('catalog_files cf', 'cf.id_catalog=catalog.id AND cf.field="photo"', 'left')
                        ->select('catalog.id,catalog.email,catalog.phone,catalog.website,catalog.cords,cl.name,cl.address,cl.open_hours,l.link,cf.path')
                        ->where('catalog.id_page_cont', $content['id'])
                        ->where('cl.id_lang', $id_lang)
                        ->groupStart()
                        ->where('l.id_lang', $id_lang)
                        ->orWhere('l.id', null)
                        ->groupEnd()
                        ->where('catalog.publish', 1);
                
                if (empty($content['config']['order'])) {
                    $content['config']['order'] = '';
                }
                
                $filters = array();
                if(!empty($link['get'])) {
                    for($i=0;$i<count($link['get']);$i+=2) {
                        $filters[$link['get'][$i]] = urldecode($link['get'][$i + 1]);
                    }
                }
                if(!empty($filters)) {
                    foreach($filters as $name=>$value) {
                        if(!empty($value)) {
                            switch($name) {
                                case 'o':
                                    switch($value) {
                                        case 'a-z': $content['config']['order'] = 'alphabetic_a_z';
                                            break;
                                        case 'z-a': $content['config']['order'] = 'alphabetic_z_a';
                                            break;
                                        case 'p': $content['config']['order'] = 'most_popular';
                                            break;
                                        case 'o': $content['config']['order'] = 'order';
                                            break;
                                        case 'l': $content['config']['order'] = 'latest';
                                            break;
                                    }
                                    break;
                                case 's': $query->groupStart()->like('cl.name', $value, 'both')->orLike('cl.address', $value, 'both')->groupEnd();
                                    break;
                            }
                        }
                    }
                }
                switch ($content['config']['order']) {
                    case 'alphabetic_a_z':
                        $query->orderBy('cl.name', 'ASC');
                        break;
                    case 'alphabetic_z_a':
                        $query->orderBy('cl.name', 'DESC');
                        break;
                    case 'most_popular':
                        $query->orderBy('cl.views', 'ASC');
                        break;
                    case 'latest':
                        $query->orderBy('catalog.created_at', 'DESC');
                        break;
                    case 'order':
                    default: $query->orderBy('catalog.order', 'ASC');
                        break;
                }
                $lists = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'catalog-' . $content['id']);
                return array(
                    'views_dir' => 'list',
                    'list' => $lists,
                    'pager' => !empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination'] ? $this->catalogModel->pager : null,
                    'search_engine' => !empty($content['config']) && !empty($content['config']['search_engine']),
                    'form_url' => !empty($link['link']) ? (!empty($this->locale) && $this->locale ? $this->locale . '/' : '') . $link['link'] : uri_string(),
                    'filters' => $filters,
                );
                break;
        }
    }

    public function getSingleByLinkId($id_link, $id_lang, $link) {
        $get = $this->request->getGet();
        $catalog = $this->catalogModel->db->table('catalog c')
                        ->join('catalog_lang cl', 'c.id=cl.id_catalog')
                        ->join('page_content pc', 'c.id_page_cont=pc.id')
                        ->select('pc.id_page,pc.id_sidebar,c.id,c.id_page_cont,c.website,c.email,c.phone,c.type,c.template,c.cords,cl.name,cl.content,cl.address,cl.open_hours,cl.tags')
                        ->where('c.publish', 1)
                        ->where('cl.id_lang', $id_lang)
                        ->where('cl.id_link', $id_link)
                        ->get()->getRowArray();
        if (!empty($catalog)) {
            $this->catalogModel->db->table('catalog_lang')->where('id_catalog', $catalog['id'])->where('id_lang', $id_lang)->set('views', 'views+1', false)->update();
            $catalog['photo'] = $this->catalogModel->db
                    ->table('catalog_files cf')
                    ->join('catalog_files_lang cfl', 'cf.id=cfl.id_file')
                    ->select('cf.path,cf.mime,cfl.caption,cfl.author')
                    ->where('cf.id_catalog', $catalog['id'])
                    ->where('cf.field', 'photo')
                    ->where('cfl.id_lang', $id_lang)
                    ->where('cf.publish', 1)
                    ->get()
                    ->getRowArray();
            $catalog['photos'] = $this->catalogModel->db
                    ->table('catalog_files cf')
                    ->join('catalog_files_lang cfl', 'cf.id=cfl.id_file')
                    ->select('cf.path,cf.mime,cfl.caption,cfl.author')
                    ->where('cf.id_catalog', $catalog['id'])
                    ->where('cf.field', 'photos')
                    ->where('cfl.id_lang', $id_lang)
                    ->where('cf.publish', 1)
                    ->orderBy('cf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $catalog['tags'] = explode(',', trim($catalog['tags'], ','));
            if (!empty($catalog['tags'])) {
                $catalog['tags'] = $this->catalogModel->db->table('tags t')->select('t.id,t.tag')->where('t.id_lang', $id_lang)->whereIn('t.id', $catalog['tags'])->get()->getResultArray();
            }
        }
        return $catalog;
    }

    public function assets($slug = '', $template = '', $id_catalog = 0, $data = array()) {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'js_ready' => array()
        );
        $assets['js'][] = '/assets/js/catalog.js';
        switch ($slug) {
            case 'single_element':
                $cords = !empty($data['cords']) ? explode(',', $data['cords']) : array();
                if (!empty($cords) && !empty($cords[0]) && !empty($cords[1])) {
                    $markers = array(
                        array(
                            'title' => addslashes($data['name']),
                            'lat' => !empty($cords[0]) ? $cords[0] : '',
                            'lng' => !empty($cords[1]) ? $cords[1] : '',
                            'text' => '<b>' . addslashes($data['name']) . '</b><br />' . str_replace("\r\n", "<br />", $data['address']),
                            'icon' => '/assets/gfx/marker_28_42.png',
                            'icon_width' => 28,
                            'icon_height' => 42,
                            'svg' => '',
                        )
                    );
                    $assets['js'][] = 'https://maps.google.com/maps/api/js?key=' . (!empty($this->settings) && !empty($this->settings['widget_gmjs']) ? $this->settings['widget_gmjs'] : '');
                    $assets['js'][] = '/assets/js/maps-googlemaps.js';
                    $assets['js_ready'][''] = "$('#map').GoogleMap('map', {lat: " . (!empty($cords[0]) ? $cords[0] : '0') . ", lng: " . (!empty($cords[1]) ? $cords[1] : '0') . ", zoom: 14, styles: '[]', markers: '" . json_encode($markers) . "'});";
                }
                $assets['js'][] = '/assets/js/jquery.touchSwipe.min.js';
                $assets['js'][] = '/assets/js/tiolightbox.js';
                $assets['css'][] = '/assets/css/tiolightbox.css';
                $assets['js_ready'][] = '$(".photos a").TiO_lightbox({});';
                break;
        }
        return $assets;
    }
    
    
    public function getMetaTags($id_catalog, $id_lang, $link, $data=array()) {
        helper('text');
        $meta_tags = array();
        $meta = $this->catalogModel->join('catalog_lang cl', 'catalog.id=cl.id_catalog')->join('catalog_meta_lang cml', 'catalog.id=cml.id_catalog', 'left')->select('cl.name,cl.content,cml.title as meta_title,cml.description as meta_desc,cml.keywords as meta_keys')->where('catalog.id', $id_catalog)->where('cl.id_lang', $id_lang)->where('cml.id_lang', $id_lang)->first();
        if (!empty($meta)) {
            $meta['image'] = $this->catalogModel->db->table('catalog_files cf')->join('catalog_files_lang cfl', 'cf.id=cfl.id_file', 'left')->select('cf.path,cf.ext,cfl.caption')->where('cf.id_catalog', $id_catalog)->where('cf.field', 'photo')->where('cfl.id_lang', $id_lang)->get()->getRowArray();
            if (!empty($meta['meta_title'])) {
                $meta_tags['title'] = $meta['meta_title'];
                $meta_tags['image_alt'] = $meta['meta_title'];
            } elseif (!empty($meta['name'])) {
                $meta_tags['title'] = $meta['name'];
                $meta_tags['image_alt'] = $meta['name'];
            }
            if (!empty($meta['content'])) {
                $meta_tags['description'] = character_limiter($meta['content'], 160);
            }
            if (!empty($meta['meta_keys'])) {
                $meta_tags['keywords'] = $meta['meta_keys'];
            }
            if (!empty($meta['image']) && !empty($meta['image']['path']) && file_exists(WRITEPATH . 'uploads/' . $meta['image']['path'])) {
                $size = getimagesize(WRITEPATH . 'uploads/' . $meta['image']['path']);
                $meta_tags['image'] = base_url() . 'image/' . $meta['image']['path'];
                $meta_tags['image_width'] = $size[0];
                $meta_tags['image_height'] = $size[1];
                $meta_tags['image_type'] = $meta['image']['ext'];
                if (!empty($meta['image']['caption'])) {
                    $meta_tags['image_alt'] = $meta['image']['caption'];
                }
            }
        }
        return $meta_tags;
    }
}
