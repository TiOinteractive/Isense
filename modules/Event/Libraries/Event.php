<?php

namespace Modules\Event\Libraries;

use Modules\Event\Models\EventModel;
use Modules\Event\Models\EventCalendarModel;
use Modules\Event\Models\EventTypeModel;
use Modules\Cinema\Models\CinemaCalendarModel;

class Event {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->eventModel = new EventModel();
        $this->eventCalendarModel = new EventCalendarModel();
        $this->eventTypeModel = new EventTypeModel();
        $this->cinemaCalendarModel = new CinemaCalendarModel();
    }

    public function index($content, $id_lang, $slug = '', $link=array()) {
        $get = $this->request->getGet();
        $config = new \Config\Pager;
        switch ($slug) {
            case 'event_cinema':
                $date = date('Y-m-d');
                $query = $this->eventCalendarModel->db->table('event_calendar ec')->join('event e', 'e.id=ec.id_event')
                    ->join('event_lang el', 'e.id=el.id_event')
                    ->join('event_files ef', 'ef.id_event=e.id AND ef.field="photo"')
                    ->join('links l', 'l.id=el.id_link')
                    ->join('event_type_lang etl', 'etl.id_type=e.id_type', 'left')
                    ->join('links l2', 'l2.id=etl.id_link', 'left')
                    ->select('ec.id,ec.id_event,ec.id_place,e.for_kids,e.patronage,e.source,el.name,l.link,ec.date_start,ec.date_end,ec.hours,ec.custom_place,ef.path as photo,etl.name as type_name,etl.slug as type_slug,crop_dimension')
                    ->where('el.id_lang', $id_lang)
                    ->groupBy('e.id')
                    ->groupStart()
                        ->groupStart()
                            ->where('ec.date_start >=', $date)
                            ->where('ec.date_end', NULL)
                        ->groupEnd()
                        ->orGroupStart()
                            ->where('ec.date_start >=', $date)
                            ->orWhere('ec.date_end >=', $date)
                        ->groupEnd()
                    ->groupend()
                    ->groupStart()
                        ->where('etl.id_lang', $id_lang)
                        ->orWhere('etl.id', null)
                    ->groupEnd()
                    ->where('e.publish', 1);
                
                if(empty($content['config'])) {
                    $content['config'] = array();
                }
                if(!empty($content['config']['types'])) {
                    $query->whereIn('e.id_type', $content['config']['types']);
                }
                if(!empty($content['config']['home'])) {
                    $query->where('e.home', 1);
                }
                if(empty($content['config']['order'])) {
                    $content['config']['order'] = '';
                }
                
                switch($content['config']['order']) {
                    case 'latest': $query->orderBy('ec.date_start', 'DESC')->orderBy('ec.hours', 'DESC');
                        break;
                    case 'random': $query->orderBy('ec.id', 'RANDOM');
                        break;
                    case 'date':
                    default: $query->orderBy('ec.date_start', 'ASC')->orderBy('ec.hours', 'ASC');
                        break;
                }
                if(!empty($content['config']['no'])) {
                    $query->limit($content['config']['no']);
                }
                $events = $query->get()->getResultArray();
                if(!empty($events)) {
                    foreach($events as $k=>$e) {
                        if(!empty($this->locale)) {
                            $events[$k]['link'] = ($this->locale ? $this->locale . '/' : '') . $e['link'];
                        }
                        if(!empty($e['id_place'])) {
                            $events[$k]['place'] = $this->eventModel->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->select('ep.id,epl.name')->where('epl.id_lang', $id_lang)->where('ep.id', $e['id_place'])->get()->getRowArray();
                        }
                    }
                }
                
                
                $query = $this->eventModel->db->table('cinema_calendar cc')
                                ->join('cinema_movie cm', 'cm.id=cc.id_movie')
                                ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                                ->join('cinema_files cf', 'cf.id_cinema=cm.id AND cf.field="movie_poster"')
                                ->join('links l', 'l.id=cml.id_link')
                                ->join('cinema_announcement ca', 'ca.id_movie=cm.id', 'left')
                                ->select('cm.id,cml.title,cf.path as poster,cc.id_place,cc.date,l.link,ca.date as announcement_date')
                                ->where('cml.id_lang', $id_lang)
                                ->like('cc.date', date('Y-m-d'), 'after')
                                ->where('cm.publish', 1)
                                ->groupBy('cm.id');
                
                if(empty($content['config']['order2'])) {
                    $content['config']['order2'] = '';
                }
                
                switch($content['config']['order2']) {
                    case 'latest': $query->orderBy('ca.date', 'DESC')->orderBy('cc.date', 'DESC');
                        break;
                    case 'random': $query->orderBy('ca.id', 'RANDOM');
                        break;
                    case 'date':
                    default: $query->orderBy('ca.date', 'ASC')->orderBy('cc.date', 'ASC');
                        break;
                }
                if(!empty($content['config']['no2'])) {
                    $query->limit($content['config']['no2']);
                }
                $movies = $query->get()->getResultArray();
                if(!empty($movies)) {
                    foreach($movies as $k=>$m) {
                        $movies[$k]['link'] = ($this->locale ? $this->locale . '/' : '') . $m['link'];
                        $movies[$k]['genres'] = $this->eventModel->db->table('cinema_movie_genres cmg')->join('cinema_genre cg', 'cg.id=cmg.id_genre')->join('cinema_genre_lang cgl', 'cgl.id_genre=cg.id')->select('cg.id,cgl.name')->where('cg.publish', 1)->where('cmg.id_movie', $m['id'])->get()->getResultArray();
                        //if(!empty($m['id_place'])) {
                        //    $movies[$k]['place'] = $this->eventModel->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->select('ep.id,epl.name')->where('epl.id_lang', $id_lang)->where('ep.id', $m['id_place'])->get()->getRowArray();
                        //}
                    }
                }
                return array(
                    'title2' => !empty($content['config']['lang']) && !empty($content['config']['lang'][$id_lang]) && !empty($content['config']['lang'][$id_lang]['title2']) ? $content['config']['lang'][$id_lang]['title2'] : '',
                    'subtitle2' => !empty($content['config']['lang']) && !empty($content['config']['lang'][$id_lang]) && !empty($content['config']['lang'][$id_lang]['subtitle2']) ? $content['config']['lang'][$id_lang]['subtitle2'] : '',
                    'url2' => !empty($content['config']['lang']) && !empty($content['config']['lang'][$id_lang]) && !empty($content['config']['lang'][$id_lang]['url2']) ? $content['config']['lang'][$id_lang]['url2'] : '',
                    'events' => $events,
                    'movies' => $movies,
                );
                
                break;
            case 'places_selected':
                $query = $this->eventModel->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->join('links l', 'l.id=epl.id_link')->select('ep.id,epl.name,l.link')->where('ep.publish', 1)->where('epl.id_lang', $id_lang);
                if(!empty($content['config'])) {
                    if(!empty($content['config']['types'])) {
                        $query->whereIn('ep.id_type', $content['config']['types']);
                    }
                }
                $places = $query->orderBy('epl.name', 'ASC')->get()->getResultArray();
                if(!empty($places) && !empty($this->locale)) {
                    foreach($places as $k=>$p) {
                        $places[$k]['link'] = ($this->locale ? $this->locale . '/' : '') . $p['link'];
                    }
                }
                return array(
                    'places' => $places,
                    'views_dir' => 'places'
                );
                break;
            case 'places':
			
				$filters = array();
                if(!empty($link['get'])) {
                    for($i=0;$i<count($link['get']);$i+=2) {
                        $filters[$link['get'][$i]] = urldecode($link['get'][$i + 1]);
                    }
                }
			
                $structure = $this->eventModel->getMainPlaceTypesWithPlacesList($id_lang,false,$filters);
				$cat_list=$this->eventModel->getPlaceTypesForList($id_lang);
                if(!empty($structure)) {
                    foreach($structure as $c=>$type) {
                        if (!empty($locale)) {
                            $structure[$c]['link'] = ($locale ? $locale . '/' : '') . $type['link'];
                        }
                        if(!empty($structure[$c]['places']) && !empty($locale)) {
                            foreach($structure[$c]['places'] as $p=>$place) {
                                $structure[$c]['places'][$p]['link'] = ($locale ? $locale . '/' : '') . $place['link'];
                            }
                        }
						if(!empty($type['places'])) {
						   foreach($type['places'] as $k=>$place) {	
							
							$type['places'][$k]['photo']=$this->eventModel->db->table('event_files ef')->join('event_files_lang fl', 'ef.id=fl.id_file')->select('ef.path,fl.caption,fl.author')->where('fl.id_lang', $id_lang)->where('ef.field', 'place_photos')->where('ef.id_event', $place['id'])->OrderBy('order','asc')->get()->getRowArray();
							
						   }	
						   $structure[$c]=$type;
						}	
                    }
                }
                return array(
                    'structure' => $structure,
					'cat_list'=>$cat_list,
					'filters'=>$filters
                );
                break;
            case 'calendar':
                $events_count = $this->eventCalendarModel->countCalendarEvents(date('Y-m'), !empty($content['config']['lists']) ? $content['config']['lists'] : null);
                return array(
                    'list' => $events_count,
                );
                break;
            case 'boxes':
                $query_union = $this->eventCalendarModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->join('news_files nf', 'nf.id_news=n.id AND nf.field="photo"')->select('n.id,nl.title as name,l.link,n.patronate as patronage,0 as recommended,n.home,n.created_at as date,\'news\' as type,nf.path as photo,nf.crop_dimension')->where('nl.id_lang', $id_lang)->where('n.publish', 1);
                $builder_union = $this->eventCalendarModel->db->table('event_calendar ec')->join('event e', 'e.id=ec.id_event')->join('event_lang el', 'el.id_event=e.id')->join('links l', 'l.id=el.id_link')->join('event_files ef', 'ef.id_event=e.id AND ef.field="photo"')->select('e.id,el.name,l.link,e.patronage,e.recommended,e.home,e.created_at as date,\'event\' as type,ef.path as photo,ef.crop_dimension')->where('el.id_lang', $id_lang)->where('e.publish', 1);
                $query = $this->eventCalendarModel->db->newQuery();
                if(!empty($content['config'])) {
                    if(!empty($content['config']['lists'])) {
                        $query_union->whereIn('n.id_page_cont', $content['config']['lists']);
                    }
                    if(!empty($content['config']['options'])) {
                        $query_union->groupStart();
                        $builder_union->groupStart();
                        foreach($content['config']['options'] as $k=>$option) {
                            switch($option) {
                                case 'home':
                                    if($k) {
                                        $query_union->orWhere('n.home', 1);
                                        $builder_union->orWhere('e.home', 1);
                                    } else {
                                        $query_union->where('n.home', 1);
                                        $builder_union->where('e.home', 1);
                                    }
                                    break;
                                case 'patronage':
                                    if($k) {
                                        $query_union->orWhere('n.patronate', 1);
                                        $builder_union->orWhere('e.patronage', 1);
                                    } else {
                                        $query_union->where('n.patronate', 1);
                                        $builder_union->where('e.patronage', 1);
                                    }
                                    break;
                                case 'recommended':
                                    if($k) {
                                        $builder_union->orWhere('e.recommended', 1);
                                    } else {
                                        $builder_union->where('e.recommended', 1);
                                    }
                                    break;
                            }
                        }
                        $query_union->groupEnd();
                        $builder_union->groupEnd();
                    }
                    if(!empty($content['config']['order'])) {
                        switch($content['config']['order']) {
                            case 'latest': $query->orderBy('date', 'DESC');
                                break;
                            case 'random': $query->orderBy('name', 'RANDOM');
                                break;
                        }
                    }
                    if(!empty($content['config']['no'])) {
                        $query->limit($content['config']['no']);
                    }
                }
                $union = $query_union;
                $builder = $builder_union->union($union);
                
                
                $list = $query->fromSubquery($builder, 'q')->get()->getResultArray();
                if (!empty($list) && !empty($this->locale)) {
                    foreach ($list as $k => $l) {
                        $list[$k]['link'] = ($this->locale ? $this->locale . '/' : '') . $l['link'];
                    }
                }
                return array(
                    'list' => $list,
                );
                break;
            case 'selected2':
            case 'selected':
            case 'list':
            default:
                // Blok listy na stronie zawezamy do wlasnego bloku - w sidebarze $content['id'] to id z sidebar_content, nie z page_content (Sidebar::showSidebar wywoluje index() z $link=null).
                // selected/selected2 zawezamy do blokow wskazanych w konfiguracji; brak wskazania = bez zawezenia.
                $id_page_cont = $slug === 'list' && !empty($link) ? array($content['id']) : (!empty($content['config']['lists']) ? $content['config']['lists'] : null);
                $events = array();
                $filters = array();
                if(!empty($link['get'])) {
                    for($i=0;$i<count($link['get']);$i+=2) {
                        $filters[$link['get'][$i]] = $link['get'][$i + 1];
                    }
                }
                $date_start = date('Y-m-d');
                $date_end = null;
                if(!empty($filters['d'])) {
                    $date_tmp = explode('-', $filters['d']);
                    if(!empty($date_tmp[1])) {
                        $date_start = date('Y-m-d', strtotime($date_tmp[0]));
                        $date_end = date('Y-m-d', strtotime($date_tmp[1]));
                    } else {
                        $date_start = $date_end = date('Y-m-d', strtotime($date_tmp[0]));
                    }
                }
                if(!empty($content['config'])) {
                    if(!empty($content['config']['tags'])) {
                        $tags = $this->eventTypeModel->db->table('tags t')->select('t.id,t.tag')->whereIn('t.tag', explode(',', $content['config']['tags']))->get()->getResultArray();
                    }
                }
                $query = $this->eventCalendarModel;
                if (empty($content['config']) || empty($content['config']['pagination']) || !$content['config']['pagination']) {
                    $query->db->table('event_calendar');
                }    
                $query->join('event e', 'e.id=event_calendar.id_event')
                    ->join('event_lang el', 'e.id=el.id_event')
                    ->join('event_place ep', 'ep.id=event_calendar.id_place', 'left')
                    ->join('event_place_lang epl', 'ep.id=epl.id_place', 'left')
                    ->join('links l', 'l.id=el.id_link')
                    ->join('links l2', 'l2.id=epl.id_link', 'left')
                    ->join('event_type_lang etl', 'etl.id_type=e.id_type', 'left')
                    ->select('IF(date_end IS NULL, 0, 1) as period,DATEDIFF(date_end, date_start) as date_diff,IF(date_start < CURDATE(), CURDATE(), date_start) as date_order,event_calendar.id,event_calendar.id_event,event_calendar.id_place,e.for_kids,e.patronage,e.source,el.price,el.name,l.link,event_calendar.custom_place,event_calendar.date_start,event_calendar.date_end,event_calendar.hours,epl.name as place,l2.link as place_link,etl.name as type_name,etl.slug as type_slug')
                    ->where('el.id_lang', $id_lang)
                    ->where('e.publish', 1)
                    ->groupStart()
                        ->where('epl.id_lang', $id_lang)
                        ->orWhere('ep.id', NULL)
                    ->groupEnd()
                    ->groupStart()
                        ->where('ep.publish', 1)
                        ->orWhere('event_calendar.custom_place !=', '')
                    ->groupEnd()
                    ->groupStart()
                        ->where('etl.id_lang', $id_lang)
                        ->orWhere('etl.id', null)
                    ->groupEnd();
                if($id_page_cont) {
                    $query->whereIn('e.id_page_cont', $id_page_cont);
                }
                if(empty($content['config']['show_all'])) {
                    if(empty($date_end)) {
                        $query->groupStart()
                                ->groupStart()
                                    ->where('event_calendar.date_start >=', $date_start)
                                    ->where('event_calendar.date_end', null)
                                ->groupEnd()
                                ->orWhere('event_calendar.date_end >=', $date_start)
                            ->groupEnd();
                    } else {
                        $query->groupStart()
                            ->groupStart()
                                ->where('event_calendar.date_start <=', $date_end)
                                ->where('event_calendar.date_end >=', $date_start)
                            ->groupEnd()
                            ->orGroupStart()
                                ->where('event_calendar.date_start =', $date_start)
                                ->where('event_calendar.date_end', NULL)
                            ->groupEnd()
                        ->groupEnd();
                    }
                }
                if(!empty($content['config'])) {
                    if(!empty($content['config']['tags']) && !empty($tags)) {
                        $query->groupStart();
                        foreach($tags as $t) {
                            $query->orLike('el.tags', ',' . $t['id'] . ',', 'both');
                        }
                        $query->groupEnd();
                    }
                    if(!empty($content['config']['types'])) {
                        $query->whereIn('e.id_type', $content['config']['types']);
                    }
                    if(!empty($content['config']['recommended']) && !empty($content['config']['patronage'])) {
                        $query->groupStart()->where('e.recommended', 1)->orWhere('e.patronage', 1)->groupEnd();
                    } else {
                        if(!empty($content['config']['recommended'])) {
                            $query->where('e.recommended', 1);
                        }
                        if(!empty($content['config']['patronage'])) {
                            $query->where('e.patronage', 1);
                        }
                    }
                }
                if(!empty($filters)) {
                    foreach($filters as $name=>$value) {
                        if(!empty($value)) {
                            switch($name) {
                                case 'd':
                                    $date = explode('-', $value);
                                    if(!isset($date[1])) {
                                        $query->groupStart()
                                            ->groupStart()->where('event_calendar.date_start=', date('Y-m-d', strtotime($date[0])))->where('event_calendar.date_end=', NULL)->groupEnd()
                                            ->orGroupStart()->where('event_calendar.date_start<=', date('Y-m-d', strtotime($date[0])))->where('event_calendar.date_end>=', date('Y-m-d', strtotime($date[0])))->groupEnd()
                                        ->groupEnd();
                                    } else {
                                        $query->groupStart()
                                            ->groupStart()->where('event_calendar.date_start<=', date('Y-m-d', strtotime($date[0])))->where('event_calendar.date_end>=', date('Y-m-d', strtotime($date[1])))->groupEnd()
                                            ->orGroupStart()->where('event_calendar.date_start>=', date('Y-m-d', strtotime($date[0])))->where('event_calendar.date_start<=', date('Y-m-d', strtotime($date[1])))->where('event_calendar.date_end', NULL)->groupEnd()
                                        ->groupEnd();
                                    }
                                    break;
                                case 'o':
                                    if($value == 'p') {
                                        $content['config']['order'] = 'most_popular';
                                        $content['config']['group_day'] = false;
                                        if(empty($filters['v'])) {
                                            $content['template'] = substr($content['template'], 0, -4) . '_popular.php';
                                        }
                                    }
                                    break;
                                case 's': $query->like('el.name', urldecode($value),'both');
                                    break;
                                case 't': $query->whereIn('etl.slug', explode(',', $value));
                                    break;
                                case 'v':
                                    $content['template'] = substr($content['template'], 0, -4) . '_' . $value . '.php';
                                    if($value == 1) {
                                        $content['config']['group_day'] = false;
                                    }
                                    break;
                            }
                        }
                    }
                }
                if(!empty($content['config']) && !empty($content['config']['order'])) {
                    switch($content['config']['order']) {
                        case 'most_popular': $query->orderBy('el.views', 'DESC');$query->orderBy('event_calendar.date_start', 'ASC')->orderBy('event_calendar.hours', 'ASC');$query->GroupBy('e.id');
                            break;
                        case 'latest': $query->orderBy('e.created_at', 'ASC');
                            break;
                        case 'random': $query->orderBy('e.id', 'RANDOM');
                            break;
                        case 'closest': 
                        default: $query->orderBy('date_order', 'ASC')->orderBy('date_diff', 'ASC')->orderBy('event_calendar.date_start', 'ASC')->orderBy('event_calendar.hours', 'ASC');
                            break;
                    }
                }
                if($slug == 'selected') {
                    $query->having('date_diff <=', 4)->orHaving('date_end', null);
                }
                if (empty($content['config']) || empty($content['config']['pagination']) || !$content['config']['pagination']) {
                    $list = $query->limit(!empty($content['config']) && !empty($content['config']['no']) ? intval($content['config']['no']) : $config->perPage)->get()->getResultArray();
                } else {
                    $list = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'event-' . $content['id']);
                }
                if (!empty($list)) {
                    foreach ($list as $k => $e) {   
                        if(!empty($content['config']['gallery_photo'])) {
                            $e['photo'] = $this->eventCalendarModel->db->table('event_files ef')->join('event_files_lang efl', 'ef.id=efl.id_file')->select('ef.path,ef.crop_dimension,efl.caption,efl.author')->where('ef.id_event', $e['id_event'])->where('ef.publish', 1)->where('ef.field', 'photos')->where('efl.id_lang', $id_lang)->orderBy('ef.order')->limit(1)->get()->getRowArray();
                        }
                        if(empty($e['photo'])) {
                            $e['photo'] = $this->eventCalendarModel->db->table('event_files ef')->select('ef.path,ef.crop_dimension')->where('ef.id_event', $e['id_event'])->where('ef.publish', 1)->where('ef.field', 'photo')->get()->getRowArray();
                        }
                        if(!empty($this->locale)) {
                            $e['link'] = ($this->locale ? $this->locale . '/' : '') . $e['link'];
                        }
                        if(empty($e['date_end']) || $e['date_start'] == $e['date_end']) {
                            $e['date'] = date('d.m.Y', strtotime($e['date_start']));
                        } else {
                            $d1 = explode('-', $e['date_start']);
                            $d2 = explode('-', $e['date_end']);
                            $e['date'] = $d1[2] . ($d1[1] != $d2[1] ? '.' . $d1[1] : '') . ($d1[0] != $d2[0] ? '.' . $d1[0] : '') . ' - ' . $d2[2] . '.' . $d2[1] . '.' . $d2[0];
                        }
                        $e['short_date'] = $e['date'];
                        if(!empty($e['hours'])) {
                            $e['hours'] = json_decode($e['hours']);
                            $e['date'] .= ' ' . implode(', ', $e['hours']);
                        }
                        if(!empty($content['config']) && !empty($content['config']['group_day'])) {
                            if($e['date_start'] < $date_start) {
                                $e['date_start'] = $date_start;
                            }
                            if(!empty($e['date_end']) && $e['date_end'] != '0000-00-00') {
                                if(!empty($date_end) && $e['date_end'] > $date_end) {
                                    $e['date_end'] = $date_end;
                                }
                                $date_key = $e['date_start'];
                                $count = 30;
                                do {
                                    $e['date'] = $date_key;
                                    $events[$date_key][] = $e;
                                    $date_key = date('Y-m-d', strtotime($date_key . ' + 1 day'));
                                    $count--;
                                } while($count && $date_key <= $e['date_end']);
                            } else {
                                $e['date'] = $e['date_start'];
                                $events[$e['date_start']][] = $e;
                            }
                        } else {
                            $e['date'] = $e['date_start'];
                            $events[] = $e;
                        }
                    }
                }
                
                $days = array();
                if(!empty($content['config']) && !empty($content['config']['search_engine'])) {
                    $today_time = strtotime(date('Y-m-d'));
                    for($i=0; $i<30; $i++) {
                        $date = date('Y-m-d', strtotime('+' . $i . ' days'));
                        $time = strtotime($date);
                        $day_no = date('N', $time);
                        $day = date('d', $time);
                        $month_no = date('n', $time);
                        $diff = ($time - $today_time) / (24 * 60 * 60);
                        $day_name = '';
                        switch ($diff) {
                            case 0: $day_name = lang('Event.Today');
                                break;
                            case 1: $day_name = lang('Event.Tomorrow');
                                break;
                            case 2: $day_name = lang('Event.AfterTomorrow');
                                break;
                            default: $day_name = lang('Event.days_names_no.' . $day_no);
                                break;
                        }
                        // warunki dat musza byc w zewnetrznej grupie, inaczej AND ponizej zwiazalby sie tylko z ostatnia galezia OR
                        $count_query = $this->eventCalendarModel->db->table('event e')->join('event_calendar ec', 'e.id=ec.id_event')->groupStart()->groupStart()->where('ec.date_start', $date)->where('ec.date_end', NULL)->groupEnd()->orGroupStart()->where('ec.date_start <=', $date)->where('ec.date_end >=', $date)->groupEnd()->groupEnd();
                        if($id_page_cont) {
                            $count_query->whereIn('e.id_page_cont', $id_page_cont);
                        }
                        $days[] = array(
                            'date' => $date,
                            'date_format' => date('d.m.Y', $time),
                            'day' => $day,
                            'day_no' => $day_no,
                            'month_no' => date('n', $time),
                            'day_name' => $day_name,
                            'month_name' => lang('Event.user.months_names_no2.' . $month_no),
                            'weekend_format' => in_array($day_no, array(5,6,7)) ? ($day - $day_no + 5) . '.' . date('m.Y', $time) . '-' . ($day - $day_no + 7) . '.' . date('m.Y', $time) : '',
                            'time' => $time,
                            'count' => $count_query->countAllResults()
                        );
                    }
                }
                if(!empty($days)) {
                    $date = date('Y-m-d');
                    if(!empty($filters['d'])) {
                        if(strlen($filters['d']) <= 10) {
                            $date = date('Y-m-d', strtotime($filters['d']));
                        }
                    }
                    $pagination = array(
                        'first' => base_url() . $link['link'],
                        'prev' => $date != date('Y-m-d') ? (date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime('+1 day')) ?  base_url() . $link['link'] :  base_url() . $link['link'] . '/g/d/' . date('d.m.Y', strtotime($date . ' -1 day'))) : '',
                        'next' => $date < $days[count($days) - 1]['date'] ? base_url() . $link['link'] . '/g/d/' . date('d.m.Y', strtotime($date . ' +1 day')) : '',
                        'last' => base_url() . $link['link'] . '/g/d/' . $days[count($days) - 1]['date_format'],
                    );
                    if($date != date('Y-m-d')) {
                        $pagination['canonical'] = base_url() . $link['link'];
                    }
                }
                $events_count = $this->eventCalendarModel->countCalendarEvents(date('Y-m'), $id_page_cont);
                return array(
                    'days' => $days,
                    'events_count' => $events_count,
                    'views_dir' => 'list',
                    'template' => $content['template'],
                    'filters' => $filters,
                    'types' => !empty($content['config']) && !empty($content['config']['search_engine']) ? $this->eventTypeModel->getTypesForEventsList($id_lang, $id_page_cont) : array(),
                    'search_engine' => !empty($content['config']) && !empty($content['config']['search_engine']),
                    'form_url' => !empty($link['link']) ? (!empty($this->locale) && $this->locale ? $this->locale . '/' : '') . $link['link'] : uri_string(),
                    'list' => $events,
                    'pager' => !empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination'] ? $this->eventCalendarModel->pager : null,
                    'pagination' => isset($pagination) ? $pagination : null
                );
                break;
        }
    }

    public function assets($slug = '', $template = '', $id_news=0, $data=array()) {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'js_ready' => array()
        );
        switch ($slug) {
            case 'calendar':
                break;
            case 'single_element':
                $assets['js'][] = 'https://maps.google.com/maps/api/js?sensor=false&key=' . (!empty($data['settings']) && !empty($data['settings']['google_map_key']) ? $data['settings']['google_map_key'] : (string) env('google.mapsApiKey'));
                $assets['js_ready'][] = 'updateViews();';
               break;			
        }
        $assets['css'][] = '/assets/css/slick.css';
        $assets['js'][] = '/assets/js/slick.min.js';
        return $assets;
    }
    
    public function ajax($post, $id_lang, $module_data, $link) 
    {
        if(!empty($post['action'])) {
            switch($post['action']) {
                case 'views':
                    $this->eventModel->db->table('event_lang')->where('id_link', $link['id'])->where('id_lang', $id_lang)->set('views', 'views+1', false)->update();
                    break;
                case 'calendar':
                    $date = date('Y-m', strtotime($post['date']));
                    $id_page_cont = null;
                    if(!empty($module_data['element_slug']) && !empty($post['content'])) {
                        if($module_data['element_slug'] === 'list' && empty($post['sidebar'])) {
                            // j.w. - przy $post['sidebar'] Home::index rozwiazuje $post['content'] z sidebar_content, wiec nie jest to id_page_cont.
                            $id_page_cont = array($post['content']);
                        } elseif($module_data['element_slug'] === 'calendar') {
                            // Home::index nie podaje configu bloku do ajax(), wiec doczytujemy go sami - inaczej zmiana miesiaca skasowalaby zawezenie.
                            $config = $this->eventCalendarModel->db->table(!empty($post['sidebar']) ? 'sidebar_content' : 'page_content')->select('config')->where('id', $post['content'])->get()->getRowArray();
                            $config = !empty($config['config']) ? unserialize($config['config']) : array();
                            $id_page_cont = !empty($config['lists']) ? $config['lists'] : null;
                        }
                    }
                    $data = array(
                        'list' => $this->eventCalendarModel->countCalendarEvents($date, $id_page_cont),
                        'date' => $date
                    );
                    if(str_contains($module_data['template'], '.php')) {
                        $module_data['template'] = str_replace('.php', '', $module_data['template']);
                    }
                    $module_data['template'] .= '_inside.php';
                    $html = view('Modules\Event\Views\user\calendar\\_month_inside.php', array('data' => $data, 'url' => $module_data['url'], 'global_links' => $this->global_links));
                    $response = array(
                        'status' => true,
                        'html' => base64_encode(urlencode($html))
                    );
                    return $this->response->setJSON($response);
                    break;
            }
        }
    }

    public function getSingleByLinkId($id_link, $id_lang, $link) {
        $get = $this->request->getGet();
        if(empty($link['module_slug'])) {
            $link['module_slug'] = '';
        }
        switch($link['module_slug']) {
            case 'place':
                $place = $this->eventModel->db->table('event_place ep')
                    ->join('event_place_lang epl', 'ep.id=epl.id_place')
                    ->join('page_content pc', 'ep.id_page_cont=pc.id')
                    ->join('event_files ef', 'ef.id_event=ep.id AND ef.field="place_photo"', 'left')
                    ->join('event_place_type ept', 'ept.id=ep.id_type', 'left')
                    ->join('event_place_type_lang eptl', 'ept.id=eptl.id_type', 'left')
                    ->join('links l', 'l.id=epl.id_link')
                    ->join('links l2', 'l2.id=eptl.id_link', 'left')
                    ->select('pc.id_page,ep.id,ep.template,ep.comment,epl.name,ep.www,ep.email,ep.phone,epl.content,epl.address,epl.working_hours,epl.repertoire,epl.id_link,l.link,ef.path,ept.cinema,eptl.name as place_type_name,l2.link as place_type_link')
                    ->where('epl.id_lang', $id_lang)
                    ->where('ep.publish', 1)
                    ->where('epl.id_link', $id_link)
                    ->where('ept.publish', 1)
                    ->groupStart()
                        ->where('eptl.id_lang', $id_lang)
                        ->orWhere('ept.id', null)
                    ->groupEnd()
                    ->get()->getRowArray();
                if(!empty($place)) {
                    if (!empty($this->locale) && !empty($place['place_type_link'])) {
                        $place['place_type_link'] = ($this->locale ? $this->locale . '/' : '') . $place['place_type_link'];
                    }
                    $place['photos'] = $this->eventModel->db
                        ->table('event_files ef')
                        ->join('event_files_lang efl', 'ef.id=efl.id_file')
                        ->select('ef.path,ef.mime,efl.caption,efl.author')
                        ->where('ef.id_event', $place['id'])
                        ->where('ef.field', 'place_photos')
                        ->where('efl.id_lang', $id_lang)
                        ->where('ef.publish', 1)
                        ->orderBy('ef.order', 'ASC')
                        ->get()
                        ->getResultArray();
                    if(!empty($place['cinema'])) {
                        $date = date('Y-m-d');
                        if(!empty($link['get'])) {
                            for($i=0;$i<count($link['get']);$i+=2) {
                                $get[$link['get'][$i]] = $link['get'][$i + 1];
                            }
                        }
                        if(!empty($get['d'])) {
                            $date = date('Y-m-d', strtotime($get['d']));
                        } elseif(!empty($get['date'])) {
                            $date = date('Y-m-d', strtotime($get['date']));
                        }
                        $place['calendar'] = $this->cinemaCalendarModel->getCalendar($id_lang, $this->locale, $date, $place['id']);
                        $place['dates'] = $this->cinemaCalendarModel->getCalendarDates($date, $link['link']);
                        $place['types'] = $this->cinemaCalendarModel->getMovieTypeForCalendar($id_lang);
                    }
                    $place['events'] = array();
                    $events = $this->eventCalendarModel->getPlaceRepertoire($place['id'], $id_lang);
                    if(!empty($events)) {
                        foreach ($events as $k => $e) {
                            if(!empty($this->locale)) {
                                $e['link'] = ($this->locale ? $this->locale . '/' : '') . $e['link'];
                            }
                            if(empty($e['date_end']) || $e['date_start'] == $e['date_end']) {
                                $e['date'] = date('d.m.Y', strtotime($e['date_start']));
                            } else {
                                $d1 = explode('-', $e['date_start']);
                                $d2 = explode('-', $e['date_end']);
                                $e['date'] = $d1[2] . ($d1[1] != $d2[1] ? '.' . $d1[1] : '') . ($d1[0] != $d2[0] ? '.' . $d1[0] : '') . ' - ' . $d2[2] . '.' . $d2[1] . '.' . $d2[0];
                            }
                            if(!empty($e['hours'])) {
                                $e['date'] .= ' ' . implode(', ', json_decode($e['hours']));
				$e['hours']=implode(', ', json_decode($e['hours']));
                            }
                            $place['events'][] = $e;
                        }
                    }
                    
                    $place['dir'] = 'place_single';
                }
                return $place;
                break;
            case 'place_type':
                $type = $this->eventModel->db->table('event_place_type ept')
                    ->join('event_place_type_lang eptl', 'ept.id=eptl.id_type')
                    ->join('links l', 'l.id=eptl.id_link')
                    ->select('ept.id,eptl.name,l.link')
                    ->where('eptl.id_lang', $id_lang)
                    ->where('ept.publish', 1)
                    ->where('eptl.id_link', $id_link)
                    ->orderBy('eptl.name', 'ASC')
                    ->get()->getRowArray();
                if(!empty($type)) {
					$type['cat_list']=$this->eventModel->getPlaceTypesForList($id_lang);
                    $ids = $this->eventModel->getPlaceTypeChilds($type['id']);
                    $type['places'] = $this->eventModel->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->join('links l', 'l.id=epl.id_link')->join('event_files ef', 'ef.id_event=ep.id AND ef.field="place_photo"', 'left')->select('ep.id,epl.name,l.link,ef.path')->where('epl.id_lang', $id_lang)->where('ep.publish', 1)->whereIn('ep.id_type', array_merge(array($type['id']), $ids))->get()->getResultArray();
                    $type['dir'] = 'places_single';
                    $type['template'] = 'list.php';
                    if(!empty($type['places'])) {
                        foreach($type['places'] as $p=>$place) {
                            $type['places'][$p]['link'] = ($this->locale ? $this->locale . '/' : '') . $place['link'];
							$type['places'][$p]['photo']=$this->eventModel->db->table('event_files ef')->join('event_files_lang fl', 'ef.id=fl.id_file')->select('ef.path,fl.caption,fl.author')->where('fl.id_lang', $id_lang)->where('ef.field', 'place_photos')->where('ef.id_event', $place['id'])->OrderBy('order','asc')->get()->getRowArray();
                        }
                    }
                }
                return $type;
                break;
            default:
                $event = $this->eventModel->db->table('event e')
                        ->join('event_lang el', 'e.id=el.id_event')
                        ->join('page_content pc', 'e.id_page_cont=pc.id')
                        ->join('event_files ef', 'e.id=ef.id_event AND ef.field="photo"', 'left')
                        ->join('event_type et', 'et.id=e.id_type', 'left')
                        ->join('event_type_lang etl', 'et.id=etl.id_type', 'left')
                        ->join('links l', 'l.id=el.id_link')
                        ->select('pc.id_page,e.id,e.patronage,e.for_kids,e.recommended,e.template,e.comment,e.source,el.name,el.subname,el.introduction,el.content,el.tickets,el.comments,el.price,el.id_link,l.link,etl.name as type_name,ef.path as photo,crop_dimension,el.tags,et.svg,e.id_type,e.source,etl.slug')
                        ->where('e.publish', 1)
                        ->where('el.id_lang', $id_lang)
                        ->groupStart()
                            ->where('etl.id_lang', $id_lang)
                            ->orWhere('et.id', null)
                        ->groupEnd()
                        ->where('el.id_link', $id_link)
                        ->get()->getRowArray();
                if (!empty($event)) {
                    $event['photos'] = $this->eventModel->db
                            ->table('event_files ef')
                            ->join('event_files_lang efl', 'ef.id=efl.id_file')
                            ->select('ef.path,ef.mime,efl.caption,efl.author')
                            ->where('ef.id_event', $event['id'])
                            ->where('ef.field', 'photos')
                            ->where('efl.id_lang', $id_lang)
                            ->where('ef.publish', 1)
                            ->orderBy('ef.order', 'ASC')
                            ->get()
                            ->getResultArray();
                    $event['calendar'] = $this->eventModel->db->table('event_calendar ec')
                            ->select('ec.date_start,ec.date_end,ec.hours,ec.custom_place,ec.id_place')
                            ->where('ec.id_event', $event['id'])
                            ->groupStart()
                                ->groupStart()
                                    ->where('ec.date_start >=', date('Y-m-d'))
                                    ->where('ec.date_end', NULL)
                                ->groupEnd()
                                ->orGroupStart()
                                    ->where('ec.date_start >=', date('Y-m-d'))
                                    ->orWhere('ec.date_end >=', date('Y-m-d'))
                                ->groupEnd()
                            ->groupend()
                            ->where('ec.date_start !=', NULL)
                            ->orderBy('ec.date_start', 'ASC')
                            ->orderBy('ec.hours', 'ASC')
                            ->get()->getResultArray();
                    if(empty($event['calendar'])) {
                        $event['calendar'] = $this->eventModel->db->table('event_calendar ec')
                            ->select('ec.date_start,ec.date_end,ec.hours,ec.custom_place,ec.id_place')
                            ->where('ec.id_event', $event['id'])
                            ->where('ec.date_start !=', NULL)
                            ->orderBy('ec.date_start', 'DESC')
                            ->orderBy('ec.hours', 'DESC')
                            ->limit(1)
                            ->get()->getResultArray();
                    }
                    if(!empty($event['calendar'])) {
                        foreach($event['calendar'] as $k=>$c) {
                            if(!empty($c['id_place'])) {
                                $event['calendar'][$k]['place'] = $this->eventModel->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place', 'left')->join('links l', 'l.id=epl.id_link')->select('epl.name,ep.id,l.link')->where('ep.id', $c['id_place'])->where('ep.publish', 1)->where('epl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->get()->getRowArray();
                            }
                            $event['calendar'][$k]['hours'] = json_decode($c['hours']);
                            if(empty($c['date_end']) || $c['date_start'] == $c['date_end']) {
                                $event['calendar'][$k]['date'] = date('d.m.Y', strtotime($c['date_start']));
                            } else {
                                $d1 = explode('-', $c['date_start']);
                                $d2 = explode('-', $c['date_end']);
                                $event['calendar'][$k]['date'] = $d1[2] . ($d1[1] != $d2[1] ? '.' . $d1[1] : '') . ($d1[0] != $d2[0] ? '.' . $d1[0] : '') . ' - ' . $d2[2] . '.' . $d2[1] . '.' . $d2[0];
                            }
                            if(!empty($c['hours'])) {
                                $event['calendar'][$k]['date'] .= ' ' . implode(', ', json_decode($c['hours']));
                            }
                        }
                    }
                    $event['tags'] = explode(',', trim($event['tags'], ','));
                    if(!empty($event['tags'])) {
                        $event['tags'] = $this->eventModel->db->table('tags t')->select('t.id,t.tag')->where('t.id_lang', $id_lang)->whereIn('t.id', $event['tags'])->get()->getResultArray();
                    }
                    if(!empty($event['calendar']) and !empty($event['calendar'][0]['id_place'])) {
                        $event['place_events']=$this->eventCalendarModel->getPlaceEventRepertoire($event['calendar'][0]['id_place'],$event['id'],$id_lang,10);
                    }	
                    if(!empty($event['id_type'])) {
                        $event['type_events']=$this->eventCalendarModel->getTypeRepertoire($event['id_type'],$event['id'],$id_lang,10);	
                    }		
                } else {
                    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                    exit();
                }
                return $event;
                break;
        }
    }
    
    public function showMainPlaceTypesWithHomePlacesList($id_lang, $locale, $id_page=0) {
        $page = array();
        $types = $this->eventModel->getMainPlaceTypesWithPlacesList($id_lang, true);
        if(!empty($types)) {
            foreach($types as $c=>$type) {
                if (!empty($locale)) {
                    $types[$c]['link'] = ($locale ? $locale . '/' : '') . $type['link'];
                }
                if(!empty($types[$c]['places']) && !empty($locale)) {
                    foreach($types[$c]['places'] as $p=>$place) {
                        $types[$c]['places'][$p]['link'] = ($locale ? $locale . '/' : '') . $place['link'];
                    }
                }
            }
            if(!empty($id_page)) {
                $page = $this->eventModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->select('p.id,pl.name,l.link')->where('pl.id_lang', $id_lang)->where('p.publish', 1)->where('p.id', $id_page)->get()->getRowArray();
                if(!empty($page) && !empty($locale)) {
                    $page['link'] = ($locale ? $locale . '/' : '') . $page['link'];
                }
            }
        }
        return view('\Modules\Event\Views/user/types_home_places', array('types' => $types, 'page' => $page, 'id_lang' => $id_lang, 'locale' => $locale));
    }

    public function getBreadcramb($object, $locale, $id_lang, $link) {
        if (!empty($object)) {
            if(empty($link['module_slug'])) {
                $link['module_slug'] = '';
            }
            switch ($link['module_slug']) {
                case 'place_type':
				$page = $this->eventModel->db->table('module_element e')->join('page_content pc', 'pc.id_module_element=e.id')->join('page_lang pl', 'pl.id_page=pc.id_page')->join('links l', 'l.id=pl.id_link')->Select('pc.id_page,pl.name,pl.id_link,l.link')->where('pl.id_lang', $id_lang)->where('e.slug', 'places')->get()->getRowArray();
				 return array(array(
                        'id' => '',
                        'name' => 'Rozrywka',
                        'link' => ($locale ? '/' . $locale : '') . '/rozrywka',
						)
                    ,array(
                        'id' => '',
                        'name' => $page['name'],
                        'link' => ($locale ? '/' . $locale : '') . '/'.$page['link'],
                    ),
					array(
                        'id' => $object['id'],
                        'name' => $object['name'],
                        'link' => ($locale ? '/' . $locale : '') . '/' . $object['link'],
                    ));
                    break;
                case 'place':
                    return array(array(
                        'id' => $object['id'],
                        'name' => $object['name'],
                        'link' => ($locale ? '/' . $locale : '') . '/' . $object['link'],
                    ));
                    break;
                default:
                    return array(array(
                        'id' => $object['id'],
                        'name' => $object['name'],
                        'link' => ($locale ? '/' . $locale : '') . '/' . $object['link'],
                    ));
                    break;
            }
            
        }
        return array();
    }

    public function getMetaTags($id, $id_lang, $link, $data=array()) {
        helper('text');
        $meta_tags = array();
        if(empty($link['module_slug'])) {
            $link['module_slug'] = '';
        }
        switch ($link['module_slug']) {
            case 'place':
                $meta = $this->eventModel->db->table('event_place ep')
                    ->join('event_place_lang epl', 'ep.id=epl.id_place')
                    ->join('event_meta_lang eml', 'ep.id=eml.id_event AND slug="place"', 'left')
                    ->select('epl.name as title,epl.content,eml.title as meta_title,eml.description as meta_desc,eml.keywords as meta_keys')
                    ->where('ep.id', $id)
                    ->where('epl.id_lang', $id_lang)
                    ->groupStart()
                        ->where('eml.id_lang', $id_lang)
                        ->orWhere('eml.id', null)
                    ->groupEnd()
                    ->get()->getRowArray();
                if(!empty($meta)) {
                    $meta['image'] = $this->eventModel->db->table('event_files ef')->join('event_files_lang efl', 'ef.id=efl.id_file', 'left')->select('ef.path,ef.ext,efl.caption')->where('ef.id_event', $id)->where('ef.field', 'place_photo')->where('efl.id_lang', $id_lang)->get()->getRowArray();
                }
                break;
            case 'place_type':
				$page = $this->eventModel->db->table('module_element e')->join('page_content pc', 'pc.id_module_element=e.id')->join('page_meta_lang pl', 'pl.id_page=pc.id_page')->Select('pc.id_page,pl.title,pl.description,pl.keywords')->where('pl.id_lang', $id_lang)->where('e.slug', 'places')->get()->getRowArray();
				$place_type=$this->eventModel->db->table('event_place_type e')->join('event_place_type_lang pl', 'pl.id_type=e.id')->Select('pl.name')->where('pl.id_lang', $id_lang)->where('e.id', $id)->get()->getRowArray();
				$meta_tags['title'] = $place_type['name'].' | '.$page['title'];
                $meta_tags['image_alt'] = $meta_tags['title'];
				$meta_tags['description'] = $place_type['name'].' | '.$page['description'];
				$meta_tags['keywords'] = $place_type['name'].', '.$page['keywords'];
				return $meta_tags;
                break;
            default:
                 $meta = $this->eventModel->db->table('event e')
                    ->join('event_lang el', 'e.id=el.id_event')
                    ->join('event_meta_lang eml', 'e.id=eml.id_event AND slug=""', 'left')
                    ->join('event_type_lang etl', 'e.id_type=etl.id_type', 'left')
                    ->select('el.name as title,el.introduction,el.content,eml.title as meta_title,eml.description as meta_desc,eml.keywords as meta_keys,etl.name as type_name,etl.name2 as type_name2')
                    ->where('e.id', $id)
                    ->where('el.id_lang', $id_lang)
                    ->groupStart()
                        ->where('eml.id_lang', $id_lang)
                        ->orWhere('eml.id', null)
                    ->groupEnd()
                    ->groupStart()
                        ->where('etl.id_lang', $id_lang)
                        ->orWhere('etl.id', null)
                    ->groupEnd()
                    ->get()->getRowArray();
                $meta['calendar'] = $this->eventModel->db->table('event_calendar ec')
                    ->join('event_place_lang epl', 'epl.id_place=ec.id_place', 'left')
                    ->select('ec.date_start,ec.date_end,ec.hours,ec.custom_place,ec.id_place,epl.name as place')
                    ->where('ec.id_event', $id)
                    ->where('ec.date_start !=', NULL)
                    ->where('ec.date_start >=', date('Y-m-d'))
                    ->groupStart()
                        ->where('epl.id_lang', $id_lang)
                        ->orWhere('epl.id', null)
                    ->groupEnd()
                    ->orderBy('ec.date_start', 'ASC')
                    ->orderBy('ec.hours', 'ASC')
                    ->limit(1)
                    ->get()->getRowArray();
                $meta['keywords2'] = 'Rzeszów, dzieje się w Rzeszowie, Rozrywka w Rzeszowie, Kultura w Rzeszowie, Imprezy w Rzeszowie, Kalendarz imprez, ' . $meta['title'] . ', ' . (!empty($meta['calendar']['place']) ? $meta['calendar']['place'] : (!empty($meta['calendar']['custom_place']) ? $meta['calendar']['custom_place'] : ''));
                if(!empty($meta)) {
                    $meta['image'] = $this->eventModel->db->table('event_files ef')->join('event_files_lang efl', 'ef.id=efl.id_file', 'left')->select('ef.path,ef.ext,efl.caption')->where('ef.id_event', $id)->where('ef.field', 'place_photo')->where('efl.id_lang', $id_lang)->get()->getRowArray();
                }
                if(0 && !empty($data)) {
                    $images = [];
                    if(!empty($data['photo']) && file_exists(WRITEPATH . 'uploads/' . $data['photo']['path'])) {
                        $photo_info = getimagesize(WRITEPATH . 'uploads/' . $data['photo']['path']);
                        $images[] = [
                            '@type' => 'ImageObject',
                            '@id' => base_url() . 'image/' . $data['photo']['path'],
                            'url' => base_url() . 'image/' . $data['photo']['path'],
                            'width' => $photo_info[0],
                            'height' => $photo_info[1],
                            'caption' => $data['photo']['caption'],
                        ];
                    }
                    if(!empty($data['photos'])) {
                        foreach($data['photos'] as $photo) {
                            if(file_exists(WRITEPATH . 'uploads/' . $photo['path'])) {
                                $photo_info = getimagesize(WRITEPATH . 'uploads/' . $photo['path']);
                                $images[] = [
                                    '@type' => 'ImageObject',
                                    '@id' => base_url() . 'image/' . $photo['path'],
                                    'url' => base_url() . 'image/' . $photo['path'],
                                    'width' => $photo_info[0],
                                    'height' => $photo_info[1],
                                    'caption' => $photo['caption'],
                                ];
                            }
                        }
                    }
                    $metatags['microdata']['single'] = [
                        '@type' => 'NewsArticle',
                        '@id' => !empty($data['link']) ? $data['link'] : '',
                        'mainEntityOfPage' => [
                            '@type' => 'WebPage',
                            '@id' => !empty($data['link']) ? $data['link'] : '',
                        ],
                        'headline' => !empty($data['title']) ? esc($data['title']) : '',
                        'alternativeHeadline' => !empty($data['subtitle']) ? esc($data['subtitle']) : '',
                        'description' => !empty($data['introduction']) ? esc($data['introduction']) : '',
                        'url' => !empty($data['link']) ? $data['link'] : '',
                        'datePublished' => date('Y-m-d', strtotime($data['date'])) . 'T' . date('H:i:s', strtotime($data['date'])) . 'Z',
                        'dateModified' => !empty($data['edited_at']) ? date('Y-m-d', strtotime($data['edited_at'])) . 'T' . date('H:i:s', strtotime($data['edited_at'])) . 'Z' : '',
                        'author' => [
                            '@type' => 'Person',
                            'name' => !empty($data['author']) ? esc($data['author']) : 'Redakcja',
                        ],
                        'publisher' => [
                            '@id' => base_url() . '#organization',
                        ],
                        'image' => $images,
                        'articleSection' => !empty($data['info_page']) && !empty($data['info_page']['name']) ? $data['info_page']['name'] : '',
                        'articleBody' => !empty($data['original_content']) ? esc($data['original_content']) : esc($data['content']),
                        'isAccessibleForFree' => true,
                    ];
                }
                break;
        }
        if (!empty($meta)) {
            if (!empty($meta['meta_title'])) {
                $meta_tags['title'] = $meta['meta_title'];
                $meta_tags['image_alt'] = $meta['meta_title'];
            } elseif (!empty($meta['title'])) {
                $meta_tags['title'] = $meta['title'] . (!empty($meta['type_name']) || !empty($meta['type_name2']) ? ' - ' . (!empty($meta['type_name2']) ? $meta['type_name2'] : $meta['type_name']) . ' Rzeszów' : '');
                if(strlen($meta_tags['title']) <= 40) {
                    $meta_tags['title'] .= ' - Kalendarz wydarzeń RESinet.pl';
                } elseif(strlen($meta_tags['title']) <= 50) {
                    $meta_tags['title'] .= ' - Kalendarz wydarzeń';
                } elseif(strlen($meta_tags['title']) <= 60) {
                    $meta_tags['title'] .= ' - RESinet.pl';
                }
                $meta_tags['image_alt'] = $meta['title'];
            }
            $desc_prefix = '';
            if(!empty($meta['calendar']['place'])) {
                $desc_prefix .= $meta['calendar']['place'];
            } elseif(!empty($meta['calendar']['custom_place'])) {
                $desc_prefix .= $meta['calendar']['custom_place'];
            }
            if(!empty($meta['calendar']['date_start'])) {
                $desc_prefix .= ' ' . date('d.m.Y', strtotime($meta['calendar']['date_start']));
                if(!empty($meta['calendar']['date_end'])) {
                    $desc_prefix .= ' - ' . date('d.m.Y', strtotime($meta['calendar']['date_end']));
                }
            }
            if(!empty($desc_prefix)) {
                $desc_prefix .= '. ';
            }
            if (!empty($meta['meta_desc'])) {
                $meta_tags['description'] = $meta['meta_desc'];
            } elseif (!empty($meta['introduction'])) {
                $meta_tags['description'] = character_limiter($desc_prefix . $meta['introduction'], 160);
            } elseif (!empty($meta['content'])) {
                $meta_tags['description'] = character_limiter($desc_prefix . strip_tags($meta['content']), 160);
            }
            if (!empty($meta['meta_keys'])) {
                $meta_tags['keywords'] = $meta['meta_keys'];
            } elseif(!empty($meta['keywords2'])) {
                $meta_tags['keywords'] = $meta['keywords2'];
            }
            if (!empty($meta['image']) && !empty($meta['image']['path']) && file_exists(WRITEPATH . 'uploads/' . $meta['image']['path'])) {
                $size = getimagesize(WRITEPATH . 'uploads/' . $meta['image']['path']);
                $meta_tags['image'] = base_url() . 'image/' . $meta['image']['path'];
                $meta_tags['image_width'] = $size[0];
                $meta_tags['image_height'] = $size[1];
                $meta_tags['image_type'] = $meta['image']['ext'];
                if (!empty($meta['image']['caption'])) {
                    $meta_tags['image_alt'] = $meta['image']['caption'];
                }
            }
        }
        return $meta_tags;
    }
    
    public function getContentMetaTags($content, $metatags, $data, $settings, $language) {
        if (empty($data)) {
            return $metatags;
        }
        $main_entity = [];
        if(!empty($data['list'])) {
            $list = [];
            $k = 1;
            if(!empty($content['config']['group_day'])) {
                foreach($data['list'] as $day) {
                    if(!empty($day)) {
                        foreach($day as $l) {
                            if(!empty($l['photo']) && !empty($l['photo']['path']) && file_exists(WRITEPATH . 'uploads/' . $l['photo']['path'])) {
                                $l['photo']['info'] = getimagesize(WRITEPATH . 'uploads/' . $l['photo']['path']);
                            }
                            if(!empty($l['hours'])) {
                                if(!is_array($l['hours'])) {
                                    $l['hours'] = json_decode($l['hours'], true);
                                }
                                if(!empty($l['hours'])) {
                                    if(count($l['hours']) < 1) {
                                        $sub_events = [];
                                        foreach($l['hours'] as $h) {
                                            $sub_events[] = [
                                                '@type' => 'Event',
                                                'name' => esc($l['name']),
                                                'startDate' => date('Y-m-d', strtotime($l['date_start'])) . (!empty($l['hour']) ? 'T' . date('H:i:s', strtotime($h)) . 'Z' : ''),
                                                'endDate' => !empty($l['date_end']) ? date('Y-m-d', strtotime($l['date_end'])) : '',
                                            ];
                                        }
                                    } else {
                                        $l['hour'] = date('H:i:s', strtotime($l['hours'][0]));
                                    }
                                }
                            }
                            $list[] = [
                                '@type' => 'ListItem',
                                'position' => $k,
                                'item' => [
                                    '@type' => 'Event',
                                    'name' => esc($l['name']),
                                    'description' => '',
                                    'startDate' => empty($sub_events) ? date('Y-m-d', strtotime($l['date_start'])) . (!empty($l['hour']) ? 'T' . $l['hour'] . 'Z' : '') : '',
                                    'endDate' => empty($sub_events) && !empty($l['date_end']) ? date('Y-m-d', strtotime($l['date_end'])) : '',
                                    'eventStatus' => '',
                                    'eventAttendanceMode' => '',
                                    'location' => [
                                       '@type' => 'Place',
                                       'name' => !empty($l['place']) ? $l['place'] : $l['custom_place'],
                                    ],
                                    'subEvent' =>  !empty($sub_events) ? $sub_events : ''
                                ],
                                'organizer' => [
                                    '@type' => '',
                                    'name' => '',
                                    'url' => '',
                                ],
                                'image' => !empty($l['photo']) && !empty($l['photo']['path']) ? base_url() . 'image/' . $l['photo']['path'] : '',
                                'url' => base_url() . $l['link']
                            ];
                            $k++;
                        }
                    }
                }
            } else {
                foreach($data['list'] as $l) {
                    if(!empty($l['photo']) && !empty($l['photo']['path']) && file_exists(WRITEPATH . 'uploads/' . $l['photo']['path'])) {
                        $l['photo']['info'] = @getimagesize(WRITEPATH . 'uploads/' . $l['photo']['path']);
                    }
                    if(!empty($l['hours'])) {
                        if(!is_array($l['hours'])) {
                            $l['hours'] = json_decode($l['hours'], true);
                        }
                        if(!empty($l['hours'])) {
                            if(count($l['hours']) < 1) {
                                $sub_events = [];
                                foreach($l['hours'] as $h) {
                                    $sub_events[] = [
                                        '@type' => 'Event',
                                        'name' => esc($l['name']),
                                        'startDate' => date('Y-m-d', strtotime($l['date_start'])) . (!empty($l['hour']) ? 'T' . date('H:i:s', strtotime($h)) . 'Z' : ''),
                                        'endDate' => !empty($l['date_end']) ? date('Y-m-d', strtotime($l['date_end'])) : '',
                                    ];
                                }
                            } else {
                                $l['hour'] = date('H:i:s', strtotime($l['hours'][0]));
                            }
                        }
                    }
                    $list[] = [
                        '@type' => 'ListItem',
                        'position' => $k,
                        'item' => [
                            '@type' => 'Event',
                            'name' => esc($l['name']),
                            'description' => '',
                            'startDate' => empty($sub_events) ? date('Y-m-d', strtotime($l['date_start'])) . (!empty($l['hour']) ? 'T' . $l['hour'] . 'Z' : '') : '',
                            'endDate' => empty($sub_events) && !empty($l['date_end']) ? date('Y-m-d', strtotime($l['date_end'])) : '',
                            'eventStatus' => '',
                            'eventAttendanceMode' => '',
                            'location' => [
                               '@type' => 'Place',
                               'name' => !empty($l['place']) ? $l['place'] : $l['custom_place'],
                            ],
                            'subEvent' =>  !empty($sub_events) ? $sub_events : ''
                        ],
                        'organizer' => [
                            '@type' => '',
                            'name' => '',
                            'url' => '',
                        ],
                        'image' => !empty($l['photo']) && !empty($l['photo']['path']) ? base_url() . 'image/' . $l['photo']['path'] : '',
                        'url' => base_url() . $l['link']
                    ];
                    $k++;
                }
            }
            $main_entity[] = [
                '@type' => 'ItemList',
                'numberOfItems' => count($list),
                'itemListElement' => $list
            ];
        }
        if(!empty($data['events'])) {
            $list = [];
            foreach($data['events'] as $k=>$l) {
                if(!empty($l['photo']) && !empty($l['photo']['path']) && file_exists(WRITEPATH . 'uploads/' . $l['photo']['path'])) {
                    $l['photo']['info'] = getimagesize(WRITEPATH . 'uploads/' . $l['photo']['path']);
                }
                if(!empty($l['hours'])) {
                    $l['hours'] = json_decode($l['hours'], true);
                    if(!empty($l['hours'])) {
                        if(count($l['hours']) < 1) {
                            $sub_events = [];
                            foreach($l['hours'] as $h) {
                                $sub_events[] = [
                                    '@type' => 'Event',
                                    'name' => esc($l['name']),
                                    'startDate' => date('Y-m-d', strtotime($l['date_start'])) . (!empty($l['hour']) ? 'T' . date('H:i:s', strtotime($h)) . 'Z' : ''),
                                    'endDate' => !empty($l['date_end']) ? date('Y-m-d', strtotime($l['date_end'])) : '',
                                ];
                            }
                        } else {
                            $l['hour'] = date('H:i:s', strtotime($l['hours'][0]));
                        }
                    }
                }
                $list[] = [
                    '@type' => 'ListItem',
                    'position' => $k + 1,
                    'item' => [
                        '@type' => 'Event',
                        'name' => esc($l['name']),
                        'description' => '',
                        'startDate' => empty($sub_events) ? date('Y-m-d', strtotime($l['date_start'])) . (!empty($l['hour']) ? 'T' . $l['hour'] . 'Z' : '') : '',
                        'endDate' => empty($sub_events) && !empty($l['date_end']) ? date('Y-m-d', strtotime($l['date_end'])) : '',
                        'eventStatus' => '',
                        'eventAttendanceMode' => '',
                        'location' => [
                           '@type' => 'Place',
                           'name' => !empty($l['place']) ? $l['place']['name'] : $l['custom_place'],
                        ],
                        'subEvent' =>  !empty($sub_events) ? $sub_events : ''
                    ],
                    'organizer' => [
                        '@type' => '',
                        'name' => '',
                        'url' => '',
                    ],
                    'image' => base_url() . 'image/' . $l['photo'],
                    'url' => base_url() . $l['link']
                ];
            }
            $main_entity[] = [
                '@type' => 'ItemList',
                'numberOfItems' => count($list),
                'itemListElement' => $list
            ];
        
        }
        
        if(!empty($main_entity)) {
            if(empty($metatags['microdata']['collection_page'])) {
                $metatags['microdata']['collection_page'] = [
                    '@type' => 'CollectionPage',
                    '@id' => $metatags['canonical'] . '#collectionpage',
                    'url' => $metatags['canonical'],
                    'name' => $metatags['title'],
                    'description' => $metatags['description'],
                    'inLanguage' => !empty($language['iso_code']) ? $language['iso_code'] : $language['lang_code'],
                    'isPartOf' => [
                        '@id' => base_url() . '#website'
                    ],
                    'mainEntity' => $main_entity
                ];
            } else {
                $metatags['microdata']['collection_page']['mainEntity'] = array_merge($metatags['microdata']['collection_page']['mainEntity'], $main_entity);
            }
        }
        return $metatags;
    }
}