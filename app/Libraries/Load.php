<?php

namespace App\Libraries;

use App\Libraries\Page;
use App\Libraries\Link;


class Load
{
    
    public function __construct() 
    {
        helper(['form', 'url', 'file', 'filesystem']);
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->response = \Config\Services::response();
        $this->pageClass = new Page();
        $this->linkClass = new Link();
        $this->id_lang = 1;
        $this->request = \Config\Services::request();
        $agent = $this->request->getUserAgent();
        $this->is_mobile = $agent->isMobile();
    }
    
    public function initController() {
        
    }
    
    public function index($action) {
        helper(['text', 'filesystem']);
        if (method_exists($this, $action)) {
            return $this->$action();
        }
    }
    
    private function header() {
        $settings = $this->pageClass->getSettings($this->id_lang);
        $global_links = $this->linkClass->getGlobalLinks($this->id_lang, '');
        $global_links = array_merge($global_links, $this->pageClass->getSpecialWebsites($this->id_lang, ''));
        $global_links = array_merge($global_links, $this->pageClass->getDirectWebsites($this->id_lang, ''));
        $html = '<link rel="stylesheet" href="' . base_url() . 'assets/css/header.css">';
        //$html .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/style.css">';
        //$html .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/foto.css">';
        $html .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/fonts.css">';
        $html .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/jquery-confirm.min.css">';
        //$html .= '<script src="' . base_url() . 'assets/js/jquery.min.js"></script>';
        $html .= '<script src="' . base_url() . 'assets/js/javascript.js"></script>';
        $html .= '<script src="' . base_url() . 'assets/js/header.js"></script>';
        $html .= '<script src="' . base_url() . 'assets/js/jquery-confirm.min.js"></script>';
        $html .= view($this->is_mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header', array('id_lang' => $this->id_lang, 'page' => $this->pageClass->page, 'locale' => '', 'global_links' => $global_links, 'settings' => $settings));
        echo str_replace(array('href="/', 'action="/'), array('href="' . base_url(), 'action="' . base_url()), $html);
    }
	
	
	private function header_mobile() {
        $settings = $this->pageClass->getSettings($this->id_lang);
        $global_links = $this->linkClass->getGlobalLinks($this->id_lang, '');
        $global_links = array_merge($global_links, $this->pageClass->getSpecialWebsites($this->id_lang, ''));
        $global_links = array_merge($global_links, $this->pageClass->getDirectWebsites($this->id_lang, ''));
        $html = '<meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="' . base_url() . 'assets/css/header.css">';
        //$html .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/style.css">';
        //$html .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/foto.css">';
		$html .= '<link rel="stylesheet" href="font-awasome/css/all.min.css">';
        $html .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/fonts.css">';
        $html .= '<link rel="stylesheet" href="' . base_url() . 'assets/css/jquery-confirm.min.css">';
        //$html .= '<script src="' . base_url() . 'assets/js/jquery.min.js"></script>';
        $html .= '<script src="' . base_url() . 'assets/js/javascript.js"></script>';
        $html .= '<script src="' . base_url() . 'assets/js/header.js"></script>';
        $html .= '<script src="' . base_url() . 'assets/js/jquery-confirm.min.js"></script><div class="mobile">';
        $html .= view($this->is_mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/m_header', array('id_lang' => $this->id_lang, 'page' => $this->pageClass->page, 'locale' => '', 'global_links' => $global_links, 'settings' => $settings));
		$html.='</div>';
        echo str_replace(array('href="/', 'action="/'), array('href="' . base_url(), 'action="' . base_url()), $html);
    }
	
	
	
    
    private function footer() {
        
    }
    
    private function emailbuilder() {
        $data = array(
            'news' => array(),
            'entertainment' => array(),
            'calendar' => array(),
            'cinema' => array(),
        );
        
        $news = $this->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->select('n.id,nl.title,l.link')->where('nl.id_lang', $this->id_lang)->where('l.id_lang', $this->id_lang)->where('n.publish', 1)->where('n.newsletter', 1)->whereIn('n.id_page_cont', array(10,11,12,13,38))->orderBy('FIELD(n.id_page_cont,38)', 'ASC', false)->orderBy('n.date', 'DESC')->limit(50)->get()->getResultArray();
        if(!empty($news)) {
            foreach($news as $n) {
                $n['photo'] = $this->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->select('nf.path,nf.crop_dimension,nfl.caption,nfl.author')->where('nf.id_news', $n['id'])->where('nf.publish', 1)->where('nf.field', 'photo')->where('nfl.id_lang', $this->id_lang)->get()->getRowArray();
                $data['news'][] = array(
                    'name' => $n['title'],
                    'link' => base_url() . $n['link'],
                    'img' => base_url() . 'image/c/460/300/' . (!empty($n['photo']) ? $n['photo']['path'] : ''),
                    'original_img' => base_url() . 'image/' . (!empty($n['photo']) ? $n['photo']['path'] : ''),
                );
            }
        }
        
        $entertainment_news = $this->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->select('n.id,nl.title,l.link')->where('nl.id_lang', $this->id_lang)->where('l.id_lang', $this->id_lang)->where('n.publish', 1)->where('n.newsletter', 1)->whereIn('n.id_page_cont', array(3))->orderBy('n.date', 'DESC')->limit(50)->get()->getResultArray();
        if(!empty($entertainment_news)) {
            foreach($entertainment_news as $n) {
                $n['photo'] = $this->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->select('nf.path,nf.crop_dimension,nfl.caption,nfl.author')->where('nf.id_news', $n['id'])->where('nf.publish', 1)->where('nf.field', 'photo')->where('nfl.id_lang', $this->id_lang)->get()->getRowArray();
                $data['entertainment'][] = array(
                    'name' => $n['title'],
                    'link' => base_url() . $n['link'],
                    'img' => base_url() . 'image/c/460/300/' . (!empty($n['photo']) ? $n['photo']['path'] : ''),
                    'original_img' => base_url() . 'image/' . (!empty($n['photo']) ? $n['photo']['path'] : ''),
                );
            }
        }
        
        $cinema = $this->db->table('cinema_movie cm')->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')->join('links l', 'l.id=cml.id_link')->join('cinema_calendar cc', 'cm.id=cc.id_movie')->select('cm.id,cml.title,l.link')->where('cml.id_lang', $this->id_lang)->where('l.id_lang', $this->id_lang)->where('cm.publish', 1)->where('cc.date >=', date('Y-m-d'))->orderBy('cc.date', 'ASC')->groupBy('cm.id')->limit(8)->get()->getResultArray();
        if(!empty($cinema)) {
            foreach($cinema as $c) {
                $c['photo'] = $this->db->table('cinema_files cf')->join('cinema_files_lang cfl', 'cf.id=cfl.id_file')->select('cf.path,cfl.caption,cfl.author')->where('cf.id_cinema', $c['id'])->where('cf.publish', 1)->where('cf.field', 'movie_poster')->where('cfl.id_lang', $this->id_lang)->get()->getRowArray();
                $data['cinema'][] = array(
                    'name' => $c['title'],
                    'link' => base_url() . $c['link'],
                    'img' => base_url() . 'image/r/400/578/' . (!empty($c['photo']) ? $c['photo']['path'] : ''),
                    'original_img' => base_url() . 'image/' . (!empty($c['photo']) ? $c['photo']['path'] : ''),
                );
            }
        }
        
        $date_start = date('Y-m-d');
        $events = $this->db->table('event_calendar')->join('event e', 'e.id=event_calendar.id_event')
                ->join('event_lang el', 'e.id=el.id_event')
                ->join('event_place ep', 'ep.id=event_calendar.id_place', 'left')
                ->join('event_place_lang epl', 'ep.id=epl.id_place', 'left')
                ->join('links l', 'l.id=el.id_link')
                ->join('links l2', 'l2.id=epl.id_link', 'left')
                ->join('event_type_lang etl', 'etl.id_type=e.id_type', 'left')
                ->select('IF(date_end IS NULL, 1, 0) as period,event_calendar.id,event_calendar.id_event,event_calendar.id_place,e.for_kids,e.patronage,el.price,el.name,l.link,event_calendar.custom_place,event_calendar.date_start,event_calendar.date_end,event_calendar.hours,epl.name as place,l2.link as place_link,etl.name as type_name,etl.slug as type_slug')
                ->where('el.id_lang', $this->id_lang)
                ->where('e.publish', 1)
                ->groupStart()
                    ->where('epl.id_lang', $this->id_lang)
                    ->orWhere('ep.id', NULL)
                ->groupEnd()
                ->groupStart()
                    ->where('ep.publish', 1)
                    ->orWhere('event_calendar.custom_place !=', '')
                ->groupEnd()
                ->groupStart()
                    ->where('etl.id_lang', $this->id_lang)
                    ->orWhere('etl.id', null)
                ->groupEnd()
                ->groupStart()
                    ->groupStart()
                        ->where('event_calendar.date_start >=', $date_start)
                        ->where('event_calendar.date_end', null)
                    ->groupEnd()
                    ->orWhere('event_calendar.date_end >=', $date_start)
                ->groupEnd()
                ->orderBy('period', 'DESC')
                ->orderBy('event_calendar.date_start', 'ASC')
                ->orderBy('event_calendar.hours', 'ASC')
                ->limit(16)
                ->get()->getResultArray();
        if(!empty($events)) {
            foreach($events as $e) {
                $e['photo'] = $this->db->table('event_files ef')->join('event_files_lang efl', 'ef.id=efl.id_file')->select('ef.path,efl.caption,efl.author')->where('ef.id_event', $e['id_event'])->where('ef.publish', 1)->where('ef.field', 'photo')->where('efl.id_lang', $this->id_lang)->get()->getRowArray();
                $data['calendar'][] = array(
                    'name' => $e['name'],
                    'link' => base_url() . $e['link'],
                    'img' => base_url() . 'image/c/600/400/' . (!empty($e['photo']) ? $e['photo']['path'] : ''),
                    'original_img' => base_url() . 'image/' . (!empty($e['photo']) ? $e['photo']['path'] : ''),
                    "club" => !empty($e['place']) ? $e['place'] : $e['custom_place'],
                    "club_link" => !empty($e['place_link']) ? base_url() . $e['place_link'] : '',
                    "date_from" => $e['date_start'],
                    "date_to" => $e['date_end'],
                    "type" => $e['type_name'],
                );
            }
        }
        
        return $this->response->setJSON($data);
    }
}