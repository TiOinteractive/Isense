<?php

namespace App\Libraries;


class Search
{
    
    public function __construct() 
    {
        helper(['form', 'url', 'file', 'filesystem']);
        $this->db = \Config\Database::connect();
        $this->linkClass = new Link();
        $this->request = \Config\Services::request();
    }
    
    public function initController() {
        
    }
    
    public function index($action) {
        //exit();
        ini_set('max_execution_time', '600');
        helper(['text', 'filesystem']);
        if (method_exists($this, $action)) {
            return $this->$action();
        }
    }
    
    
    private function news() {
        $get = $this->request->getGet();
        $filters = array();
        $news = array();
        $count = 0;
        if(!empty($get) && !empty($get['search'])) {
            $search = explode(PHP_EOL, $get['search']);
            if(!empty($search)) {
                $filters['search'] = $search;
                $query = $this->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->select('n.id,n.id_page_cont,n.date,nl.title,l.link')->where('nl.id_lang', 1);
                $query->groupStart();
                foreach($search as $s) {
                    $s = trim($s);
                    $query->orLike('nl.title', $s, 'both')->orLike('nl.content', $s, 'both');
                }
                $query->groupEnd();
                $news = $query->orderBy('n.date', 'DESC')->orderBy('n.id', 'DESC')->get()->getResultArray();
                //echo $this->db->getLastQuery()->getQuery();
                $count = count($news);
            }
        }
        return view('admin/search/news', array('filters' => $filters, 'news' => $news, 'count' => $count));
    }
    
}
    
    
    