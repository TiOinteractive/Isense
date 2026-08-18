<?php

namespace Modules\Flavors\Controllers;
use App\Controllers\BaseController;
use Modules\Flavors\Models\FlavorsCategoryModel;
use Modules\Flavors\Models\FlavorsParametersModel;
use Modules\Flavors\Models\FlavorsCuisineModel;
use Modules\Flavors\Models\FlavorsRestaurantsModel;
use Modules\Flavors\Models\FlavorsGradesModel;
use Modules\Flavors\Models\FlavorsCommentsModel;
use Modules\Flavors\Models\FlavorsTagsModel;
use App\Libraries\Breadcrumb;
use App\Libraries\Link;


class FlavorsAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->flavorsCategoryModel = new FlavorsCategoryModel();
		$this->flavorsParametersModel = new FlavorsParametersModel();
		$this->flavorsCuisineModel = new FlavorsCuisineModel();
		$this->flavorsRestaurantsModel = new FlavorsRestaurantsModel();
		$this->flavorsGradesModel = new FlavorsGradesModel();
		$this->flavorsCommentsModel = new FlavorsCommentsModel();
		$this->flavorsTagsModel = new FlavorsTagsModel();
    }
    
    public function index($action='', $id=0) 
    {
		
	  helper('text');	
	  $this->breadcrumb = new Breadcrumb();	
	  $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
      $this->breadcrumb->add(lang('Flavors.RestaurantsList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors');    
      $parameter = array();
	  $cuisine=array();
	  $category=array();
	  $restaurant=array();
	  if(empty($action)) {$action='restaurants';}
	  
	  switch ($action) {	
	     case 'comments':
		 $flashdata = $this->session->getFlashdata('flavors');
		 $this->breadcrumb->add(lang('Flavors.CommentsList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/comments');
		 $breadcrumb = $this->breadcrumb->render();
		 $get = $this->request->getGet();
		 $on_page_list = array(20 => 20,40 => 40,80 => 80);
		 $query = $this->flavorsCommentsModel->select('flavors_comments.id,nick,flavors_comments.comment,name,flavors_comments.created_at,ip,link,publish,status')
		 ->join('flavors_restaurant_lang pl', 'flavors_comments.id_restaurant = pl.id_restaurant')
		 ->join('links', 'links.id = pl.id_link')
		 ->where('pl.id_lang',$this->id_lang);
		 if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('name', $value);
									$query->orlike('nick', $value);
                                }
                                break;
                        }
                    }
        }
				if (empty($get['order'])) {
					$get['order']='id,desc';
                }
                switch ($get['order']) {
                    case 'date,asc': $query->orderBy('flavors_comments.created_at', 'ASC');
                        break;
                    case 'date,desc': $query->orderBy('flavors_comments.created_at', 'DESC');
                        break;
                    case 'id,asc': $query->orderBy('flavors_comments.id', 'ASC');
                        break;
                    case 'id,desc': $query->orderBy('flavors_comments.id', 'DESC');
                        break;						
                     case 'nick,asc': $query->orderBy('flavors_comments.nick', 'ASC');
                        break;
                    case 'nick,desc': $query->orderBy('flavors_comments.nick', 'DESC');
                        break;
					case 'publish,asc': $query->orderBy('flavors_comments.publish', 'ASC');
                        break;
                    case 'publish,desc': $query->orderBy('flavors_comments.publish', 'DESC');
                        break;
                    case 'name,asc': $query->orderBy('name', 'ASC');
                        break;
                    case 'name,desc': $query->orderBy('name', 'DESC');
                    break; 						
          }
		 $comments_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
		 if(!empty($comments_list )) {
                    foreach($comments_list  as $c) {
                        if($c['status'] == 'new') {
                            $this->flavorsCommentsModel->changeCommenStatus($c['id'], 'viewed');
                        }
                    }
                }
		 echo view('Modules\Flavors\Views\admin\comments_list', array('action' => $action,'comments_list'=>$comments_list,'breadcrumbs' => $breadcrumb, 'filters' => $get, 'on_page_list' => $on_page_list, 'pager' => $this->flavorsCommentsModel->pager));
		 break;
	     case 'edit-category':
		     $category = $this->flavorsCategoryModel->getCategoryById($id, $this->id_lang);
		 case 'add-category':
		 $this->breadcrumb->add(lang('Flavors.CategoriesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/categories'); 
		   if($id) {
                $this->breadcrumb->add(lang('Flavors.CategoryEdit'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/edit-category/'.$id);
          } else {
                   $this->breadcrumb->add(lang('Flavors.AddCategory'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/add-category');
          } 
		    $post = $this->request->getPost();
                if (!empty($post)) {
					    $result = false;
						$errors = array();
						$validation = \Config\Services::validation();
								if (!empty($post['lang'])) {
									foreach ($post['lang'] as $id_lang => $lang) {
										$validation->reset();
										$check_unique = $this->flavorsParametersModel->checkParametersUnique($lang['name'], $id_lang, $action);
										$lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
										$data = array();
										$validation->setRules([
											'name' => [
												'rules' => 'required',
												'errors' => [
													'required' => $lang_name . lang('Flavors.ParametersNameError')
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
								}
								if (empty($errors)) {
									$result = $this->flavorsCategoryModel->saveCategory($id, $post);
								}
								if ($result) {
									$this->session->setFlashdata('flavors', array(
										'status' => true,
										'msg' => ($id ? lang('Flavors.CategoryEditSuccess') : lang('Flavors.CategoryAddSuccess')) . '!'
									));
									HistoryStat($id, '', 'flavors_categories', 'Flavors', $id ? lang('Flavors.CategoryEditSuccess') : lang('Flavors.CategoryAddSuccess'));
										return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/edit-category/' . $this->flavorsCategoryModel->id);
								} else {
									$flashdata = array(
										'status' => false,
										'msg' => ($id ? lang('Flavors.CategoryEditError') : lang('Flavors.CategoryAddError')) . '!',
										'list' => $errors
									);
								}
								$category = $post;
								$category['id'] = $id;
		        }
				else {
					$flashdata = $this->session->getFlashdata('flavors');
				}
				$breadcrumb = $this->breadcrumb->render();
		   		$direct_links = array();
                $links = $this->flavorsCategoryModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_restaurant')->get()->getResultArray();
                if(!empty($links)) {
                    foreach($links as $l) {
                        $direct_links[$l['id_lang']] = $l['value'];
                    }
                }
                if(!empty($this->languages)) {
                    foreach($this->languages as $lang) {
                        if(empty($direct_links[$lang['id']])) {
                            $direct_links[$lang['id']] = 'movie';
                        }
                    }
                }
		   $pages=$this->flavorsCategoryModel->getCategoryStructure($this->id_lang, 0, array($id));
		   echo view('Modules\Flavors\Views\admin\add-category', array('action' => $action,'direct_links'=>$direct_links,'category'=>$category,'pages'=> $pages, 'id_lang'=>$this->id_lang, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
	     break;
		 case 'categories':
		   $flashdata = $this->session->getFlashdata('flavors');
		   $this->breadcrumb->add(lang('Flavors.CategoriesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/categories'); 
		   $breadcrumb = $this->breadcrumb->render();
		   $categories=$this->flavorsCategoryModel->getCategoryStructure($this->id_lang, 0, array($id));
		   echo view('Modules\Flavors\Views\admin\categories', array('action' => $action, 'categories' => $categories, 'breadcrumbs' => $breadcrumb));
		 break;
	     case 'edit-parameter':
		     $parameter = $this->flavorsParametersModel->getParameterById($id, $this->id_lang);
		 case 'add-parameter':
		   $this->breadcrumb->add(lang('Flavors.ParametersList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/parameters');   
		   if($id) {
                $this->breadcrumb->add(lang('Flavors.ParameterEdit'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/edit-parameter/'.$id);
          } else {
                   $this->breadcrumb->add(lang('Flavors.AddParameter'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/add-parameter');
          } 
		    $post = $this->request->getPost();
                if (!empty($post)) {
					   $result = false;
						$errors = array();
						$validation = \Config\Services::validation();
								if (!empty($post['lang'])) {
									foreach ($post['lang'] as $id_lang => $lang) {
										$validation->reset();
										$check_unique = $this->flavorsParametersModel->checkParametersUnique($lang['name'], $id_lang, $action);
										$lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
										$data = array();
										$validation->setRules([
											'name' => [
												'rules' => 'required|check_unique[' . $check_unique . ']',
												'errors' => [
													'required' => $lang_name . lang('Flavors.ParametersNameError'),
													'check_unique' => $lang_name . lang('Flavors.ParametersNameUniqueError')
												],
											],
											'filter_name' => [
												'rules' => 'required',
												'errors' => [
													'required' => $lang_name . lang('Flavors.ParametersFilterNameError')
												],
											],
										]);
										if (!$validation->run($lang)) {
											$errors[] = array_merge($validation->getErrors());
										}
									}
								}
								if (empty($errors)) {
									$result = $this->flavorsParametersModel->saveParameter($id, $post);
								}
								if ($result) {
									$this->session->setFlashdata('flavors', array(
										'status' => true,
										'msg' => ($id ? lang('Flavors.ParameterEditSuccess') : lang('Flavors.ParameterAddSuccess')) . '!'
									));
									HistoryStat($id, '', 'flavors_parameters', 'Flavors', $id ? lang('Flavors.ParameterEditSuccess') : lang('Flavors.ParameterAddSuccess'));
										return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/edit-parameter/' . $this->flavorsParametersModel->id);
								} else {
									$flashdata = array(
										'status' => false,
										'msg' => ($id ? lang('Flavors.ParameterEditError') : lang('Flavors.ParameterAddError')) . '!',
										'list' => $errors
									);
								}
								$parameter = $post;
								$parameter['id'] = $id;
		        }
				else {
					$flashdata = $this->session->getFlashdata('flavors');
				}
		   $breadcrumb = $this->breadcrumb->render();
		   echo view('Modules\Flavors\Views\admin\add-parameter', array('action' => $action,'parameter'=>$parameter,'id_lang'=>$this->id_lang, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
		 break;
		 case 'edit-cuisine':
		     $cuisine = $this->flavorsCuisineModel->getCuisineById($id, $this->id_lang);
		 case 'add-cuisine':
		   $this->breadcrumb->add(lang('Flavors.CuisineList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/cuisine');  
		  if($id) {
                $this->breadcrumb->add(lang('Flavors.CuisineEdit'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/edit-cuisine/'.$id);
          } else {
                $this->breadcrumb->add(lang('Flavors.AddCuisine'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/add-cuisine');
          } 
		    $post = $this->request->getPost();
                if (!empty($post)) {
		   
					   $result = false;
						$errors = array();
						$validation = \Config\Services::validation();
								if (!empty($post['lang'])) {
									foreach ($post['lang'] as $id_lang => $lang) {
										$validation->reset();
										$lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
										$data = array();
										$validation->setRules([
											'name' => [
												'rules' => 'required',
												'errors' => [
													'required' => $lang_name . lang('Flavors.ParametersNameError')
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
								}
								if (empty($errors)) {
									$result = $this->flavorsCuisineModel->saveCuisine($id, $post);
								}
								if ($result) {
									$this->session->setFlashdata('flavors', array(
										'status' => true,
										'msg' => ($id ? lang('Flavors.CuisineEditSuccess') : lang('Flavors.CuisineAddSuccess')) . '!'
									));
									HistoryStat($id, '', 'flavors_cuisine', 'Flavors', $id ? lang('Flavors.CuisineEditSuccess') : lang('Flavors.CuisineAddSuccess'));
										return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/edit-cuisine/' . $this->flavorsCuisineModel->id);
								} else {
									$flashdata = array(
										'status' => false,
										'msg' => ($id ? lang('Flavors.CuisineEditError') : lang('Flavors.CuisineAddError')) . '!',
										'list' => $errors
									);
								}
								$cuisine = $post;
								$cuisine['id'] = $id;
		        }
				else {
					$flashdata = $this->session->getFlashdata('flavors');
				}
		   $breadcrumb = $this->breadcrumb->render();
		   $direct_links = array();
                $links = $this->flavorsCuisineModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_cuisine')->get()->getResultArray();
                if(!empty($links)) {
                    foreach($links as $l) {
                        $direct_links[$l['id_lang']] = $l['value'];
                    }
                }
                if(!empty($this->languages)) {
                    foreach($this->languages as $lang) {
                        if(empty($direct_links[$lang['id']])) {
                            $direct_links[$lang['id']] = 'movie';
                        }
                    }
                }
		   echo view('Modules\Flavors\Views\admin\add-cuisine', array('action' => $action,'cuisine'=>$cuisine,'direct_links'=>$direct_links,'id_lang'=>$this->id_lang, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
		 break;
		 case 'parameters':
		   $flashdata = $this->session->getFlashdata('flavors');
		   $this->breadcrumb->add(lang('Flavors.ParametersList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/parameters'); 
		   $breadcrumb = $this->breadcrumb->render();
            $get = $this->request->getGet();
            $query = $this->flavorsParametersModel->join('flavors_parameters_lang pl', 'flavors_parameters.id=pl.id_parameter')->select('flavors_parameters.id,pl.name,pl.filter_name,publish')->where('pl.id_lang', $this->id_lang);
                if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'name':
                                if (!empty($value)) {
                                    $query->groupStart();
                                    $query->like('pl.name', $value);
                                    $query->orLike('pl.filter_name', $value);
                                    $query->groupEnd();
                                }
                                break;
                        }
                    }
                }
                if (empty($get['order'])) {
                    $get['order'] = 'name,asc';
                }

                switch ($get['order']) {
                    case 'name,asc': $query->orderBy('pl.name', 'ASC');
                        break;
                    case 'name,desc': $query->orderBy('pl.name', 'DESC');
                        break;
                    case 'filtername,asc': $query->orderBy('pl.filter_name', 'ASC');
                        break;
                    case 'filtername,desc': $query->orderBy('pl.filter_name', 'DESC');
                        break;
                    case 'publish,asc': $query->orderBy('publish', 'ASC');
                        break;
                    case 'publish,desc': $query->orderBy('publish', 'DESC');
                        break;						
                    case 'id;asc': $query->orderBy('parameters.id', 'ASC');
                        break;
                }
				$on_page_list = array(20 => 20,40 => 40,80 => 80);
                $parameters = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if (!empty($parameters)) {
                    foreach ($parameters as $k => $v) {
                        $parameters[$k]['values_count'] = $this->flavorsParametersModel->db->table('flavors_parameters_value')->where('id_parameter', $v['id'])->get()->getNumRows();
                    }
                }
		   echo view('Modules\Flavors\Views\admin\parameters', array('action' => $action, 'parameters' => $parameters, 'breadcrumbs' => $breadcrumb, 'filters' => $get, 'on_page_list' => $on_page_list, 'pager' => $this->flavorsParametersModel->pager));	
		 break;
		 case 'cuisine':
		  $flashdata = $this->session->getFlashdata('flavors');
		   $this->breadcrumb->add(lang('Flavors.CuisineList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/cuisine'); 
		   $breadcrumb = $this->breadcrumb->render();
            $get = $this->request->getGet();
            $query = $this->flavorsCuisineModel->join('flavors_cuisine_lang pl', 'flavors_cuisine.id=pl.id_cuisine')->select('flavors_cuisine.id,pl.name,flavors_cuisine.publish,flavors_cuisine.menu,ico_svg')->where('pl.id_lang', $this->id_lang);
                if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'name':
                                if (!empty($value)) {
                                    $query->like('pl.name', $value);
                                }
                                break;
                        }
                    }
                }
                if (empty($get['order'])) {
                    $get['order'] = 'order;asc';
                }
                switch ($get['order']) {
                    case 'name,asc': $query->orderBy('pl.name', 'ASC');
                        break;
                    case 'name,desc': $query->orderBy('pl.name', 'DESC');
                        break;
                    case 'publish,asc': $query->orderBy('publish', 'ASC');
                        break;
                    case 'publish,desc': $query->orderBy('publish', 'DESC');
                        break;						
                    case 'id;asc': $query->orderBy('flavors_cuisine.id', 'ASC');
                        break;
					 case 'order;asc': $query->orderBy('flavors_cuisine.order', 'ASC');
                        break;	
                }
				
				$on_page_list = array(20 => 20,40 => 40,80 => 80);
				if(empty($get['on_page'])) {$get['on_page']=40;}
                $cuisine_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 40);
		   echo view('Modules\Flavors\Views\admin\cuisine_list', array('action' => $action, 'cuisine_list' => $cuisine_list, 'breadcrumbs' => $breadcrumb, 'filters' => $get, 'on_page_list' => $on_page_list, 'pager' => $this->flavorsCuisineModel->pager)); 
		 break;
		 case 'edit-restaurant':
		  $restaurant = $this->flavorsRestaurantsModel->getRestaurantById($id, $this->id_lang);
		 case 'add-restaurant':
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
                                    'required' => $lang_name . lang('Flavors.RestaurantNameError')
                                ],
                            ],
                            'link' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Flavors.RestaurantDirectLinkError')
                                ],
                            ],
                        ]);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
            }
		    if(empty($errors)) {
                $result = $this->flavorsRestaurantsModel->saveRestaurant($id, $post);
            }
		    if($result) {
                        $this->session->setFlashdata('flavors', array(
                            'status' => true,
                            'msg' => ($id ? lang('Flavors.RestaurantEditSuccess') : lang('Flavors.RestaurantAddSuccess')) . '!'
                        ));
						HistoryStat($id, 0,'flavors_restaurant','Flavors',$id ? lang('Flavors.RestaurantEditSuccess') : lang('Flavors.RestaurantAddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/edit-restaurant/' .$this->flavorsRestaurantsModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Flavors.RestaurantEditError') : lang('Flavors.RestaurantAddError')) . '!',
                            'list' => $errors
                        );
                    }
            $restaurant = $post;
            $restaurant['id'] = $id;
		  }
		  else {
			$flashdata = $this->session->getFlashdata('flavors'); 
	      }		  
		  if($id) {
                $this->breadcrumb->add(lang('Flavors.RestaurantEdit'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/edit-restaurant/'.$id);
          } else {
                $this->breadcrumb->add(lang('Flavors.AddRestaurant'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/add-restaurant');
          } 
		   $breadcrumb = $this->breadcrumb->render();
		   $direct_links = array();
                $links = $this->flavorsCuisineModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_restaurant')->get()->getResultArray();
                if(!empty($links)) {
                    foreach($links as $l) {
                        $direct_links[$l['id_lang']] = $l['value'];
                    }
                }
                if(!empty($this->languages)) {
                    foreach($this->languages as $lang) {
                        if(empty($direct_links[$lang['id']])) {
                            $direct_links[$lang['id']] = 'movie';
                        }
                    }
                }
		   $parameters=$this->flavorsParametersModel->GetParametersList($this->id_lang); 				
		   $pages=$this->flavorsCategoryModel->getCategoryStructure($this->id_lang, 0, array($id));		
		   echo view('Modules\Flavors\Views\admin\add-restaurant', array('action' => $action,'restaurant'=>$restaurant,'pages'=>$pages,'parameters'=>$parameters,'direct_links'=>$direct_links,'id_lang'=>$this->id_lang, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb)); 
		 break;
		 case 'grades':
		 $flashdata = $this->session->getFlashdata('flavors');
		 $this->breadcrumb->add(lang('Flavors.RatingList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/grades');
		 $breadcrumb = $this->breadcrumb->render();
		 $get = $this->request->getGet();
		 $on_page_list = array(20 => 20,40 => 40,80 => 80);
		 $query = $this->flavorsGradesModel->select('flavors_rating.id,AVG(rating) as avg_rating,link,name,user,id_user,flavors_rating.id_restaurant,flavors_rating.created_at')
		 ->join('flavors_restaurant_lang pl', 'flavors_rating.id_restaurant = pl.id_restaurant')
		 ->join('links', 'links.id = pl.id_link')
		 ->where('pl.id_lang',$this->id_lang)
		 ->GroupBy('id_restaurant')->GroupBy('id_user');
		 if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('name', $value);
									$query->orlike('user', $value);
                                }
                                break;
                        }
                    }
        }
				if (empty($get['order'])) {
					$get['order']='id,desc';
                }
                switch ($get['order']) {
                    case 'date,asc': $query->orderBy('flavors_rating.created_at', 'ASC');
                        break;
                    case 'date,desc': $query->orderBy('flavors_rating.created_at', 'DESC');
                        break;
                    case 'id,asc': $query->orderBy('flavors_rating.id', 'ASC');
                        break;
                    case 'id,desc': $query->orderBy('flavors_rating.id', 'DESC');
                        break;						
                    case 'rating,asc': $query->orderBy('avg_rating', 'ASC');
                        break;
                    case 'rating,desc': $query->orderBy('avg_rating', 'DESC');
                        break;	
          }
		 $rating_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
		 if(!empty($rating_list)) {
			foreach($rating_list as $k=>$v) { 
				$rating_list[$k]['type_1']=$this->flavorsGradesModel->db->table('flavors_rating')->select('rating')->where('id_user',$v['id_user'])->where('id_restaurant',$v['id_restaurant'])->where('type',1)->get()->getRowArray();
				$rating_list[$k]['type_2']=$this->flavorsGradesModel->db->table('flavors_rating')->select('rating')->where('id_user',$v['id_user'])->where('id_restaurant',$v['id_restaurant'])->where('type',2)->get()->getRowArray();
				$rating_list[$k]['type_3']=$this->flavorsGradesModel->db->table('flavors_rating')->select('rating')->where('id_user',$v['id_user'])->where('id_restaurant',$v['id_restaurant'])->where('type',3)->get()->getRowArray(); 
				$rating_list[$k]['type_4']=$this->flavorsGradesModel->db->table('flavors_rating')->select('rating')->where('id_user',$v['id_user'])->where('id_restaurant',$v['id_restaurant'])->where('type',4)->get()->getRowArray(); 
			}
		 }	 
		 echo view('Modules\Flavors\Views\admin\rating_list', array('action' => $action,'rating_list'=>$rating_list,'breadcrumbs' => $breadcrumb, 'filters' => $get, 'on_page_list' => $on_page_list, 'pager' => $this->flavorsGradesModel->pager));
		 break;
		 case 'tags':
		 $flashdata = $this->session->getFlashdata('flavors');
		 $this->breadcrumb->add(lang('Flavors.TagsList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/flavors/tags');
		 $breadcrumb = $this->breadcrumb->render();
		 $get = $this->request->getGet();
		 $on_page_list = array(20 => 20,40 => 40,80 => 80);
		 $query = $this->flavorsTagsModel->select('id,value,created_at')
		 ->where('id_lang',$this->id_lang);
		 if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('value', $value);
                                }
                                break;
                        }
                    }
        }
				if (empty($get['order'])) {
					$get['order']='id,desc';
                }
                switch ($get['order']) {
                    case 'value,asc': $query->orderBy('value', 'ASC');
                        break;
                    case 'value,desc': $query->orderBy('value', 'DESC');
                        break;
					case 'date,asc': $query->orderBy('created_at', 'ASC');
                        break;
                    case 'date,desc': $query->orderBy('created_at', 'DESC');
                        break;
          }
		 $tags_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
		 echo view('Modules\Flavors\Views\admin\tags_list', array('action' => $action,'tags_list'=>$tags_list,'breadcrumbs' => $breadcrumb, 'filters' => $get, 'on_page_list' => $on_page_list, 'pager' => $this->flavorsTagsModel->pager));
		 break;
		 case 'restaurants':
		 $flashdata = $this->session->getFlashdata('flavors');
		 $breadcrumb = $this->breadcrumb->render();
         $get = $this->request->getGet();
		 $on_page_list = array(20 => 20,40 => 40,80 => 80);
		 $pages=$this->flavorsCategoryModel->getCategoryStructure($this->id_lang, 0, array());
        $query = $this->flavorsRestaurantsModel
         ->join('flavors_restaurant_lang rl', 'flavors_restaurant.id=rl.id_restaurant')
         ->select('flavors_restaurant.id,flavors_restaurant.publish,flavors_restaurant.recommended,flavors_restaurant.awarded,flavors_restaurant.archives,rl.name,rl.views,rl.address,rl.city,flavors_restaurant.created_at')
         ->where('rl.id_lang', $this->id_lang);
		if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('rl.name', $value);
                                }
                                break;
                            case 'publish': 
                                if(in_array($value, array(0,1))) {
                                    $query->where('flavors_restaurant.publish', $value);
																		echo 'test';
                                }
                                break;
                            case 'awarded':
                                if(in_array($value, array(0,1))) {
                                    $query->where('flavors_restaurant.awarded', $value);
																		echo 'test';
                                }
                            break;
							case 'recommended':
                                if(in_array($value, array(0,1))) {
                                    $query->where('flavors_restaurant.recommended', $value);
																		echo 'test';
                                }
                            break;
							case 'archive':
                                if(in_array($value, array(0,1))) {
                                    $query->where('flavors_restaurant.archives', $value);
																		echo 'test';
                                }
                            break;	
							case 'id_category': 
                                if(!empty($value)) {
                                    $query->where('flavors_restaurant.id_category', $value);
                                }
                            break;
                        }
                    }
        }
		else {
			$query->where('flavors_restaurant.archives',0);
		}	
		 
		 if(empty($get['order'])) {
				 $get['order_array'] = array();
				 $query->orderBy('flavors_restaurant.created_at','DESC');
				}
                if(!empty($get['order'])) {
                    $tmp = explode(',', $get['order']);
                    $get['order_array'][$tmp[0]] = $tmp[1] == 'asc' ? 'asc' : 'desc';
                    switch ($tmp[0]) {
                        case 'name': $query->orderBy('name', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'views': $query->orderBy('views', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'awarded': $query->orderBy('awarded', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'recommended': $query->orderBy('recommended', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'archives': $query->orderBy('archives', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
						case 'date': $query->orderBy('created_at', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                    }
                }
		 
		 $restaurants_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
		 if(!empty($restaurants_list)) {
			foreach($restaurants_list as $k=>$v) { 
			   $restaurants_list[$k]['photo']=$this->flavorsRestaurantsModel->db->table('flavors_restaurant_files')->select('path')->where('field', 'photo')->where('id_restaurant',$v['id'])->get()->getRowArray();
			} 
		 }	 
		   echo view('Modules\Flavors\Views\admin\restaurants_list', array('action' => $action,'list'=>$restaurants_list, 'pages'=>$pages,'breadcrumbs' => $breadcrumb, 'filters' => $get, 'on_page_list' => $on_page_list, 'pager' => $this->flavorsRestaurantsModel->pager));
		 break;
	  }
    }
	
	   public function assets($action = '') {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css';
        $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css';
        $assets['js'][] = 'https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js';
        $assets['js'][] = 'https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js';
        $assets['js'][] = 'https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js';
        $assets['js'][] = 'https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.iframe-transport.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-process.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-image.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-audio.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-video.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-validate.js';
        $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-ui.js';
        $assets['js'][] = '/adm/js/file-uploader.js';
        $assets['js'][] = '/adm/js/flavors.js';
		if($action=="add-restaurant" or $action=="edit-restaurant") {
			$assets['css_footer'][] = '/adm/third-party/tags/jquery.tagsinput.css';
			$assets['js'][] = '/adm/third-party/tags/jquery.tagsinput.js';
		}	
        return $assets;
    }
	 public function ajax($action = '', $id = 0) {
		if(empty($action)) {$action='restaurants';echo '1';} 
        if (!empty($action)) {
            switch ($action) {
				case 'publishrestaurant':
                    return $this->publishRestaurant($id);
                break;
				case 'awardrestaurant':
                    return $this->awardRestaurant($id);
                break;
				case 'recommendrestaurant':
                    return $this->recommendRestaurant($id);
                break;
				case 'archiverestaurant':
                    return $this->archiveRestaurant($id);
                break;
				case 'deleterestaurant':
                    return $this->deleteRestaurant($id);
                break;
                case 'publishparameter':
                    return $this->publishParameter($id);
                    break;
                case 'deleteparameter':
                    return $this->deleteParameter($id);
                    break;
                case 'delete_parameter_value':
                    return $this->deleteParameterValue($id);
                    break;
                case 'par_values':
                    return $this->loadParameterValues($id);
                    break;
                case 'add_parameter_value':
                    return $this->addParameterValue($id);
                    break;
                case 'save_parameter_value':
                    return $this->saveParameterValue($id, $action);
                break;	
				case 'publishcuisine':
                    return $this->publishCuisine($id);
                    break;
				case 'menucuisine':
                    return $this->menuCuisine($id);
                    break;	
				case 'publishcomment':
                    return $this->publishComment($id);
                break;	
                case 'deletecusine':
                    return $this->deleteCuisine($id);
                break;		
				case 'deletecomment':
                    return $this->deleteComment($id);
                break;
				case 'deletetag':
                    return $this->deleteTag($id);
                break;				
				case 'add-category-parameter':
                    return $this->addCategoryParameter($id);
                break;
                case 'save-cat-param':   
                    return $this->saveCategoryParameter($id);
				break;
				case 'delete-category': 
					return $this->deleteCategory($id);
				break;
				case 'SaveOrderCategory':
				 $post = $this->request->getPost();
					parse_str($post['data'], $params);
					$r=$this->flavorsCategoryModel->saveCategoryOrder($params,$id);
					$response = array(
					'status' => true,
					'result' => $r,
					'msg' => $r ? lang('News.OrderChanged') : lang('News.Error')
				);
				 break;
				 case 'SaveOrderRestaurant':
				 $post = $this->request->getPost();
					parse_str($post['data'], $params);
					$r=$this->flavorsRestaurantsModel->saveRestaurantOrder($params,$id);
					$response = array(
					'status' => true,
					'result' => $r,
					'msg' => $r ? lang('News.OrderChanged') : lang('News.Error')
				);
				 break;
				 case 'SaveOrderCuisine':
				 $post = $this->request->getPost();
					parse_str($post['data'], $params);
					$r=$this->flavorsCuisineModel->saveCuisineOrder($params,$id);
					$response = array(
					'status' => true,
					'result' => $r,
					'msg' => $r ? lang('News.OrderChanged') : lang('News.Error')
				);
				 break;
                 case 'add-additional-categories':

					$post = $this->request->getPost();
					$pages=$this->flavorsCategoryModel->getCategoryStructure($this->id_lang, 0);	
					$html = view('Modules\Flavors\Views\admin\additional_categories', array('categories'=>$pages,'post'=>$post, 'locale' => $this->locale));

                   $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        );
                 break;
                case 'save-additional-categories':
                return $this->saveAdditionalCategories();
                break;
				case 'save-cuisine':
                return $this->saveAdditionalCuisine();
                break;				
                case 'add-cuisine':
					$post = $this->request->getPost();
					$pages=$this->flavorsCuisineModel->table('flavors_cuisine p')
                ->join('flavors_cuisine_lang pl', 'flavors_cuisine.id = pl.id_cuisine')
                ->select('flavors_cuisine.id,pl.name')
                ->where('pl.id_lang',$this->id_lang)
                ->orderBy('pl.name', 'ASC')->get()->getResultArray();			
			    $html = view('Modules\Flavors\Views\admin\restaurant_cuisine', array('categories'=>$pages,'post'=>$post, 'locale' => $this->locale));

                   $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        );	
           break;
				
			case 'editcomment':
			$comment=array();
			$comment=$this->flavorsCommentsModel
                ->select('comment,id')
                ->where('id_lang',$this->id_lang)
				  ->where('id',$id)->get()->getRowArray();			
			
			$html = view('Modules\Flavors\Views\admin\comment_edit', array('comment'=>$comment,'locale' => $this->locale));

                   $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        );	
            break; 	
            case 'savecomment':
             return $this->saveComment($id,$this->id_lang);
            break; 	
           	case 'edittag':
			$tag=array();
			$tag=$this->flavorsTagsModel
                ->select('value,id')
                ->where('id_lang',$this->id_lang)
				  ->where('id',$id)->get()->getRowArray();			
			$html = view('Modules\Flavors\Views\admin\tag_edit', array('tag'=>$tag,'locale' => $this->locale));
                   $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        );	
            break; 	
            case 'savetag':
             return $this->saveTag($id,$this->id_lang);
            break; 
			case 'related-news-save':
               return $this->relatedNewsSave($id);
            break;
			case 'restaurantsearchtags':
				$get = $this->request->getGet();			
				$response=array();
				if(!empty($get['term']) and strlen($get['term'])>2) {
					$tagsData = array();
					$response = $this->flavorsRestaurantsModel->db->table('flavors_tags')
					->select('id,value')
					->like('value', $get['term'])
					->where('id_lang',$this->id_lang)
					->get()
					->getResultArray();
					
				}	
			break;	
			case 'add-news':
			  return $this->relatedNewsModal($id);
			break;
            }
        }
		  return $this->response->setJSON($response);
    }
	
	
	
	
	 private function relatedNewsSave($id_restaurant) {
		$post = $this->request->getPost();
        $html = "";
        $result = false;
        $related_news = array();
		if(!empty($post['news'])) {
			$related_news=$this->flavorsRestaurantsModel->db->table('news n')->join('news_lang nl', 'nl.id_news = n.id','left')->Select('n.id,title,n.created_at,n.id_page_cont')->Where('id_lang',$this->id_lang)->Where('publish',1)->OrderBy('n.created_at','DESC')->whereIn('n.id',$post['news'])->get()->getResultArray();
			if(!empty($related_news)) {
			   foreach($related_news as $k=>$v) {	
				  $related_news[$k]['photo']=$this->flavorsRestaurantsModel->db->table('news_files')->Select('path')->Where('field','photo')->Where('id_news',$v['id'])->get()->getRowArray();
			   }
			   $result=true;
			}	
		}	
        $html = view('Modules\Flavors\Views\admin\add_related_news_list', array('related_news' => $related_news, 'languages' => $this->languages, 'locale' => $this->locale));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'html' => base64_encode(urlencode($html)),
            'msg' => $result ? lang('Flavors.RelatedNewsAddSuccess') : lang('Flavors.RelatedNewsAddError')
        ));
    }
	
	
	
	
	
	    private function relatedNewsModal($id_restaurant) {
		$post = $this->request->getPost();
		$selected=array();
		if(!empty($post['news'])) {
		  $selected=$post['news'];	
		}	
        if(!empty($post['name'])) {
            $news=$this->flavorsRestaurantsModel->db->table('news n')->join('news_lang nl', 'nl.id_news = n.id','left')->Select('n.id,title,n.created_at,n.id_page_cont')->Where('id_lang',$this->id_lang)->Where('publish',1)->OrderBy('n.created_at','DESC')->Like('nl.title',$post['name'])->get()->getResultArray();
			if(!empty($news)) {
			   foreach($news as $k=>$v) {	
				  $news[$k]['photo']=$this->flavorsRestaurantsModel->db->table('news_files')->Select('path')->Where('field','photo')->Where('id_news',$v['id'])->get()->getRowArray();
			   }
			}	
            $html = view('Modules\Flavors\Views\admin\related_news_list', array('newslist'=>$news,'selected'=>$selected,'id_restaurant' => $id_restaurant, 'languages' => $this->languages, 'locale' => $this->locale));
            return $this->response->setJSON(array(
                'status' => true,
                'html' => base64_encode(urlencode($html))
            ));
        } else {
            $news=$this->flavorsRestaurantsModel->db->table('news n')->join('news_lang nl', 'nl.id_news = n.id','left')->Select('n.id,title,n.created_at,n.id_page_cont')->Where('id_lang',$this->id_lang)->Where('publish',1)->OrderBy('n.created_at','DESC')->Limit(10);
			if(!empty($selected)) {
				$news=$news->WhereNotIn('n.id',$selected);	
			}
			$news=$news->get()->getResultArray();
			
			if(!empty($news)) {
			   foreach($news as $k=>$v) {	
				  $news[$k]['photo']=$this->flavorsRestaurantsModel->db->table('news_files')->Select('path')->Where('field','photo')->Where('id_news',$v['id'])->get()->getRowArray();
			   }
			}	
            $html = view('Modules\Flavors\Views\admin\related_news_modal', array('newslist'=>$news,'selected'=>$selected,'id_restaurant' => $id_restaurant, 'languages' => $this->languages, 'locale' => $this->locale));
            return $this->response->setJSON(array(
                'status' => true,
                'html' => base64_encode(urlencode($html))
            ));
        }
    }
	
	
	
	    public function saveAdditionalCategories() {
        $post = $this->request->getPost();
        $html='';
        if(!empty($post['categories'])) {
           foreach(	$post['categories'] as $cat) {
                   $html.='<p><input type="hidden" name="categories['.$cat.']" value="'.$cat.'" /><b> '.$this->flavorsCategoryModel->getCategoryPatch($cat,$this->id_lang.'</b>' ,' <span> » </span> ').'</p>';
           }	
        }	
        return $this->response->setJSON(array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        ));
    }
	
	    public function saveAdditionalCuisine() {
        $post = $this->request->getPost();
        $html='';
        if(!empty($post['categories'])) {
           foreach(	$post['categories'] as $cat) {
			       $info=$this->flavorsCuisineModel->getCuisineLang($cat);
                   $html.='<p><input type="hidden" name="cuisine['.$cat.']" value="'.$cat.'" /><b>'.$info[$this->id_lang]['name'].'</b></p>';
           }	
        }	
        return $this->response->setJSON(array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        ));
    }
	
	public function saveComment($id,$id_lang) {
		 helper('text');
        $post = $this->request->getPost();
		 $r = $this->flavorsCommentsModel->where('id', $id)->set('comment', $post['comment'])->update();		 
		 $response = array(
                'status' => $r,
                'msg' => $r ? lang('Flavors.CommentsSaved') : lang('Flavors.CommentsNotSaved'),
				'truncate'=>character_limiter($post['comment'],60),
				'all'=>$post['comment'],
				'id'=>$id
            );
		
		return $this->response->setJSON($response);
    }
	
	public function saveTag($id,$id_lang) {
		 helper('text');
        $post = $this->request->getPost();
		 $r = $this->flavorsTagsModel->where('id', $id)->set('value', $post['tag'])->update();		 
		 $response = array(
                'status' => $r,
                'msg' => $r ? lang('Flavors.TagSaved') : lang('Flavors.TagNotSaved'),
				'all'=>$post['tag'],
				'id'=>$id
            );
		
		return $this->response->setJSON($response);
    }
	
	    private function publishParameter($id) {
        $parameter = $this->flavorsParametersModel->select('id,publish')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->flavorsParametersModel->where('id', $id)->set('publish', $parameter['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['publish'] ? 0 : 1,
                'msg' => $parameter['publish'] ? lang('Flavors.ParametersRepublished') : lang('Flavors.ParametersPublished')
            );
            HistoryStat($id, '', 'flavors_parameters', 'Flavors', $parameter['publish'] ? lang('Flavors.ParametersRepublished') : lang('Flavors.ParametersPublished'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['publish'],
                'msg' => lang('Flavors.ParameterEditError')
            );
        }
        return $this->response->setJSON($response);
    }
	
	
	 private function publishRestaurant($id) {
        $parameter = $this->flavorsRestaurantsModel->select('id,publish')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->flavorsRestaurantsModel->where('id', $id)->set('publish', $parameter['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['publish'] ? 0 : 1,
                'msg' => $parameter['publish'] ? lang('Flavors.RestaurantRepublished') : lang('Flavors.RestaurantPublished')
            );
            HistoryStat($id, '', 'flavors_restaurant', 'Flavors', $parameter['publish'] ? lang('Flavors.RestaurantRepublished') : lang('Flavors.RestaurantPublished'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['publish'],
                'msg' => lang('Flavors.RestaurantPublishError')
            );
        }
        return $this->response->setJSON($response);
    }
	
	private function awardRestaurant($id) {
        $parameter = $this->flavorsRestaurantsModel->select('id,awarded')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->flavorsRestaurantsModel->where('id', $id)->set('awarded', $parameter['awarded'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['awarded'] ? 0 : 1,
                'msg' => $parameter['awarded'] ? lang('Flavors.RestaurantAwardedOff') : lang('Flavors.RestaurantAwardedOn')
            );
            HistoryStat($id, '', 'flavors_restaurant', 'Flavors', $parameter['awarded'] ? lang('Flavors.RestaurantAwardedOff') : lang('Flavors.RestaurantAwardedOn'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['awarded'],
                'msg' => lang('Flavors.RestaurantAwardedError')
            );
        }
        return $this->response->setJSON($response);
    }
	
	private function recommendRestaurant($id) {
        $parameter = $this->flavorsRestaurantsModel->select('id,recommended')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->flavorsRestaurantsModel->where('id', $id)->set('recommended', $parameter['recommended'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['recommended'] ? 0 : 1,
                'msg' => $parameter['recommended'] ? lang('Flavors.RestaurantRecommendedOff') : lang('Flavors.RestaurantRecommendedOn')
            );
            HistoryStat($id, '', 'flavors_restaurant', 'Flavors', $parameter['recommended'] ? lang('Flavors.RestaurantRecommendedOff') : lang('Flavors.RestaurantRecommendedOn'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['recommended'],
                'msg' => lang('Flavors.RestaurantRecommendedError')
            );
        }
        return $this->response->setJSON($response);
    }
	
		private function archiveRestaurant($id) {
        $parameter = $this->flavorsRestaurantsModel->select('id,archives')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->flavorsRestaurantsModel->where('id', $id)->set('archives', $parameter['archives'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['archives'] ? 0 : 1,
                'msg' => $parameter['archives'] ? lang('Flavors.RestaurantArchiveOff') : lang('Flavors.RestaurantArchiveOn')
            );
            HistoryStat($id, '', 'flavors_restaurant', 'Flavors', $parameter['archives'] ? lang('Flavors.RestaurantArchiveOff') : lang('Flavors.RestaurantArchiveOn'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['archives'],
                'msg' => lang('Flavors.RestaurantArchiveError')
            );
        }
        return $this->response->setJSON($response);
    }
	
	
	 private function publishCuisine($id) {
        $parameter = $this->flavorsCuisineModel->select('id,publish')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->flavorsCuisineModel->where('id', $id)->set('publish', $parameter['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['publish'] ? 0 : 1,
                'msg' => $parameter['publish'] ? lang('Flavors.CuisineRepublished') : lang('Flavors.CuisinePublished')
            );
            HistoryStat($id, '', 'flavors_cuisine', 'Flavors', $parameter['publish'] ? lang('Flavors.CuisineRepublished') : lang('Flavors.CuisinePublished'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['publish'],
                'msg' => lang('Flavors.CuisineEditError')
            );
        }
        return $this->response->setJSON($response);
    }
	
	 private function menuCuisine($id) {
        $parameter = $this->flavorsCuisineModel->select('id,menu')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->flavorsCuisineModel->where('id', $id)->set('menu', $parameter['menu'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['menu'] ? 0 : 1,
                'msg' => $parameter['menu'] ? lang('Flavors.CuisineMenuOff') : lang('Flavors.CuisineMenuOn')
            );
            HistoryStat($id, '', 'flavors_cuisine', 'Flavors', $parameter['menu'] ? lang('Flavors.CuisineMenuOff') : lang('Flavors.CuisineMenuOn'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['menu'],
                'msg' => lang('Flavors.CuisineEditError')
            );
        }
        return $this->response->setJSON($response);
    }
	
	
	 private function publishComment($id) {
        $parameter = $this->flavorsCommentsModel->select('id,publish')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->flavorsCommentsModel->where('id', $id)->set('publish', $parameter['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['publish'] ? 0 : 1,
                'msg' => $parameter['publish'] ? lang('Flavors.CommentRepublished') : lang('Flavors.CommentPublished')
            );
            HistoryStat($id, '', 'flavors_comments', 'Flavors', $parameter['publish'] ? lang('Flavors.CommentRepublished') : lang('Flavors.CommentPublished'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['publish'],
                'msg' => lang('Flavors.CommentPublishError')
            );
        }
        return $this->response->setJSON($response);
    }
	

	function addCategoryParameter($id) {	
	    $post = $this->request->getPost();
		$selected=array();
		if(!empty($post['selected'])) {
			parse_str($post['selected'],$selected);
		}	
		$parameters=$this->flavorsParametersModel->db->table('flavors_parameters f')->join('flavors_parameters_lang fl', 'f.id=fl.id_parameter')->select('f.id,fl.name,fl.filter_name')->where('fl.id_lang',$this->id_lang)->get()->getResultArray();
		$response = array(
                'status' => true,
                'msg' => view('Modules\Flavors\Views\admin\category_parameters_modal', array('parameters' => $parameters,'selected'=>$selected, 'locale' => $this->locale)) 
            );
		
		return $this->response->setJSON($response);
	}
	
	function saveCategoryParameter($id) {
		$post = $this->request->getPost();
		$params_list=array();
		if(!empty($post['param'])) {
			foreach($post['param'] as $id_p=>$par) {
			  $param=array();	
			  $param=$this->flavorsParametersModel->getParameterById($par,$this->id_lang);
			  $param['values']=$this->flavorsParametersModel->valuesList($par,$this->id_lang);
			  $params_list[]=$param;
			}
		}
         echo view('Modules\Flavors\Views\admin\category_parameter_modal_save', array('params' => $params_list, 'id_lang' => $this->id_lang, 'locale' => $this->locale));	
	}	
	
	
	
	
	  private function deleteCuisine($id) {
        $result = $this->flavorsCuisineModel->deleteCuisine($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Flavors.CuisineRemoved') : lang('Flavors.DeleteCuisineError')
        ));
        HistoryStat($id, '', 'flavors_cuisine', 'Flavors', $result ? lang('Flavors.CuisineRemoved') : lang('Flavors.DeleteCuisineError'));
    }
	
	 private function deleteComment($id) {
        $result = $this->flavorsCommentsModel->deleteComment($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Flavors.CommentRemoved') : lang('Flavors.DeleteCommentError')
        ));
        HistoryStat($id, '', 'flavors_comments', 'Flavors', $result ? lang('Flavors.CommentRemoved') : lang('Flavors.DeleteCommentError'));
    }
	
	
	 private function deleteTag($id) {
        $result = $this->flavorsTagsModel->deleteTag($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Flavors.TagRemoved') : lang('Flavors.DeleteTagError')
        ));
        HistoryStat($id, '', 'flavors_tags', 'Flavors', $result ? lang('Flavors.TagRemoved') : lang('Flavors.DeleteTagError'));
    }
	
	
	  private function deleteParameter($id) {
        $result = $this->flavorsParametersModel->deleteParameter($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Flavors.ParametersRemoved') : lang('Flavors.DeleteParametersError')
        ));
        HistoryStat($id, '', 'flavors_parameters', 'Flavors', $result ? lang('Flavors.ParametersRemoved') : lang('Flavors.DeleteParametersError'));
    }
	
	  private function deleteRestaurant($id) {
        $result = $this->flavorsRestaurantsModel->deleteRestaurant($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Flavors.RestaurantRemoved') : lang('Flavors.DeleteRestaurantError')
        ));
        HistoryStat($id, '', 'flavors_restaurant', 'Flavors', $result ? lang('Flavors.RestaurantRemoved') : lang('Flavors.DeleteRestaurantError'));
    }
	
	public function deleteParameterValue($id) {
        $result = $this->flavorsParametersModel->deleteParameterValue($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Flavors.ParametersValueRemoved') : lang('Flavors.DeleteParametersValueError')
        ));
        HistoryStat($id, '', 'flavors_parameters_value', 'Flavors', $result ? lang('Flavors.ParametersValueRemoved') : lang('Flavors.DeleteParametersValueError'));
    }
	  
	
	
	    private function loadParameterValues($id) {
        $params['value_list'] = $this->flavorsParametersModel->valuesList($id, $this->id_lang);
        echo view('Modules\Flavors\Views\admin\parameters_values_list', array('values' => $params, 'id_parameter' => $id, 'locale' => $this->locale));
    }
	
	 private function addParameterValue($id) {
        $value = array();
        $parameter = $this->flavorsParametersModel->select('id')->where('id', $id)->first();
        if (!empty($_GET['id_value'])) {
            $value = $this->flavorsParametersModel->getValueById($_GET['id_value']);
        }
        echo view('Modules\Flavors\Views\admin\parameter_modal_value', array('id_parameter' => $id, 'parameter' => $parameter, 'languages' => $this->languages, 'value' => $value, 'locale' => $this->locale));
    }
	
	    function saveParameterValue($id, $action) {
        $post = $this->request->getPost();
        if (!empty($post)) {
            $result = false;
            $errors = array();
            $validation = \Config\Services::validation();
            
            if (!empty($post['lang'])) {
                foreach ($post['lang'] as $id_lang => $lang) {
                    $validation->reset();
                    $check_unique = $this->flavorsParametersModel->checkValuesUnique($lang['value'], $id_lang, $id, $action);
                    $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                    $data = array();
                    $validation->setRules([
                        'value' => [
                            'rules' => 'required|check_unique[' . $check_unique . ']',
                            'errors' => [
                                'required' => $lang_name . lang('Flavors.ParametersValueError'),
                                'check_unique' => $lang_name . lang('Flavors.ParametersValueUniqueError')
                            ],
                        ],
                    ]);
                    if (!$validation->run($lang)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                }
            }
            if (empty($errors)) {
                $result = $this->flavorsParametersModel->saveValue($id, $post);
            }
            if ($result) {
                if (empty($_GET['id_value'])) {
                    exit;
                }
                $flashdata = array(
                    'status' => true,
                    'msg' => ($id ? lang('Flavors.ParametersEditSuccess') : lang('Flavors.ParametersValueAddSuccess')) . '!'
                );
                HistoryStat($id, '', 'parameters_value', 'Value', $id ? lang('Flavors.ParametersEditSuccess') : lang('Flavors.ParametersValueAddSuccess'));
            } else {
                $flashdata = array(
                    'status' => false,
                    'msg' => ($id ? lang('Flavors.ParametersEditError') : lang('Flavors.ParametersValueAddError')) . '!',
                    'list' => $errors
                );
            }
            $parameter = $post;
            $parameter['id'] = $id;
        } else {
            $flashdata = $this->session->getFlashdata('flavors');
        }
        echo view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array()));
    }
	
	   private function deleteCategory($id) 
    {
        $result = $this->flavorsCategoryModel->deleteCategory($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Foto.CategoryRemoved') : lang('Foto.Error')
        ));
		HistoryStat($id,'','foto_category','Foto',$result ? lang('Foto.CategoryRemoved') : lang('Foto.Error'));
    }
	
	public function pageContent($id_content, $slug='') 
    {
        helper('filesystem');
		switch($slug) {
			case 'selected':
			   $templates = get_templates_by_dir('modules/Flavors/Views/user/restaurants');
			   return array(
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Flavors\Views\admin\selected_config'
                );
			break;
			case 'tags':
			   $templates = get_templates_by_dir('modules/Flavors/Views/user/tags');
			   return array(
                    'pc_templates' => $templates
                );
			break;
			case 'map':
			   $templates = get_templates_by_dir('modules/Flavors/Views/user/maps');
			   return array(
                    'pc_templates' => $templates
                );
			break;
			case 'search':
			   $templates = get_templates_by_dir('modules/Flavors/Views/user/search');
			   return array(
                    'pc_templates' => $templates
                );
			break;
			case 'ranking':
			   $templates = get_templates_by_dir('modules/Flavors/Views/user/ranking');
			   return array(
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Flavors\Views\admin\ranking_config'
                );
			break;
			case 'home':
			   $templates = get_templates_by_dir('modules/Flavors/Views/user/home');
			   $menus=$this->flavorsCategoryModel->db->table('menu m')->join('menu_lang ml', 'm.id=ml.id_menu')->select('m.id,ml.name')->OrderBy('ml.name','ASC')->get()->getResultArray();
			   return array(
                    'pc_templates' => $templates,
					'menus'=>$menus,
                    'form_view' => 'Modules\Flavors\Views\admin\resinethome_config'
                );
			break;
			case 'category':
		       $templates = get_templates_by_dir('modules/Flavors/Views/user/restaurants');
                return array(
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Flavors\Views\admin\categories_config'
                );
		    break;
			case 'cuisine_list':
			    $templates = get_templates_by_dir('modules/Flavors/Views/user/cuisine_list');
                return array(
					'pc_templates' => $templates,
                    'form_view' => 'Modules\Flavors\Views\admin\cuisine_list_config'
                );
		    break;
		}	
	}	
}
?>