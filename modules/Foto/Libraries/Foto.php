<?php

namespace Modules\Foto\Libraries;

use Modules\Foto\Models\FotoModel;
use Modules\Foto\Models\FotoGalleryModel;
use Modules\Foto\Models\FotoCategoryModel;

class Foto {
	
	
	public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->fotoModel = new FotoModel();
		$this->fotoGalleryModel = new FotoGalleryModel();
		$this->fotoCategoryModel = new FotoCategoryModel();
    }
	
	public function index($content, $id_lang, $slug = '',$link) {
            $config = new \Config\Pager;
		$get = $this->request->getGet();
		$user_l=$this->fotoGalleryModel->db->table('links')->Select('link')->Where('slug','user_photo')->get()->getRowArray();
		$photo_l=$this->fotoGalleryModel->db->table('settings')->join('settings_lang l','l.id_settings=settings.id')->Select('l.value')->Where('settings.name','url_single_photo')->where('l.id_lang', $id_lang)->get()->getRowArray();
		$gallery_l=$this->fotoGalleryModel->db->table('settings')->join('settings_lang l','l.id_settings=settings.id')->Select('l.value')->Where('settings.name','url_gallery_photo')->where('l.id_lang', $id_lang)->get()->getRowArray();
		$pager = service('pager');
		if(!empty($link['get']) and !empty($link['get'][0]) and $link['get'][0]=="user" and !empty($link['get'][1])) {
			$custom_meta=array();
			$user_data=$this->fotoModel->getUserInfo($link['get'][1],$id_lang);
			$custom_meta['title']=$user_data['name'].' - Galeria zdjęć - Rzeszów - Podkarpacie';
			
						if(!empty($user_data)) {
		                $cnt_galleries=$user_data['cnt']['gallery_count'];
						$perPage =16;
						if(empty($get['show'])) {
						$page    = (int) ($get['page'] ?? 1);
						}
						else {$page=1;}
						$user_data['gallery_pager'] = $pager->makeLinks($page,$perPage,$cnt_galleries, 'usergallery_full'); 
						 $od = ($page - 1) * $perPage; 
						if(empty($get['show'])) {
						$user_data['gallery']= $this->fotoGalleryModel->db->table('foto_gallery')
                        ->join('foto_gallery_lang gl', 'foto_gallery.id=gl.id_gallery')
                        ->join('links l', 'l.id=gl.id_link')
						->join('users u', 'u.id=foto_gallery.id_user', 'left')
                        ->select('foto_gallery.id,l.link,gl.name,number_of_photo,id_user,u.nick,u.name as user_name,u.surname as user_surname')
                        ->where('foto_gallery.publish', 1)
                        ->where('gl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang)
						->where('foto_gallery.id_user',$user_data['id'])
                         ->orderBy('foto_gallery.created_at', 'DESC')->limit($perPage,$od)->get()->getResultArray();
						if(!empty($user_data['gallery'])) {
						   foreach($user_data['gallery'] as $k=>$v) {	
								$foto=$this->fotoCategoryModel->db->table('foto_gallery_files')->join('foto_gallery_files_lang gl', 'foto_gallery_files.id=gl.id_file','left')->Select('path,caption,author')->where('foto_gallery_files.id_gallery',$v['id'])->where('foto_gallery_files.publish', 1)->OrderBy('main','DESC')->OrderBy('foto_gallery_files.id','ASC')->get()->getRowArray();
								if(!empty($foto)) {
									$user_data['gallery'][$k]['photo']=$foto['path'];
									$user_data['gallery'][$k]['photo_caption']=$foto['caption'];
									$user_data['gallery'][$k]['photo_author']=$foto['author'];
						        }
								$user_data['gallery'][$k]['user_link']='foto/g/user/'.crc32($v['id_user']);
								$_GET['user_link']='foto/g/user/'.crc32($v['id_user']);
								$_GET['user_name']=$v['nick'];
                           }						   
						}	
                        }
						$perPage =20;
						unset($_GET['test']);					
						$pager = service('pager');	
						if(!empty($get['show'])) {
						$page_p    = (int) ($get['page'] ?? 1);
						}
						else {$page_p=1;}
						if(!empty($get['show']) or empty($get['page'])) {
						$pager = service('pager');
						$cnt_photos=$user_data['cnt']['files'];
						$user_data['photo_pager'] = $pager->makeLinks($page_p,$perPage,$cnt_photos, 'userphoto_full'); 
						$od = ($page_p - 1) * $perPage;  
						 $user_data['photos']= $this->fotoModel->db->table('foto_files')
							->join('foto_files_lang gfl', 'gfl.id_file=foto_files.id', 'left')
							->join('users u', 'u.id=foto_files.id_user', 'left')
							->select('foto_files.id,gfl.name,foto_files.path as photo,id_user,u.nick,u.name as user_name,u.surname as user_surname,id_page_cont')
							->where('foto_files.publish', 1)
							->groupStart()
								->where('gfl.id_lang', $id_lang)
								->orWhere('gfl.id_lang', null)
							->groupEnd()
							->where('foto_files.id_user',$user_data['id'])
							->orderBy('foto_files.created_at', 'DESC')->limit($perPage,$od)->get()->getResultArray();
							if(!empty($user_data['photos'])) {
							   foreach($user_data['photos'] as $k=>$v) {
								  $user_data['photos'][$k]['user_link']='foto/g/user/'.crc32($v['id_user']);
								   if(!empty($v['name'])) {$user_data['photos'][$k]['link']=  'foto/zdjecia/g/'.mb_url_title($v['name'], '-', true).'/'.crc32($v['id']);}
								   else {$user_data['photos'][$k]['link']=  'foto/zdjecia/g/'.crc32($v['id']);}
							   }						   
							}	
						}							
				}	
				else {
					throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();	
					exit();
				}	
				
				return array('custom_data'=>array('file'=>$user_data,'template'=>'\Modules\Foto\Views/user/user','custom_meta'=>$custom_meta)); 
		}
		elseif(!empty($link['get']) and $slug=='photos') {
		  $photo_data=$this->fotoModel->getSinglePhoto(end($link['get']),$id_lang);
	
		  
		  if(empty($photo_data)) {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();	
				exit();				  			
			}
			else {
			  	$photo_data['user_link']=$user_l['link'];	
			}	

		    $custom_bread=array();
			
			if(!empty($photo_data['id_page_cont'])) {
			  $page_info=$this->fotoModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->Select('pc.id_page,p.re_id')->Where('pc.id',$photo_data['id_page_cont'])->get()->getRowArray();
			  if(!empty($page_info['re_id'])) {
				$page_info=$this->fotoModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->Select('pl.name,l.link')->Where('p.id',$page_info['re_id'])->Where('l.id_lang',$id_lang)->Where('pl.id_lang',$id_lang)->Where('p.publish',1)->get()->getRowArray();  
				  if(!empty($page_info['link'])) {
				     $custom_bread[]=array('name'=>$page_info['name'],'link'=>'/'.$page_info['link']);
				  }
			  }	  
			}
			$custom_meta=array();
			if(!empty($photo_data['name'])) {
				$custom_meta['title']=$photo_data['name'];
				$custom_meta['keywords']=$photo_data['name'];
			}
			if(!empty($photo_data['category']['main_cat']['name'])) {
				$custom_bread[]=array('name'=>$photo_data['category']['main_cat']['name'],'link'=>'/'.$photo_data['category']['main_cat']['link']);
				$custom_meta['title'].=' - '.$photo_data['category']['main_cat']['name'];
				$custom_meta['keywords'].=', '.$photo_data['category']['main_cat']['name'];
			}
		    if(!empty($photo_data['category']['name'])) {  $custom_bread[]=array('name'=>$photo_data['category']['name'],'link'=>'/'.$photo_data['category']['link']); 
			   $custom_meta['title'].=' - '.$photo_data['category']['name'];
			   $custom_meta['keywords'].=', '.$photo_data['category']['name'];
			}
			$custom_meta['title'].=' - Galeria zdjęć';
			$custom_meta['description']='Rzeszowska Galeria Zdjęć portalu RESinet.pl, zawierająca fotografie dodawane przez użytkowników portalu.';
			$custom_meta['keywords'].=', Galeria Zdjęć, fotografia, foto, fotki, zdjęcia, fotografie, foty';
			return array('custom_data'=>array('file'=>$photo_data,'template'=>'\Modules\Foto\Views/user/single_file','custom_breadcrumbs'=>$custom_bread,'custom_meta'=>$custom_meta));
			}
		elseif(!empty($get['search'])) {
			$search_data=array();		
			$search_data['search_term']=$get['search'];		
			$cnt_galleries=$this->fotoCategoryModel->db->table('foto_gallery')->join('foto_gallery_lang gl', 'foto_gallery.id=gl.id_gallery')->Select('foto_gallery.id')->groupStart()->like('gl.name',$get['search'])->orlike('gl.description',$get['search'])->orlike('gl.keywords',$get['search'])->groupEnd()->Where('foto_gallery.publish',1)->where('gl.id_lang', $id_lang)->countAllResults();
			$cnt_photos=$this->fotoCategoryModel->db->table('foto_files')->join('foto_files_lang gl', 'foto_files.id=gl.id_file')->Select('foto_files.id')->groupStart()->like('gl.name',$get['search'])->orlike('gl.description',$get['search'])->orlike('gl.keywords',$get['search'])->groupEnd()->Where('foto_files.publish',1)->where('gl.id_lang', $id_lang)->countAllResults();
			$search_data['cnt_galleries']=$cnt_galleries;
		    $search_data['cnt_photos']=$cnt_photos;
			$pager = service('pager');
			$page    = (int) ($get['page'] ?? 1);
			$total=($cnt_galleries*2)+$cnt_photos;
			$perPage =16;
			$show_number=$perPage;
		    $search_data['pager'] = $pager->makeLinks($page,$perPage, $total, 'front_full');
                      $od = ($page - 1) * $perPage;
					  if($cnt_galleries>0) {
						$perPage=$perPage/2;  
					    $od = ($page - 1) * ($perPage);
					    $search_data['gallery']= $this->fotoGalleryModel->db->table('foto_gallery')
                        ->join('foto_gallery_lang gl', 'foto_gallery.id=gl.id_gallery')
                        ->join('links l', 'l.id=gl.id_link')
						->join('users u', 'u.id=foto_gallery.id_user', 'left')
                        ->select('foto_gallery.id,l.link,gl.name,number_of_photo,id_user,u.nick,u.name as user_name,u.surname as user_surname')
                        ->where('foto_gallery.publish', 1)
                        ->where('gl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang)
						->groupStart()->like('gl.name',$get['search'])->orlike('gl.description',$get['search'])->orlike('gl.keywords',$get['search'])->groupEnd()
                         ->orderBy('foto_gallery.created_at', 'DESC')->limit($perPage,$od)->get()->getResultArray();
						if(!empty( $search_data['gallery'])) {
						   foreach( $search_data['gallery'] as $k=>$v) {	
								$foto=$this->fotoCategoryModel->db->table('foto_gallery_files')->join('foto_gallery_files_lang gl', 'foto_gallery_files.id=gl.id_file','left')->Select('path,caption,author')->where('foto_gallery_files.id_gallery',$v['id'])->groupStart()->where('gl.id_lang', $id_lang)->orWhere('gl.id_lang', null)->groupEnd()->where('foto_gallery_files.publish', 1)->OrderBy('main','DESC')->OrderBy('foto_gallery_files.id','ASC')->get()->getRowArray();
								if(!empty($foto)) {
									$search_data['gallery'][$k]['photo']=$foto['path'];
									$search_data['gallery'][$k]['photo_caption']=$foto['caption'];
									$search_data['gallery'][$k]['photo_author']=$foto['author'];
						        }
								$search_data['gallery'][$k]['user_link']='foto/g/user/'.crc32($v['id_user']);
                           }						   
						}	
                      }
					   	  if($cnt_galleries>0) {
							  
							  $perPage=$show_number;  
							 if(!empty($search_data['gallery']) and (count($search_data['gallery'])*2)<$show_number) { 
									$perPage=$show_number-(count($search_data['gallery'])*2);
									$od = ($page-ceil(floor($cnt_galleries*2)/$show_number))*$perPage;
							 } 
							 else {
								 $przesun_foto=($cnt_galleries*2)%$show_number; 
								 $od = ((($page-ceil(floor($cnt_galleries*2)/$show_number))*$perPage))-$przesun_foto;
							 }	 
					      }
						if($od >=0) {	
						  $search_data['photos']= $this->fotoModel->db->table('foto_files')
							->join('foto_files_lang gfl', 'gfl.id_file=foto_files.id', 'left')
							->join('users u', 'u.id=foto_files.id_user', 'left')
							->select('foto_files.id,gfl.name,foto_files.path as photo,id_user,u.nick,u.name as user_name,u.surname as user_surname,id_page_cont')
							->where('foto_files.publish', 1)
							->groupStart()
								->where('gfl.id_lang', $id_lang)
								->orWhere('gfl.id_lang', null)
							->groupEnd()
							->groupStart()->like('gfl.name',$get['search'])->orlike('gfl.description',$get['search'])->orlike('gfl.keywords',$get['search'])->groupEnd()
							->orderBy('foto_files.created_at', 'DESC')->limit($perPage,$od)->get()->getResultArray();
							if(!empty($search_data['photos'])) {
							   foreach($search_data['photos'] as $k=>$v) {
								  $search_data['photos'][$k]['user_link']='foto/g/user/'.crc32($v['id_user']);
								   if(!empty($v['name'])) {$search_data['photos'][$k]['link']=  'foto/zdjecia/g/'.mb_url_title($v['name'], '-', true).'/'.crc32($v['id']);}
								   else {$search_data['photos'][$k]['link']=  'foto/zdjecia/g/'.crc32($v['id']);}
							   }						   
							}	
						}	  
			return array('custom_data'=>array('file'=>$search_data,'template'=>'\Modules\Foto\Views/user/search_photo'));
		}	
        else {		
		switch ($slug) {
          case 'gallery':
		   $query = $this->fotoGalleryModel
                        ->join('foto_gallery_lang gl', 'foto_gallery.id=gl.id_gallery')
                        ->join('links l', 'l.id=gl.id_link')
						->join('users u', 'u.id=foto_gallery.id_user', 'left')
                        ->select('foto_gallery.id,l.link,gl.name,number_of_photo,id_user,u.nick,u.name as user_name,u.surname as user_surname')
                        ->where('foto_gallery.publish', 1)
                        ->where('gl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang);
						
			switch ($content['config']['order']) {
                    case 'latest':
                    default: $query->orderBy('foto_gallery.created_at', 'DESC');
                        break;
                }			
						
						
		   $gallery = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'gallery-' . $content['id']);
		   if (!empty($gallery)) {
                    foreach ($gallery as $k => $g) {
						$foto=$this->fotoCategoryModel->db->table('foto_gallery_files')->join('foto_gallery_files_lang gl', 'foto_gallery_files.id=gl.id_file','left')->Select('path,caption,author')->where('foto_gallery_files.id_gallery',$g['id'])->groupStart()->where('gl.id_lang', $id_lang)->orWhere('gl.id_lang', null)->groupEnd()->where('foto_gallery_files.publish', 1)->OrderBy('main','DESC')->OrderBy('foto_gallery_files.id','ASC')->get()->getRowArray();
								if(!empty($foto)) {
									$gallery[$k]['photo']=$foto['path'];
									$gallery[$k]['photo_caption']=$foto['caption'];
									$gallery[$k]['photo_author']=$foto['author'];
						        }
                        $gallery[$k]['link'] = ($this->locale ? $this->locale . '/' : '') . $g['link'];
						if(!empty($g['id_user'])) {
							$gallery[$k]['user_link']='foto/g/user/'.crc32($g['id_user']);
						}	
                    }
           }
			return array(
                    'views_dir' => 'gallery',
                    'list' => $gallery,
                    'pager' => !empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination'] ? $this->fotoGalleryModel->pager : null,
            );
            break;	
            case 'photos':
				$query = $this->fotoModel
                        ->join('foto_files_lang gfl', 'gfl.id_file=foto_files.id', 'left')
						->join('users u', 'u.id=foto_files.id_user', 'left')
                        ->select('foto_files.id,gfl.name,foto_files.path as photo,id_user,u.nick,u.name as user_name,u.surname as user_surname,id_page_cont')
                        ->where('foto_files.publish', 1)
                        ->groupStart()
                            ->where('gfl.id_lang', $id_lang)
                            ->orWhere('gfl.id_lang', null)
                        ->groupEnd();
            switch ($content['config']['order']) {
                    case 'latest':
                    default: $query->orderBy('foto_files.created_at', 'DESC');
                        break;
                }	
				$photos = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'photo-' . $content['id']);
				if(!empty($photos)) {
				  foreach($photos as $k=>$v) {
					 
						 $photos[$k]['user_link']='foto/g/user/'.crc32($v['id_user']);
						 if(!empty($v['name'])) {$photos[$k]['link']= 'foto/zdjecia/g/'.mb_url_title($v['name'], '-', true).'/'.crc32($v['id']);}
						 else {$photos[$k]['link']=  'foto/zdjecia/g/'.crc32($v['id']);}
				  }
				}
				return array(
                    'views_dir' => 'files',
                    'list' => $photos,
                    'pager' => !empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination'] ? $this->fotoModel->pager : null,
            );
            break;	
            case 'categories':
				$structure=$this->fotoCategoryModel->getCategoryStructure($id_lang,0,'',0,true);
				return array(
                    'views_dir' => 'categories',
                    'categories_list' => $structure
                );
			break;
			case 'selected_gallery':
				$query = $this->fotoGalleryModel;
				if(empty($content['config']['pagination'])) {
				$query->db->table('foto_gallery');
				}
                $query->join('foto_gallery_lang nl', 'foto_gallery.id=nl.id_gallery')
                        ->join('links l', 'l.id=nl.id_link')
                        ->select('foto_gallery.id,foto_gallery.number_of_photo,foto_gallery.id_category,nl.name,l.link,l.redirect as link_redirect')
                        ->where('foto_gallery.publish', 1)
                        ->where('nl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang);

                if (empty($content['config']['order'])) {
                    $content['config']['order'] = '';
                }
                if(!empty($content['config']['option'])) {
                    switch ($content['config']['option']) {
                        case 'home': $query->where('foto_gallery.home', 1);
                            break;
                    }
                }
				 if(!empty($content['config']['category'])) {
					  $query->where('foto_gallery.id_category', $content['config']['category']);
					 
				 }	 
				
				
                if (empty($content['config']['order'])) {
                    $content['config']['order'] = '';
                }
                switch ($content['config']['order']) {
                    case 'random':
                        $query->orderBy('foto_gallery.order', 'RANDOM');
                        break;
                    case 'date':
                        $query->orderBy('foto_gallery.created_at', 'DESC');
                        break;
                    case 'latest':
					$query->orderBy('foto_gallery.created_at', 'DESC');
					break;
                    default: $query->orderBy('foto_gallery.id', 'DESC');
                        break;
                }
                if(!empty($content['config']['pagination'])) {
				$news = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'news-' . $content['id']);
				}
				else {
					$news=$query->Limit(intVal(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage))->get()->getResultArray();
				}	
                if (!empty($news)) {
                    foreach ($news as $k => $n) {
						$photo=$this->fotoGalleryModel->db->table('foto_gallery_files f')->join('foto_gallery_files_lang nfl', 'nfl.id_file=f.id', 'left')->Where('nfl.id_Lang',$id_lang)->Where('f.id_gallery',$n['id'])->OrderBy('main','DESC')->OrderBy('f.id','ASC')->get()->getRowArray();
						$news[$k]['photo_author']=$photo['author'];
						$news[$k]['photo_caption']=$photo['caption'];
						$news[$k]['photo']=$photo['path'];
                        $news[$k]['link'] = (!$n['link_redirect'] && $this->locale ? $this->locale . '/' : '') . $n['link'];
						$news[$k]['cats']=$this->fotoCategoryModel->getSinglePhotoCategories($n['id_category'],$id_lang);
                    }
                }
                return array(
                    'views_dir' => 'list',
                    'list' => $news,
                    'pager' => !empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination'] ? $this->newsModel->pager : null,
                );
                break;
				case 'rzeszow':
				if(!empty($content['config']['cat'])) {
				   $subcat=$this->fotoCategoryModel->getCategoryStructure($id_lang,$content['config']['cat']);
				   $cats=array();
				   $cats[]=$content['config']['cat'];
				   if(!empty($subcat)) {
					  foreach($subcat as $scat) { 
					   $cats[]=$scat['id'];
					  }
				   }	   
				$query = $this->fotoModel->db->table('foto_files')
                        ->join('foto_files_lang fl', 'foto_files.id=fl.id_file')
                        ->select('foto_files.id,fl.name,foto_files.path')
                        ->where('foto_files.publish', 1)
                        ->where('fl.id_lang', $id_lang)
                        ->whereIn('foto_files.id_category', $cats);
                if(!empty($content['config']['no'])) {
				   $query->limit($content['config']['no']);	
				}	
				if(!empty($content['config']['order']) and $content['config']['order']=='latest') {
					$query->OrderBy('foto_files.created_at','DESC');
				}	
				if(!empty($content['config']['order']) and $content['config']['order']=='random') {
					$query->OrderBy('foto_files.created_at','RANDOM');
				}
                $list_photo=$query->get()->getResultArray();
				if(!empty($list_photo)) {
				   foreach($list_photo as $k=>$v) {
						if(!empty($v['name'])) {$list_photo[$k]['link']=  'foto/zdjecia/g/'.mb_url_title($v['name'], '-', true).'/'.crc32($v['id']);}
						else {$list_photo[$k]['link']=  'foto/zdjecia/g/'.crc32($v['id']);}
				   }	
				}	
				return array(
                    'views_dir' => 'home',
                    'list' =>$list_photo
                );
				}
				break;
		}
		}
	}
	public function getSingleByLinkId($id_link, $id_lang,$link) {
	    $session = \Config\Services::session();
		$photo_l=$this->fotoGalleryModel->db->table('settings')->join('settings_lang l','l.id_settings=settings.id')->Select('l.value')->Where('settings.name','url_single_photo')->where('l.id_lang', $id_lang)->get()->getRowArray();
		$gallery_l=$this->fotoGalleryModel->db->table('settings')->join('settings_lang l','l.id_settings=settings.id')->Select('l.value')->Where('settings.name','url_gallery_photo')->where('l.id_lang', $id_lang)->get()->getRowArray();
		switch ($link['action']) {
			case 'gallery':
			$gallery=$this->fotoGalleryModel
				->join('foto_gallery_lang fl', 'foto_gallery.id=fl.id_gallery')
                ->join('page_content pc', 'foto_gallery.id_page_cont=pc.id')
				->join('page p', 'foto_gallery.id_page_cont=p.id')
                ->join('links l', 'l.id=fl.id_link')
				->join('users u', 'u.id=foto_gallery.id_user')
                ->select('foto_gallery.id,fl.name,p.template as page_template,id_user,foto_gallery.id_page_cont,link,pc.config,pc.template,p.re_id as page_re_id,foto_gallery.id_category,foto_gallery.created_at,description,keywords,u.name as user_name,u.surname as user_surname,u.nick as user_nick,u.id as user_id')
                ->where('foto_gallery.publish', 1)
                ->where('fl.id_lang', $id_lang)
                ->where('fl.id_link', $id_link)
				->where('u.active', 1)
                ->first();
			
			if(!empty($gallery)) {	
			 $gallery['related_gallery']=$this->fotoGalleryModel->GetRelatedGalleryList($gallery['id'],$id_lang);
				if(empty($_SESSION['gallery_view_stats_'.$gallery['id']])) {	
					$this->fotoGalleryModel->db->table('foto_gallery_lang')->set('views', 'views+1', false)->where('id_gallery', $gallery['id'])->update();
					$session->set('gallery_view_stats_'.$gallery['id'], '1');
				}	
	
			if(!empty($gallery['user_id'])) {
              $user_l=$this->fotoGalleryModel->db->table('links')->Select('link')->Where('slug','user_photo')->get()->getRowArray();
              if(!empty($user_l['link'])) {
				$gallery['user_link']='foto/g/user/'.crc32($gallery['user_id']);
              }				  
            }				
			   $gallery['photos']=$this->fotoGalleryModel->db->table('foto_gallery_files f')->join('foto_gallery_files_lang fl', 'f.id=fl.id_file')->Select('f.id,path,caption,author')->where('f.id_gallery', $gallery['id'])->where('fl.id_lang', $id_lang)->OrderBy('main','DESC')->OrderBy('order','ASC')->OrderBy('id','DESC')->get()->getResultArray();
			   if(!empty($gallery['photos'])) {
                                $gallery['main_photo']=$gallery['photos'][0];
                                $gallery['main_photo']['next_url']='';
                                $gallery['main_photo']['prev_url']='';
                           }
			   if(!empty($gallery['photos'])) {
                                if(!empty($gallery['photos'][1])) {
				   $gallery['main_photo']['next_url']=$gallery['link'].'/g/'.crc32($gallery['photos'][1]['id']); 
                                }
				   if(!empty($link['get'])) {
					   $photo_active='';
					   foreach($gallery['photos'] as $k=>$photo) {
						   if($link['get'][0]==crc32($photo['id'])) {
						       $photo_active=$k+1;
						   } 
					   }	   
					if(!empty($photo_active)) {
					  $page=$photo_active;	
					  $gallery['main_photo']=$gallery['photos'][($photo_active-1)];
					  if($page>1) {
					     $gallery['main_photo']['prev_url']=$gallery['link'].'/g/'.crc32($gallery['photos'][($photo_active-2)]['id']); 
					  }
					  if($page<count($gallery['photos'])) {
					    $gallery['main_photo']['next_url']=$gallery['link'].'/g/'.crc32($gallery['photos'][$photo_active]['id']);
					  }
				    }
					else {
					  throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();	
					  exit();	
					}	
				   
				   }
				   
				  if(!empty($page) and !empty($gallery['photos'][($page-1)]['id']) and empty($session->get('galleryp_view_stats_'.$gallery['photos'][($page-1)]['id']))) {
					$this->fotoGalleryModel->db->table('foto_gallery_files_lang')->set('views', 'views+1', false)->where('id_file', $gallery['photos'][($page-1)]['id'])->update();
					$session->set('galleryp_view_stats_'.$gallery['photos'][($page-1)]['id'], '1');
				  }
                  elseif(!empty($gallery['photos'][0]['id']) and empty($session->get('galleryp_view_stats_'.$gallery['photos'][0]['id']))) {
                      $this->fotoGalleryModel->db->table('foto_gallery_files_lang')->set('views', 'views+1', false)->where('id_file',$gallery['photos'][0]['id'])->update();
                      $session->set('galleryp_view_stats_'.$gallery['photos'][0]['id'], '1'); 
                  }					    
			   }	   
			    
			}
			else {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();	
			    exit();	
			}	
			
			return array(
			    'action'=>$link['action'],
				'id'=>$gallery['id'],
				'page'=> !empty($page) ? $page : 1,
				'page_re_id'=>$gallery['page_re_id'],
				'gallery'=>$gallery,
			    'page_template'=>!empty($gallery['page_template']) ? $gallery['page_template'] : '',
				'template'=>!empty($gallery['template']) ? '\Modules\Foto\Views/user/gallery/single_'.$gallery['template'] : '',
				'active_id'=>!empty($gallery['id']) ? $gallery['id'] : ''
		      );
			break;
			case 'category':
			   $category=$query = $this->fotoCategoryModel
				->join('foto_category_lang fl', 'foto_category.id=fl.id_category')
                ->join('page_content pc', 'foto_category.id_page_cont=pc.id')
				->join('page p', 'foto_category.id_page_cont=p.id')
                ->join('links l', 'l.id=fl.id_link')
                ->select('foto_category.id,fl.name,p.template as page_template,link,pc.config,pc.template,p.re_id as page_re_id,foto_category.re_id')
                ->where('foto_category.publish', 1)
                ->where('fl.id_lang', $id_lang)
                ->where('fl.id_link', $id_link)
                ->first();
				if(!empty($category['config'])) {
					$category['config']=unserialize($category['config']);
				}
				if(empty($category)) {
					throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();	
					exit();	
				}
				elseif(!empty($category['re_id'])) {
				    $cnt_galleries=$this->fotoCategoryModel->db->table('foto_gallery')->Select('id')->Where('publish',1)->Where('id_category',$category['id'])->countAllResults();
				    $cnt_photo=$this->fotoCategoryModel->db->table('foto_files')->Select('id')->Where('publish',1)->Where('id_category',$category['id'])->countAllResults();	
				    $total=($cnt_galleries*2)+$cnt_photo;
					$user_l=$this->fotoGalleryModel->db->table('links')->Select('link')->Where('slug','user_photo')->get()->getRowArray();
					if(!empty($category['config']['no'])) {
					  $pager = service('pager');
					  $page    = (int) ($this->request->getGet('page') ?? 1);
					  $perPage = $category['config']['no'];
					  $category['pager'] = $pager->makeLinks($page, $perPage, $total, 'front_full');
                      $od = ($page - 1) * $perPage;
					  if($cnt_galleries>0) {
						$perPage=$perPage/2;  
					    $od = ($page - 1) * ($perPage);
					    $category['gallery']= $this->fotoGalleryModel->db->table('foto_gallery')
                        ->join('foto_gallery_lang gl', 'foto_gallery.id=gl.id_gallery','left')
                        ->join('links l', 'l.id=gl.id_link','left')
						->join('users u', 'u.id=foto_gallery.id_user', 'left')
                        ->select('foto_gallery.id,l.link,gl.name,number_of_photo,id_user,u.nick,u.name as user_name,u.surname as user_surname')
                        ->where('foto_gallery.publish', 1)
                        ->where('gl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang)
						->where('foto_gallery.id_category', $category['id'])
                        ->orderBy('foto_gallery.created_at', 'DESC')->limit($perPage,$od)->get()->getResultArray();
						if(!empty($category['gallery'])) {
						   foreach($category['gallery'] as $k=>$v) {	
						       $foto=$this->fotoCategoryModel->db->table('foto_gallery_files')->join('foto_gallery_files_lang gl', 'foto_gallery_files.id=gl.id_file','left')->Select('path,caption,author')->where('foto_gallery_files.id_gallery',$v['id'])->groupStart()->where('gl.id_lang', $id_lang)->orWhere('gl.id_lang', null)->groupEnd()->where('foto_gallery_files.publish', 1)->OrderBy('main','DESC')->OrderBy('foto_gallery_files.id','ASC')->get()->getRowArray();
								if(!empty($foto)) {
									$category['gallery'][$k]['photo']=$foto['path'];
									$category['gallery'][$k]['photo_caption']=$foto['caption'];
									$category['gallery'][$k]['photo_author']=$foto['author'];
						        }
								$category['gallery'][$k]['user_link']='foto/g/user/'.crc32($v['id_user']);
                           }						   
						}	
                      }
					   	  if($cnt_galleries>0) {
							  
							  $perPage=$category['config']['no'];  
							 if(!empty($category['gallery']) and (count($category['gallery'])*2)<$category['config']['no']) { 
									$perPage=$category['config']['no']-(count($category['gallery'])*2);
									$od = ($page-ceil(floor($cnt_galleries*2)/$category['config']['no']))*$perPage;
							 } 
							 else {
								 $przesun_foto=($cnt_galleries*2)%$category['config']['no']; 
								 $od = ((($page-ceil(floor($cnt_galleries*2)/$category['config']['no']))*$perPage))-$przesun_foto;
							 }	 
					      }
						if($od >=0) {
						  $category['photos']= $this->fotoModel->db->table('foto_files')
							->join('foto_files_lang gfl', 'gfl.id_file=foto_files.id', 'left')
							->join('users u', 'u.id=foto_files.id_user', 'left')
							->select('foto_files.id,gfl.name,foto_files.path as photo,id_user,u.nick,u.name as user_name,u.surname as user_surname,id_page_cont')
							->where('foto_files.publish', 1)
							->groupStart()
								->where('gfl.id_lang', $id_lang)
								->orWhere('gfl.id_lang', null)
							->groupEnd()
							->orderBy('foto_files.created_at', 'DESC')
							->where('foto_files.id_category', $category['id'])->limit($perPage,$od)->get()->getResultArray();
							if(!empty($category['photos'])) {
							   foreach($category['photos'] as $k=>$v) {
								   $category['photos'][$k]['user_link']='foto/g/user/'.crc32($v['id_user']);
								   if(!empty($v['name'])) {$category['photos'][$k]['link']=  'foto/zdjecia/g/'.mb_url_title($v['name'], '-', true).'/'.crc32($v['id']);}
								   else {$category['photos'][$k]['link']=  'foto/zdjecia/g/'.crc32($v['id']);}
							   }						   
							}	
						}
                    }						
				}
				else {
				   $category['categoryPhotoCatList']=$this->fotoCategoryModel->showPhotoCatList($category['id'],$id_lang);
				}
			  return array(
			    'action'=>$link['action'],
				'id'=>$category['id'],
				'page_re_id'=>$category['page_re_id'],
				'category'=>$category,
			    'page_template'=>!empty($category['page_template']) ? $category['page_template'] : '',
				'template'=>!empty($category['template']) ? '\Modules\Foto\Views/user/categories/'.$category['template'] : '',
				'active_id'=>!empty($category['id']) ? $category['id'] : ''
		      );
		    break;
		}	
	}	
	
	public function getBreadcramb($data,$locale, $id_lang,$link) {
		if(!empty($data['page_re_id'])) {
		   $page_bread=$this->fotoModel->db->table('page p')->join('page_lang pl', 'pl.id_page=p.id')->join('links l', 'l.id=pl.id_link')->Select('p.id,pl.name,l.link')->where('p.id', $data['page_re_id'])->where('p.publish', 1)->where('pl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->get()->getRowArray();
		   if(!empty( $page_bread)) {
				$bread['id']= $page_bread['id'];
				$bread['name']=$page_bread['name'];
				$bread['link']=($locale ? '/' . $locale : '') . '/'.$page_bread['link'];
				$bread_list[]=$bread;
		   }
		}
		
		if(!empty($link['action']) and $link['action']=='category') {
		    if(!empty($data['category']['re_id'])) {
				$cat_bread=$this->fotoCategoryModel->join('foto_category_lang pl', 'pl.id_category=foto_category.id')->join('links l', 'l.id=pl.id_link')->Select('foto_category.id,pl.name,l.link')->where('foto_category.id',$data['category']['re_id'])->where('foto_category.publish', 1)->where('pl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->get()->getRowArray();
			   if(!empty( $cat_bread)) {
					$bread['id']= $cat_bread['id'];
					$bread['name']=$cat_bread['name'];
					$bread['link']=($locale ? '/' . $locale : '') . '/'.$cat_bread['link'];
					$bread_list[]=$bread;
			   }
		    }
			if(!empty($data['category']['name'])) {
			
					$bread['id']= $data['category']['id'];
					$bread['name']=$data['category']['name'];
					$bread['link']=($locale ? '/' . $locale : '') . '/'.$data['category']['link'];
					$bread_list[]=$bread;
		    }
		}

		if(!empty($link['action']) and $link['action']=='gallery') {
			if(!empty($data['gallery']['id_page_cont'])) {
				$page_bread=$this->fotoModel->db->table('page p')->join('page_lang pl', 'pl.id_page=p.id')->join('links l', 'l.id=pl.id_link')->Select('p.id,pl.name,l.link')->where('p.id', $data['gallery']['id_page_cont'])->where('p.publish', 1)->where('pl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->get()->getRowArray();
			   if(!empty( $page_bread)) {
					$bread['id']= $page_bread['id'];
					$bread['name']=$page_bread['name'];
					$bread['link']=($locale ? '/' . $locale : '') . '/'.$page_bread['link'];
					$bread_list[]=$bread;
			   }		
			}		
			
			if(!empty($data['gallery']['name'])) {
						$bread['id']= $data['gallery']['id'];
						$bread['name']=$data['gallery']['name'];
						$bread['link']=($locale ? '/' . $locale : '') . '/'.$data['gallery']['link'];
						$bread_list[]=$bread;
				}
			}
		return $bread_list;
	}	
	
	
	public function showStats() {
		$Stats['photos']=$this->fotoModel->db->table('foto_files')->where('publish', 1)->countAllResults();
		$Stats['gallery']=$this->fotoModel->db->table('foto_gallery')->where('publish', 1)->countAllResults();
		$Stats['gallery_files']=$this->fotoModel->db->table('foto_gallery_files f')->join('foto_gallery g', 'g.id=f.id_gallery')->where('g.publish', 1)->countAllResults();
		$Stats['photos_unpublish']=$this->fotoModel->db->table('foto_files')->where('publish', 0)->countAllResults();
		$Stats['gallery_unpublish']=$this->fotoModel->db->table('foto_gallery_files f')->join('foto_gallery g', 'g.id=f.id_gallery')->where('g.publish', 0)->countAllResults();
		$Stats['photos_user']=$this->fotoModel->db->table('foto_files')->GroupBy('id_user')->countAllResults();
		$Stats['gallery_user']=$this->fotoModel->db->table('foto_gallery')->GroupBy('id_user')->countAllResults();
	  return view('Modules\Foto\Views\user\foto_stats',array('stats'=>$Stats));	
	}
	
	public function showCats($id_lang,$id=0,$active_id=0) {
		
		$pages=$this->fotoCategoryModel->getCategoryStructure($id_lang, 0, array($id),0,true);
		return view('Modules\Foto\Views\user\categories',array('pages'=>$pages,'active_id'=>$active_id));
		
	}
	
	public function getMetaTags($id, $id_lang, $link, $data=array()) {
		$meta_tags = array();
       if(!empty($link['action'])) {
		   switch ($link['action']) {
			   case 'category':
				   $category_meta=$this->fotoCategoryModel
					->join('foto_category_lang cl', 'foto_category.id=cl.id_category')
					->join('foto_category_meta_lang ml', 'foto_category.id=ml.id_category')
					->select('cl.name,ml.title,ml.description,ml.keywords,foto_category.re_id')
					->where('foto_category.publish', 1)
					->where('ml.id_lang', $id_lang)
					->where('cl.id_lang', $id_lang)
					->where('foto_category.id', $id)
					->first();
					if(!empty($category_meta['re_id'])) {
						$re_category=$this->fotoCategoryModel
						->join('foto_category_lang cl', 'foto_category.id=cl.id_category')
						->select('cl.name')
						->where('foto_category.publish', 1)
						->where('cl.id_lang', $id_lang)
						->where('foto_category.id',$category_meta['re_id'])
						->first();
						$meta_tags['title']=$category_meta['name'].' - '.$re_category['name'].' - Galeria zdjęć';
						$meta_tags['keywords']=$category_meta['name'].', '.$re_category['name'].', Podkarpacie, Galeria Zdjęć, fotografia, foto, fotki, zdjęcia, fotografie, foty';
					}
					else {
						$meta_tags['title']=$category_meta['name'].' - Galeria zdjęć';
						$meta_tags['keywords']=$category_meta['name'].', Podkarpacie, Galeria Zdjęć, fotografia, foto, fotki, zdjęcia, fotografie, foty';
					}	
					  $meta_tags['description']='Rzeszowska Galeria Zdjęć portalu RESinet.pl, zawierająca fotografie dodawane przez użytkowników portalu.';
					if(!empty($category_meta['title'])) {
					   $meta_tags['title']=$category_meta['title'];
				   }
				   if(!empty($category_meta['keywords'])) {
					   $meta_tags['keywords']=$category_meta['keywords'];
				   }
				   if(!empty($category_meta['description'])) {
					   $meta_tags['description']=$category_meta['description'];
				   }
				break;
			  case 'gallery':
                 $gallery_meta=$this->fotoGalleryModel
					->join('foto_gallery_lang gl', 'foto_gallery.id=gl.id_gallery')
					->select('gl.name,foto_gallery.id_category')
					->where('foto_gallery.publish', 1)
					->where('gl.id_lang', $id_lang)
					->where('foto_gallery.id', $id)
					->first();
			   $meta_tags['title']=$gallery_meta['name'].' - Galeria zdjęć';		
					$category_meta=$this->fotoCategoryModel
					->join('foto_category_lang cl', 'foto_category.id=cl.id_category')
					->select('cl.name,foto_category.re_id')
					->where('foto_category.publish', 1)
					->where('cl.id_lang', $id_lang)
					->where('foto_category.id',$gallery_meta['id_category'])
					->first();
					if(!empty($category_meta['re_id'])) {
						$re_category=$this->fotoCategoryModel
						->join('foto_category_lang cl', 'foto_category.id=cl.id_category')
						->select('cl.name')
						->where('foto_category.publish', 1)
						->where('cl.id_lang', $id_lang)
						->where('foto_category.id',$category_meta['re_id'])
						->first();
						$meta_tags['title'].=' - '.$re_category['name'];
					}
					
					
					$foto=$this->fotoCategoryModel->db->table('foto_gallery_files')->join('foto_gallery_files_lang gl', 'foto_gallery_files.id=gl.id_file','left')->Select('path,ext')->where('foto_gallery_files.id_gallery',$id)->where('foto_gallery_files.publish', 1)->OrderBy('main','DESC')->OrderBy('foto_gallery_files.id','ASC')->get()->getRowArray();
					if(!empty($foto['path'])) {
                       $meta_tags['image']='https://www.resinet.pl/image/original/'.$foto['path'];
                       $meta_tags['image_type']=$foto['ext'];  
					   $meta_tags['image_alt']=$gallery_meta['name'].', Podkarpacie, Galeria Zdjęć, fotografia, foto, fotki, zdjęcia, fotografie, foty'; 
					}	
                    $meta_tags['title'].=' - '.$category_meta['name'];
					$meta_tags['keywords']= $gallery_meta['name'].', Podkarpacie, Galeria Zdjęć, fotografia, foto, fotki, zdjęcia, fotografie, foty';
					$meta_tags['description']='Rzeszowska Galeria Zdjęć portalu RESinet.pl, zawierająca fotografie dodawane przez użytkowników portalu.';
              break;   			  
		   } 
	   }	    
	   return $meta_tags;
    }		
	
	
}
?>	