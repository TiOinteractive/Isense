<?php

namespace Modules\Cinema\Controllers;
use App\Controllers\BaseController;
use Modules\Cinema\Models\CinemaCalendarModel;
use Modules\Cinema\Models\CinemaMovieModel;
use Modules\Cinema\Models\CinemaGenreModel;
use Modules\Cinema\Models\CinemaTypeModel;
use Modules\Cinema\Models\CinemaAnnouncementModel;
use App\Libraries\Breadcrumb;
use Shuchkin\SimpleXLSX;
use Shuchkin\SimpleXLS;
use Laravie\Parser\Xml\Reader;
use Laravie\Parser\Xml\Document;


class CinemaAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->cinemaCalendarModel = new CinemaCalendarModel();
        $this->cinemaMovieModel = new CinemaMovieModel();
        $this->cinemaGenreModel = new CinemaGenreModel();
        $this->cinemaTypeModel = new CinemaTypeModel();
        $this->cinemaAnnouncementModel = new CinemaAnnouncementModel();
    }

    public function index($action = '', $id = 0, $id2 = 0) {
        $calendar = array();
        $movie = array();
        $genre = array();
        $type = array();
        $announcement = array();
        $page = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG'));
        if(in_array($action, array('movie', 'edit-movie', 'add-movie', 'save-movie', 'add-movies'))) {
            $id_content = $id;
            $id = $id2;
            $page = $this->cinemaMovieModel->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
            if(!empty($page)) {
                $page_info = $this->cinemaMovieModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('p.id,pl.name,p.re_id')->where('p.id', $page['id_page'])->where('l.default', 1)->get()->getRowArray();
            }
            if(!empty($page_info)) {
                $this->breadcrumb->add(lang('Admin.page.PagesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page');
                $this->breadcrumb->add(lang('Admin.page.PageContent') . ': ' . $page_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $page['id_page'] . '/' . $id_content);
            }
        } else {
            $this->breadcrumb->add(lang('Cinema.CinemaCalendar'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema');
        }
        switch ($action) {
            case 'edit-announcement':
            case 'add-announcement':
            case 'save-announcement':
                if(!empty($id)) {
                    $movie = $this->cinemaMovieModel->join('cinema_movie_lang cml', 'cinema_movie.id=cml.id_movie')->select('cinema_movie.id,cml.title')->where('cinema_movie.id', $id)->where('cinema_movie.publish', 1)->where('cml.id_lang', $this->id_lang)->first();
                }
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    
                    $validation->setRules([
                        'date' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.CinemaDateError')
                            ],
                        ],
                        'id_movie' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.MovieError')
                            ],
                        ],
                        'place' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.CinemaPlaceError')
                            ],
                        ],
                    ]);
                    if (!$validation->run($post)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                            
                    if (empty($errors)) {
                        $result = $this->cinemaAnnouncementModel->saveAnnouncement($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('cinema_announcement', array(
                            'status' => true,
                            'msg' => ($id ? lang('Cinema.AnnouncementEditSuccess') : lang('Cinema.AnnouncementAddSuccess')) . '!',
                            'statistics' => $this->cinemaAnnouncementModel->statistics
                        ));
                        HistoryStat($id,'','cinema_announcement','Cinema',$id ? lang('Cinema.AnnouncementEditSuccess') : lang('Cinema.AnnouncementAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit-announcement/' . $this->cinemaAnnouncementModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Cinema.AnnouncementEditError') : lang('Cinema.AnnouncementAddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $announcement = $post;
                } else {
                    $flashdata = $this->session->getFlashdata('cinema_announcement');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Cinema.NewAnnouncementAdd') . (!empty($movie['title']) ? ': ' . $movie['title'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit-announcement/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Cinema.NewAnnouncementAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/add-announcement');
                }
                $breadcrumb = $this->breadcrumb->render();
                $places = $this->cinemaCalendarModel->getPlacesForList($this->id_lang);
                $movies = $this->cinemaMovieModel->getMoviesForList($this->id_lang);                
                echo view('Modules\Cinema\Views\admin\announcement_add', array('action' => $action, 'announcement' => $announcement, 'movie' => $movie, 'places' => $places, 'movies' => $movies, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'announcement':
                $this->breadcrumb->add(lang('Cinema.AnnouncementList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/announcement');
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->cinemaAnnouncementModel->join('cinema_movie cm', 'cm.id=cinema_announcement.id_movie')->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')->join('cinema_files cf', 'cf.id_cinema=cm.id AND field="movie_poster"', 'left')->join('event_place_lang epl', 'epl.id_place=cinema_announcement.id_place', 'left')->select('cinema_announcement.id,cinema_announcement.id_movie,cinema_announcement.date,cml.title,cf.path,epl.name as place')->where('cml.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'title': 
                                if(!empty($value)) {
                                    $query->like('cml.title', $value);
                                }
                                break;
                            case 'movie':
                                if(!empty($value)) {
                                    $query->where('cinema_announcement.id_movie', $value);
                                }
                                break;
                            case 'place':
                                if(!empty($value)) {
                                    $query->where('cinema_announcement.id_place', $value);
                                }
                                break;
                            case 'type':
                                if(!empty($value)) {
                                    $query->join('cinema_movie_types cmt', 'cmt.id_movie=cm.id');
                                    $query->where('cmt.id_type', $value);
                                }
                                break;
                            case 'genre':
                                if(!empty($value)) {
                                    $query->join('cinema_movie_genres cmg', 'cmg.id_movie=cm.id');
                                    $query->where('cmg.id_genre', $value);
                                }
                                break;
                            case 'date':
                                if(!empty($value)) {
                                    $tmp = explode('-', $value);
                                    $date_start = !empty($tmp) && !empty($tmp[0]) ? date('Y-m-d', strtotime($tmp[0])) : '';
                                    $date_end = !empty($tmp) && !empty($tmp[1]) ? date('Y-m-d', strtotime($tmp[1])) : '';
                                    if(!empty($date_start)) {
                                        $query->where('cinema_announcement.date >=', $date_start);
                                    }
                                    if(!empty($date_end)) {
                                        $query->where('cinema_announcement.date <=', $date_end);
                                    }
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'date;desc';
                }
                switch($get['order']) {
                    case 'created_at;desc': $query->orderBy('cinema_announcement.created_at', 'DESC');
                        break;
                    case 'created_at;asc': $query->orderBy('cinema_announcement.created_at', 'ASC');
                        break;
                    case 'date;desc': $query->orderBy('cinema_announcement.date', 'DESC');
                        break;
                    case 'title;desc': $query->orderBy('cml.title', 'DESC');
                        break;
                    case 'title;asc': $query->orderBy('cml.title', 'ASC');
                        break;
                    case 'date;desc': 
                    default: 
                        $query->orderBy('cinema_announcement.date', 'ASC');
                        break;
                }
                $announcements = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if(!empty($announcements)) {
                    foreach($announcements as $k=>$a) {
                        $announcements[$k]['types'] = $this->cinemaCalendarModel->db->table('cinema_movie_types cmp')->join('cinema_type_lang ctl', 'cmp.id_type=ctl.id_type')->select('cmp.id_type,ctl.name')->where('cmp.id_movie', $a['id_movie'])->where('ctl.id_lang', $this->id_lang)->orderBy('ctl.name ASC')->get()->getResultArray();
                        $announcements[$k]['genres'] = $this->cinemaCalendarModel->db->table('cinema_movie_genres cmg')->join('cinema_genre_lang cgl', 'cmg.id_genre=cgl.id_genre')->select('cmg.id_genre,cgl.name')->where('cmg.id_movie', $a['id_movie'])->where('cgl.id_lang', $this->id_lang)->orderBy('cgl.name ASC')->get()->getResultArray();
                    }
                }
                $order_list = array(
                    array('field' => '', 'name' => lang('Cinema.sort.Default')),
                    array('field' => 'title;asc', 'name' => lang('Cinema.sort.TitleAsc')),
                    array('field' => 'title;desc', 'name' => lang('Cinema.sort.TitleDesc')),
                    array('field' => 'date;asc', 'name' => lang('Cinema.sort.DateAsc')),
                    array('field' => 'date;desc', 'name' => lang('Cinema.sort.DateDesc')),
                    array('field' => 'created_at;asc', 'name' => lang('Cinema.sort.AddDateAsc')),
                    array('field' => 'created_at;desc', 'name' => lang('Cinema.sort.AddDateDesc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                $genres = $this->cinemaGenreModel->getGenresForList($this->id_lang);
                $types = $this->cinemaTypeModel->getTypesForList($this->id_lang);
                $places = $this->cinemaCalendarModel->getPlacesForList($this->id_lang);
                if(!empty($get['movie'])) {
                    $movie = $this->cinemaMovieModel->join('cinema_movie_lang cml', 'cinema_movie.id=cml.id_movie')->select('cinema_movie.id,cml.title')->where('cinema_movie.id', $get['movie'])->where('cinema_movie.publish', 1)->where('cml.id_lang', $this->id_lang)->first();
                }
                echo view('Modules\Cinema\Views\admin\announcement_list', array(
                    'announcements' => $announcements, 
                    'types' => $types, 
                    'genres' => $genres, 
                    'places' => $places, 
                    'movie' => $movie, 
                    'filters' => $get, 
                    'breadcrumbs' => $breadcrumb, 
                    'order_list' => $order_list, 
                    'on_page_list'=>$on_page_list,
                    'pager' => $this->cinemaAnnouncementModel->pager
                ));
                break;
            case 'edit-type':
                $type = $this->cinemaTypeModel->getTypeById($id, $this->id_lang);
            case 'add-type':
            case 'save-type':
                $this->breadcrumb->add(lang('Cinema.TypeList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/type');
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    if (!empty($post['lang'])) {
                        foreach ($post['lang'] as $id_lang => $lang) {
                            $validation->reset();
                            $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                            $validation->setRules([
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Cinema.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->cinemaTypeModel->saveType($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('cinema_type', array(
                            'status' => true,
                            'msg' => ($id ? lang('Cinema.TypeEditSuccess') : lang('Cinema.TypeAddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','cinema_type','Cinema',$id ? lang('Cinema.TypeEditSuccess') : lang('Cinema.TypeAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit-type/' . $this->cinemaTypeModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Cinema.TypeEditError') : lang('Cinema.TypeAddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $type = $post;
                    $type['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('cinema_type');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Cinema.TypeEdit') . (!empty($event['name']) ? ': ' . $event['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit-type/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Cinema.NewTypeAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/add-type');
                }
                $direct_links = array();
                $links = $this->cinemaTypeModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_movie')->get()->getResultArray();
                if(!empty($links)) {
                    foreach($links as $l) {
                        $direct_links[$l['id_lang']] = $l['value'];
                    }
                }
                if(!empty($this->languages)) {
                    foreach($this->languages as $lang) {
                        if(empty($direct_links[$lang['id']])) {
                            $direct_links[$lang['id']] = 'movie';
                        }
                    }
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Cinema\Views\admin\type_add', array('action' => $action, 'type' => $type, 'direct_links' => $direct_links, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'type':
                $this->breadcrumb->add(lang('Cinema.TypeList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/type');
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->cinemaTypeModel->join('cinema_type_lang ctl', 'cinema_type.id=ctl.id_type')->select('cinema_type.id,cinema_type.publish,ctl.name')->where('ctl.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('ctl.name', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('cinema_type.publish', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'name;asc';
                }
                switch($get['order']) {
                    case 'created_at;asc': $query->orderBy('cinema_type.created_at', 'ASC');
                        break;
                    case 'created_at;desc': $query->orderBy('cinema_type.created_at', 'DESC');
                        break;
                    case 'name;desc': $query->orderBy('ctl.name', 'DESC');
                        break;
                    case 'name;asc': 
                    default: 
                        $query->orderBy('ctl.name', 'ASC');
                        break;
                }
                $types = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                $order_list = array(
                    array('field' => '', 'name' => lang('Cinema.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Cinema.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Cinema.sort.NameDesc')),
                    array('field' => 'created_at;asc', 'name' => lang('Cinema.sort.AddDateAsc')),
                    array('field' => 'created_at;desc', 'name' => lang('Cinema.sort.AddDateDesc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                echo view('Modules\Cinema\Views\admin\type_list', array(
                    'types' => $types, 
                    'filters' => $get, 
                    'breadcrumbs' => $breadcrumb, 
                    'order_list' => $order_list, 
                    'on_page_list'=>$on_page_list,
                    'pager' => $this->cinemaTypeModel->pager
                ));
                break;
            case 'edit-genre':
                $genre = $this->cinemaGenreModel->getGenreById($id, $this->id_lang);
            case 'add-genre':
            case 'save-genre':
                $this->breadcrumb->add(lang('Cinema.GenreList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/genre');
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    if (!empty($post['lang'])) {
                        foreach ($post['lang'] as $id_lang => $lang) {
                            $validation->reset();
                            $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                            $validation->setRules([
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Cinema.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->cinemaGenreModel->saveGenre($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('cinema_genre', array(
                            'status' => true,
                            'msg' => ($id ? lang('Cinema.GenreEditSuccess') : lang('Cinema.GenreAddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','cinema_genre','Cinema',$id ? lang('Cinema.GenreEditSuccess') : lang('Cinema.GenreAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit-genre/' . $this->cinemaGenreModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Cinema.GenreEditError') : lang('Cinema.GenreAddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $genre = $post;
                    $genre['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('cinema_genre');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Cinema.GenreEdit') . (!empty($event['name']) ? ': ' . $event['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit-genre/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Cinema.NewGenreAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/add-genre');
                }
                $direct_links = array();
                $links = $this->cinemaGenreModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_movie')->get()->getResultArray();
                if(!empty($links)) {
                    foreach($links as $l) {
                        $direct_links[$l['id_lang']] = $l['value'];
                    }
                }
                if(!empty($this->languages)) {
                    foreach($this->languages as $lang) {
                        if(empty($direct_links[$lang['id']])) {
                            $direct_links[$lang['id']] = 'movie';
                        }
                    }
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Cinema\Views\admin\genre_add', array('action' => $action, 'genre' => $genre, 'direct_links' => $direct_links, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'genre':
                $this->breadcrumb->add(lang('Cinema.GenreList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/genre');
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->cinemaGenreModel->join('cinema_genre_lang cgl', 'cinema_genre.id=cgl.id_genre')->select('cinema_genre.id,cinema_genre.publish,cgl.name')->where('cgl.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('cgl.name', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('cinema_genre.publish', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'name;asc';
                }
                switch($get['order']) {
                    case 'created_at;asc': $query->orderBy('cinema_genre.created_at', 'ASC');
                        break;
                    case 'created_at;desc': $query->orderBy('cinema_genre.created_at', 'DESC');
                        break;
                    case 'name;desc': $query->orderBy('cgl.name', 'DESC');
                        break;
                    case 'name;asc': 
                    default: 
                        $query->orderBy('cgl.name', 'ASC');
                        break;
                }
                $genres = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                $order_list = array(
                    array('field' => '', 'name' => lang('Cinema.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Cinema.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Cinema.sort.NameDesc')),
                    array('field' => 'created_at;asc', 'name' => lang('Cinema.sort.AddDateAsc')),
                    array('field' => 'created_at;desc', 'name' => lang('Cinema.sort.AddDateDesc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                echo view('Modules\Cinema\Views\admin\genre_list', array(
                    'genres' => $genres, 
                    'filters' => $get, 
                    'breadcrumbs' => $breadcrumb, 
                    'order_list' => $order_list, 
                    'on_page_list'=>$on_page_list,
                    'pager' => $this->cinemaGenreModel->pager
                ));
                break;
            case 'add-movies':
                $movies = array();
                $post = $this->request->getPost();
                if (!empty($post) && !empty($post['movies'])) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                        'template' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.TemplateError')
                            ],
                        ]
                    ]);
                    if (!$validation->run($post)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                    foreach ($post['movies'] as $no=>$movie) {
                        if (!empty($movie['lang'])) {
                            foreach ($movie['lang'] as $id_lang => $lang) {
                                $validation->reset();
                                $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                                $validation->setRules([
                                    'title' => [
                                        'rules' => 'required',
                                        'errors' => [
                                            'required' => '<b>' . $movie['basename'] . ':</b> ' . $lang_name . lang('Cinema.TitleError')
                                        ],
                                    ]
                                ]);
                                if (!$validation->run($lang)) {
                                    $errors[] = array_merge($validation->getErrors());
                                }
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->cinemaMovieModel->saveCinemaMassMovies($id_content, $post['movies'], $post['template']);
                    }
                    if ($result) {
                        $this->session->setFlashdata('cinema_movies', array(
                            'status' => true,
                            'msg' => lang('Cinema.MassMoviesAddSuccess') . '!'
                        ));
                        HistoryStat($id,'','cinema_movies','Cinema',lang('Cinema.MassMoviesAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/add-movies/' . $id_content);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => lang('Cinema.MassMoviesAddError') . '!',
                            'list' => $errors
                        );
                    }
                    $movies = $post['movies'];
                } else {
                    $flashdata = $this->session->getFlashdata('cinema_movies');
                }
                $this->breadcrumb->add(lang('Cinema.NewCinemaMassMoviesAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/add-cinema');
                $breadcrumb = $this->breadcrumb->render();
                $genres = $this->cinemaGenreModel->getGenresForList($this->id_lang);
                $templates = get_templates_by_dir('modules/Cinema/Views/user/single');
                echo view('Modules\Cinema\Views\admin\movies_add', array('action' => $action, 'id_content' => $id_content, 'movies' => $movies, 'genres' => $genres, 'templates' => $templates, 'page' => $page, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'edit-movie':
                $movie = $this->cinemaMovieModel->getCinemaMovieById($id, $this->id_lang);
            case 'add-movie':
            case 'save-movie':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                        'template' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.TemplateError')
                            ],
                        ],
                    ]);
                    if (!$validation->run($post)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                    if (!empty($post['lang'])) {
                        foreach ($post['lang'] as $id_lang => $lang) {
                            $validation->reset();
                            $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                            $validation->setRules([
                                'title' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Cinema.TitleError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->cinemaMovieModel->saveCinemaMovie($id, $id_content, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('cinema_movie', array(
                            'status' => true,
                            'msg' => ($id ? lang('Cinema.MovieEditSuccess') : lang('Cinema.MovieAddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','cinema_movie','Cinema',$id ? lang('Cinema.MovieEditSuccess') : lang('Cinema.MovieAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit-movie/' . $id_content . '/' . $this->cinemaMovieModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Cinema.MovieEditError') : lang('Cinema.MovieAddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $movie = $post;
                    $movie['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('cinema_movie');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Cinema.CinemaMovieEdit') . (!empty($movie['name']) ? ': ' . $movie['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit-movie/' . $id_content . '/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Cinema.NewCinemaMovieAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/add-movie/' . $id_content);
                }
                $breadcrumb = $this->breadcrumb->render();
                $genres = $this->cinemaGenreModel->getGenresForList($this->id_lang);
                $types = $this->cinemaTypeModel->getTypesForList($this->id_lang);
                $templates = get_templates_by_dir('modules/Cinema/Views/user/single');
                echo view('Modules\Cinema\Views\admin\movie_add', array('action' => $action, 'id_content' => $id_content, 'movie' => $movie, 'types' => $types, 'genres' => $genres, 'templates' => $templates, 'page' => $page, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'import':
                $flashdata = array();
                $this->breadcrumb->add(lang('Cinema.CalendarImport'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/import');
                $breadcrumb = $this->breadcrumb->render();
                $places = $this->cinemaCalendarModel->getPlacesForList($this->id_lang);
                echo view('Modules\Cinema\Views\admin\cinema_import', array('action' => $action, 'places' => $places, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'edit':
            case 'add':
            case 'save':
                if(!empty($id)) {
                    $movie = $this->cinemaMovieModel->join('cinema_movie_lang cml', 'cinema_movie.id=cml.id_movie')->select('cinema_movie.id,cml.title')->where('cinema_movie.id', $id)->where('cinema_movie.publish', 1)->where('cml.id_lang', $this->id_lang)->first();
                }
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation =  \Config\Services::validation();
                    $validation->setRules([
                        'date' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.CinemaDateError')
                            ],
                        ],
                        'id_movie' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.MovieError')
                            ],
                        ],
                        'id_place' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.CinemaPlaceError')
                            ],
                        ],
                        'id_type' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.TypeError')
                            ],
                        ],
                        'hour' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Cinema.CinemaPlaceError')
                            ],
                        ],
                    ]);
                    if (!$validation->run($post)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                    if (empty($errors)) {
                        $result = $this->cinemaCalendarModel->saveCalendar($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('cinema_calendar', array(
                            'status' => true,
                            'msg' => ($id ? lang('Cinema.EditSuccess') : lang('Cinema.AddSuccess')) . '!',
                            'statistics' => $this->cinemaCalendarModel->statistics
                        ));
                        HistoryStat($id,'','cinema','Cinema',$id ? lang('Cinema.EditSuccess') : lang('Cinema.AddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit/' . $this->cinemaCalendarModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Cinema.EditError') : lang('Cinema.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $calendar = $post;
                    $calendar['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('cinema_calendar');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Cinema.NewCalendarAdd') . (!empty($movie['title']) ? ': ' . $movie['title'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Cinema.NewCalendarAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/cinema/add');
                }
                $places = $this->cinemaCalendarModel->getPlacesForList($this->id_lang);
                $types = $this->cinemaTypeModel->getTypesForList($this->id_lang);
                $movies = $this->cinemaMovieModel->getMoviesForList($this->id_lang);
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Cinema\Views\admin\cinema_add', array('action' => $action, 'calendar' => $calendar, 'movie' => $movie, 'places' => $places, 'types' => $types, 'movies' => $movies, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            default :
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->cinemaCalendarModel
                        ->join('cinema_movie_lang cml', 'cml.id_movie=cinema_calendar.id_movie')
                        ->select('cinema_calendar.id,cinema_calendar.date,cinema_calendar.id_place,cinema_calendar.id_movie,cml.title,cml.original')//,epl.name as place,cf.path
                        ->where('cml.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'title': 
                                if(!empty($value)) {
                                    $query->like('cml.title', $value);
                                }
                            break;
                            case 'movie':
                                if(!empty($value)) {
                                    $query->where('cinema_calendar.id_movie', $value);
                                }
                                break;
                            case 'place':
                                if(!empty($value)) {
                                    $query->where('cinema_calendar.id_place', $value);
                                }
                            case 'type':
                                if(!empty($value)) {
                                    $query->join('cinema_calendar_types cct', 'cct.id_calendar=cinema_calendar.id');
                                    $query->where('cct.id_type', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('cinema_calendar.publish', $value);
                                }
                                break;
                            case 'date':
                                if(!empty($value)) {
                                    $tmp = explode('-', $value);
                                    $date_start = !empty($tmp) && !empty($tmp[0]) ? date('Y-m-d', strtotime($tmp[0])) : '';
                                    $date_end = !empty($tmp) && !empty($tmp[1]) ? date('Y-m-d', strtotime($tmp[1] . ' +1 day')) : '';
                                    if(!empty($date_start)) {
                                        $query->where('cinema_calendar.date >=', $date_start);
                                    }
                                    if(!empty($date_end)) {
                                        $query->where('cinema_calendar.date <', $date_end);
                                    }
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'date;desc';
                }
                switch($get['order']) {
                    case 'created_at;desc': $query->orderBy('cinema_calendar.created_at', 'DESC');
                        break;
                    case 'created_at;asc': $query->orderBy('cinema_calendar.created_at', 'ASC');
                        break;
                    case 'date;desc': $query->orderBy('cinema_calendar.date', 'DESC');
                        break;
                    case 'title;desc': $query->orderBy('cml.title', 'DESC');
                        break;
                    case 'title;asc': $query->orderBy('cml.title', 'ASC');
                        break;
                    case 'date;desc': 
                    default: 
                        $query->orderBy('cinema_calendar.date', 'ASC');
                        break;
                }
                $calendar = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if(!empty($calendar)) {
                    foreach($calendar as $k=>$c) {
                        $calendar[$k]['types'] = $this->cinemaCalendarModel->db->table('cinema_calendar_types cct')->join('cinema_type_lang ctl', 'ctl.id_type=cct.id_type')->select('cct.id_type as id,ctl.name')->where('cct.id_calendar', $c['id'])->where('ctl.id_lang', $this->id_lang)->orderBy('ctl.name', 'ASC')->get()->getResultArray();
                        $calendar[$k]['place'] = $this->cinemaCalendarModel->db->table('event_place_lang epl')->select('epl.name')->where('epl.id_place', $c['id_place'])->where('epl.id_lang', $this->id_lang)->get()->getRowArray();
                        $calendar[$k]['poster'] = $this->cinemaCalendarModel->db->table('cinema_files cf')->select('cf.path')->where('cf.id_cinema', $c['id_movie'])->where('cf.field', 'movie_poster')->get()->getRowArray();
                    }
                }
                $order_list = array(
                    array('field' => '', 'name' => lang('Cinema.sort.Default')),
                    array('field' => 'title;asc', 'name' => lang('Cinema.sort.TitleAsc')),
                    array('field' => 'title;desc', 'name' => lang('Cinema.sort.TitleDesc')),
                    array('field' => 'date;asc', 'name' => lang('Cinema.sort.DateAsc')),
                    array('field' => 'date;desc', 'name' => lang('Cinema.sort.DateDesc')),
                    array('field' => 'created_at;asc', 'name' => lang('Cinema.sort.AddDateAsc')),
                    array('field' => 'created_at;desc', 'name' => lang('Cinema.sort.AddDateDesc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                $types = $this->cinemaTypeModel->getTypesForList($this->id_lang);
                $places = $this->cinemaCalendarModel->getPlacesForList($this->id_lang);
                if(!empty($get['movie'])) {
                    $movie = $this->cinemaMovieModel->join('cinema_movie_lang cml', 'cinema_movie.id=cml.id_movie')->select('cinema_movie.id,cml.title')->where('cinema_movie.id', $get['movie'])->where('cinema_movie.publish', 1)->where('cml.id_lang', $this->id_lang)->first();
                }
                echo view('Modules\Cinema\Views\admin\cinema_list', array(
                    'calendar' => $calendar, 
                    'types' => $types, 
                    'places' => $places, 
                    'movie' => $movie, 
                    'filters' => $get, 
                    'breadcrumbs' => $breadcrumb, 
                    'order_list' => $order_list, 
                    'on_page_list'=>$on_page_list,
                    'pager' => $this->cinemaCalendarModel->pager
                ));
                break;
        }
    }
    
    public function assets($action='') {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        switch ($action) {
            case 'import':
            case 'add-movies':
            case 'add-movie':
            case 'edit-movie':
            case 'save-movie':
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css';
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/tmpl.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/load-image.all.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/canvas-to-blob.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.blueimp-gallery.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.iframe-transport.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-process.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-image.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-audio.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-video.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-validate.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-ui.js';
                $assets['js'][] = '/adm/js/file-uploader.js';
                $assets['js'][] = '/adm/js/cinema.js';
                break;
            case 'edit':
            case 'save':
            case 'add':
            case 'add-announcement':
                $assets['js'][] = '/adm/js/cinema.js';
                break;
            default :
                break;
        }
        return $assets;
    }
    
    public function ajax($action='', $id=0, $id2=0) 
    {
        $post = $this->request->getPost();
        if(!empty($action)) {
            switch($action) {
                case 'publish-movie': 
                    return $this->publishCinemaMovie($id);
                    break;
                case 'delete-movie': 
                    return $this->deleteCinemaMovie($id);
                    break;
                case 'home-movie': 
                    return $this->homeCinemaMovie($id);
                    break;
                case 'publish-genre': 
                    return $this->publishCinemaGenre($id);
                    break;
                case 'delete-genre': 
                    return $this->deleteCinemaGenre($id);
                    break;
                case 'publish-type': 
                    return $this->publishCinemaType($id);
                    break;
                case 'delete-type': 
                    return $this->deleteCinemaType($id);
                    break;
                case 'delete': 
                    return $this->deleteCinemaCalendar($id);
                    break;
                case 'delete-announcement': 
                    return $this->deleteCinemaAnnouncement($id);
                    break;
                case 'save':
                case 'edit':
                case 'add':
                case 'save-announcement':
                case 'edit-announcement':
                case 'add-announcement':
                    if(!empty($post['action']) && $post['action'] == 'movies') {
                        return $this->searchMovies(!empty($post['search']) ? $post['search'] : '');
                    }
                    break;
                case 'add-cinema-hour': 
                    return $this->addCinemaHour($post);
                    break;
                case 'import': 
                    if(!empty($post['place'])) {
                        return $this->saveCalendar($post);
                    } else {
                        return $this->importCalendar($post);
                    }
                    break;
            }
        }
    }
    
    private function publishCinemaMovie($id) 
    {
        $movie = $this->cinemaMovieModel->select('id,publish')->where('id', $id)->first();
        if(!empty($movie)) {
            $r = $this->cinemaMovieModel->where('id', $id)->set('publish', $movie['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $movie['publish'] ? 0 : 1,
                'msg' => $movie['publish'] ? lang('Cinema.Republished') : lang('Cinema.Published')
            );
            HistoryStat($id,'','cinema_movie','Cinema',$movie['publish'] ? lang('Cinema.Republished') : lang('Cinema.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $movie['publish'],
                'msg' => lang('Cinema.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function deleteCinemaMovie($id) 
    {
        $result = $this->cinemaMovieModel->deleteCinemaMovie($id);
        HistoryStat($id,'','cinema_movie','Cinema',$result ? lang('Cinema.Removed') : lang('Cinema.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Cinema.Removed') : lang('Cinema.Error')
        ));
    }

    private function homeCinemaMovie($id) 
    {
        $movie = $this->cinemaMovieModel->select('id,home')->where('id', $id)->first();
        if(!empty($movie)) {
            $r = $this->cinemaMovieModel->where('id', $id)->set('home', $movie['home'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $movie['home'] ? 0 : 1,
                'msg' => $movie['home'] ? lang('Cinema.TurnOff') : lang('Cinema.TurnOn')
            );
            HistoryStat($id,'','cinema_movie','Cinema',$movie['home'] ? lang('Cinema.TurnOff') : lang('Cinema.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $movie['home'],
                'msg' => lang('Cinema.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function publishCinemaGenre($id) 
    {
        $genre = $this->cinemaGenreModel->select('id,publish')->where('id', $id)->first();
        if(!empty($genre)) {
            $r = $this->cinemaGenreModel->where('id', $id)->set('publish', $genre['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $genre['publish'] ? 0 : 1,
                'msg' => $genre['publish'] ? lang('Cinema.Republished') : lang('Cinema.Published')
            );
            HistoryStat($id,'','cinema_genre','Cinema',$genre['publish'] ? lang('Cinema.Republished') : lang('Cinema.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $genre['publish'],
                'msg' => lang('Cinema.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function deleteCinemaGenre($id) 
    {
        $result = $this->cinemaGenreModel->deleteGenre($id);
        HistoryStat($id,'','cinema_genre','Cinema',$result ? lang('Cinema.Removed') : lang('Cinema.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Cinema.Removed') : lang('Cinema.Error')
        ));
    }
    
    private function publishCinemaType($id) 
    {
        $type = $this->cinemaTypeModel->select('id,publish')->where('id', $id)->first();
        if(!empty($type)) {
            $r = $this->cinemaTypeModel->where('id', $id)->set('publish', $type['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $type['publish'] ? 0 : 1,
                'msg' => $type['publish'] ? lang('Cinema.Republished') : lang('Cinema.Published')
            );
            HistoryStat($id,'','cinema_type','Cinema',$type['publish'] ? lang('Cinema.Republished') : lang('Cinema.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $type['publish'],
                'msg' => lang('Cinema.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function deleteCinemaType($id) 
    {
        $result = $this->cinemaTypeModel->deleteType($id);
        HistoryStat($id,'','cinema_type','Cinema',$result ? lang('Cinema.Removed') : lang('Cinema.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Cinema.Removed') : lang('Cinema.Error')
        ));
    }
    
    private function deleteCinemaCalendar($id) 
    {
        $result = $this->cinemaCalendarModel->deleteCalendar($id);
        HistoryStat($id,'','cinema_calendar','Cinema',$result ? lang('Cinema.Removed') : lang('Cinema.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Cinema.Removed') : lang('Cinema.Error')
        ));
    }
    
    private function deleteCinemaAnnouncement($id) 
    {
        $result = $this->cinemaAnnouncementModel->deleteAnnouncement($id);
        HistoryStat($id,'','cinema_announcement','Cinema',$result ? lang('Cinema.Removed') : lang('Cinema.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Cinema.Removed') : lang('Cinema.Error')
        ));
    }

    private function searchMovies($search_text) {
        $movies = $this->cinemaMovieModel->getMoviesForList($this->id_lang, $search_text);
        $html = view('Modules\Cinema\Views\admin\announcement_movies', array('movies' => $movies, 'languages' => $this->languages, 'locale' => $this->locale));
        $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        );
        return $this->response->setJSON($response);
    }
    
    public function addCinemaHour($post) {
        $html = view('Modules\Cinema\Views\admin\cinema_hour', array('languages' => $this->languages, 'locale' => $this->locale, 'remove' => true, 'h' => !empty($post['h']) ? $post['h'] : 0));
        $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        );
        return $this->response->setJSON($response);
    }
    
    
    private function saveCalendar($post) {
        $result = false;
        $count = $post['count'] + 1;
        
        $errors = array();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'is' => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Cinema.CinemaIsError')
                ],
            ],
            'date' => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Cinema.CinemaDateError')
                ],
            ],
            'movie' => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Cinema.MovieError')
                ],
            ],
            'place' => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Cinema.CinemaPlaceError')
                ],
            ],
        ]);
        if (!$validation->run($post)) {
            $errors[] = array_merge($validation->getErrors());
        }

        if (empty($errors)) {
            $result = $this->cinemaCalendarModel->saveSingleCalendar($post);
        }
        
        $html = view('Modules\Cinema\Views\admin\cinema_import_modal_content', array('count' => $count, 'selected' => $post['selected'], 'total' => $post['total'], 'languages' => $this->languages, 'locale' => $this->locale));
        $response = array(
            'status' => true,
            'result' => $result,
            'tr' => $post['tr'],
            'html' => base64_encode(urlencode($html)),
            'total' => $post['total'],
            'selected' => $post['selected'],
            'count' => $count,
            'title' => $post['title'],
            'msg' => '',
            'errors' => $errors,
            'exist' => !empty($this->cinemaCalendarModel->statistics['exists']),
        );
        return $this->response->setJSON($response);
    }
    
    private function importCalendar($post) {
        $import_data = array();
        $types = $this->cinemaTypeModel->getTypesForList($this->id_lang);
        if(!empty($post['file']) && file_exists(WRITEPATH . 'uploads/' . $post['file'])) {
            $ext = pathinfo(WRITEPATH . 'uploads/' . $post['file'], PATHINFO_EXTENSION);
            switch($ext) {
                case 'csv':
                    $import_data = $this->importFromCSV($post, $types);
                    break;
                case 'xml':
                    $import_data = $this->importFromXML($post, $types);
                    break;
                case 'xls':
                    $import_data = $this->importFromXLS($post, $types);
                    break;
                case 'xlsx':
                    $import_data = $this->importFromXLSX($post, $types);
                    break;
            }
        }
        $ids = array();
        if(!empty($import_data)) {
            foreach($import_data as $d) {
                if(!empty($d['id_movie'])) {
                    $ids[] = $d['id_movie'];
                }
            }
        }
        $import_data = array_slice($import_data, 0, 200);
        $movies = $this->cinemaMovieModel->getMoviesForList($this->id_lang, '', $ids, 'latest');    
        $html = view('Modules\Cinema\Views\admin\cinema_import_content', array('movies' => $movies, 'types' => $types, 'languages' => $this->languages, 'locale' => $this->locale, 'import_data' => $import_data));
        $response = array(
            'status' => true,
            'html' => base64_encode(urlencode(mb_convert_encoding($html, 'UTF-8'))),
        );
        return $this->response->setJSON($response);
    }
    
    private function importFromCSV($post, $types) {
        $import_data = array();
        $rows = array();
        if (($handle = fopen(WRITEPATH . 'uploads/' . $post['file'], "r")) !== FALSE) {
            while (($data = fgetcsv($handle, null, $post['template'] == 'helios' ? ";" : ",")) !== FALSE) {
                $rows[] = $data;
            }
        }
        fclose($handle);
        $index = 0;
        $tmp = array();
        do {
            $data = !empty($rows[$index]) ? $rows[$index] : array();
            switch($post['template']) {
                case 'kzrcafe':
                case 'helios':
                    if(empty($data) || $data[1] == '') {
                        if(!empty($tmp) && !empty($tmp['repertoire'])) {
                            $current_day_of_week = date('N', strtotime($post['date']));
                            foreach($tmp['repertoire'] as $repertoire) {
                                if(!empty($repertoire['days'])) {
                                    $movie = $this->cinemaCalendarModel->findMovieByTitle($tmp['title'], $this->id_lang);
                                    $movie_types = $this->getMovieTypesFromTitle($tmp['title'], $types);
                                    foreach($repertoire['days'] as $day) {
                                        switch(trim($day)) {
                                            case 'pn': $day_of_week = 1; $day_of_week_name = 'Monday';
                                                break;
                                            case 'wt': $day_of_week = 2; $day_of_week_name = 'Tuesday';
                                                break;
                                            case 'śr': $day_of_week = 3; $day_of_week_name = 'Wednesday';
                                                break;
                                            case 'czw': $day_of_week = 4; $day_of_week_name = 'Thursday';
                                                break;
                                            case 'pt': $day_of_week = 5; $day_of_week_name = 'Friday';
                                                break;
                                            case 'sb': $day_of_week = 6; $day_of_week_name = 'Saturday';
                                                break;
                                            case 'nd': $day_of_week = 7; $day_of_week_name = 'Sunday';
                                                break;
                                        }
                                        if(!empty($day_of_week) && $current_day_of_week == $day_of_week) {
                                            $date = date('Y-m-d', strtotime($post['date']));
                                        } elseif(!empty($day_of_week_name)) {
                                            $date = date('Y-m-d', strtotime($post['date'] . ' next ' . $day_of_week_name));
                                        }
                                        if(!empty($date) && !empty($repertoire['hours'])) {
                                            foreach($repertoire['hours'] as $hour) {
                                                $import_data[] = array(
                                                    'date' => date('d.m.Y H:i', strtotime($date . ' ' . $hour)),
                                                    'title' => $tmp['title'],
                                                    'id_movie' => !empty($movie) ? $movie['id'] : 0,
                                                    'premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                    'pre-premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                    'special' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                    'surprise' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                    'types' => $movie_types,
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if(!empty($data)) {
                            $tmp = array(
                                'title' => $data[0],
                                'repertoire' => array()
                            );
                        }
                    } else {
                        $hours = array();
                        $count = 1;
                        while ($count<=100 && !empty($data[$count])) {
                            $hours[] = date('H:i', strtotime($data[$count]));
                            $count++;
                        }
                        $tmp['repertoire'][] = array(
                            'days' => explode(',', $data[0]),
                            'hours' => $hours
                        );
                    }
                    break;
                case 'multikino':
                    if($index && !empty($data[7])) {
                        $data[7] = mb_convert_encoding($data[7], 'UTF-8', 'UTF-8');
                        /*if(empty($tmp['date'])) {
                            $tmp['date'] = $post['date'];
                        }
                        if(!empty($tmp['hour_time']) && (abs($tmp['hour_time'] - strtotime($data[1])) > 6 * 60 * 60 || (substr($data[1], 0, 2) == '00' && substr($tmp['hour_time'], 0, 2) != '00'))) {
                            $tmp['date'] = date('d.m.Y', strtotime($tmp['date'] . ' + 1 day'));
                        }
                        $tmp['hour_time'] = strtotime($data[1]);*/
                        $movie = $this->cinemaCalendarModel->findMovieByTitle($data[7], $this->id_lang);
                        $movie_types = $this->getMovieTypesFromTitle($data[7], $types);
                        $import_data[] = array(
                            'date' => date('d.m.Y H:i', strtotime($data[1])),
                            'title' => $data[7],
                            'id_movie' => !empty($movie) ? $movie['id'] : 0,
                            'premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                            'pre-premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                            'special' => 0,//str_contains($data[7], '') ? 1 : 0,
                            'surprise' => 0,//str_contains($data[7], '') ? 1 : 0,
                            'types' => $movie_types,
                        );
                    }
                    break;
                case 'zorza':
                    if(!empty($data[14])) {
                        $movie = $this->cinemaCalendarModel->findMovieByTitle($data[14], $this->id_lang);
                        $movie_types = $this->getMovieTypesFromTitle($data[14], $types);
                        $import_data[] = array(
                            'date' => date('d.m.Y H:i', strtotime($data[6] . ' ' . $data[15])),
                            'title' => $data[14],
                            'id_movie' => !empty($movie) ? $movie['id'] : 0,
                            'premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                            'pre-premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                            'special' => 0,//str_contains($data[7], '') ? 1 : 0,
                            'surprise' => 0,//str_contains($data[7], '') ? 1 : 0,
                            'types' => $movie_types,
                        );
                    }
                    break;
            }
            
            $index++;
        } while ($index <= count($rows));
        return $import_data;
    }
    
    private function importFromXML($post, $types) {
        $import_data = array();
        $xml = simplexml_load_string(file_get_contents(WRITEPATH . 'uploads/' . $post['file']));
        switch($post['template']) {
            case 'helios':
                $tmp = array();
                $index = 0;
                do {
                    $data = $xml->Worksheet->Table->Row[$index];
                    if(empty($data) || is_array($data->Cell) || empty($data->Cell[1]->Data)) {
                        if(!empty($tmp) && !empty($tmp['repertoire'])) {
                            $current_day_of_week = date('N', strtotime($post['date']));
                            foreach($tmp['repertoire'] as $repertoire) {
                                if(!empty($repertoire['days'])) {
                                    $movie = $this->cinemaCalendarModel->findMovieByTitle($tmp['title'], $this->id_lang);
                                    $movie_types = $this->getMovieTypesFromTitle($tmp['title'], $types);
                                    foreach($repertoire['days'] as $day) {
                                        switch(trim($day)) {
                                            case 'pn': $day_of_week = 1; $day_of_week_name = 'Monday';
                                                break;
                                            case 'wt': $day_of_week = 2; $day_of_week_name = 'Tuesday';
                                                break;
                                            case 'śr': $day_of_week = 3; $day_of_week_name = 'Wednesday';
                                                break;
                                            case 'czw': $day_of_week = 4; $day_of_week_name = 'Thursday';
                                                break;
                                            case 'pt': $day_of_week = 5; $day_of_week_name = 'Friday';
                                                break;
                                            case 'sb': $day_of_week = 6; $day_of_week_name = 'Saturday';
                                                break;
                                            case 'nd': $day_of_week = 7; $day_of_week_name = 'Sunday';
                                                break;
                                        }
                                        if(!empty($day_of_week) && $current_day_of_week == $day_of_week) {
                                            $date = date('Y-m-d', strtotime($post['date']));
                                        } elseif(!empty($day_of_week_name)) {
                                            $date = date('Y-m-d', strtotime($post['date'] . ' next ' . $day_of_week_name));
                                        }
                                        if(!empty($date) && !empty($repertoire['hours'])) {
                                            foreach($repertoire['hours'] as $hour) {
                                                $import_data[] = array(
                                                    'date' => date('d.m.Y H:i', strtotime($date . ' ' . $hour)),
                                                    'title' => $tmp['title'],
                                                    'id_movie' => !empty($movie) ? $movie['id'] : 0,
                                                    'premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                    'pre-premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                    'special' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                    'surprise' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                    'types' => $movie_types,
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if(!empty($data)) {
                            $tmp = array(
                                'title' => is_array($data->Cell) ? $data->Cell[0]->Data : $data->Cell->Data,
                                'repertoire' => array()
                            );
                        }
                    } else {
                        $hours = array();
                        $count = 1;
                        while ($count<=100 && !empty($data->Cell[$count]->Data)) {
                            $hours[] = date('H:i', strtotime($data->Cell[$count]->Data));
                            $count++;
                        }

                        $tmp['repertoire'][] = array(
                            'days' => explode(',', $data->Cell[0]->Data),
                            'hours' => $hours
                        );
                    }
                    $index++;
                } while ($index <= count($xml->Worksheet->Table->Row));
                break;
            case 'zorza':
                $tmp = array();
                foreach($xml->Group as $group) {
                    if(!empty($group->Group)) {
                        foreach($group->Group as $data) {
                            if(!empty($data->Details)) {
                                foreach($data->Details as $details) {
                                    $movie = $this->cinemaCalendarModel->findMovieByTitle($details->Section->Field[0]->Value, $this->id_lang);
                                    $movie_types = $this->getMovieTypesFromTitle($details->Section->Field[0]->Value, $types);
                                    $import_data[] = array(
                                        'date' => date('d.m.Y H:i', strtotime($details->Section->Field[1]->Value)),
                                        'title' => $details->Section->Field[0]->Value,
                                        'id_movie' => !empty($movie) ? $movie['id'] : 0,
                                        'premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                        'pre-premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                        'special' => 0,//str_contains($data[7], '') ? 1 : 0,
                                        'surprise' => 0,//str_contains($data[7], '') ? 1 : 0,
                                        'types' => $movie_types,
                                    );
                                }
                            }
                        }
                    }
                }
                break;
        }
        return $import_data;
    }
    
    private function importFromXLS($post, $types) {
        $import_data = array();
        if ( $xls = SimpleXLS::parseFile(WRITEPATH . 'uploads/' . $post['file']) ) {
            $index = 0;
            $tmp = array();
            $rows = $xls->rows();
            do {
                $data = !empty($rows[$index]) ? $rows[$index] : array();
                
                switch($post['template']) {
                    case 'helios':
                        if(empty($data) || $data[1] == '') {
                            if(!empty($tmp) && !empty($tmp['repertoire'])) {
                                $current_day_of_week = date('N', strtotime($post['date']));
                                foreach($tmp['repertoire'] as $repertoire) {
                                    if(!empty($repertoire['days'])) {
                                        $movie = $this->cinemaCalendarModel->findMovieByTitle($tmp['title'], $this->id_lang);
                                        $movie_types = $this->getMovieTypesFromTitle($tmp['title'], $types);
                                        foreach($repertoire['days'] as $day) {
                                            switch(trim($day)) {
                                                case 'pn': $day_of_week = 1; $day_of_week_name = 'Monday';
                                                    break;
                                                case 'wt': $day_of_week = 2; $day_of_week_name = 'Tuesday';
                                                    break;
                                                case 'śr': $day_of_week = 3; $day_of_week_name = 'Wednesday';
                                                    break;
                                                case 'czw': $day_of_week = 4; $day_of_week_name = 'Thursday';
                                                    break;
                                                case 'pt': $day_of_week = 5; $day_of_week_name = 'Friday';
                                                    break;
                                                case 'sb': $day_of_week = 6; $day_of_week_name = 'Saturday';
                                                    break;
                                                case 'nd': $day_of_week = 7; $day_of_week_name = 'Sunday';
                                                    break;
                                            }
                                            if(!empty($day_of_week) && $current_day_of_week == $day_of_week) {
                                                $date = date('Y-m-d', strtotime($post['date']));
                                            } elseif(!empty($day_of_week_name)) {
                                                $date = date('Y-m-d', strtotime($post['date'] . ' next ' . $day_of_week_name));
                                            }
                                            if(!empty($date) && !empty($repertoire['hours'])) {
                                                foreach($repertoire['hours'] as $hour) {
                                                    $import_data[] = array(
                                                        'date' => date('d.m.Y H:i', strtotime($date . ' ' . $hour)),
                                                        'title' => $tmp['title'],
                                                        'id_movie' => !empty($movie) ? $movie['id'] : 0,
                                                        'premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                        'pre-premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                        'special' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                        'surprise' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                        'types' => $movie_types,
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if(!empty($data)) {
                                $tmp = array(
                                    'title' => $data[0],
                                    'repertoire' => array()
                                );
                            }
                        } else {
                            $hours = array();
                            $count = 1;
                            while ($count<=100 && !empty($data[$count])) {
                                $hours[] = date('H:i', strtotime($data[$count]));
                                $count++;
                            }
                            
                            $tmp['repertoire'][] = array(
                                'days' => explode(',', $data[0]),
                                'hours' => $hours
                            );
                        }
                        break;
                }
                
                $index++;
            } while ($index <= count($rows));
        } else {
            //echo SimpleXLS::parseError();
        }
        
        return $import_data;
    }
    
    private function importFromXLSX($post, $types) {
        $import_data = array();
        if ( $xlsx = SimpleXLSX::parse(WRITEPATH . 'uploads/' . $post['file']) ) {
            $index = 0;
            $tmp = array();
            $rows = $xlsx->rows();
            do {
                $data = !empty($rows[$index]) ? $rows[$index] : array();
                switch($post['template']) {
                    case 'kzrcafe':
                        if(empty($data) || $data[1] == '') {
                            if(!empty($tmp) && !empty($tmp['repertoire'])) {
                                $current_day_of_week = date('N', strtotime($post['date']));
                                foreach($tmp['repertoire'] as $repertoire) {
                                    if(!empty($repertoire['days'])) {
                                        $movie = $this->cinemaCalendarModel->findMovieByTitle($tmp['title'], $this->id_lang);
                                        $movie_types = $this->getMovieTypesFromTitle($tmp['title'], $types);
                                        foreach($repertoire['days'] as $day) {
                                            switch(trim($day)) {
                                                case 'pn': $day_of_week = 1; $day_of_week_name = 'Monday';
                                                    break;
                                                case 'wt': $day_of_week = 2; $day_of_week_name = 'Tuesday';
                                                    break;
                                                case 'śr': $day_of_week = 3; $day_of_week_name = 'Wednesday';
                                                    break;
                                                case 'czw': $day_of_week = 4; $day_of_week_name = 'Thursday';
                                                    break;
                                                case 'pt': $day_of_week = 5; $day_of_week_name = 'Friday';
                                                    break;
                                                case 'sb': $day_of_week = 6; $day_of_week_name = 'Saturday';
                                                    break;
                                                case 'nd': $day_of_week = 7; $day_of_week_name = 'Sunday';
                                                    break;
                                            }
                                            if(!empty($day_of_week) && $current_day_of_week == $day_of_week) {
                                                $date = date('Y-m-d', strtotime($post['date']));
                                            } elseif(!empty($day_of_week_name)) {
                                                $date = date('Y-m-d', strtotime($post['date'] . ' next ' . $day_of_week_name));
                                            }
                                            if(!empty($date) && !empty($repertoire['hours'])) {
                                                foreach($repertoire['hours'] as $hour) {
                                                    $import_data[] = array(
                                                        'date' => date('d.m.Y H:i', strtotime($date . ' ' . $hour)),
                                                        'title' => $tmp['title'],
                                                        'id_movie' => !empty($movie) ? $movie['id'] : 0,
                                                        'premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                        'pre-premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                        'special' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                        'surprise' => 0,//str_contains($data[7], '') ? 1 : 0,
                                                        'types' => $movie_types,
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if(!empty($data)) {
                                $tmp = array(
                                    'title' => $data[0],
                                    'repertoire' => array()
                                );
                            }
                        } else {
                            $hours = array();
                            $count = 1;
                            while ($count<=100 && !empty($data[$count])) {
                                $hours[] = date('H:i', strtotime($data[$count]));
                                $count++;
                            }
                            
                            $tmp['repertoire'][] = array(
                                'days' => explode(',', $data[0]),
                                'hours' => $hours
                            );
                        }
                        break;
                    case 'multikino':
                        if($index && !empty($data[7])) {
                            /*if(empty($tmp['date'])) {
                                $tmp['date'] = $post['date'];
                            }
                            if(!empty($tmp['hour_time']) && abs($tmp['hour_time'] - strtotime($data[1])) > 4 * 60 * 60) {
                                $tmp['date'] = date('d.m.Y', strtotime($tmp['date'] . ' + 1 day'));
                            }
                            $tmp['hour_time'] = strtotime($data[1]);*/
                            $movie = $this->cinemaCalendarModel->findMovieByTitle($data[7], $this->id_lang);
                            $movie_types = $this->getMovieTypesFromTitle($data[7], $types);
                            $import_data[] = array(
                                'date' => date('d.m.Y H:i', strtotime($data[1])),
                                'title' => $data[7],
                                'id_movie' => !empty($movie) ? $movie['id'] : 0,
                                'premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                'pre-premiere' => 0,//str_contains($data[7], '') ? 1 : 0,
                                'special' => 0,//str_contains($data[7], '') ? 1 : 0,
                                'surprise' => 0,//str_contains($data[7], '') ? 1 : 0,
                                'types' => $movie_types,
                            );
                        }
                        break;
                }
                $index++;
            } while($index <= count($rows));
        } else {
            //echo SimpleXLSX::parseError();
        }
        return $import_data;
    }
    
    private function getMovieTypesFromTitle($title, $types) {
        $movie_types = array();
        $title = str_replace(array('/', '+', '-'), ' ', $title);
        $title = preg_replace('/[^A-Za-z0-9\-ĄąĆćĘęŁłŃńÓóŚsŻżŹź]/', ' ', $title);
        $title = trim(preg_replace('/\s+/', ' ', $title));
        $tmp_title = explode(' ', strtolower($title));
        if(!empty($types)) {
            foreach($types as $type) {
                $slugs = explode(PHP_EOL, $type['slugs']);
                if(!empty($slugs)) {
                    foreach($slugs as $slug) {
                        if(array_search(trim(strtolower($slug)), $tmp_title)) {
                            $movie_types[] = $type['id'];
                            break;
                        }
                    }
                } elseif(array_search(strtolower($type['name']), $tmp_title)) {
                    $movie_types[] = $type['id'];
                }
            }
        }
        return $movie_types;
    }
    
    public function getFileUploadHtml($post, $data) {
        $genres = $this->cinemaGenreModel->getGenresForList($this->id_lang);
        $html = view('Modules\Cinema\Views\admin\upload_' . (!empty($post['option']) ? $post['option'] : 'file'), array('genres' => $genres, 'file' => $data, 'field' => $post['field'], 'multi' => !empty($post['multi']) ? $post['multi'] : false, 'no' => uniqid(), 'languages' => $this->languages));
        return $html;
    }
    
    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        switch ($slug) {
            case 'home':
                $templates = get_templates_by_dir('modules/Cinema/Views/user/home');
                return array(
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Cinema\Views\admin\home_config'
                );
                break;
            case 'announcement':
                $templates = get_templates_by_dir('modules/Cinema/Views/user/announcement');
                return array(
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Cinema\Views\admin\calendar_config'
                );
                break;
            case 'calendar':
                $templates = get_templates_by_dir('modules/Cinema/Views/user/calendar');
                return array(
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Cinema\Views\admin\calendar_config'
                );
                break;
            case 'movies':
                $get = $this->request->getGet();
                $query = $this->cinemaMovieModel->join('cinema_movie_lang cml', 'cml.id_movie=cinema_movie.id')->join('cinema_files cf', 'cf.id_cinema=cinema_movie.id AND cf.field="movie_poster"', 'left')->select('cinema_movie.id,cinema_movie.publish,cml.title,cf.path');
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'title': 
                                if(!empty($value)) {
                                    $query->like('cml.title', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('cinema_movie.publish', $value);
                                }
                                break;
                            case 'type':
                                if(!empty($value)) {
                                    $query->join('cinema_movie_types cmt', 'cmt.id_movie=cinema_movie.id');
                                    $query->where('cmt.id_type', $value);
                                }
                                break;
                            case 'genre':
                                if(!empty($value)) {
                                    $query->join('cinema_movie_genres cmg', 'cmg.id_movie=cinema_movie.id');
                                    $query->where('cmg.id_genre', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'created_at;desc';
                }
                switch($get['order']) {
                    case 'created_at;desc': $query->orderBy('cinema_movie.created_at', 'DESC');
                        break;
                    case 'title;desc': $query->orderBy('cml.title', 'DESC');
                        break;
                    case 'title;asc': $query->orderBy('cml.title', 'ASC');
                        break;
                    case 'created_at;desc': 
                    default: 
                        $query->orderBy('cinema_movie.created_at', 'ASC');
                        break;
                }
                $movies = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if(!empty($movies)) {
                    foreach($movies as $k=>$m) {
                        $movies[$k]['types'] = $this->cinemaMovieModel->db->table('cinema_movie_types cmp')->join('cinema_type_lang ctl', 'cmp.id_type=ctl.id_type')->select('cmp.id_type,ctl.name')->where('cmp.id_movie', $m['id'])->where('ctl.id_lang', $this->id_lang)->orderBy('ctl.name ASC')->get()->getResultArray();
                        $movies[$k]['genres'] = $this->cinemaMovieModel->db->table('cinema_movie_genres cmg')->join('cinema_genre_lang cgl', 'cmg.id_genre=cgl.id_genre')->select('cmg.id_genre,cgl.name')->where('cmg.id_movie', $m['id'])->where('cgl.id_lang', $this->id_lang)->orderBy('cgl.name ASC')->get()->getResultArray();
                        $count = $this->cinemaMovieModel->db->table('cinema_movie_lang cml')->where('cml.id_movie', $m['id'])->selectSum('cml.views')->get()->getRowArray();
                        $movies[$k]['views'] = $count['views'];
                    }
                }
                $order_list = array(
                    array('field' => '', 'name' => lang('Cinema.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Cinema.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Cinema.sort.NameDesc')),
                    array('field' => 'created_at;asc', 'name' => lang('Cinema.sort.AddDateAsc')),
                    array('field' => 'created_at;desc', 'name' => lang('Cinema.sort.AddDateDesc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                $genres = $this->cinemaGenreModel->getGenresForList($this->id_lang);
                $types = $this->cinemaTypeModel->getTypesForList($this->id_lang);
                $templates = get_templates_by_dir('modules/Cinema/Views/user/movies');
                return array(
                    'movies' => $movies,
                    'filters' => $get,
                    'genres' => $genres, 
                    'types' => $types, 
                    'order_list' => $order_list,
                    'pager' => $this->cinemaMovieModel->pager,
                    'on_page_list' => $on_page_list,
                    'pc_templates' => $templates,
                    'list_view' => 'Modules\Cinema\Views\admin\movie_list',
                    'form_view' => 'Modules\Cinema\Views\admin\movies_config'
                );
                break;
        }
    }
}