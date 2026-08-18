<?php

namespace Modules\Slider\Controllers;

use App\Controllers\BaseController;
use Modules\Slider\Models\SliderModel;
use Modules\News\Models\NewsModel;
use App\Libraries\Breadcrumb;

class SliderAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->sliderModel = new SliderModel();
        $this->newsModel = new NewsModel();
    }

    public function index($action = '', $id = 0) {
        $slider = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Slider.SliderList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/slider');

        switch ($action) {
            case 'archive':
                $archive = true;
            case 'edit':
                $slider = $this->sliderModel->getSliderById($id, $this->id_lang, !empty($archive));
            case 'add':
            case 'save':
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
                                        'required' => $lang_name . lang('Slider.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (!empty($post['slide'])) {
                        foreach ($post['slide'] as $no => $slide) {
                            if (!empty($slide['lang'])) {
                                foreach ($slide['lang'] as $id_lang => $lang) {
                                    $validation->reset();
                                    $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                                    $validation->setRules([
                                        'title' => [
                                            'rules' => 'required',
                                            'errors' => [
                                                'required' => $lang_name . lang('Slider.slide.TitleError', [$no])
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
                        $result = $this->sliderModel->saveSlider($id, $post, !empty($archive));
                    }
                    if ($result) {
                        $this->session->setFlashdata('slider', array(
                            'status' => true,
                            'msg' => ($id ? lang('Slider.EditSuccess') : lang('Slider.AddSuccess')) . '!'
                        ));
                        HistoryStat($id, '', 'slider', 'Slider', $id ? lang('Slider.EditSuccess') : lang('Slider.AddSuccess'));
                        if (!empty($archive)) {
                            return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/slider/archive/' . $this->sliderModel->id);
                        } else {
                            return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/slider/edit/' . $this->sliderModel->id);
                        }
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Slider.EditError') : lang('Slider.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $slider = $post;
                    $slider['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('slider');
                }
                if ($id) {
                    if (!empty($archive)) {
                        $this->breadcrumb->add(lang('Slider.SliderArchive') . (!empty($slider['name']) ? ': ' . $slider['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/slider/archive/' . $id);
                    } else {
                        $this->breadcrumb->add(lang('Slider.SliderEdit') . (!empty($slider['name']) ? ': ' . $slider['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/slider/edit/' . $id);
                    }
                } else {
                    $this->breadcrumb->add(lang('Slider.NewSliderAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/slider/add');
                }

                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Slider\Views\admin\add', array('action' => $action, 'slider' => $slider, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb, 'archive' => !empty($archive)));
                break;
            default :
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->sliderModel->join('slider_lang sl', 'slider.id=sl.id_slider')->select('slider.id,slider.publish,sl.name')->where('sl.id_lang', $this->id_lang);
                if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'name':
                                if (!empty($value)) {
                                    $query->like('sl.name', $value);
                                }
                                break;
                            case 'publish':
                                if (in_array($value, array(0, 1))) {
                                    $query->where('slider.publish', $value);
                                }
                                break;
                        }
                    }
                }
                if (empty($get['order'])) {
                    $get['order'] = 'id;asc';
                }
                switch ($get['order']) {
                    case 'name;asc': $query->orderBy('sl.name', 'ASC');
                        break;
                    case 'name;desc': $query->orderBy('sl.name', 'DESC');
                        break;
                    default: $query->orderBy('slider.id', 'ASC');
                        break;
                }
                $sliders = $query->paginate(20);
                $order_list = array(
                    array('field' => '', 'name' => lang('Slider.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Slider.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Slider.sort.NameDesc')),
                );
                echo view('Modules\Slider\Views\admin\list', array('sliders' => $sliders, 'breadcrumbs' => $breadcrumb, 'filters' => $get, 'order_list' => $order_list, 'pager' => $this->sliderModel->pager));
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
                $assets['js'][] = '/adm/js/slider.js';
                $assets['js'][] = '/adm/js/file-uploader.js';
                break;
            default :
                break;
        }
        return $assets;
    }

    public function ajax($action = '', $id = 0) {
        $post = $this->request->getPost();
        if (!empty($action)) {
            switch ($action) {
                case 'slide-add':
                    return $this->addSlide($post);
                    break;
                case 'publish':
                    return $this->publishSlider($id);
                    break;
                case 'delete':
                    return $this->deleteSlider($id);
                    break;
            }
        }
    }

    private function addSlide($post = array()) {
        $html = view('Modules\Slider\Views\admin\add_slide', array('no' => $post['no'], 'languages' => $this->languages, 'locale' => $this->locale));
        return $this->response->setJSON(array(
                    'status' => true,
                    'html' => base64_encode(urlencode($html))
        ));
    }

    private function deleteSlider($id) {
        $result = $this->sliderModel->deleteSlider($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Slider.Removed') : lang('Slider.Error')
        ));
        HistoryStat($id, '', 'slider', 'Slider', $result ? lang('Slider.Removed') : lang('Slider.Error'));
    }

    private function publishSlider($id) {
        $slider = $this->sliderModel->select('id,publish')->where('id', $id)->first();
        if (!empty($slider)) {
            $r = $this->sliderModel->where('id', $id)->set('publish', $slider['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $slider['publish'] ? 0 : 1,
                'msg' => $slider['publish'] ? lang('Slider.Republished') : lang('Slider.Published')
            );
            HistoryStat($id, '', 'slider', 'Slider', $slider['publish'] ? lang('Slider.Republished') : lang('Slider.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $slider['publish'],
                'msg' => lang('Slider.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        $sliders = array();
        $list = $this->sliderModel->join('slider_lang sl', 'slider.id=sl.id_slider')->select('slider.id,sl.name')->where('sl.id_lang', $this->id_lang)->where('slider.publish', 1)->orderBy('sl.name', 'ASC')->findAll();
        if (!empty($list)) {
            foreach ($list as $l) {
                $l['link'] = ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/slider/edit/' . $l['id'];
                $sliders[$l['id']] = $l;
            }
        }
        switch ($slug) {
            case 'bigbox':
                $templates = get_templates_by_dir('modules/Slider/Views/user/bigbox');
                $big_box_List[1] = $this->sliderModel->db->table('news n')->Select('n.id,n.date,n.id_page_cont,n.publish,n.show_in_box,n.created_at,nl.title,nf.path')->join('news_lang nl', 'n.id=nl.id_news')->join('news_files nf', 'nf.id_news=n.id', 'left')->groupStart()->where('nf.publish', 1)->orWhere('nf.publish', null)->groupEnd()->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->where('n.show_in_box', 1)->where('nl.id_lang', $this->id_lang)->OrderBy('date', 'DESC')->get()->getResultArray();
                $big_box_List[2] = $this->sliderModel->db->table('news n')->Select('n.id,n.date,n.id_page_cont,n.publish,n.show_in_box,n.created_at,nl.title,nf.path')->join('news_lang nl', 'n.id=nl.id_news')->join('news_files nf', 'nf.id_news=n.id', 'left')->groupStart()->where('nf.publish', 1)->orWhere('nf.publish', null)->groupEnd()->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->where('n.show_in_box', 2)->where('nl.id_lang', $this->id_lang)->OrderBy('date', 'DESC')->get()->getResultArray();
                $big_box_List[3] = $this->sliderModel->db->table('news n')->Select('n.id,n.date,n.id_page_cont,n.publish,n.show_in_box,n.created_at,nl.title,nf.path')->join('news_lang nl', 'n.id=nl.id_news')->join('news_files nf', 'nf.id_news=n.id', 'left')->groupStart()->where('nf.publish', 1)->orWhere('nf.publish', null)->groupEnd()->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->where('n.show_in_box', 3)->where('nl.id_lang', $this->id_lang)->OrderBy('date', 'DESC')->get()->getResultArray();
                $big_box_List[4] = $this->sliderModel->db->table('news n')->Select('n.id,n.date,n.id_page_cont,n.publish,n.show_in_box,n.created_at,nl.title,nf.path')->join('news_lang nl', 'n.id=nl.id_news')->join('news_files nf', 'nf.id_news=n.id', 'left')->groupStart()->where('nf.publish', 1)->orWhere('nf.publish', null)->groupEnd()->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->where('n.show_in_box', 4)->where('nl.id_lang', $this->id_lang)->OrderBy('date', 'DESC')->get()->getResultArray();
                $big_box_List[5] = $this->sliderModel->db->table('news n')->Select('n.id,n.date,n.id_page_cont,n.publish,n.show_in_box,n.created_at,nl.title,nf.path')->join('news_lang nl', 'n.id=nl.id_news')->join('news_files nf', 'nf.id_news=n.id', 'left')->groupStart()->where('nf.publish', 1)->orWhere('nf.publish', null)->groupEnd()->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->where('n.show_in_box', 5)->where('nl.id_lang', $this->id_lang)->OrderBy('date', 'DESC')->get()->getResultArray();
                $big_box_List[6] = $this->sliderModel->db->table('news n')->Select('n.id,n.date,n.id_page_cont,n.publish,n.show_in_box,n.created_at,nl.title,nf.path')->join('news_lang nl', 'n.id=nl.id_news')->join('news_files nf', 'nf.id_news=n.id', 'left')->groupStart()->where('nf.publish', 1)->orWhere('nf.publish', null)->groupEnd()->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->where('n.show_in_box', 6)->where('nl.id_lang', $this->id_lang)->OrderBy('date', 'DESC')->get()->getResultArray();
                return array(
                    'elements' => $sliders,
                    'templates' => $templates,
                    'big_box_list' => $big_box_List,
                    'form_view' => 'Modules\Slider\Views\admin\bigbox_config'
                );
                break;
            default:
                $templates = get_templates_by_dir('modules/Slider/Views/user');
                return array(
                    'elements' => $sliders,
                    'pc_templates' => $templates
                );
                break;
        }
    }

}
