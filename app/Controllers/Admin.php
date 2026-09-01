<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AdminModel;
use App\Models\LanguageModel;
use App\Models\SettingsModel;
use App\Models\PageModel;
use App\Models\PageContentModel;
use App\Models\MenuModel;
use App\Models\ModuleModel;
use App\Models\SidebarModel;
use App\Libraries\Breadcrumb;
use App\Libraries\Link;
use App\Libraries\ClearCache;
use App\Libraries\Service;
use App\Libraries\Assets;

class Admin extends BaseController {

    public function __construct() {
        helper("history");
        new ClearCache();
        $this->assetsClass = new Assets(array(
            'css' => array('/adm/third-party/font-awasome/css/all.min.css', '/adm/css/style.css'),
            'css_footer' => array('/adm/css/jquery-confirm.min.css', '/adm/third-party/jodit/build/jodit.min.css', '/adm/css/daterangepicker.min.css'),
            'js' => array('/adm/third-party/jquery/jquery.min.js', '/adm/third-party/jquery/jquery-ui.min.js', '/adm/js/jquery-confirm.min.js' , '/adm/third-party/jodit/build/jodit.min.js', '/adm/js/moment.min.js', '/adm/js/daterangepicker.min.js', '/adm/js/javascript.js'),
        ));
    }

    public function index($page = '', $action = '', $id = 0, $id2 = 0) {
        helper(['form']);
        $this->session = session();
        $this->pageModel = new PageModel();
        $languageModel = new LanguageModel();
        $service = new Service();
        $app_config = new \Config\App();
        $this->CheckAccess();
        $this->locale = $this->request->getLocale() == $this->request->getDefaultLocale() ? '' : $this->request->getLocale();
        $lang = $languageModel->select('id,lang_code')->where('lang_code', $this->request->getLocale())->first();
        if (empty($lang)) {
            $lang = $languageModel->select('id,lang_code')->where('default', 1)->first();
        }
        $this->id_lang = $lang['id'];
        $this->lang_code = $lang['lang_code'];
        if (!empty($this->session->get('adminLoggedIn'))) {
            $this->languages = array();
            $languages = $languageModel->select('id,name,short_name,slug,default,lang_code')->where('publish', 1)->orderBy('default', 'DESC')->findAll();
            if (!empty($languages)) {
                $url = uri_string(true);
                $url_arr = explode('/', $url);
                if (!empty($url_arr) && $url_arr[0] != env('ADMIN_PANEL_SLUG') && strlen($url_arr[0]) >= 2 && strlen($url_arr[0]) <= 3) {
                    unset($url_arr[0]);
                    $url = implode('/', $url_arr);
                }
                foreach ($languages as $k => $lang) {
                    $lang['link'] = ($lang['slug'] ? '/' . $lang['slug'] : '') . '/' . $url;
                    $languages[$k] = $lang;
                    $this->languages[$lang['id']] = $lang;
                }
            }
            $page = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $page))));
            if ($this->request->isAJAX()) {
                if (method_exists($this, 'ajax' . ucfirst($page))) {
                    $method = 'ajax' . ucfirst($page);
                    return $this->$method($action, $id, $id2);
                } elseif (is_dir(ROOTPATH . 'modules/' . ucfirst($page)) && class_exists('\Modules\\' . ucfirst($page) . '\Controllers\\' . ucfirst($page) . 'Admin')) {
                    $class = '\Modules\\' . ucfirst($page) . '\Controllers\\' . ucfirst($page) . 'Admin';
                    $module = new $class();
                    $module->id_lang = $this->id_lang;
                    $module->locale = $this->locale;
                    $module->languages = $this->languages;
                    if (method_exists($module, 'ajax')) {
                        return $module->ajax($action, $id, $id2);
                    }
                }
            } else {
                $admin = array(
                    'login' => $this->session->get('login'),
                    'name' => $this->session->get('name'),
                    'email' => $this->session->get('email')
                );
                $moduleModel = new ModuleModel();
                $modules = $moduleModel->join('module_lang ml', 'module.id=ml.id_module')->select('module.id,module.slug,module.ico,ml.name')->where('ml.id_lang', $this->id_lang)->where('module.publish', 1)->where('module.main', 1)->findAll();
				if(!empty($modules)) {
					foreach($modules as $k=>$mod) {
					  if($mod['id']==21) {
					   $modules[$k]['comments']=$moduleModel->db->table('comments')->where('status', 'new')->countAllResults();
					   $modules[$k]['comments_link']=($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/comments';
					  }
					  elseif($mod['id']==17) {
					   $modules[$k]['comments']=$moduleModel->db->table('flavors_comments')->where('status', 'new')->countAllResults();
					   $modules[$k]['comments_link']=($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/comments';
					  }
				    }
				}
				if(empty($url_arr)) {$url_arr=array();}
                $this->assetsClass->addJS('/adm/js/language_' . $this->request->getLocale() . '.js');
                echo view('admin/header', array('admin' => $admin, 'languages' => $this->languages, 'locale' => $this->locale, 'lang_code' => $this->lang_code, 'css_files' => $this->assetsClass->getCss(), 'environment' => ENVIRONMENT, 'service' => $service->check(), 'main_modules' => $modules,'url_array'=>$url_arr));
                $left_pages = $this->pageModel->getPagesStructure($this->id_lang);
                $left_submenu = $moduleModel->getSubModulesStructure($page, uri_string(), $this->id_lang, $id, $id2);
                echo view('admin/menu_left', array('left_pages' => $left_pages, 'left_submodules' => $left_submenu, 'url_array' => $url_arr));
                
                if (method_exists($this, $page)) {
                    $r = $this->$page($action, $id, $id2);
                    if (is_a($r, 'CodeIgniter\HTTP\RedirectResponse') || is_a($r, 'CodeIgniter\HTTP\DownloadResponse')) {
                        return $r;
                    }
                } elseif (is_dir(ROOTPATH . 'modules/' . ucfirst($page)) && class_exists('\Modules\\' . ucfirst($page) . '\Controllers\\' . ucfirst($page) . 'Admin')) {
                    $class = '\Modules\\' . ucfirst($page) . '\Controllers\\' . ucfirst($page) . 'Admin';
                    $module = new $class();
                    $module->id_lang = $this->id_lang;
                    $module->locale = $this->locale;
                    $module->languages = $this->languages;
                    if(method_exists($module, 'index')) {
                        $r = $module->index($action, $id, $id2);
                        if (is_a($r, 'CodeIgniter\HTTP\RedirectResponse') || is_a($r, 'CodeIgniter\HTTP\DownloadResponse')) {
                            return $r;
                        }
                    }
                    if(method_exists($module, 'assets')) {
                        $assets = $module->assets($action);
                        $this->assetsClass->addAssets($assets);
                    }
                } elseif ($page == '') {
                    return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page');
                    exit();
                    $this->breadcrumb = new Breadcrumb();
                    $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
                    $breadcrumbs = $this->breadcrumb->render();
                    echo view('admin/dashboard', array('breadcrumbs' => $breadcrumbs));
                } else {
                    $this->breadcrumb = new Breadcrumb();
                    $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
                    $breadcrumbs = $this->breadcrumb->render();
                    echo view('admin/404', array('breadcrumbs' => $breadcrumbs));
                }
                echo view('admin/footer', array('css_footer' => $this->assetsClass->getCssFooter(), 'js_files' => $this->assetsClass->getJs(), 'js_ready' => $this->assetsClass->getJsReady(), 'js_load' => $this->assetsClass->getJsLoad()));
            }
        } else {
            echo view('admin/signin');
        }
    }
    
    public function loginAuth() {
        $session = session();
        $adminModel = new AdminModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $data = $adminModel->having('active', 1)->where('email', $email)->orWhere('login', $email)->first();
        if ($data) {
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if ($authenticatePassword) {
                $ses_data = [
                    'id' => $data['id'],
                    'login' => $data['login'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'adminLoggedIn' => TRUE,
                    'filebrowser' => TRUE
                ];
                $adminModel->set('last_active', date("Y-m-d H:i:s"))->where('id', $data['id'])->update();
                $session->set($ses_data);
                return redirect()->to('/' . env('ADMIN_PANEL_SLUG'));
            } else {
                $session->setFlashdata('msg', lang('Signin.loginincorrect') . '.');
                return redirect()->to('/' . env('ADMIN_PANEL_SLUG'));
            }
        } else {
            $session->setFlashdata('msg', lang('Signin.loginincorrect') . '.');
            return redirect()->to('/' . env('ADMIN_PANEL_SLUG'));
        }
    }

    public function lostPassword() {
        helper(['form']);
		
		if(empty($this->locale)) {$this->locale='';}
        echo view('admin/lost-password',array('locale' => $this->locale));
    }

    public function remindPass() {
        helper(['text']);
        $session = session();
        $adminModel = new AdminModel();
        $email = $this->request->getVar('email');
        $data = $adminModel->having('active', 1)->where('email', $email)->first();
        if ($data) {
            $token = str_shuffle($data['id'] . random_string('alnum', 20));
            $email = \Config\Services::email();
            $email->attach(base_url() . 'adm/img/tio_logo_6-0-1.jpg');
            $cid_logo = $email->setAttachmentCID(base_url() . 'adm/img/tio_logo_6-0-1.jpg');
            $this->data['data']['cid_logo'] = $cid_logo;
            $this->data['data']['header'] = lang('Signin.EmailTopicNewPass');
            $this->locale = $this->request->getLocale() == $this->request->getDefaultLocale() ? '' : $this->request->getLocale();
            
            $this->data['data']['msg'] = str_replace('{token}', base_url() . ($this->locale ? '/' . $this->locale : '') . env('ADMIN_PANEL_SLUG') . '/new-password/' . $token, lang('Signin.EmailMsgNewPass'));
            $message = view('emails/new-password', $this->data);
            $token_valid = date("Y-m-d H:i:s", strtotime("+3 hours"));
            $adminModel->set('token', $token)->set('token_valid', $token_valid)->where('active', 1)->where('id', $data['id'])->update();
            $email = \Config\Services::email();
            $email->setTo($data['email']);
            $email->setSubject(lang('Signin.EmailTopicNewPass'));
            $email->setMessage($message);
            if ($email->send()) {
                $session->setFlashdata('msg-send', lang('Signin.mailsend'));
                return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/lost-password');
            } else {
                $session->setFlashdata('msg', lang('Signin.mailnotsend'));
                return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/lost-password');
            }
        } else {
            $session->setFlashdata('msg', lang('Signin.emailincorrect'));
            return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/lost-password');
        }
    }

    public function newPassword($hash, $ok = '') {
        helper(['form', 'url']);
        $adminModel = new AdminModel();
        if (!$ok) {
            $data = $adminModel->select('id,token_valid,token')->where('active', 1)->where('token', $hash)->where('token_valid >=', date("Y-m-d H:i:s"))->first();
            if ($data and $_POST and ($_POST['newpass'] or $_POST['newpass2'])) {
                $validation = \Config\Services::validation();
                $validation->setRules([
                    'newpass' => 'required|min_length[8]|regex_match[/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[0-9])(?=.*[!^(){}?\[\]<>~%@#&*+=_-])/]',
                    'newpass2' => 'required|min_length[8]|matches[newpass]|regex_match[/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[0-9])(?=.*[!^(){}?\[\]<>~%@#&*+=_-])/]',
                        ],
                        [
                            'newpass' => [
                                'min_length' => lang('Signin.validation.min_length'),
                                'required' => lang('Signin.validation.required'),
                                'regex_match' => lang('Signin.validation.regex_match'),
                            ],
                            'newpass2' => [
                                'min_length' => lang('Signin.validation.min_length'),
                                'required' => lang('Signin.validation.required'),
                                'matches' => lang('Signin.validation.matches'),
                                'regex_match' => lang('Signin.validation.regex_match'),
                            ],
                        ]
                );
                if ($validation->withRequest($this->request)->run()) {
                    $adminModel->set('token_valid', '')->set('password', password_hash($_POST['newpass'], PASSWORD_BCRYPT))->where('active', 1)->where('id', $data['id'])->where('token', $hash)->update();
                    return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/new-password/' . $hash . '/ok');
                } else {
                    $data['errors'] = $validation->getErrors();
                }
            }
        }
        if ($ok) {
            $data['susscess'] = 'ok';
        }
        if (!isset($data)) {
            $data = array();
        }
        $this->locale = $this->request->getLocale() == $this->request->getDefaultLocale() ? '' : $this->request->getLocale();
        $data['locale'] = $this->locale;
        $data['token'] = $hash;
        echo view('admin/new-password', $data);
    }

    public function logout() {
        $session = session();
        $session->destroy();
        return redirect()->to('/' . env('ADMIN_PANEL_SLUG'));
    }

    public function administration() {
        $administrations = array(
            array(
                'name' => lang('Admin.administration.Settings'),
                'link' => ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/settings',
                'ico' => '<svg viewBox="0 0 32 32"><g><path d="M30,16c0-1.969-1.431-3.611-3.307-3.94l-0.347-0.835c1.095-1.56,0.945-3.731-0.447-5.124   c-1.346-1.346-3.599-1.521-5.125-0.448l-0.835-0.346C19.61,3.431,17.969,2,16,2c-1.969,0-3.611,1.431-3.94,3.307l-0.835,0.346   C9.667,4.56,7.494,4.708,6.101,6.101c-0.756,0.755-1.172,1.76-1.172,2.829c0,0.833,0.253,1.628,0.724,2.296L5.307,12.06   C3.43,12.389,2,14.031,2,16c0,0,0,0,0,0s0,0,0,0c0,1.97,1.431,3.611,3.307,3.94l0.346,0.834c-1.094,1.561-0.945,3.732,0.448,5.125   c1.344,1.345,3.601,1.521,5.125,0.448l0.835,0.346c0.329,1.876,1.971,3.307,3.94,3.307c1.97,0,3.611-1.431,3.939-3.307l0.835-0.347   c1.559,1.095,3.73,0.947,5.125-0.447c0.756-0.756,1.172-1.76,1.172-2.829c0-0.833-0.254-1.628-0.724-2.296l0.346-0.834   C28.569,19.612,30,17.971,30,16C30,16.001,30,16.001,30,16C30,16,30,16,30,16z M26,18.002c-0.404,0-0.769,0.243-0.924,0.617   l-0.808,1.948c-0.155,0.374-0.069,0.804,0.217,1.09c0.378,0.378,0.586,0.88,0.586,1.414c0,0.535-0.208,1.037-0.586,1.415   c-0.78,0.78-2.051,0.778-2.829,0c-0.286-0.286-0.716-0.372-1.09-0.217l-1.949,0.808C18.243,25.232,18,25.597,18,26.001   c0,1.103-0.897,2-2,2c-1.103,0-2-0.897-2-2c0-0.404-0.244-0.77-0.617-0.924l-1.95-0.808c-0.375-0.155-0.803-0.069-1.09,0.217   c-0.756,0.756-2.072,0.756-2.828,0c-0.78-0.78-0.78-2.049,0-2.829c0.286-0.286,0.372-0.716,0.217-1.09l-0.808-1.948   C6.769,18.245,6.404,18.002,6,18.002c-1.103,0-2-0.897-2-2.001c0,0,0,0,0,0s0,0,0,0c0-1.103,0.897-2,2-2   c0.404,0,0.769-0.244,0.924-0.617l0.808-1.95c0.155-0.374,0.069-0.804-0.217-1.09c-0.378-0.378-0.586-0.88-0.586-1.414   c0-0.534,0.208-1.037,0.586-1.415c0.78-0.78,2.049-0.78,2.829,0c0.286,0.286,0.716,0.37,1.09,0.217l1.949-0.808   C13.756,6.769,14,6.404,14,6c0-1.103,0.897-2,2-2c1.103,0,2,0.897,2,2c0,0.404,0.244,0.769,0.617,0.924l1.95,0.808   c0.374,0.154,0.804,0.07,1.09-0.217c0.756-0.756,2.072-0.756,2.828,0c0.779,0.78,0.779,2.049,0,2.829   c-0.286,0.286-0.372,0.716-0.217,1.09l0.808,1.95C25.23,13.756,25.596,14,26,14c1.103,0,2,0.897,2,2c0,0,0,0,0,0s0,0,0,0   C28,17.104,27.103,18.002,26,18.002z"/><path d="M16,11c-2.757,0-5,2.243-5,5s2.243,5,5,5c0.552,0,1-0.447,1-1s-0.448-1-1-1c-1.654,0-3-1.346-3-3s1.346-3,3-3s3,1.346,3,3   c0,0.582-0.166,1.146-0.481,1.632c-0.301,0.463-0.169,1.082,0.295,1.383c0.463,0.301,1.082,0.17,1.383-0.295   C20.722,17.91,21,16.969,21,16C21,13.243,18.757,11,16,11z"/></g></svg>',
            ),
            array(
                'name' => lang('Admin.administration.Administrators'),
                'link' => ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administrators',
                'ico' => '<svg viewBox="0 0 16 16"><g id="Guide"/><g id="Layer_2"><g><path d="M8,7c1.38,0,2.5-1.12,2.5-2.5S9.38,2,8,2S5.5,3.12,5.5,4.5S6.62,7,8,7z M8,3c0.83,0,1.5,0.67,1.5,1.5S8.83,6,8,6    S6.5,5.33,6.5,4.5S7.17,3,8,3z"/><path d="M9.71,7.5H6.29C4.47,7.5,3,9.07,3,10.99v2.51C3,13.78,3.22,14,3.5,14h9c0.28,0,0.5-0.22,0.5-0.5v-2.51    C13,9.07,11.53,7.5,9.71,7.5z M12,13H4v-2.01C4,9.62,5.03,8.5,6.29,8.5h3.43c1.26,0,2.29,1.12,2.29,2.49V13z"/></g></g></svg>',
            ),
            array(
                'name' => lang('Admin.administration.MainMenu'),
                'link' => ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/main-menu',
                'ico' => '<svg viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path></svg>',
            ),
            array(
                'name' => lang('Admin.administration.Sidebars'),
                'link' => ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/sidebar',
                'ico' => '<svg viewBox="0 0 16 16"><path d="M2 2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2zm12-1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h12z"/><path d="M13 4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V4z"/></svg>',
                // Ukryte na zyczenie — iSense nie korzysta z paskow bocznych.
                // Sekcja dziala dalej, wystarczy skasowac ta linie, by wrocila na liste.
                'hide' => true,
            ),
            array(
                'name' => lang('Admin.special_website.SpecialWebsiteSettings'),
                'link' => ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/special-website-settings',
                'ico' => '<svg viewBox="0 0 32 32"><g id="background"><rect fill="none" height="32" width="32"/></g><g id="computer_x5F_settings"><path d="M30.766,27.531C31.545,26.2,31.999,24.654,32,23c-0.001-3.121-1.589-5.868-4-7.482V2H4v18h10.522   c-0.226,0.638-0.387,1.306-0.464,2H4l-4,8h17.349c1.545,1.248,3.51,1.999,5.651,2c2.142-0.001,4.104-0.752,5.649-2H32   L30.766,27.531z M6,18V4h20v10.523C25.061,14.19,24.054,14,23,14c-3.122,0-5.869,1.588-7.483,4H6z M16.115,23   c0.009-3.801,3.084-6.876,6.885-6.885c3.799,0.009,6.874,3.084,6.883,6.885c-0.009,3.799-3.084,6.874-6.883,6.883   C19.199,29.874,16.124,26.799,16.115,23z"/><path d="M28,24v-2.001h-1.663c-0.063-0.212-0.145-0.413-0.245-0.606l1.187-1.187l-1.416-1.415l-1.165,1.166   c-0.22-0.123-0.452-0.221-0.697-0.294V18h-2v1.662c-0.229,0.068-0.446,0.158-0.652,0.27l-1.141-1.14l-1.415,1.415l1.14,1.14   c-0.112,0.207-0.202,0.424-0.271,0.653H18v2h1.662c0.073,0.246,0.172,0.479,0.295,0.698l-1.165,1.163l1.413,1.416l1.188-1.187   c0.192,0.101,0.394,0.182,0.605,0.245V28H24v-1.665c0.229-0.068,0.445-0.158,0.651-0.27l1.212,1.212l1.414-1.416l-1.212-1.21   c0.111-0.206,0.201-0.423,0.27-0.651H28z M22.999,24.499c-0.829-0.002-1.498-0.671-1.501-1.5c0.003-0.829,0.672-1.498,1.501-1.501   c0.829,0.003,1.498,0.672,1.5,1.501C24.497,23.828,23.828,24.497,22.999,24.499z"/></g></svg>',
                // Ukryte na zyczenie — iSense nie ma stron specjalnych z tej listy.
                // Sekcja dziala dalej, wystarczy skasowac ta linie, by wrocila na liste.
                'hide' => true,
            )
        );
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.administration.Administration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administration');
        $breadcrumbs = $this->breadcrumb->render();
        echo view('admin/administration/list', array('administrations' => $administrations, 'breadcrumbs' => $breadcrumbs));
    }

    public function modules() {
        $moduleModel = new ModuleModel();
        $modules = $moduleModel->join('module_lang ml', 'module.id=ml.id_module')->select('module.id,module.slug,module.ico,ml.name')->where('ml.id_lang', $this->id_lang)->where('module.publish', 1)->where('module.separate', 1)->findAll();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.modules.Modules'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/modules');
        $breadcrumbs = $this->breadcrumb->render();
        echo view('admin/modules/list', array('modules' => $modules, 'breadcrumbs' => $breadcrumbs));
    }

    public function settings() {
        $settingsModel = new SettingsModel();
        $post = $this->request->getPost();
        $settings = $settingsModel->getAllSettings();
        $flashdata = array();
        if (!empty($post)) {
            $result = $settingsModel->saveSettings($post);
            if ($result) {
                $this->session->setFlashdata('settings', array(
                    'status' => true,
                    'msg' => lang('Admin.settings.EditSuccess') . '!'
                ));
                HistoryStat('', '', 'settings', 'Settings', lang('Admin.settings.EditSuccess'));
                return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/settings');
            } else {
                $flashdata = array(
                    'status' => false,
                    'msg' => lang('Admin.settings.EditError') . '!',
                    'list' => $errors
                );
                $settings = $post;
            }
        } else {
            $flashdata = $this->session->getFlashdata('settings');
        }
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css';
        $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/tmpl.min.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/load-image.all.min.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/canvas-to-blob.min.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.blueimp-gallery.min.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.iframe-transport.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-process.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-image.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-audio.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-video.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-validate.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-ui.js';
        $assets['js'][] = '/adm/js/slider.js';
        $assets['js'][] = '/adm/js/file-uploader.js';
        $this->assetsClass->addAssets($assets);
        
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.administration.Administration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administration');
        $this->breadcrumb->add(lang('Admin.settings.Settings'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/settings');
        $breadcrumbs = $this->breadcrumb->render();
        echo view('admin/settings/form', array('settings' => $settings, 'breadcrumbs' => $breadcrumbs, 'flashdata' => $flashdata));
    }

    public function administrators($action = '', $id = 0) {
        helper(['form', 'url']);
        $adminModel = new AdminModel();
        $adminModel->locale = $this->locale;
        $administrator = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.administration.Administration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administration');
        $this->breadcrumb->add(lang('Admin.administration.Administrators'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administrators');
        $result = array();
        switch ($action) {
            case 'save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $post['edit_id'] = $id;
                    $result = $adminModel->saveAdministrator($post);
                }
                $administrator = $adminModel->getAdministratorById($id);
                $breadcrumbs = $this->breadcrumb->render();
                echo view('admin/administration/administrator_edit', array('administrator' => $administrator, 'result' => $result, 'breadcrumbs' => $breadcrumbs));
                break;
            case 'edit':
                $administrator = $adminModel->getAdministratorById($id);
                $this->breadcrumb->add(lang('Admin.administrators.Administrator') . ': ' . $administrator['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administrators');
                $breadcrumbs = $this->breadcrumb->render();
                echo view('admin/administration/administrator_edit', array('administrator' => $administrator, 'breadcrumbs' => $breadcrumbs));
                break;
            case 'add':
                $breadcrumbs = $this->breadcrumb->render();
                echo view('admin/administration/administrator', array('administrator' => $administrator, 'breadcrumbs' => $breadcrumbs));
                break;
            default :
                $administrators = $adminModel->getAdministratorsList();
                $breadcrumbs = $this->breadcrumb->render();
                echo view('admin/administration/administrators', array('administrators' => $administrators, 'breadcrumbs' => $breadcrumbs));
                break;
        }
    }

    public function specialWebsiteSettings($action = '', $id = 0) {
        $settingsModel = new SettingsModel();
        $post = $this->request->getPost();
        $linkClass = new Link();
        $links = $linkClass->getModuleLinks();
        $specials = $settingsModel->getSpecialWebsites();
        $direct = $settingsModel->getAllDirectLinks();
        if (!empty($post)) {
            if(!empty($post['links'])) {
                $result = $linkClass->saveModuleLinks($post['links']);
            }
            if(!empty($post['special'])) {
                $result = $settingsModel->saveSpecialWebsites($post['special']);
            }
            if(!empty($post['direct'])) {
                $result = $settingsModel->saveDirectLinks($post['direct']);
            }

            if ($result) {
                $this->session->setFlashdata('special_settings', array(
                    'status' => true,
                    'msg' => lang('Admin.settings.EditSuccess') . '!'
                ));
                HistoryStat('', '', 'special_settings', 'Basket settings', lang('Admin.settings.EditSuccess'));
                return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/special-website-settings');
            } else {
                $flashdata = array(
                    'status' => false,
                    'msg' => lang('Admin.settings.EditError') . '!',
                    'list' => $errors
                );
            }
        } else {
            $flashdata = $this->session->getFlashdata('special_settings');
        }
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.administration.Administration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administration');
        $this->breadcrumb->add(lang('Admin.special_website.SpecialWebsiteSettings'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/special-website-settings');
        $pages = $this->pageModel->getPagesStructure($this->id_lang);
        $breadcrumbs = $this->breadcrumb->render();
        echo view('admin/special/form', array('links' => $links, 'specials' => $specials, 'direct' => $direct, 'pages' => $pages, 'breadcrumbs' => $breadcrumbs, 'flashdata' => $flashdata));
    }

    public function ajaxAdministrators($action = '', $id = 0, $id2 = 0) {
        helper(['form']);
        $adminModel = new AdminModel();
        $administrator = array();
        $result = array();
        $session = session();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.administration.Administration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administration');
        $this->breadcrumb->add(lang('Admin.administration.Administrators'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administrators');
        $breadcrumbs = $this->breadcrumb->render();
        $resultAjax = array();
        switch ($action) {
            case 'active':
                $result = $adminModel->ActiveAdministrator($id);
                $administrators = $adminModel->getAdministratorsList();
                $resultAjax['form'] = view('admin/administration/administrators', array('administrators' => $administrators, 'breadcrumbs' => $breadcrumbs));
                $resultAjax['response'] = $result['response'];
                break;
            case 'delete':
                $result = $adminModel->DeleteAdministrator($id);
                $administrators = $adminModel->getAdministratorsList();
                $resultAjax['form'] = view('admin/administration/administrators', array('administrators' => $administrators, 'breadcrumbs' => $breadcrumbs));
                $resultAjax['response'] = $result['response'];
                break;
            case 'save':
            case 'add':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = $adminModel->addAdministrator($post);
                    if (!isset($result['response'])) {
                        $administrator = $post;
                    } elseif (isset($result['response'])) {
                        if (isset($result['response']['msg'])) {
                            $session->setFlashdata('response', $result['response']['msg']);
                        }
                        if (isset($result['response']['msg-error'])) {
                            $session->setFlashdata('response-error', $result['response']['error']);
                        }
                        $resultAjax['response'] = $result['response'];
                    }
                }
                $resultAjax['form'] = view('admin/administration/administrator', array('administrator' => $administrator, 'result' => $result, 'breadcrumbs' => $breadcrumbs, 'locale' => $this->locale));
                break;
        }
        echo json_encode($resultAjax);
    }
    
    private function getMenuParentsFromElements($elements) {
        $parents = array();
        if(!empty($elements)) {
            foreach($elements as $el) {
                $link = array(
                    'id' => $el['id'],
                    'name' => $el['name'],
                    'level' => $el['level'],
                    'id_parent' => $el['id_parent'],
                );
                if(!empty($el['list'])) {
                    $link['list'] = $this->getMenuParentsFromElements($el['list']);
                }
                $parents[] = $link;
            }
        }
        return $parents;
    }

    public function menu($action = '', $id = 0) {
        $moduleModel = new ModuleModel();
        $menuModel = new MenuModel();
        $menuModel->languages = $this->languages;
        $menu = array();
        $parents = array();

        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.menu.MenuList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/menu');

        switch ($action) {
            case 'edit':
                $menu = $menuModel->getMenuById($id, $this->id_lang);
                if(!empty($menu['element'])) {
                    $parents = $this->getMenuParentsFromElements($menu['element']);
                }
            case 'add':
            case 'save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    foreach ($post['lang'] as $id_lang => $lang) {
                        $validation->reset();
                        $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                        $validation->setRules([
                            'name' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Admin.menu.NameError')
                                ],
                            ],
                        ]);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if (!empty($post['element'])) {
                        foreach ($post['element'] as $no => $el) {
                            if (!empty($el['lang'])) {
                                foreach ($el['lang'] as $id_lang => $lang) {
                                    $validation->reset();
                                    $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                                    $validation->setRules([
                                        'name' => [
                                            'rules' => 'required',
                                            'errors' => [
                                                'required' => $lang_name . lang('Admin.menu.ElementNameError', [$no])
                                            ],
                                        ],
                                        'name' => [
                                            'rules' => 'required',
                                            'errors' => [
                                                'required' => $lang_name . lang('Admin.menu.ElementUrlError', [$no])
                                            ],
                                        ]
                                    ]);
                                    if (!$validation->run($lang)) {
                                        $errors[] = array_merge($validation->getErrors());
                                    }
                                }
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $menuModel->saveMenu($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('menu', array(
                            'status' => true,
                            'msg' => ($id ? lang('Admin.menu.EditSuccess') : lang('Admin.menu.AddSuccess')) . '!'
                        ));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/menu/edit/' . $menuModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Admin.menu.EditError') : lang('Admin.menu.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $menu = $post;
                    $menu['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('menu');
                }

                if ($id) {
                    $this->breadcrumb->add(lang('Admin.menu.Menu') . (!empty($menu['name']) ? ': ' . $menu['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/menu/edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Admin.menu.Menu'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/menu/add');
                }
                $breadcrumbs = $this->breadcrumb->render();
                $available_elements = array(
                    'pages' => $this->pageModel->getPagesStructure($this->id_lang),
                );
                if (is_dir(ROOTPATH . 'modules/News') && class_exists('\Modules\News\Controllers\NewsAdmin') && class_exists('\Modules\News\Models\NewsModel') && !empty($moduleModel->select('id')->where('slug', 'News')->where('publish', 1)->first())) {
                    $newsModel = new \Modules\News\Models\NewsModel();
                    $available_elements['news'] = $newsModel->getNewsList($this->id_lang);
                }
                if (is_dir(ROOTPATH . 'modules/Gallery') && class_exists('\Modules\Gallery\Controllers\GalleryAdmin') && class_exists('\Modules\Gallery\Models\GalleryModel') && !empty($moduleModel->select('id')->where('slug', 'Gallery')->where('publish', 1)->first())) {
                    $galleryModel = new \Modules\Gallery\Models\GalleryModel();
                    $available_elements['gallery'] = $galleryModel->getGalleryList($this->id_lang);
                }
                if (is_dir(ROOTPATH . 'modules/Event') && class_exists('\Modules\Event\Controllers\EventAdmin') && !empty($moduleModel->select('id')->where('slug', 'Event')->where('publish', 1)->first())) {
                    if(class_exists('\Modules\Event\Models\EventTypeModel')) {                    
                        $eventTypeModel = new \Modules\Event\Models\EventTypeModel();
                        $available_elements['event_type'] = $eventTypeModel->getTypesForList($this->id_lang);
                    }
                    if(class_exists('\Modules\Event\Models\EventPlaceTypeModel')) {
                        $eventPlaceTypeModel = new \Modules\Event\Models\EventPlaceTypeModel();
                        $available_elements['place_type'] = $eventPlaceTypeModel->getTypesStructure($this->id_lang);
                    }
                }
				 if (is_dir(ROOTPATH . 'modules/Flavors') && class_exists('\Modules\Flavors\Controllers\FlavorsAdmin') && class_exists('\Modules\Flavors\Models\FlavorsCategoryModel') && !empty($moduleModel->select('id')->where('slug', 'Flavors')->where('publish', 1)->first())) {
                    $flavorModel = new \Modules\Flavors\Models\FlavorsCategoryModel();
                    $available_elements['flavors'] = $flavorModel->getCategoryStructure($this->id_lang);
                }
				
				
				
				
				$this->assetsClass->addAssets(array(
                    'css_footer' => array(
                        '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css',
                        '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css'
                    ),
                    'js' => array(
                        '/adm/third-party/jquery-file-upload-master/js/tmpl.min.js',
                        '/adm/third-party/jquery-file-upload-master/js/load-image.all.min.js',
                        '/adm/third-party/jquery-file-upload-master/js/canvas-to-blob.min.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.blueimp-gallery.min.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.iframe-transport.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-process.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-image.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-audio.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-video.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-validate.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-ui.js',
                        '/adm/js/file-uploader.js',
                        '/adm/js/page.js'
                    )
                ));
                $this->assetsClass->addJs('/adm/js/menu.js');
                echo view('admin/menu/add', array('action' => $action, 'menu' => $menu, 'parents' => $parents, 'available_elements' => $available_elements, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumbs));
                break;
            default :
                $get = $this->request->getGet();
                $breadcrumbs = $this->breadcrumb->render();
                $menus = $menuModel->getMenuList($this->id_lang, !empty($get) ? $get : array());
                $order_list = array(
                    array('field' => '', 'name' => lang('Admin.menu.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Admin.menu.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Admin.menu.sort.NameDesc')),
                );
                echo view('admin/menu/list', array('menus' => $menus, 'breadcrumbs' => $breadcrumbs, 'filters' => $get, 'order_list' => $order_list, 'pager' => $menuModel->pager));
                break;
        }
    }
    
    private function ajaxMenu($action = '', $id = 0, $id_content = 0) {
        $menuModel = new MenuModel();
        $response = array(
            'status' => false
        );
        switch ($action) {
            case 'publish': 
                $menu = $menuModel->select('id,publish')->where('id', $id)->first();
                if(!empty($menu)) {
                    $r = $menuModel->where('id', $id)->set('publish', $menu['publish'] ? 0 : 1)->update();
                    $response = array(
                        'status' => $r,
                        'publish' => $menu['publish'] ? 0 : 1,
                        'msg' => $menu['publish'] ? lang('Admin.menu.Republished') : lang('Admin.menu.Published')
                    );
                    HistoryStat($id,'','event','Event',$menu['publish'] ? lang('Admin.menu.Republished') : lang('Admin.menu.Published'));
                } else {
                    $response = array(
                        'status' => true,
                        'publish' => $menu['publish'],
                        'msg' => lang('Admin.menu.Error')
                    );
                }
                return $this->response->setJSON($response);
                break;
            case 'add-elements':
                $post = $this->request->getPost();
                $html = '';
                if (!empty($post) && !empty($post['elements'])) {
                    foreach ($post['elements'] as $m => $p) {
                        switch ($m) {
                            case 'event_type':
                                if (!empty($p)) {
                                    foreach ($p as $id_type) {
                                        $no = uniqid('n');
                                        $eventModel = new \Modules\Event\Models\EventModel();
                                        $menu_item = $eventModel->getEventTypeForMenu($id_type, $this->id_lang, $this->languages);
                                        if (!empty($menu_item)) {
                                            $menu_item['type'] = 'event_type';
                                            $menu_item['order'] = $menuModel->db->table('menu_item')->where('id_menu', $id)->where('id_parent', 0)->countAllResults();
                                            $menu_item['id'] = $menuModel->addMenuElement($id, $menu_item);
                                            if($menu_item['id']) {
                                                $html .= view('admin/menu/menu_items', array('languages' => $this->languages, 'menu_item' => $menu_item, 'locale' => $this->locale,'no' => $menu_item['order'] + 1));
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'place_type':
                                if (!empty($p)) {
                                    foreach ($p as $id_type) {
                                        $no = uniqid('n');
                                        $eventModel = new \Modules\Event\Models\EventModel();
                                        $menu_item = $eventModel->getPlaceTypeForMenu($id_type, $this->id_lang, $this->languages);
                                        if (!empty($menu_item)) {
                                            $menu_item['type'] = 'place_type';
                                            $menu_item['order'] = $menuModel->db->table('menu_item')->where('id_menu', $id)->where('id_parent', 0)->countAllResults();
                                            $menu_item['id'] = $menuModel->addMenuElement($id, $menu_item);
                                            if($menu_item['id']) {
                                                $html .= view('admin/menu/menu_items', array('languages' => $this->languages, 'menu_item' => $menu_item, 'locale' => $this->locale,'no' => $menu_item['order'] + 1));
                                            }
                                        }
                                    }
                                }
                                break;
							case 'flavors':
								if (!empty($p)) {
                                    foreach ($p as $id_type) {
                                        $no = uniqid('n');
                                        $flavorsCatModel = new \Modules\Flavors\Models\FlavorsCategoryModel();
                                        $menu_item = $flavorsCatModel->getCatForMenu($id_type, $this->id_lang, $this->languages);
                                        if (!empty($menu_item)) {
                                            $menu_item['type'] = 'flavors';
                                            $menu_item['order'] = $menuModel->db->table('menu_item')->where('id_menu', $id)->where('id_parent', 0)->countAllResults();
                                            $menu_item['id'] = $menuModel->addMenuElement($id, $menu_item);
                                            if($menu_item['id']) {
                                                $html .= view('admin/menu/menu_items', array('languages' => $this->languages, 'menu_item' => $menu_item, 'locale' => $this->locale,'no' => $menu_item['order'] + 1));
                                            }
                                        }
                                    }
                                }
                            break;							
                            case 'gallery':
                                if (!empty($p)) {
                                    foreach ($p as $id_gallery) {
                                        $no = uniqid('n');
                                        $galleryModel = new \Modules\Gallery\Models\GalleryModel();
                                        $menu_item = $galleryModel->getGalleryForMenu($id_gallery, $this->id_lang, $this->languages);
                                        if (!empty($menu_item)) {
                                            $menu_item['type'] = 'gallery';
                                            $menu_item['order'] = $menuModel->db->table('menu_item')->where('id_menu', $id)->where('id_parent', 0)->countAllResults();
                                            $menu_item['id'] = $menuModel->addMenuElement($id, $menu_item);
                                            if($menu_item['id']) {
                                                $html .= view('admin/menu/menu_items', array('languages' => $this->languages, 'menu_item' => $menu_item, 'locale' => $this->locale,'no' => $menu_item['order'] + 1));
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'news':
                                if (!empty($p)) {
                                    foreach ($p as $id_news) {
                                        $no = uniqid('n');
                                        $newsModel = new \Modules\News\Models\NewsModel();
                                        $menu_item = $newsModel->getNewsForMenu($id_news, $this->id_lang, $this->languages);
                                        if (!empty($menu_item)) {
                                            $menu_item['type'] = 'news';
                                            $menu_item['order'] = $menuModel->db->table('menu_item')->where('id_menu', $id)->where('id_parent', 0)->countAllResults();
                                            $menu_item['id'] = $menuModel->addMenuElement($id, $menu_item);
                                            if($menu_item['id']) {
                                                $html .= view('admin/menu/menu_items', array('languages' => $this->languages, 'menu_item' => $menu_item,  'locale' => $this->locale,'no' => $menu_item['order'] + 1));
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'pages':
                                if (!empty($p)) {
                                    foreach ($p as $id_page) {
                                        $menu_item = $this->pageModel->getPageForMenu($id_page, $this->id_lang, $this->languages);
                                        if (!empty($menu_item)) {
                                            $menu_item['type'] = 'page';
                                            $menu_item['order'] = $menuModel->db->table('menu_item')->where('id_menu', $id)->where('id_parent', 0)->countAllResults();
                                            $menu_item['id'] = $menuModel->addMenuElement($id, $menu_item);
                                            if($menu_item['id']) {
                                                $html .= view('admin/menu/menu_items', array('languages' => $this->languages, 'menu_item' => $menu_item, 'locale' => $this->locale, 'no' => $menu_item['order'] + 1));
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'own':
                                $no = uniqid('n');
                                $menu_item = array(
                                    'type' => 'own',
                                    'lang' => array()
                                );
                                if(!empty($this->languages)) {
                                    foreach($this->languages as $id_lang=>$lang) {
                                        $menu_item['lang'][$id_lang] = array(
                                            'name' => '',
                                            'url' => '',
                                            'title' => '',
                                        );
                                    }
                                }
                                $menu_item['order'] = $menuModel->db->table('menu_item')->where('id_menu', $id)->where('id_parent', 0)->countAllResults();
                                $menu_item['id'] = $menuModel->addMenuElement($id, $menu_item);
                                if($menu_item['id']) {
                                    $html .= view('admin/menu/menu_items', array('languages' => $this->languages, 'menu_item' => $menu_item, 'locale' => $this->locale, 'no' => $menu_item['order'] + 1));
                                }
                                break;
                        }
                    }
                }
                $response = array(
                    'status' => true,
                    'html' => base64_encode(urlencode($html))
                );
                break;
            case 'delete':
                $result = $menuModel->deleteMenu($id);
                return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Admin.menu.Removed') : lang('Admin.menu.Error')
                ));
                break;
        }
        return $this->response->setJSON($response);
    }

    public function page($action = '', $id = 0, $id_content = 0) {
        $pageContentModel = new PageContentModel();
        $sidebarModel = new SidebarModel();
        $menuModel = new MenuModel();
        $page_content = array();
        $flashdata = array();
        $module_data = array();
        $page = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('wstecz', '');
        $this->breadcrumb->add('<i class="fa-solid fa-house"></i>', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.page.PagesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page');
        
        $page_info = $this->pageModel->getPageInfo($id, $this->id_lang);
        switch ($action) {
            case 'content':
                $post = $this->request->getPost();
                $content_elements = $pageContentModel->getPageContentElements($id, $this->id_lang);
                if (empty($id_content) && !empty($content_elements)) {
                    $id_content = $content_elements[array_key_first($content_elements)]['id'];
                }
                $page_content = $pageContentModel->getPageContentById($id_content);
                
                if (!empty($id_content) && !empty($content_elements) && !empty($content_elements[$id_content])) {
                    $class = '\App\Controllers\\' . ucfirst($content_elements[$id_content]['slug']);
                    if (!class_exists($class)) {
                        $class = '\Modules\\' . ucfirst($content_elements[$id_content]['slug']) . '\Controllers\\' . ucfirst($content_elements[$id_content]['slug']) . 'Admin';
                    }
                    if (class_exists($class)) {
                        $module = new $class();
                        $module->id_lang = $this->id_lang;
                        $module->locale = $this->locale;
                        $module->languages = $this->languages;
                        if(method_exists($module, 'assets')) {
                            $assets = $module->assets($action);
                            $this->assetsClass->addAssets($assets);
                        }
                        if (method_exists($module, 'pageContent')) {
                            $module_data = $module->pageContent($id_content, !empty($page_content['slug']) ? $page_content['slug'] : '');
                        }
                    }
                }
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    $validation_rules = [];
                    if (isset($post['template'])) {
                        $validation_rules['template'] = [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Admin.page_content.ElementError')
                            ],
                        ];
                    }
                    if (isset($post['id_element'])) {
                        $validation_rules['id_element'] = [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Admin.page_content.ElementError')
                            ],
                        ];
                    }
                    if (!empty($validation_rules)) {
                        $validation->setRules($validation_rules);
                        if (!$validation->run($post)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if (empty($errors)) {
                        $pageContentModel->db->transStart();
                        $pageContentModel->savePageContent($id_content, $post);
                        if (is_object($module) && method_exists($module, 'savePageContent') && !empty($post['form_data'])) {
                            $module->savePageContent($id_content, $post['form_data']);
                        }
                        $pageContentModel->db->transComplete();
                        $result = $pageContentModel->db->transStatus();
                    }
                    if ($result) {
                        $this->session->setFlashdata('page', array(
                            'status' => true,
                            'msg' => lang('Admin.page_content.EditSuccess') . '!'
                        ));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $id . '/' . $id_content);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => lang('Admin.page_content.EditError') . '!',
                            'list' => $errors
                        );
                    }
                    $page_content = $post;
                    if (!empty($post['form_data'])) {
                        $module_data['form_data'] = $post['form_data'];
                    }
                } else {
                    $flashdata = $this->session->getFlashdata('page');
                }

                $this->breadcrumb->add(lang('Admin.page.PageContent') . ': ' . $page_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $id . '/' . $id_content);
                $sidebars = $sidebarModel->getSidebarsForSelect($this->id_lang);
                $breadcrumbs = $this->breadcrumb->render();
                echo view('admin/page/content', array('action' => $action, 'id_page' => $id, 'id_content' => $id_content, 'page_content' => $page_content, 'module_data' => $module_data, 'content_elements' => $content_elements, 'sidebars' => $sidebars, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumbs, 'page_info' => $page_info));
                break;
            case 'configuration':
                $page = $this->pageModel->getPageById($id);
                $page['name'] = $page_info['name'];
            case 'add':
            case 'save':
                helper('filesystem');
                $moduleModel = new ModuleModel();
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    foreach ($post['lang'] as $id_lang => $lang) {
                        $validation->reset();
                        $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                        $validation_rules = [
                            'name' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Admin.page.NameError')
                                ],
                            ]
                        ];
                        if (empty($id) || $id != 1) {
                            $validation_rules['link'] = [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Admin.page.DirectLinkError')
                                ],
                            ];
                        }
                        $validation->setRules($validation_rules);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if (!empty($post['content'])) {
                        foreach ($post['content'] as $element) {
                            // Pomijamy pusty wiersz startowy (strona bez bloków treści renderowana z szablonu).
                            if (empty($element['id_element']) && empty($element['id'])) {
                                continue;
                            }
                            $validation->reset();
                            $validation->setRules([
                                'id_element' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => lang('Admin.page.AssignedModuleError')
                                    ],
                                ],
                            ]);
                            if (!$validation->run($element)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->pageModel->savePage($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('page', array(
                            'status' => true,
                            'msg' => ($id ? lang('Admin.page.EditSuccess') : lang('Admin.page.AddSuccess')) . '!'
                        ));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/configuration/' . $this->pageModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Admin.page.EditError') : lang('Admin.page.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $page = $post;
                    $page['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('page');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Admin.page.PageConfiguration') . ': ' . $page_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/configuration/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Admin.page.PageConfiguration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') .'/page/add');
                }
                $breadcrumbs = $this->breadcrumb->render();
                $pages = $this->pageModel->getPagesStructure($this->id_lang, 0, array($id));
                $module_elements = $moduleModel->getModuleElements($this->id_lang);
                //$menu_list = $menuModel->getFullMenuList($this->id_lang);
                $sidebars = $sidebarModel->getSidebarsForSelect($this->id_lang);
                $templates = get_templates_by_dir('app/Views/user/page');
                $this->assetsClass->addAssets(array(
                    'css_footer' => array(
                        '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css',
                        '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css'
                    ),
                    'js' => array(
                        '/adm/third-party/jquery-file-upload-master/js/tmpl.min.js',
                        '/adm/third-party/jquery-file-upload-master/js/load-image.all.min.js',
                        '/adm/third-party/jquery-file-upload-master/js/canvas-to-blob.min.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.blueimp-gallery.min.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.iframe-transport.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-process.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-image.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-audio.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-video.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-validate.js',
                        '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-ui.js',
                        '/adm/js/file-uploader.js',
                        '/adm/js/page.js'
                    )
                ));
                echo view('admin/page/add', array('action' => $action, 'page' => $page, 'pages' => $pages, 'module_elements' => $module_elements, 'sidebars' => $sidebars, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumbs, 'templates' => $templates));
                break;
            default :
                $breadcrumbs = $this->breadcrumb->render();
                $pages = $this->pageModel->getPagesStructure($this->id_lang);
                echo view('admin/page/list', array('pages' => $pages, 'breadcrumbs' => $breadcrumbs));
                break;
        }
    }

    private function ajaxPage($action = '', $id = 0, $id_content = 0) {
        $response = array(
            'status' => false
        );
        switch ($action) {
            case 'content':
                $post = $this->request->getPost();
                $pageContentModel = new PageContentModel();
                if(!isset($post['name'])) {
                    $post['name'] = '';
                }
                $pageContentModel->db->transStart();
                $lang = $pageContentModel->db->table('page_content_lang')->select('id')->where('id_page_cont', $id_content)->where('id_lang', $this->id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $pageContentModel->db->table('page_content_lang')->set('name', $post['name'])->where('id_page_cont', $id_content)->where('id_lang', $this->id_lang)->update();
                } else {
                    $pageContentModel->db->table('page_content_lang')->insert(array(
                        'id_page_cont' => $id_content,
                        'id_lang' => $this->id_lang,
                        'title' => '',
                        'subtitle' => '',
                        'name' => $post['name']
                    ));
                }
                
                $pageContentModel->db->transComplete();
		$result = $pageContentModel->db->transStatus();
                $response = array(
                    'status' => $result
                );
                break;
            case 'delete-module-el':
                $moduleModel = new ModuleModel();
                $post = $this->request->getPost();
                if(!empty($id)) {
                    $data = $moduleModel->join('module_element me', 'me.id_module=module.id')->join('module_element_lang mel', 'me.id=mel.id_module_el')->join('page_content pc', 'pc.id_module_element=me.id')->select('module.slug as module,mel.name,pc.id,pc.id_element,me.slug')->where('pc.id', $id)->where('module.publish', 1)->where('mel.id_lang', $this->id_lang)->first();
                    if(!empty($post) && $post['action'] == 'delete') {
                        $result = false;
                        $msg = '';
						$is_empty = true;
						$moduleModel->db->transStart();
						if(is_dir(ROOTPATH . 'modules/' . ucfirst($data['module'])) && class_exists('\Modules\\' . ucfirst($data['module']) . '\Controllers\\' . ucfirst($data['module']) . 'Admin')) {
							$class = '\Modules\\' . ucfirst($data['module']) . '\Controllers\\' . ucfirst($data['module']) . 'Admin';
							$module = new $class();
							$module->id_lang = $this->id_lang;
							$module->locale = $this->locale;
							$module->languages = $this->languages;
							if (method_exists($module, 'preDeletePageModule')) {
								$is_empty = $module->preDeletePageModule($data);
							}
							if ($is_empty && method_exists($module, 'deletePageModule')) {
								$module->deletePageModule($data);
							}
						}
						if ($is_empty) {
							$moduleModel->db->table('page_content')->where('id', $data['id'])->delete();
							$moduleModel->db->table('page_content_lang')->where('id_page_cont', $data['id'])->delete();
						}
						$moduleModel->db->transComplete();
						$result = $moduleModel->db->transStatus();
						if(!$is_empty) {
							$msg = lang('Admin.page.DeleteModuleConfirmError') . '.<br />' . lang('Admin.page.DeleteModuleErrorConfirmInfo') . '<br /><br /><i>' . lang('Admin.page.DeleteModuleTip') . '</i>';
						} else {
							$msg = $result ? lang('Admin.page.DeleteModuleSuccess') : lang('Admin.page.DeleteModuleError') . '<br /><br /><i>' . lang('Admin.page.DeleteModuleTip') . '</i>';
						}
						
						if($result) {
							$msg = $msg ? $msg : lang('Admin.page.DeleteModuleSuccess');
						} else {
							$msg = lang('Admin.page.DeleteModuleError') . '.<br /><br /><i>' . lang('Admin.page.DeleteModuleTip') . '</i>';
						}
                        $response = array(
                                'message' => $msg,
                                'status' => $result
                            );
                    } else {
                        if($data['id_element']) {
                            $response = array(
                                'message' => lang('Admin.page.DeleteModuleConfirm') . ' <b>' . $data['name'] . '</b> ' . lang('Admin.page.DeleteModuleConfirmWith') . '?<br /><br /><i>' . lang('Admin.page.DeleteModuleTip') . '</i>',
                                'status' => true
                            );
                        } else {
                            $result = false;
                            if(is_dir(ROOTPATH . 'modules/' . ucfirst($data['module'])) && class_exists('\Modules\\' . ucfirst($data['module']) . '\Controllers\\' . ucfirst($data['module']) . 'Admin')) {
                                $class = '\Modules\\' . ucfirst($data['module']) . '\Controllers\\' . ucfirst($data['module']) . 'Admin';
                                $module = new $class();
                                $module->id_lang = $this->id_lang;
                                $module->locale = $this->locale;
                                $module->languages = $this->languages;
                                if (method_exists($module, 'preDeletePageModule')) {
                                    $result = $module->preDeletePageModule($data);
                                } else {
                                    $result = true;
                                }
                            }
                            if($result) {
                                $response = array(
                                    'message' => lang('Admin.page.DeleteModuleConfirm') . ' <b>' . $data['name'] . '</b> ' . lang('Admin.page.DeleteModuleConfirmWith') . '?<br /><br /><i>' . lang('Admin.page.DeleteModuleTip') . '</i>',
                                    'status' => true
                                );
                            } else {
                                $response = array(
                                    'message' => lang('Admin.page.DeleteModuleConfirmError') . '<br />' . lang('Admin.page.DeleteModuleConfirmErrorInfo') . '<br /><br /><i>' . lang('Admin.page.DeleteModuleTip') . '</i>',
                                    'status' => false
                                );
                            }
                        }
                    }
                } else {
					if(!empty($post) && $post['action'] == 'delete') {
						$response = array(
							'message' => lang('Admin.page.DeleteModuleSuccess'),
							'status' => true
						);
					} else {
						$response = array(
							'message' => lang('Admin.page.DeleteModuleConfirm'),
							'status' => true
						);
					}
                }
                break;
            case 'add-module-el':
                $moduleModel = new ModuleModel();
                $post = $this->request->getPost();
                $module_elements = $moduleModel->getModuleElements($this->id_lang);
                $html = view('admin/page/add_module_el', array('module_elements' => $module_elements, 'no' => $post['no'], 'locale' => $this->locale));
                $response = array(
                    'status' => true,
                    'html' => base64_encode(urlencode($html))
                );
                break;
            case 'publish':
                $page = $this->pageModel->select('id,publish')->where('id', $id)->first();
                if (!empty($page)) {
                    $r = $this->pageModel->where('id', $id)->set('publish', $page['publish'] ? 0 : 1)->update();
                    $response = array(
                        'status' => $r,
                        'publish' => $page['publish'] ? 0 : 1,
                        'msg' => $page['publish'] ? lang('Admin.page.Republished') : lang('Admin.page.Published')
                    );
                    HistoryStat($id, '', 'page', 'Page', $page['publish'] ? lang('Admin.page.Republished') : lang('Admin.page.Published'));
                } else {
                    $response = array(
                        'status' => true,
                        'publish' => $page['publish'],
                        'msg' => lang('Admin.page.Error')
                    );
                }
                break;
            case 'delete':
                $page = $this->pageModel->select('id,re_id')->where('id', $id)->first();
                if (!empty($page)) {
					$count = $this->pageModel->db->table('page_content')->where('id_page', $page['id'])->countAllResults();
					if(!empty($count)) {
						$response = array(
							'alert' => true,
							'status' => true,
							'result' => false,
							'msg' => lang('Admin.page.RemoveFirstContent')
						);
					} else {
						$r = $this->pageModel->deletePage($id, $page['re_id']);
						$response = array(
							'status' => true,
							'result' => $r,
							'msg' => $r ? lang('Admin.page.Removed') : lang('Admin.page.Error')
						);
					}
                } else {
                    $response = array(
                        'status' => true,
                        'publish' => $page['publish'],
                        'msg' => lang('Admin.page.PageNotFound')
                    );
                }
                break;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxLinks($action = '', $id = 0, $id_content = 0) {
        $response = array(
            'status' => false
        );
        $post = $this->request->getPost();
        $linkClass = new Link();
        switch ($action) {
            case 'check-all':
                $lang_links = array();
                if (!empty($post['lang_links'])) {
                    foreach ($post['lang_links'] as $l) {
                        $link = $linkClass->generateLink($l['name'], $l['id_lang'], $l['id_link'], $post['id_page'], !empty($post['module']) ? $post['module'] : '', !empty($l['direct_link']) ? $l['direct_link'] : '');
                        $error = '';
                        if(!empty($linkClass->url_conflict)) {
                            $error = str_replace('{url}', '/' . $linkClass->url_conflict, lang('Admin.links.DirectLinkConflict'));
                        } elseif(!empty($linkClass->redirect_conflict)) {
                            $error = str_replace('{url}', '/' . $linkClass->redirect_conflict, lang('Redirects.RedirectConflict'));
                        }
                        $lang_links[] = array(
                            'id_lang' => $l['id_lang'],
                            'link' => $link,
                            'error' => $error
                        );
                    }
                }
                $response = array(
                    'status' => true,
                    'lang_links' => $lang_links
                );
                break;
            case 'check':
                if(!empty($post['link'])) {
                    $link = $linkClass->checkLink($post['link'], $post['id_lang'], $id);
                } else {
                    $link = $linkClass->generateLink($post['name'], $post['id_lang'], $id, !empty($post['id_page']) ? $post['id_page'] : 0, !empty($post['module']) ? $post['module'] : '', !empty($post['direct_link']) ? $post['direct_link'] : '');
                }
                $error = '';
                if(!empty($linkClass->url_conflict)) {
                    $error = str_replace('{url}', '/' . $linkClass->url_conflict, lang('Admin.links.DirectLinkConflict'));
                } elseif(!empty($linkClass->redirect_conflict)) {
                    $error = str_replace('{url}', '/' . $linkClass->redirect_conflict, lang('Redirects.RedirectConflict'));
                }
                $response = array(
                    'status' => true,
                    'link' => $link,
                    'error' => $error
                );
                break;
        }
        return $this->response->setJSON($response);
    }

    public function accessdenied() {
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $breadcrumbs = $this->breadcrumb->render();
        $this->access_denied = true;
        echo view('admin/access_denied', array('breadcrumbs' => $breadcrumbs));
    }

    public function CheckAccess() {

        $this->pageModel = new PageModel();
        if (isset($_SESSION['role']) and $_SESSION['role'] != 'super_admin') {
            $this->locale = $this->request->getLocale() == $this->request->getDefaultLocale() ? '' : $this->request->getLocale();
            if (isset($this->locale) and $this->locale != '') {
                $check_url = str_replace('/' . $this->locale . '/' . env('ADMIN_PANEL_SLUG') . '/', '', uri_string());
            } else {
                $check_url = str_replace('/' . env('ADMIN_PANEL_SLUG') . '/', '', uri_string());
            }
            if (isset($check_url)) {
                $tab_check = explode('/', $check_url);
                if (isset($tab_check)) {
                    $check_query = $this->pageModel->db->table('admin_blocksecure')->groupStart()->Where('active', 1)->Where('role', $_SESSION['role'])->groupStart()->Where('address', '/' . $check_url)->orWhere('address', '/' . $tab_check[0] . '*')->groupEnd()->groupEnd()->get()->getRowArray();
                    if (isset($check_query)) {
                        header("Location: " . ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/accessdenied');
                        exit;
                    }
                }
            }
        }
    }
    
    public function mainMenu($action = '', $id = 0, $id_content = 0) {
        $moduleModel = new ModuleModel();
        $moduleModel->locale = $this->locale;
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.administration.Administration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administration');
        $this->breadcrumb->add(lang('Admin.administration.MainMenu'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/main-menu');
        $breadcrumbs = $this->breadcrumb->render();
        switch ($action) {
            default :
                $modules = $moduleModel->getModulesForMainMenu($this->id_lang);
                echo view('admin/main_menu/list', array('modules' => $modules, 'breadcrumbs' => $breadcrumbs));
                break;
        }
    }
    
    public function ajaxMainMenu($action = '', $id = 0, $id_content = 0) {
        $moduleModel = new ModuleModel();
        $module = $moduleModel->select('id,main')->where('id', $id)->first();
        if(!empty($module)) {
            $r = $moduleModel->where('id', $id)->set('main', $module['main'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $module['main'] ? 0 : 1,
                'msg' => $module['main'] ? lang('Admin.TurnedOff') : lang('Admin.TurnedOn')
            );
        } else {
            $response = array(
                'status' => true,
                'publish' => $module['main'],
                'msg' => lang('Admin.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    public function sidebar($action = '', $id = 0, $id_content = 0) {
        $sidebarModel = new SidebarModel();
        $moduleModel = new ModuleModel();
        $sidebarModel->locale = $this->locale;
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.administration.Administration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administration');
        $this->breadcrumb->add(lang('Admin.administration.Sidebars'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/sidebar');
        $sidebar = array();
        $module_data = array();
        $sidebar_content = array();
        $sidebar_info = $sidebarModel->getSidebarInfo($id, $this->id_lang);
        switch ($action) {
            case 'content':
                $post = $this->request->getPost();
                $content_elements = $sidebarModel->getSidebarContentElements($id, $this->id_lang);
                if (empty($id_content) && !empty($content_elements)) {
                    $id_content = $content_elements[array_key_first($content_elements)]['id'];
                }
                $sidebar_content = $sidebarModel->getSidebarContentById($id_content);
                
                if (!empty($id_content) && !empty($content_elements) && !empty($content_elements[$id_content])) {
                    $class = '\App\Controllers\\' . ucfirst($content_elements[$id_content]['slug']);
                    if (!class_exists($class)) {
                        $class = '\Modules\\' . ucfirst($content_elements[$id_content]['slug']) . '\Controllers\\' . ucfirst($content_elements[$id_content]['slug']) . 'Admin';
                    }
                    if (class_exists($class)) {
                        $module = new $class();
                        $module->id_lang = $this->id_lang;
                        $module->locale = $this->locale;
                        $module->languages = $this->languages;
                        $module->sidebar = true;
                        if(method_exists($module, 'assets')) {
                            $assets = $module->assets($action);
                            $this->assetsClass->addAssets($assets);
                        }
                        if (method_exists($module, 'pageContent')) {
                            $module_data = $module->pageContent($id_content, !empty($sidebar_content['slug']) ? $sidebar_content['slug'] : '');
                        }
                    }
                }
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    $validation_rules = [];
                    if (isset($post['template'])) {
                        $validation_rules['template'] = [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Admin.page_content.ElementError')
                            ],
                        ];
                    }
                    if (isset($post['id_element'])) {
                        $validation_rules['id_element'] = [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Admin.page_content.ElementError')
                            ],
                        ];
                    }
                    if (!empty($validation_rules)) {
                        $validation->setRules($validation_rules);
                        if (!$validation->run($post)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if (empty($errors)) {
                        $sidebarModel->db->transStart();
                        $sidebarModel->saveSidebarContentPost($id_content, $post);
                        $sidebarModel->db->transComplete();
                        $result = $sidebarModel->db->transStatus();
                    }
                    if ($result) {
                        $this->session->setFlashdata('sidebar', array(
                            'status' => true,
                            'msg' => lang('Admin.sidebar.EditSuccess') . '!'
                        ));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/sidebar/content/' . $id . '/' . $id_content);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => lang('Admin.sidebar.EditError') . '!',
                            'list' => $errors
                        );
                    }
                    $sidebar_content = $post;
                    if (!empty($post['form_data'])) {
                        $module_data['form_data'] = $post['form_data'];
                    }
                } else {
                    $flashdata = $this->session->getFlashdata('sidebar');
                }

                $this->breadcrumb->add(lang('Admin.page.PageContent') . ': ' . $sidebar_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/sidebar/content/' . $id . '/' . $id_content);
                $breadcrumbs = $this->breadcrumb->render();
                echo view('admin/sidebar/content', array('action' => $action, 'id_sidebar' => $id, 'id_content' => $id_content, 'sidebar_content' => $sidebar_content, 'page_content' => $sidebar_content, 'module_data' => $module_data, 'content_elements' => $content_elements, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumbs, 'sidebar_info' => $sidebar_info));
                break;
            case 'edit':
                $sidebar = $sidebarModel->getSidebarById($id, $this->id_lang);
            case 'save':
            case 'add':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    if (!empty($post['lang'])) {
                        foreach ($post['lang'] as $id_lang => $lang) {
                            $validation->reset();
                            $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                            $validation->setRules([
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Admin.sidebar.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $sidebarModel->saveSidebar($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('sidebar', array(
                            'status' => true,
                            'msg' => ($id ? lang('Admin.sidebar.EditSuccess') : lang('Admin.sidebar.AddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','sidebar','Sidebar',$id ? lang('Admin.sidebar.EditSuccess') : lang('Admin.sidebar.AddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/sidebar/edit/' . $sidebarModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Admin.sidebar.EditError') : lang('Admin.sidebar.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $type = $post;
                    $type['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('sidebar');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Admin.sidebar.SidebarEdit') . (!empty($sidebar['name']) ? ': ' . $sidebar['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/sidebar/edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Admin.sidebar.NewSidebarAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/sidebar/add');
                }
                $module_elements = $moduleModel->getModuleElements($this->id_lang, true);
                $templates = get_templates_by_dir('app/Views/user/sidebar');
                $this->assetsClass->addAssets(array(
                    'js' => array(
                        '/adm/js/sidebar.js'
                    )
                ));
                $breadcrumbs = $this->breadcrumb->render();
                echo view('admin/sidebar/add', array('sidebar' => $sidebar, 'action' => $action, 'module_elements' => $module_elements, 'breadcrumbs' => $breadcrumbs, 'flashdata' => $flashdata));
                break;
            default :
                $sidebars = $sidebarModel->join('sidebar_lang sl', 'sidebar.id=sl.id_sidebar')->select('sidebar.id,sidebar.publish,sl.name')->where('sl.id_lang', $this->id_lang)->find();
                $breadcrumbs = $this->breadcrumb->render();
                echo view('admin/sidebar/list', array('sidebars' => $sidebars, 'breadcrumbs' => $breadcrumbs));
                break;
        }
    }
    
    private function ajaxSidebar($action = '', $id = 0, $id_content = 0) {
        $response = array(
            'status' => false
        );
        $sidebarModel = new SidebarModel();
        $moduleModel = new ModuleModel();
        switch ($action) {
            case 'content':
                $post = $this->request->getPost();
                if(!isset($post['name'])) {
                    $post['name'] = '';
                }
                $sidebarModel->db->transStart();
                $lang = $sidebarModel->db->table('sidebar_content_lang')->select('id')->where('id_sidebar_cont', $id_content)->where('id_lang', $this->id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $sidebarModel->db->table('sidebar_content_lang')->set('name', $post['name'])->where('id_sidebar_cont', $id_content)->where('id_lang', $this->id_lang)->update();
                } else {
                    $sidebarModel->db->table('sidebar_content_lang')->insert(array(
                        'id_sidebar_cont' => $id_content,
                        'id_lang' => $this->id_lang,
                        'title' => '',
                        'subtitle' => '',
                        'name' => $post['name']
                    ));
                }
                
                $sidebarModel->db->transComplete();
		$result = $sidebarModel->db->transStatus();
                $response = array(
                    'status' => $result
                );
                break;
            case 'delete-module-el':
                $post = $this->request->getPost();
                if(!empty($id)) {
                    $data = $moduleModel->join('module_element me', 'me.id_module=module.id')->join('module_element_lang mel', 'me.id=mel.id_module_el')->join('sidebar_content sc', 'sc.id_module_element=me.id')->select('module.slug as module,mel.name,sc.id,sc.id_element,me.slug')->where('sc.id', $id)->where('module.publish', 1)->where('mel.id_lang', $this->id_lang)->first();
                    if(!empty($post) && $post['action'] == 'delete') {
                        $result = false;
                        $msg = '';
                        $is_empty = true;
                        $sidebarModel->db->transStart();
                        if(is_dir(ROOTPATH . 'modules/' . ucfirst($data['module'])) && class_exists('\Modules\\' . ucfirst($data['module']) . '\Controllers\\' . ucfirst($data['module']) . 'Admin')) {
                                $class = '\Modules\\' . ucfirst($data['module']) . '\Controllers\\' . ucfirst($data['module']) . 'Admin';
                                $module = new $class();
                                $module->id_lang = $this->id_lang;
                                $module->locale = $this->locale;
                                $module->languages = $this->languages;
                                if (method_exists($module, 'preDeleteSidebarModule')) {
                                        $is_empty = $module->preDeleteSidebarModule($data);
                                }
                                if ($is_empty && method_exists($module, 'deleteSidebarModule')) {
                                        $module->deleteSidebarModule($data);
                                }
                        }
                        if ($is_empty) {
                            $sidebarModel->db->table('sidebar_content')->where('id', $data['id'])->delete();
                            $sidebarModel->db->table('sidebar_content_lang')->where('id_sidebar_cont', $data['id'])->delete();
                        }
                        $sidebarModel->db->transComplete();
                        $result = $sidebarModel->db->transStatus();
                        if(!$is_empty) {
                            $msg = lang('Admin.sidebar.DeleteModuleConfirmError') . '.<br />' . lang('Admin.sidebar.DeleteModuleErrorConfirmInfo') . '<br /><br /><i>' . lang('Admin.sidebar.DeleteModuleTip') . '</i>';
                        } else {
                            $msg = $result ? lang('Admin.sidebar.DeleteModuleSuccess') : lang('Admin.sidebar.DeleteModuleError') . '<br /><br /><i>' . lang('Admin.sidebar.DeleteModuleTip') . '</i>';
                        }

                        if($result) {
                            $msg = $msg ? $msg : lang('Admin.sidebar.DeleteModuleSuccess');
                        } else {
                            $msg = lang('Admin.sidebar.DeleteModuleError') . '.<br /><br /><i>' . lang('Admin.sidebar.DeleteModuleTip') . '</i>';
                        }
                        $response = array(
                            'message' => $msg,
                            'status' => $result
                        );
                    } else {
                        if($data['id_element']) {
                            $response = array(
                                'message' => lang('Admin.sidebar.DeleteModuleConfirm') . ' <b>' . $data['name'] . '</b> ' . lang('Admin.sidebar.DeleteModuleConfirmWith') . '?<br /><br /><i>' . lang('Admin.sidebar.DeleteModuleTip') . '</i>',
                                'status' => true
                            );
                        } else {
                            $result = false;
                            if(is_dir(ROOTPATH . 'modules/' . ucfirst($data['module'])) && class_exists('\Modules\\' . ucfirst($data['module']) . '\Controllers\\' . ucfirst($data['module']) . 'Admin')) {
                                $class = '\Modules\\' . ucfirst($data['module']) . '\Controllers\\' . ucfirst($data['module']) . 'Admin';
                                $module = new $class();
                                $module->id_lang = $this->id_lang;
                                $module->locale = $this->locale;
                                $module->languages = $this->languages;
                                if (method_exists($module, 'preDeleteSidebarModule')) {
                                    $result = $module->preDeleteSidebarModule($data);
                                } else {
                                    $result = true;
                                }
                            }
                            if($result) {
                                $response = array(
                                    'message' => lang('Admin.sidebar.DeleteModuleConfirm') . ' <b>' . $data['name'] . '</b> ' . lang('Admin.sidebar.DeleteModuleConfirmWith') . '?<br /><br /><i>' . lang('Admin.sidebar.DeleteModuleTip') . '</i>',
                                    'status' => true
                                );
                            } else {
                                $response = array(
                                    'message' => lang('Admin.sidebar.DeleteModuleConfirmError') . '<br />' . lang('Admin.sidebar.DeleteModuleConfirmErrorInfo') . '<br /><br /><i>' . lang('Admin.sidebar.DeleteModuleTip') . '</i>',
                                    'status' => false
                                );
                            }
                        }
                    }
                } else {
                    if(!empty($post) && $post['action'] == 'delete') {
                        $response = array(
                            'message' => lang('Admin.sidebar.DeleteModuleSuccess'),
                            'status' => true
                        );
                    } else {
                        $response = array(
                            'message' => lang('Admin.sidebar.DeleteModuleConfirm'),
                            'status' => true
                        );
                    }
                }
                break;
            case 'add-module-el':
                $post = $this->request->getPost();
                $module_elements = $moduleModel->getModuleElements($this->id_lang, true);
                $html = view('admin/sidebar/add_module_el', array('module_elements' => $module_elements, 'no' => $post['no'], 'locale' => $this->locale));
                $response = array(
                    'status' => true,
                    'html' => base64_encode(urlencode($html))
                );
                break;
            case 'publish':
                $sidebar = $sidebarModel->select('id,publish')->where('id', $id)->first();
                if (!empty($sidebar)) {
                    $r = $sidebarModel->where('id', $id)->set('publish', $sidebar['publish'] ? 0 : 1)->update();
                    $response = array(
                        'status' => $r,
                        'publish' => $sidebar['publish'] ? 0 : 1,
                        'msg' => $sidebar['publish'] ? lang('Admin.sidebar.Republished') : lang('Admin.sidebar.Published')
                    );
                    HistoryStat($id, '', 'sidebar', 'Sidebar', $sidebar['publish'] ? lang('Admin.sidebar.Republished') : lang('Admin.sidebar.Published'));
                } else {
                    $response = array(
                        'status' => true,
                        'publish' => $sidebar['publish'],
                        'msg' => lang('Admin.sidebar.Error')
                    );
                }
                break;
            case 'delete':
                $sidebar = $sidebarModel->select('id')->where('id', $id)->first();
                if (!empty($sidebar)) {
                    $count = $sidebarModel->db->table('sidebar_content')->where('id_sidebar', $sidebar['id'])->countAllResults();
                    if(!empty($count)) {
                        $response = array(
                            'alert' => true,
                            'status' => true,
                            'result' => false,
                            'msg' => lang('Admin.sidebar.RemoveFirstContent')
                        );
                    } else {
                        $r = $sidebarModel->deleteSidebar($id);
                        $response = array(
                            'status' => true,
                            'result' => $r,
                            'msg' => $r ? lang('Admin.sidebar.Removed') : lang('Admin.sidebar.Error')
                        );
                    }
                } else {
                    $response = array(
                        'status' => true,
                        'publish' => $sidebar['publish'],
                        'msg' => lang('Admin.sidebar.SidebarNotFound')
                    );
                }
                break;
        }
        return $this->response->setJSON($response);
    }
}
