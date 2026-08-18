<?php

namespace Modules\Gallery\Controllers;

use App\Controllers\BaseController;
use Modules\Gallery\Models\GalleryModel;
use App\Libraries\Breadcrumb;

class GalleryAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->galleryModel = new GalleryModel();
    }

    public function index($action = '', $id_content = 0, $id = 0) {
        helper('filesystem');
        $gallery = array();

        $page = $this->galleryModel->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
        $page_info = $this->galleryModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('p.id,pl.name')->where('p.id', $page['id_page'])->where('l.default', 1)->get()->getRowArray();

        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.page.PagesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page');
        $this->breadcrumb->add(lang('Admin.page.PageContent') . ': ' . $page_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $page['id_page'] . '/' . $id_content);

        switch ($action) {
            case 'edit':
                $gallery = $this->galleryModel->getGalleryById($id, $this->id_lang);
            case 'add':
            case 'save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                        'template' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Gallery.TemplateError')
                            ],
                        ],
                    ]);
                    if (!$validation->run($post)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                    foreach ($post['lang'] as $id_lang => $lang) {
                        $validation->reset();
                        $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                        $validation->setRules([
                            'name' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Gallery.NameError')
                                ],
                            ],
                            'link' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Gallery.DirectLinkError')
                                ],
                            ],
                        ]);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->galleryModel->saveGallery($id, $id_content, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('gallery', array(
                            'status' => true,
                            'msg' => ($id ? lang('Gallery.EditSuccess') : lang('Gallery.AddSuccess')) . '!'
                        ));
                        HistoryStat($id, $id_content, 'gallery', 'Gallery', $id ? lang('Gallery.EditSuccess') : lang('Gallery.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/gallery/edit/' . $id_content . '/' . $this->galleryModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Gallery.EditError') : lang('Gallery.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $gallery = $post;
                    $gallery['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('gallery');
                }
                $templates = get_templates_by_dir('modules/Gallery/Views/user/single');
                if ($id) {
                    $this->breadcrumb->add(lang('Gallery.GalleryEdit') . (!empty($gallery['name']) ? ': ' . $gallery['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/gallery/edit/' . $id_content . '/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Gallery.GalleryAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/gallery/add/' . $id_content);
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Gallery\Views\admin\add', array('action' => $action, 'id_content' => $id_content, 'gallery' => $gallery, 'page' => $page, 'templates' => $templates, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            default :

                break;
        }
    }

    public function assets($action = '') {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        switch ($action) {
            case 'content':
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

    public function ajax($action = '', $id = 0) {
        if (!empty($action)) {
            switch ($action) {
                case 'order':
                    return $this->orderGallery($id);
                    break;
                case 'home':
                    return $this->homeGallery($id);
                    break;
                case 'publish':
                    return $this->publishGallery($id);
                    break;
                case 'delete':
                    return $this->deleteGallery($id);
                    break;
            }
        }
    }

    private function homeGallery($id) {
        $news = $this->galleryModel->select('id,home')->where('id', $id)->first();
        if (!empty($news)) {
            $r = $this->galleryModel->where('id', $id)->set('home', $news['home'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $news['home'] ? 0 : 1,
                'msg' => $news['home'] ? lang('Gallery.TurnOff') : lang('Gallery.TurnOn')
            );
            HistoryStat($id, '', 'gallery', 'Gallery', $news['home'] ? lang('Gallery.TurnOff') : lang('Gallery.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $news['home'],
                'msg' => lang('Gallery.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function orderGallery($id) {
        $post = $this->request->getPost();
        $this->galleryModel->transStart();
        if (isset($post['old_pos']) && isset($post['new_pos']) && $post['old_pos'] != $post['new_pos']) {
            $gallery = $this->galleryModel->select('id,order,id_page_cont')->where('order', $post['old_pos'])->first();
            if ($post['new_pos'] > $post['old_pos']) {
                $this->galleryModel->set('order', '`order`-1', FALSE)->where('order<=', $post['new_pos'])->where('order>', $post['old_pos'])->where('id_page_cont', $gallery['id_page_cont'])->update();
            } elseif ($post['new_pos'] < $post['old_pos']) {
                $r = $this->galleryModel->set('order', '`order`+1', FALSE)->where('order>=', $post['new_pos'])->where('order<', $post['old_pos'])->where('id_page_cont', $gallery['id_page_cont'])->update();
            }
            $this->galleryModel->where('id', $gallery['id'])->set('order', $post['new_pos'])->update();
        }
        $this->galleryModel->transComplete();
        $r = $this->galleryModel->transStatus();
        $response = array(
            'status' => true,
            'result' => $r,
            'msg' => $r ? lang('Gallery.OrderChanged') : lang('Gallery.Error'),
            'new_pos' => $post['new_pos'],
            'old_pos' => $post['old_pos'],
        );
        return $this->response->setJSON($response);
    }

    private function publishGallery($id) {
        $gallery = $this->galleryModel->select('id,publish')->where('id', $id)->first();
        if (!empty($gallery)) {
            $r = $this->galleryModel->where('id', $id)->set('publish', $gallery['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $gallery['publish'] ? 0 : 1,
                'msg' => $gallery['publish'] ? lang('Gallery.Republished') : lang('Gallery.Published')
            );
            HistoryStat($id, '', 'gallery', 'Gallery', $gallery['publish'] ? lang('Gallery.Republished') : lang('Gallery.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $gallery['publish'],
                'msg' => lang('Gallery.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function deleteGallery($id) {
        $result = $this->galleryModel->deleteGallery($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Gallery.Removed') : lang('Gallery.Error')
        ));
        HistoryStat($id, '', 'gallery', 'Gallery', $result ? lang('Gallery.Removed') : lang('Gallery.Error'));
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        switch ($slug) {
            case 'photos':
                $templates = get_templates_by_dir('modules/Gallery/Views/user/photos');
                $lists = $this->galleryModel
                        ->join('gallery_lang gl', 'gl.id_gallery=gallery.id')
                        ->select('gallery.id,gl.name')
                        ->where('gl.id_lang', $this->id_lang)
                        ->orderBy('gl.name', 'ASC')
                        ->get()
                        ->getResultArray();
                return array(
                    'lists' => $lists,
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Gallery\Views\admin\photos_config'
                );
                break;
            case 'selected':
                $templates = get_templates_by_dir('modules/Gallery/Views/user/list');
                $lists = $this->galleryModel->db->table('page_content pc')
                        ->join('page_lang pl', 'pl.id_page=pc.id_page')
                        ->select('pl.id_page,pl.name,pc.id as id_content')
                        ->where('pl.id_lang', $this->id_lang)
                        ->whereIn('pc.id_module_element', array(4, 5))
                        ->get()
                        ->getResultArray();
                return array(
                    'lists' => $lists,
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Gallery\Views\admin\selected_config'
                );
                break;
            case 'single':
                $gallery = $this->galleryModel->getGalleryByContentId($id_content);
                $templates = get_templates_by_dir('modules/Gallery/Views/user/single');
                return array(
                    'form_data' => $gallery,
                    'templates' => $templates,
                    'form_view' => 'Modules\Gallery\Views\admin\add_single'
                );
                break;
            case 'list':
            default:
                $get = $this->request->getGet();
                $query = $this->galleryModel
                        ->join('gallery_lang gl', 'gallery.id=gl.id_gallery')
                        ->select('gallery.id,gallery.publish,gallery.home,gallery.order,gl.name')
                        ->where('gallery.id_page_cont', $id_content)
                        ->where('gl.id_lang', $this->id_lang);
                if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'name':
                                if (!empty($value)) {
                                    $query->like('gl.name', $value);
                                }
                                break;
                            case 'publish':
                                if (in_array($value, array(0, 1))) {
                                    $query->where('gallery.publish', $value);
                                }
                                break;
                            case 'home':
                                if (in_array($value, array(0, 1))) {
                                    $query->where('gallery.home', $value);
                                }
                                break;
                        }
                    }
                }
               
				
				if(empty($get['order'])) {
				 $get['order_array'] = array();
				 $query->orderBy('order','ASC');
				}
                if(!empty($get['order'])) {
                    $tmp = explode(',', $get['order']);
                    $get['order_array'][$tmp[0]] = $tmp[1] == 'asc' ? 'asc' : 'desc';
                    switch ($tmp[0]) {
                        case 'name': $query->orderBy('name', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'home': $query->orderBy('home', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'publish': $query->orderBy('publish', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'order': $query->orderBy('order', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                    }
                }
				
				

                $gallery_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
				$on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                $templates = get_templates_by_dir('modules/Gallery/Views/user/list');
                return array(
                    'gallery_list' => $gallery_list,
                    'filters' => $get,
                    'order_list' => '',
					'on_page_list'=>$on_page_list,
                    'pager' => $this->galleryModel->pager,
                    'pc_templates' => $templates,
                    'list_view' => 'Modules\Gallery\Views\admin\list',
                    'form_view' => 'Modules\Gallery\Views\admin\list_config'
                );
                break;
        }
    }

    public function savePageContent($id_content, $post) {
        $this->galleryModel->saveGalleryByPageContent($id_content, $post);
    }

    public function preDeletePageModule($data) {
        if (!empty($data['slug'])) {
            switch ($data['slug']) {
                case 'list':
                    $count = $this->galleryModel->where('id_page_cont', $data['id'])->countAllResults();
                    return $count ? false : true;
                    break;
                default: return true;
                    break;
            }
        }
        return true;
    }

    public function deletePageModule($data) {
        $gallery = $this->galleryModel->select('id')->where('id_page_cont', $data['id'])->first();
        if (!empty($gallery)) {
            $langs = $this->galleryModel->db->table('gallery_lang')->select('id_link')->where('id_gallery', $gallery['id'])->get()->getResultArray();
            if (!empty($langs)) {
                foreach ($langs as $l) {
                    $this->galleryModel->db->table('links')->where('id', $l['id_link'])->delete();
                }
            }
            $files_list = $this->galleryModel->db->table('gallery_files')->select('id,path')->where('id_gallery', $gallery['id'])->get()->getResultArray();
            if (!empty($files_list)) {
                foreach ($files_list as $f) {
                    $this->galleryModel->removeGalleryFile($f);
                }
            }
            $this->galleryModel->db->table('gallery_lang')->where('id_gallery', $gallery['id'])->delete();
            $this->galleryModel->db->table('gallery_meta_lang')->where('id_gallery', $gallery['id'])->delete();
            $this->galleryModel->where('id', $gallery['id'])->delete();
        }
    }

}
