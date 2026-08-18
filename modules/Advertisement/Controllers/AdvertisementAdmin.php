<?php

namespace Modules\Advertisement\Controllers;

use App\Controllers\BaseController;
use Modules\Advertisement\Models\AdvertisementModel;
use Modules\News\Models\NewsModel;
use App\Libraries\Breadcrumb;

class AdvertisementAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->advertisementModel = new AdvertisementModel();
        $this->newsModel = new NewsModel();
    }

    public function index($action = '', $id = 0) {
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Advertisement.Advertisement'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/advertisement');
        $advertisement = array();
        switch ($action) {
            case 'edit':
                $advertisement = $this->advertisementModel->getAdvertisementById($id, $this->id_lang);
            case 'add':
            case 'save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation =  \Config\Services::validation();
                    if (!empty($post['lang'])) {
                        foreach ($post['lang'] as $id_lang => $lang) {
                            $validation->reset();
                            $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                            $validation->setRules([
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Advertisement.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->advertisementModel->saveAdvertisement($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('advertisement', array(
                            'status' => true,
                            'msg' => ($id ? lang('Advertisement.EditSuccess') : lang('Advertisement.AddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','advertisement','Advertisement',$id ? lang('Advertisement.EditSuccess') : lang('Advertisement.AddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/advertisement/edit/' . $this->advertisementModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Advertisement.EditError') : lang('Advertisement.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $advertisement = $post;
                    $advertisement['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('advertisement');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Advertisement.AdvertisementEdit') . (!empty($advertisement['name']) ? ': ' . $advertisement['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/advertisement/edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Advertisement.NewAdvertisementAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/advertisement/add');
                }
                $templates = get_templates_by_dir('modules/Advertisement/Views/user/single');
                $breadcrumb = $this->breadcrumb->render();
                $pages = $this->advertisementModel->db->table('page_content pc')
                    ->join('page_content_lang pcl', 'pc.id=pcl.id_page_cont', 'left')
                    ->join('page p', 'p.id=pc.id_page')
                    ->join('page_lang pl', 'pl.id_page=p.id')
                    ->select('pl.id_page,pl.name,pc.id as id_content,p.re_id,pcl.name as content_name,pcl.title,pc.order')
                    ->groupStart()
                        ->where('pcl.id_lang', $this->id_lang)
                        ->orWhere('pcl.id', null)
                    ->groupEnd()
                    ->where('pl.id_lang', $this->id_lang)
                    ->where('pc.id_module_element', 2)
                    ->get()
                    ->getResultArray();
                if(!empty($pages)) {
                    foreach($pages as $k=>$p) {
                        if(!empty($p['re_id'])) {
                            $pages[$k]['tree_name'] = $this->newsModel->getNewsTreeName($p['re_id'], $this->id_lang);
                        }
                    }
                }
                echo view('Modules\Advertisement\Views\admin\add', array('action' => $action, 'advertisement' => $advertisement, 'pages' => $pages, 'templates' => $templates, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            default:
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->advertisementModel->join('advertisement_lang al', 'advertisement.id=al.id_advertisement')->select('advertisement.id,advertisement.publish,al.name')->where('al.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        if(!empty($value)) {
                            switch($name) {
                                case 'search':
                                    
                                    break;
                            }
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'created_at;desc';
                }
                switch($get['order']) {
                    case 'created_at;asc': $query->orderBy('advertisement.created_at', 'ASC');
                        break;
                    case 'created_at;desc': 
                    default: 
                        $query->orderBy('advertisement.created_at', 'DESC');
                        break;
                }
                $advertisements = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                $order_list = array(
                    array('field' => '', 'name' => lang('Advertisement.sort.Default')),
                    array('field' => 'created_at;asc', 'name' => lang('Advertisement.sort.AddDateAsc')),
                    array('field' => 'created_at;desc', 'name' => lang('Advertisement.sort.AddDateDesc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                echo view('Modules\Advertisement\Views\admin\list', array(
                    'advertisements' => $advertisements, 
                    'filters' => $get, 
                    'breadcrumbs' => $breadcrumb, 
                    'order_list' => $order_list, 
                    'on_page_list'=>$on_page_list,
                    'pager' => $this->advertisementModel->pager
                ));
                break;
            
        }
    }
    
    public function assets($action='') {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        switch ($action) {
            case 'edit':
            case 'add':
            case 'save':
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
                $assets['js'][] = '/adm/js/file-uploader.js';
                break;
            default :
                break;
        }
        return $assets;
    }
    
    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        $advertisements = array();
        $list = $this->advertisementModel->db->table('advertisement a')->join('advertisement_lang al', 'a.id=al.id_advertisement')->select('a.id,al.name')->where('al.id_lang', $this->id_lang)->where('a.publish', 1)->orderBy('al.name', 'ASC')->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $l['link'] = ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/advertisement/edit/' . $l['id'];
                $advertisements[$l['id']] = $l;
            }
        }
        return array(
            'elements' => $advertisements,
        );
    }
    
    public function ajax($action='', $id=0, $id2=0) 
    {
        $post = $this->request->getPost();
        if(!empty($action)) {
            switch($action) {
                case 'publish': 
                    return $this->publishAdvertisement($id);
                    break;
                case 'delete': 
                    return $this->deleteAdvertisement($id);
                    break;
            }
        }
    }
    
    private function publishAdvertisement($id) 
    {
        $advertisement = $this->advertisementModel->select('id,publish')->where('id', $id)->first();
        if(!empty($advertisement)) {
            $r = $this->advertisementModel->where('id', $id)->set('publish', $advertisement['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $advertisement['publish'] ? 0 : 1,
                'msg' => $advertisement['publish'] ? lang('Advertisement.Republished') : lang('Advertisement.Published')
            );
            HistoryStat($id,'','advertisement','Advertisement',$advertisement['publish'] ? lang('Advertisement.Republished') : lang('Advertisement.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $advertisement['publish'],
                'msg' => lang('Advertisement.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function deleteAdvertisement($id) 
    {
        $result = $this->advertisementModel->deleteAdvertisement($id);
        HistoryStat($id,'','advertisement','Advertisement',$result ? lang('Advertisement.Removed') : lang('Advertisement.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Advertisement.Removed') : lang('Advertisement.Error')
        ));
    }
}