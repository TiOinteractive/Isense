<?php

namespace Modules\Cinema\Libraries;

use Modules\Cinema\Models\CinemaCalendarModel;
use Modules\Cinema\Models\CinemaMovieModel;

class Cinema {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->cinemaCalendarModel = new CinemaCalendarModel();
        $this->cinemaMovieModel = new CinemaMovieModel();
    }

    public function index($content, $id_lang, $slug = '', $link = array()) {
        $config = new \Config\Pager;
        $get = $this->request->getGet();
        switch ($slug) {
            case 'announcement':
                $date = date('Y-m-d');
		$dno = date('N', strtotime($date));
		if($dno < 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno + 2) . ' days'));
		elseif($dno > 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno - 5) . ' days'));
		else $date_from = $date;
		$time_to = time();//strtotime($date_from . ' + 7 days');
		$list = array(
			'premiere' => array(),
			'announcement' => array(),
		);
                $movies = $this->cinemaMovieModel->db->table('cinema_announcement ca')
                    ->join('cinema_movie cm', 'cm.id=ca.id_movie')
                    ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                    ->join('cinema_files cf', 'cf.id_cinema=cm.id AND cf.field="movie_poster"', 'left')
                    ->join('links l', 'l.id=cml.id_link')
                    ->select('ca.date,ca.id_movie,cm.duration,cm.for_kids,cm.production,cml.title,cml.country,cml.director,cml.scenario,cml.actors,l.link,cf.path')
                    ->where('cml.id_lang', $id_lang)
                    ->where('ca.date >=', $date_from)
                    ->where('cm.publish', 1)
                    ->orderBy('ca.date', 'ASC')
                    ->groupBy('ca.id_movie')
                    ->orderBy('ca.date ASC')
                    ->get()->getResultArray();
		if(!empty($movies)) {
                    foreach($movies as $k=>$m) {
                        $date = substr($m['date'], 0, 10);
                        $m['date'] = date('d.m.Y', strtotime($m['date']));
                        $m['genres'] = $this->cinemaMovieModel->db->table('cinema_movie_genres cmg')
                            ->join('cinema_genre cg', 'cg.id=cmg.id_genre')
                            ->join('cinema_genre_lang cgl', 'cg.id=cgl.id_genre')
                            ->select('cgl.name')
                            ->where('cg.publish', 1)
                            ->where('cgl.id_lang', $id_lang)
                            ->where('cmg.id_movie', $m['id_movie'])
                            ->get()->getResultArray();
                        if(strtotime($m['date']) <= $time_to) {
                            $list['premiere'][] = $m;
                        } else {
                            $list['announcement'][] = $m;
                        }
                    }
		}
                return array(
                    'list' => $list,
                );
                
                break;
            case 'home':
                $movies = array();
                if(!empty($content['config']['option'])) {
                    switch ($content['config']['option']) {
                        case 'premieres': 
                            $date = date('Y-m-d');
                            $dno = date('N', strtotime($date));
                            if($dno < 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno + 2) . ' days'));
                            elseif($dno > 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno - 5) . ' days'));
                            else $date_from = $date;
                            $date_to = date('Y-m-d', strtotime($date_from . ' + 7 days'));
                            $movies = $this->cinemaCalendarModel->db->table('cinema_announcement ca')
                                ->join('cinema_movie cm', 'cm.id=ca.id_movie')
                                ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                                ->join('cinema_files cf', 'cf.id_cinema=cm.id AND cf.field="movie_poster"')
                                ->join('links l', 'l.id=cml.id_link')
                                ->select('cm.id,cml.title,cf.path as poster,l.link,1 as premiere')
                                ->where('cml.id_lang', $id_lang)
                                ->where('ca.date >=', $date_from)
                                ->where('ca.date <', $date_to)
                                ->where('cm.publish', 1)
                                ->orderBy('ca.date', 'ASC')
                                ->get()->getResultArray();
                            break;
                        case 'announcements': 
                            
                            $date = date('Y-m-d');
                            //$dno = intval(date('N', strtotime($date)));
                            //if($dno <= 5) $date = date('Y-m-d', strtotime($date . ' + ' . (5 - $dno + 7) . ' days'));
                            //elseif($dno > 5) $date = date('Y-m-d', strtotime($date . ' +' . (7 - $dno + 5) . ' days'));
                            $movies = $this->cinemaCalendarModel->db->table('cinema_announcement ca')
                                ->join('cinema_movie cm', 'cm.id=ca.id_movie')
                                ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                                ->join('cinema_files cf', 'cf.id_cinema=cm.id AND cf.field="movie_poster"')
                                ->join('links l', 'l.id=cml.id_link')
                                ->select('cm.id,cml.title,cf.path as poster,l.link,1 as announcement')
                                ->where('cml.id_lang', $id_lang)
                                ->where('ca.date >=', $date)
                                ->where('cm.publish', 1)
                                ->orderBy('ca.date', 'ASC')
                                ->get()->getResultArray();
                            break;
                        case 'today': 
                        default:
                            $list = $this->cinemaCalendarModel->db->table('cinema_calendar cc')
                                ->join('cinema_movie cm', 'cm.id=cc.id_movie')
                                ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                                ->join('cinema_files cf', 'cf.id_cinema=cm.id AND cf.field="movie_poster"')
                                ->join('links l', 'l.id=cml.id_link')
                                ->join('cinema_announcement ca', 'ca.id_movie=cm.id', 'left')
                                ->select('cm.id,cml.title,cf.path as poster,l.link,ca.date as announcement_date')
                                ->where('cml.id_lang', $id_lang)
                                ->like('cc.date', date('Y-m-d'), 'after')
                                ->where('cm.publish', 1)
                                ->groupBy('cm.id')
                                ->orderBy('premiere', 'DESC')
                                ->orderBy('ca.date', 'ASC')
                                ->orderBy('cc.date', 'ASC')
                                ->get()->getResultArray();
                            if(!empty($list)) {
                                $date = date('Y-m-d');
                                $dno = date('N', strtotime($date));
                                if($dno < 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno + 2) . ' days'));
                                elseif($dno > 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno - 5) . ' days'));
                                else $date_from = $date;
                                $time_from = strtotime($date_from);
                                $time_to = strtotime($date_from . ' + 7 days');
                                $tmp = [];
                                foreach($list as $k=>$l) {
                                    if(!empty($l['announcement_date'])) {
                                        if(strtotime($l['announcement_date']) >= $time_from && strtotime($l['announcement_date']) < $time_to) {
                                            $l['premiere'] = 1;
                                            $movies[] = $l;
                                        } else {
                                            $tmp[] = $l;
                                        }
                                    } else {
                                        $tmp[] = $l;
                                    }
                                }
                                $movies = array_merge($movies, $tmp);
                            }
                            break;
                    }
                }

                if (!empty($movies)) {
                    foreach ($movies as $k => $m) {
                        if(!empty($this->locale)) {
                            $movies[$k]['link'] = ($this->locale ? $this->locale . '/' : '') . $m['link'];
                        }
                    }
                }
                return array(
                    'list' => $movies,
                );
                break;
            case 'calendar':
                $calendar = array();
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
                $dates = $this->cinemaCalendarModel->getCalendarDates($date, $link['link']);
                if(!empty($dates)) {
                    $pagination = array(
                        'first' => base_url() . $link['link'],
                        'prev' => $date != date('Y-m-d') ? ($date == date('Y-m-d', strtotime('+1 day')) ?  base_url() . $link['link'] :  base_url() . $link['link'] . '/g/d/' . date('Y-m-d', strtotime($date . ' -1 day'))) : '',
                        'next' => $date != $dates[count($dates) - 1]['date'] ? base_url() . $link['link'] . '/g/d/' . date('Y-m-d', strtotime($date . ' +1 day')) : '',
                        'last' => base_url() . $link['link'] . '/g/d/' . $dates[count($dates) - 1]['date'],
                    );
                    //if($date != date('Y-m-d')) {
                        //$pagination['canonical'] = base_url() . $link['link'] . '/g/d/' . $date;
                    //}
                }
                
                return array(
                    'dates' => $dates,
                    'calendar' => $this->cinemaCalendarModel->getCalendar($id_lang, $this->locale, $date),
                    'types' => $this->cinemaCalendarModel->getMovieTypeForCalendar($id_lang),
                    'cinemas' => $this->cinemaCalendarModel->getCinemasForCalendar($id_lang, $this->locale),
                    'pagination' => !empty($pagination) ? $pagination : null
                );
                break;
            case 'movies':
                $query = $this->cinemaMovieModel->join('cinema_movie_lang cml', 'cinema_movie.id=cml.id_movie')
                    ->join('links l', 'l.id=cml.id_link')
                    ->join('cinema_files cf', 'cf.id_cinema=cinema_movie.id AND cf.field="movie_poster"', 'left')
                    ->select('cinema_movie.id,cml.title,l.link,cf.path')
                    ->where('cinema_movie.publish', 1)
                    ->where('cml.id_lang', $id_lang)
                    ->orderBy('cinema_movie.created_at', 'DESC');
                $movies = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'movie-' . $content['id']);
                
                if(!empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination']) {
                    $pagination = array(
                        'first' => base_url() . $link['link'],
                        'prev' => $this->cinemaMovieModel->pager->getPreviousPageURI('movie-' . $content['id']),
                        'next' => $this->cinemaMovieModel->pager->getNextPageURI('movie-' . $content['id']),
                        'last' => base_url() . $link['link'] . '?page_movie-' . $content['id'] . '=' . $this->cinemaMovieModel->pager->getLastPage('movie-' . $content['id']),
                    );
                }
                
                return array(
                    'movies' => $movies,
                    'pager' => !empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination'] ? $this->cinemaMovieModel->pager : null,
                    'pagination' => !empty($pagination) ? $pagination : null
                );
                break;
        }
    }
    
    public function getSingleByLinkId($id_link, $id_lang, $link) {
        $get = $this->request->getGet();
        $movie = $this->cinemaMovieModel->db->table('cinema_movie cm')
                ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                ->join('page_content pc', 'cm.id_page_cont=pc.id')
                ->join('cinema_files cf', 'cf.id_cinema=cm.id AND cf.field="movie_poster"', 'left')
                ->join('cinema_files_lang cfl', 'cf.id=cfl.id_file', 'left')
                ->join('links l', 'l.id=cml.id_link')
                ->select('pc.id_page,cm.id,cm.id_page_cont,cm.production,cm.duration,cm.age,cm.recommended,cm.for_kids,cm.patronage,cm.dont_miss,cm.video_url,cm.template,cml.title,cml.original,cml.introduction,cml.content,cml.country,cml.director,cml.actors,cml.scenario,cml.distributor,l.link,cf.path as poster,cfl.caption as poster_caption,cfl.author as poster_author')
                ->where('cm.publish', 1)
                ->where('cml.id_lang', $id_lang)
                ->groupStart()
                    ->where('cfl.id_lang', $id_lang)
                    ->orWhere('cfl.id', null)
                ->groupEnd()
                ->where('cml.id_link', $id_link)
                ->get()->getRowArray();
        if(!empty($movie)) {
            $this->cinemaMovieModel->db->table('cinema_movie_lang')->where('id_movie', $movie['id'])->where('id_lang', $id_lang)->set('views', 'views+1', false)->update();
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
            $dno = date('N', strtotime($date));
            if($dno < 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno + 2) . ' days'));
            elseif($dno > 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno - 5) . ' days'));
            else $date_from = $date;
            $date_to = date('Y-m-d', strtotime($date_from . ' + 7 days'));
            if(!empty($movie['video_url'])) {
                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $movie['video_url'], $matches);
                if(!empty($matches)) {
                    $movie['external_id'] = $matches[0];
                    $movie['video_source'] = 'youtube';
                } else {
                    preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $movie['video_url'], $matches);
                    if(!empty($matches) && !empty($matches[3])) {
                        $movie['external_id'] = $matches[3];
                        $movie['video_source'] = 'vimeo';
                    }
                    preg_match('!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!', $movie['video_url'], $matches);
                    if(!empty($matches)) {
                        if(isset($matches[6])) {
                            $movie['external_id'] = $matches[6];
                            $movie['video_source'] = 'dailymotion';
                        } elseif(isset($matches[4])) {
                            $movie['external_id'] = $matches[4];
                            $movie['video_source'] = 'dailymotion';
                        } elseif(isset($matches[2])) {
                            $movie['external_id'] = $matches[2];
                            $movie['video_source'] = 'dailymotion';
                        }
                    }
                }
            }
            $movie['premiere'] = $this->cinemaCalendarModel->db->table('cinema_announcement ca')
                    ->select('ca.date')
                    ->where('ca.date >=', $date_from)
                    //->where('ca.date <', $date_to)
                    ->where('ca.id_movie', $movie['id'])
                    ->orderBy('ca.date', 'ASC')
                    ->get()->getRowArray();
            $movie['genres'] = $this->cinemaCalendarModel->db->table('cinema_movie_genres cmg')
                    ->join('cinema_genre cg', 'cg.id=cmg.id_genre')
                    ->join('cinema_genre_lang cgl', 'cg.id=cgl.id_genre')
                    ->select('cgl.name')
                    ->where('cg.publish', 1)
                    ->where('cgl.id_lang', $id_lang)
                    ->where('cmg.id_movie', $movie['id'])
                    ->get()->getResultArray();
            $movie['photo'] = $this->cinemaCalendarModel->db
                    ->table('cinema_files cf')
                    ->join('cinema_files_lang cfl', 'cf.id=cfl.id_file')
                    ->select('cf.path,cf.mime,cfl.caption,cfl.author')
                    ->where('cf.id_cinema', $movie['id'])
                    ->where('cf.field', 'movie_photo')
                    ->where('cfl.id_lang', $id_lang)
                    ->where('cf.publish', 1)
                    ->get()
                    ->getRowArray();
            $movie['photos'] = $this->cinemaCalendarModel->db
                    ->table('cinema_files cf')
                    ->join('cinema_files_lang cfl', 'cf.id=cfl.id_file')
                    ->select('cf.path,cf.mime,cfl.caption,cfl.author')
                    ->where('cf.id_cinema', $movie['id'])
                    ->where('cf.field', 'movie_photos')
                    ->where('cfl.id_lang', $id_lang)
                    ->where('cf.publish', 1)
                    ->orderBy('cf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $movie['calendar'] = $this->cinemaCalendarModel->getMovieCalendarByID($movie['id'], $id_lang, $date);
            $movie['cinemas'] = $this->cinemaCalendarModel->getCinemasForCalendar($id_lang, $this->locale);
            $movie['dates'] = $this->cinemaCalendarModel->getCalendarDates($date, $link['link']);
            $movie['types'] = $this->cinemaCalendarModel->getMovieTypeForCalendar($id_lang);
        }
        return $movie;
    }

    public function getBreadcramb($object, $locale, $id_lang, $link) {
        if (!empty($object)) {
            return array(array(
                'id' => $object['id'],
                'name' => $object['title'],
                'link' => ($locale ? '/' . $locale : '') . '/' . $object['link'],
            ));
        }
        return array();
    }

    public function getMetaTags($id_movie, $id_lang, $link, $data=array()) {
        helper('text');
        $meta_tags = array();
        $meta = $this->cinemaMovieModel->db->table('cinema_movie cm')->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')->join('cinema_meta_lang cmel', 'cm.id=cmel.id_cinema AND slug="movie"', 'left')->select('cml.title,cml.introduction,cmel.title as meta_title,cmel.description as meta_desc,cmel.keywords as meta_keys')->where('cm.id', $id_movie)->where('cml.id_lang', $id_lang)->where('cmel.id_lang', $id_lang)->get()->getRowArray();
        if (!empty($meta)) {
            $meta['image'] = $this->cinemaMovieModel->db->table('cinema_files cf')->join('cinema_files_lang cfl', 'cf.id=cfl.id_file', 'left')->select('cf.path,cf.ext,cfl.caption')->where('cf.id_cinema', $id_movie)->where('cf.field', 'movie_poster')->where('cfl.id_lang', $id_lang)->get()->getRowArray();
            if (!empty($meta['meta_title'])) {
                $meta_tags['title'] = $meta['meta_title'];
                $meta_tags['image_alt'] = $meta['meta_title'];
            } elseif (!empty($meta['title'])) {
                $meta_tags['title'] = $meta['title'];
                $meta_tags['image_alt'] = $meta['title'];
            }
            if (!empty($meta['meta_desc'])) {
                $meta_tags['description'] = $meta['meta_desc'];
            } elseif (!empty($meta['introduction'])) {
                $meta_tags['description'] = character_limiter($meta['introduction'], 160);
            }
            if (!empty($meta['meta_keys'])) {
                $meta_tags['keywords'] = $meta['meta_keys'];
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
	
	public function assets($slug = '', $template = '', $id_news=0, $data=array()) {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'js_ready' => array()
        );
        $assets['css'][] = '/assets/css/slick.css';
        $assets['js'][] = '/assets/js/slick.min.js';
        $assets['js'][] = '/assets/js/video.js';
        return $assets;
    }
}