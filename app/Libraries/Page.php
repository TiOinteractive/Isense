<?php

namespace App\Libraries;

use App\Models\PageModel;
use App\Models\PageContentModel;
use App\Models\LanguageModel;
use App\Models\SettingsModel;

class Page {

    public $id_page = 0;
    public $page = array(
        'home' => false
    );

    public function __construct() {
        $this->pageModel = new PageModel();
        $this->pageContentModel = new PageContentModel();
        $this->languageModel = new LanguageModel();
    }

    public function getPageByLinkId($id_link, $id_lang) {
        $page = $this->pageModel->db->table('page p')
                        ->join('page_lang pl', 'p.id=pl.id_page')
                        ->join('links l', 'l.id=pl.id_link')
                        ->join('files f', 'f.id=p.id_photo', 'left')
                        ->select('p.re_id,pl.id_page,p.template,p.id_sidebar,pl.name,pl.header,l.link,f.path as photo')
                        ->where('pl.id_lang', $id_lang)
                        ->where('pl.id_link', $id_link)
                        ->where('p.publish', 1)
                        ->get()->getRowArray();
        if (!empty($page)) {
            $page['home'] = $page['link'] == '';
            $this->id_page = $page['id_page'];
            $page['id_parents'] = array();
            if ($page['re_id']) {
                $page['id_parents'] = $this->getPageParentsIds($page['re_id']);
            }
            $this->page = $page;
            $page['content'] = $this->getPageContentById($page['id_page'], $id_lang);
        }
        return $page;
    }

    public function getPageById($id_page, $id_lang) {
        $page = $this->pageModel->db->table('page p')
                        ->join('page_lang pl', 'p.id=pl.id_page')
                        ->join('links l', 'l.id=pl.id_link')
                        ->join('files f', 'f.id=p.id_photo', 'left')
                        ->select('p.re_id,pl.id_page,p.template,pl.name,pl.header,l.link,f.path as photo')
                        ->where('pl.id_lang', $id_lang)
                        ->where('p.id', $id_page)
                        ->where('p.publish', 1)
                        ->get()->getRowArray();
        if (!empty($page)) {
            $page['home'] = $page['link'] == '';
            $this->id_page = $page['id_page'];
            $page['id_parents'] = array();
            if ($page['re_id']) {
                $page['id_parents'] = $this->getPageParentsIds($page['re_id']);
            }
            $this->page = $page;
        }
        return $page;
    }

    private function getPageContentById($id_page, $id_lang) {
        if (empty($id_page))
            return array();
        $content = array();
        $data = $this->pageContentModel
                ->join('page_content_lang pcl', 'page_content.id=pcl.id_page_cont', 'left')
                ->select('page_content.id,page_content.id_element,page_content.id_sidebar,page_content.template,page_content.id_module_element,page_content.config,page_content.settings,pcl.title,pcl.subtitle,pcl.url')
                ->where('page_content.id_page', $id_page)
                ->where('page_content.publish', 1)
                ->where('pcl.id_lang', $id_lang)
                ->orderBy('page_content.order', 'ASC')
                ->findAll();
        if (!empty($data)) {
            foreach ($data as $d) {
                $d['config'] = unserialize($d['config']);
                $d['settings'] = unserialize($d['settings']);
                $content['cont_' . $d['id']] = $d;
            }
        }
        return $content;
    }

    public function getPageMeta($id_page, $id_lang) {
        $metatags = array();
        $meta = $this->pageModel->db->table('page p')->join('page_lang pl', 'pl.id_page=p.id')->join('page_meta_lang pml', 'p.id=pml.id_page', 'left')->select('p.id_meta_photo,pl.name,pl.header,pml.title,pml.description,pml.keywords,p.no_index')->where('p.id', $id_page)->where('pl.id_lang', $id_lang)->where('pml.id_lang', $id_lang)->get()->getRowArray();
        if (!empty($meta)) {
            if (!empty($meta['title'])) {
                $metatags['title'] = $meta['title'];
                $metatags['image_alt'] = $meta['title'];
            } elseif (!empty($meta['name'])) {
                $metatags['title'] = $meta['name'];
                $metatags['image_alt'] = $meta['name'];
            } elseif (!empty($meta['header'])) {
                $metatags['title'] = $meta['header'];
                $metatags['image_alt'] = $meta['header'];
            }
            if (!empty($meta['no_index'])) {
                $metatags['no_index'] = $meta['no_index'];
            }
            if (!empty($meta['description'])) {
                $metatags['description'] = $meta['description'];
            }
            if (!empty($meta['keywords'])) {
                $metatags['keywords'] = $meta['keywords'];
            }
            if (!empty($meta['id_meta_photo'])) {
                $meta['meta_photo'] = $this->pageModel->db->table('files')->select('path,ext')->where('id', $meta['id_meta_photo'])->get()->getRowArray();
                if (!empty($meta['meta_photo']) && !empty($meta['meta_photo']['path']) && file_exists(WRITEPATH . 'uploads/' . $meta['meta_photo']['path'])) {
                    $size = getimagesize(WRITEPATH . 'uploads/' . $meta['meta_photo']['path']);
                    $metatags['image'] = base_url() . 'image/' . $meta['meta_photo']['path'];
                    $metatags['image_width'] = $size[0];
                    $metatags['image_height'] = $size[1];
                    $metatags['image_type'] = $meta['meta_photo']['ext'];
                }
            }
            
        }
        return $metatags;
    }

    public function getLanguageLinks($id_page) {
        $links = array();
        $list = $this->pageModel->db->table('page_lang pl')->join('links l', 'l.id=pl.id_link')->select('pl.id_lang,l.link')->where('id_page', $id_page)->orderBy('id_lang', 'ASC')->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                $links[$l['id_lang']] = $l['link'];
            }
        }
        return $links;
    }

    private function getMenuElements($id_menu, $id_lang, $lang_slug, $id_parent = 0, $active_ids = array(), $submenu_levels = 0) {
        $items = $this->pageModel->db->table('menu_item mi')->join('menu_item_lang mil', 'mi.id=mil.id_menu_item')->select('mi.id,mi.target,mi.id_target,mi.type,mi.class,mil.name,mil.url,mil.title,mi.id_photo,mi.id_parent_active,mi.svg')->where('mil.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.id_parent', $id_parent)->orderBy('mi.order', 'ASC')->get()->getResultArray();
        if (!empty($items)) {
            foreach ($items as $k => $item) {
                if (!empty($item['id_photo'])) {
                    $items[$k]['photo'] = $this->pageModel->db->table('files')->select('path,name')->where('id', $item['id_photo'])->get()->getRowArray();
                }
                if ($item['type'] != 'own' && $item['id_target']) {
                    if ($item['id'] && !empty($active_ids['menu']) && in_array($item['id'], $active_ids['menu'])) {
                        $items[$k]['active'] = true;
                    } else {
                        $items[$k]['active'] = false;
                    }
                    if ($item['type'] == 'page' && $item['id_target'] && !empty($active_ids['page']) && in_array($item['id_target'], $active_ids['page'])) {
                        $items[$k]['active'] = true;
                    }
                    switch ($item['type']) {
                        case 'event_type':
                            $settings = $this->pageModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_calendar')->where('sl.id_lang', $id_lang)->get()->getRowArray();
                            $lang_data = $this->pageModel->db->table('event_type et')->join('event_type_lang etl', 'et.id=etl.id_type')->select('etl.id_lang,etl.name,etl.slug as url')->where('et.id', $item['id_target'])->where('et.publish', 1)->where('etl.id_lang', $id_lang)->get()->getRowArray();
                            if (!empty($lang_data)) {
                                $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . (!empty($settings) ? $settings['value'] : '') . '/g/t/' . $lang_data['url'];
                            } else {
                                unset($items[$k]);
                            }
                            break;
                        case 'page':
                            $url = $this->pageModel->db->table('links l')->join('page_lang pl', 'pl.id_link=l.id')->join('page p', 'p.id=pl.id_page')->select('l.link')->where('p.id', $item['id_target'])->where('pl.id_lang', $id_lang)->where('p.publish', 1)->get()->getRowArray();
                            if (!empty($url)) {
                                $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . $url['link'];
                            } else {
                                unset($items[$k]);
                            }
                            break;
                        case 'news':
                            $url = $this->pageModel->db->table('links l')->join('news_lang nl', 'nl.id_link=l.id')->join('news n', 'n.id=nl.id_news')->select('l.link')->where('n.id', $item['id_target'])->where('n.publish', 1)->where('nl.id_lang', $id_lang)->where('n.publish', 1)->get()->getRowArray();
                            if (!empty($url)) {
                                $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . $url['link'];
                            } else {
                                unset($items[$k]);
                            }
                            break;
                        case 'gallery':
                            $url = $this->pageModel->db->table('links l')->join('gallery_lang gl', 'gl.id_link=l.id')->join('gallery g', 'g.id=gl.id_gallery')->select('l.link')->where('g.id', $item['id_target'])->where('g.publish', 1)->where('gl.id_lang', $id_lang)->where('g.publish', 1)->get()->getRowArray();
                            if (!empty($url)) {
                                $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . $url['link'];
                            } else {
                                unset($items[$k]);
                            }
                            break;
                        case 'flavors':
                            $url = $this->pageModel->db->table('links l')->join('flavors_categories_lang gl', 'gl.id_link=l.id')->join('flavors_categories g', 'g.id=gl.id_category')->select('l.link')->where('g.id', $item['id_target'])->where('g.publish', 1)->where('gl.id_lang', $id_lang)->where('g.publish', 1)->get()->getRowArray();
                            if (!empty($url)) {
                                $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . $url['link'];
                            } else {
                                unset($items[$k]);
                            }
                            break;
                        case 'place_type':
                            $url = $this->pageModel->db->table('links l')->join('event_place_type_lang eptl', 'eptl.id_link=l.id')->join('event_place_type ept', 'ept.id=eptl.id_type')->select('l.link')->where('ept.id', $item['id_target'])->where('ept.publish', 1)->where('eptl.id_lang', $id_lang)->where('ept.publish', 1)->get()->getRowArray();
                            if (!empty($url)) {
                                $items[$k]['url'] = (!empty($lang_slug) ? '/' . $lang_slug : '') . '/' . $url['link'];
                            } else {
                                unset($items[$k]);
                            }
                            break;
                    }
                }
                if(!empty($items[$k])) {
                    $items[$k]['submenu'] = $this->getMenuElements($id_menu, $id_lang, $lang_slug, $item['id'], $active_ids, $submenu_levels - 1);
                }
            }
        }
        return $items;
    }

    public function getLanguages() {
        $languages = array();
        $list = $this->languageModel->db->table('language')->select('id,name,short_name,slug,lang_code,default,primary,iso_code')->where('publish', 1)->orderBy('default', 'DESC')->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $lang) {
                $languages[$lang['id']] = $lang;
            }
        }
        return $languages;
    }

    public function checkLanguage() {
        $slug = '';
        $url_arr = explode('/', uri_string(true));
        if (!empty($url_arr) && strlen($url_arr[0]) >= 2 && strlen($url_arr[0]) <= 3) {
            $slug = $url_arr[0];
        }
        $lang = $this->languageModel->db->table('language')->select('id,name,short_name,lang_code,slug,iso_code')->where('slug', $slug)->get()->getRowArray();
        return !empty($lang) ? $lang : null;
    }

    public function getPrimaryLang() {
        $lang = $this->languageModel->db->table('language')->select('id,name,short_name,lang_code,slug,iso_code')->where('primary', 1)->get()->getRowArray();
        return $lang;
    }

    public function getSettings($id_lang) {
        $settingsModel = new SettingsModel();
        return $settingsModel->getSettings($id_lang);
    }

    public function getDefaultMetatags($settings, $language, $link=array()) {
        $metatags = array(
            'title' => !empty($settings['meta_title']) ? $settings['meta_title'] : (!empty($settings['company_name']) ? $settings['company_name'] : ''),
            'description' => !empty($settings['meta_description']) ? $settings['meta_description'] : '',
            'keywords' => !empty($settings['meta_keywords']) ? $settings['meta_keywords'] : '',
            'canonical' => base_url() . (uri_string() != '/' ? uri_string() : ''),
            'image_alt' => !empty($settings['meta_title']) ? $settings['meta_title'] : (!empty($settings['company_name']) ? $settings['company_name'] : ''),
            'url' => base_url() . (uri_string() != '/' ? uri_string() : ''),
            'site_name' => !empty($settings['company_name']) ? $settings['company_name'] : '',
            'favicon' => !empty($settings['favicon']) ? base_url() . 'image/' . $settings['favicon']['path'] : '',
            'apple_icon' => !empty($settings['favicon']) ? base_url() . 'image/' . $settings['favicon']['path'] : '',
            'index' => !empty($settings['index_website']),
            'lang' => !empty($language['lang_code']) ? $language['lang_code'] : '',
            'alternative' => array()
        );
        if (!empty($settings['meta_photo']) && !empty($settings['meta_photo']['path']) && file_exists(WRITEPATH . 'uploads/' . $settings['meta_photo']['path'])) {
            $size = getimagesize(WRITEPATH . 'uploads/' . $settings['meta_photo']['path']);
            $metatags['image'] = base_url() . 'image/' . $settings['meta_photo']['path'];
            $metatags['image_width'] = $size[0];
            $metatags['image_height'] = $size[1];
            $metatags['image_type'] = $settings['meta_photo']['ext'];
        }
        if(!empty($link['link'])) {
            $metatags['canonical'] = base_url() . (!empty($language['slug']) ? $language['slug'] . '/' : '') . $link['link'];
        }
        $social_links = [];
        if(!empty($settings['facebook'])) {
            $social_links[] = $settings['facebook'];
        }
        if(!empty($settings['youtube'])) {
            $social_links[] = $settings['youtube'];
        }
        if(!empty($settings['instagram'])) {
            $social_links[] = $settings['instagram'];
        }
        if(!empty($settings['twitter'])) {
            $social_links[] = $settings['twitter'];
        }
        if(!empty($settings['tiktok'])) {
            $social_links[] = $settings['tiktok'];
        }
        $metatags['microdata'] = [
            'website' => [
                '@type' => "WebSite",
                '@id' => base_url() . '#website',
                'url' => base_url(),
                'name' => !empty($settings['company_name']) ? $settings['company_name'] : '',
                'description' => !empty($settings['meta_description']) ? $settings['meta_description'] : '',
                "inLanguage" => !empty($language['iso_code']) ? $language['iso_code'] : $language['lang_code']
            ],
            'organization' => [
                '@type' => ['Organization', 'NewsMediaOrganization'],
                '@id' => base_url() . '#organization',
                'url' => base_url(),
                'name' => !empty($settings['company_name']) ? $settings['company_name'] : '',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => !empty($settings['logo']) && !empty($settings['logo']['path']) ? base_url() . 'image/original/' . $settings['logo']['path'] : ''
                ],
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'ul Twardowskiego 4B',
                    'addressLocality' => 'Rzeszów',
                    'addressRegion' => 'Podkarpackie',
                    'postalCode' => '35-302',
                    'addressCountry' => 'PL',
                    'name' => 'Redakcja RESinet.pl'
                ],
                'contactPoint' => [
                    [
                        '@type' => 'ContactPoint',
                        'contactType' => 'editorial',
                        'telephone' => $settings['phone'],
                        'email' => $settings['email'],
                        'availableLanguage' => ["Polish"],
                    ],
                    [
                        '@type' => 'ContactPoint',
                        'contactType' => 'advertising',
                        'telephone' => $settings['advert_phone'],
                        'email' => $settings['advert_email'],
                        'availableLanguage' => ["Polish"],
                    ]
                ],
                'areaServed' => [
                    [
                        "@type" => "City",
                        "name" => "Rzeszów",
                        "sameAs" => "https://pl.wikipedia.org/wiki/Rzeszów"
                    ],
                    [
                        "@type" => "AdministrativeArea", 
                        "name" => "Podkarpackie",
                        "sameAs" => "https://pl.wikipedia.org/wiki/Województwo_podkarpackie"
                    ]
                ],
                'sameAs' => $social_links,
                'foundingDate' => '2001',
                "knowsAbout" => [
                    "Rzeszów", 
                    "Podkarpacie", 
                    "lokalne wiadomości",
                    "wydarzenia regionalne"
                  ],
                  "slogan" => "Najnowsze wiadomości z Rzeszowa i regionu"
            ],
        ];
        return $metatags;
    }

    private function getPageParentsIds($re_id) {
        $ids = array();
        $page = $this->pageModel->select('id,re_id')->where('id', $re_id)->first();
        if (!empty($page)) {
            $ids[] = $page['id'];
            if (!empty($page['re_id'])) {
                $ids = array_merge($ids, $this->getPageParentsIds($page['re_id']));
            }
        }
        return $ids;
    }

    private function getMenuParentsIds($id_menu) {
        if (empty($this->id_page))
            return array();
        $ids = array();
        $menu_item = $this->pageModel->db->table('menu_item mi')->select('mi.id,mi.id_parent')->where('id_menu', $id_menu)->where('id_target', $this->id_page)->get()->getRowArray();
        if (!empty($menu_item)) {
            $ids[] = $menu_item['id'];
            $ids = array_merge($ids, $this->getMenuParentsId($id_menu, $menu_item['id_parent']));
        }
        return $ids;
    }

    private function getMenuParentsId($id_menu, $id_parent, $id_parent_active=null) {
        $ids = array();
        $menu_item = $this->pageModel->db->table('menu_item mi')->select('mi.id,mi.id_parent,mi.id_parent_active')->where('id_menu', $id_menu)->where('id', $id_parent)->get()->getRowArray();
        if (!empty($menu_item)) {
            if(!empty($id_parent_active)) {
                $ids[] = $id_parent_active;
            } else {
                $ids[] = $menu_item['id'];
            }
            if (!empty($menu_item['id_parent'])) {
                $ids = array_merge($ids, $this->getMenuParentsId($id_menu, $menu_item['id_parent'], $menu_item['id_parent_active']));
            }
        }
        return $ids;
    }

    public function showMenu($id_menu, $id_lang, $locale, $template, $submenu_levels = 0, $options = array()) {
        $check_menu = $this->pageModel->db->table('menu')->select('id')->where('id', $id_menu)->where('publish', 1)->get()->getRowArray();
        if(!empty($check_menu)) {
            $external_submenu = array();
            $url = uri_string();
            if ($locale) {
                $url = substr($url, strlen($locale) + 1);
            }
            if (str_contains($url, '/g/')) {
                $get = explode('/', substr($url, strpos($url, '/g/') + 3));
                $url = substr($url, 0, strpos($url, '/g/'));
            }
            $active_ids = array(
                'menu' => array(),
                'page' => array(),
            );
            $current = $this->pageModel->db->table('links l')->select('l.id,l.link,l.module_slug,l.slug,l.action,m.slug as module')->join('module m', 'l.id_module=m.id', 'left')->where('l.link', $url)->where('l.id_lang', $id_lang)->get()->getRowArray();
                    if (!empty($current)) {
                $ids = array(
                    'event' => array(6, ),
                    'entertainment' => array(),
                    'cinema' => array(7, 4, 22, 23, 90, 91, 733),
                );
                switch (strtolower($current['module'])) {
                                    case 'catalog':
                                     $element = $this->pageModel->db->table('catalog c')->join('catalog_lang cl', 'c.id=cl.id_catalog')->join('menu_item mi', 'mi.id_target=c.id_page_cont')->select('mi.id,mi.id_parent,mi.id_parent_active,mi.id_target')->where('cl.id_link', $current['id'])->where('cl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'page')->get()->getRowArray();
                                    break;
                    case 'foto':
                        if ($current['action'] == 'gallery') {
                            $page = $this->pageModel->db->table('foto_gallery g')->join('foto_gallery_lang gl', 'g.id=gl.id_gallery')->join('page_content pc', 'pc.id=g.id_page_cont')->select('pc.id_page')->where('gl.id_link', $current['id'])->where('gl.id_lang', $id_lang)->get()->getRowArray();
                        } elseif ($current['action'] == 'category') {
                            $page = $this->pageModel->db->table('foto_category g')->join('foto_category_lang gl', 'g.id=gl.id_category')->join('page_content pc', 'pc.id=g.id_page_cont')->select('pc.id_page')->where('gl.id_link', $current['id'])->where('gl.id_lang', $id_lang)->get()->getRowArray();
                        }
                        break;
                    case 'event':
                        switch ($current['module_slug']) {
                            case 'place':
                                $element = $this->pageModel->db->table('event_place_lang epl')->join('menu_item mi', 'mi.id_target=epl.id_place')->select('mi.id,mi.id_parent,mi.id_parent_active,mi.id_target')->where('epl.id_link', $current['id'])->where('epl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'place')->get()->getRowArray();
                                $page = $this->pageModel->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->join('page_content pc', 'pc.id=ep.id_page_cont')->select('pc.id_page')->where('epl.id_link', $current['id'])->where('epl.id_lang', $id_lang)->get()->getRowArray();
                                break;
                            case 'place_type':
                                $element = $this->pageModel->db->table('event_place_type_lang eptl')->join('menu_item mi', 'mi.id_target=eptl.id_type')->select('mi.id,mi.id_parent,mi.id_parent_active,mi.id_target')->where('eptl.id_link', $current['id'])->where('eptl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'place_type')->get()->getRowArray();
                                break;
                            default:
                                $element = $this->pageModel->db->table('event_lang el')->join('event e', 'e.id=el.id_event')->join('page_content pc', 'pc.id=e.id_page_cont')->join('menu_item mi', 'mi.id_target=pc.id_page')->select('mi.id,mi.id_parent,mi.id_parent_active,mi.id_target')->where('el.id_link', $current['id'])->where('el.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->get()->getRowArray();
                                $page = $this->pageModel->db->table('event e')->join('event_lang el', 'e.id=el.id_event')->join('page_content pc', 'pc.id=e.id_page_cont')->select('pc.id_page')->where('el.id_link', $current['id'])->where('el.id_lang', $id_lang)->get()->getRowArray();
                                break;
                        }
                        break;
                    case 'cinema':
                        $element = $this->pageModel->db->table('cinema_movie_lang cml')->join('cinema_movie cm', 'cm.id=cml.id_movie')->join('page_content pc', 'pc.id=cm.id_page_cont')->join('menu_item mi', 'mi.id_target=pc.id_page')->select('mi.id,mi.id_parent,mi.id_parent_active,mi.id_target')->where('cml.id_link', $current['id'])->where('cml.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->get()->getRowArray();
                        $page = $this->pageModel->db->table('cinema_movie cm')->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')->join('page_content pc', 'pc.id=cm.id_page_cont')->select('pc.id_page')->where('cml.id_link', $current['id'])->where('cml.id_lang', $id_lang)->get()->getRowArray();
                        break;
                    case 'news':
                        $element = $this->pageModel->db->table('news_lang nl')->join('menu_item mi', 'mi.id_target=nl.id_news')->select('mi.id,mi.id_parent,mi.id_parent_active,mi.id_target')->where('nl.id_link', $current['id'])->where('nl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'news')->get()->getRowArray();
                        $page = $this->pageModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('page_content pc', 'pc.id=n.id_page_cont')->select('pc.id_page')->where('nl.id_link', $current['id'])->where('nl.id_lang', $id_lang)->get()->getRowArray();
                        break;
                    case 'category': $element = $this->pageModel->db->table('category_lang cl')->join('menu_item mi', 'mi.id_target=cl.id_category')->select('mi.id,mi.id_parent,mi.id_parent_active,mi.id_target')->where('cl.id_link', $current['id'])->where('cl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'category')->get()->getRowArray();
                        break;
                    case 'gallery':
                        $element = $this->pageModel->db->table('gallery_lang gl')->join('menu_item mi', 'mi.id_target=gl.id_gallery')->select('mi.id,mi.id_parent,mi.id_parent_active,mi.id_target')->where('gl.id_link', $current['id'])->where('gl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'gallery')->get()->getRowArray();
                        $page = $this->pageModel->db->table('gallery g')->join('gallery_lang gl', 'g.id=gl.id_gallery')->join('page_content pc', 'pc.id=g.id_page_cont')->select('pc.id_page')->where('gl.id_link', $current['id'])->where('gl.id_lang', $id_lang)->get()->getRowArray();
                        break;
                    default:
                        $element = $this->pageModel->db->table('page_lang pl')->join('menu_item mi', 'mi.id_target=pl.id_page')->select('mi.id,mi.id_parent,mi.id_parent_active,mi.id_target,pl.id_page')->where('pl.id_link', $current['id'])->where('pl.id_lang', $id_lang)->where('mi.id_menu', $id_menu)->where('mi.type', 'page')->get()->getRowArray();
                        $page = $this->pageModel->db->table('page_lang pl')->select('pl.id_page')->where('pl.id_link', $current['id'])->where('pl.id_lang', $id_lang)->get()->getRowArray();
                        break;
                }
            }



            if (!empty($element)) {
                $active_ids['menu'] = array($element['id']);
                $active_ids['menu'] = array_merge($active_ids['menu'], $this->getMenuParentsId($id_menu, $element['id_parent']));
            } elseif (!empty($page)) {
                $active_ids['page'] = $this->getPageParentsIds($page['id_page']);
            }
            if (!empty($options['mode']) && $options['mode'] == 'external_active_submenu') {
                $submenu_levels = 0;
            }

            $menu = $this->getMenuElements($id_menu, $id_lang, $locale, 0, $active_ids, $submenu_levels);
            if (!empty($menu) && (!empty($active_ids['menu']) || !empty($active_ids['page']))) {
                foreach ($menu as $m) {
                    if (in_array($m['id'], $active_ids['menu']) || ($m['type'] == 'page' && in_array($m['id_target'], $active_ids['page']))) {
                        $external_submenu = $this->getMenuElements($id_menu, $id_lang, $locale, $m['id'], $active_ids, 0);
                        break;
                    }
                }
            }
            $html = view('user/menu/' . $template, array('menu' => $menu, 'id_lang' => $id_lang, 'locale' => $locale, 'external_submenu' => $external_submenu));
            return $html;
        }
        return '';
    }

    public function getSpecialWebsites($id_lang, $locale) {
        $specials = array();
        $list = $this->pageModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('settings s', 's.value=p.id')->join('links l', 'l.id=pl.id_link')->select('pl.name,s.name as slug,l.link')->where('pl.id_lang', $id_lang)->like('s.name', 'special_%')->where('p.publish', 1)->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                $specials[substr($l['slug'], 8)] = array(
                    'name' => $l['name'],
                    'link' => ($locale ? $locale . '/' : '') . $l['link'],
                );
            }
        }
        return $specials;
    }

    public function getDirectWebsites($id_lang, $locale) {
        $specials = array();
        $list = $this->pageModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('s.name,sl.value')->where('sl.id_lang', $id_lang)->like('s.name', 'url_%')->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                $specials[substr($l['name'], 4)] = $l['value'];
            }
        }
        return $specials;
    }

}
