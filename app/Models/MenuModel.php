<?php

namespace App\Models;  
use CodeIgniter\Model;

class MenuModel extends Model {

    protected $table = 'menu';
    
    protected $allowedFields = [
        'publish',
        'edited_at',
        'created_at'
    ];
    
    public function getFullMenuList($id_lang, $get=array()) 
    {
        $menu_list = $this->db->table('menu m')
            ->join('menu_lang ml', 'm.id = ml.id_menu')
            ->select('m.id,m.publish,ml.name')
            ->where('ml.id_lang', $id_lang)
            ->orderBy('ml.name', 'ASC')
            ->get()
            ->getResultArray();
        return $menu_list;
    }
    
    public function getMenuList($id_lang, $get=array()) 
    {
        $this->join('menu_lang ml', 'menu.id = ml.id_menu')->select('menu.id,menu.publish,ml.name')->where('ml.id_lang', $id_lang);
        if(!empty($get)) {
            foreach($get as $name=>$value) {
                switch($name) {
                    case 'name': 
                        if(!empty($value)) {
                            $this->like('name', $value);
                        }
                        break;
                }
            }
        }
        if(empty($get['order'])) {
            $get['order'] = 'ml.name';
        }
        switch($get['order']) {
            case 'name;desc': $this->orderBy('ml.name', 'DESC');
                break;
            case 'name;asc': 
            default: $this->orderBy('ml.name', 'ASC');
                break;
        }
        $menu_list = $this->paginate(20);
        return $menu_list;
    }
    
    public function getMenuById($id, $id_lang) 
    {
        $menu = $this->where('id', $id)->first();
        if(!empty($menu)) {
            $menu['lang'] = $this->getMenuLang($id);
            if(!empty($menu['lang']) && !empty($menu['lang'][$id_lang]) && !empty($menu['lang'][$id_lang]['name'])) {
                $menu['name'] = $menu['lang'][$id_lang]['name'];
            } else {
                $menu['name'] = '';
            }
            $menu['element'] = $this->getMenuElements($id, $id_lang);
        }
        return $menu;
    }
    
    private function getMenuLang($id_menu) 
    {
        $langs = array();
        $data = $this->db->table('menu_lang')->where('id_menu', $id_menu)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    private function getMenuElements($id_menu, $id_lang, $id_parent=0, $level=0) 
    {
        $elements = $this->db->table('menu_item mi')
                ->join('menu_item_lang mil', 'mi.id = mil.id_menu_item')
                ->select('mi.*,mil.name')
                ->where('mil.id_lang', $id_lang)
                ->orderBy('mi.order', 'ASC')
                ->where('id_parent', $id_parent)
                ->where('id_menu', $id_menu)
                ->get()
                ->getResultArray();
        if(!empty($elements)) {
            foreach($elements as $k=>$element) {
                $elements[$k]['level'] = $level;
                $elements[$k]['lang'] = $this->getMenuElementLang($element['id'], $element['type'], $element['id_target']);
                $elements[$k]['list'] = $this->getMenuElements($id_menu, $id_lang, $element['id'], $level + 1);
                if (!empty($element['id_photo'])) {
                    $elements[$k]['photo'] = $this->db->table('tio_files')->where('id', $element['id_photo'])->limit(1)->get()->getRowArray();
                }
            }
        }
        return $elements;
    }
    
    private function getMenuElementLang($id_item, $type, $id_target) 
    {
        $langs = array();
        $data = $this->db->table('menu_item_lang')->where('id_menu_item', $id_item)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                if($type != 'own' && $id_target) {
                    switch($type) {
                        case 'event_type':
                            $settings = $this->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_calendar')->where('sl.id_lang', $d['id_lang'])->get()->getRowArray();
                            $lang_data = $this->db->table('event_type_lang etl')->select('etl.id_lang,etl.name,etl.slug as url')->where('etl.id_type', $id_target)->where('etl.id_lang', $d['id_lang'])->get()->getRowArray();
                            if(!empty($lang_data)) {
                                $d['url'] = (!empty($this->languages) && !empty($this->languages[$d['id_lang']]) && $this->languages[$d['id_lang']]['slug'] ? '/' . $this->languages[$d['id_lang']]['slug'] : '') . '/' . (!empty($settings) ? $settings['value'] : '') . '/g/t/' . $lang_data['url'];
                            }
                            break;
                        case 'page': 
                            $url = $this->db->table('links l')->join('page_lang pl', 'pl.id_link=l.id')->join('page p', 'p.id=pl.id_page')->select('l.link')->where('p.id', $id_target)->where('pl.id_lang', $d['id_lang'])->get()->getRowArray();
                            if(!empty($url)) {
                                $d['url'] = (!empty($this->languages) && !empty($this->languages[$d['id_lang']]) && $this->languages[$d['id_lang']]['slug'] ? '/' . $this->languages[$d['id_lang']]['slug'] : '') . '/' . $url['link'];
                            }
                            break;
                        case 'news': 
                            $url = $this->db->table('links l')->join('news_lang nl', 'nl.id_link=l.id')->join('news n', 'n.id=nl.id_news')->select('l.link')->where('n.id', $id_target)->where('nl.id_lang', $d['id_lang'])->get()->getRowArray();
                            if(!empty($url)) {
                                $d['url'] = (!empty($this->languages) && !empty($this->languages[$d['id_lang']]) && $this->languages[$d['id_lang']]['slug'] ? '/' . $this->languages[$d['id_lang']]['slug'] : '') . '/' . $url['link'];
                            }
                            break;
                        case 'gallery': 
                            $url = $this->db->table('links l')->join('gallery_lang gl', 'gl.id_link=l.id')->join('gallery g', 'g.id=gl.id_gallery')->select('l.link')->where('g.id', $id_target)->where('gl.id_lang', $d['id_lang'])->get()->getRowArray();
                            if(!empty($url)) {
                                $d['url'] = (!empty($this->languages) && !empty($this->languages[$d['id_lang']]) && $this->languages[$d['id_lang']]['slug'] ? '/' . $this->languages[$d['id_lang']]['slug'] : '') . '/' . $url['link'];
                            }
                            break;
						 case 'flavors': 
                            $url = $this->db->table('links l')->join('flavors_categories_lang gl', 'gl.id_link=l.id')->join('flavors_categories g', 'g.id=gl.id_category')->select('l.link')->where('g.id', $id_target)->where('gl.id_lang', $d['id_lang'])->get()->getRowArray();
                            if(!empty($url)) {
                                $d['url'] = (!empty($this->languages) && !empty($this->languages[$d['id_lang']]) && $this->languages[$d['id_lang']]['slug'] ? '/' . $this->languages[$d['id_lang']]['slug'] : '') . '/' . $url['link'];
                            }
                            break;	
                    }
                }
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function saveMenu($id, $post) 
    {
        if(empty($post)) return false;
        $data = array(
            'publish' => !empty($post['publish']) ? $post['publish'] : 0
        );
        $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        
        $this->saveMenuLang($this->id, $post['lang']);
        if(!empty($post['element'])) {$this->saveMenuElements($this->id, $post['element']);}
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveMenuLang($id_menu, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_menu' => $id_menu,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                );
                $lang = $this->db->table('menu_lang')->select('id')->where('id_menu', $id_menu)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('menu_lang')->set($data)->where('id_menu', $id_menu)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('menu_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveMenuElements($id_menu, $elements) 
    {
        $ids = array();
        if(!empty($elements)) {
            foreach($elements as $element) {
                $data = array(
                    'id_menu' => $id_menu,
                    'id_parent' => !empty($element['id_parent']) ? $element['id_parent'] : 0,
                    'id_target' => !empty($element['id_target']) ? $element['id_target'] : 0,
                    'id_photo' => !empty($element['photo']) && !empty($element['photo']['id']) ? $element['photo']['id'] : 0,
                    'target' => !empty($element['target']) ? $element['target'] : '',
                    'order' => !empty($element['order']) ? $element['order'] : 0,
                    'type' => !empty($element['type']) ? $element['type'] : '',
                    'class' => !empty($element['class']) ? $element['class'] : '',
                    'svg' => !empty($element['svg']) ? $element['svg'] : '',
                    'id_parent_active' => !empty($element['id_parent_active']) ? $element['id_parent_active'] : null,
                );
                if(!empty($element['id']) && !empty($this->db->table('menu_item')->select('id')->where('id', $element['id'])->get()->getRowArray())) {
                    $result = $this->db->table('menu_item')->set($data)->where('id', $element['id'])->update();
                    $id_element = $element['id'];
                } else {
                    $result = $this->db->table('menu_item')->insert($data);
                    $id_element = $this->db->insertID();
                }
                $ids[] = $id_element;
                $this->saveMenuElementLang($id_element, $element['lang'], $element['type']);
            }
        }
        $query = $this->db->table('menu_item')->select('id')->where('id_menu', $id_menu);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $elements_list = $query->get()->getResultArray();
        if(!empty($elements_list)) {
            foreach($elements_list as $el) {
                $this->db->table('menu_item_lang')->where('id_menu_item', $el['id'])->delete();
                $this->db->table('menu_item')->where('id', $el['id'])->delete();
            }
        }
    }
    
    private function saveMenuElementLang($id_item, $lang_data, $type) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_menu_item' => $id_item,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                    'url' => $type != 'own' ? '' : $lang['url'],
                    'title' => $lang['title'],
                );
                $element = $this->db->table('menu_item_lang')->select('id')->where('id_menu_item', $id_item)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($element) && !empty($element['id'])) {
                    $result = $this->db->table('menu_item_lang')->set($data)->where('id_menu_item', $id_item)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('menu_item_lang')->insert($data);
                }
            }
        }
    }
    
    public function addMenuElement($id_menu, $item) {
        $data = array(
            'id_menu' => $id_menu,
            'id_parent' => 0,
            'id_target' => !empty($item['id_target']) ? $item['id_target'] : 0,
            'target' => '',
            'order' => !empty($item['order']) ? $item['order'] : 0,
            'type' => !empty($item['type']) ? $item['type'] : '',
        );
        $result = $this->db->table('menu_item')->insert($data);
        if($result) {
            $id_element = $this->db->insertID();
            $this->saveMenuElementLang($id_element, $item['lang'], !empty($item['type']) ? $item['type'] : '');
            return $id_element;
        }
        return null;
    }
    
    public function deleteMenu($id) 
    {
        $this->db->transStart();
        $this->db->table('menu_item_lang mil')->join('menu_item mi', 'mi.id=mil.id_menu_item')->where('mi.id_menu', $id)->delete();
        $this->db->table('menu_item')->where('id_menu', $id)->delete();
        $this->db->table('menu_lang')->where('id_menu', $id)->delete();
        $this->db->table('menu')->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}