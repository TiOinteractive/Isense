<?php

namespace Modules\News\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use Modules\Tags\Models\TagsModel;

class NewsModel extends Model{

    protected $table = 'news';
    
    protected $allowedFields = [
        'id_page_cont',
        'template',
        'order',
        'home',
        'publish',
        'newsletter',
        'date',
        'comment',
        'publish_date',
        'edited_at',
        'created_at',
        'dont_miss',
        'investments',
        'show_in_box',
        'patronate',
        'slider',
        'script'
    ];
    
    public function getNewsById($id, $id_lang) 
    {
        $news = $this->where('id', $id)->first();
        if(!empty($news)) {
            if(empty($news['date']) || strtotime($news['date']) < 0) {
                $news['date'] = '';
            }
            $news['lang'] = $this->getNewsLang($id);
            if(!empty($news['lang']) && !empty($news['lang'][$id_lang]) && !empty($news['lang'][$id_lang]['title'])) {
                $news['name'] = $news['lang'][$id_lang]['title'];
            } else {
                $news['name'] = '';
            }
            $news['meta']['lang'] = $this->getNewsMetaLang($id);
            $news['photo'] = $this->getNewsFile($id, 'photo');
            $news['photos'] = $this->getNewsFiles($id, 'photos');
            $news['audio'] = $this->getNewsFiles($id, 'audio');
            $news['video'] = $this->getNewsFiles($id, 'video');
        }
        return $news;
    }
    
    private function getNewsLang($id_news) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('news_lang')->where('id_news', $id_news)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            $this->tagsModel = new TagsModel();
            foreach($data as $d) {
                if(!empty($d['tags'])) {
                    $d['tags']=$this->tagsModel->GetTagsById($d['tags']);
                }	
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang'], true);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getNewsMetaLang($id_news) 
    {
        $langs = array();
        $data = $this->db->table('news_meta_lang')->where('id_news', $id_news)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    private function getNewsFile($id_news, $field='') 
    {
        $file = $this->db->table('news_files')->select('id,name,basename,path,order,type,publish,ext,crop_dimension')->where('id_news', $id_news)->where('field', $field)->orderBy('order', 'ASC')->get()->getRowArray();
        if(!empty($file)) {
            $file['lang'] = $this->getNewsFileLang($file['id']);
        }
		if(!empty($file['crop_dimension'])) {
			$file['crop_dimension']=json_decode($file['crop_dimension']);
		}	
        return $file;
    }
    
    private function getNewsFiles($id_news, $field='') 
    {
        $files = $this->db->table('news_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_news', $id_news)->where('field', $field)->orderBy('order', 'ASC')->get()->getResultArray();
        if(!empty($files)) {
            foreach($files as $k=>$file) {
                $files[$k]['lang'] = $this->getNewsFileLang($file['id']);
            }
        }
        return $files;
    }
    
    private function getNewsFileLang($id_file) 
    {
        $langs = array();
        $data = $this->db->table('news_files_lang')->where('id_file', $id_file)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveNews($id, $id_content, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;
		   if(!empty($post['move_to'])) {
			 if(!empty($post['lang'])) {
				 $linkClass = new Link();
            foreach($post['lang'] as $id_lang=>$lang) {
				$post['lang'][$id_lang]['link']=$linkClass->generateLink($lang['title'], $id_lang, $lang['id_link'], $post['move_to']); 
			}			  
			}   
			   $id_content=$post['move_to'];
		}	 	
        if(!empty($post['publish_date'])) {$post['publish']=0;}		
		$this->db->transStart();
        $data = array(
            'id_page_cont' => $id_content,
            'template' => $post['template'],
            'date' => date('Y-m-d', strtotime($post['date'])),
            'home' => !empty($post['home']) ? $post['home'] : 0,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
            'comment' => !empty($post['comment']) ? $post['comment'] : 0,
            'publish_date' => !empty($post['publish_date']) ? date("Y-m-d H:i:s",strtotime($post['publish_date'])) : NULL,
            'dont_miss' => !empty($post['dont_miss']) ? $post['dont_miss'] : 0,
            'investments' => !empty($post['investments']) ? $post['investments'] : 0,
            'slider' => !empty($post['slider']) ? $post['slider'] : 0,
            'patronate' => !empty($post['patronate']) ? $post['patronate'] : 0,
            'show_in_box'=> !empty($post['show_in_box']) ? $post['show_in_box'] : NULL,
            'script'=> !empty($post['script']) ? $post['script'] : NULL,
        );
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->set('order', '`order`+1', FALSE)->Where('id_page_cont',$id_content)->Where('order >=',0)->update();
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        
        $this->saveNewsLang($this->id, $post['lang'], $id_content);
        $this->saveNewsMetaLang($this->id, $post['meta']['lang']);
        $this->saveNewsFile($this->id, !empty($post['photo']) ? $post['photo'] : array(), 'photo', true);
        $this->saveNewsFiles($this->id, !empty($post['photos']) ? $post['photos'] : array(), 'photos');
        $this->saveNewsFiles($this->id, !empty($post['audio']) ? $post['audio'] : array(), 'audio');
        $this->saveNewsFiles($this->id, !empty($post['video']) ? $post['video'] : array(), 'video');
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveNewsLang($id_news, $lang_data, $id_content) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'News')->get()->getRowArray();
            $linkClass = new Link();
            $this->tagsModel = new TagsModel();
            foreach($lang_data as $id_lang=>$lang) {
                if(!empty($lang['tags'])) {	
                    $lang['tags']= $this->tagsModel->AddTags($lang['tags'],$id_lang, $id_content);
                }
                else {$lang['tags']='';}
                $data = array(
                        'id_news' => $id_news,
                        'id_lang' => $id_lang,
                        'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0, false, null, null, !empty($lang['link_sync']) ? $lang['link_sync'] : 0, !empty($lang['link_redirect']) ? $lang['link_redirect'] : 0),
                        'title' => $lang['title'],
                        'subtitle' => $lang['subtitle'],
                        'header' => $lang['header'],
                        'introduction' => $lang['introduction'],
                        'content' => $lang['content'],
                        'author'=>$lang['author'],
                        'source'=>$lang['source'],
                        'tags'=>$lang['tags']
                );
                $lang = $this->db->table('news_lang')->select('id')->where('id_news', $id_news)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('news_lang')->set($data)->where('id_news', $id_news)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('news_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveNewsMetaLang($id_news, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_news' => $id_news,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                );
                $lang = $this->db->table('news_meta_lang')->select('id')->where('id_news', $id_news)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('news_meta_lang')->set($data)->where('id_news', $id_news)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('news_meta_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveNewsFiles($id_news, $files, $field='') 
    {
        $ids = array();
        if(!empty($files)) {
            foreach($files as $file) {
                $ids[] = $this->saveNewsFile($id_news, $file, $field);
            }
        }
        $query = $this->db->table('news_files')->select('id,path')->where('id_news', $id_news)->where('field', $field);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $files_list = $query->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeNewsFile($f);
            }
        }
    }
    
    private function saveNewsFile($id_news, $file, $field='', $remove=false) 
    {
        if(!empty($file)) {
            if(!empty($file['id']) && !empty($this->db->table('news_files')->select('id')->where('id', $file['id'])->get()->getRowArray())) {
                $data = array(
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
					'crop_dimension' => !empty($file['crop']) ? json_encode($file['crop']) : ''
                );
                $result = $this->db->table('news_files')->set($data)->where('id', $file['id'])->update();
                $id_file = $file['id'];
            } else {
                $file_obj = new \CodeIgniter\Files\File(WRITEPATH . 'uploads/' . $file['path']);
                if(!is_dir(WRITEPATH . 'uploads/news')) {
                    mkdir(WRITEPATH . 'uploads/news');
                }
                if(!is_dir(WRITEPATH . 'uploads/news/' . date('Ymd'))) {
                    mkdir(WRITEPATH . 'uploads/news/' . date('Ymd'));
                }
                $r = $file_obj->move(WRITEPATH . 'uploads/news/' . date('Ymd') , $file['basename']);
                $file_path = 'news/' . date('Ymd') . '/' . $r->getFilename();
                $file_info = pathinfo(WRITEPATH . 'uploads/' . $file_path);
                if(!empty($file) && !empty($file['crop'])) {
                    $file['crop']['path'] = $file_path;
                }
                $data = array(
                    'id_news' => $id_news,
                    'field' => $field,
                    'name' => $file['name'],
                    'basename' => $file['basename'],
                    'path' => $file_path,
                    'mime' => $r->getMimeType(),
                    'type' => file_type($r->getMimeType()),
                    'ext' => $file_info['extension'],
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
					'crop_dimension' => !empty($file['crop']) ? json_encode($file['crop']) : ''
                );
                $result = $this->db->table('news_files')->insert($data);
                $id_file = $this->db->insertID();
            }
            $this->saveNewsFileLang($id_file, $file['lang']);
        }
        if($remove) {
            $query = $this->db->table('news_files')->select('id,path')->where('id_news', $id_news)->where('field', $field);
            if(!empty($id_file)) {
                $query->where('id !=', $id_file);
            }
            $files_list = $query->get()->getResultArray();
            if(!empty($files_list)) {
                foreach($files_list as $f) {
                    $this->removeNewsFile($f);
                }
            }
        }
        return !empty($id_file) ? $id_file : 0;
    }
    
    private function saveNewsFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => $lang['caption'],
                    'author' => $lang['author'],
                );
                $lang = $this->db->table('news_files_lang')->select('id')->where('id_file', $id_file)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('news_files_lang')->set($data)->where('id_file', $id_file)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('news_files_lang')->insert($data);
                }
            }
        }
    }
    
    private function removeNewsFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('news_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('news_files')->where('id', $file['id'])->delete();
    }
    
    public function deleteNews($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('news_lang')->select('id_link')->where('id_news', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $files_list = $this->db->table('news_files')->select('id,path')->where('id_news', $id)->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeNewsFile($f);
            }
        }
        //$news = $this->select('order')->where('id', $id)->first();
        $this->db->table('news_lang')->where('id_news', $id)->delete();
        $this->db->table('news_meta_lang')->where('id_news', $id)->delete();
        $this->where('id', $id)->delete();
        //$this->set('order', '`order`-1', FALSE)->where('order >', $news['order'])->update();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getNewsList($id_lang) 
    {
        $news = $this->db->table('news n')
                ->join('news_lang nl', 'n.id = nl.id_news')
                ->select('n.id,n.publish,nl.title as name')
                ->where('nl.id_lang', $id_lang)
                ->where('n.publish', 1)
                ->orderBy('nl.title', 'ASC')
                ->get()
                ->getResultArray();
        return $news;
    }
    
    public function getNewsForMenu($id, $id_lang) 
    {
        $news = $this->db->table('news n')
                ->join('news_lang nl', 'n.id = nl.id_news')
                ->select('n.id as id_target,n.publish,nl.title as name')
                ->where('nl.id_lang', $id_lang)
                ->where('n.id', $id)
                ->get()
                ->getRowArray();
        if(!empty($news)) {
            $news['lang'] = array();
            $lang_data = $this->db->table('news_lang nl')->join('links l', 'l.id = nl.id_link')->select('nl.id_lang,nl.title as name,l.link as url')->where('nl.id_news', $id)->orderBy('nl.id_lang', 'ASC')->get()->getResultArray();
            if(!empty($lang_data)) {
                foreach($lang_data as $l) {
                    $l['title'] = $l['name'];
                    if(!empty($l['url'])) $l['url'] = (!empty($languages) && !empty($languages[$l['id_lang']]) && $languages[$l['id_lang']]['slug'] ? '/' . $languages[$l['id_lang']]['slug'] : '') . '/' . $l['url'];
                    $news['lang'][$l['id_lang']] = $l;
                }
            }
        }
        return $news;
    }

    public function getNewsTreeName($id_page, $id_lang) {
        $name = "";
        $page = $this->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->select('p.re_id,pl.name')->where('pl.id_lang', $id_lang)->where('p.id', $id_page)->get()->getRowArray();
        if(!empty($page)) {
            $name .= $page['name'] . '/';
            if(!empty($page['re_id'])) {
                $name = $this->getNewsTreeName($page['re_id'], $id_lang);
            }
        }
        return $name;
    }
	
	public function saveBigBox($id_box, $news) {
        $this->db->transStart();
        if (!empty($news)) {
            foreach ($news as $id) {
                $data = array(
                    'show_in_box' => $id_box,
                );
                $result = $this->db->table('news')->set($data)->where('id', $id)->update();
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
	
	public function getNewsBox($id_box,$id_lang) {
		$list=$this->db->table('news n')->Select('n.id,n.date,n.id_page_cont,n.publish,n.show_in_box,n.created_at,nl.title,nf.path')->join('news_lang nl', 'n.id=nl.id_news')->join('news_files nf', 'nf.id_news=n.id', 'left')->groupStart()->where('nf.publish', 1)->orWhere('nf.publish', null)->groupEnd()->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->where('n.show_in_box',$id_box)->where('nl.id_lang', $id_lang)->OrderBy('date','DESC')->get()->getResultArray();
        return $list;
	}	
	
	    public function removeBigBox($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $result = $this->db->table('news')->set(array('show_in_box'=>NULL))->where('id', $id)->update();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getMainPageId($id_page) {
        $page = $this->db->table('page')->select('id, re_id')->where('id', $id_page)->get()->getRowArray();
        if(!empty($page) && !empty($page['re_id'])) {
            $page = $this->getMainPageId($page['re_id']);
        }
        return $page;
    }
    
    public function getSubpageContentIds($id_page) {
        $ids = array($id_page);
        $list = $this->db->table('page p')->select('p.id,p.re_id')->where('p.re_id', $id_page)->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $ids = array_merge($ids, $this->getSubpageContentIds($l['id']));
            }
        }
        return $ids;
    }
}

