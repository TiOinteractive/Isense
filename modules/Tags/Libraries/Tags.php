<?php

namespace Modules\Tags\Libraries;

use Modules\Tags\Models\TagsModel;
use Modules\News\Models\NewsModel;
use Modules\Event\Models\EventCalendarModel;
use Modules\Catalog\Models\CatalogModel;
use Modules\Flavors\Models\FlavorsRestaurantsModel;

class Tags {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->tagsModel = new TagsModel();
        $this->newsModel = new NewsModel();
        $this->eventCalendarModel = new EventCalendarModel();
        $this->catalogModel = new CatalogModel();
        $this->flavorsRestaurantsModel = new FlavorsRestaurantsModel();
    }

    public function slugs($slug, $id_lang, $link) {
        helper('text');
        
        switch($slug) {
            case 'search_tags':
                $tag = array();
                $list = array();
                $filters = array();
                if(!empty($link['get'])) {
                    for($i=0; $i<count($link['get']); $i+=2) {
                        if(!empty($link['get'][$i + 1])) {
                            $filters[$link['get'][$i]] = urldecode($link['get'][$i + 1]);
                        } elseif(!empty($link['get'][$i])) {
                            $filters[$link['get'][$i-2]] .= '/' . $link['get'][$i];
                        }
                    }
                }
                if(!empty($filters['t'])) {
                    $tag = $this->tagsModel->db->table('tags')->select('id,tag')->where('id_lang', $id_lang)->where('tag', $filters['t'])->get()->getRowArray();
                    if(!empty($tag)) {
                        $news = $this->newsModel
                                ->join('news_lang nl', 'news.id=nl.id_news')
                                ->join('links l', 'l.id=nl.id_link')
                                ->select('news.id,news.id_page_cont,news.date,nl.title,nl.header,nl.introduction,l.link,l.redirect as link_redirect')
                                ->where('nl.id_lang', $id_lang)
                                ->where('l.id_lang', $id_lang)
                                ->like('nl.tags', ',' . $tag['id'] . ',')
                                ->orderBy('news.date', 'DESC')
                                ->paginate(10, 'news');
                        if (!empty($news)) {
                            $list['news'] = array();
                            foreach ($news as $k => $n) {
                                $n['photo'] = $this->newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->select('nf.path,nf.crop_dimension,nfl.caption,nfl.author')->where('nf.id_news', $n['id'])->where('nf.publish', 1)->where('nf.field', 'photo')->where('nfl.id_lang', $id_lang)->get()->getRowArray();
                                $n['link'] = (!$n['link_redirect'] && $this->locale ? $this->locale . '/' : '') . $n['link'];
                                $n['link_external'] = $n['link_redirect'] && str_contains($n['link'], 'http');
                                if (empty($n['header'])) {
                                    $n['info_page'] = $this->newsModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->select('p.id,pl.name,pl.header,l.link')->where('pc.id', $n['id_page_cont'])->where('pl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->where('p.publish', 1)->get()->getRowArray();
                                }
                                $list['news'][] = $n;
                            }
                        }
                        
                        $events = $this->eventCalendarModel
                                ->join('event e', 'e.id=event_calendar.id_event')
                                ->join('event_lang el', 'e.id=el.id_event')
                                ->join('event_place ep', 'ep.id=event_calendar.id_place', 'left')
                                ->join('event_place_lang epl', 'ep.id=epl.id_place', 'left')
                                ->join('links l', 'l.id=el.id_link')
                                ->join('links l2', 'l2.id=epl.id_link', 'left')
                                ->join('event_type_lang etl', 'etl.id_type=e.id_type', 'left')
                                ->select('IF(date_end IS NULL, 1, 0) as period,event_calendar.id,event_calendar.id_event,event_calendar.id_place,e.for_kids,e.patronage,el.price,el.name,l.link,event_calendar.custom_place,event_calendar.date_start,event_calendar.date_end,event_calendar.hours,epl.name as place,l2.link as place_link,etl.name as type_name,etl.slug as type_slug')
                                ->where('el.id_lang', $id_lang)
                                ->where('e.publish', 1)
                                ->where('event_calendar.date_start !=', '0000-00-00')
                                ->like('el.tags', ',' . $tag['id'] . ',')
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
                                ->groupEnd()
                                ->orderBy('event_calendar.date_start', 'DESC')
                                ->orderBy('event_calendar.hours', 'DESC')
                                ->groupBy('event_calendar.id_event')
                                ->paginate(10, 'event');
                        if (!empty($events)) {
                            $list['events'] = array();
                            foreach ($events as $k => $e) {
                                $e['photo'] = $this->eventCalendarModel->db->table('event_files ef')->join('event_files_lang efl', 'ef.id=efl.id_file')->select('ef.path,ef.crop_dimension,efl.caption,efl.author')->where('ef.id_event', $e['id_event'])->where('ef.publish', 1)->where('ef.field', 'photo')->where('efl.id_lang', $id_lang)->get()->getRowArray();
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
                                $list['events'][] = $e;
                            }
                        }
                        
                        $catalog = $this->catalogModel
                                ->join('catalog_lang cl', 'catalog.id=cl.id_catalog')
                                ->join('links l', 'l.id=cl.id_link')
                                ->select('catalog.id,catalog.id_page_cont,cl.name,cl.address,l.link')
                                ->where('cl.id_lang', $id_lang)
                                ->where('l.id_lang', $id_lang)
                                ->like('cl.tags', ',' . $tag['id'] . ',')
                                ->orderBy('catalog.order', 'ASC')
                                ->paginate(10, 'catalog');
                        if (!empty($catalog)) {
                            $list['catalog'] = array();
                            foreach ($catalog as $k => $c) {
                                $c['photo'] = $this->catalogModel->db->table('catalog_files cf')->join('catalog_files_lang cfl', 'cf.id=cfl.id_file')->select('cf.path,cfl.caption,cfl.author')->where('cf.id_catalog', $c['id'])->where('cf.publish', 1)->where('cf.field', 'photo')->where('cfl.id_lang', $id_lang)->get()->getRowArray();
                                $c['link'] = ($this->locale ? $this->locale . '/' : '') . $c['link'];
                                $c['info_page'] = $this->catalogModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->select('p.id,pl.name,pl.header,l.link')->where('pc.id', $c['id_page_cont'])->where('pl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->where('p.publish', 1)->get()->getRowArray();
                                $list['catalog'][] = $c;
                            }
                        }
                        
                        $restaurants = $this->flavorsRestaurantsModel
                                ->join('flavors_restaurant_lang frl', 'flavors_restaurant.id=frl.id_restaurant')
                                ->join('links l', 'l.id=frl.id_link')
                                ->select('flavors_restaurant.id,flavors_restaurant.id_category,frl.name,frl.address,frl.city,l.link')
                                ->where('frl.id_lang', $id_lang)
                                ->where('l.id_lang', $id_lang)
                                ->like('frl.tags', ',' . $tag['id'] . ',')
                                ->orderBy('flavors_restaurant.order', 'ASC')
                                ->paginate(10, 'restaurant');
                        if (!empty($restaurants)) {
                            $list['restaurants'] = array();
                            foreach ($restaurants as $k => $r) {
                                $r['photo'] = $this->flavorsRestaurantsModel->db->table('flavors_restaurant_files frf')->join('flavors_restaurant_files_lang frfl', 'frf.id=frfl.id_file')->select('frf.path,frf.crop_dimension,frfl.caption,frfl.author')->where('frf.id_restaurant', $r['id'])->where('frf.publish', 1)->where('frf.field', 'photo')->where('frfl.id_lang', $id_lang)->get()->getRowArray();
                                $r['link'] = ($this->locale ? $this->locale . '/' : '') . $r['link'];
                                $r['category'] = $this->flavorsRestaurantsModel->db->table('flavors_categories fc')->join('flavors_categories_lang fcl', 'fc.id=fcl.id_category')->join('links l', 'l.id=fcl.id_link')->select('fc.id,fcl.name,l.link')->where('fc.id', $r['id_category'])->where('fcl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->where('fc.publish', 1)->get()->getRowArray();
                                $list['restaurants'][] = $r;
                            }
                        }
                    }
                }
                return array(
                    'template' => 'search.php',
                    'list' => $list,
                    'search_tag' => !empty($filters['t']) ? $filters['t'] : '',
                    'tag' => $tag,
                    'news_pager' => $this->newsModel->pager,
                    'event_pager' => $this->eventCalendarModel->pager,
                    'catalog_pager' => $this->catalogModel->pager,
                    'restaurants_pager' => $this->flavorsRestaurantsModel->pager,
                );
                break;
        }
    }
}