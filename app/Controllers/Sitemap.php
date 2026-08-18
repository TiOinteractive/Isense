<?php

namespace App\Controllers;

use App\Libraries\Page;
use App\Models\SettingsModel;
use App\Models\PageModel;
use App\Models\ModuleModel;
use App\Models\FileModel;


class Sitemap extends BaseController {
    private $max_items_per_sitemap = 2000;
    
    public function __construct() 
    {
        $this->session = session();
        $this->pageClass = new Page();
        $this->db = \Config\Database::connect();
    }
    
    public function sitemaps() {
        $moduleModel = new ModuleModel();
        $list = array();
        $list[] = array(
            'link' => 'sitemap-main.xml'
        );
        if (is_dir(ROOTPATH . 'modules/News') && class_exists('\Modules\News\Controllers\NewsAdmin') && class_exists('\Modules\News\Models\NewsModel') && !empty($moduleModel->select('id')->where('slug', 'News')->where('publish', 1)->first())) {
            $count = $this->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('language l', 'l.id=nl.id_lang')->join('links li', 'li.id=nl.id_link')->where('n.publish', 1)->where('l.default', 1)->countAllResults();
            for($i = 0; $i < $count / $this->max_items_per_sitemap; $i++) {
                $list[] = array(
                    'link' => 'sitemap-allnews-' . ($i + 1) . '.xml'
                );
            }
            
        }
        if (is_dir(ROOTPATH . 'modules/Event') && class_exists('\Modules\Event\Controllers\EventAdmin') && class_exists('\Modules\Event\Models\EventModel') && !empty($moduleModel->select('id')->where('slug', 'Event')->where('publish', 1)->first())) {
            $list[] = array(
                'link' => 'sitemap-event.xml'
            );
        }
        if (is_dir(ROOTPATH . 'modules/Catalog') && class_exists('\Modules\Catalog\Controllers\CatalogAdmin') && class_exists('\Modules\Catalog\Models\CatalogModel') && !empty($moduleModel->select('id')->where('slug', 'Catalog')->where('publish', 1)->first())) {
            $list[] = array(
                'link' => 'sitemap-catalog.xml'
            );
        }
        if (is_dir(ROOTPATH . 'modules/Cinema') && class_exists('\Modules\Cinema\Controllers\CinemaAdmin') && class_exists('\Modules\Cinema\Models\CinemaMovieModel') && !empty($moduleModel->select('id')->where('slug', 'Cinema')->where('publish', 1)->first())) {
            $list[] = array(
                'link' => 'sitemap-cinema.xml'
            );
        }
        if (is_dir(ROOTPATH . 'modules/Foto') && class_exists('\Modules\Foto\Controllers\FotoAdmin') && !empty($moduleModel->select('id')->where('slug', 'Foto')->where('publish', 1)->first())) {
            $list[] = array(
                'link' => 'sitemap-foto.xml'
            );
        }
        if (is_dir(ROOTPATH . 'modules/Flavors') && class_exists('\Modules\Flavors\Controllers\FlavorsAdmin') && !empty($moduleModel->select('id')->where('slug', 'Flavors')->where('publish', 1)->first())) {
            $list[] = array(
                'link' => 'sitemap-flavors.xml'
            );
        }
        $this->response->setContentType('application/xml');
        echo view('sitemaps', array('list' => $list));
    }
    
    public function singleSitemap($name, $count=0) {
        $this->moduleModel = new ModuleModel();
        $this->fileModel = new FileModel();
        $this->languages = $this->pageClass->getLanguages(1);
        $list = array();
        if (method_exists($this, $name)) {
            $list = $this->$name($count);
        }
        $this->response->setContentType('application/xml');
        echo view('sitemap', array('list' => $list, 'languages' => $this->languages));
    }
    
    //only google news
    private function news($count=0) {
        $list = array();
        if (is_dir(ROOTPATH . 'modules/News') && class_exists('\Modules\News\Controllers\NewsAdmin') && class_exists('\Modules\News\Models\NewsModel') && !empty($this->moduleModel->select('id')->where('slug', 'News')->where('publish', 1)->first())) {
            $newsModel = new \Modules\News\Models\NewsModel();
            $news = $newsModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('language l', 'l.id=nl.id_lang')->join('links li', 'li.id=nl.id_link')->select('n.id,n.edited_at,n.created_at,li.link,nl.id_lang')->where('n.publish', 1)->where('l.default', 1)->orderBy('n.date', 'DESC')->limit(20)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($news)) {
                foreach($news as $k=>$n) {
                    $news[$k]['images'] = $newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->join('language l', 'l.id=nfl.id_lang')->select('nf.name,nf.path,nfl.caption')->where('l.default', 1)->where('nf.id_news', $n['id'])->whereIn('field', array('photo', 'photos'))->orderBy('nf.field', 'ASC')->orderBy('nf.order', 'ASC')->get()->getResultArray();
                    $news[$k]['videos'] = $newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->join('language l', 'l.id=nfl.id_lang')->select('nf.name,nf.path,nfl.caption')->where('l.default', 1)->where('nf.id_news', $n['id'])->where('field', 'video')->orderBy('nf.order', 'ASC')->get()->getResultArray();
                    $news[$k]['priority'] = 0.6;
                    $news[$k]['changefreq'] = 'monthly';
                    $news[$k]['langs'] = $newsModel->db->table('news_lang nl')->join('links l', 'l.id=nl.id_link')->select('nl.id_lang,nl.edited_at,nl.created_at,l.link')->where('nl.id_news', $n['id'])->get()->getResultArray();
                }
                $list['news'] = $news;
            }
        }
        return $list;
    }
    
    private function main($count=0) {
        $list = array();
        $pageModel = new PageModel();
        $pages = $pageModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->join('links li', 'li.id=pl.id_link')->select('p.id,p.edited_at,p.created_at,p.id_photo,p.id_meta_photo,pl.name,li.link,pl.id_lang')->where('p.publish', 1)->where('p.no_index', 0)->where('l.default', 1)->get()->getResultArray();
        if(count($this->languages) >= 1 && !empty($pages)) {
            foreach($pages as $k=>$p) {
                $pages[$k]['images'] = array();
                if($p['id_photo']) {
                    $photo = $this->fileModel->select('name,path')->where('id', $p['id_photo'])->first();
                    if(!empty($photo)) {
                        $pages[$k]['images'][] = $photo;
                    }
                }
                if($p['id_meta_photo']) {
                    $photo = $this->fileModel->select('name,path')->where('id', $p['id_meta_photo'])->first();
                    if(!empty($photo)) {
                        $pages[$k]['images'][] = $photo;
                    }
                }
                $content = $this->moduleModel->db->table('module_element me')->join('page_content pc', 'me.id=pc.id_module_element')->join('module m', 'm.id=me.id_module')->select('pc.id_element,m.slug')->where('pc.id_page', $p['id'])->where('me.publish', 1)->where('pc.id_element !=', 0)->where('me.publish', 1)->get()->getResultArray();
                if(!empty($content)) {
                    foreach($content as $c) {
                        $class = '\Modules\\' . ucfirst($c['slug']) . '\Models\\' . ucfirst($c['slug'] . 'Model');
                        if(is_dir(ROOTPATH . 'modules/' . ucfirst($c['slug'])) && class_exists($class)) {
                            $model = new $class();
                            if(method_exists($model, 'getElementPhotosForSitemap')) {
                                $pages[$k]['images'] = array_merge($pages[$k]['images'], $model->getElementPhotosForSitemap($c['id_element']));
                            }
                            
                        }
                    }
                }
                $pages[$k]['priority'] = 1;
                $pages[$k]['changefreq'] = 'weekly';
                $pages[$k]['langs'] = $pageModel->db->table('page_lang pl')->join('links l', 'l.id=pl.id_link')->select('pl.id_lang,pl.edited_at,pl.created_at,l.link')->where('pl.id_page', $p['id'])->get()->getResultArray();
            }
            $list['pages'] = $pages;
        }
        if (is_dir(ROOTPATH . 'modules/Gallery') && class_exists('\Modules\Gallery\Controllers\GalleryAdmin') && class_exists('\Modules\Gallery\Models\GalleryModel') && !empty($this->moduleModel->select('id')->where('slug', 'Gallery')->where('publish', 1)->first())) {
            $galleryModel = new \Modules\Gallery\Models\GalleryModel();
            $galleries = $galleryModel->db->table('gallery g')->join('gallery_lang gl', 'g.id=gl.id_gallery')->join('language l', 'l.id=gl.id_lang')->join('links li', 'li.id=gl.id_link')->select('g.id,g.edited_at,g.created_at,li.link,gl.id_lang')->where('g.publish', 1)->where('l.default', 1)->where('gl.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($galleries)) {
                foreach($galleries as $k=>$g) {
                    $galleries[$k]['images'] = $galleryModel->db->table('gallery_files gf')->join('gallery_files_lang gfl', 'gf.id=gfl.id_file')->join('language l', 'l.id=gfl.id_lang')->select('gf.name,gf.path,gfl.caption')->where('l.default', 1)->where('gf.id_gallery', $g['id'])->whereIn('field', array('photo', 'photos'))->orderBy('gf.field', 'ASC')->get()->getResultArray();
                    $galleries[$k]['priority'] = 0.4;
                    $galleries[$k]['changefreq'] = 'monthly';
                    $galleries[$k]['langs'] = $galleryModel->db->table('gallery_lang gl')->join('links l', 'l.id=gl.id_link')->select('gl.id_lang,gl.edited_at,gl.created_at,l.link')->where('gl.id_gallery', $g['id'])->get()->getResultArray();
                }
                $list['galleries'] = $galleries;
            }
        }
        return $list;
    }
    
    private function allnews($count=0) {
        $list = array();
        if (is_dir(ROOTPATH . 'modules/News') && class_exists('\Modules\News\Controllers\NewsAdmin') && class_exists('\Modules\News\Models\NewsModel') && !empty($this->moduleModel->select('id')->where('slug', 'News')->where('publish', 1)->first())) {
            $newsModel = new \Modules\News\Models\NewsModel();
            if(!empty($count)) {
                $count_all = $newsModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('language l', 'l.id=nl.id_lang')->join('links li', 'li.id=nl.id_link')->where('n.publish', 1)->where('l.default', 1)->countAllResults();
                $news = $newsModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('language l', 'l.id=nl.id_lang')->join('links li', 'li.id=nl.id_link')->select('n.id,n.edited_at,n.created_at,li.link,nl.id_lang')->where('n.publish', 1)->where('l.default', 1)->orderBy('n.date', 'ASC')->limit($this->max_items_per_sitemap, $this->max_items_per_sitemap * ($count - 1))->get()->getResultArray();
            } else {
                $news = $newsModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('language l', 'l.id=nl.id_lang')->join('links li', 'li.id=nl.id_link')->select('n.id,n.edited_at,n.created_at,li.link,nl.id_lang')->where('n.publish', 1)->where('l.default', 1)->orderBy('n.date', 'DESC')->get()->getResultArray();
            }
            if(count($this->languages) >= 1 && !empty($news)) {
                foreach($news as $k=>$n) {
                    $news[$k]['images'] = $newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->join('language l', 'l.id=nfl.id_lang')->select('nf.name,nf.path,nfl.caption')->where('l.default', 1)->where('nf.id_news', $n['id'])->whereIn('field', array('photo', 'photos'))->orderBy('nf.field', 'ASC')->orderBy('nf.order', 'ASC')->get()->getResultArray();
                    $news[$k]['videos'] = $newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->join('language l', 'l.id=nfl.id_lang')->select('nf.name,nf.path,nfl.caption')->where('l.default', 1)->where('nf.id_news', $n['id'])->where('field', 'video')->orderBy('nf.order', 'ASC')->get()->getResultArray();
                    $news[$k]['priority'] = 0.6;
                    $news[$k]['changefreq'] = 'monthly';
                    $news[$k]['langs'] = $newsModel->db->table('news_lang nl')->join('links l', 'l.id=nl.id_link')->select('nl.id_lang,nl.edited_at,nl.created_at,l.link')->where('nl.id_news', $n['id'])->get()->getResultArray();
                }
                $list['news'] = $news;
            }
        }
        return $list;
    }
    
    private function event($count=0) {
        $list = array();
        if (is_dir(ROOTPATH . 'modules/Event') && class_exists('\Modules\Event\Controllers\EventAdmin') && class_exists('\Modules\Event\Models\EventModel') && !empty($this->moduleModel->select('id')->where('slug', 'Event')->where('publish', 1)->first())) {
            $eventModel = new \Modules\Event\Models\EventModel();
            $place_types = $eventModel->db->table('event_place_type ept')->join('event_place_type_lang eptl', 'ept.id=eptl.id_type')->join('language l', 'l.id=eptl.id_lang')->join('links li', 'li.id=eptl.id_link')->select('ept.id,ept.edited_at,ept.created_at,li.link,eptl.id_lang')->where('ept.publish', 1)->where('l.default', 1)->where('eptl.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($place_types)) {
                foreach($place_types as $k=>$t) {
                    $place_types[$k]['priority'] = 0.4;
                    $place_types[$k]['changefreq'] = 'monthly';
                    $place_types[$k]['langs'] = $eventModel->db->table('event_place_type_lang eptl')->join('links l', 'l.id=eptl.id_link')->select('eptl.id_lang,eptl.edited_at,eptl.created_at,l.link')->where('eptl.id_type', $t['id'])->get()->getResultArray();
                }
                $list['place_types'] = $place_types;
            }
            $places = $eventModel->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->join('language l', 'l.id=epl.id_lang')->join('links li', 'li.id=epl.id_link')->select('ep.id,ep.edited_at,ep.created_at,li.link,epl.id_lang')->where('ep.publish', 1)->where('l.default', 1)->where('epl.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($places)) {
                foreach($places as $k=>$p) {
                    $places[$k]['images'] = $eventModel->db->table('event_files ef')->join('event_files_lang efl', 'ef.id=efl.id_file')->join('language l', 'l.id=efl.id_lang')->select('ef.name,ef.path,efl.caption')->where('l.default', 1)->where('ef.id_event', $p['id'])->whereIn('field', array('place_photo', 'place_photos'))->orderBy('ef.field', 'ASC')->get()->getResultArray();
                    $places[$k]['priority'] = 0.4;
                    $places[$k]['changefreq'] = 'monthly';
                    $places[$k]['langs'] = $eventModel->db->table('event_place_lang epl')->join('links l', 'l.id=epl.id_link')->select('epl.id_lang,epl.edited_at,epl.created_at,l.link')->where('epl.id_place', $p['id'])->get()->getResultArray();
                }
                $list['places'] = $places;
            }
            $events = $eventModel->db->table('event e')->join('event_lang el', 'e.id=el.id_event')->join('language l', 'l.id=el.id_lang')->join('links li', 'li.id=el.id_link')->select('e.id,e.edited_at,e.created_at,li.link,el.id_lang')->where('e.publish', 1)->where('l.default', 1)->where('el.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($events)) {
                foreach($events as $k=>$e) {
                    $events[$k]['images'] = $eventModel->db->table('event_files ef')->join('event_files_lang efl', 'ef.id=efl.id_file')->join('language l', 'l.id=efl.id_lang')->select('ef.name,ef.path,efl.caption')->where('l.default', 1)->where('ef.id_event', $e['id'])->whereIn('field', array('photo', 'photos'))->orderBy('ef.field', 'ASC')->get()->getResultArray();
                    $events[$k]['priority'] = 0.4;
                    $events[$k]['changefreq'] = 'monthly';
                    $events[$k]['langs'] = $eventModel->db->table('event_lang el')->join('links l', 'l.id=el.id_link')->select('el.id_lang,el.edited_at,el.created_at,l.link')->where('el.id_event', $e['id'])->get()->getResultArray();
                }
                $list['events'] = $events;
            }
        }
        return $list;
    }
    
    private function catalog($count=0) {
        $list = array();
        if (is_dir(ROOTPATH . 'modules/Catalog') && class_exists('\Modules\Catalog\Controllers\CatalogAdmin') && class_exists('\Modules\Catalog\Models\CatalogModel') && !empty($this->moduleModel->select('id')->where('slug', 'Catalog')->where('publish', 1)->first())) {
            $catalogModel = new \Modules\Catalog\Models\CatalogModel();
            $catalog = $catalogModel->db->table('catalog c')->join('catalog_lang cl', 'c.id=cl.id_catalog')->join('language l', 'l.id=cl.id_lang')->join('links li', 'li.id=cl.id_link')->select('c.id,c.edited_at,c.created_at,li.link,cl.id_lang')->where('c.publish', 1)->where('l.default', 1)->where('cl.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($catalog)) {
                foreach($catalog as $k=>$c) {
                    $catalog[$k]['images'] = $catalogModel->db->table('catalog_files cf')->join('catalog_files_lang cfl', 'cf.id=cfl.id_file')->join('language l', 'l.id=cfl.id_lang')->select('cf.name,cf.path,cfl.caption')->where('l.default', 1)->where('cf.id_catalog', $c['id'])->whereIn('field', array('photo', 'photos'))->orderBy('cf.field', 'ASC')->get()->getResultArray();
                    $catalog[$k]['priority'] = 0.4;
                    $catalog[$k]['changefreq'] = 'monthly';
                    $catalog[$k]['langs'] = $catalogModel->db->table('catalog_lang cl')->join('links l', 'l.id=cl.id_link')->select('cl.id_lang,cl.edited_at,cl.created_at,l.link')->where('cl.id_catalog', $c['id'])->get()->getResultArray();
                }
                $list['catalog'] = $catalog;
            }
        }
        return $list;
    }
    
    private function cinema($count=0) {
        $list = array();
        if (is_dir(ROOTPATH . 'modules/Cinema') && class_exists('\Modules\Cinema\Controllers\CinemaAdmin') && class_exists('\Modules\Cinema\Models\CinemaMovieModel') && !empty($this->moduleModel->select('id')->where('slug', 'Cinema')->where('publish', 1)->first())) {
            $cinemaModel = new \Modules\Cinema\Models\CinemaMovieModel();
            $movies = $cinemaModel->db->table('cinema_movie cm')->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')->join('language l', 'l.id=cml.id_lang')->join('links li', 'li.id=cml.id_link')->select('cm.id,cm.edited_at,cm.created_at,li.link,cml.id_lang')->where('cm.publish', 1)->where('l.default', 1)->where('cml.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($movies)) {
                foreach($movies as $k=>$m) {
                    $movies[$k]['images'] = $cinemaModel->db->table('cinema_files cf')->join('cinema_files_lang cfl', 'cf.id=cfl.id_file')->join('language l', 'l.id=cfl.id_lang')->select('cf.name,cf.path,cfl.caption')->where('l.default', 1)->where('cf.id_cinema', $m['id'])->whereIn('field', array('movie_poster'))->orderBy('cf.field', 'ASC')->get()->getResultArray();
                    $movies[$k]['priority'] = 0.4;
                    $movies[$k]['changefreq'] = 'monthly';
                    $movies[$k]['langs'] = $cinemaModel->db->table('cinema_movie_lang cml')->join('links l', 'l.id=cml.id_link')->select('cml.id_lang,cml.edited_at,cml.created_at,l.link')->where('cml.id_movie', $m['id'])->get()->getResultArray();
                }
                $list['movies'] = $movies;
            }
        }
        return $list;
    }
    
    private function foto($count=0) {
        $list = array();
        if (is_dir(ROOTPATH . 'modules/Foto') && class_exists('\Modules\Foto\Controllers\FotoAdmin') && class_exists('\Modules\Foto\Models\FotoGalleryModel') && !empty($this->moduleModel->select('id')->where('slug', 'Foto')->where('publish', 1)->first())) {
            $galleryModel = new \Modules\Foto\Models\FotoGalleryModel();
            $galleries = $galleryModel->db->table('foto_gallery f')->join('foto_gallery_lang fl', 'f.id=fl.id_gallery')->join('language l', 'l.id=fl.id_lang')->join('links li', 'li.id=fl.id_link')->select('f.id,f.edited_at,f.created_at,li.link,fl.id_lang')->where('f.publish', 1)->where('l.default', 1)->where('fl.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($galleries)) {
                foreach($galleries as $k=>$g) {
                    $galleries[$k]['images'] = $galleryModel->db->table('foto_gallery_files cf')->join('foto_gallery_files_lang cfl', 'cf.id=cfl.id_file')->join('language l', 'l.id=cfl.id_lang')->select('cf.name,cf.path,cfl.caption')->where('l.default', 1)->where('cf.id_gallery', $g['id'])->orderBy('cf.main', 'DESC')->get()->getResultArray();
                    $galleries[$k]['priority'] = 0.4;
                    $galleries[$k]['changefreq'] = 'monthly';
                    $galleries[$k]['langs'] = $galleryModel->db->table('foto_gallery_lang fl')->join('links l', 'l.id=fl.id_link')->select('fl.id_lang,fl.edited_at,fl.created_at,l.link')->where('fl.id_gallery', $g['id'])->get()->getResultArray();
                }
                $list['foto'] = $galleries;
            }
        }
        return $list;
    }
    
    private function flavors($count=0) {
        $list = array();
        if (is_dir(ROOTPATH . 'modules/Flavors') && class_exists('\Modules\Flavors\Controllers\FlavorsAdmin') && class_exists('\Modules\Flavors\Models\FlavorsRestaurantsModel') && !empty($this->moduleModel->select('id')->where('slug', 'Flavors')->where('publish', 1)->first())) {
            $flavorModel = new \Modules\Flavors\Models\FlavorsRestaurantsModel();
            $categories = $flavorModel->db->table('flavors_categories c')->join('flavors_categories_lang cl', 'c.id=cl.id_category')->join('language l', 'l.id=cl.id_lang')->join('links li', 'li.id=cl.id_link')->select('c.id,c.edited_at,c.created_at,li.link,cl.id_lang')->where('c.publish', 1)->where('l.default', 1)->where('cl.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($categories)) {
                foreach($categories as $k=>$c) {
                    $categories[$k]['priority'] = 0.4;
                    $categories[$k]['changefreq'] = 'monthly';
                    $categories[$k]['langs'] = $flavorModel->db->table('flavors_categories_lang cl')->join('links l', 'l.id=cl.id_link')->select('cl.id_lang,cl.edited_at,cl.created_at,l.link')->where('cl.id_category', $c['id'])->get()->getResultArray();
                }
                $list['categories'] = $categories;
            }
		
            $cuisines = $flavorModel->db->table('flavors_cuisine  c')->join('flavors_cuisine_lang cl', 'c.id=cl.id_cuisine')->join('language l', 'l.id=cl.id_lang')->join('links li', 'li.id=cl.id_link')->select('c.id,c.edited_at,c.created_at,li.link,cl.id_lang')->where('c.publish', 1)->where('l.default', 1)->where('cl.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($cuisines)) {
                foreach($cuisines as $k=>$c) {
                    $cuisines[$k]['priority'] = 0.4;
                    $cuisines[$k]['changefreq'] = 'monthly';
                    $cuisines[$k]['langs'] = $flavorModel->db->table('flavors_cuisine_lang cl')->join('links l', 'l.id=cl.id_link')->select('cl.id_lang,cl.edited_at,cl.created_at,l.link')->where('cl.id_cuisine', $c['id'])->get()->getResultArray();
                }
                $list['cuisines'] = $cuisines;
            }
		
            $restaurants = $flavorModel->db->table('flavors_restaurant fr')->join('flavors_restaurant_lang frl', 'fr.id=frl.id_restaurant')->join('language l', 'l.id=frl.id_lang')->join('links li', 'li.id=frl.id_link')->select('fr.id,fr.edited_at,fr.created_at,li.link,frl.id_lang')->where('fr.publish', 1)->where('l.default', 1)->where('frl.id_link !=', 0)->get()->getResultArray();
            if(count($this->languages) >= 1 && !empty($restaurants)) {
                foreach($restaurants as $k=>$r) {
                    $restaurants[$k]['images'] = $flavorModel->db->table('flavors_restaurant_files rf')->join('flavors_restaurant_files_lang rfl', 'rf.id=rfl.id_file')->join('language l', 'l.id=rfl.id_lang')->select('rf.name,rf.path,rfl.caption')->where('l.default', 1)->where('rf.id_restaurant', $r['id'])->orderBy('rf.field', 'ASC')->get()->getResultArray();
                    $restaurants[$k]['priority'] = 0.4;
                    $restaurants[$k]['changefreq'] = 'monthly';
                    $restaurants[$k]['langs'] = $flavorModel->db->table('flavors_restaurant_lang frl')->join('links l', 'l.id=frl.id_link')->select('frl.id_lang,l.link')->where('frl.id_restaurant', $r['id'])->get()->getResultArray();
                }
                $list['restaurants'] = $restaurants;
            }
        }
        return $list;
    }
}