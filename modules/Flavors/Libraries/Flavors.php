<?php

namespace Modules\Flavors\Libraries;

use Modules\Flavors\Models\FlavorsRestaurantsModel;
use Modules\Flavors\Models\FlavorsCuisineModel;
use Modules\Flavors\Models\FlavorsCategoryModel;
use Modules\Flavors\Models\FlavorsGradesModel;
use Modules\News\Models\NewsModel;
use App\Libraries\Page;
class Flavors {
	
	public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->restaurantModel = new FlavorsRestaurantsModel();
		$this->flavorsCuisineModel = new FlavorsCuisineModel();
		$this->flavorsCategoryModel = new FlavorsCategoryModel();
		$this->flavorsGradesModel = new FlavorsGradesModel();
		$this->newsModel = new NewsModel();
		$this->pageClass = new Page();
    }
	
	public function index($content, $id_lang, $slug = '',$link='') {
	   $config = new \Config\Pager;
        switch ($slug) {
            case 'selected':
			 $query=$this->restaurantModel->Select('id')->Where('publish',1)->Where('archives',0);
			if(!empty($content['config']['option'])) {
                    switch ($content['config']['option']) {
                        case 'recommended': $query->where('recommended', 1);
                            break;
						case 'awarded': $query->where('awarded', 1);
                            break;	
                    }
                } 
			 
			 switch ($content['config']['order']) {
                    case 'random':
                        $query->orderBy('order', 'RANDOM');
                        break;
					case 'latestrandom':
					    $query->orderBy('created_at', 'DESC');
                        break;		
                    case 'date':
                        $query->orderBy('created_at', 'DESC');
                        break;
                    case 'latest':
                    default: $query->orderBy('order', 'ASC');
                        break;
                }
			$list = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'news-' . $content['id']);
			if(!empty($content['config']['order']) and $content['config']['order']=="latestrandom") {
				shuffle($list);
			}
			if(!empty($list)) {
			  foreach($list as $k=>$v) {	
				  $list[$k]=$this->restaurantModel->GetRestaurantListById($v['id'],$id_lang);
			  }	
			}	
			return array(
                    'views_dir' => 'restaurants',
                    'list' => $list,
                    'pager' => !empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination'] ? $this->restaurantModel->pager : null,
                );
			break;
			case 'ranking':
			return $this->restaurantModel->GetRestaurantRanking($id_lang,$content['config']);
			break;
			case 'cuisine_list':
			return array(
			  'list'=>$this->flavorsCuisineModel->GetCuisineList($id_lang,$content['config'])
			);
			break;
			case 'category':
			 $list_all=$this->flavorsCategoryModel->GetAllRestaurants($id_lang);
			return array(
                    'views_dir' => 'restaurants',
                    'list' => $list_all,
                    'pager' => $list_all['paginate'],
                );
			break;
            case 'tags':
              $get = $this->request->getGet();
              if(!empty($get['tag'])) {
                $list=array(); 
				$list['tag']=$get['tag'];
                $list['restaurants']=$this->restaurantModel->SearchRestaurantsByTag($get['tag'],$id_lang);
                $list['news']=$this->restaurantModel->SearchNewsByTag($get['tag'],$id_lang);
             return array(
                    'views_dir' => 'tags',
                    'list' =>$list
                );
              }
			  else {
				 throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();	
				  exit(); 
			  }	  
			break;
			case 'map':
			 $get = $this->request->getGet();
		     $list=array('1'); 
			 return array(
                    'views_dir' => 'maps',
					'categories'=> $this->flavorsCategoryModel->db->table('flavors_categories c')->join('flavors_categories_lang cl', 'c.id=cl.id_category')->Select('c.id,cl.name')->Where('c.publish',1)->Where('cl.id_lang',$id_lang)->OrderBy('name','ASC')->get()->getResultArray(),
					'cuisines'=> $this->flavorsCuisineModel->GetCuisineList($id_lang,$content['config']),
					'list'=> $this->restaurantModel->GetRestaurantsMap($id_lang,$get),
					'filters'=>$get
                );
			break;
			case 'home':
			 $newsList=$this->newsModel->join('news_lang nl', 'news.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->join('news_files nf', 'nf.id_news=news.id', 'left')->join('news_files_lang nfl', 'nfl.id_file=nf.id', 'left')
		->select('news.id,news.date,nl.title,nl.introduction,nl.author,l.link,nf.path as photo,nfl.caption as photo_caption,nfl.author as photo_author')
        ->where('news.publish', 1)->where('nl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->groupStart()->where('nf.publish', 1)->orWhere('nf.publish', null)
       ->groupEnd()->groupStart()->where('nfl.id_lang', $id_lang)->orWhere('nfl.id_lang', null)->groupEnd()->where('news.id_page_cont',38)->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->Limit(intval($content['config']['no']))->orderBy('date', 'DESC')->get()->getResultArray();
			  return array(
                    'views_dir' => 'home',
					'list'=>$newsList,
					'config'=>$content['config']
		      );		
			
			break;
			case 'search':
			  $get = $this->request->getGet();
			  if(!empty($get['szukane'])) {
				$list=array(); 
				$list['search']=$get['szukane'];
                $list['restaurants']=$this->restaurantModel->SearchRestaurants($get['szukane'],$id_lang);
                $list['news']=$this->restaurantModel->SearchNews($get['szukane'],$id_lang);
				 return array(
						'views_dir' => 'search',
						'list' =>$list
					);
			  }
              else {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();	
				  exit(); 
              }				  
			break;
		}	
	}
	public function getSingleByLinkId($id_link, $id_lang,$link) {
	if(!empty($link['module_slug'])) {
      switch($link['module_slug']) {
			case 'cuisine':
			$cuisine['cuisine']=$this->flavorsCuisineModel->GetCuisineRestaurants($id_link,$id_lang);
			$custom_bread=array();
			$flavors_id_page['id_page']=44;
				if(!empty($flavors_id_page['id_page'])) {
				  $page_info=$this->restaurantModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->Select('pc.id_page')->Where('pc.id',$flavors_id_page['id_page'])->get()->getRowArray();
				  if(!empty($page_info['id_page'])) {
					$page_info=$this->restaurantModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->Select('pl.name,l.link')->Where('p.id',$page_info['id_page'])->Where('l.id_lang',$id_lang)->Where('pl.id_lang',$id_lang)->Where('p.publish',1)->get()->getRowArray();  
					  if(!empty($page_info['link'])) {
						 $custom_bread[]=array('name'=>$page_info['name'],'link'=>'/'.$page_info['link']);
					  }
				  }	  
				}
			if(!empty($cuisine['cuisine']['link'])) {$custom_bread[]=array('name'=>lang('Flavors.Cuisine').' '.$cuisine['cuisine']['name'],'link'=>'/'.$cuisine['cuisine']['link']);}	
			$custom_meta=array();
			if(!empty($cuisine['cuisine']['meta']['title'])) {$custom_meta['title']=$cuisine['cuisine']['meta']['title'];}
			else {
				$title=lang('Flavors.Cuisine').' '.$cuisine['cuisine']['name'];
			if(!empty($cuisine['cuisine']['denmark'])) { $title=$title.' - '.$cuisine['cuisine']['denmark'];   }
				$custom_meta['title']=$title.' - '.lang('Flavors.MetaTitleSuffix');
			}	
			if(!empty($cuisine['cuisine']['meta']['description'])) {$custom_meta['description']=$cuisine['cuisine']['meta']['description'];}
			else {
				
				$custom_meta['description']=lang('Flavors.MetaDescCuisinePrefix').' '.$cuisine['cuisine']['name'].lang('Flavors.MetaDescCuisineSuffix');
			}	
			if(!empty($cuisine['cuisine']['meta']['keywords'])) {$custom_meta['keywords']=$cuisine['cuisine']['meta']['keywords'];}
			else {
				$custom_meta['keywords']=$cuisine['cuisine']['name'];
				if(!empty($cuisine['cuisine']['denmark'])) { $custom_meta['keywords']=$custom_meta['keywords'].', '.$cuisine['cuisine']['denmark'];   }
			}	
			
			
			
			$settings = $this->pageClass->getSettings($id_lang);
			$custom_meta['favicon']=base_url().'image/'.$settings['favicon_flavor']['path'];
			$custom_meta['apple_icon']=base_url().'image/'.$settings['favicon_flavor']['path'];
			return array(
			        'page_content'=>$cuisine, 
			        'template'=>'\Modules\Flavors\Views/user/cuisine/single_cuisine',
					'page_template'=>'szablon_smaki_parameters.php',
					'custom_breadcrumbs'=>$custom_bread,
					'custom_meta'=>$custom_meta,
					'pager'=>$cuisine['cuisine']['paginate']
            );
            break;
			case 'category':
			    $category['category']=$this->flavorsCategoryModel->GetCategoryRestaurants($id_link,$id_lang);
				$custom_bread=array();
				$flavors_id_page['id_page']=18;
				if(!empty($flavors_id_page['id_page'])) {
				  $page_info=$this->restaurantModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->Select('pc.id_page')->Where('pc.id',$flavors_id_page['id_page'])->get()->getRowArray();
				  if(!empty($page_info['id_page'])) {
					$page_info=$this->restaurantModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->Select('pl.name,l.link')->Where('p.id',$page_info['id_page'])->Where('l.id_lang',$id_lang)->Where('pl.id_lang',$id_lang)->Where('p.publish',1)->get()->getRowArray();  
					  if(!empty($page_info['link'])) {
						 $custom_bread[]=array('name'=>$page_info['name'],'link'=>'/'.$page_info['link']);
					  }
				  }	  
				}
				if(!empty($category['category']['link'])) {$custom_bread[]=array('name'=>$category['category']['name'],'link'=>'/'.$category['category']['link']);}
                if(empty($category['category']['meta_title'])) {$category['category']['meta_title']=$category['category']['name'].', jedzenie Rzeszów';}
				if(empty($category['category']['meta_key'])) {$category['category']['meta_key']=$category['category']['name'].', Rzeszów, gastronomia, lokale gastronomiczne, pizzerie, restauracje, kebab, bary, Fast ford, cukiernie, pierogarnie, piwiarnie, puby';}
				$settings = $this->pageClass->getSettings($id_lang);
				$custom_meta=array('title'=>$category['category']['meta_title'],'description'=>$category['category']['meta_desc'],'keywords'=>$category['category']['meta_key'],'favicon'=>base_url().'image/'.$settings['favicon_flavor']['path'],'apple_icon'=>base_url().'image/'.$settings['favicon_flavor']['path']);
				$page_background=array();
				if(!empty($category['category']['background'])) { $page_background=$category['category']['background'];}
				return array(
						'page_content'=>$category,
                        'page_background'=>$page_background,						
						'template'=>'\Modules\Flavors\Views/user/category/category',
						'page_template'=>'szablon_smaki_parameters.php',
						'custom_breadcrumbs'=>$custom_bread,
						'custom_meta'=>$custom_meta,
						'pager'=>$category['category']['paginate']
				);
			break;
			case 'restaurant':
			   $custom_bread=array();
			   $restaurant['restaurant']=$this->restaurantModel->GetRestaurant($id_link,$id_lang);
			   if(!empty($id_link) and !empty($id_lang)) {$this->restaurantModel->db->table('flavors_restaurant_lang')->where('id_link',$id_link)->where('id_lang', $id_lang)->set('views', 'views+1', false)->update();}
			   $flavors_id_page['id_page']=18;
				if(!empty($flavors_id_page['id_page'])) {
				  $page_info=$this->restaurantModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->Select('pc.id_page')->Where('pc.id',$flavors_id_page['id_page'])->get()->getRowArray();
				  if(!empty($page_info['id_page'])) {
					$page_info=$this->restaurantModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->Select('pl.name,l.link')->Where('p.id',$page_info['id_page'])->Where('l.id_lang',$id_lang)->Where('pl.id_lang',$id_lang)->Where('p.publish',1)->get()->getRowArray();  
					  if(!empty($page_info['link'])) {
						 $custom_bread[]=array('name'=>$page_info['name'],'link'=>'/'.$page_info['link']);
					  }
				  }	  
				}
				if(!empty($restaurant['restaurant']['dish_type']) and !in_array(1,$restaurant['restaurant']['dish_type'])) {
					$restaurant['restaurant']['vegetarian_list']=$this->restaurantModel->GetVegetarianOther($restaurant['restaurant']['id'],$id_lang,4);
				}
				
				if(!empty($restaurant['restaurant']['news'])) {
					$restaurant['restaurant']['news_list']=$this->restaurantModel->GetRestaurantNewsList($restaurant['restaurant']['news'],$id_lang);
				}
				
				if(!empty($restaurant['restaurant']['coordinates'])) {
				  $restaurant['restaurant']['similar_location']=$this->restaurantModel->GetSimilarLocationRestaurants($restaurant['restaurant']['id'],$restaurant['restaurant']['coordinates'],$id_lang,400,4);	
				}	
				
				if(!empty($restaurant['restaurant']['categories'][0]['id'])) {
					$restaurant['restaurant']['others']=$this->restaurantModel->GetOthersRestaurants($restaurant['restaurant']['id'],$restaurant['restaurant']['categories'][0]['id'],$id_lang,4);
				}	
				$restaurant['restaurant']['ranking_others']=$this->restaurantModel->GetRestaurantsRanking($restaurant['restaurant']['id'],$id_lang,5);
				if(!empty($restaurant['restaurant']['categories'][0])) {$custom_bread[]=array('name'=>$restaurant['restaurant']['categories'][0]['name'],'link'=>'/'.$restaurant['restaurant']['categories'][0]['link']);}	
				if(!empty($restaurant['restaurant']['link'])) {$custom_bread[]=array('name'=>$restaurant['restaurant']['name'],'link'=>'/'.$restaurant['restaurant']['link']);}	
				$page_background=array();
				if(!empty($restaurant['restaurant']['background'])) { $page_background=$restaurant['restaurant']['background'];}
				$custom_meta=array();
				if(!empty($restaurant['restaurant']['meta_title'])) {
					$title=$restaurant['restaurant']['meta_title'];	
				}	
				else {
					$title=$restaurant['restaurant']['name'].' - '.lang('Flavors.MetaTitlePrefix');
					if(!empty($restaurant['restaurant']['categories'])) {
						foreach($restaurant['restaurant']['categories'] as $cat) {
						  $title=$title.', '.$cat['name'];	
						}
					}	
					if(!empty($restaurant['restaurant']['cuisine_type'])) {
						foreach($restaurant['restaurant']['cuisine_type'] as $cat) {
						  $title=$title.', '.$cat['name'];	
						}
					}
					$title=$title.' - '.lang('Flavors.MetaTitleSuffix');
				}
				if(!empty($restaurant['restaurant']['meta_description'])) {
					$meta_description=$restaurant['restaurant']['meta_description'];	
				}	
				else {
					$meta_description=lang('Flavors.MetaDescPrefix').' '.$restaurant['restaurant']['name'].'. '.lang('Flavors.MetaDescSuffix');
				}
				if(!empty($restaurant['restaurant']['meta_keywords'])) {
					$meta_keywords=$restaurant['restaurant']['meta_keywords'];	
				}	
				else {
				  $meta_keywords=$restaurant['restaurant']['name'];
				  if(!empty($restaurant['restaurant']['categories'])) {
						foreach($restaurant['restaurant']['categories'] as $cat) {
						  $meta_keywords=$meta_keywords.', '.$cat['name'];	
						}
					}	
					if(!empty($restaurant['restaurant']['cuisine_type'])) {
						foreach($restaurant['restaurant']['cuisine_type'] as $cat) {
						  $meta_keywords=$meta_keywords.', '.$cat['name'];	
						}
					}
				  $meta_keywords=$meta_keywords.', '.lang('Flavors.MetaTitleSuffix');
				}
				$settings = $this->pageClass->getSettings($id_lang);
				$custom_meta=array('title'=>$title,'description'=>$meta_description,'keywords'=>$meta_keywords,'favicon'=>base_url().'image/'.$settings['favicon_flavor']['path'],'apple_icon'=>base_url().'image/'.$settings['favicon_flavor']['path']);
				return array(
						'page_content'=>$restaurant, 
						'page_background'=>$page_background,
						'template'=>'\Modules\Flavors\Views/user/restaurant/restaurant',
						'custom_breadcrumbs'=>$custom_bread,
						'custom_meta'=>$custom_meta,
						'page_template'=>'szablon_smaki_page.php'
				);
			break;
      }
    }
    else {	
		return array(
			    'page_template'=>'szablon_smaki_main.php',
				'template'=>'test'
		      );
	}
		
	}	
	
	public function CuisineMenu($id_lang,$active) {
		
		$cousineTypes=$this->flavorsCuisineModel->CuisineTypesMenu($id_lang);
		return view('Modules\Flavors\Views\user\cuisine_menu',array('cuisineList'=>$cousineTypes,'active'=>$active));	
	}	
	
	public function FlavorMenu($id_lang,$active) {
		
		$menu=$this->restaurantModel->RestaurantListMenu($id_lang);
		$countRestaurants=$this->restaurantModel->CountRestaurantsAll();
		return view('Modules\Flavors\Views\user\restaurantMenu',array('menu'=>$menu,'cnt_restaurants'=>$countRestaurants,'active'=>$active));	
	}	
	
	public function HomeRating($id_lang,$show) {
		$rating=$this->restaurantModel->GetHomeRestaurantRating($id_lang,$show);
		return view('Modules\Flavors\Views\user\homerating',array('rating'=>$rating));
	}	
	
	public function NewsBox($id_lang,$id_page_cont,$news) {
			
		
		/*
		if(!empty($news['list'])) {
				 foreach($news['list'] as $k=>$news_item) {
				     if($news_item['show_in_box']>0 and $news_item['show_in_box']<4) {
				        array_splice($news['list'], $k, 1);
				     }
				 } 
		}	
*/

		
		
		$box_list=array();
		$box_list[1] = $this->newsModel->join('news_lang nl', 'news.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->join('news_files nf', 'nf.id_news=news.id', 'left')->join('news_files_lang nfl', 'nfl.id_file=nf.id', 'left')
		->select('news.id,news.date,nl.title,nl.introduction,l.link,nf.path as photo,nfl.caption as photo_caption,nfl.author as photo_author,nf.crop_dimension')
        ->where('news.publish', 1)
		->where('news.show_in_box', 1)
        ->where('nl.id_lang', $id_lang)
        ->where('l.id_lang', $id_lang)
       ->groupStart()
       ->where('nf.publish', 1)
       ->orWhere('nf.publish', null)
       ->groupEnd()
       ->groupStart()
       ->where('nfl.id_lang', $id_lang)
       ->orWhere('nfl.id_lang', null)
       ->groupEnd()
       ->where('news.id_page_cont', $id_page_cont)
       ->groupStart()
       ->where('nf.field', 'photo')
       ->orWhere('nf.field', null)
       ->groupEnd()->orderBy('title', 'RANDOM')->get()->getRowArray();
		$box_list[2] = $this->newsModel->join('news_lang nl', 'news.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->join('news_files nf', 'nf.id_news=news.id', 'left')->join('news_files_lang nfl', 'nfl.id_file=nf.id', 'left')
		->select('news.id,news.date,nl.title,nl.introduction,l.link,nf.path as photo,nfl.caption as photo_caption,nfl.author as photo_author,nf.crop_dimension')
        ->where('news.publish', 1)
		->where('news.show_in_box', 2)
        ->where('nl.id_lang', $id_lang)
        ->where('l.id_lang', $id_lang)
       ->groupStart()
       ->where('nf.publish', 1)
       ->orWhere('nf.publish', null)
       ->groupEnd()
       ->groupStart()
       ->where('nfl.id_lang', $id_lang)
       ->orWhere('nfl.id_lang', null)
       ->groupEnd()
       ->where('news.id_page_cont', $id_page_cont)
       ->groupStart()
       ->where('nf.field', 'photo')
       ->orWhere('nf.field', null)
       ->groupEnd()->orderBy('title', 'RANDOM')->get()->getRowArray();
	   $box_list[3] = $this->newsModel->join('news_lang nl', 'news.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->join('news_files nf', 'nf.id_news=news.id', 'left')->join('news_files_lang nfl', 'nfl.id_file=nf.id', 'left')
		->select('news.id,news.date,nl.title,nl.introduction,l.link,nf.path as photo,nfl.caption as photo_caption,nfl.author as photo_author,nf.crop_dimension')
        ->where('news.publish', 1)
		->where('news.show_in_box', 3)
        ->where('nl.id_lang', $id_lang)
        ->where('l.id_lang', $id_lang)
       ->groupStart()
       ->where('nf.publish', 1)
       ->orWhere('nf.publish', null)
       ->groupEnd()
       ->groupStart()
       ->where('nfl.id_lang', $id_lang)
       ->orWhere('nfl.id_lang', null)
       ->groupEnd()
       ->where('news.id_page_cont', $id_page_cont)
       ->groupStart()
       ->where('nf.field', 'photo')
       ->orWhere('nf.field', null)
       ->groupEnd()->orderBy('title', 'RANDOM')->get()->getRowArray();
$box_list=array();
		if(empty($box_list[1]) and !empty($news['list'])) {
					$first_key = key($news['list']);
					$box_list[1]=$news['list'][$first_key];
					unset($news['list'][$first_key]);
		}	
		else {
			$box_foto=$box_list[1]['photo'];
			$box_list[1]['photo']=array();
			$box_list[1]['photo']['path']=$box_foto;
			$box_list[1]['photo']['caption']=$box_list[1]['photo_caption'];
			$box_list[1]['photo']['author']=$box_list[1]['photo_author'];
			$box_list[1]['photo']['crop_dimension']=$box_list[1]['crop_dimension'];
		}	

       if(empty($box_list[2]) ) {
			$first_key = key($news['list']);
			$box_list[2]=$news['list'][$first_key];
			unset($news['list'][$first_key]);		
		}	
		else {
			$box_foto=$box_list[2]['photo'];
			$box_list[2]['photo']=array();
			$box_list[2]['photo']['path']=$box_foto;
			$box_list[2]['photo']['caption']=$box_list[2]['photo_caption'];
			$box_list[2]['photo']['author']=$box_list[2]['photo_author'];
			$box_list[2]['photo']['crop_dimension']=$box_list[2]['crop_dimension'];	
		}
		if(empty($box_list[3]) and !empty($news['list'])) {
			$first_key = key($news['list']);
			$box_list[3]=$news['list'][$first_key];
			unset($news['list'][$first_key]);
		}
		else {
			$box_foto=$box_list[3]['photo'];
			$box_list[3]['photo']=array();
			$box_list[3]['photo']['path']=$box_foto;
			$box_list[3]['photo']['caption']=$box_list[3]['photo_caption'];
			$box_list[3]['photo']['author']=$box_list[3]['photo_author'];
			$box_list[3]['photo']['crop_dimension']=$box_list[3]['crop_dimension'];
		}
		
		return view('Modules\Flavors\Views\user\homeNewsBox',array('box_list'=>$box_list));
	}
	
	 public function assets($slug = '', $template = '', $id_news=0, $data=array()) {
        $assets = array(
            'js' => array('/assets/js/jquery.raty.js'),
            'css' => array(),
            'js_ready' => array()
        );
		switch ($slug) {
			case 'map':
			 $assets['js'][] = 'https://maps.google.com/maps/api/js?key=' . (!empty($this->settings) && !empty($this->settings['widget_gmjs']) ? $this->settings['widget_gmjs'] : '');
			break;
			case 'selected':
				$assets['css'][] = '/assets/css/slick.css';
                $assets['js'][] = '/assets/js/slick.min.js';
				$assets['js'][] = 'https://maps.google.com/maps/api/js?key=' . (!empty($this->settings) && !empty($this->settings['widget_gmjs']) ? $this->settings['widget_gmjs'] : '');
			break;
            case 'single_element':
			        $assets['js'][] = 'https://maps.google.com/maps/api/js?key=' . (!empty($this->settings) && !empty($this->settings['widget_gmjs']) ? $this->settings['widget_gmjs'] : '');
                    $assets['js_ready'][]='$("#restaurant-single .menu a").TiO_lightbox({rel:"menu"});';
            break;
		}
        return $assets;
    }
	
	 public function ajax($post, $id_lang, $module_data, $link) 
    {
		 if(!empty($post['action'])) {
            switch($post['action']) {
				 case 'marker':
				 $data=$this->RestaurantMarker($link,$id_lang);
				 if(empty($data['ico']['path'])) {$data['ico']['path']='';}
		         $html = view('Modules\Flavors\Views\user\\restaurant_marker', array('data' => $data,'link'=>$link));
				 $response = array(
                        'status' => true,
						'lat'=>$post['lat'],
						'lng'=>$post['lng'],
                        'html' => base64_encode(urlencode($html)),
						'ico'=>$data['ico']['path']
                    );
                 return $this->response->setJSON($response);
				 break;
				 case 'rate':
				   $data_form=array();
				   $response_status=false;
				   $session_user=$this->session->get('user');
				   if(!empty($post['data_form'])) {
					   parse_str($post['data_form'], $data_form);
					   $response_status=$this->flavorsGradesModel->SaveRestaurantRate($post,$data_form,$id_lang,$this->request->getIPAddress(),$session_user);
				   }
				   $user_rates=array();
				   if(!empty($session_user)) {
				    $user_rates=$this->flavorsGradesModel->GetRestaurantUserRate($post['id_restaurant'],$id_lang,$session_user);
				   }	
					$data=$this->restaurantModel->GetRestaurantListById($post['id_restaurant'],$id_lang);
					 $html = view('Modules\Flavors\Views\user\\restaurant_rate_modal', array('data' => $data,'session_user'=>$session_user,'user_rates'=>$user_rates,'data_form'=>$data_form,'response'=> $response_status));
					 $response = array(
                        'status' => true,
						'session_user'=>$session_user,
						'response_status'=>$response_status,
						'url'=>$post['url'],
						'id_comment'=>$post['id_comment'],
						'id_restaurant'=>$post['id_restaurant'],
						'title'=>'Oceniasz lokal: '.$data['name'],
						'link'=>$link,
                        'html' => base64_encode(urlencode($html))
                    );
				 return $this->response->setJSON($response);
				 break;
			}
		 }			
	}	
	
	public function custom_settings($settings) {
	  if(!empty($settings['favicon_flavor'])) {
        $settings['favicon']=$settings['favicon_flavor'];
      } 		  
		
	  return $settings;
    }
	
	public function custom_metatags($metatags,$settings) {
	  if(!empty($settings['favicon_flavor']['path'])) {
		  $metatags['favicon']=$metatags['url'].'/image/'.$settings['favicon_flavor']['path'];
		  $metatags['apple_icon']=$metatags['url'].'/image/'.$settings['favicon_flavor']['path'];
	  }	  
	  return $metatags;
    }
	
	public function RestaurantMarker($link,$id_lang) {
		if(!empty($link['id'])) {
		    $data=$this->restaurantModel->db->table('flavors_restaurant f')->join('flavors_restaurant_lang fl', 'f.id=fl.id_restaurant')->Select('f.id,fl.name,fl.address,fl.city,f.id_category,fl.phone')->Where('fl.id_lang',$id_lang)->Where('fl.id_link',$link['id'])->Where('f.archives',0)->Where('f.publish',1)->Where('f.id_category>',0)->get()->getRowArray();
			if(!empty($data['id_category'])) {
				$data['ico']=$this->restaurantModel->db->table('flavors_categories_files f')->join('flavors_categories_files_lang fl', 'f.id = fl.id_file')->Select('path,caption')->Where('field','mapicon')->where('fl.id_lang', $id_lang)->where('f.id_category', $data['id_category'])->get()->getRowArray();
			}
			$data['logo']=$this->restaurantModel->db->table('flavors_restaurant_files f')->join('flavors_restaurant_files_lang fl', 'f.id = fl.id_file')->Select('path,caption')->Where('field','logo')->where('fl.id_lang', $id_lang)->where('f.id_restaurant', $data['id'])->get()->getRowArray();
			if(empty($data['logo'])) {
			  $data['logo']=$this->restaurantModel->db->table('flavors_restaurant_files f')->join('flavors_restaurant_files_lang fl', 'f.id = fl.id_file')->Select('path,caption')->Where('field','photo')->where('fl.id_lang', $id_lang)->where('f.id_restaurant', $data['id'])->get()->getRowArray();	
			}	
		    return $data;
		}
	}	
	
	public function RestaurantsMap($id_lang) {
		$maps=$this->restaurantModel->GetRestaurantsMap($id_lang,array());
		return view('Modules\Flavors\Views\user\home_restaurant_maps',array('maps'=>$maps));		
	}
	
}
?>	