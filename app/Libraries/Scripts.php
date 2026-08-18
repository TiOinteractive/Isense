<?php

namespace App\Libraries;

use App\Libraries\Link;
use CodeIgniter\HTTP\Response as CI_Response;


class Scripts extends CI_Response
{
    
    public function __construct() 
    {
        helper(['form', 'url', 'file', 'filesystem']);
        $this->db = \Config\Database::connect();
        $this->linkClass = new Link();
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
    }
    
    public function initController() {
        
    }
    
    public function index($module, $action) {
        //exit();
        ini_set('max_execution_time', '600');
        helper(['text', 'filesystem']);
        if (method_exists($this, $module)) {
            $r = $this->$module($action);
            //var_dump('RESULT: ' . ($r ? '1' : '0'));
            return $r;
        }
    }
    
    private function export($action) {
        switch($action) {
            case 'sponsored-news':
                $news = $this->db->table('news n')
                    ->join('news_lang nl', 'n.id=nl.id_news')
                    ->join('links l', 'l.id=nl.id_link')
                    ->join('news_meta_lang nml', 'n.id=nml.id_news', 'left')
                    ->select('n.id,n.date,n.publish,nl.title,l.link,nml.keywords')
                    ->where('nl.id_lang', 1)
                    ->where('nml.id_lang', 1)
                    ->where('n.date <=', '2021-12-31')
                    ->where('n.id_page_cont', 13)
                    ->where('n.publish', 1)
                    ->get()->getResultArray();
                if(!empty($news)) {
                    foreach($news as $n) {
                        if(str_contains($n['keywords'], 'TM') || str_contains($n['keywords'], 'WP') || str_contains($n['keywords'], 'LH') || str_contains($n['keywords'], 'BC') || str_contains($n['keywords'], 'SC') || str_contains($n['keywords'], 'KW') || str_contains($n['keywords'], 'BS') || str_contains($n['keywords'], 'TP') || str_contains($n['keywords'], 'Wp') || str_contains($n['keywords'], 'wp')) {
                            if(!str_contains($n['keywords'], 'BSeo')) {
                                //$this->db->table('news')->set('publish', 0)->where('id', $n['id'])->update();
                            }
                        }
                    }
                    /*ob_start();
                    $df = fopen("php://output", 'w');
                    fputcsv($df, array_keys(reset($news)));
                    foreach ($news as $row) {
                        $row['link'] = base_url() . $row['link'];
                       fputcsv($df, $row);
                    }
                    fclose($df);
                    $csv = ob_get_clean();
                    //$this->response->setStatusCode(200)->setContentType('')->setBody($csv)->send();
                    return $this->response->setContentType('text/csv', 'UTF-8')->download('news.csv', $csv);*/
                }
                break;
        }
    }
    
    private function newsletter($action) {
        $this->db_newsletter = \Config\Database::connect('newsletter');
        switch($action) {
            case 'emails':
                $this->db->transStart();
                $group_id = 8;
                $new_group_id = 2;
                $emails = $this->db_newsletter->table('adresy_mail am')->select('am.mail,am.id_grupa,am.imie,am.nazwisko,am.token,am.token_expire,am.data,am.id_status,am.source,am.data_zmiany,am.data_statusu')->where('am.id_grupa', $group_id)->groupStart()->where('am.id_status', 1)->orWhere('am.data >=', date('Y-m-d', strtotime("-1 month")))->orWhere('am.source!=', null)->groupEnd()->orderBy('am.id')->get()->getResultArray();
                if(!empty($emails)) {
                    foreach($emails as $email) {
                        if( strpos(file_get_contents(WRITEPATH . 'newsletter/list_selected.txt'), $email['mail']) !== false) {
                        //if(exec('grep ' . escapeshellarg($email['mail']) . ' .' . WRITEPATH . 'newsletter/list_all.txt')) {
                            
                        } else {
                            $is = $this->db->table('newsletter_email')->select('id')->where('email', trim($email['mail']))->where('id_group', $new_group_id)->get()->getRowArray();
                            $data = array(
                                'id_group' => $new_group_id,
                                'email' => trim($email['mail']),
                                'name' => trim($email['imie']),
                                'surname' => trim($email['nazwisko']),
                                'agreement' => 1,
                                'hash_valid' => null,
                                'active' => $email['id_status'] == 1 ? 1 : 0,
                                'source' => !empty($email['source']) ? $email['source'] : null,
                                'edited_at' => date('Y-m-d H:i:s', strtotime($email['data_zmiany'])),
                                'created_at' => date('Y-m-d H:i:s', strtotime($email['data'])),
                            );
                            if(!empty($is)) {
                                $id = $is['id'];
                                $result = $this->db->table('newsletter_email')->where('id', $id)->update($data);
                            } else {
                                $hash = random_string('sha1');
                                $count = 1;
                                do {
                                    $is = $this->db->table('newsletter_email')->select('id')->where('hash', $hash)->get()->getRowArray();
                                    if(!empty($is)) {
                                        $hash = random_string('sha1');
                                    }
                                    ++$count;
                                } while(!empty($is) && $count<=1000);
                                $data['hash'] = $hash;
                                $result = $this->db->table('newsletter_email')->insert($data);
                                $id = $this->db->insertID();
                            }
                        }
                    }
                }
                $this->db->transComplete();
                return $this->db->transStatus();
                break;
        }
    }
    
    private function main($action) {
        switch($action) {
            case 'event-default':
                $calendar = $this->db->table('event_calendar')->select('IF(date_end IS NULL, 0, 1) as period,event_calendar.*')->orderBy('period', 'ASC')->groupBy('id_event')->get()->getResultArray();
                if(!empty($calendar)) {
                    foreach($calendar as $c) {
                        //$this->db->table('event_calendar')->set('default', 1)->where('id', $c['id'])->update();
                    }
                }
                break;
        }
    }
    
    private function links($action) {
        switch($action) {
            case 'empty-links':
                $list = $this->db->table('event_lang el')
                    ->select('l.id,el.id_event,el.id_link')
                    ->join('links l', 'l.id=el.id_link', 'left')
                    //->where('l.id', null)
                    ->get()->getResultArray();
                if(!empty($list)) {
                    foreach($list as $l) {
                        if(empty($l['id'])) {
                            var_dump($l);
                            echo '<br />';
                        }
                    }
                }
                
                break;
            case 'repair-events':
                $list = $this->db->table('event_lang')->select('id_event,id_link,COUNT(id) as count')->groupBy('id_link')->having('count >=', 2)->get()->getResultArray();
                if(!empty($list)) {
                    foreach($list as $l) {
                        $events = $this->db->table('event_lang')->select('id,id_event,name')->where('id_link', $l['id_link'])->orderBy('id_event', 'ASC')->get()->getResultArray();
                        if(!empty($events)) {
                            foreach($events as $k=>$event) {
                                if($k == 0) {
                                    
                                } else {
                                    $link = $this->linkClass->generateLink(!empty($event['name']) ? $event['name'] : '', 1, 0, 6, 'event', '');
                                    //$id_link = $this->linkClass->saveLink($link, 1, 0, 13);
                                    //$result = $this->db->table('event_lang')->set('id_link', $id_link)->where('id', $event['id'])->update();
                                    var_dump($link);
                                    echo '<br />';
                                }
                            }
                        }
                    }
                }
                break;
        }
    }
    
    private function entertainment($action) {
        $this->db_entertainment = \Config\Database::connect('entertainment');
        switch($action) {
            case 'places-links':
                /*
                $places = $this->db_entertainment->table('miejsca m')->join('miejsca_info_pl mi', 'm.id=mi.id_miejsce')->join('linki l', 'l.id=mi.id_link')->select('m.id,l.link')->orderBy('m.id', 'ASC')->get()->getResultArray();
                if(!empty($places)) {
                    foreach($places as $place) {
                        $is = $this->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->join('links l', 'epl.id_link=l.id')->select('ep.id,l.link')->where('epl.id_lang', 1)->where('ep.id_old', $place['id'])->get()->getRowArray();
                        if(!empty($is)) {
                            var_dump($place['link']);
                            echo '<br />';
                            var_dump($is['link']);
                            echo '<br />';
                            echo '<br />';
                            $data = array(
                                'from' => '/' . $place['link'],
                                'to' => '/' . $is['link'],
                                'type' => '301',
                                'publish' => 0,
                                'short' => 0,
                                'group' => 'entertainment'
                            );
                            $result = $this->db->table('redirects')->insert($data);
                        }
                    }
                }
                */
                
                break;
            case 'news':
                $this->db->transStart();
                /* usunięcie istniejących pozycji */
                /*$news = $this->db->table('news')->select('id')->where('id_page_cont', 3)->get()->getResultArray();
                if(!empty($news)) {
                    foreach($news as $n) {
                        $lang = $this->db->table('news_lang')->select('id_link')->where('id_news', $n['id'])->get()->getResultArray();
                        if(!empty($lang)) {
                            foreach($lang as $l) {
                                $this->db->table('links')->where('id', $l['id_link'])->delete();
                            }
                        }
                        $this->db->table('news_lang')->where('id_news', $n['id'])->delete();
                        
                        $files = $this->db->table('news_files')->select('id,path')->where('id_news', $n['id'])->get()->getResultArray();
                        if(!empty($files)) {
                            foreach($files as $f) {
                                $this->db->table('news_files_lang')->where('id_file', $f['id'])->delete();
                                if(file_exists(WRITEPATH . 'uploads/' . $f['path'])) {
                                    @unlink(WRITEPATH . 'uploads/' . $f['path']);
                                }
                            }
                        }
                        $this->db->table('news_files')->where('id_news', $n['id'])->delete();
                        
                    }
                    $this->db->table('news')->where('id_page_cont', 3)->delete();
                }*/
                
                /* dodanie nowych pozycji */
                $news = $this->db_entertainment->table('news')->select('*')->orderBy('data_dodania', 'ASC')->where('data_dodania >=', '2024-04-25')->get()->getResultArray();
                if(!empty($news)) {
                    foreach($news as $j=>$n) {
                        $is = $this->db->table('news')->select('id')->where('id_old', $n['id'])->where('id_page_cont', 3)->get()->getRowArray();
                        $data = array(
                            'id_page_cont' => 3,
                            'order' => $n['pozycja'],
                            'publish' => !empty($n['publikacja']) && $n['publikacja'] == 'tak' ? 1 : 0,
                            'edited_at' => date('Y-m-d H:i:s', strtotime($n['data_dodania'])),
                            'created_at' => date('Y-m-d H:i:s', strtotime($n['data_edycji'])),
                            'template' => 'small_full.php',
                            'home' => !empty($n['home']) && $n['home'] == 'tak' ? 1 : 0,
                            'date' => date('Y-m-d H:i:s', strtotime($n['data_dodania'])),
                            'comment' => !empty($n['komentarze']) && $n['komentarze'] == 'tak' ? 1 : 0,
                            'publish_date' =>  NULL,
                            'dont_miss' => 0,
                            'investments' => 0,
                            'patronate' => !empty($n['patronat']) && $n['patronat'] == 'tak' ? 1 : 0,
                            'show_in_box' => !empty($n['big_box']) && $n['big_box'] == 'tak' ? 1 : 0,
                            'newsletter' => !empty($n['newsletter']) && $n['newsletter'] == 'tak' ? 1 : 0,
                            'id_old' => $n['id'],
                        );
                        if(!empty($is)) {
                            $id = $is['id'];
                            $result = $this->db->table('news')->where('id', $id)->update($data);
                        } else {
                            $result = $this->db->table('news')->insert($data);
                            $id = $this->db->insertID();
                        }
                        
                        if($result && $id) {
                            $lang = $this->db_entertainment->table('news_info_pl ni')->join('linki l', 'l.id=ni.id_link', 'left')->select('ni.*,l.link')->where('ni.id_news', $n['id'])->get()->getRowArray();
                            if(!empty($lang)) {
                                $is_lang = $this->db->table('news_lang')->select('id,id_lang,id_link')->where('id_news', $id)->where('id_lang', 1)->get()->getRowArray();
                                //$link = $this->linkClass->generateLink(!empty($lang['temat']) ? $lang['temat'] : '', 1, !empty($is_lang) && !empty($is_lang['id_link']) ? $is_lang['id_link'] : 0, 3, 'page', '');
                                $link = 'rozrywka/' . $lang['link'];
                                preg_match_all('/<img[^>]+>/i', $lang['tresc'], $img_tags); 
                                if(!empty($img_tags) && !empty($img_tags[0])) {
                                    $lang['tresc'] = preg_replace('/(<img[^>]+) style=".*?"/i', '$1', $lang['tresc']);
                                    foreach($img_tags[0] as $img_tag) {
                                        preg_match( '@src="([^"]+)"@' , $img_tag, $img_attr );
                                        if(!empty($img_attr) && !empty($img_attr[1])) {
                                            $new_path = 'files/' . substr($img_attr[1], 19);
                                            $lang['tresc'] = str_replace('src="' . $img_attr[1] . '"', 'src="/' . $new_path . '"' , $lang['tresc']);
                                            $dir_path = '';
                                            $tmp = explode('/', $new_path);
                                            if(!empty($tmp)) {
                                                unset($tmp[count($tmp) - 1]);
                                                foreach($tmp as $t) {
                                                    $dir_path .= ($dir_path ? '/' : '') . $t;
                                                    if(!is_dir(ROOTPATH . 'public/' . $dir_path)) {
                                                        mkdir(ROOTPATH . 'public/' . $dir_path);
                                                    }
                                                }
                                            }
                                            copy('https://www.rozrywka.resinet.pl' . $img_attr[1], ROOTPATH . 'public/' . $new_path);
                                        }
                                    }
                                }
                                $data = array(
                                    'id_news' => $id,
                                    'id_lang' => 1,
                                    'id_link' => $this->linkClass->saveLink($link, 1,  !empty($is_lang) && !empty($is_lang['id_link']) ? $is_lang['id_link'] : 0, 2, false, null, null),
                                    'title' => $lang['temat'],
                                    'subtitle' => $lang['big_box'],
                                    'header' => '',
                                    'introduction' => $lang['wstep'],
                                    'content' => $lang['tresc'],
                                    'author' => $lang['autor'],
                                    'source' => '',
                                    'views' => $lang['ilosc_wyswietlen'],
                                    'tags' => '',
                                    'edited_at' => date('Y-m-d H:i:s', strtotime($n['data_edycji'])),
                                    'created_at' => date('Y-m-d H:i:s', strtotime($n['data_dodania'])),
                                );
                                if($is_lang) {
                                    $result = $this->db->table('news_lang')->where('id', $is_lang['id'])->update($data);
                                } else {
                                    $result = $this->db->table('news_lang')->insert($data);
                                }
                                
                                $is_meta = $this->db->table('news_meta_lang')->select('id')->where('id_news', $id)->where('id_lang', 1)->get()->getRowArray();
                                $desc = $lang['wstep'];
                                if(!$desc) $desc = $lang['tresc'];
                                $desc = substr(preg_replace('/\s\s+/', ' ', htmlspecialchars(strip_tags($desc), ENT_QUOTES, 'UTF-8')), 0, 300);
                                $desc = substr($desc, 0, strrpos($desc, ' ')); 
                                $meta = array(
                                    'id_news' => $id,
                                    'id_lang' => 1,
                                    'title' => $lang['temat'] . ' | Rzeszów | Serwis Rozrywkowy RESINET.PL',
                                    'description' => $desc,
                                    'keywords' => '',
                                );
                                if(!empty($is_meta)) {
                                    $result = $this->db->table('news_meta_lang')->where('id', $is_meta['id'])->update($meta);
                                } else {
                                    $result = $this->db->table('news_meta_lang')->insert($meta);
                                }
                                
                                $lang['tagi'] = trim($lang['tagi'], ',');
                                if(!empty($lang['tagi'])) {
                                    $tags = $this->db_entertainment->table('tagi')->select('*')->whereIn('id', explode(',', $lang['tagi']))->get()->getResultArray();
                                    if(!empty($tags)) {
                                        $id_tags = array();
                                        foreach($tags as $t) {
                                            if(!empty($t['tag'])) {
                                                $is_tag = $this->db->table('tags')->select('*')->where('id_lang', 1)->where('tag', $t['tag'])->get()->getRowArray();
                                                if(!empty($is_tag)) {
                                                    $id_tags[] = $is_tag['id'];
                                                } else {
                                                    $this->db->table('tags')->insert(array(
                                                        'id_page_cont' => 0,
                                                        'tag' => $t['tag'],
                                                        'id_lang' => 1,
                                                        'date' => date('Y-m-d H:i:s', strtotime($t['data_dodania']))
                                                    ));
                                                    $id_tags[] = $this->db->insertID();
                                                }
                                            }
                                        }
                                        if(!empty($id_tags)) {
                                            $this->db->table('news_lang')->set('tags', ',' . implode(',', $id_tags) . ',')->where('id_news', $id)->where('id_lang', 1)->update();
                                        }
                                    }
                                }
                            }
                        }
                        $n['zdjecie_glowne'] = trim($n['zdjecie_glowne'], ',');
                        if(!empty($n['zdjecie_glowne'])) {
                            $photo = $this->db_entertainment->table('news_pliki')->select('*')->where('id', $n['zdjecie_glowne'])->get()->getRowArray();
                            if(!empty($photo)) {
                                $this->saveFileResinet($id, 'news_files', 'id_news', 'news', date('Ymd', strtotime($n['data_dodania'])), 'photo', 'https://www.rozrywka.resinet.pl/pliki/zdjecia/aktualnosci', $photo);
                                //$this->saveFile($id, 'news_files', 'id_news', 'news', date('Ymd', strtotime($n['data_dodania'])), 'photo', '/home/www/rozrywka.resinet/html/pliki/zdjecia/aktualnosci', $photo);
                            }
                        }
                        $n['zdjecia'] = trim($n['zdjecia'], ',');
                        if(!empty($n['zdjecia'])) {
                            $photos = $this->db_entertainment->table('news_pliki')->select('*')->whereIn('id', explode(',', $n['zdjecia']))->get()->getResultArray();
                            if(!empty($photos)) {
                                foreach($photos as $photo) {
                                    $this->saveFileResinet($id, 'news_files', 'id_news', 'news', date('Ymd', strtotime($n['data_dodania'])), 'photos', 'https://www.rozrywka.resinet.pl/pliki/zdjecia/aktualnosci', $photo);
                                    //$this->saveFile($id, 'news_files', 'id_news', 'news', date('Ymd', strtotime($n['data_dodania'])), 'photos', '/home/www/rozrywka.resinet/html/pliki/zdjecia/aktualnosci', $photo);
                                }
                            }
                        }
                    }
                }
                $this->db->transComplete();
                return $this->db->transStatus();
                break;
            case 'places':
                $ids_array = array(
                    6 => 16,     //Biblioteka
                    7 => 16,     //Dom kultury
                    9 => 13,     //Klub
                    11 => 18,   //Plener
                    12 => 13,    //Pub
                    13 => 18,    //Inne
                    14 => 15,    //Teatr
                    27 => 14,    //Galeria
                    28 => 14,    //Muzeum
                    29 => 16,   //Filharmonia
                    30 => 17     //Kino
                );
                $this->db->transStart();
                /* usunięcie istniejących pozycji */
                /*$places = $this->db->table('event_place')->select('id')->where('id_type !=', 4)->get()->getResultArray();
                if(!empty($places)) {
                    foreach($places as $p) {
                        $lang = $this->db->table('event_place_lang')->select('id_link')->where('id_place', $p['id'])->get()->getResultArray();
                        if(!empty($lang)) {
                            foreach($lang as $l) {
                                $r = $this->db->table('links')->where('id', $l['id_link'])->delete();
                            }
                        }
                        $r = $this->db->table('event_place_lang')->where('id_place', $p['id'])->delete();
                        
                        $files = $this->db->table('event_files')->select('id,path')->where('id_event', $p['id'])->whereIn('field', array('place_photo', 'place_photos'))->get()->getResultArray();
                        if(!empty($files)) {
                            foreach($files as $f) {
                                $r = $this->db->table('event_files_lang')->where('id_file', $f['id'])->delete();
                                if(file_exists(WRITEPATH . 'uploads/' . $f['path'])) {
                                    @unlink(WRITEPATH . 'uploads/' . $f['path']);
                                }
                            }
                        }
                        $r = $this->db->table('event_files')->where('id_event', $p['id'])->whereIn('field', array('place_photo', 'place_photos'))->delete();
                        
                    }
                    $r = $this->db->table('event_place')->where('id_type !=', 4)->delete();
                }*/
                /* dodanie nowych pozycji */
                $places = $this->db_entertainment->table('miejsca')->select('*')->orderBy('data_dodania', 'DESC')->limit(100)->get()->getResultArray();
                if(!empty($places)) {
                    foreach($places as $j=>$p) {
                        $is = $this->db->table('event_place')->select('id')->where('id_old', $p['id'])->get()->getRowArray();
                        $data = array(
                            'id_page_cont' => 5,
                            'www' => '',
                            'email' => '',
                            'phone' => '',
                            'id_type' => !empty($ids_array[$p['id_rodzaj']]) ? $ids_array[$p['id_rodzaj']] : 0,
                            'template' => 'tpl.php',
                            'home' => 0,
                            'publish' => !empty($p['publikacja']) && $p['publikacja'] == 'tak' ? 1 : 0,
                            'edited_at' => date('Y-m-d H:i:s', strtotime($p['data_edycji'])),
                            'created_at' => date('Y-m-d H:i:s', strtotime($p['data_dodania'])),
                            'comment' => !empty($p['komentarze']) && $p['komentarze'] == 'tak' ? 1 : 0,
                            'id_old' => $p['id']
                        );
                        if(!empty($is)) {
                            $id = $is['id'];
                            $result = $this->db->table('event_place')->where('id', $id)->update($data);
                        } else {
                            $result = $this->db->table('event_place')->insert($data);
                            $id = $this->db->insertID();
                        }
                        
                        if($result && $id) {
                            $lang = $this->db_entertainment->table('miejsca_info_pl')->select('*')->where('id_miejsce', $p['id'])->get()->getRowArray();
                            if(!empty($lang)) {
                                $is_lang = $this->db->table('event_place_lang')->select('id,id_lang,id_link')->where('id_place', $id)->where('id_lang', 1)->get()->getRowArray();
                                $link = $this->linkClass->generateLink(!empty($lang['nazwa']) ? $lang['nazwa'] : '', 1, !empty($is_lang) && !empty($is_lang['id_link']) ? $is_lang['id_link'] : 0, 5, 'event_place', '');
                                $data = array(
                                    'id_place' => $id,
                                    'id_lang' => 1,
                                    'id_link' => $this->linkClass->saveLink($link, 1, !empty($is_lang) && !empty($is_lang['id_link']) ? $is_lang['id_link'] : 0, 13, false, null, 'place'),
                                    'name' => $lang['nazwa'],
                                    'content' => $lang['opis'],
                                    'address' => $lang['adres'],
                                    'repertoire' => $lang['repertuar'],
                                    'views' => $lang['ilosc_wyswietlen'],
                                    'edited_at' => date('Y-m-d H:i:s', strtotime($p['data_edycji'])),
                                    'created_at' => date('Y-m-d H:i:s', strtotime($p['data_edycji'])),
                                );
                                if($is_lang) {
                                    $result = $this->db->table('event_place_lang')->where('id', $is_lang['id'])->update($data);
                                } else {
                                    $result = $this->db->table('event_place_lang')->insert($data);
                                }
                                $this->db->table('event_place')->where('id', $id)->update(array(
                                    'www' => $lang['www'],
                                    'email' => $lang['mail'],
                                ));
                            
                                $is_meta = $this->db->table('event_meta_lang')->select('id')->where('id_event', $id)->where('slug', 'place')->where('id_lang', 1)->get()->getRowArray();
                                $desc = $lang['opis'];
                                $desc = substr(preg_replace('/\s\s+/', ' ', htmlspecialchars(strip_tags($desc), ENT_QUOTES, 'UTF-8')), 0, 300);
                                $desc = substr($desc, 0, strrpos($desc, ' ')); 
                                $meta = array(
                                    'id_event' => $id,
                                    'id_lang' => 1,
                                    'title' => $lang['nazwa'] . ' | Kluby, puby, lokale, knajpy w Rzeszowie | Serwis Rozrywkowy RESinet.pl',
                                    'description' => $desc,
                                    'keywords' => '',
                                    'slug' => 'place',
                                );
                                if(!empty($is_meta)) {
                                    $result = $this->db->table('event_meta_lang')->where('id', $is_meta['id'])->update($meta);
                                } else {
                                    $result = $this->db->table('event_meta_lang')->insert($meta);
                                }
                            }
                        }
                        
                        $p['zdjecie_glowne'] = trim($p['zdjecie_glowne'], ',');
                        if(!empty($p['zdjecie_glowne'])) {
                            $photo = $this->db_entertainment->table('miejsca_pliki')->select('*')->where('id', $p['zdjecie_glowne'])->get()->getRowArray();
                            if(!empty($photo)) {
                                $this->saveFileResinet($id, 'event_files', 'id_event', 'event', date('Ymd', strtotime($p['data_edycji'])), 'place_photo', 'https://www.rozrywka.resinet.pl/pliki/zdjecia/miejsca', $photo);
                                //$this->saveFile($id, 'event_files', 'id_event', 'event', date('Ymd', strtotime($p['data_dodania'])), 'place_photo', '/home/www/rozrywka.resinet/html/pliki/zdjecia/miejsca', $photo);
                            }
                        }
                        
                        $p['zdjecia'] = trim($p['zdjecia'], ',');
                        if(!empty($p['zdjecia'])) {
                            $photos = $this->db_entertainment->table('miejsca_pliki')->select('*')->whereIn('id', explode(',', $p['zdjecia']))->get()->getResultArray();
                            if(!empty($photos)) {
                                foreach($photos as $photo) {
                                    $this->saveFileResinet($id, 'event_files', 'id_event', 'event', date('Ymd', strtotime($p['data_edycji'])), 'place_photos', 'https://www.rozrywka.resinet.pl/pliki/zdjecia/miejsca', $photo);
                                    //$this->saveFile($id, 'event_files', 'id_event', 'event', date('Ymd', strtotime($p['data_dodania'])), 'place_photos', '/home/www/rozrywka.resinet/html/pliki/zdjecia/miejsca', $photo);
                                }
                            }
                        }
                    }
                }
                $this->db->transComplete();
                return $this->db->transStatus();
                break;
            case 'event_types':
                $this->db->transStart();
                /* usunięcie istniejących pozycji */
                /*$event_types = $this->db->table('event_type')->select('id')->get()->getResultArray();
                if(!empty($event_types)) {
                    foreach($event_types as $et) {
                        $lang = $this->db->table('event_type_lang')->select('id_link')->where('id_type', $et['id'])->get()->getResultArray();
                        if(!empty($lang)) {
                            foreach($lang as $l) {
                                $r = $this->db->table('links')->where('id', $l['id_link'])->delete();
                            }
                        }
                        $r = $this->db->table('event_type_lang')->where('id_type', $et['id'])->delete();
                    }
                    $r = $this->db->table('event_type')->where('id>', 0)->delete();
                }*/
                
                /* dodanie nowych pozycji */
                $event_types = $this->db_entertainment->table('kalendarium_rodzaj')->select('*')->orderBy('id', 'ASC')->get()->getResultArray();
                if(!empty($event_types)) {
                    foreach($event_types as $j=>$et) {
                        $is = $this->db->table('event_type')->select('id')->where('id_old', $p['id'])->get()->getRowArray();
                        $data = array(
                            'publish' => 1,
                            'edited_at' => null,
                            'created_at' => date('Y-m-d H:i:s'),
                            'id_old' => $et['id'],
                            'slug_old' => $et['skrot']
                        );
                        if(!empty($is)) {
                            $id = $is['id'];
                            $result = $this->db->table('event_type')->where('id', $id)->update($data);
                        } else {
                            $result = $this->db->table('event_type')->insert($data);
                            $id = $this->db->insertID();
                        }
                        if($result && $id) {
                            $is_lang = $this->db->table('event_type_lang')->select('id,id_lang,id_link')->where('id_type', $id)->where('id_lang', 1)->get()->getRowArray();
                            $slug = mb_url_title(str_replace(array('/', ','), '-', $et['nazwa']), '-', true);
                            $oryginal_slug = $slug;
                            $count = 1;
                            do {
                                $query = $this->db->table('event_type_lang')->select('id')->where('slug', $slug)->where('id_lang', $id_lang);
                                if(!empty($is_lang)) {
                                    $query->where('id !=', $is_lang['id']);
                                }
                                $is = $query->get()->getRowArray();
                                if(!empty($is)) {
                                    $slug = $oryginal_slug . '-' . $count;
                                }
                                ++$count;
                            } while(!empty($is) && $count<=1000);
                            $data = array(
                                'id_type' => $id,
                                'id_lang' => 1,
                                'id_link' => 0,
                                'name' => $et['nazwa'],
                                'slug' => $slug,
                                'content' => '',
                                'edited_at' => null,
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                            if($is_lang) {
                                $result = $this->db->table('event_type_lang')->where('id', $is_lang['id'])->update($data);
                            } else {
                                $result = $this->db->table('event_type_lang')->insert($data);
                            }
                            
                        }
                    }
                }
                $this->db->transComplete();
                return $this->db->transStatus();
                break;
            case 'events':
                $this->db->transStart();
                /* usunięcie istniejących pozycji */
                /*$events = $this->db->table('event')->select('id')->get()->getResultArray();
                if(!empty($events)) {
                    foreach($events as $e) {
                        $lang = $this->db->table('event_lang')->select('id_link')->where('id_event', $e['id'])->get()->getResultArray();
                        if(!empty($lang)) {
                            foreach($lang as $l) {
                                $r = $this->db->table('links')->where('id', $l['id_link'])->delete();
                            }
                        }
                        $r = $this->db->table('event_lang')->where('id_event', $e['id'])->delete();
                        
                        $files = $this->db->table('event_files')->select('id,path')->where('id_event', $e['id'])->whereIn('field', array('photo', 'photos'))->get()->getResultArray();
                        if(!empty($files)) {
                            foreach($files as $f) {
                                $r = $this->db->table('event_files_lang')->where('id_file', $f['id'])->delete();
                                if(file_exists(WRITEPATH . 'uploads/' . $f['path'])) {
                                    @unlink(WRITEPATH . 'uploads/' . $f['path']);
                                }
                            }
                        }
                        $r = $this->db->table('event_files')->where('id_event', $e['id'])->whereIn('field', array('photo', 'photos'))->delete();
                        
                    }
                    $r = $this->db->table('event')->where('id>', 0)->delete();
                }*/
                /* dodanie nowych pozycji */
                $date_from = '2023-01-01';
                //$events1 = $this->db_entertainment->table('kalendarium')->select('*')->where('data_od >=', $date_from)->orWhere('data_do >=', $date_from)->get()->getResultArray();
                //$events2 = $this->db_entertainment->table('kalendarium_repertuar kr')->join('kalendarium_info_pl ki', 'ki.id=kr.id_impreza')->join('kalendarium k', 'k.id=ki.id_kalendarium')->select('k.*')->where('kr.data_od >=', $date_from)->groupBy('k.id')->get()->getResultArray();
                //$new_events = array_merge($events1, $events2);
                $new_events = $this->db_entertainment->table('kalendarium')->select('*')->where('id >=', 9027)->orderBy('id', 'ASC')->get()->getResultArray();
                if(!empty($new_events)) {
                    foreach($new_events as $j=>$e) {
                        $is = $this->db->table('event')->select('id')->where('id_old', $e['id'])->get()->getRowArray();
                        $type = $this->db->table('event_type')->select('id')->where('slug_old', $e['rodzaj'])->get()->getRowArray();
                        $data = array(
                            'id_page_cont' => 36,
                            'id_type' => !empty($type['id']) ? $type['id'] : 0,
                            'publish' => !empty($e['publikacja']) && $e['publikacja'] == 'tak' ? 1 : 0,
                            'home' => !empty($e['home']) && $e['home'] == 'tak' ? 1 : 0,
                            'patronage' => !empty($e['patronat']) && $e['patronat'] == 'tak' ? 1 : 0,
                            'for_kids' => !empty($e['dla_dzieci']) && $e['dla_dzieci'] == 'tak' ? 1 : 0,
                            'recommended' => !empty($e['polecamy']) && $e['polecamy'] == 'tak' ? 1 : 0,
                            'template' => 'tpl.php',
                            'edited_at' => date('Y-m-d H:i:s', strtotime($e['data_dodania'])),
                            'created_at' => date('Y-m-d H:i:s', strtotime($e['data_edycji'])),
                            'comment' => !empty($e['komentarze']) && $e['komentarze'] == 'tak' ? 1 : 0,
                            'id_old' => $e['id']
                        );
                        if(!empty($is)) {
                            $id = $is['id'];
                            $result = $this->db->table('event')->where('id', $id)->update($data);
                        } else {
                            $result = $this->db->table('event')->insert($data);
                            $id = $this->db->insertID();
                        }
                        if($result && $id) {
                            $lang = $this->db_entertainment->table('kalendarium_info_pl ki')->join('linki l', 'l.id=ki.id_link', 'left')->select('ki.*,l.link')->where('ki.id_kalendarium', $e['id'])->get()->getRowArray();
                            if(!empty($lang)) {
                                $is_lang = $this->db->table('event_lang')->select('id,id_lang,id_link')->where('id_event', $id)->where('id_lang', 1)->get()->getRowArray();
                                //$link = $this->linkClass->generateLink(!empty($lang['nazwa']) ? $lang['nazwa'] : $e['rodzaj'], 1, !empty($is_lang) && !empty($is_lang['id_link']) ? $is_lang['id_link'] : 0, 6, 'event', '');
                                $link = 'rozrywka/' . $lang['link'];
                                preg_match_all('/<img[^>]+>/i', $lang['tresc'], $img_tags); 
                                if(!empty($img_tags) && !empty($img_tags[0])) {
                                    $lang['tresc'] = preg_replace('/(<img[^>]+) style=".*?"/i', '$1', $lang['tresc']);
                                    foreach($img_tags[0] as $img_tag) {
                                        preg_match( '@src="([^"]+)"@' , $img_tag, $img_attr );
                                        if(!empty($img_attr) && !empty($img_attr[1])) {
                                            $new_path = 'files/' . substr($img_attr[1], 19);
                                            $lang['tresc'] = str_replace('src="' . $img_attr[1] . '"', 'src="/' . $new_path . '"' , $lang['tresc']);
                                            $dir_path = '';
                                            $tmp = explode('/', $new_path);
                                            if(!empty($tmp)) {
                                                unset($tmp[count($tmp) - 1]);
                                                foreach($tmp as $t) {
                                                    $dir_path .= ($dir_path ? '/' : '') . $t;
                                                    if(!is_dir(ROOTPATH . 'public/' . $dir_path)) {
                                                        mkdir(ROOTPATH . 'public/' . $dir_path);
                                                    }
                                                }
                                            }
                                            copy('https://www.rozrywka.resinet.pl' . $img_attr[1], ROOTPATH . 'public/' . $new_path);
                                        }
                                    }
                                }
                                $data = array(
                                    'id_event' => $id,
                                    'id_lang' => 1,
                                    'id_link' => $this->linkClass->saveLink($link, 1,  !empty($is_lang) && !empty($is_lang['id_link']) ? $is_lang['id_link'] : 0, 13),
                                    'name' => !empty($lang['nazwa']) ? $lang['nazwa'] : $e['rodzaj'],
                                    'subname' => '',
                                    'introduction' => '',
                                    'content' => !empty($lang['tresc']) ? $lang['tresc'] : '',
                                    'tags' => '',
                                    'tickets' => $e['bilety'],
                                    'comments' => $e['uwagi'],
                                    'price' => $e['cena'],
                                    'views' => !empty($lang['wyswietlen']) ? $lang['wyswietlen'] : 0,
                                    'edited_at' => date('Y-m-d H:i:s', strtotime($e['data_edycji'])),
                                    'created_at' => date('Y-m-d H:i:s'),
                                );
                                if($is_lang) {
                                    $result = $this->db->table('event_lang')->where('id', $is_lang['id'])->update($data);
                                } else {
                                    $result = $this->db->table('event_lang')->insert($data);
                                }
                                
                                $is_meta = $this->db->table('event_meta_lang')->select('id')->where('id_event', $id)->where('slug', '')->where('id_lang', 1)->get()->getRowArray();
                                $desc = !empty($lang['tresc']) ? $lang['tresc'] : '';
                                $desc = substr(preg_replace('/\s\s+/', ' ', htmlspecialchars(strip_tags($desc), ENT_QUOTES, 'UTF-8')), 0, 300);
                                $desc = substr($desc, 0, strrpos($desc, ' ')); 
                                $meta = array(
                                    'id_event' => $id,
                                    'id_lang' => 1,
                                    'title' => $lang['nazwa'] . ' | Kalendarium imprez i koncertów w Rzeszowie | Serwis Rozrywkowy RESINET.PL',
                                    'description' => $desc,
                                    'keywords' => '',
                                    'slug' => '',
                                );
                                if(!empty($is_meta)) {
                                    $result = $this->db->table('event_meta_lang')->where('id', $is_meta['id'])->update($meta);
                                } else {
                                    $result = $this->db->table('event_meta_lang')->insert($meta);
                                }
                                
                                $lang['tagi'] = trim($lang['tagi'], ',');
                                if(!empty($lang['tagi'])) {
                                    $tags = $this->db_entertainment->table('tagi')->select('*')->whereIn('id', explode(',', $lang['tagi']))->get()->getResultArray();
                                    if(!empty($tags)) {
                                        $id_tags = array();
                                        foreach($tags as $t) {
                                            if(!empty($t['tag'])) {
                                                $is_tag = $this->db->table('tags')->select('*')->where('id_lang', 1)->where('tag', $t['tag'])->get()->getRowArray();
                                                if(!empty($is_tag)) {
                                                    $id_tags[] = $is_tag['id'];
                                                } else {
                                                    $this->db->table('tags')->insert(array(
                                                        'id_page_cont' => 0,
                                                        'tag' => $t['tag'],
                                                        'id_lang' => 1,
                                                        'date' => date('Y-m-d H:i:s', strtotime($t['data_dodania']))
                                                    ));
                                                    $id_tags[] = $this->db->insertID();
                                                }
                                            }
                                        }
                                        if(!empty($id_tags)) {
                                            $this->db->table('event_lang')->set('tags', ',' . implode(',', $id_tags) . ',')->where('id_event', $id)->where('id_lang', 1)->update();
                                        }
                                    }
                                }
                            }
                        }
                        $e['zdjecie_glowne'] = trim($e['zdjecie_glowne'], ',');
                        if(!empty($e['zdjecie_glowne'])) {
                            $photo = $this->db_entertainment->table('kalendarium_pliki')->select('*')->where('id', $e['zdjecie_glowne'])->get()->getRowArray();
                            if(!empty($photo)) {
                                $this->saveFileResinet($id, 'event_files', 'id_event', 'event', date('Ymd', $e['data_dodania']), 'photo', 'https://www.rozrywka.resinet.pl/pliki/zdjecia/kalendarium', $photo);
                                //$this->saveFile($id, 'event_files', 'id_event', 'event', date('Ymd', strtotime($e['data_dodania'])), 'photo', '/home/www/rozrywka.resinet/html/pliki/zdjecia/kalendarium', $photo);
                            }
                        }
                        $e['zdjecia'] = trim($e['zdjecia'], ',');
                        if(!empty($n['zdjecia'])) {
                            $photos = $this->db_entertainment->table('kalendarium_pliki')->select('*')->whereIn('id', explode(',', $e['zdjecia']))->get()->getResultArray();
                            if(!empty($photos)) {
                                foreach($photos as $photo) {
                                    $this->saveFileResinet($id, 'event_files', 'id_event', 'event', date('Ymd', $e['data_dodania']), 'photos', 'https://www.rozrywka.resinet.pl/pliki/zdjecia/kalendarium', $photo);
                                    //$this->saveFile($id, 'event_files', 'id_event', 'event', date('Ymd', strtotime($e['data_dodania'])), 'photos', '/home/www/rozrywka.resinet/html/pliki/zdjecia/kalendarium', $photo);
                                }
                            }
                        }
                    }
                }
                 
                $this->db->transComplete();
                return $this->db->transStatus();
                break;
            case 'events_calendar':
                $this->db->transStart();
                /* usunięcie istniejących pozycji */
                //$r = $this->db->table('event_calendar')->where('id >', 0)->delete();
                /* dodanie nowych pozycji */
                
                $events = $this->db->table('event')->select('id,id_old')->orderBy('id_old', 'ASC')->get()->getResultArray();
                if(!empty($events)) {
                    foreach($events as $event) {
                        $events_calendar = $this->db_entertainment->table('kalendarium')->select('*')->where('id', $event['id_old'])->where('data_od !=', '0000-00-00')->get()->getResultArray();
                        if(!empty($events_calendar)) {
                            foreach($events_calendar as $j=>$ec) {
                                $lang = $this->db_entertainment->table('kalendarium_info_pl')->select('*')->where('id_kalendarium', $ec['id'])->get()->getRowArray();
                                $place = array();
                                if(!empty($lang['id_miejsce'])) {
                                    $place = $this->db->table('event_place')->select('id')->where('id_old', $lang['id_miejsce'])->get()->getRowArray();
                                }
                                $data = array(
                                    'id_event' => !empty($event['id']) ? $event['id'] : 0,
                                    'id_place' => !empty($place) ? $place['id'] : 0,
                                    'date_start' => $ec['data_od'] != '0000-00-00' ? $ec['data_od'] : null,
                                    'date_end' => $ec['data_do'] != '0000-00-00' ? $ec['data_do'] : null,
                                    'hours' => !empty($ec['godzina']) ? json_encode(array(date('H:i', strtotime($ec['godzina'])))) : null,
                                    'custom_place' => !empty($lang['miejsce']) ? $lang['miejsce'] : '',
                                    'edited_at' => date('Y-m-d H:i:s', strtotime($ec['data_edycji'])),
                                    'created_at' => date('Y-m-d H:i:s', strtotime($ec['data_dodania'])),
                                );
                                $result = $this->db->table('event_calendar')->insert($data);
                            }
                        }
                        
                        $event_calendar = $this->db_entertainment->table('kalendarium k')->join('kalendarium_info_pl ki', 'k.id=ki.id_kalendarium')->select('k.*,ki.miejsce,ki.id_miejsce')->where('k.id', $event['id_old'])->get()->getRowArray();
                        $events_calendar2 = $this->db_entertainment->table('kalendarium_repertuar kr')->join('kalendarium_info_pl ki', 'kr.id_impreza=ki.id')->select('kr.*')->where('ki.id_kalendarium', $event['id_old'])->get()->getResultArray();
                        if(!empty($events_calendar2)) {
                            foreach($events_calendar2 as $j=>$ec2) {
                                $place = array();
                                if(!empty($ec2['id_miejsce'])) {
                                    $place = $this->db->table('event_place')->select('id')->where('id_old', $ec2['id_miejsce'])->get()->getRowArray();
                                } elseif(!empty($events_calendar['id_miejsce'])) {
                                    $place = $this->db->table('event_place')->select('id')->where('id_old', $event_calendar['id_miejsce'])->get()->getRowArray();
                                }
                                $hours = json_decode($ec2['godz_json'], true);
                                $tmp_h = array();
                                if(!empty($hours)) {
                                    foreach($hours as $h) {
                                        if($h != '00:00') {
                                            $tmp_h[] = $h;
                                        }
                                    }
                                }
                                $data = array(
                                    'id_event' => !empty($event) ? $event['id'] : 0,
                                    'id_place' => !empty($place) ? $place['id'] : 0,
                                    'date_start' => $ec2['data_od'] != '0000-00-00' ? $ec2['data_od'] : null,
                                    'date_end' => null,
                                    'hours' => !empty($tmp_h) ? json_encode($tmp_h) : null,
                                    'custom_place' => !empty($ec2['miejsce_nazwa']) ? $ec2['miejsce_nazwa'] : (!empty($event_calendar['miejsce']) ? $event_calendar['miejsce'] : ''),
                                    'edited_at' => null,
                                    'created_at' => date('Y-m-d H:i:s', strtotime($ec2['data_dodania'])),
                                );
                                $result = $this->db->table('event_calendar')->insert($data);  
                            }
                        }
                    }
                }
                
                $this->db->transComplete();
                return $this->db->transStatus();
                break;
            
        }
    }
    
    
    private function resinet($action) {
        $this->db_oldresinet = \Config\Database::connect('oldresinet');
        switch($action) {
            case 'news':
                $this->db->transStart();
                $page = array(
                    'spons' => array(673, 13),
                    'rze' => array(14, 10),
                    'podk' => array(15, 11),
                );
                $old_res_id = $page['podk'][0];
                $new_res_id = $page['podk'][1];
                /* dodanie nowych pozycji */
                $news = $this->db_oldresinet->table('news_tresc')->select('*')->where('id_strony', $old_res_id)->where('data >=', '2024-04-27')->orderBy('data', 'ASC')->get()->getResultArray();
                if(!empty($news)) {
                    foreach($news as $j=>$n) {
                        $news_page = $this->db_oldresinet->table('strony')->select('id,link')->where('id', $n['id_strony'])->get()->getRowArray();
                        $n_date = $a = substr($n['id_wpis'], 0, 4) . '-' . substr($n['id_wpis'], 4, 2) . '-' . substr($n['id_wpis'], 6, 2) . ' ' . substr($n['id_wpis'], 8, 2) . ':' . substr($n['id_wpis'], 10, 2) . ':' . substr($n['id_wpis'], 4, 2);
                        $is = $this->db->table('news')->select('id')->where('id_old', $n['id'])->where('id_page_cont', $new_res_id)->get()->getRowArray();
                        $data = array(
                            'id_page_cont' => $new_res_id,
                            'order' => $n['kolejnosc'],
                            'publish' => !empty($n['publikacja']) && $n['publikacja'] == 'tak' ? 1 : 0,
                            'edited_at' => null,
                            'created_at' => date('Y-m-d H:i:s', strtotime($n_date)),
                            'template' => 'small_full.php',
                            'home' => !empty($n['home']) && $n['home'] == 'tak' ? 1 : 0,
                            'date' => !empty($n['data']) && $n['data'] != '0000-00-00' ? date('Y-m-d H:i:s', strtotime($n['data'])) : null,
                            'comment' => !empty($n['kom']) && $n['kom'] == 'tak' ? 1 : 0,
                            'publish_date' =>  NULL,
                            'dont_miss' => !empty($n['nie_przegap']) && $n['nie_przegap'] == 'tak' ? 1 : 0,
                            'investments' => !empty($n['inwestycje']) && $n['inwestycje'] == 'tak' ? 1 : 0,
                            'patronate' => !empty($n['patronat']) && $n['patronat'] == 'tak' ? 1 : 0,
                            'show_in_box' => !empty($n['bb']) && $n['bb'] == 'tak' ? 1 : 0,
                            'newsletter' => !empty($n['newsletter']) && $n['newsletter'] == 'tak' ? 1 : 0,
                            'id_old' => $n['id'],
                        );
                        if(!empty($is)) {
                            $id = $is['id'];
                            $result = $this->db->table('news')->where('id', $id)->update($data);
                        } else {
                            $result = $this->db->table('news')->insert($data);
                            $id = $this->db->insertID();
                        }
                        
                        if($result && $id) {
                            
                            $is_lang = $this->db->table('news_lang')->select('id,id_lang,id_link')->where('id_news', $id)->where('id_lang', 1)->get()->getRowArray();
                            if($news_page['link']=='aktualnosci/art.-sposn.') {
                                $news_page['link'] = 'aktualnosci';
                            }
                            $link = $news_page['link'] . '/' . $n['nlink'];
                            $opts = array(
                                "ssl"=>array(
                                    "allow_self_signed"=>true,
                                    "verify_peer"=>false,
                                    "verify_peer_name"=>false
                                )
                            );
                            preg_match_all('/<img[^>]+>/i', $n['tresc'], $img_tags); 
                            if(!empty($img_tags) && !empty($img_tags[0])) {
                                $n['tresc'] = preg_replace('/(<img[^>]+) style=".*?"/i', '$1', $n['tresc']);
                                foreach($img_tags[0] as $img_tag) {
                                    preg_match( '@src="([^"]+)"@' , $img_tag, $img_attr );
                                    if(!empty($img_attr) && !empty($img_attr[1])) {
                                        if(substr($img_attr[1], 0, 18) == '/userfiles/images/') {
                                            $new_path = 'files/' . str_replace('/userfiles/images/', '', $img_attr[1]);
                                        } elseif(substr($img_attr[1], 0, 39) == 'https://www.resinet.pl/userfiles/image/') {
                                            $new_path = 'files/' . str_replace('https://www.resinet.pl/userfiles/image/', '', $img_attr[1]);
                                            $img_attr[1] = str_replace('https://www.resinet.pl', '', $img_attr[1]);
                                        } elseif(substr($img_attr[1], 0, 38) == 'http://www.resinet.pl/userfiles/image/') {
                                            $new_path = 'files/' . str_replace('http://www.resinet.pl/userfiles/image/', '', $img_attr[1]);
                                            $img_attr[1] = str_replace('http://www.resinet.pl', '', $img_attr[1]);
                                        } else {
                                            $new_path = '';
                                        }
                                        if(!empty($new_path)) {
                                            $n['tresc'] = str_replace('src="' . $img_attr[1] . '"', 'src="/' . $new_path . '"' , $n['tresc']);
                                            $dir_path = '';
                                            $tmp = explode('/', $new_path);
                                            if(!empty($tmp)) {
                                                unset($tmp[count($tmp) - 1]);
                                                foreach($tmp as $t) {
                                                    $dir_path .= ($dir_path ? '/' : '') . $t;
                                                    if(!is_dir(ROOTPATH . 'public/' . $dir_path)) {
                                                        mkdir(ROOTPATH . 'public/' . $dir_path);
                                                    }
                                                }
                                            }
                                            copy('https://www.resinet.pl' . $img_attr[1], ROOTPATH . 'public/' . $new_path, stream_context_create($opts));
                                        }
                                    }
                                }
                            }
                            
                            $lang_data = array(
                                'id_news' => $id,
                                'id_lang' => 1,
                                'id_link' => $this->linkClass->saveLink($link, 1,  !empty($is_lang) && !empty($is_lang['id_link']) ? $is_lang['id_link'] : 0, 2, false, null, null),
                                'title' => $n['temat'],
                                'subtitle' => $n['temat'],
                                'header' => '',
                                'introduction' => $n['wstep'],
                                'content' => $n['tresc'],
                                'author' => '',
                                'source' => $n['zrodlo'],
                                'views' => $n['czytano'],
                                'tags' => '',
                                'edited_at' => null,
                                'created_at' => date('Y-m-d H:i:s', strtotime($n_date)),
                            );
                            if($is_lang) {
                                $result = $this->db->table('news_lang')->where('id', $is_lang['id'])->update($lang_data);
                            } else {
                                $result = $this->db->table('news_lang')->insert($lang_data);
                            }
                            
                            $is_meta = $this->db->table('news_meta_lang')->select('id')->where('id_news', $id)->where('id_lang', 1)->get()->getRowArray();
                            $title = $n['temat'];
                            if($old_res_id == 14){
                                $title .= ' | Rzeszów | Aktualności | RESinet';
                            }
                            if($old_res_id == 15){
                                $title .= ' | Podkarpacie | Aktualności | RESinet';
                            }
                            if($old_res_id == 16){
                                $title .= ' | Kraj | Aktualności | RESinet';
                            }
                            $keywords = "Wiadomości aktualności, informacje, " . $n['slowa'] . " - " . $n['tagi'];
                            $desc = $n['temat'] . " - " . strip_tags($n['wstep']);
                            $desc = substr($desc, 0, 180);
                            $iz=0;
                            foreach(explode(' ',strip_tags($n['wstep'])) as $slowo){
                                $iz = $iz + strlen($slowo);
                                if($iz<170)
                                    $desc .= $slowo . ' ';
                            }
                            $meta = array(
                                'id_news' => $id,
                                'id_lang' => 1,
                                'title' => $title,
                                'description' => $desc,
                                'keywords' => $keywords,
                            );
                            if(!empty($is_meta)) {
                                $result = $this->db->table('news_meta_lang')->where('id', $is_meta['id'])->update($meta);
                            } else {
                                $result = $this->db->table('news_meta_lang')->insert($meta);
                            }
                                
                            $n['tagi'] = trim($n['tagi'], ',');
                            if(!empty($n['tagi'])) {
                                $tags = explode(',', $n['tagi']);
                                if(!empty($tags)) {
                                    $id_tags = array();
                                    foreach($tags as $t) {
                                        $t = trim($t);
                                        if(!empty($t)) {
                                            $is_tag = $this->db->table('tags')->select('*')->where('id_lang', 1)->where('tag', $t)->get()->getRowArray();
                                            if(!empty($is_tag)) {
                                                $id_tags[] = $is_tag['id'];
                                            } else {
                                                $this->db->table('tags')->insert(array(
                                                    'id_page_cont' => 0,
                                                    'tag' => $t,
                                                    'id_lang' => 1,
                                                    'date' => date('Y-m-d H:i:s', strtotime($n_date))
                                                ));
                                                $id_tags[] = $this->db->insertID();
                                            }
                                        }
                                    }
                                    if(!empty($id_tags)) {
                                        $this->db->table('news_lang')->set('tags', ',' . implode(',', $id_tags) . ',')->where('id_news', $id)->where('id_lang', 1)->update();
                                    }
                                }
                            }   
                        
                            $photos = $this->db_oldresinet->table('news_foty')->select('*')->where('id_wpis', $n['id_wpis'])->orderBy('id', 'ASC')->get()->getResultArray();
                            if(!empty($photos)) {
                                foreach($photos as $k=>$p) {
                                    $photo = array(
                                        'plik' => $p['plik'],
                                        'alt' => $p['alt'],
                                        'podpis' => $p['podpis'],
                                        'order' => $k
                                    );
                                    if($k == 0) {
                                        $this->saveFileResinet($id, 'news_files', 'id_news', 'news', date('Ymd', strtotime($n_date)), 'photo', 'https://www.resinet.pl/_foty_news', $photo);
                                    } else {
                                        $this->saveFileResinet($id, 'news_files', 'id_news', 'news', date('Ymd', strtotime($n_date)), 'photos', 'https://www.resinet.pl/_foty_news', $photo);
                                    }
                                }
                            }
                        }
                    }
                }
                $this->db->transComplete();
                return $this->db->transStatus();
                break;
            case 'inrzeszow3':
                $page_ids = array(
                    'noclegi' => array(36, 62, array(245,246,247)),
                );
                $id_page = $page_ids['noclegi'][0];
                $id_page_cont = $page_ids['noclegi'][1];
                $resinet_id_pages = $page_ids['noclegi'][2];
                $list = $this->db_oldresinet->table('cms_informator')->select('*')->whereIn('id_strony', $resinet_id_pages)->orderBy('id', 'ASC')->get()->getResultArray();
                if(!empty($list)) {
                    foreach($list as $k=>$l) {
                        $is = $this->db->table('catalog')->select('id')->where('id_old', $l['id'])->get()->getRowArray();
                        $data = array(
                            'id_page_cont' => $id_page_cont,
                            'id_parent' => 0,
                            'type' => 'nolink',
                            'website' => $l['www'],
                            'email' => '',
                            'phone' => $l['tel'],
                            'cords' => '',
                            'template' => 'tpl.php',
                            'order' => $k,
                            'publish' => $l['publikacja'] == '1' ? 1: 0,
                            'comment' => 0,
                            'edited_at' => null,
                            'created_at' => date('Y-m-d H:i:s'),
                            'id_old' => $l['id'],
                        );
                        if(!empty($is)) {
                            $result = $this->db->table('catalog')->where('id', $is['id'])->update($data);
                            $id = $is['id'];
                        } else {
                            $result = $this->db->table('catalog')->insert($data);
                            $id = $this->db->insertID();
                        }
                        if($result && $id) {
                            $is_lang = $this->db->table('catalog_lang')->select('id,id_link')->where('id_catalog', $id)->get()->getRowArray();
                            $data = array(
                                'id_catalog' => $id,
                                'id_lang' => 1,
                                'id_link' => 0,
                                'name' => $l['nazwa'],
                                'content' => '',
                                'address' => $l['adres'],
                                'open_hours' => '',
                                'views' => 0,
                                'edited_at' => null,
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                            if(!empty($is_lang)) {
                                $result = $this->db->table('catalog_lang')->where('id_lang', 1)->where('id', $is_lang['id'])->update($data);
                            } else {
                                $result = $this->db->table('catalog_lang')->insert($data);
                            }
                        }
                    }
                }
                break;
            case 'inrzeszow2':
                $page_ids = array(
                    'parafie' => array(33, 59, array(191,192,193,194)),
                );
                $id_page = $page_ids['parafie'][0];
                $id_page_cont = $page_ids['parafie'][1];
                $resinet_id_pages = $page_ids['parafie'][2];
                $list = $this->db_oldresinet->table('news_tresc')->select('*')->whereIn('id_strony', $resinet_id_pages)->orderBy('id', 'ASC')->get()->getResultArray();
                if(!empty($list)) {
                    foreach($list as $l) {
                        $is = $this->db->table('catalog')->select('id')->where('id_old', $l['id'])->get()->getRowArray();
                        $data = array(
                            'id_page_cont' => $id_page_cont,
                            'id_parent' => 0,
                            'type' => 'simple',
                            'website' => '',
                            'email' => '',
                            'cords' => '',
                            'template' => 'tpl.php',
                            'order' => $l['kolejnosc'],
                            'publish' => $l['publikacja'] == 'tak' ? 1: 0,
                            'comment' => $l['kom'] == 'tak' ? 1: 0,
                            'edited_at' => null,
                            'created_at' => date('Y-m-d H:i:s', strtotime($l['data'])),
                            'id_old' => $l['id'],
                        );
                        if(!empty($is)) {
                            $result = $this->db->table('catalog')->where('id', $is['id'])->update($data);
                            $id = $is['id'];
                        } else {
                            $result = $this->db->table('catalog')->insert($data);
                            $id = $this->db->insertID();
                        }
                        if($result && $id) {
                            $is_lang = $this->db->table('catalog_lang')->select('id,id_link')->where('id_catalog', $id)->get()->getRowArray();
                            $link = $this->linkClass->generateLink($l['temat'], 1, !empty($is_lang) ? $is_lang['id_link'] : 0, $id_page, 'page', '');
                            $data = array(
                                'id_catalog' => $id,
                                'id_lang' => 1,
                                'id_link' => $this->linkClass->saveLink($link, 1, !empty($is_lang) ? $is_lang['id_link'] : 0, 18, false, null, null, 1, 0),
                                'name' => $l['temat'],
                                'content' => $l['tresc'],
                                'address' => strip_tags($l['wstep']),
                                'open_hours' => strip_tags($l['uwaga']),
                                'views' => $l['czytano'],
                                'edited_at' => null,
                                'created_at' => date('Y-m-d H:i:s', strtotime($l['data'])),
                            );
                            if(!empty($is_lang)) {
                                $result = $this->db->table('catalog_lang')->where('id_lang', 1)->where('id', $is_lang['id'])->update($data);
                            } else {
                                $result = $this->db->table('catalog_lang')->insert($data);
                            }
                            
                            $photos = $this->db_oldresinet->table('news_foty')->select('*')->where('id_wpis', $l['id_wpis'])->orderBy('id', 'ASC')->limit(1)->get()->getResultArray();
                            if(!empty($photos)) {
                                foreach($photos as $photo) {
                                    $this->saveFileResinet($id, 'catalog_files', 'id_catalog', 'catalog', date('Ymd', strtotime($l['data'])), 'photo', 'https://www.resinet.pl/_foty_news', $photo);
                                }
                            }
                            
                            $photos2 = $this->db_oldresinet->table('news_foty')->select('*')->where('id_wpis', $l['id_wpis'])->orderBy('id', 'ASC')->limit(100, 1)->get()->getResultArray();
                            if(!empty($photos2)) {
                                foreach($photos2 as $photo) {
                                    $this->saveFileResinet($id, 'catalog_files', 'id_catalog', 'catalog', date('Ymd', strtotime($l['data'])), 'photos', 'https://www.resinet.pl/_foty_news', $photo);
                                }
                            }
                        }
                    }
                }
                break;
            case 'inrzeszow':
                $page_ids = array(
                    'atrakcje' => array(20, 20, 700),
                    'place_zabaw' => array(39, 65, 709),
                    'zabytki_pomniki' => array(43, 69, 174),
                );
                $id_page = $page_ids['zabytki_pomniki'][0];
                $id_page_cont = $page_ids['zabytki_pomniki'][1];
                $resinet_id_page = $page_ids['zabytki_pomniki'][2];
                $list = $this->db_oldresinet->table('news_tresc')->select('*')->where('id_strony', $resinet_id_page)->orderBy('id', 'ASC')->get()->getResultArray();
                if(!empty($list)) {
                    foreach($list as $l) {
                        $is = $this->db->table('catalog')->select('id')->where('id_old', $l['id'])->get()->getRowArray();
                        $data = array(
                            'id_page_cont' => $id_page_cont,
                            'id_parent' => 0,
                            'type' => 'simple',
                            'website' => '',
                            'email' => '',
                            'cords' => '',
                            'template' => 'tpl.php',
                            'order' => $l['kolejnosc'],
                            'publish' => $l['publikacja'] == 'tak' ? 1: 0,
                            'comment' => $l['kom'] == 'tak' ? 1: 0,
                            'edited_at' => null,
                            'created_at' => date('Y-m-d H:i:s', strtotime($l['data'])),
                            'id_old' => $l['id'],
                        );
                        if(!empty($is)) {
                            $result = $this->db->table('catalog')->where('id', $is['id'])->update($data);
                            $id = $is['id'];
                        } else {
                            $result = $this->db->table('catalog')->insert($data);
                            $id = $this->db->insertID();
                        }
                        if($result && $id) {
                            $is_lang = $this->db->table('catalog_lang')->select('id,id_link')->where('id_catalog', $id)->get()->getRowArray();
                            $link = $this->linkClass->generateLink($l['temat'], 1, !empty($is_lang) ? $is_lang['id_link'] : 0, $id_page, 'page', '');
                            $data = array(
                                'id_catalog' => $id,
                                'id_lang' => 1,
                                'id_link' => $this->linkClass->saveLink($link, 1, !empty($is_lang) ? $is_lang['id_link'] : 0, 18, false, null, null, 1, 0),
                                'name' => $l['temat'],
                                'content' => $l['tresc'],
                                'address' => '',
                                'open_hours' => '',
                                'views' => $l['czytano'],
                                'edited_at' => null,
                                'created_at' => date('Y-m-d H:i:s', strtotime($l['data'])),
                            );
                            if(!empty($is_lang)) {
                                $result = $this->db->table('catalog_lang')->where('id_lang', 1)->where('id', $is_lang['id'])->update($data);
                            } else {
                                $result = $this->db->table('catalog_lang')->insert($data);
                            }
                            
                            $photos = $this->db_oldresinet->table('news_foty')->select('*')->where('id_wpis', $l['id_wpis'])->orderBy('id', 'ASC')->limit(1)->get()->getResultArray();
                            if(!empty($photos)) {
                                foreach($photos as $photo) {
                                    $this->saveFileResinet($id, 'catalog_files', 'id_catalog', 'catalog', date('Ymd', strtotime($l['data'])), 'photo', '/home/www/resinet/html/_foty_news', $photo);
                                }
                            }
                            
                            $photos2 = $this->db_oldresinet->table('news_foty')->select('*')->where('id_wpis', $l['id_wpis'])->orderBy('id', 'ASC')->limit(100, 1)->get()->getResultArray();
                            if(!empty($photos2)) {
                                foreach($photos2 as $photo) {
                                    $this->saveFileResinet($id, 'catalog_files', 'id_catalog', 'catalog', date('Ymd', strtotime($l['data'])), 'photos', '/home/www/resinet/html/_foty_news', $photo);
                                }
                            }
                        }
                    }
                }
                
                break;
            case 'inrzeszow-tags':
                $list = $this->db->table('catalog c')->join('catalog_meta_lang cml', 'c.id=cml.id_catalog')->select('c.id,cml.keywords')->where('cml.id_lang', 1)->where('cml.keywords !=', '')->get()->getResultArray();
                if(!empty($list)) {
                    foreach($list as $l) {
                        $l['keywords'] = trim($l['keywords'], ',');
                        if(!empty($l['keywords'])) {
                            $tags = explode(',', $l['keywords']);
                            if(!empty($tags)) {
                                $id_tags = array();
                                foreach($tags as $t) {
                                    $t = trim($t);
                                    if(!empty($t)) {
                                        $is_tag = $this->db->table('tags')->select('*')->where('id_lang', 1)->where('tag', $t)->get()->getRowArray();
                                        if(!empty($is_tag)) {
                                            $id_tags[] = $is_tag['id'];
                                        } else {
                                            $this->db->table('tags')->insert(array(
                                                'id_page_cont' => 0,
                                                'tag' => $t,
                                                'id_lang' => 1,
                                                'date' => date('Y-m-d H:i:s')
                                            ));
                                            $id_tags[] = $this->db->insertID();
                                        }
                                    }
                                }
                                if(!empty($id_tags)) {
                                    $this->db->table('catalog_lang')->set('tags', ',' . implode(',', $id_tags) . ',')->where('id_catalog', $l['id'])->where('id_lang', 1)->update();
                                }
                            }
                        }
                    }
                }
                break;
        }
    }
    
    
    private function aut($action) {
        $this->db_autresinet = \Config\Database::connect('autresinet');
        switch($action) {
            case 'users':
                $this->db->transStart();
                $list = $this->db_autresinet->table('users')->select('*')->where('aktywny', 1)->where('mail !=', '')->orderBy('id_user', 'ASC')->limit(2000, 13000)->get()->getResultArray();

                if(!empty($list)) {
                    foreach($list as $l) {
                        $is = $this->db->table('users')->select('id')->where('id_old', $l['id_user'])->get()->getRowArray();
                        $l['mail'] = trim($l['mail']);
                        //$token = str_shuffle($l['mail'] . random_string('alnum', 20));
                        $data = array(
                            'mail' => $l['mail'],
                            'password' => password_hash($l['pass'], PASSWORD_BCRYPT),
                            'name' => $l['imie'],
                            'surname' => $l['nazwisko'],
                            'nick' => $l['login'] ? $l['login'] : substr($l['mail'], 0, 1) . '***' . substr($l['mail'], -1),
                            'city' => $l['miasto'],
                            'phone' => $l['telefon_komorkowy'] ? $l['telefon_komorkowy'] : $l['telefon'],
                            'created_at' => !empty($l['rejestracja']) ? date('Y-m-d H:i:s', strtotime($l['rejestracja'])) : date('Y-m-d H:i:s'),
                            'edited_at' => null,
                            'secret' => null,
                            'secret_valid_to' => null,
                            'google_id' => null,
                            'fb_id' => !empty($l['fb_id']) ? $l['fb_id'] : null,
                            'newsletter' => 0,
                            'active' => !empty($l['aktywny']) && $l['aktywny'] ? 1 : 0,
                            'comments' => 1,
                            'id_old' => $l['id_user'],
                        );
                        if(!empty($is)) {
                            $id = $is['id'];
                            $result = $this->db->table('users')->where('id', $id)->update($data);
                        } else {
                            $result = $this->db->table('users')->insert($data);
                            $id = $this->db->insertID();
                        }
                    }
                }
                $this->db->transComplete();
                return $this->db->transStatus();
                break;
        }
    }
    
    
    
    private function saveFileResinet($id, $table, $key, $dir, $subdir, $field, $path, $file) {
        $file_info = pathinfo($path . '/' . $file['plik']);
        $mime = $this->get_image_mime_type($path . '/' . $file['plik']);
        //$mime = mime_content_type($path . '/' . $file['plik']);
        $mb_client_name = mb_url_title($file_info['filename'], '-');
        $mb_client_name_ext = $mb_client_name . '.' . $file_info['extension'];
        
        if(!is_dir(WRITEPATH . 'uploads/' . $dir)) {
            mkdir(WRITEPATH . 'uploads/' . $dir);
        }
        if(!is_dir(WRITEPATH . 'uploads/' . $dir . '/' . $subdir)) {
            mkdir(WRITEPATH . 'uploads/' . $dir . '/' . $subdir);
        }
        
        $is = $this->db->table($table)->select('id')->where('field', $field)->where($key, $id)->where('path', $dir . '/' . $subdir . '/' . $mb_client_name_ext)->get()->getRowArray();
        
        if(empty($is)) {
            //$r = copy($path . '/' . $file['plik'], WRITEPATH . 'uploads/' . $dir . '/' . $subdir . '/' . $mb_client_name_ext);
            $count = 1;
            do {
                $is = file_exists(WRITEPATH . 'uploads/' . $dir . '/' . $subdir . '/' . $mb_client_name_ext);
                if($is) {
                    $mb_client_name_ext = $mb_client_name . '-' . $count . '.' . $file_info['extension'];
                }
                ++$count;
            } while($is && $count<=100);
            $r = copy($path . '/' . $file['plik'], WRITEPATH . 'uploads/' . $dir . '/' . $subdir . '/' . $mb_client_name_ext);
            var_dump(WRITEPATH . 'uploads/' . $dir . '/' . $subdir . '/' . $mb_client_name_ext);
        } else {
            $r = true;
        }
        $data = array(
            $key => $id,
            'field' => $field,
            'name' => $mb_client_name,
            'basename' => $mb_client_name_ext,
            'path' => $dir . '/' . $subdir . '/' . $mb_client_name_ext,
            'mime' => $mime,
            'type' => file_type($mime),
            'ext' => $file_info['extension'],
            'order' => !empty($file['order']) ? $file['order'] : 0,
            'publish' => 1,
        );
        if($r) {
            if(empty($is)) {
                $result = $this->db->table($table)->insert($data);
                $id_file = $this->db->insertID();
            } else {
                $this->db->table($table)->where('id', $is['id'])->update($data);
                $id_file = $is['id'];
            }
            $lang_data = array(
                'id_file' => $id_file,
                'id_lang' => 1,
                'caption' => !empty($file['alt']) ? $file['alt'] : '',
                'author' => !empty($file['podpis']) ? $file['podpis'] : '',
            );
            if(empty($is)) {
                $this->db->table($table . '_lang')->insert($lang_data);
            } else {
                $this->db->table($table . '_lang')->where('id_file', $id_file)->where('id_lang', 1)->update($lang_data);
            }
        }
    }
    
    
    private function saveFile($id, $table, $key, $dir, $subdir, $field, $path, $file) {
        $file_info = pathinfo($path . '/' . $file['plik']);
        $mime = $this->get_image_mime_type($path . '/' . $file['plik']);
        //$mime = mime_content_type($path . '/' . $file['plik']);
        $mb_client_name = mb_url_title($file_info['filename'], '-');
        $mb_client_name_ext = $mb_client_name . '.' . $file_info['extension'];
        
        if(!is_dir(WRITEPATH . 'uploads/' . $dir)) {
            mkdir(WRITEPATH . 'uploads/' . $dir);
        }
        if(!is_dir(WRITEPATH . 'uploads/' . $dir . '/' . $subdir)) {
            mkdir(WRITEPATH . 'uploads/' . $dir . '/' . $subdir);
        }
        
        $r = copy($path . '/' . $file['plik'], WRITEPATH . 'uploads/' . $dir . '/' . $subdir . '/' . $mb_client_name_ext);
        
        $data = array(
            $key => $id,
            'field' => $field,
            'name' => $mb_client_name,
            'basename' => $mb_client_name_ext,
            'path' => $dir . '/' . $subdir . '/' . $mb_client_name_ext,
            'mime' => $mime,
            'type' => file_type($mime),
            'ext' => $file_info['extension'],
            'order' => !empty($file['order']) ? $file['order'] : 0,
            'publish' => 1,
        );
        if($r) {
            $file['params'] = unserialize($file['params']);
            $this->db->table($table)->insert($data);
            $id_file = $this->db->insertID();
            $this->db->table($table . '_lang')->insert(array(
                'id_file' => $id_file,
                'id_lang' => 1,
                'caption' => !empty($file['params']) && !empty($file['params']['podpis_pliku']) && !empty($file['params']['podpis_pliku']['_pl']) ? $file['params']['podpis_pliku']['_pl'] : '',
                'author' => '',
            ));
        }
        
    }
    
    private function get_image_mime_type(string $image_path):?string
    {
        $mimes  = [
            IMAGETYPE_GIF => "image/gif",
            IMAGETYPE_JPEG => "image/jpg",
            IMAGETYPE_PNG => "image/png",
            IMAGETYPE_SWF => "image/swf",
            IMAGETYPE_PSD => "image/psd",
            IMAGETYPE_BMP => "image/bmp",
            IMAGETYPE_TIFF_II => "image/tiff",
            IMAGETYPE_TIFF_MM => "image/tiff",
            IMAGETYPE_JPC => "image/jpc",
            IMAGETYPE_JP2 => "image/jp2",
            IMAGETYPE_JPX => "image/jpx",
            IMAGETYPE_JB2 => "image/jb2",
            IMAGETYPE_SWC => "image/swc",
            IMAGETYPE_IFF => "image/iff",
            IMAGETYPE_WBMP => "image/wbmp",
            IMAGETYPE_XBM => "image/xbm",
            IMAGETYPE_ICO => "image/ico"];

        if (($image_type = exif_imagetype($image_path))
            && (array_key_exists($image_type ,$mimes)))
        {
            return $mimes[$image_type];
        }
        return NULL;
    }
}