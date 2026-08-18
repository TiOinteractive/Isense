<?php

namespace Modules\Event\Libraries;

use Modules\Event\Models\EventModel;
use App\Models\SettingsModel;

class CronJob
{
    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->eventModel = new EventModel();
        $this->settingsModel = new SettingsModel();
        $this->id_lang = 1;
        $this->locale = '';
    }

    public function initController() {
        
    }
    
    public function import() {
        helper('filesystem');
        $config = new \Config\Email();
        $eventConfig = config('Event');
        $mail_to = array_filter(array_map('trim', explode(',', $eventConfig->importMailTo)));
        foreach($eventConfig->importUrls as $id_content => $source) {
            if(empty($source)) continue;
            // Klucz cache musi byc per feed - wspolny sprawilby, ze kolejny feed zaimportowalby JSON poprzedniego do swojego bloku.
            $json = $this->session->get('kupbilecik_json_' . $id_content);
            if(empty($json)) {
                $json = file_get_contents($source);
                $this->session->set('kupbilecik_json_' . $id_content, $json);
            }
            $obj = json_decode($json, true);
            $stats = $this->eventModel->importEvents('kupbilecik', $id_content, $obj['events']);
            if($stats['result']) {
                $stats['created'] = $this->getEvents($stats['created']);
                $stats['updated'] = $this->getEvents($stats['updated']);
                $stats['removed'] = $this->getEvents($stats['removed']);
                $stats['duplicated'] = $this->getEvents($stats['duplicated']);
                $stats['skipped'] = $this->getEvents($stats['skipped']);
            }

            $settings = $this->settingsModel->getSettings($this->id_lang);
            $email = \Config\Services::email();
            $data = array(
                'header' => str_replace('{SHOPNAME}', $settings['company_short_name'], lang('Users.account.EmailTopicActivate')),
                'settings' => $settings,
                'stats' => $stats
            );
            if (!empty($settings['logo']['path'])) {
                $email->attach(base_url() . 'foto/r/300/100/' . $settings['logo']['path']);
                $data['cid_logo'] = $email->setAttachmentCID(base_url() . ($this->locale ? '/' . $this->locale : '') . 'foto/r/300/100/' . $settings['logo']['path']);
            }
            $email->setFrom($config->fromEmail, $settings['company_name']);
            $email->setTo($mail_to);
            $email->setSubject('CRON - import wydarzeń - podsumowanie (blok ' . $id_content . ')');
            $message = view('Modules\Event\Views\admin/event_import_mail', $data);
            $email->setMessage($message);
            $email->send();
        }
    }
    
    private function getEvents($ids) {
        $events = array();
        if(!empty($ids)) {
            $events = $this->eventModel->db->table('event_calendar ec')
                ->join('event e', 'e.id=ec.id_event')
                ->join('event_lang el', 'e.id=el.id_event')
                ->join('links l', 'l.id=el.id_link')
                ->select('ec.id,ec.id_event,ec.id_place,ec.custom_place,ec.date_start,ec.date_end,ec.hours,e.id_type,e.id_page_cont,el.name,l.link')
                ->where('el.id_lang', $this->id_lang)
                ->whereIn('ec.id', $ids)
                ->get()->getResultArray();
            if(!empty($events)) {
                $base_url = base_url();
                foreach($events as $k=>$event) {
                    $events[$k]['link'] = $base_url . '/' . $event['link'];
                    $events[$k]['adm_link'] = $base_url . '/tiocms/event/edit/' . $event['id_page_cont'] . '/' . $event['id_event'];
                    $events[$k]['calendar_link'] = $base_url . '/tiocms/event/calendar?event=' . $event['id_event'];
                    $events[$k]['type'] = $this->eventModel->db->table('event_type et')->join('event_type_lang etl', 'et.id=etl.id_type')->select('et.id,etl.name')->where('et.id', $event['id_type'])->get()->getRowArray();
                    $events[$k]['place'] = $this->eventModel->db->table('event_place ep')->join('event_place_lang epl', 'ep.id=epl.id_place')->select('ep.id,epl.name')->where('ep.id', $event['id_place'])->get()->getRowArray();
                }
            }
        }
        return $events;
    }
}