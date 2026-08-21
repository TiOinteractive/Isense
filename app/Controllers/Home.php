<?php

namespace App\Controllers;

use App\Models\ModuleModel;
use App\Libraries\Link;
use App\Libraries\Page;
use App\Libraries\Breadcrumbs;
use App\Libraries\Assets;

class Home extends BaseController {

    public function __construct() {
        $this->session = session();
        $this->pageClass = new Page();
        $this->assetsClass = new Assets(array(
            'css' => array('/adm/third-party/font-awasome/css/all.min.css', '/assets/css/style.css', '/assets/css/tiolightbox.css'),
            'js' => array('/assets/js/jquery.min.js', '/assets/js/javascript.js', '/assets/js/jquery.touchSwipe.min.js', '/assets/js/tiolightbox.js', '/assets/js/jquery-confirm.min.js', '/assets/js/header.js'),
            'css_footer' => array('/assets/css/jquery-confirm.min.css'),
            'js_ready' => array('$("a[rel=lightbox]").TiO_lightbox();')
        ));
    }

    public function index() {
        helper('cookie');
        $linkClass = new Link();
        $breadcrumbsClass = new Breadcrumbs();
        $moduleModel = new ModuleModel();
        $agent = $this->request->getUserAgent();
        $is_mobile = $agent->isMobile();

        if (is_dir(ROOTPATH . 'modules/Redirects') && class_exists('\Modules\Redirects\Libraries\Redirects') && !empty($moduleModel->select('id')->where('publish', 1)->where('slug', 'Redirects')->first())) {
            $redirectsClass = new \Modules\Redirects\Libraries\Redirects();
            $redirect = $redirectsClass->checkRedirectsForUrl('/' . uri_string());
            if (!empty($redirect) && !empty($redirect['to'])) {
                return redirect()->to($redirect['to'], $redirect['type'], 'location');
                exit();
            }
        }
        $language = $this->pageClass->checkLanguage();
        if (empty($language)) {
            $language = $lang = $this->pageClass->getPrimaryLang();
            $this->request->setLocale($lang['lang_code']);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            exit();
        }
        $this->request->setLocale($language['lang_code']);
        $id_lang = $language['id'];
        $languages = $this->pageClass->getLanguages();
        $settings = $this->pageClass->getSettings($id_lang);
        $global_links = $linkClass->getGlobalLinks($id_lang, $language['slug']);
        $global_links = array_merge($global_links, $this->pageClass->getSpecialWebsites($id_lang, $language['slug']));
        $global_links = array_merge($global_links, $this->pageClass->getDirectWebsites($id_lang, $language['slug']));
        if (!empty($settings['technical_break']) && $settings['technical_break']) {
            echo view('user/technical_break', array('metatags' => $metatags, 'logo' => !empty($settings['logo']) ? $settings['logo']['path'] : null, 'company_name' => !empty($settings['company_name']) ? $settings['company_name'] : null));
            exit();
        }
        $session_user = $this->session->get('user');
        $link = $linkClass->getLinkByUrl(uri_string(true), $language);
        if (empty($link)) {
            //throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            //exit();
        }
        $metatags = $this->pageClass->getDefaultMetatags($settings, $language, $link);
        if ($this->request->isAJAX()) {
            $post = $this->request->getPost();
            if (!empty($post)) {
                if (!empty($link['slug']) && !empty($link['id_module'])) {
                    $module_data = $moduleModel->db->table('module m')
                                    ->select('m.slug')
                                    ->where('m.id', $link['id_module'])
                                    ->where('m.publish', 1)
                                    ->get()->getRowArray();
                    $class = '\Modules\\' . ucfirst($module_data['slug']) . '\Libraries\\' . ucfirst($module_data['slug']);
                    if (is_dir(ROOTPATH . 'modules/' . ucfirst($module_data['slug'])) && class_exists($class)) {
                        $module = new $class();
                        $module->locale = $language['slug'];
                        $module->global_links = $global_links;
                        $module->settings = $settings;
                        $module->is_mobile = $is_mobile;
                        if (method_exists($module, 'ajaxSlugs')) {
                            return $module->ajaxSlugs($post, $id_lang, $link['slug'], $link);
                        }
                    }
                } elseif (!empty($link['id_module']) || !empty($post['content'])) {
                    if (!empty($post['content'])) {
                        if (!empty($post['sidebar'])) {
                            $module_data = $moduleModel->db->table('module m')
                                            ->join('module_element me', 'm.id=me.id_module')
                                            ->join('sidebar_content sc', 'sc.id_module_element=me.id')
                                            ->join('sidebar_content_lang scl', 'sc.id=scl.id_sidebar_cont')
                                            ->select('m.slug,m.separate,me.slug as element_slug,sc.template,scl.url')
                                            ->where('sc.id', $post['content'])
                                            ->where('m.publish', 1)
                                            ->where('scl.id_lang', $id_lang)
                                            ->get()->getRowArray();
                        } else {
                            $module_data = $moduleModel->db->table('module m')
                                            ->join('module_element me', 'm.id=me.id_module')
                                            ->join('page_content pc', 'pc.id_module_element=me.id')
                                            ->join('page_content_lang pcl', 'pc.id=pcl.id_page_cont')
                                            ->join('page p', 'p.id=pc.id_page')
                                            ->select('m.slug,m.separate,me.slug as element_slug,pc.template,pcl.url')
                                            ->where('pc.id', $post['content'])
                                            ->where('m.publish', 1)
                                            ->where('pcl.id_lang', $id_lang)
                                            ->get()->getRowArray();
                        }
                    } else {
                        $module_data = $moduleModel->db->table('module m')->select('m.slug,m.separate')
                                        ->where('m.id', $link['id_module'])
                                        ->where('m.publish', 1)
                                        ->get()->getRowArray();
                    }
                    $class = '\Modules\\' . ucfirst($module_data['slug']) . '\Libraries\\' . ucfirst($module_data['slug']);
                    if (is_dir(ROOTPATH . 'modules/' . ucfirst($module_data['slug'])) && class_exists($class)) {
                        $module = new $class();
                        $module->locale = $language['slug'];
                        $module->global_links = $global_links;
                        $module->settings = $settings;
                        $module->is_mobile = $is_mobile;
                        if (method_exists($module, 'ajax')) {
                            return $module->ajax($post, $id_lang, $module_data, $link);
                        }
                    }
                }
            }
        } else {
            $footer_flashdata[] = $this->session->getFlashdata('newsletter_action');
            if (!empty($link)) {
                if ($link['id_module']) {
                    $module_data = $moduleModel->db->table('module m')
                                    ->select('m.slug,m.separate')
                                    ->where('m.id', $link['id_module'])
                                    ->where('m.publish', 1)
                                    ->get()->getRowArray();
                    $class = '\Modules\\' . ucfirst($module_data['slug']) . '\Libraries\\' . ucfirst($module_data['slug']);
                    if (is_dir(ROOTPATH . 'modules/' . ucfirst($module_data['slug'])) && class_exists($class)) {
                        $module = new $class();
                        $module->locale = $language['slug'];
                        $module->global_links = $global_links;
                        $module->settings = $settings;
                        $module->is_mobile = $is_mobile;
                        $breadcrumbs = array();
                        if (!empty($link['slug'])) {
                            if (method_exists($module, 'slugs')) {
                                $data = $module->slugs($link['slug'], $id_lang, $link);
                                if (is_a($data, 'CodeIgniter\HTTP\RedirectResponse') || is_a($data, 'CodeIgniter\HTTP\DownloadResponse')) {
                                    return $data;
                                }
                                $template = '\Modules\\' . ucfirst($module_data['slug']) . '\Views/user/slugs/' . (!empty($data) && !empty($data['template']) ? $data['template'] : '');
                                $mobile_template = '\Modules\\' . ucfirst($module_data['slug']) . '\Views/user/slugs/m_' . (!empty($data) && !empty($data['template']) ? $data['template'] : '');
                                if (method_exists($module, 'getBreadcrambs')) {
                                    $breadcrumbs = array_merge($breadcrumbs, $module->getBreadcrambs($language['slug'], $id_lang, $link['slug'], $link));
                                }
                                if (!empty($languages) && count($languages) > 1 && method_exists($module, 'getLanguageLinks')) {
                                    $links = $module->getLanguageLinks($id_lang);
                                    if (!empty($links) && !empty($languages)) {
                                        foreach ($languages as $l => $lang) {
                                            $languages[$l]['link'] = (!empty($lang['slug']) ? '/' . $lang['slug'] : '') . (!empty($links[$l]) ? '/' . $links[$l] : '');
                                            if (!empty($languages[$l]['link'])) {
                                                $metatags['alternative'][] = array('link' => $languages[$l]['link'], 'lang_code' => $lang['lang_code']);
                                            } else {
                                                $languages[$l]['link'] = '/';
                                            }
                                        }
                                    }
                                }
                            }
                            if (method_exists($module, 'assets')) {
                                $assets = $module->assets($link['slug'], (!empty($data) && !empty($data['template']) ? substr($data['template'], 0, -4) : ''), !empty($data) && !empty($data['id']) ? $data['id'] : 0);
                                $this->assetsClass->addAssets($assets);
                            }
                        }
                        if (method_exists($module, 'getSingleByLinkId')) {
                            $data = $module->getSingleByLinkId($link['id'], $id_lang, $link);
                            if (!empty($data['id_page'])) {
                                $page = $this->pageClass->getPageById($data['id_page'], $id_lang);
                                $breadcrumbs = $breadcrumbsClass->getPageBreadcrumbs($page['id_page'], $page['id_parents'], $page['home'], $id_lang, $language['slug']);
                            }
                            if (!empty($data) && !empty($data['page_template'])) {
                                $template = '\App\Views/user/page/' . (!empty($data) && !empty($data['page_template']) ? $data['page_template'] : '');
                                $mobile_template = '\App\Views/user/page/m_' . (!empty($data) && !empty($data['page_template']) ? $data['page_template'] : '');
                            } else {
                                $template = '\Modules\\' . ucfirst($module_data['slug']) . '\Views/user/' . (!empty($data) && !empty($data['dir']) ? $data['dir'] : 'single') . '/' . (!empty($data) && !empty($data['template']) ? $data['template'] : '');
                                $mobile_template = '\Modules\\' . ucfirst($module_data['slug']) . '\Views/user/' . (!empty($data) && !empty($data['dir']) ? $data['dir'] : 'single') . '/m_' . (!empty($data) && !empty($data['template']) ? $data['template'] : '');
                            }
                            if (!empty($data) && !empty($data['id'])) {
                                if (method_exists($module, 'getMetatags')) {
                                    $metatags = deepArrayMergeMultiple($metatags, $module->getMetatags($data['id'], $id_lang, $link, $data));
                                } elseif (!empty($page['id_page'])) {
                                    $metatags = array_merge($metatags, $this->pageClass->getPageMeta($page['id_page'], $id_lang));
                                }
                                if (method_exists($module, 'getBreadcramb')) {
                                    $breadcrumbs = array_merge($breadcrumbs, $module->getBreadcramb($data, $language['slug'], $id_lang, $link));
                                }
                                if (method_exists($module, 'getLanguageLinks')) {
                                    $links = $module->getLanguageLinks($data['id']);
                                    if (!empty($links) && !empty($languages)) {
                                        foreach ($languages as $l => $lang) {
                                            $languages[$l]['link'] = (!empty($lang['slug']) ? '/' . $lang['slug'] : '') . (!empty($links[$l]) ? '/' . $links[$l] : '');
                                            if (!empty($languages[$l]['link'])) {
                                                $metatags['alternative'][] = array('link' => $languages[$l]['link'], 'lang_code' => $lang['lang_code']);
                                            } else {
                                                $languages[$l]['link'] = '/';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if (!empty($data['custom_breadcrumbs'])) {
                            $breadcrumbs = $data['custom_breadcrumbs'];
                        }
                        if (method_exists($module, 'custom_settings')) {
                            $settings = $module->custom_settings($settings);
                        }
                        if (method_exists($module, 'custom_metatags')) {
                            $metatags = array_merge($metatags, $module->custom_metatags($metatags, $settings));
                        }
                        if (method_exists($module, 'assets')) {
                            $assets = $module->assets('single_element', (!empty($data) && !empty($data['template']) ? substr($data['template'], 0, -4) : ''), !empty($data) && !empty($data['id']) ? $data['id'] : 0, !empty($data) ? $data : array());
                            $this->assetsClass->addAssets($assets);
                        }
                        if (!empty($data['custom_meta'])) {
                            $metatags = array_merge($metatags, $data['custom_meta']);
                        }
                        if (!empty($page['template']) and $page['template'] == 'szablon_smaki_page.php') {
                            $metatags['favicon'] = base_url() . 'image/' . $settings['favicon_flavor']['path'];
                            $metatags['apple_icon'] = base_url() . 'image/' . $settings['favicon_flavor']['path'];
                        }
                        // Szablon iSense ma wlasny naglowek/stopke - jego CSS/JS musi sie zaladowac
                        // takze wtedy, gdy strona nie zawiera zadnego bloku modulu Isense.
                        if (!empty($page['template']) && strpos($page['template'], 'isense') === 0) {
                            $this->assetsClass->addAssets(array('css' => array('/assets/isense/css/isense.css'), 'js' => array('/assets/isense/js/isense.js')));
                        }
                        echo view('user/head', array('metatags' => $metatags, 'settings' => $settings, 'global_links' => $global_links, 'session_user' => $session_user, 'languages' => $languages, 'locale' => $language['slug'], 'css_files' => $this->assetsClass->getCss(), 'environment' => ENVIRONMENT, 'mobile' => $is_mobile, 'home' => !empty($page['home']), 'id_lang' => $id_lang, 'page' => $this->pageClass->page, 'breadcrumbs' => $breadcrumbs));
                        //echo view($is_mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header', array('id_lang' => $id_lang, 'page' => $this->pageClass->page, 'breadcrumbs' => $breadcrumbs));
                        if (!empty($data)) {
                            if ($is_mobile && file_exists(ROOTPATH . lcfirst(str_replace('\\', '/', substr($mobile_template, 1))))) {
                                echo view($mobile_template, array('data' => $data));
                            } elseif (file_exists(ROOTPATH . lcfirst(str_replace('\\', '/', substr($template, 1))))) {
                                echo view($template, array('data' => $data));
                            }
                        }
                        //echo view($is_mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer', array('css_footer' => $this->assetsClass->getCssFooter(), 'css_code' => $this->assetsClass->getCssCode(), 'js_files' => $this->assetsClass->getJs(), 'js_ready' => $this->assetsClass->getJsReady(), 'js_load' => $this->assetsClass->getJsLoad(), 'js_code' => $this->assetsClass->getJsCode()));
                        echo view('user/foot', array('css_footer' => $this->assetsClass->getCssFooter(), 'css_code' => $this->assetsClass->getCssCode(), 'js_files' => $this->assetsClass->getJs(), 'js_ready' => $this->assetsClass->getJsReady(), 'js_load' => $this->assetsClass->getJsLoad(), 'js_code' => $this->assetsClass->getJsCode(), 'footer_flashdata' => $footer_flashdata));
                    } else {
                        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                        exit();
                    }
                } else {
                    $page = $this->pageClass->getPageByLinkId($link['id'], $id_lang);
                    if (!empty($page)) {
                        if (!empty($page['content'])) {
                            foreach ($page['content'] as $k => $content) {
                                $module_data = $moduleModel->db->table('module m')
                                                ->join('module_element me', 'm.id=me.id_module')
                                                ->select('m.slug,m.separate,me.slug as element_slug')
                                                ->where('me.id', $content['id_module_element'])
                                                ->where('m.publish', 1)
                                                ->get()->getRowArray();
                                $class = '\App\Libraries\\' . ucfirst($module_data['slug']);
                                $template = '\App\Views/user/' . (!empty($module_data['element_slug']) ? $module_data['element_slug'] . '/' : '');
                                if (!class_exists($class)) {
                                    $class = '\Modules\\' . ucfirst($module_data['slug']) . '\Libraries\\' . ucfirst($module_data['slug']);
                                    $template = '\Modules\\' . ucfirst($module_data['slug']) . '\Views/user/' . (!empty($module_data['element_slug']) ? $module_data['element_slug'] . '/' : '');
                                    if (!is_dir(ROOTPATH . 'modules/' . ucfirst($module_data['slug']))) {
                                        $class = null;
                                    }
                                }
                                if (!empty($class) && class_exists($class)) {
                                    $module = new $class();
                                    $module->locale = $language['slug'];
                                    $module->global_links = $global_links;
                                    $module->settings = $settings;
                                    $module->is_mobile = $is_mobile;
                                    //var_dump($class);
                                    if (method_exists($module, 'index')) {
                                        $mdata = $module->index($content, $id_lang, $module_data['element_slug'], $link);
                                        if (!empty($mdata) && !empty($mdata['custom_page_template'])) {
                                            $page['template'] = $mdata['custom_page_template'];
                                        }
                                        if (!empty($mdata) && array_key_exists('custom_data', $mdata)) {
                                            $page['custom_data'] = $mdata;
                                        } else {
                                            $page['content'][$k]['data'] = $mdata;
                                        }
                                        if (empty($page['content'][$k]['data'])) {
                                            unset($page['content'][$k]);
                                            continue;
                                        } elseif (!empty($page['content'][$k]['data']['views_dir'])) {
                                            $template = '\Modules\\' . ucfirst($module_data['slug']) . '\Views/user/' . $page['content'][$k]['data']['views_dir'] . '/';
                                        }
                                    }
                                    if (!empty($page['content'][$k]['data']['custom_breadcrumbs'])) {
                                        $page['custom_data']['custom_data']['custom_breadcrumbs'] = $page['content'][$k]['data']['custom_breadcrumbs'];
                                    }
                                } else {
                                    unset($page['content'][$k]);
                                    continue;
                                }
                                $mobile_template = $template;
                                if (!empty($page['content'][$k]['data']['template'])) {
                                    $template .= $page['content'][$k]['data']['template'];
                                    $mobile_template .= 'm_' . $page['content'][$k]['data']['template'];
                                } elseif (!empty($content['template'])) {
                                    $template .= $content['template'];
                                    $mobile_template .= 'm_' . $content['template'];
                                } else {
                                    unset($page['content'][$k]);
                                    continue;
                                }
                                if (method_exists($module, 'assets')) {
                                    $assets = $module->assets($module_data['element_slug'], !empty($template) ? substr($template, strrpos($template, '/') + 1, -4) : '', !empty($page['content'][$k]['data']) && !empty($page['content'][$k]['data']['id']) ? $page['content'][$k]['data']['id'] : 0, !empty($page['content'][$k]['data']) ? $page['content'][$k]['data'] : array());
                                    $this->assetsClass->addAssets($assets);
                                }
                                if ($is_mobile && file_exists(ROOTPATH . lcfirst(str_replace('\\', '/', substr($mobile_template, 1))))) {
                                    $page['content'][$k]['template'] = $mobile_template;
                                } elseif (file_exists(ROOTPATH . lcfirst(str_replace('\\', '/', substr($template, 1))))) {
                                    $page['content'][$k]['template'] = $template;
                                } else {
                                    $page['content'][$k]['template'] = '';
                                }
                                if (method_exists($module, 'custom_settings')) {
                                    $settings = $module->custom_settings($settings);
                                }
                                if (method_exists($module, 'getContentMetaTags')) {
                                    $metatags = $module->getContentMetaTags($content, $metatags, !empty($page['content'][$k]['data']) ? $page['content'][$k]['data'] : array(), $settings, $language);
                                }
                                if (method_exists($module, 'custom_metatags') and $page['template'] != 'szablon_resinet_main.php') {
                                    $metatags = $module->custom_metatags($metatags, $settings);
                                }
                                if (empty($metatags['pagination']) && isset($page['content'][$k]['data']['pagination'])) {
                                    $metatags = array_merge($metatags, $page['content'][$k]['data']['pagination']);
                                }
                            }
                        }
                        if (!empty($page['template']) and $page['template'] == 'szablon_smaki_page.php') {
                            $metatags['favicon'] = base_url() . 'image/' . $settings['favicon_flavor']['path'];
                            $metatags['apple_icon'] = base_url() . 'image/' . $settings['favicon_flavor']['path'];
                        }
                        $metatags = array_merge($metatags, $this->pageClass->getPageMeta($page['id_page'], $id_lang, $metatags));
                        $links = $this->pageClass->getLanguageLinks($page['id_page']);
                        if (!empty($links) && !empty($languages)) {
                            foreach ($languages as $l => $lang) {
                                $languages[$l]['link'] = (!empty($lang['slug']) ? '/' . $lang['slug'] : '') . (!empty($links[$l]) ? '/' . $links[$l] : '');
                                if (!empty($languages[$l]['link'])) {
                                    $metatags['alternative'][] = array('link' => $languages[$l]['link'], 'lang_code' => $lang['lang_code']);
                                } else {
                                    $languages[$l]['link'] = '/';
                                }
                            }
                        }
                        if (!empty($page['custom_data']['custom_data']['custom_breadcrumbs'])) {
                            $bread = $page['custom_data']['custom_data']['custom_breadcrumbs'];
                        } else {
                            $bread = $breadcrumbsClass->getPageBreadcrumbs($page['id_page'], $page['id_parents'], $page['home'], $id_lang, $language['slug']);
                        }
                        if (!empty($page['custom_data']['custom_data']['custom_meta'])) {
                            $metatags = array_merge($metatags, $page['custom_data']['custom_data']['custom_meta']);
                        }
                        // Szablon iSense ma wlasny naglowek/stopke - jego CSS/JS musi sie zaladowac
                        // takze wtedy, gdy strona nie zawiera zadnego bloku modulu Isense.
                        if (!empty($page['template']) && strpos($page['template'], 'isense') === 0) {
                            $this->assetsClass->addAssets(array('css' => array('/assets/isense/css/isense.css'), 'js' => array('/assets/isense/js/isense.js')));
                        }
                        echo view('user/head', array('metatags' => $metatags, 'settings' => $settings, 'global_links' => $global_links, 'session_user' => $session_user, 'languages' => $languages, 'locale' => $language['slug'], 'css_files' => $this->assetsClass->getCss(), 'environment' => ENVIRONMENT, 'mobile' => $is_mobile, 'home' => !empty($page['home']), 'id_lang' => $id_lang, 'page' => $this->pageClass->page, 'breadcrumbs' => $bread));
                        //echo view($is_mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header', array('id_lang' => $id_lang, 'page' => $this->pageClass->page, 'breadcrumbs' => $bread));
                        echo view($is_mobile && file_exists(APPPATH . 'Views/user/page/m_' . $page['template']) ? 'user/page/m_' . $page['template'] : 'user/page/' . $page['template'], $page);
                        //echo view($is_mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer', array('css_footer' => $this->assetsClass->getCssFooter(), 'css_code' => $this->assetsClass->getCssCode(), 'js_files' => $this->assetsClass->getJs(), 'js_ready' => $this->assetsClass->getJsReady(), 'js_load' => $this->assetsClass->getJsLoad(), 'js_code' => $this->assetsClass->getJsCode()));
                        echo view('user/foot', array('css_footer' => $this->assetsClass->getCssFooter(), 'css_code' => $this->assetsClass->getCssCode(), 'js_files' => $this->assetsClass->getJs(), 'js_ready' => $this->assetsClass->getJsReady(), 'js_load' => $this->assetsClass->getJsLoad(), 'js_code' => $this->assetsClass->getJsCode(), 'footer_flashdata' => $footer_flashdata));
                    } else {
                        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                        exit();
                    }
                }
            } else {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                exit();
            }
        }
    }
}
