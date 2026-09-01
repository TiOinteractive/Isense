<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Link;

class PageModel extends Model {

    protected $table = 'page';
    protected $allowedFields = [
        're_id',
        'id_photo',
        'id_meta_photo',
        'template',
        'order',
        'publish',
        'no_index',
        'id_sidebar',
        'edited_at',
        'created_at'
    ];

    public function getPageById($id) {
        $page = $this->where('id', $id)->first();
        if (!empty($page)) {
            if (!empty($page['id_photo'])) {
                $page['photo'] = $this->db->table('tio_files')->where('id', $page['id_photo'])->limit(1)->get()->getRowArray();
            }
            if (!empty($page['id_meta_photo'])) {
                $page['meta_photo'] = $this->db->table('tio_files')->where('id', $page['id_meta_photo'])->limit(1)->get()->getRowArray();
            }
            $page['lang'] = $this->getPageLang($id, $page['re_id']);
            // Nazwa strony (nagłówek formularza) — z domyślnego/pierwszego języka; tio_page nie ma kolumny name.
            $page['name'] = ! empty($page['lang']) ? (reset($page['lang'])['name'] ?? '') : '';
            $page['meta']['lang'] = $this->getPageMetaLang($id);
            $page['content'] = $this->db->table('page_content pc')->join('module_element me', 'me.id=pc.id_module_element')->join('module m', 'm.id=me.id_module')->select('pc.*')->where('pc.id_page', $id)->where('m.publish', 1)->orderBy('pc.order', 'ASC')->get()->getResultArray();
        }
        return $page;
    }

    public function getPageLang($id, $re_id) {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('page_lang')->where('id_page', $id)->orderBy('id_lang')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $d['parent_url'] = $linkClass->getParentLink($re_id, $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function getPageMetaLang($id) {
        $langs = array();
        $data = $this->db->table('page_meta_lang')->where('id_page', $id)->orderBy('id_lang')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    private function getPageMenu($id_page) {
        $menu = array();
        $list = $this->db->table('page_menu')->select('id_menu')->where('id_page', $id_page)->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                $menu[] = $l['id_menu'];
            }
        }
        return $menu;
    }

    public function getPageForMenu($id, $id_lang, $languages) {
        $page = $this->db->table('page p')
                ->join('page_lang pl', 'p.id = pl.id_page')
                ->select('p.id as id_target,p.publish,pl.name')
                ->where('pl.id_lang', $id_lang)
                ->where('p.id', $id)
                ->get()
                ->getRowArray();
        if (!empty($page)) {
            $page['lang'] = array();
            $lang_data = $this->db->table('page_lang pl')->join('links l', 'l.id = pl.id_link')->select('pl.id_lang,pl.name,l.link as url')->where('pl.id_page', $id)->orderBy('pl.id_lang', 'ASC')->get()->getResultArray();
            if (!empty($lang_data)) {
                foreach ($lang_data as $l) {
                    $l['title'] = $l['name'];
                    $l['url'] = (!empty($languages) && !empty($languages[$l['id_lang']]) && $languages[$l['id_lang']]['slug'] ? '/' . $languages[$l['id_lang']]['slug'] : '') . '/' . $l['url'];
                    $page['lang'][$l['id_lang']] = $l;
                }
            }
        }
        return $page;
    }

    public function savePage($id, $post) {
        if (empty($post))
            return false;
        $data = array(
            're_id' => $post['re_id'],
            'id_photo' => !empty($post['photo']) && !empty($post['photo']['id']) ? $post['photo']['id'] : 0,
            'id_meta_photo' => !empty($post['meta_photo']) && !empty($post['meta_photo']['id']) ? $post['meta_photo']['id'] : 0,
            'template' => $post['template'],
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
            'no_index' => !empty($post['no_index']) ? $post['no_index'] : 0,
            'id_sidebar' => !empty($post['id_sidebar']) ? $post['id_sidebar'] : 0,
        );
        $this->db->transStart();
        if ($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
            HistoryStat($id, '', 'page', 'Page', lang('Admin.page.EditSuccess'));
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
            HistoryStat($id, '', 'page', 'Page', lang('Admin.page.AddSuccess'));
        }

        $this->savePageLang($this->id, $post['lang']);
        $this->savePageMetaLang($this->id, $post['meta']['lang']);
        $this->savePageContent($this->id, $post['content']);
        //$this->savePageMenu($this->id, !empty($post['menu']) ? $post['menu'] : array());
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    private function savePageLang($id_page, $lang_data) {
        if (!empty($lang_data)) {
            foreach ($lang_data as $id_lang => $lang) {
                $linkClass = new Link();
                $data = array(
                    'id_page' => $id_page,
                    'id_lang' => $id_lang,
                    'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], 0, $id_page == 1),
                    'name' => $lang['name'],
                    'header' => $lang['header'],
                );
                $lang = $this->db->table('page_lang')->select('id')->where('id_page', $id_page)->where('id_lang', $id_lang)->get()->getRowArray();
                if (!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('page_lang')->set($data)->where('id_page', $id_page)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('page_lang')->insert($data);
                }
            }
        }
    }

    private function savePageMetaLang($id_page, $lang_data) {
        if (!empty($lang_data)) {
            foreach ($lang_data as $id_lang => $lang) {
                $data = array(
                    'id_page' => $id_page,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                );
                $lang = $this->db->table('page_meta_lang')->select('id')->where('id_page', $id_page)->where('id_lang', $id_lang)->get()->getRowArray();
                if (!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('page_meta_lang')->set($data)->where('id_page', $id_page)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('page_meta_lang')->insert($data);
                }
            }
        }
    }

    private function savePageContent($id_page, $content) {
        $ids = array();
        if (!empty($content)) {
            foreach ($content as $cont) {
                // Pomijamy pusty wiersz startowy (strona bez bloków treści — np. strony renderowane z szablonu).
                if (empty($cont['id_element']) && empty($cont['id'])) {
                    continue;
                }
                $data = array(
                    'id_page' => $id_page,
                    'id_module_element' => $cont['id_element'],
                    'order' => $cont['order'],
                    'publish' => !empty($cont['publish']) ? $cont['publish'] : 0,
                );
                if ($cont['id'] && !empty($this->db->table('page_content')->select('id')->where('id', $cont['id'])->get()->getRowArray())) {
                    $result = $this->db->table('page_content')->set($data)->where('id', $cont['id'])->update();
                    $id_cont = $cont['id'];
                } else {
                    $result = $this->db->table('page_content')->insert($data);
                    $id_cont = $this->db->insertID();
                }
                $ids[] = $id_cont;
            }
        }
        $query = $this->db->table('page_content')->select('id')->where('id_page', $id_page);
        if (!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $content_list = $query->get()->getResultArray();
        if (!empty($content_list)) {
            foreach ($content_list as $c) {
                $this->db->table('page_content')->where('id', $c['id'])->delete();
            }
        }
    }

    private function savePageMenu($id_page, $menu) {
        $ids = array();
        if (!empty($menu)) {
            foreach ($menu as $m) {
                $data = array(
                    'id_page' => $id_page,
                    'id_menu' => $m,
                );
                $menu = $this->db->table('page_menu')->select('id')->where('id_page', $id_page)->where('id_menu', $m)->get()->getRowArray();
                if (!empty($menu)) {
                    $result = $this->db->table('page_menu')->set($data)->where('id_page', $id_page)->where('id_menu', $m)->update();
                    $id_pm = $menu['id'];
                } else {
                    $result = $this->db->table('page_menu')->insert($data);
                    $id_pm = $this->db->insertID();
                }
                $ids[] = $id_pm;
            }
        }
        $query = $this->db->table('page_menu')->select('id')->where('id_page', $id_page);
        if (!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $list = $query->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                $this->db->table('page_menu')->where('id', $l['id'])->delete();
            }
        }
    }

    public function getPagesStructure($id_lang, $re_id = 0, $exclude_ids = array(), $level = 0) {
        $db = $this->db->table('page p')
                ->join('page_lang pl', 'p.id = pl.id_page')
                ->select('p.id,p.re_id,p.publish,pl.name')
                ->where('pl.id_lang', $id_lang)
                ->where('p.re_id', $re_id);
        if (!empty($exclude_ids)) {
            $db->whereNotIn('p.id', $exclude_ids);
        }
        $pages = $db->orderBy('p.order', 'ASC')->get()->getResultArray();
        if (!empty($pages)) {
            foreach ($pages as $k => $page) {
                $pages[$k]['level'] = $level;
                $pages[$k]['list'] = $this->getPagesStructure($id_lang, $page['id'], $exclude_ids, $level + 1);
            }
        }
        return $pages;
    }

    public function getPagesList($id_lang) {
        $pages = $this->db->table('page p')
                ->join('page_lang pl', 'p.id = pl.id_page')
                ->select('p.id,p.publish,pl.name')
                ->where('pl.id_lang', $id_lang)
                ->where('p.publish', 1)
                ->orderBy('pl.name', 'ASC')
                ->get()
                ->getResultArray();
        return $pages;
    }

    public function deletePage($id, $re_id = 0) {
        $this->db->transStart();
        $this->db->table('page')->set('re_id', $re_id)->where('re_id', $id)->update();
        $langs = $this->db->table('page_lang')->select('id_link')->where('id_page', $id)->get()->getResultArray();
        if (!empty($langs)) {
            foreach ($langs as $l) {
			    $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $content = $this->db->table('page_content')->select('id')->where('id_page', $id)->get()->getResultArray();
        if (!empty($content)) {
            foreach ($content as $c) {
                $this->db->table('page_content_lang')->where('id_page_cont', $c['id'])->delete();
            }
        }
        $this->db->table('page_content')->where('id_page', $id)->delete();
        $this->db->table('page_meta_lang')->where('id_page', $id)->delete();
        $this->db->table('page_lang')->where('id_page', $id)->delete();
        $this->db->table('page')->where('id', $id)->delete();
        HistoryStat($id, '', 'page', 'Page', lang('Admin.page.Removed'));
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function getPageInfo($id, $id_lang) {
        $page = $this->join('page_lang pl', 'page.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('page.id,pl.name')->where('page.id', $id)->where('l.default', 1)->first();
        return $page;
    }
}
