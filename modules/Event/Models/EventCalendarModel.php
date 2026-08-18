<?php

namespace Modules\Event\Models;

use CodeIgniter\Model;
use App\Libraries\Link;

class EventCalendarModel extends Model {

    protected $table = 'event_calendar';
    protected $allowedFields = [
        'id_event',
        'id_place',
        'date_start',
        'date_end',
        'hours',
        'custom_place',
        'edited_at',
        'created_at',
    ];

    public function deleteEventCalendar($id) {
        if (empty($id))
            return false;
        $this->db->transStart();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function countCalendarEvents($month, $id_page_cont = null) {
        $events_count = array();
        $date_start = date('Y-m', strtotime($month)) . '-01';
        $date_end = date('Y-m-d', strtotime($month . '-01 + 1 month - 1 day'));
        $query = $this->db->table('event_calendar ec')
                        ->join('event e', 'e.id=ec.id_event')
                        ->join('event_place ep', 'ep.id=ec.id_place', 'left')
                        ->select('ec.date_start,ec.date_end,COUNT(ec.id) as count')
                        ->groupStart()
                        ->groupStart()
                        ->where('ec.date_start >=', $date_start)
                        ->where('ec.date_start <=', $date_end)
                        ->where('ec.date_end', NULL)
                        ->groupEnd()
                        ->orGroupStart()
                        ->where('ec.date_end >=', $date_start)
                        ->where('ec.date_start <=', $date_end)
                        ->groupEnd()
                        ->groupend()
                        ->groupStart()
                        ->where('ep.publish', 1)
                        ->orWhere('ec.custom_place !=', '')
                        ->groupEnd()
                        ->where('e.publish', 1)
                        ->orderBy('ec.date_start', 'ASC')
                        ->groupBy('ec.date_start, ec.date_end');
        if ($id_page_cont) {
            $query->whereIn('e.id_page_cont', $id_page_cont);
        }
        $list = $query->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                if (empty($l['date_end'])) {
                    $date = date('Y-m-d', strtotime($l['date_start']));
                    if (!isset($events_count[$date])) {
                        $events_count[$date] = 0;
                    }
                    $events_count[$date] += $l['count'];
                } else {
                    $date = $l['date_start'];
                    if ($date < $date_start) {
                        $date = $date_start;
                    }
                    while ($date >= $l['date_start'] && $date <= $l['date_end'] && $date <= $date_end) {
                        if (!isset($events_count[$date])) {
                            $events_count[$date] = 0;
                        }
                        $events_count[$date] += $l['count'];
                        $date = date('Y-m-d', strtotime($date . ' + 1 day'));
                    }
                }
            }
        }
        return $events_count;
    }

    public function getPlaceRepertoire($id_place, $id_lang) {
        $date = date('Y-m-d');
        $events = $this->db->table('event_calendar ec')
                        ->join('event e', 'e.id=ec.id_event')
                        ->join('event_lang el', 'e.id=el.id_event')
                        ->join('event_files ef', 'ef.id_event=e.id AND ef.field="photo"')
                        ->join('links l', 'l.id=el.id_link')
                        ->join('event_type_lang etl', 'etl.id_type=e.id_type', 'left')
                        ->join('links l3', 'l3.id=etl.id_link', 'left')
                        ->select('ec.id,ec.id_event,ec.id_place,e.for_kids,e.source,el.name,ef.path as photo,l.link,ec.custom_place,ec.date_start,ec.date_end,ec.hours,etl.name as type_name,l3.link as type_link,patronage,price')
                        ->where('el.id_lang', $id_lang)
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
                        ->where('e.publish', 1)
                        ->where('ec.id_place', $id_place)
                        ->orderBy('ec.date_start', 'ASC')
                        ->orderBy('ec.hours', 'ASC')
                        ->get()->getResultArray();
        return $events;
    }

    public function getPlaceEventRepertoire($id_place, $id_event, $id_lang, $limit) {

        $events = array();
        $date = date('Y-m-d');
        $query = $this->db->table('event_calendar')->join('event e', 'e.id=event_calendar.id_event')
                ->join('event_lang el', 'e.id=el.id_event')
                ->join('event_place ep', 'ep.id=event_calendar.id_place', 'left')
                ->join('event_place_lang epl', 'ep.id=epl.id_place', 'left')
                ->join('links l', 'l.id=el.id_link')
                ->join('links l2', 'l2.id=epl.id_link', 'left')
                ->join('event_type_lang etl', 'etl.id_type=e.id_type', 'left')
                ->select('event_calendar.id,event_calendar.id_event,event_calendar.id_place,e.for_kids,e.source,e.patronage,el.price,el.name,l.link,event_calendar.custom_place,event_calendar.date_start,event_calendar.date_end,event_calendar.hours,epl.name as place,l2.link as place_link,etl.name as type_name,etl.slug as type_slug')
                ->where('el.id_lang', $id_lang)
                ->groupStart()
                ->where('epl.id_lang', $id_lang)
                ->orWhere('ep.id', NULL)
                ->groupEnd()
                ->groupStart()
                ->groupStart()
                ->where('event_calendar.date_start >=', $date)
                ->where('event_calendar.date_end', NULL)
                ->groupEnd()
                ->orGroupStart()
                ->where('event_calendar.date_start >=', $date)
                ->orWhere('event_calendar.date_end >=', $date)
                ->groupEnd()
                ->groupend()
                ->groupStart()
                ->where('ep.publish', 1)
                ->orWhere('event_calendar.custom_place !=', '')
                ->groupEnd()
                ->groupStart()
                ->where('etl.id_lang', $id_lang)
                ->orWhere('etl.id', null)
                ->groupEnd()
                ->where('e.publish', 1)
                ->where('event_calendar.id_place', $id_place)
                ->where('e.id!=', $id_event);
        $query->orderBy('event_calendar.date_start', 'ASC')->orderBy('event_calendar.hours', 'ASC')->Limit($limit);
        $list = $query->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $k => $e) {
                $e['photo'] = $this->db->table('event_files ef')->select('ef.path,ef.crop_dimension')->where('ef.id_event', $e['id_event'])->where('ef.publish', 1)->where('ef.field', 'photo')->get()->getRowArray();
                if (!empty($this->locale)) {
                    $e['link'] = ($this->locale ? $this->locale . '/' : '') . $e['link'];
                }
                if (empty($e['date_end']) || $e['date_start'] == $e['date_end']) {
                    $e['date'] = date('d.m.Y', strtotime($e['date_start']));
                } else {
                    $d1 = explode('-', $e['date_start']);
                    $d2 = explode('-', $e['date_end']);
                    $e['date'] = $d1[2] . ($d1[1] != $d2[1] ? '.' . $d1[1] : '') . ($d1[0] != $d2[0] ? '.' . $d1[0] : '') . ' - ' . $d2[2] . '.' . $d2[1] . '.' . $d2[0];
                }
                $e['short_date'] = $e['date'];
                if (!empty($e['hours'])) {
                    $e['hours'] = json_decode($e['hours']);
                    $e['date'] .= ' ' . implode(', ', $e['hours']);
                }
                if (empty($get['date'])) {
                    if ($e['date_start'] < date('Y-m-d')) {
                        $date_key = date('Y-m-d');
                    } else {
                        $date_key = $e['date_start'];
                    }
                } else {
                    $date_key = $get['date'];
                }
                if (!isset($events[$date_key])) {
                    $events[$date_key] = array();
                }
                $events[$date_key][] = $e;
            }
        }
        return $events;
    }

    public function getTypeRepertoire($id_type, $id_event, $id_lang, $limit) {

        $events = array();
        $date = date('Y-m-d');
        $query = $this->db->table('event_calendar')->join('event e', 'e.id=event_calendar.id_event')
                ->join('event_lang el', 'e.id=el.id_event')
                ->join('event_place ep', 'ep.id=event_calendar.id_place', 'left')
                ->join('event_place_lang epl', 'ep.id=epl.id_place', 'left')
                ->join('links l', 'l.id=el.id_link')
                ->join('links l2', 'l2.id=epl.id_link', 'left')
                ->join('event_type_lang etl', 'etl.id_type=e.id_type', 'left')
                ->select('event_calendar.id,event_calendar.id_event,event_calendar.id_place,e.for_kids,e.patronage,el.price,el.name,l.link,event_calendar.custom_place,event_calendar.date_start,event_calendar.date_end,event_calendar.hours,epl.name as place,l2.link as place_link,etl.name as type_name,etl.slug as type_slug')
                ->where('el.id_lang', $id_lang)
                ->groupStart()
                ->where('epl.id_lang', $id_lang)
                ->orWhere('ep.id', NULL)
                ->groupEnd()
                ->groupStart()
                ->groupStart()
                ->where('event_calendar.date_start >=', $date)
                ->where('event_calendar.date_end', NULL)
                ->groupEnd()
                ->orGroupStart()
                ->where('event_calendar.date_start >=', $date)
                ->orWhere('event_calendar.date_end >=', $date)
                ->groupEnd()
                ->groupend()
                ->groupStart()
                ->where('ep.publish', 1)
                ->orWhere('event_calendar.custom_place !=', '')
                ->groupEnd()
                ->groupStart()
                ->where('etl.id_lang', $id_lang)
                ->orWhere('etl.id', null)
                ->groupEnd()
                ->where('e.publish', 1)
                ->where('e.id_type', $id_type)
                ->where('e.id!=', $id_event);
        $query->orderBy('event_calendar.date_start', 'ASC')->orderBy('event_calendar.hours', 'ASC')->Limit($limit);
        $list = $query->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $k => $e) {
                $e['photo'] = $this->db->table('event_files ef')->select('ef.path,ef.crop_dimension')->where('ef.id_event', $e['id_event'])->where('ef.publish', 1)->where('ef.field', 'photo')->get()->getRowArray();
                if (!empty($this->locale)) {
                    $e['link'] = ($this->locale ? $this->locale . '/' : '') . $e['link'];
                }
                if (empty($e['date_end']) || $e['date_start'] == $e['date_end']) {
                    $e['date'] = date('d.m.Y', strtotime($e['date_start']));
                } else {
                    $d1 = explode('-', $e['date_start']);
                    $d2 = explode('-', $e['date_end']);
                    $e['date'] = $d1[2] . ($d1[1] != $d2[1] ? '.' . $d1[1] : '') . ($d1[0] != $d2[0] ? '.' . $d1[0] : '') . ' - ' . $d2[2] . '.' . $d2[1] . '.' . $d2[0];
                }
                $e['short_date'] = $e['date'];
                if (!empty($e['hours'])) {
                    $e['hours'] = json_decode($e['hours']);
                    $e['date'] .= ' ' . implode(', ', $e['hours']);
                }
                if (empty($get['date'])) {
                    if ($e['date_start'] < date('Y-m-d')) {
                        $date_key = date('Y-m-d');
                    } else {
                        $date_key = $e['date_start'];
                    }
                } else {
                    $date_key = $get['date'];
                }
                if (!isset($events[$date_key])) {
                    $events[$date_key] = array();
                }
                $events[$date_key][] = $e;
            }
        }
        return $events;
    }

}
