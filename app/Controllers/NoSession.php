<?php

namespace App\Controllers;



class NoSession extends BaseController {
    
    public function __construct() 
    {
        
    }
    
    public function index($module, $action, $hash) 
    {
        if (method_exists($this, $module)) {
            return $this->$module($action, $hash);
        }
        
    }
    
    private function newsletter($action, $hash) 
    {
        if (is_dir(ROOTPATH . 'modules/Newsletter') && class_exists('\Modules\Newsletter\Models\NewsletterModel')) {
            $class = '\Modules\Newsletter\Models\NewsletterModel';
            $module_class = new $class();
            switch($action) {
                case 'update-sending':
                    $history = $module_class->db->table('newsletter_history')->select('id,id_newsletter,emails,status')->where('hash', $hash)
                        ->groupStart()
                            ->groupStart()
                                ->whereIn('status', array('sending', 'set'))
                                ->where('send_time>=', date('Y-m-d H:i:s', strtotime('-1 day')))
                            ->groupEnd()
                            ->orGroupStart()
                                ->where('status', 'sent')
                                ->where('edited_at>=', date('Y-m-d H:i:s', strtotime('-5 minute')))
                            ->groupEnd()
                        ->groupEnd()
                        ->get()->getRowArray();
                    if(!empty($history)) {
                        $count = $module_class->db->table('newsletter_stats')->where('id_newsletter', $history['id_newsletter'])->where('id_history', $history['id'])->countAllResults();
                        $response = array(
                            'emails' => $history['emails'],
                            'history_status' => $history['status'],
                            'count' => $count,
                            'status' => true,
                        );
                    } else {
                        $response = array(
                            'status' => false,
                        );
                    }
                    break;
                case 'check-sending':
                    $newsletter = $module_class->db->table('newsletter')->select('id')->where('hash', $hash)->get()->getRowArray();
                    $history = $module_class->db->table('newsletter_history')->select('id,groups,status,emails')->where('id_newsletter', $newsletter['id'])->where('status', 'sending')->get()->getRowArray();
                    if(!empty($history)) {
                        $count = $module_class->db->table('newsletter_stats')->where('id_newsletter', $newsletter['id'])->where('id_history', $history['id'])->countAllResults();
                        $response = array(
                            'emails' => $history['emails'],
                            'count' => $count,
                            'status' => true,
                        );
                    } else {
                        $response = array(
                            'status' => false,
                        );
                    }
                    break;
            }
        }
        return $this->response->setJSON($response);
    }
}