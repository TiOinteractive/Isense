<?php

namespace Modules\News\Controllers;

use App\Controllers\BaseController;
use Modules\News\Models\NewsModel;
use Modules\Tags\Models\TagsModel;
use App\Models\PageContentModel;
use App\Libraries\Breadcrumb;

class NewsAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->newsModel = new NewsModel();
        $this->tagsModel = new TagsModel();
    }

    public function index($action = '', $id_content = 0, $id = 0) {
        helper('filesystem');
        $news = array();
        $page = $this->newsModel->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
        $page_info = $this->newsModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('p.id,pl.name,p.re_id')->where('p.id', $page['id_page'])->where('l.default', 1)->get()->getRowArray();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.page.PagesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page');
        $this->breadcrumb->add(lang('Admin.page.PageContent') . ': ' . $page_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $page['id_page'] . '/' . $id_content);
        if (!empty($page_info['re_id']) and $page_info['re_id'] > 0 and $action != 'add') {
            $page['moveTo'] = $this->newsModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('p.id,pl.name')->where('p.re_id', $page_info['re_id'])->where('p.id!=', $page_info['id'])->where('l.default', 1)->get()->getResultArray();
        } elseif ($action != 'add' and $page_info['re_id'] == 0) {
            $page['moveTo'] = $this->newsModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('p.id,pl.name')->where('p.re_id', $page_info['id'])->where('l.default', 1)->get()->getResultArray();
        }
        switch ($action) {
            case 'configuration':
                $pageContentModel = new PageContentModel();
                $post = $this->request->getPost();
                $settings = array();
                $flashdata = array();
                
                $settings = $pageContentModel->getSettings($id_content);
                $flashdata = array();
                if (!empty($post)) {
                    $result = $this->mapsModel->saveSettings($post);
                    if ($result) {
                        $this->session->setFlashdata('maps', array(
                            'status' => true,
                            'msg' => lang('Maps.configuration.EditSuccess') . '!'
                        ));
                        HistoryStat('', '', 'maps', 'Maps', lang('Maps.configuration.EditSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/maps/configuration');
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => lang('Maps.configuration.EditError') . '!',
                            'list' => $errors
                        );
                        $settings = $post;
                    }
                } else {
                    $flashdata = $this->session->getFlashdata('maps');
                }
                
                $this->breadcrumb->add(lang('News.Configuration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/news/configuration/' . $id_content);
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\News\Views\admin\settings', array('settings' => $settings, 'id_content' => $id_content, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'edit':
                $news = $this->newsModel->getNewsById($id, $this->id_lang);
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
                                'required' => lang('News.TemplateError')
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
                            'title' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('News.TitleError')
                                ],
                            ],
                            'link' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('News.DirectLinkError')
                                ],
                            ],
                        ]);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->newsModel->saveNews($id, $id_content, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('news', array(
                            'status' => true,
                            'msg' => ($id ? lang('News.EditSuccess') : lang('News.AddSuccess')) . '!' . ' <a href="' . ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/news/edit/' . $id_content . '/' . $this->newsModel->id . '"><i>Edytuj</i></a><br />'
                        ));
                        HistoryStat($id, $id_content, 'news', 'News', $id ? lang('News.EditSuccess') : lang('News.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $page['id_page'] . '/' . $id_content);
                        //return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/news/edit/' . $id_content . '/' . $this->newsModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('News.EditError') : lang('News.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $news = $post;
                    $news['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('news');
                }
                $templates = get_templates_by_dir('modules/News/Views/user/single');
                if ($id) {
                    $this->breadcrumb->add(lang('News.NewsEdit') . (!empty($news['name']) ? ': ' . $news['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/news/edit/' . $id_content . '/' . $id);
                } else {
                    $this->breadcrumb->add(lang('News.NewsAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/news/add/' . $id_content);
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\News\Views\admin\add', array('action' => $action, 'id_content' => $id_content, 'news' => $news, 'page' => $page, 'templates' => $templates, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
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
            case 'edit':
            case 'add':
            case 'save':
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css';
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css';
                $assets['css_footer'][] = '/adm/third-party/tags/jquery.tagsinput.css';
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
                $assets['js'][] = '/adm/third-party/tags/jquery.tagsinput.js';
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
                    return $this->orderNews($id);
                    break;
                case 'home':
                    return $this->homeNews($id);
                    break;
                case 'investments':
                    return $this->investmentsNews($id);
                    break;
                case 'slider':
                    return $this->sliderNews($id);
                    break;
                case 'newsletter':
                    return $this->newsletterNews($id);
                    break;
                case 'newsletter-clear':
                    return $this->newsletterClearNews($id);
                    break;
                case 'publish':
                    return $this->publishNews($id);
                    break;
                case 'delete':
                    return $this->deleteNews($id);
                    break;
				case 'add_bigbox':
                  return $this->BigBoxModal($id);
                break;	
                case 'bigbox-save':
                   return $this->SaveBigBox($id); 
                break;
				case 'removeBigBox':
                    return $this->removeBigBox($id);
                break;
				case 'select_box':
                    return $this->changeBox($id);
                break;					
            }
        }
    }
	
	
	
	    private function BigBoxModal($id) {
		$post = $this->request->getPost();
		$ids = array();
            $list = $this->newsModel->db->table('news')->select('id')->where('show_in_box>',0)->get()->getResultArray();
            if(!empty($list)) {
                foreach($list as $l) {
                    $ids[] = $l['id'];
                }
            }
        if(!empty($post)) {
            $query = $this->newsModel
                    ->join('news_lang nl', 'news.id=nl.id_news')
                    ->join('news_files pf', 'pf.id_news=nl.id_news', 'left')
					->join('page_content_lang pl', 'pl.id_page_cont=news.id_page_cont', 'left')
                    ->select('news.id,nl.title,news.date,pl.title as page_title,pl.name as page_name,pf.path')
                    ->where('nl.id_lang', $this->id_lang)
					->where('pl.id_lang', $this->id_lang)
                    ->groupStart()
                        ->where('pf.field', 'photo')
                        ->orWhere('pf.id',NULL)
                    ->groupEnd();
            if(!empty($ids)) {
                $query->whereNotIn('news.id', $ids);
            }
            foreach ($post as $name => $value) {
                switch ($name) {
                    case 'title':
                        if (!empty($value)) {
                            $query->groupStart();
                            $query->like('nl.title', $value);
                            $query->groupEnd();
                        }
                        break;
					  case 'date':
                           if (!empty($value)) {
                                $date_range = explode('-', $value);
                           if (!empty($date_range[0])) {
                                $query->where('news.date>=', date('Y-m-d', strtotime($date_range[0])));
                           }
                          if (!empty($date_range[1])) {
                            $query->where('news.date<=', date('Y-m-d', strtotime($date_range[1])));
                         }
                        }
                     break;	
                }
            }
            $query->orderBy('news.date', 'DESC');
            $news = $query->paginate(50);
            $html = view('Modules\News\Views\admin\bigbox_news_list', array('newslist' => $news,'id_box'=>$id,'languages' => $this->languages, 'locale' => $this->locale));
            return $this->response->setJSON(array(
                'status' => true,
                'html' => base64_encode(urlencode($html))
            ));
        } else {
           
		   
		    $query = $this->newsModel
                    ->join('news_lang nl', 'news.id=nl.id_news')
                    ->join('news_files pf', 'pf.id_news=nl.id_news', 'left')
					->join('page_content_lang pl', 'pl.id_page_cont=news.id_page_cont', 'left')
                    ->select('news.id,nl.title,news.date,pl.title as page_title,pl.name as page_name,pf.path')
                    ->where('nl.id_lang', $this->id_lang)
					->where('pl.id_lang', $this->id_lang)
                    ->groupStart()
                        ->where('pf.field', 'photo')
                        ->orWhere('pf.id',NULL)
                    ->groupEnd();
            if(!empty($ids)) {
                $query->whereNotIn('news.id', $ids);
            }
            $query->orderBy('news.date', 'DESC');
            $news = $query->paginate(50);
            $html = view('Modules\News\Views\admin\bigbox_modal', array('newslist' => $news,'id_box'=>$id,'languages' => $this->languages, 'locale' => $this->locale));
            return $this->response->setJSON(array(
                'status' => true,
                'html' => base64_encode(urlencode($html))
            ));
        }
    }
	
	
	 private function SaveBigBox($id_box) {
		$post = $this->request->getPost();
        $html = "";
        $result = false;
        $box_list = array();
        $this->newsModel->db->transStart();
        if (!empty($post) && !empty($post['news'])) {
            $result = $this->newsModel->saveBigBox($id_box, $post['news']);
            if ($result) {
               $box_list = $this->newsModel->getNewsBox($id_box, $this->id_lang);
            }
        }
        $this->newsModel->db->transComplete();
        $result = $this->newsModel->db->transStatus();
        $html = view('Modules\News\Views\admin\bigbox_list', array('box_list' => $box_list, 'box' => $id_box, 'locale' => $this->locale));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'html' => base64_encode(urlencode($html)),
            'msg' => $result ? lang('News.BigBoxNewsAddSuccess') : lang('News.BigBoxNewsAddError')
        ));
    }
	
	
	
	
	

    private function orderNews($id) {
        $post = $this->request->getPost();
        $this->newsModel->transStart();
        if (isset($post['old_pos']) && isset($post['new_pos']) && $post['old_pos'] != $post['new_pos']) {
            $news = $this->newsModel->select('id,order,id_page_cont')->where('order', $post['old_pos'])->where('id_Page_cont',$id)->first();
            if ($post['new_pos'] > $post['old_pos']) {
                $this->newsModel->set('order', '`order`-1', FALSE)->where('order<=', $post['new_pos'])->where('order>', $post['old_pos'])->where('id_page_cont', $news['id_page_cont'])->update();
            } elseif ($post['new_pos'] < $post['old_pos']) {
                $r = $this->newsModel->set('order', '`order`+1', FALSE)->where('order>=', $post['new_pos'])->where('order<', $post['old_pos'])->where('id_page_cont', $news['id_page_cont'])->update();
            }
            $this->newsModel->where('id', $news['id'])->set('order', $post['new_pos'])->update();
        }
        $this->newsModel->transComplete();
        $r = $this->newsModel->transStatus();
        $response = array(
            'status' => true,
            'result' => $r,
            'msg' => $r ? lang('News.OrderChanged') : lang('News.Error'),
            'new_pos' => $post['new_pos'],
            'old_pos' => $post['old_pos'],
        );
        return $this->response->setJSON($response);
    }

    private function homeNews($id) {
        $news = $this->newsModel->select('id,home')->where('id', $id)->first();
        if (!empty($news)) {
            $r = $this->newsModel->where('id', $id)->set('home', $news['home'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $news['home'] ? 0 : 1,
                'msg' => $news['home'] ? lang('News.TurnOff') : lang('News.TurnOn')
            );
            HistoryStat($id, '', 'news', 'News', $news['home'] ? lang('News.TurnOff') : lang('News.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $news['home'],
                'msg' => lang('News.Error')
            );
        }
        return $this->response->setJSON($response);
    }

   private function changeBox($id) {
      $post = $this->request->getPost();
      if(!empty($post['value'])) { 
		$r = $this->newsModel->where('id', $id)->set('show_in_box', $post['value'])->update();
		$response = array(
                'status' => $r,
                'msg' => lang('News.ShowInBoxOn').' '.$post['value']
        );
		HistoryStat($id, '', 'news', 'News', lang('News.ShowInBoxOn').' '.$post['value']);
	  }	  
	  else {
		 $r = $this->newsModel->where('id', $id)->set('show_in_box',NULL)->update();
		$response = array(
                'status' => $r,
                'msg' => lang('News.ShowInBoxOff')
        ); 
		HistoryStat($id, '', 'news', 'News', lang('News.ShowInBoxOff'));  
	  }	  

     return $this->response->setJSON($response);
   }

    private function investmentsNews($id) {
        $news = $this->newsModel->select('id,investments')->where('id', $id)->first();
        if (!empty($news)) {
            $r = $this->newsModel->where('id', $id)->set('investments', $news['investments'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $news['investments'] ? 0 : 1,
                'msg' => $news['investments'] ? lang('News.TurnOff') : lang('News.TurnOn')
            );
            HistoryStat($id, '', 'news', 'News', $news['investments'] ? lang('News.TurnOff') : lang('News.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $news['investments'],
                'msg' => lang('News.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function sliderNews($id) {
        $news = $this->newsModel->select('id,slider')->where('id', $id)->first();
        if (!empty($news)) {
            $r = $this->newsModel->where('id', $id)->set('slider', $news['slider'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $news['slider'] ? 0 : 1,
                'msg' => $news['slider'] ? lang('News.TurnOff') : lang('News.TurnOn')
            );
            HistoryStat($id, '', 'news', 'News', $news['slider'] ? lang('News.TurnOff') : lang('News.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $news['slider'],
                'msg' => lang('News.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function newsletterNews($id) {
        $news = $this->newsModel->select('id,id_page_cont,newsletter')->where('id', $id)->first();
        if (!empty($news)) {
            $news_page_cont_ids = $this->getNewsPages($news['id_page_cont']);
            $r = $this->newsModel->where('id', $id)->set('newsletter', $news['newsletter'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'newsletter' => $news['newsletter'] ? 0 : 1,
                'msg' => $news['newsletter'] ? lang('News.TurnOff') : lang('News.TurnOn'),
                'count' => !empty($news_page_cont_ids) ? $this->newsModel->db->table('news')->where('newsletter', 1)->whereIn('id_page_cont', $news_page_cont_ids)->countAllResults() : 0
            );
            HistoryStat($id, '', 'news', 'News', $news['newsletter'] ? lang('News.TurnOff') : lang('News.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'newsletter' => $news['newsletter'],
                'msg' => lang('News.Error'),
                'count' => '-'
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function newsletterClearNews($id_content) {
        $news_page_cont_ids = $this->getNewsPages($id_content);
        if(!empty($news_page_cont_ids)) {
            $response = array(
                'status' => $this->newsModel->db->table('news')->set('newsletter', 0)->where('newsletter', 1)->whereIn('id_page_cont', $news_page_cont_ids)->update(),
                'count' => $this->newsModel->db->table('news')->where('newsletter', 1)->whereIn('id_page_cont', $news_page_cont_ids)->countAllResults()
            );
        } else {
            $response = array(
                'status' => true,
                'count' => 0
            );
        }
        return $this->response->setJSON($response);
    }

    private function publishNews($id) {
        $news = $this->newsModel->select('id,publish,publish_date')->where('id', $id)->first();
        if (!empty($news)) {
            if (empty($news['publish'])) {
                $news['publish_date'] = NULL;
            }
            $r = $this->newsModel->where('id', $id)->set(array('publish' => $news['publish'] ? 0 : 1, 'publish_date' => $news['publish_date']))->update();
            $response = array(
                'status' => $r,
                'publish' => $news['publish'] ? 0 : 1,
                'msg' => $news['publish'] ? lang('News.Republished') : lang('News.Published')
            );
            HistoryStat($id, '', 'news', 'News', $news['publish'] ? lang('News.Republished') : lang('News.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $news['publish'],
                'msg' => lang('News.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function deleteNews($id) {
        $result = $this->newsModel->deleteNews($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('News.Removed') : lang('News.Error')
        ));
        HistoryStat($id, '', 'news', 'News', $result ? lang('News.Removed') : lang('News.Error'));
    }
	
	private function removeBigBox($id) {
        $result = $this->newsModel->removeBigBox($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('News.RemovedBigBox') : lang('News.Error')
        ));
        HistoryStat($id, '', 'news', 'News', $result ? lang('News.RemovedBigBox') : lang('News.Error'));
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        switch ($slug) {
			case 'most_read':
			  $templates = get_templates_by_dir('modules/News/Views/user/list');
			  return array(
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\News\Views\admin\mostread_config'
                );
			break;
			case 'instagram':
			  $templates = get_templates_by_dir('modules/News/Views/user/instagram');
			  return array(
			        'pc_templates' => $templates, 
                    'form_view' => 'Modules\News\Views\admin\instagram_config'
                );
			break;
            case 'selected':
                $templates = get_templates_by_dir('modules/News/Views/user/list');
                $lists = $this->newsModel->db->table('page_content pc')
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
                if(!empty($lists)) {
                    foreach($lists as $k=>$list) {
                        if(!empty($list['re_id'])) {
                            $lists[$k]['tree_name'] = $this->newsModel->getNewsTreeName($list['re_id'], $this->id_lang);
                        }
                    }
                }
                $tags = $this->tagsModel->db->table('tags')
                        ->select('id,tag')
                        ->where('id_lang', $this->id_lang)
                        ->where("TRIM(COALESCE(tag, '')) != ''", null, false)
                        ->orderBy('tag', 'ASC')
                        ->get()
                        ->getResultArray();
                return array(
                    'lists' => $lists,
                    'tags' => $tags,
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\News\Views\admin\selected_config'
                );
                break;
            case 'list':
            default:
                $get = $this->request->getGet();
                $query = $this->newsModel
                        ->join('news_lang nl', 'news.id=nl.id_news')
                        ->select('news.id,news.publish,news.home,news.investments,news.slider,news.newsletter,news.order,nl.title,date,publish_date,show_in_box')
                        ->where('news.id_page_cont', $id_content)
                        ->where('nl.id_lang', $this->id_lang);
                if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'title':
                                if (!empty($value)) {
                                    $query->like('nl.title', $value);
                                }
                                break;
                            case 'date':
                                if (!empty($value)) {
                                    $date_range = explode('-', $value);
                                    if (!empty($date_range[0])) {
                                        $query->where('news.date>=', date('Y-m-d', strtotime($date_range[0])));
                                    }
                                    if (!empty($date_range[1])) {
                                        $query->where('news.date<=', date('Y-m-d', strtotime($date_range[1])));
                                    }
                                }
                                break;
                            case 'publish':
                                if (in_array($value, array(0, 1))) {
                                    $query->where('news.publish', $value);
                                }
                                break;
                            case 'home':
                                if (in_array($value, array(0, 1))) {
                                    $query->where('news.home', $value);
                                }
                                break;
							 case 'box':
                                if (in_array($value, array(1,2,3))) {
                                    $query->where('news.show_in_box', $value);
                                }
                                break;	
                        }
                    }
                }

                if (empty($get['order'])) {
                    $get['order_array'] = array();
                    $query->orderBy('id', 'DESC');
                }
                if (!empty($get['order'])) {
                    $tmp = explode(',', $get['order']);
                    $get['order_array'][$tmp[0]] = $tmp[1] == 'asc' ? 'asc' : 'desc';
                    switch ($tmp[0]) {
                        case 'name': $query->orderBy('title', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'home': $query->orderBy('home', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'investments': $query->orderBy('investments', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'slider': $query->orderBy('slider', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'newsletter': $query->orderBy('newsletter', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'publish': $query->orderBy('publish', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'order': $query->orderBy('order', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'date': $query->orderBy('date', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
						case 'show_in_box': $query->orderBy('show_in_box', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;	
                    }
                }
                $news_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if(!empty($news_list)) {
                    foreach($news_list as $k=>$nl) {
                        $count = $this->newsModel->db->table('news_lang nl')->where('nl.id_news', $nl['id'])->selectSum('nl.views')->get()->getRowArray();
						$foto=$this->newsModel->db->table('news_files')->Select('path')->Where('id_news',$nl['id'])->Where('field','photo')->get()->getRowArray();
						if(!empty($foto['path'])) {$news_list[$k]['path']=$foto['path'];}
                        $news_list[$k]['views'] = $count['views'];
                    }
                }
                $templates = get_templates_by_dir('modules/News/Views/user/list');
                $order_list = array(
                    array('field' => 'order;asc', 'name' => lang('News.sort.OrderAsc')),
                    array('field' => 'order;desc', 'name' => lang('News.sort.OrderDesc')),
                    array('field' => 'title;asc', 'name' => lang('News.sort.TitleAsc')),
                    array('field' => 'title;desc', 'name' => lang('News.sort.TitleDesc')),
                    array('field' => 'date;desc', 'name' => lang('News.sort.DateDesc')),
                    array('field' => 'date;asc', 'name' => lang('News.sort.DateAsc')),
                    array('field' => 'investments;desc', 'name' => lang('News.sort.InvestmentsDesc')),
                    array('field' => 'investments;asc', 'name' => lang('News.sort.InvestmentsAsc')),
                    array('field' => 'newsletter;desc', 'name' => lang('News.sort.NewsletterDesc')),
                    array('field' => 'newsletter;asc', 'name' => lang('News.sort.NewsletterAsc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                $news_page_cont_ids = $this->getNewsPages($id_content);
                $flashdata = $this->session->getFlashdata('news');
                return array(
                    'flashdata' => $flashdata,
                    'news_list' => $news_list,
                    'filters' => $get,
                    'order_list' => '',
                    'pager' => $this->newsModel->pager,
                    'on_page_list' => $on_page_list,
                    'pc_templates' => $templates,
                    'list_view' => 'Modules\News\Views\admin\list',
                    'form_view' => 'Modules\News\Views\admin\list_config',
                    'newsletter_count' => !empty($news_page_cont_ids) ? $this->newsModel->db->table('news')->where('newsletter', 1)->whereIn('id_page_cont', $news_page_cont_ids)->countAllResults() : 0
                );
                break;
        }
    }
    
    private function getNewsPages($id_content) {
        $content_ids = array();
        $page_content =  $this->newsModel->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
        if(!empty($page_content)) {
            $page = $this->newsModel->getMainPageId($page_content['id_page']);
            if(!empty($page)) {
                $ids = $this->newsModel->getSubpageContentIds($page['id']);
                if(!empty($ids)) {
                    $content_list = $this->newsModel->db->table('page_content')->select('id')->whereIn('id_page', $ids)->get()->getResultArray();
                    if(!empty($content_list)) {
                        foreach($content_list as $l) {
                            $content_ids[] = $l['id'];
                        }
                    }
                }
            }
        }
        return $content_ids;
    }
    

    public function preDeletePageModule($data) {
        if (!empty($data['slug'])) {
            switch ($data['slug']) {
                case 'list':
                    $count = $this->newsModel->where('id_page_cont', $data['id'])->countAllResults();
                    return $count ? false : true;
                    break;
                default: return true;
                    break;
            }
        }
        return true;
    }
}
