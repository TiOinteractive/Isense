<?php

namespace Modules\Foto\Controllers;
use App\Controllers\BaseController;
use Modules\Foto\Models\FotoCategoryModel;
use Modules\Foto\Models\FotoGalleryModel;
use Modules\Foto\Models\FotoModel;
use App\Libraries\Breadcrumb;
use App\Libraries\Link;

class FotoAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->fotoCategoryModel = new FotoCategoryModel();
		$this->fotoGalleryModel = new FotoGalleryModel();
		$this->fotoModel = new FotoModel();
    }
    
    public function index($action='', $id_content=0, $id=0) 
    {
	  helper('filesystem');	
	  $page = $this->fotoCategoryModel->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
      $page_info = $this->fotoCategoryModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('p.id,pl.name,p.re_id')->where('p.id', $page['id_page'])->where('l.default', 1)->get()->getRowArray();	
	  $this->breadcrumb = new Breadcrumb();	
	  $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
      $this->breadcrumb->add(lang('Admin.page.PagesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page');
      $this->breadcrumb->add(lang('Admin.page.PageContent') . ': ' . $page_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $id_content); 	  
	   $category=array();
	   $gallery=array();
	  switch ($action) {	
	     case 'edit-category': 
                $category = $this->fotoCategoryModel->getCategoryById($id, $this->id_lang);
		 case 'add-category':
         case 'save-category':
		       $post = $this->request->getPost();
                if(!empty($post)) {
		        $result = false;
                    $errors = array();
                    $validation =  \Config\Services::validation();
                    foreach ($post['lang'] as $id_lang=>$lang) {
                        $validation->reset();
                        $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                        $validation->setRules([
                            'name' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Foto.NameError')
                                ],
                            ],
                            'link' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Foto.DirectLinkError')
                                ],
                            ],
                        ]);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if(empty($errors)) {
                        $result = $this->fotoCategoryModel->saveCategory($id, $id_content, $post);
                    }
					if($result) {
                        $this->session->setFlashdata('foto', array(
                            'status' => true,
                            'msg' => ($id ? lang('Foto.EditCategorySuccess') : lang('Foto.AddCategorySuccess')) . '!'
                        ));
						HistoryStat($id, $id_content,'foto_category','Foto',$id ? lang('Foto.EditCategorySuccess') : lang('News.AddCategorySuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/foto/edit-category/' . $id_content . '/' . $this->fotoCategoryModel->id);
                    } else {
						HistoryStat($id, $id_content,'foto_category','Foto',$id ? lang('Foto.EditCategoryError') : lang('News.AddCategoryError'));
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Foto.EditCategoryError') : lang('Foto.AddCategoryError')) . '!',
                            'list' => $errors
                        );
                    }
                    $category= $post;
                    $category['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('foto');
                }	
				if($id) {
                    $this->breadcrumb->add(lang('Foto.CategoryEdit') . (!empty($category['name']) ? ': ' . $category['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/foto/edit-category/' . $id_content . '/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Foto.CategoryAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/foto/add-category/' . $id_content);
                }
                $breadcrumb = $this->breadcrumb->render();
				$pages=$this->fotoCategoryModel->getCategoryStructure($this->id_lang, 0, array($id));
                echo view('Modules\Foto\Views\admin\add-category', array('action' => $action, 'id_content' => $id_content, 'category' => $category, 'page' => $page, 'pages' => $pages, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));				
		 break;
		case 'gallery-add':
           $post = $this->request->getPost();
		    if(!empty($post)) {
					$result = false;
                    $errors = array();
                    $validation =  \Config\Services::validation();
                    foreach ($post['lang'] as $id_lang=>$lang) {
                        $validation->reset();
                        $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                        $validation->setRules([
                            'name' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Foto.NameError')
                                ],
                            ],
                            'link' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Foto.DirectLinkError')
                                ],
                            ],
                        ]);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
					if (empty($errors)) {
                        $result = $this->fotoGalleryModel->addGallery($id, $id_content, $post);
                    }
					 if ($result) {
                        $this->session->setFlashdata('foto', array(
                            'status' => true,
                            'msg' => ($id ? lang('Gallery.EditSuccess') : lang('Gallery.AddSuccess')) . '!'
                        ));
                        HistoryStat($id, $id_content, 'foto_gallery', 'Foto', $id ? lang('Gallery.EditSuccess') : lang('Gallery.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/foto/gallery-edit/' . $id_content . '/' . $this->fotoGalleryModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => lang('Gallery.AddError'). '!',
                            'list' => $errors
                        );
                    }
					 $gallery=$post;
			}		
			else {
				   $flashdata = $this->session->getFlashdata('foto');
			}	
			$this->breadcrumb->add(lang('Foto.GalleryAdd') . (!empty($gallery['lang'][$this->id_lang]['name']) ? ': ' . $gallery['lang'][$this->id_lang]['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/foto/gallery-add/' . $id_content . '/');
			$pages=$this->fotoCategoryModel->getCategoryStructure($this->id_lang, 0, array());
			$users_list=$this->fotoGalleryModel->db->table('users')->select('id,name,nick,mail')->GroupStart()->Like('mail','@tio')->orLike('mail','@resinet')->orLike('mail','cupik@wp.pl')->GroupEnd()->OrderBy('mail', 'ASC')->get()->getResultArray();	
			$breadcrumb = $this->breadcrumb->render();
		  echo view('Modules\Foto\Views\admin\add-gallery',array('action' => $action, 'id_content' => $id_content,'gallery'=>$gallery,'users'=>$users_list,'id_lang'=>$this->id_lang,'page'=>$page,'pages' => $pages, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
        break;		
		case 'photo-add':
           $post = $this->request->getPost();
		    if(!empty($post)) {
					$result = false;
                    $errors = array();
                    $validation =  \Config\Services::validation();
                    foreach ($post['lang'] as $id_lang=>$lang) {
                        $validation->reset();
                        $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                        $validation->setRules([
                            'name' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Foto.NameError')
                                ],
                            ],
                        ]);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
					if (empty($errors)) {
                        $result = $this->fotoModel->addPhoto($id, $id_content, $post);
                    }
					 if ($result) {
                        $this->session->setFlashdata('foto', array(
                            'status' => true,
                            'msg' => ($id ? lang('Foto.PhotoEditSuccess') : lang('Foto.PhotoAddSuccess')) . '!'
                        ));
                        HistoryStat($id, $id_content, 'foto_files', 'Foto', $id ? lang('Foto.PhotoEditSuccess') : lang('Foto.PhotoAddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $id_content);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => lang('Foto.PhotoAddError'). '!',
                            'list' => $errors
                        );
                    }
					 $gallery=$post;
			}		
			else {
				   $flashdata = $this->session->getFlashdata('foto');
			}	
			$this->breadcrumb->add(lang('Foto.PhotoAdd') . (!empty($gallery['lang'][$this->id_lang]['name']) ? ': ' . $gallery['lang'][$this->id_lang]['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/foto/photo-add/' . $id_content . '/');
			$pages=$this->fotoCategoryModel->getCategoryStructure($this->id_lang, 0, array());
			$users_list=$this->fotoGalleryModel->db->table('users')->select('id,name,nick,mail')->GroupStart()->Like('mail','@tio')->orLike('mail','@resinet')->GroupEnd()->OrderBy('mail', 'ASC')->get()->getResultArray();	
			$breadcrumb = $this->breadcrumb->render();
		  echo view('Modules\Foto\Views\admin\add-photo',array('action' => $action, 'id_content' => $id_content,'gallery'=>$gallery,'users'=>$users_list,'id_lang'=>$this->id_lang,'page'=>$page,'pages' => $pages, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
        break;	
		case 'gallery-edit':
		
		    $post = $this->request->getPost();
                if(!empty($post)) {
					$result = false;
                    $errors = array();
                    $validation =  \Config\Services::validation();
                    foreach ($post['lang'] as $id_lang=>$lang) {
                        $validation->reset();
                        $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                        $validation->setRules([
                            'name' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Foto.NameError')
                                ],
                            ],
                            'link' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Foto.DirectLinkError')
                                ],
                            ],
                        ]);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if(empty($errors)) {
                        $result = $this->fotoGalleryModel->saveGallery($id, $id_content, $post);
                    }
					if($result) {
                        $this->session->setFlashdata('foto', array(
                            'status' => true,
                            'msg' => lang('Foto.EditGallerySuccess') . '!'
                        ));
						HistoryStat($id, $id_content,'foto_gallery','Foto',lang('Foto.EditGallerySuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/foto/gallery-edit/' . $id_content . '/' . $this->fotoGalleryModel->id);
                    } else {
						HistoryStat($id, $id_content,'foto_gallery','Foto',lang('Foto.EditGalleryError'));
                        $flashdata = array(
                            'status' => false,
                            'msg' => lang('Foto.EditGalleryError') . '!',
                            'list' => $errors
                        );
                    }
		        }
				else {
				   $flashdata = $this->session->getFlashdata('foto');
				}	
			$gallery = $this->fotoGalleryModel->getGalleryById($id, $this->id_lang);
			$gallery['related'] = $this->fotoGalleryModel->getGalleryRelatedById($id, $this->id_lang);
			$this->breadcrumb->add(lang('Foto.GalleryEdit') . (!empty($gallery['lang'][$this->id_lang]['name']) ? ': ' . $gallery['lang'][$this->id_lang]['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/foto/gallery-edit/' . $id_content . '/' . $id);
			$pages=$this->fotoCategoryModel->getCategoryStructure($this->id_lang, 0, array());
			$breadcrumb = $this->breadcrumb->render();
		  echo view('Modules\Foto\Views\admin\edit-gallery',array('action' => $action, 'id_content' => $id_content,'gallery'=>$gallery,'id_lang'=>$this->id_lang,'page'=>$page,'pages' => $pages, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
		break;
		
	 }	
	}
	
	public function pageContent($id_content, $slug='') 
    {
        helper('filesystem');
		switch($slug) {
			case 'selected_gallery':
			$templates = get_templates_by_dir('modules/Foto/Views/user/list');
			$pages=$this->fotoCategoryModel->getCategoryStructure($this->id_lang, 0, array());
			return array(
                    'pc_templates' => $templates,
					'pages'=>$pages,
                    'form_view' => 'Modules\Foto\Views\admin\selected_gallery_config'
                );
                break;
			
			break;
			case 'rzeszow':
			$templates = get_templates_by_dir('modules/Foto/Views/user/home');
			$category_lists=$this->fotoCategoryModel->getCategoryStructure($this->id_lang, 0);
			return array(
                    'pc_templates' => $templates,
					'categories'=>$category_lists,
                    'form_view' => 'Modules\Foto\Views\admin\rzeszow_config'
                );
            break;
			break;
			case 'photos':
			$get = $this->request->getGet();
			$query = $this->fotoModel
             ->join('foto_files_lang fl', 'foto_files.id=fl.id_file')
			  ->join('users u', 'foto_files.id_user=u.id','left')
            ->select('foto_files.id,foto_files.path,fl.name,foto_files.publish,foto_files.home,fl.views,foto_files.id_user,u.nick as user_name,foto_files.id_category,foto_files.created_at')
            ->where('foto_files.id_page_cont', $id_content)
            ->where('fl.id_lang', $this->id_lang);
			
			if(!empty($get)) {
			      foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->groupStart();
									$query->like('fl.name', $value);
									$query->orLike('u.nick', $value);
									$query->orLike('u.mail', $value);
									$query->groupEnd();
                                }
                                break;
							case 'id_category': 
                                if(!empty($value)) {
                                    $query->Where('foto_files.id_category', $value);
                                }
                            break;	
                            case 'date': 
                                if(!empty($value)) {
                                    $date_range = explode('-', $value);
                                    if(!empty($date_range[0])) {
                                        $query->where('foto_files.created_at>=', date('Y-m-d', strtotime($date_range[0])));
                                    }
                                    if(!empty($date_range[1])) {
                                        $query->where('foto_files.created_at<=', date('Y-m-d', strtotime($date_range[1])));
                                    }
                                }
                                break;
                            case 'publish': 
                                if(in_array($value, array(0,1))) {
                                    $query->where('foto_files.publish', $value);
                                }
                                break;
                            case 'home':
                                if(in_array($value, array(0,1))) {
                                    $query->where('foto_files.home', $value);
                                }
                                break;
                        }
                    }
			}
			
			
			
			
			
			if(empty($get['order'])) {
				 $get['order_array'] = array();
				 $query->orderBy('foto_files.created_at','DESC');
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
                        case 'date': $query->orderBy('foto_files.created_at', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
						 case 'user': $query->orderBy('u.nick', $tmp[1] == 'asc' ? 'ASC' : 'DESC');$query->orderBy('u.mail', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;	
						 case 'views': $query->orderBy('views', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;	
                    }
                }
			
			
			
			$files_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
			if(!empty($files_list )) {
			  foreach($files_list as $k=>$v) {	
				  $user=$this->fotoModel->db->table('users')->select('id,name,nick,mail')->Where('id',$v['id_user'])->OrderBy('mail', 'ASC')->get()->getRowArray();
			  }	
			}	
			$templates = get_templates_by_dir('modules/Foto/Views/user/files');
			$on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
				$flashdata = $this->session->getFlashdata('foto');
			  $category_lists=$this->fotoCategoryModel->getCategoryStructure($this->id_lang, 0);
                return array(
                    'categorylists' => $category_lists,
					'files_list'=>$files_list,
                    'pc_templates' => $templates,
					'order_list' => '',
					'filters' => $get,
					'pager' => $this->fotoModel->pager,
					'flashdata'=>$flashdata,
					'on_page_list'=>$on_page_list,
					'list_view' => 'Modules\Foto\Views\admin\files_list',
                    'form_view' => 'Modules\Foto\Views\admin\files_config'
                );
			break;
            case 'categories':
			  $templates = get_templates_by_dir('modules/Foto/Views/user/categories');
			  $category_lists=$this->fotoCategoryModel->getCategoryStructure($this->id_lang, 0);
                return array(
                    'lists' => $category_lists,
                    'pc_templates' => $templates,
					'list_view' => 'Modules\Foto\Views\admin\categories_list',
                    'form_view' => 'Modules\Foto\Views\admin\categories_config'
                );
			
			break;
			case 'gallery':
			 $get = $this->request->getGet();
             $query = $this->fotoGalleryModel
             ->join('foto_gallery_lang fl', 'foto_gallery.id=fl.id_gallery')
			 ->join('users u', 'foto_gallery.id_user=u.id','left')
            ->select('foto_gallery.id,foto_gallery.publish,foto_gallery.home,foto_gallery.created_at,foto_gallery.number_of_photo,fl.views,fl.name,foto_gallery.id_user,u.nick as user_name,foto_gallery.investments')
            ->where('foto_gallery.id_page_cont', $id_content)
            ->where('fl.id_lang', $this->id_lang);
			if(!empty($get)) {
			      foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->groupStart();
									$query->like('fl.name', $value);
									$query->orLike('u.nick', $value);
									$query->orLike('u.mail', $value);
									$query->groupEnd();
                                }
                                break;
							case 'id_category': 
                                if(!empty($value)) {
                                    $query->Where('foto_gallery.id_category', $value);
                                }
                            break;	
                            case 'date': 
                                if(!empty($value)) {
                                    $date_range = explode('-', $value);
                                    if(!empty($date_range[0])) {
                                        $query->where('foto_gallery.created_at>=', date('Y-m-d', strtotime($date_range[0])));
                                    }
                                    if(!empty($date_range[1])) {
                                        $query->where('foto_gallery.created_at<=', date('Y-m-d', strtotime($date_range[1])));
                                    }
                                }
                                break;
                            case 'publish': 
                                if(in_array($value, array(0,1))) {
                                    $query->where('foto_gallery.publish', $value);
                                }
                                break;
                            case 'home':
                                if(in_array($value, array(0,1))) {
                                    $query->where('foto_gallery.home', $value);
                                }
                                break;
							 case 'invest':
                                if(in_array($value, array(0,1))) {
                                    $query->where('foto_gallery.investments', $value);
                                }
                                break;	
                        }
                    }
			}
			if(empty($get['order'])) {
				 $get['order_array'] = array();
				 $query->orderBy('created_at','DESC');
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
						case 'investments': $query->orderBy('investments', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;	
                        case 'date': $query->orderBy('created_at', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
						 case 'user': $query->orderBy('u.nick', $tmp[1] == 'asc' ? 'ASC' : 'DESC');$query->orderBy('u.mail', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;	
						case 'photos': $query->orderBy('number_of_photo', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
						 case 'views': $query->orderBy('views', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;	
                    }
                }

			$gallery_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
			if(!empty($gallery_list)) {
			  foreach($gallery_list as $k=>$v) {	
				$gallery_list[$k]['photo']=$this->fotoGalleryModel->db->table('foto_gallery_files')->select('path')->where('id_gallery', $v['id'])->OrderBy('main', 'DESC')->get()->getRowArray();	
				
			  }
			}
			
			 $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
			 $category_lists=$this->fotoCategoryModel->getCategoryStructure($this->id_lang, 0);
			  $templates = get_templates_by_dir('modules/Foto/Views/user/gallery');
                return array(
				    'gallery_list'=>$gallery_list,
                    'pc_templates' => $templates,
					'categorylists'=>$category_lists,
					'filters' => $get,
					'pager' => $this->fotoGalleryModel->pager,
                    'order_list' => '',
					'on_page_list'=>$on_page_list,
					'list_view' => 'Modules\Foto\Views\admin\gallery_list',
                    'form_view' => 'Modules\Foto\Views\admin\gallery_config'
                );
			
			break;
		}		
	}

 public function assets($action='') {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
		$assets['css_footer'][] = '/adm/third-party/tags/jquery.tagsinput.css';
	    $assets['js'][] = '/adm/third-party/tags/jquery.tagsinput.js';
        $assets['js'][] = '/adm/js/foto.js';
		
	switch ($action) {
      case 'gallery-add':
      case 'photo-add':	  
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
	}	
		
		
		
		
		
		
        return $assets;
    }	
	
	
	public function ajax($action = '', $id = 0, $id_content = 0) {
         $response = array(
            'status' => false
        );
        $post = $this->request->getPost();
		$linkClass = new Link();
		switch($action) {
		  case 'SaveOrderCategory':
			parse_str($post['data'], $params);
            $r=$this->fotoCategoryModel->saveCategoryOrder($params,$id);
			$response = array(
            'status' => true,
            'result' => $r,
            'msg' => $r ? lang('News.OrderChanged') : lang('News.Error')
        );
          break;		  
		  case 'categorylink':
            $lang_links = array();
			$cat=$this->fotoCategoryModel->getCategoryLang($post['id_page']);
               if (!empty($post['lang_links'])) {
                  foreach ($post['lang_links'] as $l) {
					 if(!empty($l['name'])) {
						$l['name']='/'.$linkClass->generateLink($l['name'],$l['id_lang']); 
					 }
					 $link = $linkClass->checkLink($cat[$l['id_lang']]['link'].$l['name'],$l['id_lang'],$cat[$l['id_lang']]['id_link']);
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
		   case 'categorylinkcheck':
            $lang_links = array();
			$cat=$this->fotoCategoryModel->getCategoryLang($post['re_id']);
			if (!empty($post['lang_links'])) {
                  foreach ($post['lang_links'] as $l) {
					 if(!empty($cat)) {
						  if(!empty($l['name'])) {
								$l['name']='/'.$linkClass->generateLink($l['name'],$l['id_lang']); 
						  }
						$link = $linkClass->checkLink($cat[$l['id_lang']]['link'].$l['name'],$l['id_lang'],$cat[$l['id_lang']]['id_link']);
					 }  
					 else {
						 $page_info = $this->fotoCategoryModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->select('pl.id_link')->where('p.id', $post['id_page'])->where('pl.id_lang', $l['id_lang'])->get()->getRowArray();	
						 $l['name']=$linkClass->generateLink($l['name'],$l['id_lang']); 
						 $link = $linkClass->checkLink($linkClass->getLink($page_info['id_link'],$l['id_lang']).'/'.$l['name'],$l['id_lang'],'');
					 }	 
					   $error = '';
                        if(!empty($linkClass->url_conflict)) {
                            $error = str_replace('{url}', '/' . $linkClass->url_conflict, lang('Admin.links.DirectLinkConflict'));
                        } elseif(!empty($linkClass->redirect_conflict)) {
                            $error = str_replace('{url}', '/' . $linkClass->redirect_conflict, lang('Redirects.RedirectConflict'));
                        }
                        $lang_links = array(
                            'id_lang' => $l['id_lang'],
                            'link' => $link,
                            'error' => $error
                        );  
				  }	  
               }
			$response = array(
                    'status' => true,
                    'link' => $lang_links['link']
                );
          break;
		  case 'publish-category': 
                return $this->publishCategory($id);
          break;
		  case 'delete-category': 
                return $this->deleteCategory($id);
          break;
		  case 'galleryhome': 
               return $this->homeGallery($id);
            break;
          case 'gallerypublish': 
               return $this->publishGallery($id);
            break;
			 case 'galleryinvest': 
               return $this->investGallery($id);
            break;
		  case 'gallerydelete': 
                 return $this->deleteGallery($id);
          break;
		    case 'filehome': 
               return $this->homeFile($id);
            break;
          case 'filepublish': 
               return $this->publishFile($id);
            break;
	      case 'filecat':
		    if(empty($post['cat'])) {$post['cat']=0;}
		    return $this->changeCatFile($id,$post['cat']);
          break;		  
		  case 'filedelete': 
                 return $this->deleteFile($id);
          break;	
		  case 'related-gallery-save':
                    return $this->relatedGallerySave($id);
          break;
		  case 'add-related-gallery':
             return $this->relatedGalleryModal($id);
          break;		  
		}	
     return $this->response->setJSON($response);
	}

    private function publishCategory($id) 
    {
        $cat = $this->fotoCategoryModel->select('id,publish')->where('id', $id)->first();
        if(!empty($cat)) {	
            $r = $this->fotoCategoryModel->where('id', $id)->set(array('publish'=>$cat['publish'] ? 0 : 1))->update();
            $response = array(
                'status' => $r,
                'publish' => $cat['publish'] ? 0 : 1,
                'msg' => $cat['publish'] ? lang('Foto.CategoryRepublished') : lang('Foto.CategoryPublished')
            );
			HistoryStat($id,'','foto_category','Foto',$cat['publish'] ? lang('Foto.CategoryRepublished') : lang('Foto.CategoryPublished'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $cat['publish'],
                'msg' => lang('Foto.Error')
            );
        }
        return $this->response->setJSON($response);
    }	
	
	   private function deleteCategory($id) 
    {
        $result = $this->fotoCategoryModel->deleteCategory($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Foto.CategoryRemoved') : lang('Foto.Error')
        ));
		HistoryStat($id,'','foto_category','Foto',$result ? lang('Foto.CategoryRemoved') : lang('Foto.Error'));
    }
	
	private function homeGallery($id) 
    {
        $news = $this->fotoGalleryModel->select('id,home')->where('id', $id)->first();
        if(!empty($news)) {
            $r = $this->fotoGalleryModel->where('id', $id)->set('home', $news['home'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $news['home'] ? 0 : 1,
                'msg' => $news['home'] ? lang('News.TurnOff') : lang('News.TurnOn')
            );
			HistoryStat($id,'','foto_gallery','Foto',$news['home'] ? lang('News.TurnOff') : lang('News.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $news['home'],
                'msg' => lang('News.Error')
            );
        }
        return $this->response->setJSON($response);
    }
	
	
	 private function publishGallery($id) 
    {
        $news = $this->fotoGalleryModel->select('id,publish')->where('id', $id)->first();
        if(!empty($news)) {	
            $r = $this->fotoGalleryModel->where('id', $id)->set('publish', $news['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $news['publish'] ? 0 : 1,
                'msg' => $news['publish'] ? lang('News.Republished') : lang('News.Published')
            );
			HistoryStat($id,'','foto_gallery','Foto',$news['publish'] ? lang('News.Republished') : lang('News.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $news['publish'],
                'msg' => lang('News.Error')
            );
        }
        return $this->response->setJSON($response);
    }
	
	private function homeFile($id) 
    {
        $news = $this->fotoModel->select('id,home')->where('id', $id)->first();
        if(!empty($news)) {
            $r = $this->fotoModel->where('id', $id)->set('home', $news['home'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $news['home'] ? 0 : 1,
                'msg' => $news['home'] ? lang('News.TurnOff') : lang('News.TurnOn')
            );
			HistoryStat($id,'','foto_files','Foto',$news['home'] ? lang('News.TurnOff') : lang('News.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $news['home'],
                'msg' => lang('News.Error')
            );
        }
        return $this->response->setJSON($response);
    }
	
	
	 private function publishFile($id) 
    {
        $news = $this->fotoModel->select('id,publish')->where('id', $id)->first();
        if(!empty($news)) {	
            $r = $this->fotoModel->where('id', $id)->set('publish', $news['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $news['publish'] ? 0 : 1,
                'msg' => $news['publish'] ? lang('News.Republished') : lang('News.Published')
            );
			HistoryStat($id,'','foto_files','Foto',$news['publish'] ? lang('News.Republished') : lang('News.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $news['publish'],
                'msg' => lang('News.Error')
            );
        }
        return $this->response->setJSON($response);
    }
	
	private function changeCatFile($id,$cat) {
		
		
		$news = $this->fotoModel->select('id')->where('id', $id)->first();
        if(!empty($news)) {	
            $r = $this->fotoModel->where('id', $id)->set('id_category', $cat)->update();
            $response = array(
                'status' => $r,
                'msg' => lang('Foto.CategoryChanged')
            );
			HistoryStat($id,'','foto_files','Foto',lang('Foto.CategoryChanged').' '.$cat);
        } else {
            $response = array(
                'status' => true,
                'msg' => lang('News.Error')
            );
        }
		
	return $this->response->setJSON($response);
	
	}
	
		 private function investGallery($id) 
    {
        $news = $this->fotoGalleryModel->select('id,investments')->where('id', $id)->first();
        if(!empty($news)) {	
            $r = $this->fotoGalleryModel->where('id', $id)->set('investments', $news['investments'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $news['investments'] ? 0 : 1,
                'msg' => $news['investments'] ? lang('Foto.InvestTurnOff') : lang('Foto.InvestTurnOn')
            );
			HistoryStat($id,'','foto_gallery','Foto',$news['investments'] ? lang('Foto.InvestTurnOff') : lang('Foto.InvestTurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $news['investments'],
                'msg' => lang('News.Error')
            );
        }
        return $this->response->setJSON($response);
    }
	
	 private function deleteGallery($id) 
    {
        $result = $this->fotoGalleryModel->deleteGallery($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('News.Removed') : lang('News.Error')
        ));
		HistoryStat($id,'','foto_gallery','Foto',$result ? lang('News.Removed') : lang('News.Error'));
    }
	
	 private function deleteFile($id) 
    {
        $result = $this->fotoModel->deleteFile($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('News.Removed') : lang('News.Error')
        ));
		HistoryStat($id,'','foto_files','Foto',$result ? lang('News.Removed') : lang('News.Error'));
    }
	
	 private function relatedGalleryModal($id_product) {
		$post = $this->request->getPost();
        if(!empty($post['data'])) {
			parse_str($post['data'],$data);
			 $ids = array();
			if(!empty($post['selected'])) {
				parse_str($post['selected'],$selected);
				if(!empty($selected['related'])) {
				   foreach($selected['related'] as $rel) {
					   $ids[]=$rel;
				   }	
				}	
			}	
            $query = $this->fotoModel->db->table('foto_gallery g')
                    ->join('foto_gallery_lang pl', 'g.id=pl.id_gallery')
                    ->join('foto_gallery_files pf', 'pf.id_gallery=pl.id_gallery', 'left')
                    ->select('g.id,pl.name,pf.path,g.created_at')
                    ->where('pl.id_lang', $this->id_lang)
                    ->where('g.id!=', $id_product)
					->where('pf.main',1);
            if(!empty($ids)) {
                $query->whereNotIn('g.id', $ids);
            }
            foreach ($data as $name => $value) {
                switch ($name) {
                    case 'name':
                        if (!empty($value)) {
                            $query->groupStart();
                            $query->like('pl.name', $value);
							$query->orLike('pl.keywords', $value);
							$query->orLike('g.id', $value);
                            $query->groupEnd();
                        }
                        break;
                    case 'publish':
                        if (in_array($value, array(0, 1))) {
                            $query->where('g.publish', $value);
                        }
                        break;
                    case 'home':
                        if (in_array($value, array(0, 1))) {
                            $query->where('gy.home', $value);
                        }
                        break;
                    case 'investments':
                        if (in_array($value, array(0, 1))) {
                            $query->where('g.investments', $value);
                        }
                        break;
                }
            }
            $query->orderBy('g.created_at', 'DESC');
            $products = $query->limit(50)->get()->getResultArray();
            $html = view('Modules\Foto\Views\admin\related_gallery_list', array('id_product' => $id_product, 'products' => $products, 'languages' => $this->languages, 'locale' => $this->locale));
            return $this->response->setJSON(array(
                'status' => true,
                'html' => base64_encode(urlencode($html))
            ));
        } else {
            $html = view('Modules\Foto\Views\admin\related_gallery_modal', array('id_product' => $id_product,  'languages' => $this->languages, 'locale' => $this->locale));
            return $this->response->setJSON(array(
                'status' => true,
                'html' => base64_encode(urlencode($html))
            ));
        }
    }
	
	private function relatedGallerySave($id_product) {
		$post = $this->request->getPost();
        $html = "";
        $result = false;
        $related_products = array();
        if (!empty($post) && !empty($post['related'])) {
	       foreach($post['related'] as $rel) {
	   $query = $this->fotoModel->db->table('foto_gallery g')
                    ->join('foto_gallery_lang pl', 'g.id=pl.id_gallery')
                    ->join('foto_gallery_files pf', 'pf.id_gallery=pl.id_gallery', 'left')
                    ->select('g.id,pl.name,pf.path,g.created_at,g.id_page_cont')
                    ->where('pl.id_lang', $this->id_lang)
                    ->where('g.id=', $rel)
					->where('pf.main',1);
            $product = $query->get()->getRowArray();
	        $html.=view('Modules\Foto\Views\admin\related_gallery_list_item_save', array('product' => $product));
	       }
	    }
        
        return $this->response->setJSON(array(
		    'result'=>true,
			'msg'=>lang('Foto.RelatedAdded'),
            'status' => true,
            'html' => base64_encode(urlencode($html))
        ));
    }
	
	
}	
?>