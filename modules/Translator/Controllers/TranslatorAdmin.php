<?php

namespace Modules\Translator\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Breadcrumb;

class TranslatorAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
    }
    
    public function index($action = '', $id = 0) {
        helper('filesystem');
        $translation = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Translator.Translator'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/translator');
        
        switch ($action) {
            case 'edit':    
            case 'save':
                if(!empty($this->languages[$id])) {
                    $lang_code = $this->languages[$id]['lang_code'];
                    if(file_exists(WRITEPATH . 'Language/' . $lang_code. '/User.php')) {
                        $translation = include(WRITEPATH . 'Language/' . $lang_code. '/User.php');
                        
                        $post = $this->request->getPost();
                        if (!empty($post)) {
                            $result = false;
                            $errors = array();
                            $data = "<?php" . PHP_EOL . "namespace Writable\Language\\" . $lang_code . "; " . PHP_EOL . "return [" . PHP_EOL;
                            foreach($post as $key=>$p) {
                                if(!empty($p) && is_array($p)) {
                                    $data .= "  '" . $key. "' => [" . PHP_EOL;
                                    foreach($p as $k=>$value) {
                                        $data .= "      '" .$k . "' => '" . str_replace("'", "\'", $value) . "'," . PHP_EOL;
                                    }
                                    $data .= "  ]," . PHP_EOL;
                                }
                            }

                            $data .= "];";
                            $result = write_file(WRITEPATH . 'Language/' . $lang_code. '/User.php', $data);
                            if ($result) {
                                $this->session->setFlashdata('translator', array(
                                    'status' => true,
                                    'msg' => lang('Translator.EditSuccess') . '!'
                                ));
                                HistoryStat($id,'','translator','Translator', lang('Translator.EditSuccess'));
                                return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/translator/edit/' . $id);
                            } else {
                                $flashdata = array(
                                    'status' => false,
                                    'msg' => lang('Translator.EditError') . '!',
                                    'list' => $errors
                                );
                            }
                            $translation = $post;
                            $translation['id'] = $id;
                        } else {
                            $flashdata = $this->session->getFlashdata('translator');
                        }
                        $this->breadcrumb->add(lang('Translator.Edit') . (!empty($this->languages[$id]) && !empty($this->languages[$id]['short_name']) ? ' - ' . $this->languages[$id]['short_name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/translator/edit/' . $id);
                        $breadcrumb = $this->breadcrumb->render();
                        echo view('Modules\Translator\Views\admin\form', array('action' => $action, 'translation' => $translation, 'language' => !empty($this->languages[$id]) ? $this->languages[$id] : array(), 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));   
                    }
                }
                break;
            default: 
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Translator\Views\admin\list', array('languages' => $this->languages, 'breadcrumbs' => $breadcrumb));
                break;
        }
        
        
        
        
    }
}