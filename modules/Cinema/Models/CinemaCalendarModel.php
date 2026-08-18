<?php

namespace Modules\Cinema\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class CinemaCalendarModel extends Model{

    protected $table = 'cinema_calendar';
    
    protected $allowedFields = [
        'id_movie',
        'id_place',
        'date',
        'premiere',
        'announcement',
        'pre-premiere',
        'special',
        'surprise',
        'edited_at',
        'created_at',
    ];
    
    public function saveCalendar($id, $post) 
    {
        $this->statistics = array(
            'added' => array(),
            'exists' => array(),
        );
        $this->db->transStart();
        if(!empty($post['date'])) {
            foreach($post['date'] as $date) {
                if(!empty($post['hour'])) {
                    foreach($post['hour'] as $hour) {
                        $h = (!empty($hour['h']) ? $hour['h'] : '00') . ':' . (!empty($hour['m']) ? $hour['m'] : '00');
                        $data = array(
                            'id_movie' => $post['id_movie'],
                            'id_place' => $post['id_place'],
                            'date' => !empty($date) ? date('Y-m-d H:i', strtotime($date . ' ' . $h)) : null,
                            'premiere' => !empty($post['premiere']) ? $post['premiere'] : 0,
                            'pre-premiere' => !empty($post['pre-premiere']) ? $post['pre-premiere'] : 0,
                            'special' => !empty($hour['special']) ? $hour['special'] : 0,
                            'surprise' => !empty($hour['surprise']) ? $hour['surprise'] : 0,
                        );
                        $exist = $this->db->table('cinema_calendar')->where($data)->get()->getRowArray();
                        if(!empty($exist)) {
                            if(!empty($post['type'])) {
                                foreach($post['type'] as $k=>$t) {
                                    if(empty($t)) {
                                        unset($post['type'][$k]);
                                    }
                                }
                                sort($post['type']);
                            } else {
                                $post['type'] = array();
                            }
                            $is = $this->db->table('cinema_calendar_types')->select('id_type')->where('id_calendar', $exist['id'])->orderBy('id_type', 'ASC')->get()->getResultArray();
                            $tmp = array();
                            if(!empty($is)) {
                                foreach($is as $i) {
                                    $tmp[] = $i['id_type'];
                                }
                            }
                            if($tmp != $post['type']) {
                                $exist = null;
                            }
                        }
                        if(empty($exist)) {
                            $result = $this->db->table('cinema_calendar')->insert($data);
                            $id = $this->db->insertID();
                            if($result && $id && !empty($post['type'])) {
                                foreach($post['type'] as $id_type) {
                                    $this->db->table('cinema_calendar_types')->insert(array('id_calendar' => $id, 'id_type' => $id_type));
                                }
                            }
                            $this->statistics['added'][] = $data;
                        } else {
                            $this->statistics['exists'][] = $data;
                        }
                    }
                }
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function saveSingleCalendar($post) {
        $this->statistics = array(
            'added' => array(),
            'exists' => array(),
        );
        $this->db->transStart();
        $data = array(
            'id_movie' => !empty($post['movie']) ? $post['movie'] : 0,
            'id_place' => !empty($post['place']) ? $post['place'] : 0,
            'date' => !empty($post['date']) ? date('Y-m-d H:i', strtotime($post['date'])) : null,
            'premiere' => !empty($post['premiere']) ? $post['premiere'] : 0,
            'pre-premiere' => !empty($post['pre_premiere']) ? $post['pre_premiere'] : 0,
            'special' => !empty($hour['special']) ? $hour['special'] : 0,
            'surprise' => !empty($hour['surprise']) ? $hour['surprise'] : 0,
        );
        $exist = $this->db->table('cinema_calendar')->where($data)->get()->getRowArray();
        if(!empty($exist)) {
            if(!empty($post['type'])) {
                foreach($post['type'] as $k=>$t) {
                    if(empty($t)) {
                        unset($post['type'][$k]);
                    }
                }
                sort($post['type']);
            } else {
                $post['type'] = array();
            }
            $is = $this->db->table('cinema_calendar_types')->select('id_type')->where('id_calendar', $exist['id'])->orderBy('id_type', 'ASC')->get()->getResultArray();
            $tmp = array();
            if(!empty($is)) {
                foreach($is as $i) {
                    $tmp[] = $i['id_type'];
                }
            }
            if($tmp != $post['type']) {
                $exist = null;
            }
        }
        if(empty($exist)) {
            $result = $this->db->table('cinema_calendar')->insert($data);
            $id = $this->db->insertID();
            if($result && $id && !empty($post['type'])) {
                foreach($post['type'] as $id_type) {
                    $this->db->table('cinema_calendar_types')->insert(array('id_calendar' => $id, 'id_type' => $id_type));
                }
            }
            $this->statistics['added'] = $data;
        } else {
            $this->statistics['exists'] = $data;
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getPlacesForList() 
    {
        $places = array();
        $list = $this->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->join('event_place_type ept', 'ept.id=ep.id_type')->select('ep.id,epl.name')->where('ep.publish', 1)->where('ept.cinema', 1)->orderBy('epl.name ASC')->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $places[$l['id']] = $l;
            }
        }
        return $places;
    }
    
    public function deleteCalendar($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function findMovieByTitle($title, $id_lang) 
    {
        //$title = '25.12 asd';
        $title = str_replace(array('/', '+', '-'), ' ', $title);
        $title = preg_replace('/[^A-Za-z0-9\-ĄąĆćĘęŁłŃńÓóŚsŻżŹź\.]/', ' ', $title);
        $title = trim(preg_replace('/\s+/', ' ', $title));
        $title = str_replace(array(' DUBBING', ' LEKTOR', ' 2D', ' 3D', ' PL', ' LEKTOR', ' NAPISY', ' 2D', ' ORG', ' PREMIERA', ' 2D/ORG', ' NAP', ' Nap', ' DUB', ' Dub', ' LEKTOR', 'Kino na Temat', 'Helios na scenie', 'Filmowe Poranki', 'Kino Konesera', 'Helios na scenie', 'DOLBY ATMOS', 'Kultura Dostępna', 'KNTJ', 'KNT', ' MARATON', ' SZKOLY', ' WYSTAWY', ' TEATR', ' OFF-OWE', ' AFM', ' SPORT', 'ATMOS', ' HDD', 'Kino Konesera', 'Kino na Temat', 'lektor', 'premierowe seanse z konkursami', 'premierowe seanse specjalne', 'Kino na obcasach', 'Akademia Filmowa 2D'), '', $title);
        $tmp_search = explode(" ", trim($title));
        if(!empty($title) && !empty($tmp_search)) {
            $search = array();
            /*
            if (!empty($tmp_search)) {
                foreach ($tmp_search as $s) {
                    if (strlen($s) > 2) {
                        $search[] = $s;
                    }
                }
            }
             */
            if (!empty($tmp_search)) {
                foreach ($tmp_search as $s) {
                    if (strlen($s) > 2) {
                        if(str_contains($s, '.')) {
                            $search[] = trim('"' . $s . '"');
                        } else {
                            $search[] = trim($s);
                        }
                    }
                }
            }
            //$math = 'MATCH (cml.title,cml.original) AGAINST (\'>"' . $title . '" ' . implode(' ', $search) . '\' IN BOOLEAN MODE)';
            $math = 'MATCH (cml.title,cml.original) AGAINST (\'' . implode(' ', $search) . '\' IN BOOLEAN MODE)';
            
            $movie = $this->db->table('cinema_movie cm')
                ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                ->select('cm.id,cml.title,cml.original,' . $math . ' as relevance')
                ->where('cm.publish', 1)
                ->where('cml.id_lang', $id_lang)
                ->groupStart()
                    ->where($math)
                    ->orLike('cml.title', $title)
                    ->orLike('cml.original', $title)
                ->groupEnd()
                ->orderBy('relevance DESC')
                ->orderBy('cml.title ASC')
                ->limit(1)
                ->get()
                ->getRowArray();
        }
        
        
        if(!empty($movie)) {
            return $movie;
        }
        return null;
    }
    
    public function getCalendarDates($date='', $link='', $movie=0, $place=0) {
        $dates = array();
        $today = date('Y-m-d');
        if(empty($date)) $date = $today;
        for($i=0;$i<14;$i++) {
            $time = strtotime($today . ' +' . $i . 'days');
	    
            $query = $this->db->table('cinema_calendar')->like('date', date('Y-m-d', $time), 'right');
            if(!empty($movie)) {
                $query->where('id_movie', $movie);
            }
            if(!empty($place)) {
                $query->where('id_place', $place);
            }
            $count = $query->countAllResults();
            $dates[$i] = array(
                'day_no' => date('N', $time),
                'day' => date('d.m', $time),
                'date' => date('Y-m-d', $time),
                'date_format' => date('d.m.Y', $time),
                'active' => $date == date('Y-m-d', $time),
                'link' => '/' . $link . (date('Y-m-d') != date('Y-m-d', $time) ? '/g/d/' . date('Y-m-d', $time) : ''),
                'count' => $count
            );
        }
        return $dates;
    }
    
    public function getCalendar($id_lang, $locale, $date='', $id_place=0) {
        if(empty($date)) $date = date('Y-m-d');
        $dno = date('N', strtotime($date));
        if($dno < 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno + 2) . ' days'));
        elseif($dno > 5) $date_from = date('Y-m-d', strtotime($date . ' -' . ($dno - 5) . ' days'));
        else $date_from = $date;
        
        $query = $this->db->table('cinema_movie cm')
                ->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')
                ->join('links l', 'l.id=cml.id_link')
                ->join('cinema_calendar cc', 'cc.id_movie=cm.id')
                ->join('cinema_files cf', 'cf.id_cinema=cm.id AND cf.field="movie_poster"', 'left')
                ->select('cm.id,cml.title,cm.for_kids,cml.country,cm.duration,cf.path,l.link')
                ->where('cml.id_lang', $id_lang);
        if(!empty($id_place)) {
            $query->where('cc.id_place', $id_place)
                ->groupBy('cc.id_place');
        }
        $movies = $query->like('cc.date', $date, 'right')
                ->groupBy('cm.id')
                ->orderBy('cml.title', 'ASC')
                ->get()->getResultArray();
        if($dno < 5) $d = $dno + 2;
        elseif($dno >= 5) $d = $dno - 5;
        if(!empty($movies)) {
            foreach($movies as $k=>$movie) {
                if(!empty($locale)) {
                    $movies[$k]['link'] = ($locale ? $locale . '/' : '') . $movie['link'];
                }
                $movies[$k]['genres'] = $this->db->table('cinema_movie_genres cmg')
                        ->join('cinema_genre cg', 'cg.id=cmg.id_genre')
                        ->join('cinema_genre_lang cgl', 'cgl.id_genre=cg.id')
                        ->where('cgl.id_lang', $id_lang)
                        ->where('cmg.id_movie', $movie['id'])
                        ->where('cg.publish', 1)
                        ->get()->getResultArray();
                $movies[$k]['calendar'] = $this->getMovieCalendarByID($movie['id'], $id_lang, $date, $id_place);
                $movies[$k]['premiere'] = false;
                $movies[$k]['prepremiere'] = false;
                $premiere = $this->db->table('cinema_announcement')->select('date')->where('id_movie', $movie['id'])->orderBy('date', 'ASC')->get()->getRowArray();
                if(!empty($premiere) && !empty($premiere['date'])) {
                    if(strtotime($premiere['date']) <= strtotime($date) && strtotime($premiere['date']) >= strtotime(date('Y-m-d', strtotime($date)) . ' -' . $d . ' days')) $movies[$k]['premiere'] = true;
                    elseif(strtotime($premiere['date']) > strtotime($date)) $movies[$k]['prepremiere'] = true;
                }
            }
        }
        return $movies;
    }
    
    public function getMovieCalendarByID($id_movie, $id_lang, $date='', $id_place=0) {
        $calendar = array();
        if (empty($date)) $data = date('Y-m-d');
        $query = $this->db->table('cinema_calendar cc')
                ->select('cc.id,cc.date,cc.id_place')
                ->where('cc.id_movie', $id_movie)
                ->like('cc.date', $date, 'right');
        if(!empty($id_place)) {
            $query->where('cc.id_place', $id_place);
        }
        $list = $query->orderBy('cc.date', 'ASC')
                ->get()->getResultArray();
        
        $now = time();
        if (!empty($list)) {
            foreach ($list as $l) {
                $types = $this->db->table('cinema_calendar_types cct')
                        ->join('cinema_type ct', 'ct.id=cct.id_type')
                        ->join('cinema_type_lang ctl', 'ct.id=ctl.id_type')
                        ->select('ct.id,ctl.name')
                        ->where('ctl.id_lang', $id_lang)
                        ->where('ct.publish', 1)
                        ->where('cct.id_calendar', $l['id'])
                        ->orderBy('ctl.name', 'ASC')
                        ->orderBy('ct.id', 'ASC')
                        ->get()->getResultArray();
                $d = strtotime($l['date']);
                $type_key = "0";
                if (!empty($types)) {
                    foreach ($types as $k => $t) {
                        if ($k == 0) {
                            $type_key = $t['id'];
                        } else {
                            $type_key .= '_' . $t['id'];
                        }
                    }
                }
                $calendar[$l['id_place']][$type_key][] = array(
                    'name' => date('H:i', $d),
                    'before' => $now > $d
                );
            }
        }
        return $calendar;
    }
    
    public function getMovieTypeForCalendar($id_lang) {
        $types = array();
	$list = $this->db->table('cinema_type ct')
                ->join('cinema_type_lang ctl', 'ct.id=ctl.id_type')
                ->join('cinema_calendar_types cct', 'ct.id=cct.id_type')
                ->select('ct.id,ctl.name')
                ->where('ct.publish', 1)
                ->where('ctl.id_lang', $id_lang)
                ->orderBy('ctl.name', 'ASC')
                ->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $types[$l['id']] = $l;
            }
        }
        return $types;
    }
    
    public function getCinemasForCalendar($id_lang, $locale) {
        $cinemas = array();
        $list = $this->db->table('event_place ep')
                ->join('event_place_lang epl', 'ep.id=epl.id_place')
                ->join('event_place_type ept', 'ept.id=ep.id_type')
                ->join('links l', 'l.id=epl.id_link')
                ->join('event_files ef', 'ef.id_event=ep.id AND ef.field="place_photo"', 'left')
                ->select('ep.id,epl.name,l.link,ef.path')
                ->where('epl.id_lang', $id_lang)
                ->where('ep.publish', 1)
                ->where('ept.cinema', 1)
                ->orderBy('epl.name', 'ASC')
                ->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                if(!empty($locale)) {
                    $l['link'] = ($locale ? $locale . '/' : '') . $l['link'];
                }
                $cinemas[$l['id']] = $l;
            }
        }
        return $cinemas;
    }
}