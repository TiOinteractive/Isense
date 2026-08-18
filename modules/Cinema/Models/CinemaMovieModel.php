<?php

namespace Modules\Cinema\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class CinemaMovieModel extends Model{

    protected $table = 'cinema_movie';
    
    protected $allowedFields = [
        'id_page_cont',
        'production',
        'duration',
        'age',
        'recommended',
        'for_kids',
        'patronage',
        'dont_miss',
        'home',
        'video_url',
        'id_video',
        'publish',
        'template',
        'edited_at',
        'created_at',
    ];
    
    public function getCinemaMovieById($id, $id_lang) 
    {
        $movie = $this->where('id', $id)->first();
        if(!empty($movie)) {
            $movie['lang'] = $this->getCinemaMovieLang($id);
            if(!empty($movie['lang']) && !empty($movie['lang'][$id_lang]) && !empty($movie['lang'][$id_lang]['title'])) {
                $movie['name'] = $movie['lang'][$id_lang]['title'];
            } else {
                $movie['name'] = '';
            }
            $movie['types'] = $this->getCinemaMovieTypes($id);
            $movie['genres'] = $this->getCinemaMovieGenres($id);
            $movie['meta']['lang'] = $this->getCinemaMovieMetaLang($id);
            $movie['poster'] = $this->getCinemaMovieFile($id, 'movie_poster');
            $movie['photo'] = $this->getCinemaMovieFile($id, 'movie_photo');
            $movie['photos'] = $this->getCinemaMovieFiles($id, 'movie_photos');
        }
        return $movie;
    }
    
    private function getCinemaMovieLang($id_movie) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('cinema_movie_lang')->where('id_movie', $id_movie)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getCinemaMovieTypes($id_movie)
    {
        $types = array();
        $list = $this->db->table('cinema_movie_types')->select('id_type')->where('id_movie', $id_movie)->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $types[] = $l['id_type'];
            }
        }
        return $types;
    }
    
    public function getCinemaMovieGenres($id_movie)
    {
        $genres = array();
        $list = $this->db->table('cinema_movie_genres')->select('id_genre')->where('id_movie', $id_movie)->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $genres[] = $l['id_genre'];
            }
        }
        return $genres;
    }
    
    public function getCinemaMovieMetaLang($id_movie) 
    {
        $langs = array();
        $data = $this->db->table('cinema_meta_lang')->where('id_cinema', $id_movie)->where('slug', 'movie')->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    private function getCinemaMovieFile($id_movie, $field='') 
    {
        $file = $this->db->table('cinema_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_cinema', $id_movie)->where('field', $field)->orderBy('order', 'ASC')->get()->getRowArray();
        if(!empty($file)) {
            $file['lang'] = $this->getCinemaMovieFileLang($file['id']);
        }
        return $file;
    }
    
    private function getCinemaMovieFiles($id_movie, $field='') 
    {
        $files = $this->db->table('cinema_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_cinema', $id_movie)->where('field', $field)->orderBy('order', 'ASC')->get()->getResultArray();
        if(!empty($files)) {
            foreach($files as $k=>$file) {
                $files[$k]['lang'] = $this->getCinemaMovieFileLang($file['id']);
            }
        }
        return $files;
    }
    
    private function getCinemaMovieFileLang($id_file) 
    {
        $langs = array();
        $data = $this->db->table('cinema_files_lang')->where('id_file', $id_file)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveCinemaMassMovies($id_content, $movies, $template) {
        helper(['file']);
        if(empty($movies)) return false;
        $this->db->transStart();
        foreach($movies as $movie) {
            $data = array(
                'id_page_cont' => $id_content,
                'production' => !empty($movie['production']) ? $movie['production'] : null,
                'duration' => !empty($movie['duration']) ? $movie['duration'] : 0,
                'age' => !empty($movie['age']) ? $movie['age'] : 0,
                'for_kids' => !empty($movie['for_kids']) ? $movie['for_kids'] : 0,
                'template' => $template,
                'video_url' => !empty($movie['video_url']) ? $movie['video_url'] : null,
                'publish' => 1,
            );
            $result = $this->insert($data);
            if($result) {
                $id = $this->getInsertID();
                $this->saveCinemaMovieLang($id, $movie['lang'], $id_content);
                $this->saveCinemaMovieGenres($id, !empty($movie['genres']) ? $movie['genres'] : array());
                $this->saveCinemaMovieFile($id, $movie, 'movie_poster', true);
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function saveCinemaMovie($id, $id_content, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;
        $data = array(
            'id_page_cont' => $id_content,
            'production' => !empty($post['production']) ? $post['production'] : null,
            'duration' => !empty($post['duration']) ? $post['duration'] : 0,
            'age' => !empty($post['age']) ? $post['age'] : 0,
            'template' => $post['template'],
            'home' => !empty($post['home']) ? $post['home'] : 0,
            'patronage' => !empty($post['patronage']) ? $post['patronage'] : 0,
            'for_kids' => !empty($post['for_kids']) ? $post['for_kids'] : 0,
            'recommended' => !empty($post['recommended']) ? $post['recommended'] : 0,
            'dont_miss' => !empty($post['dont_miss']) ? $post['dont_miss'] : 0,
            'video_url' => !empty($post['video_url']) ? $post['video_url'] : null,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
        );
        $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        
        $this->saveCinemaMovieLang($this->id, $post['lang'], $id_content);
        $this->saveCinemaMovieTypes($this->id, !empty($post['types']) ? $post['types'] : array());
        $this->saveCinemaMovieGenres($this->id, !empty($post['genres']) ? $post['genres'] : array());
        $this->saveCinemaMovieMetaLang($this->id, $post['meta']['lang']);
        $this->saveCinemaMovieFile($this->id, !empty($post['poster']) ? $post['poster'] : array(), 'movie_poster', true);
        $this->saveCinemaMovieFile($this->id, !empty($post['photo']) ? $post['photo'] : array(), 'movie_photo', true);
        $this->saveCinemaMovieFiles($this->id, !empty($post['photos']) ? $post['photos'] : array(), 'movie_photos');
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveCinemaMovieLang($id_movie, $lang_data, $id_content) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Cinema')->get()->getRowArray();
            $linkClass = new Link();
            $page = $this->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
            foreach($lang_data as $id_lang=>$lang) {
                if(empty($lang['link'])) {
                    $lang['link'] = $linkClass->generateLink($lang['title'], $id_lang, 0, $page['id_page']);
                }
                $data = array(
                    'id_movie' => $id_movie,
                    'id_lang' => $id_lang,
                    'id_link' => $linkClass->saveLink($lang['link'], $id_lang, !empty($lang['id_link']) ? $lang['id_link'] : 0, !empty($module) ? $module['id'] : 0),
                    'title' => !empty($lang['title']) ? $lang['title'] : '',
                    'original' => !empty($lang['original']) ? $lang['original'] : '',
                    'introduction' => !empty($lang['introduction']) ? $lang['introduction'] : '',
                    'content' => !empty($lang['content']) ? $lang['content'] : '',
                    'country' => !empty($lang['country']) ? $lang['country'] : '',
                    'director' => !empty($lang['director']) ? $lang['director'] : '',
                    'actors' => !empty($lang['actors']) ? $lang['actors'] : '',
                    'scenario' => !empty($lang['scenario']) ? $lang['scenario'] : '',
                    'distributor' => !empty($lang['distributor']) ? $lang['distributor'] : '',
                );
                $lang = $this->db->table('cinema_movie_lang')->select('id')->where('id_movie', $id_movie)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('cinema_movie_lang')->set($data)->where('id_movie', $id_movie)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('cinema_movie_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveCinemaMovieTypes($id_movie, $types) 
    {
        $ids = array();
        if(!empty($types)) {
            foreach($types as $id_type) {
                $data = array(
                    'id_type' => $id_type,
                    'id_movie' => $id_movie,
                );
                $is = $this->db->table('cinema_movie_types')->select('id')->where('id_movie', $id_movie)->where('id_type', $id_type)->get()->getRowArray();
                if(!empty($is) && !empty($is['id'])) {
                    $ids[] = $is['id'];
                } else {
                    $result = $this->db->table('cinema_movie_types')->insert($data);
                    $ids[] = $this->db->insertID();
                }
            }
        }
        $query = $this->db->table('cinema_movie_types')->where('id_movie', $id_movie);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $query->delete();
    }
    
    private function saveCinemaMovieGenres($id_movie, $genres) 
    {
        $ids = array();
        if(!empty($genres)) {
            foreach($genres as $id_genre) {
                $data = array(
                    'id_genre' => $id_genre,
                    'id_movie' => $id_movie,
                );
                $is = $this->db->table('cinema_movie_genres')->select('id')->where('id_movie', $id_movie)->where('id_genre', $id_genre)->get()->getRowArray();
                if(!empty($is) && !empty($is['id'])) {
                    $ids[] = $is['id'];
                } else {
                    $result = $this->db->table('cinema_movie_genres')->insert($data);
                    $ids[] = $this->db->insertID();
                }
            }
        }
        $query = $this->db->table('cinema_movie_genres')->where('id_movie', $id_movie);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $query->delete();
    }
    
    private function saveCinemaMovieMetaLang($id_movie, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_cinema' => $id_movie,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                    'keywords' => $lang['keywords'],
                    'slug' => 'movie'
                );
                $lang = $this->db->table('cinema_meta_lang')->select('id')->where('id_cinema', $id_movie)->where('slug', '')->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('cinema_meta_lang')->set($data)->where('id_cinema', $id_movie)->where('slug', '')->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('cinema_meta_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveCinemaMovieFiles($id_movie, $files, $field='') 
    {
        $ids = array();
        if(!empty($files)) {
            foreach($files as $file) {
                $ids[] = $this->saveCinemaMovieFile($id_movie, $file, $field);
            }
        }
        $query = $this->db->table('cinema_files')->select('id,path')->where('id_cinema', $id_movie)->where('field', $field);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $files_list = $query->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeCinemaMovieFile($f);
            }
        }
    }
    
    private function saveCinemaMovieFile($id_movie, $file, $field='', $remove=false) 
    {
        if(!empty($file)) {
            if(!empty($file['id']) && !empty($this->db->table('cinema_files')->select('id')->where('id', $file['id'])->get()->getRowArray())) {
                $data = array(
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('cinema_files')->set($data)->where('id', $file['id'])->update();
                $id_file = $file['id'];
            } else {
                $file_obj = new \CodeIgniter\Files\File(WRITEPATH . 'uploads/' . $file['path']);
                if(!is_dir(WRITEPATH . 'uploads/cinema')) {
                    mkdir(WRITEPATH . 'uploads/cinema');
                }
                if(!is_dir(WRITEPATH . 'uploads/cinema/' . date('Ymd'))) {
                    mkdir(WRITEPATH . 'uploads/cinema/' . date('Ymd'));
                }
                $r = $file_obj->move(WRITEPATH . 'uploads/cinema/' . date('Ymd') , $file['basename']);
                $file_path = 'cinema/' . date('Ymd') . '/' . $r->getFilename();
                $file_info = pathinfo(WRITEPATH . 'uploads/' . $file_path);
                $data = array(
                    'id_cinema' => $id_movie,
                    'field' => $field,
                    'name' => $file['name'],
                    'basename' => $file['basename'],
                    'path' => $file_path,
                    'mime' => $r->getMimeType(),
                    'type' => file_type($r->getMimeType()),
                    'ext' => $file_info['extension'],
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('cinema_files')->insert($data);
                $id_file = $this->db->insertID();
            }
            $this->saveCinemaMovieFileLang($id_file, $file['lang']);
        }
        if($remove) {
            $query = $this->db->table('cinema_files')->select('id,path')->where('id_cinema', $id_movie)->where('field', $field);
            if(!empty($id_file)) {
                $query->where('id !=', $id_file);
            }
            $files_list = $query->get()->getResultArray();
            if(!empty($files_list)) {
                foreach($files_list as $f) {
                    $this->removeCinemaMovieFile($f);
                }
            }
        }
        return !empty($id_file) ? $id_file : 0;
    }
    
    private function saveCinemaMovieFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => !empty($lang['caption']) ? $lang['caption'] : '',
                    'author' => !empty($lang['caption']) ? $lang['author'] : '',
                );
                $lang = $this->db->table('cinema_files_lang')->select('id')->where('id_file', $id_file)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('cinema_files_lang')->set($data)->where('id_file', $id_file)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('cinema_files_lang')->insert($data);
                }
            }
        }
    }
    
    private function removeCinemaMovieFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('cinema_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('cinema_files')->where('id', $file['id'])->delete();
    }
    
    public function getMoviesForList($id_lang, $search_text='', $ids=array(), $order='') 
    {
        $movies = array();
        if(!empty($ids)) {
            $list = $this->db->table('cinema_movie cm')
                ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                ->select('cm.id,cml.title,cml.original')
                ->where('cm.publish', 1)
                ->where('cml.id_lang', $id_lang)
                ->whereIn('cm.id', $ids)
                ->get()->getResultArray();
            if(!empty($list)) {
                foreach($list as $l) {
                    $movies[$l['id']] = $l;
                }
            }
        }
        
        $tmp_search = explode(" ", trim($search_text));
        $search = array();
        if (!empty($tmp_search)) {
            foreach ($tmp_search as $s) {
                if (strlen($s) > 0) {
                    $search[] = $s;
                }
            }
        }
        $query = $this->db->table('cinema_movie cm')
                ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                ->select('cm.id,cml.title,cml.original')
                ->where('cm.publish', 1)
                ->where('cml.id_lang', $id_lang);
        if (!empty($search)) {
            $query->groupStart();
            $query->where("MATCH (cml.title,cml.original) AGAINST ('" . implode(' +', $search) . "' IN BOOLEAN MODE) >=", 0.2);
            $query->orLike('cml.title', $search_text);
            $query->orLike('cml.original', $search_text);
            $query->groupEnd();
            $query->orderBy("MATCH (cml.title,cml.original) AGAINST ('" . implode(' +', $search) . "' IN BOOLEAN MODE) DESC");
        } else {
            $query->orderBy('cm.created_at DESC');
        }
        if(!empty($ids)) {
            $query->whereNotIn('cm.id', $ids);
        }
        switch($order) {
            case 'latest': $query->orderBy('cm.created_at DESC');
                break;
            default: $query->orderBy('cml.title ASC');
                break;
        }
        $list = $query/*->limit(200)*/
                ->get()->getResultArray();
        
        if(!empty($list)) {
            foreach($list as $l) {
                $movies[$l['id']] = $l;
            }
        }
        return $movies;
    }
    
    public function deleteCinemaMovie($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('cinema_movie_lang')->select('id_link')->where('id_movie', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $files_list = $this->db->table('cinema_files')->select('id,path')->where('id_cinema', $id)->whereIn('field', array('movie_poster', 'movie_photo', 'movie_photos'))->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeCinemaMovieFile($f);
            }
        }
        $this->db->table('cinema_movie_lang')->where('id_movie', $id)->delete();
        $this->db->table('cinema_meta_lang')->where('id_cinema', $id)->where('slug', 'movie')->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
}