<?php

namespace Modules\Event\Controllers;
use App\Controllers\BaseController;
use Modules\Event\Models\EventModel;
use Modules\Event\Models\EventPlaceModel;
use Modules\Event\Models\EventTypeModel;
use Modules\Event\Models\EventCalendarModel;
use Modules\Event\Models\EventPlaceTypeModel;
use Modules\Event\Models\EventCityModel;
use Modules\News\Models\NewsModel;
use App\Libraries\Breadcrumb;


class EventAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->eventModel = new EventModel();
        $this->eventPlaceModel = new EventPlaceModel();
        $this->eventTypeModel = new EventTypeModel();
        $this->eventCalendarModel = new EventCalendarModel();
        $this->eventPlaceTypeModel = new EventPlaceTypeModel();
        $this->eventCityModel = new EventCityModel();
    }

    public function index($action = '', $id = 0, $id2 = 0) {
        $event = array();
        $type = array();
        $place = array();
        $city = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG'));
        if(in_array($action, array('movie', 'edit', 'copy', 'add', 'save', 'add-events', 'place', 'add-place', 'edit-place', 'save-place'))) {
            $id_content = $id;
            $id = $id2;
            $page = $this->eventModel->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
            if(!empty($page)) {
                $page_info = $this->eventModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('p.id,pl.name,p.re_id')->where('p.id', $page['id_page'])->where('l.default', 1)->get()->getRowArray();
            }
            if(!empty($page_info)) {
                $this->breadcrumb->add(lang('Admin.page.PagesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page');
                $this->breadcrumb->add(lang('Admin.page.PageContent') . ': ' . $page_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $page['id_page'] . '/' . $id_content);
            }
        } else {
            $this->breadcrumb->add(lang('Event.EventList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event');
        }
        switch ($action) {
            case 'edit-place-type':
                $type = $this->eventPlaceTypeModel->getEventPlaceTypeById($id, $this->id_lang);
            case 'add-place-type':
            case 'save-place-type':
                $this->breadcrumb->add(lang('Event.EventPlaceTypeList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/place-type');
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
                                        'required' => $lang_name . lang('Event.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->eventPlaceTypeModel->saveEventPlaceType($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('event_place_type', array(
                            'status' => true,
                            'msg' => ($id ? lang('Event.PlaceTypeEditSuccess') : lang('Event.PlaceTypeAddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','event_place','Event',$id ? lang('Event.PlaceTypeEditSuccess') : lang('Event.PlaceTypeAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit-place-type/' . $this->eventPlaceTypeModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Event.PlaceTypeEditError') : lang('Event.PlaceTypeAddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $type = $post;
                    $type['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('event_place_type');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Event.EventPlaceTypeEdit') . (!empty($event['name']) ? ': ' . $event['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit-place-type/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Event.NewEventPlaceTypeAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/add-place-type');
                }
                $direct_links = array();
                $links = $this->eventPlaceTypeModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_place')->get()->getResultArray();
                if(!empty($links)) {
                    foreach($links as $l) {
                        $direct_links[$l['id_lang']] = $l['value'];
                    }
                }
                if(!empty($this->languages)) {
                    foreach($this->languages as $lang) {
                        if(empty($direct_links[$lang['id']])) {
                            $direct_links[$lang['id']] = 'place-type';
                        }
                    }
                }
                $breadcrumb = $this->breadcrumb->render();
                $types = $this->eventPlaceTypeModel->getTypesStructure($this->id_lang, 0, array($id));
                echo view('Modules\Event\Views\admin\event_place_type_add', array('action' => $action, 'type' => $type, 'types' => $types, 'direct_links' => $direct_links, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'place-type':
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $types = $this->eventPlaceTypeModel->getTypesStructure($this->id_lang);
                echo view('Modules\Event\Views\admin\event_place_type', array(
                    'types' => $types, 
                    'filters' => $get, 
                    'breadcrumbs' => $breadcrumb, 
                ));
                break;
            case 'edit-place':
                $place = $this->eventPlaceModel->getEventPlaceById($id, $this->id_lang);
            case 'add-place':
            case 'save-place':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                        'id_type' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Event.TypeError')
                            ],
                        ],
                        'template' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Event.TemplateError')
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
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Event.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->eventPlaceModel->saveEventPlace($id, $id_content, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('event_place', array(
                            'status' => true,
                            'msg' => ($id ? lang('Event.PlaceEditSuccess') : lang('Event.PlaceAddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','event_place','Event',$id ? lang('Event.PlaceEditSuccess') : lang('Event.PlaceAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit-place/' . $id_content . '/' . $this->eventPlaceModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Event.PlaceEditError') : lang('Event.PlaceAddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $place = $post;
                    $place['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('event_place');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Event.EventPlaceEdit') . (!empty($event['name']) ? ': ' . $event['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit-place/' . $id_content . '/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Event.NewEventPlaceAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/add-place/' . $id_content);
                }
                $breadcrumb = $this->breadcrumb->render();
                $templates = get_templates_by_dir('modules/Event/Views/user/place_single');
                $types = $this->eventPlaceTypeModel->getTypesStructure($this->id_lang);
                $cities = $this->eventCityModel->getCitiesForList($this->id_lang);
                echo view('Modules\Event\Views\admin\event_place_add', array('action' => $action, 'id_content' => $id_content, 'place' => $place, 'types' => $types, 'cities' => $cities, 'templates' => $templates, 'page' => $page, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'edit-city':
                $city = $this->eventCityModel->getEventCityById($id, $this->id_lang);
            case 'add-city':
            case 'save-city':
                $this->breadcrumb->add(lang('Event.EventCityList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/city');
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
                                        'required' => $lang_name . lang('Event.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->eventCityModel->saveEventCity($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('event_city', array(
                            'status' => true,
                            'msg' => ($id ? lang('Event.CityEditSuccess') : lang('Event.CityAddSuccess')) . '!'
                        ));
                        HistoryStat($id, '', 'event_city', 'Event', $id ? lang('Event.CityEditSuccess') : lang('Event.CityAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit-city/' . $this->eventCityModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Event.CityEditError') : lang('Event.CityAddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $city = $post;
                    $city['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('event_city');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Event.EventCityEdit') . (!empty($city['name']) ? ': ' . $city['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit-city/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Event.NewEventCityAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/add-city');
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Event\Views\admin\event_city_add', array('action' => $action, 'city' => $city, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'city':
                $this->breadcrumb->add(lang('Event.EventCityList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/city');
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->eventCityModel->join('event_city_lang ecl', 'event_city.id=ecl.id_city')->select('event_city.id,event_city.publish,ecl.name')->where('ecl.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name':
                                if(!empty($value)) {
                                    $query->like('ecl.name', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('event_city.publish', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'name;asc';
                }
                switch($get['order']) {
                    case 'date;asc': $query->orderBy('event_city.created_at', 'ASC');
                        break;
                    case 'date;desc': $query->orderBy('event_city.created_at', 'DESC');
                        break;
                    case 'name;desc': $query->orderBy('ecl.name', 'DESC');
                        break;
                    case 'name;asc':
                    default:
                        $query->orderBy('ecl.name', 'ASC');
                        break;
                }
                $cities = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                $order_list = array(
                    array('field' => '', 'name' => lang('Event.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Event.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Event.sort.NameDesc')),
                    array('field' => 'date;desc', 'name' => lang('Event.sort.DateDesc')),
                    array('field' => 'date;asc', 'name' => lang('Event.sort.DateAsc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                echo view('Modules\Event\Views\admin\event_city_list', array(
                    'cities' => $cities,
                    'filters' => $get,
                    'breadcrumbs' => $breadcrumb,
                    'order_list' => $order_list,
                    'on_page_list' => $on_page_list,
                    'pager' => $this->eventCityModel->pager
                ));
                break;
            case 'type':
                $this->breadcrumb->add(lang('Event.EventTypeList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/type');
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->eventTypeModel->join('event_type_lang etl', 'event_type.id=etl.id_type')->select('event_type.id,event_type.publish,event_type.search,etl.name')->where('etl.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('etl.name', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('event_type.publish', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'name;asc';
                }
                switch($get['order']) {
                    case 'date;asc': $query->orderBy('event_type.created_at', 'ASC');
                        break;
                    case 'date;desc': $query->orderBy('event_type.created_at', 'DESC');
                        break;
                    case 'name;desc': $query->orderBy('etl.name', 'DESC');
                        break;
                    case 'name;asc': 
                    default:
                        $query->orderBy('etl.name', 'ASC');
                        break;
                }
                $types = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                $order_list = array(
                    array('field' => '', 'name' => lang('Event.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Event.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Event.sort.NameDesc')),
                    array('field' => 'date;desc', 'name' => lang('Event.sort.DateDesc')),
                    array('field' => 'date;asc', 'name' => lang('Event.sort.DateAsc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                echo view('Modules\Event\Views\admin\event_type_list', array(
                    'types' => $types, 
                    'filters' => $get, 
                    'breadcrumbs' => $breadcrumb, 
                    'order_list' => $order_list, 
                    'on_page_list'=>$on_page_list,
                    'pager' => $this->eventTypeModel->pager
                ));
                break;
            case 'edit-type':
                $type = $this->eventTypeModel->getEventTypeById($id, $this->id_lang);
            case 'add-type':
            case 'save-type':
                $this->breadcrumb->add(lang('Event.EventTypeList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/type');
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
                                        'required' => $lang_name . lang('Event.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->eventTypeModel->saveEventType($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('event_type', array(
                            'status' => true,
                            'msg' => ($id ? lang('Event.EventTypeEditSuccess') : lang('Event.EventTypeAddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','event_type','Event',$id ? lang('Event.EventTypeEditSuccess') : lang('Event.EventTypeAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit-type/' . $this->eventTypeModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Event.EventTypeEditError') : lang('Event.EventTypeAddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $type = $post;
                    $type['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('event_type');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Event.EventEdit') . (!empty($event['name']) ? ': ' . $event['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit-type/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Event.NewEventAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/add-type');
                }
                $direct_links = array();
                $links = $this->eventTypeModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('sl.value,sl.id_lang')->where('s.name', 'url_calendar')->get()->getResultArray();
                if(!empty($links)) {
                    foreach($links as $l) {
                        $direct_links[$l['id_lang']] = $l['value'];
                    }
                }
                if(!empty($this->languages)) {
                    foreach($this->languages as $lang) {
                        if(empty($direct_links[$lang['id']])) {
                            $direct_links[$lang['id']] = 'calendar';
                        }
                    }
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Event\Views\admin\event_type_add', array('action' => $action, 'type' => $type, 'direct_links' => $direct_links, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'repertoire':
                $post = $this->request->getPost();
                $event = $this->eventModel->join('event_lang el', 'event.id=el.id_event')->select('event.id,el.name')->where('event.id', $id)->where('event.publish', 1)->where('el.id_lang', $this->id_lang)->first();
                $event['default_calendar']=$this->eventModel->db->table('event_calendar')->select('id_place,custom_place')->where('id_event', $id)->where('default', 1)->get()->getRowArray();
                $this->breadcrumb->add(lang('Event.EventEdit') . (!empty($event['name']) ? ': ' . $event['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit/' . $id);
                $this->breadcrumb->add(lang('Event.EventRepertoire'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/repertoire');
                $repertoire = array();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation =  \Config\Services::validation();
                    $validation->setRules([
                        'date' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Event.EventDateError')
                            ],
                        ],
                    ]);
                    if (!$validation->run($post)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                    
                    if (empty($errors)) {
                        $result = $this->eventModel->saveEventRepertoire($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('event_repertoire', array(
                            'status' => true,
                            'msg' => ($id ? lang('Event.EditSuccess') : lang('Event.AddSuccess')) . '!',
                            'statistics' => $this->eventModel->statistics
                        ));
                        HistoryStat($id,'','event_repertoire','Event',$id ? lang('Event.EditSuccess') : lang('Event.AddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/repertoire/' . $id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Event.EditError') : lang('Event.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $repertoire = $post;
                    $repertoire['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('event_repertoire');
                    if(!empty($event['default_calendar']['id_place'])) {
                       $repertoire['id_place']=$event['default_calendar']['id_place'];	
                    }	
                    if(!empty($event['default_calendar']['custom_place'])) {
                       $repertoire['custom_place']=$event['default_calendar']['custom_place'];	
                    }
                }
                $places = $this->eventPlaceModel->getPlacesForList($this->id_lang);
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Event\Views\admin\event_repertoire', array('action' => $action, 'repertoire' => $repertoire, 'event' => $event, 'places' => $places, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'add-events':
                $events = array();
                $this->breadcrumb->add(lang('Event.EventList'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event');
                $post = $this->request->getPost();
                if (!empty($post) && !empty($post['events'])) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                        'template' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Event.TemplateError')
                            ],
                        ]
                    ]);
                    if (!$validation->run($post)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                    foreach ($post['events'] as $no=>$event) {
                        if (!empty($event['lang'])) {
                            foreach ($event['lang'] as $id_lang => $lang) {
                                $validation->reset();
                                $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                                $validation->setRules([
                                    'name' => [
                                        'rules' => 'required',
                                        'errors' => [
                                            'required' => '<b>' . $event['basename'] . ':</b> ' . $lang_name . lang('Event.NameError')
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
                        $result = $this->eventModel->saveMassEvents($id_content, $post['events'], $post['template']);
                    }
                    if ($result) {
                        $this->session->setFlashdata('events', array(
                            'status' => true,
                            'msg' => lang('Event.MassEventsAddSuccess') . '!'
                        ));
                        HistoryStat($id,'','events','Event',lang('Event.MassEventsAddSuccess'));
                        return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/add-events');
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => lang('Event.MassEventsAddError') . '!',
                            'list' => $errors
                        );
                    }
                    $events = $post['events'];
                } else {
                    $flashdata = $this->session->getFlashdata('events');
                }
                $this->breadcrumb->add(lang('Event.NewMassEventsAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/add-events');
                $breadcrumb = $this->breadcrumb->render();
                $types = $this->eventModel->getTypesForList($this->id_lang);
                $templates = get_templates_by_dir('modules/Event/Views/user/single');
                echo view('Modules\Event\Views\admin\events_add', array('action' => $action, 'events' => $events, 'types' => $types, 'templates' => $templates, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'copy':
            case 'edit':
                $event = $this->eventModel->getEventById($id, $this->id_lang, $page, $action);
            case 'add':
            case 'save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation =  \Config\Services::validation();
                    $validation->setRules([
                        'id_type' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Event.TypeError')
                            ],
                        ],
                        'template' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Event.TemplateError')
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
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Event.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->eventModel->saveEvent($id, $id_content, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('event', array(
                            'status' => true,
                            'msg' => ($id ? lang('Event.EditSuccess') : lang('Event.AddSuccess')) . '!' . ' <a href="' . ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/event/edit/' . $id_content . '/' . $this->eventModel->id . '"><i>Edytuj</i></a><br />'
                        ));
                        HistoryStat($id,'','event','Event',$id ? lang('Event.EditSuccess') : lang('Event.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $page['id_page'] . '/' . $id_content);
                        //return redirect()->to(($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit/' . $id_content . '/' . $this->eventModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Event.EditError') : lang('Event.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $event = $post;
                    $event['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('event');
                }
                if ($id) {
                    if($action == 'copy') {
                        $this->breadcrumb->add(lang('Event.EventCopy') . (!empty($event['name']) ? ': ' . $event['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/copy/' . $id_content . '/' . $id);
                    } else {
                        $this->breadcrumb->add(lang('Event.EventEdit') . (!empty($event['name']) ? ': ' . $event['name'] : ''), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/edit/' . $id_content . '/' . $id);
                    }
                } else {
                    $this->breadcrumb->add(lang('Event.NewEventAdd'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/add/' . $id_content);
                }
                $templates = get_templates_by_dir('modules/Event/Views/user/single');
                $places = $this->eventPlaceModel->getPlacesForList($this->id_lang);
                $types = $this->eventModel->getTypesForList($this->id_lang);
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Event\Views\admin\event_add', array('action' => $action, 'id_content' => $id_content, 'event' => $event, 'templates' => $templates, 'types' => $types, 'places' => $places, 'page' => $page, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'import':
                $post = $this->request->getPost();
                $flashdata = array();
                $this->breadcrumb->add(lang('Event.CalendarImport'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event/import');
                $breadcrumb = $this->breadcrumb->render();
                $data = array(
                    'places' => array(),
                    'types' => array(),
                );
                if(!empty($post)) {
                    if(!empty($post['source']) && !empty($post['option'])) {
                        $json = $this->session->get('kupbilecik_json');
                        if(empty($json)) {
                            $json = file_get_contents($post['source']);
                            $this->session->set('kupbilecik_json', $json);
                        }
                        $obj = json_decode($json, true);
                        if(!empty($obj['events'])) {
                            switch($post['option']) {
                                case 'places':
                                    if(!empty($post['assign_places'])) {
                                        $this->eventPlaceModel->assignExternalPlaces('kupbilecik', $post['assign_places']);
                                    }
                                    foreach($obj['events'] as $event) {
                                        if(!empty($event['Object'])) {
                                            $place = array(
                                                'name' => $event['Object']['Name'],
                                                'address' => $event['Object']['Address'],
                                            );
                                            if(!in_array($place, $data['places'])) {
                                                $data['places'][] = $place;
                                            }
                                        }
                                    }
                                    break;
                                case 'types':
                                    if(!empty($post['assign_types'])) {
                                        $this->eventTypeModel->assignExternalTypes('kupbilecik', $post['assign_types']);
                                    }
                                    foreach($obj['events'] as $event) {
                                        if(!empty($event['Category']) && !isset($data['types'][$event['Category']['Type']])) {
                                            $data['types'][$event['Category']['Type']] = $event['Category']['Name'];
                                        }
                                    }
                                    break;
                                case 'events':
                                    $stats = $this->eventModel->importEvents('kupbilecik', 36, $obj['events']);
                                    break;
                            }
                        }
                    }
                }
                $templates = get_templates_by_dir('modules/Event/Views/user/single');
                $places = $this->eventPlaceModel->getPlacesForList($this->id_lang);
                $types = $this->eventModel->getTypesForList($this->id_lang);
                $external_types = $this->eventTypeModel->getExternalTypesForList('kupbilecik');
                $external_places = $this->eventPlaceModel->getExternalPlacesForList('kupbilecik');
                //$source = 'https://www.kupbilecik.pl/api/?w=R&m=14&t=json&v=1.0&p=499&token=3e6be070c1ab133f8ab7eaaa1e4de88c';
                $source = 'https://www.kupbilecik.pl/api/?m=816,14&ap=resinet&t=json&v=1.0&p=499&token=3e6be070c1ab133f8ab7eaaa1e4de88c';
                if(!empty($post) && !empty($post['source'])) {
                    $source = $post['source'];
                }
                echo view('Modules\Event\Views\admin\event_import', array('action' => $action, 'places' => $places, 'external_places' => $external_places, 'types' => $types, 'external_types' => $external_types, 'templates' => $templates, 'source' => $source, 'data' => $data, 'post' => $post, 'stats' => !empty($stats) ? $stats : array(), 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'calendar':
            default :
                $this->breadcrumb->add(lang('Event.EventCalendar'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/event');
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->eventCalendarModel->join('event e', 'e.id=event_calendar.id_event', 'left')->join('event_lang el', 'e.id=el.id_event', 'left')
                        ->join('event_type_lang etl', 'etl.id_type=e.id_type', 'left')
                        ->join('event_place_lang epl', 'epl.id_place=event_calendar.id_place', 'left')
                        ->select('event_calendar.id,event_calendar.id_event,event_calendar.id_place,event_calendar.date_start,event_calendar.date_end,event_calendar.hours,event_calendar.custom_place,e.source,el.name,etl.name as type,epl.name as place')
                        ->groupStart()->where('el.id_lang', $this->id_lang)->orWhere('el.id', null)->groupEnd()
                        ->groupStart()->where('etl.id_lang', $this->id_lang)->orWhere('etl.id', null)->groupEnd()
                        ->groupStart()->where('epl.id_lang', $this->id_lang)->orWhere('epl.id', null)->groupEnd()
                        ->where('event_calendar.date_start !=', NULL);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('el.name', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('event.publish', $value);
                                }
                                break;
                            case 'event': 
                                if(!empty($value)) {
                                    $query->like('e.id', $value);
                                }
                                break;
                            case 'type': 
                                if(!empty($value)) {
                                    $query->where('e.id_type', $value);
                                }
                                break;
                            case 'place': 
                                if(!empty($value)) {
                                    $query->where('event_calendar.id_place', $value);
                                }
                                break;
                            case 'date': 
                                if (!empty($value)) {
                                    $tmp = explode('-', $value);
                                    $date_start = !empty($tmp) && !empty($tmp[0]) ? date('Y-m-d', strtotime($tmp[0])) : '';
                                    $date_end = !empty($tmp) && !empty($tmp[1]) ? date('Y-m-d', strtotime($tmp[1])) : '';
                                    if(!empty($date_start)) {
                                        $query->groupStart();
                                            $query->groupStart()->where('event_calendar.date_start >=', $date_start)->where('event_calendar.date_end', NULL)->groupEnd();
                                            $query->orWhere('event_calendar.date_end >=', $date_start);
                                        $query->groupEnd();
                                    }
                                    if(!empty($date_end)) {
                                        $query->where('event_calendar.date_start <=', $date_end);
                                    }
                                }
                                break;
                            case 'source': 
                                if(!empty($value)) {
                                    $query->where('e.source', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'date;desc';
                }
                switch($get['order']) {
                    case 'date;asc': $query->orderBy('event_calendar.date_start', 'ASC')->orderBy('event_calendar.hours', 'ASC');;
                        break;
                    case 'cdate;asc': $query->orderBy('event_calendar.created_at', 'ASC');
                        break;
                    case 'cdate;desc': $query->orderBy('event_calendar.created_at', 'DESC');
                        break;
                    case 'name;desc': $query->orderBy('el.name', 'DESC');
                        break;
                    case 'name;asc': $query->orderBy('el.name', 'ASC');
                        break;
                    case 'date;desc': 
                    default: 
                        $query->orderBy('event_calendar.date_start', 'DESC')->orderBy('event_calendar.hours', 'DESC');
                        break;
                }
                $calendar = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if(!empty($calendar)) {
                    foreach($calendar as $k=>$c) {
						$foto=$this->eventCalendarModel->db->table('event_files')->Select('path')->Where('id_event',$c['id_event'])->Where('field','photo')->get()->getRowArray();
						if(!empty($foto['path'])) {
							$calendar[$k]['path']=$foto['path'];
						}
                        if(!empty($c['hours'])) {
                            $calendar[$k]['hours'] = json_decode($c['hours']);
                        }
                    }
                }
                $order_list = array(
                    array('field' => '', 'name' => lang('Event.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Event.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Event.sort.NameDesc')),
                    array('field' => 'cdate;desc', 'name' => lang('Event.sort.DateDesc')),
                    array('field' => 'cdate;asc', 'name' => lang('Event.sort.DateAsc')),
                    array('field' => 'date;desc', 'name' => lang('Event.sort.EventDateDesc')),
                    array('field' => 'date;asc', 'name' => lang('Event.sort.EventDateAsc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                $places = $this->eventPlaceModel->getPlacesForList($this->id_lang);
                $types = $this->eventModel->getTypesForList($this->id_lang);
                echo view('Modules\Event\Views\admin\event_calendar', array(
                    'calendar' => $calendar, 
                    'types' => $types, 
                    'places' => $places, 
                    'filters' => $get, 
                    'breadcrumbs' => $breadcrumb, 
                    'order_list' => $order_list, 
                    'on_page_list'=>$on_page_list,
                    'pager' => $this->eventCalendarModel->pager
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
            case 'add-events':
            case 'edit-place':
            case 'add-place':
            case 'save-place':
            case 'copy':
            case 'edit':
            case 'add':
            case 'save':
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css';
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css';
                $assets['css_footer'][] = '/adm/third-party/tags/jquery.tagsinput.css';
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
                $assets['js'][] = '/adm/third-party/tags/jquery.tagsinput.js';
                $assets['js'][] = '/adm/js/event.js';
                break;
            case 'content':
                $assets['css_footer'][] = '/adm/third-party/tags/jquery.tagsinput.css';
                $assets['js'][] = '/adm/third-party/tags/jquery.tagsinput.js';
                break;
            case 'repertoire':
                $assets['js'][] = '/adm/js/event.js';
                break;
            default :
                break;
        }
        return $assets;
    }
    
    public function ajax($action='', $id=0) 
    {
        if(!empty($action)) {
            switch($action) {
                case 'home': 
                    return $this->homeEvent($id);
                    break;
                case 'patronage': 
                    return $this->patronageEvent($id);
                    break;
                case 'recommended': 
                    return $this->recommendedEvent($id);
                    break;
                case 'publish': 
                    return $this->publishEvent($id);
                    break;
                case 'delete': 
                    return $this->deleteEvent($id);
                    break;
                case 'home-type': 
                    return $this->homeEventType($id);
                    break;
                case 'publish-city':
                    return $this->publishEventCity($id);
                    break;
                case 'delete-city':
                    return $this->deleteEventCity($id);
                    break;
                case 'publish-type':
                    return $this->publishEventType($id);
                    break;
                case 'search-type': 
                    return $this->searchEventType($id);
                    break;
                case 'delete-type': 
                    return $this->deleteEventType($id);
                    break;
                case 'home-place': 
                    return $this->homeEventPlace($id);
                    break;
                case 'publish-place': 
                    return $this->publishEventPlace($id);
                    break;
                case 'delete-place': 
                    return $this->deleteEventPlace($id);
                    break;
                case 'add-event-hour': 
                    return $this->addEventHour();
                    break;
                case 'delete-calendar': 
                    return $this->deleteEventCalendar($id);
                    break;
                case 'publish-place-type': 
                    return $this->publishPlaceType($id);
                    break;
                case 'delete-place-type': 
                    return $this->deletePlaceType($id);
                    break;
				case 'orderplace':
                   $post = $this->request->getPost();
				    parse_str($post['data'], $params);
					$r=$this->eventPlaceTypeModel->savePlaceOrder($params);
					
				   return $this->response->setJSON(array(
					'status' => true,
					'result' => $r,
					'msg' => $r ? lang('News.OrderChanged') : lang('News.Error')
				));
                    break;				
            }
        }
    }

    private function homeEvent($id) 
    {
        $event = $this->eventModel->select('id,home')->where('id', $id)->first();
        if(!empty($event)) {
            $r = $this->eventModel->where('id', $id)->set('home', $event['home'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $event['home'] ? 0 : 1,
                'msg' => $event['home'] ? lang('Event.TurnOff') : lang('Event.TurnOn')
            );
            HistoryStat($id,'','event','Event',$event['home'] ? lang('Event.TurnOff') : lang('Event.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $event['home'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function patronageEvent($id) 
    {
        $event = $this->eventModel->select('id,patronage')->where('id', $id)->first();
        if(!empty($event)) {
            $r = $this->eventModel->where('id', $id)->set('patronage', $event['patronage'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $event['patronage'] ? 0 : 1,
                'msg' => $event['patronage'] ? lang('Event.TurnOff') : lang('Event.TurnOn')
            );
            HistoryStat($id,'','event','Event',$event['patronage'] ? lang('Event.TurnOff') : lang('Event.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $event['patronage'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function recommendedEvent($id) 
    {
        $event = $this->eventModel->select('id,recommended')->where('id', $id)->first();
        if(!empty($event)) {
            $r = $this->eventModel->where('id', $id)->set('recommended', $event['recommended'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $event['recommended'] ? 0 : 1,
                'msg' => $event['recommended'] ? lang('Event.TurnOff') : lang('Event.TurnOn')
            );
            HistoryStat($id,'','event','Event',$event['recommended'] ? lang('Event.TurnOff') : lang('Event.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $event['recommended'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function publishEvent($id) 
    {
        $event = $this->eventModel->select('id,publish')->where('id', $id)->first();
        if(!empty($event)) {
            $r = $this->eventModel->where('id', $id)->set('publish', $event['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $event['publish'] ? 0 : 1,
                'msg' => $event['publish'] ? lang('Event.Republished') : lang('Event.Published')
            );
            HistoryStat($id,'','event','Event',$event['publish'] ? lang('Event.Republished') : lang('Event.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $event['publish'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function deleteEvent($id) 
    {
        $result = $this->eventModel->deleteEvent($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Event.Removed') : lang('Event.Error')
        ));
        HistoryStat($id,'','event','Event',$result ? lang('Event.Removed') : lang('Event.Error'));
    }

    private function homeEventType($id) 
    {
        $type = $this->eventTypeModel->select('id,home')->where('id', $id)->first();
        if(!empty($type)) {
            $r = $this->eventTypeModel->where('id', $id)->set('home', $type['home'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $type['home'] ? 0 : 1,
                'msg' => $type['home'] ? lang('Event.TurnOff') : lang('Event.TurnOn')
            );
            HistoryStat($id,'','event_type','Event',$type['home'] ? lang('Event.TurnOff') : lang('Event.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $type['home'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function publishEventCity($id)
    {
        // Klucze status/publish/msg sa wymagane przez callback listRowPublish w public/adm/js/javascript.js.
        $city = $this->eventCityModel->select('id,publish')->where('id', $id)->first();
        if(empty($city)) {
            return $this->response->setJSON(array(
                'status' => false,
                'publish' => 0,
                'msg' => lang('Event.Error')
            ));
        }
        $publish = $city['publish'] ? 0 : 1;
        $r = $this->eventCityModel->where('id', $id)->set('publish', $publish)->update();
        HistoryStat($id, '', 'event_city', 'Event', $city['publish'] ? lang('Event.Republished') : lang('Event.Published'));
        return $this->response->setJSON(array(
            'status' => $r,
            'publish' => $publish,
            'msg' => $city['publish'] ? lang('Event.Republished') : lang('Event.Published')
        ));
    }

    private function deleteEventCity($id)
    {
        // Klucze status/result/id/msg sa wymagane przez callback listRowRemove w public/adm/js/javascript.js.
        $result = $this->eventCityModel->deleteEventCity($id);
        HistoryStat($id, '', 'event_city', 'Event', $result ? lang('Event.Removed') : lang('Event.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Event.Removed') : lang('Event.Error')
        ));
    }

    private function publishEventType($id)
    {
        $type = $this->eventTypeModel->select('id,publish')->where('id', $id)->first();
        if(!empty($type)) {
            $r = $this->eventTypeModel->where('id', $id)->set('publish', $type['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $type['publish'] ? 0 : 1,
                'msg' => $type['publish'] ? lang('Event.Republished') : lang('Event.Published')
            );
            HistoryStat($id,'','event_type','Event',$type['publish'] ? lang('Event.Republished') : lang('Event.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $type['publish'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function searchEventType($id) 
    {
        $type = $this->eventTypeModel->select('id,search')->where('id', $id)->first();
        if(!empty($type)) {
            $r = $this->eventTypeModel->where('id', $id)->set('search', $type['search'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $type['search'] ? 0 : 1,
                'msg' => $type['search'] ? lang('Event.Republished') : lang('Event.Published')
            );
            HistoryStat($id,'','event_type','Event',$type['search'] ? lang('Event.Republished') : lang('Event.Published'));
        } else {
            $response = array(
                'status' => true,
                'home' => $type['search'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function deleteEventType($id) 
    {
        $result = $this->eventTypeModel->deleteEventType($id);
        HistoryStat($id,'','event_type','Event',$result ? lang('Event.Removed') : lang('Event.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Event.Removed') : lang('Event.Error')
        ));
    }

    private function homeEventPlace($id) 
    {
        $place = $this->eventPlaceModel->select('id,home')->where('id', $id)->first();
        if(!empty($place)) {
            $r = $this->eventPlaceModel->where('id', $id)->set('home', $place['home'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'home' => $place['home'] ? 0 : 1,
                'msg' => $place['home'] ? lang('Event.TurnOff') : lang('Event.TurnOn')
            );
            HistoryStat($id,'','event_place','Event',$place['home'] ? lang('Event.TurnOff') : lang('Event.TurnOn'));
        } else {
            $response = array(
                'status' => true,
                'home' => $place['home'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function publishEventPlace($id) 
    {
        $place = $this->eventPlaceModel->select('id,publish')->where('id', $id)->first();
        if(!empty($place)) {
            $r = $this->eventPlaceModel->where('id', $id)->set('publish', $place['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $place['publish'] ? 0 : 1,
                'msg' => $place['publish'] ? lang('Event.Republished') : lang('Event.Published')
            );
            HistoryStat($id,'','event_place','Event',$place['publish'] ? lang('Event.Republished') : lang('Event.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $place['publish'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function deleteEventPlace($id) 
    {
        $result = $this->eventPlaceModel->deleteEventPlace($id);
        HistoryStat($id,'','event_place','Event',$result ? lang('Event.Removed') : lang('Event.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Event.Removed') : lang('Event.Error')
        ));
    }
    
    public function addEventHour() {
        $html = view('Modules\Event\Views\admin\event_repertoire_hour', array('languages' => $this->languages, 'locale' => $this->locale, 'remove' => true));
        $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        );
        return $this->response->setJSON($response);
    }
    
    private function deleteEventCalendar($id) 
    {
        $result = $this->eventCalendarModel->deleteEventCalendar($id);
        HistoryStat($id,'','event_calendar','Event',$result ? lang('Event.Removed') : lang('Event.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Event.Removed') : lang('Event.Error')
        ));
    }
    
    private function publishPlaceType($id) 
    {
        $type = $this->eventPlaceTypeModel->select('id,publish')->where('id', $id)->first();
        if(!empty($type)) {
            $r = $this->eventPlaceTypeModel->where('id', $id)->set('publish', $type['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $type['publish'] ? 0 : 1,
                'msg' => $type['publish'] ? lang('Event.Republished') : lang('Event.Published')
            );
            HistoryStat($id,'','event_place_type','Event',$type['publish'] ? lang('Event.Republished') : lang('Event.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $type['publish'],
                'msg' => lang('Event.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    
    private function deletePlaceType($id) 
    {
        $result = $this->eventPlaceTypeModel->deletePlaceType($id);
        HistoryStat($id,'','event_place_type','Event',$result ? lang('Event.Removed') : lang('Event.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Event.Removed') : lang('Event.Error')
        ));
    }
    
    public function getFileUploadHtml($post, $data) {
        $types = $this->eventModel->db->table('event_type et')->join('event_type_lang etl', 'et.id=etl.id_type')->select('et.id,etl.name')->where('et.publish', 1)->orderBy('etl.name ASC')->get()->getResultArray();
        $html = view('Modules\Event\Views\admin\upload_' . (!empty($post['option']) ? $post['option'] : 'file'), array('file' => $data, 'types' => $types, 'field' => $post['field'], 'multi' => !empty($post['multi']) ? $post['multi'] : false, 'no' => uniqid(), 'languages' => $this->languages));
        return $html;
    }
    
    // Bloki tresci bedace listami wydarzen (element 'list') - tylko ich id trafiaja do event.id_page_cont, wiec tylko one maja sens jako zrodlo dla config[lists].
    private function getEventListsForConfig() {
        $newsModel = new NewsModel();
        $lists = $this->eventModel->db->table('page_content pc')
                ->join('page_content_lang pcl', 'pc.id=pcl.id_page_cont', 'left')
                ->join('page p', 'p.id=pc.id_page')
                ->join('page_lang pl', 'pl.id_page=p.id')
                ->select('pl.id_page,pl.name,pc.id as id_content,p.re_id,pcl.name as content_name,pcl.title,pc.order')
                ->groupStart()
                    ->where('pcl.id_lang', $this->id_lang)
                    ->orWhere('pcl.id', null)
                ->groupEnd()
                ->where('pl.id_lang', $this->id_lang)
                ->where('pc.id_module_element', 26)
                ->get()
                ->getResultArray();
        if(!empty($lists)) {
            foreach($lists as $k=>$list) {
                if(!empty($list['re_id'])) {
                    $lists[$k]['tree_name'] = $newsModel->getNewsTreeName($list['re_id'], $this->id_lang);
                }
            }
        }
        return $lists;
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        switch ($slug) {
            case 'event_cinema':
                $types = $this->eventTypeModel->getTypesForList($this->id_lang);
                $templates = get_templates_by_dir('modules/Event/Views/user/event_cinema');
                return array(
                    'lists' => array(),
                    'types' => $types,
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Event\Views\admin\event_cinema_config',
                    'form_lang_view' => 'Modules\Event\Views\admin\event_cinema_lang_config',
                );
                
                break;
            case 'places_selected':
                $types = $this->eventPlaceTypeModel->getTypesStructure($this->id_lang);
                $templates = get_templates_by_dir('modules/Event/Views/user/places');
                return array(
                    'types' => $types,
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Event\Views\admin\places_selected_config'
                );
                break;
            case 'places':
                if(empty($this->sidebar)) {
                    $get = $this->request->getGet();
                    $query = $this->eventPlaceModel->join('event_place_lang epl', 'event_place.id=epl.id_place')->join('event_place_type_lang eptl', 'eptl.id_type=event_place.id_type', 'left')->select('event_place.id,event_place.publish,epl.name,eptl.name as type')->where('epl.id_lang', $this->id_lang)->where('eptl.id_lang', $this->id_lang);
                    if(!empty($get)) {
                        foreach($get as $name=>$value) {
                            switch($name) {
                                case 'name': 
                                    if(!empty($value)) {
                                        $query->like('epl.name', $value);
                                    }
                                    break;
                                case 'publish':
                                    if(in_array($value, array(0,1))) {
                                        $query->where('event_place.publish', $value);
                                    }
                                    break;
                            }
                        }
                    }
                    if(empty($get['order'])) {
                        $get['order'] = 'name;asc';
                    }
                    switch($get['order']) {
                        case 'date;asc': $query->orderBy('event_place.created_at', 'ASC');
                            break;
                        case 'date;desc': $query->orderBy('event_place.created_at', 'DESC');
                            break;
                        case 'name;desc': $query->orderBy('epl.name', 'DESC');
                            break;
                        case 'name;asc': 
                        default:
                            $query->orderBy('epl.name', 'ASC');
                            break;
                    }
                    $places = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                    if(!empty($places)) {
                        foreach($places as $k=>$p) {
                            $count = $this->eventPlaceModel->db->table('event_place_lang epl')->where('epl.id_place', $p['id'])->selectSum('epl.views')->get()->getRowArray();
                            $foto=$this->eventModel->db->table('event_files')->Select('path')->Where('id_event',$p['id'])->Where('field','place_photo')->get()->getRowArray();
							if(!empty($foto['path'])) {$places[$k]['path']=$foto['path']; }
							$places[$k]['views'] = $count['views'];
                        }
                    }
                    $order_list = array(
                        array('field' => '', 'name' => lang('Event.sort.Default')),
                        array('field' => 'name;asc', 'name' => lang('Event.sort.NameAsc')),
                        array('field' => 'name;desc', 'name' => lang('Event.sort.NameDesc')),
                        array('field' => 'date;desc', 'name' => lang('Event.sort.DateDesc')),
                        array('field' => 'date;asc', 'name' => lang('Event.sort.DateAsc')),
                    );
                    $on_page_list = array(
                        20 => 20,
                        40 => 40,
                        80 => 80,
                    );
                    $templates = get_templates_by_dir('modules/Event/Views/user/places');
                    return array(
                        'places' => $places, 
                        'filters' => $get,
                        'order_list' => $order_list,
                        'pager' => $this->eventPlaceModel->pager,
                        'on_page_list' => $on_page_list,
                        'pc_templates' => $templates,
                        'list_view' => 'Modules\Event\Views\admin\event_place_list',
                        'form_view' => 'Modules\Event\Views\admin\places_config'
                    );
                } else {
                    $templates = get_templates_by_dir('modules/Event/Views/user/places');
                    return array(
                        'pc_templates' => $templates,
                        'form_view' => 'Modules\Event\Views\admin\places_config'
                    );
                }
                break;
            case 'calendar':
                $templates = get_templates_by_dir('modules/Event/Views/user/calendar');
                return array(
                    'lists' => $this->getEventListsForConfig(),
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Event\Views\admin\calendar_config'
                );
                break;
            case 'boxes':
                $newsModel = new NewsModel();
                $news_pages = $newsModel->db->table('page_content pc')
                        ->join('page_content_lang pcl', 'pc.id=pcl.id_page_cont', 'left')
                        ->join('page p', 'p.id=pc.id_page')
                        ->join('page_lang pl', 'pl.id_page=p.id')
                        ->select('pl.id_page,pl.name,pc.id as id_content,p.re_id,pcl.name as content_name,pcl.title,pc.order')
                        ->groupStart()
                            ->where('pcl.id_lang', $this->id_lang)
                            ->orWhere('pcl.id', null)
                        ->groupEnd()
                        ->where('pl.id_lang', $this->id_lang)
                        ->where('pc.id_module_element', 2)
                        ->get()
                        ->getResultArray();
                if(!empty($news_pages)) {
                    foreach($news_pages as $k=>$list) {
                        if(!empty($news_pages['re_id'])) {
                            $news_pages[$k]['tree_name'] = $newsModel->getNewsTreeName($list['re_id'], $this->id_lang);
                        }
                    }
                }
                $templates = get_templates_by_dir('modules/Event/Views/user/boxes');
                return array(
                    'pc_templates' => $templates,
                    'news' => $news_pages,
                    'form_view' => 'Modules\Event\Views\admin\boxes_config'
                );
                break;
            case 'selected2':
            case 'selected':
                $types = $this->eventTypeModel->getTypesForList($this->id_lang);
                $templates = get_templates_by_dir('modules/Event/Views/user/list');
                return array(
                    'lists' => $this->getEventListsForConfig(),
                    'types' => $types,
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Event\Views\admin\list_config'
                );
                break;
            case 'list':
                $get = $this->request->getGet();
                $query = $this->eventModel->join('event_lang el', 'event.id=el.id_event')
                        ->join('event_type et', 'et.id=event.id_type', 'left')
                        ->join('event_type_lang etl', 'et.id=etl.id_type', 'left')
                        ->select('event.id,event.publish,event.home,event.patronage,event.for_kids,event.recommended,event.source,el.name,etl.name as type')
                        ->where('event.id_page_cont', $id_content)
                        ->where('el.id_lang', $this->id_lang)
                        ->groupStart()
                            ->where('etl.id_lang', $this->id_lang)
                            ->orWhere('etl.id', NULL)
                        ->groupEnd();
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('el.name', $value);
                                }
                                break;
                            case 'type':
                                if(!empty($value)) {
                                    $query->where('event.id_type', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('event.publish', $value);
                                }
                                break;
                            case 'patronage':
                                if(in_array($value, array(0,1))) {
                                    $query->where('event.patronage', $value);
                                }
                                break;
                            case 'home':
                                if(in_array($value, array(0,1))) {
                                    $query->where('event.home', $value);
                                }
                                break;
                            case 'for_kids':
                                if(in_array($value, array(0,1))) {
                                    $query->where('event.for_kids', $value);
                                }
                                break;
                            case 'recommended':
                                if(in_array($value, array(0,1))) {
                                    $query->where('event.recommended', $value);
                                }
                            case 'source':
                                if(!empty($value)) {
                                    $query->where('event.source', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'date;desc';
                }
                switch($get['order']) {
                    case 'date;desc': $query->orderBy('event.created_at', 'DESC');
                        break;
                    case 'name;desc': $query->orderBy('el.name', 'DESC');
                        break;
                    case 'name;asc': $query->orderBy('el.name', 'ASC');
                        break;
                    case 'date;desc': 
                    default: 
                        $query->orderBy('event.created_at', 'ASC');
                        break;
                }
                $events = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if(!empty($events)) {
                    foreach($events as $k=>$e) {
                        $count = $this->eventModel->db->table('event_lang el')->where('el.id_event', $e['id'])->selectSum('el.views')->get()->getRowArray();
                        $foto=$this->eventModel->db->table('event_files')->Select('path')->Where('id_event',$e['id'])->Where('field','photo')->get()->getRowArray();
                        if(!empty($foto['path'])) {$events[$k]['path']=$foto['path'];}
                        $events[$k]['views'] = $count['views'];
                        $events[$k]['places'] = $this->eventModel->db->table('event_calendar ec')->join('event_place ep', 'ec.id_place=ep.id')->join('event_place_lang epl', 'ep.id=epl.id_place')->select('ep.id,ep.id_page_cont,epl.name')->where('epl.id_lang', $this->id_lang)->where('ec.id_event', $e['id'])->groupBy('ep.id')->get()->getResultArray();
                        $events[$k]['custom_places'] = $this->eventModel->db->table('event_calendar ec')->select('ec.custom_place')->where('ec.id_event', $e['id'])->groupBy('ec.custom_place')->get()->getResultArray();
                    }
                }
                $order_list = array(
                    array('field' => '', 'name' => lang('Event.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Event.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Event.sort.NameDesc')),
                    array('field' => 'date;desc', 'name' => lang('Event.sort.DateDesc')),
                    array('field' => 'date;asc', 'name' => lang('Event.sort.DateAsc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                $types = $this->eventTypeModel->getTypesForList($this->id_lang);
                $templates = get_templates_by_dir('modules/Event/Views/user/list');
                $flashdata = $this->session->getFlashdata('event');
                return array(
                    'flashdata' => $flashdata,
                    'events' => $events, 
                    'types' => $types,
                    'filters' => $get,
                    'order_list' => $order_list,
                    'pager' => $this->eventModel->pager,
                    'on_page_list' => $on_page_list,
                    'pc_templates' => $templates,
                    'list_view' => 'Modules\Event\Views\admin\event_list',
                    'form_view' => 'Modules\Event\Views\admin\list_config'
                );
                break;
        }
    }  
}