<?php

namespace Modules\Cinema\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class CinemaGenreModel extends Model{

    protected $table = 'cinema_genre';
    
    protected $allowedFields = [
        'publish',
        'edited_at',
        'created_at',
    ];
    
    public function getGenreById($id, $id_lang) 
    {
        $genre = $this->where('id', $id)->first();
        if(!empty($genre)) {
            $genre['lang'] = $this->getGenreLang($id);
            if(!empty($genre['lang']) && !empty($genre['lang'][$id_lang]) && !empty($genre['lang'][$id_lang]['name'])) {
                $genre['name'] = $genre['lang'][$id_lang]['name'];
            } else {
                $genre['name'] = '';
            }
        }
        return $genre;
    }
    
    private function getGenreLang($id_genre) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('cinema_genre_lang')->where('id_genre', $id_genre)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveGenre($id, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;
        $data = array(
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
        
        $this->saveGenreLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveGenreLang($id_genre, $lang_data) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Cinema')->get()->getRowArray();
            $linkClass = new Link();
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_genre' => $id_genre,
                    'id_lang' => $id_lang,
                    'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0),
                    'name' => $lang['name'],
                    'content' => $lang['content'],
                );
                $lang = $this->db->table('cinema_genre_lang')->select('id')->where('id_genre', $id_genre)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('cinema_genre_lang')->set($data)->where('id_genre', $id_genre)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('cinema_genre_lang')->insert($data);
                }
            }
        }
    }
    
    public function getGenresForList($id_lang)
    {
        $genres = array();
        $list = $this->db->table('cinema_genre cg')->join('cinema_genre_lang cgl', 'cg.id=cgl.id_genre')->select('cg.id,cgl.name')->where('cg.publish', 1)->where('cgl.id_lang', $id_lang)->orderBy('cgl.name ASC')->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $genres[$l['id']] = $l;
            }
        }
        return $genres;
    }
    
    public function deleteGenre($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('cinema_genre_lang')->select('id_link')->where('id_genre', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $this->db->table('cinema_genre_lang')->where('id_genre', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    
    
}