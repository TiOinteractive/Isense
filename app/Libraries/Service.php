<?php

namespace App\Libraries;

class Service
{
    public function check() {
        helper('filesystem');
        $data_file = WRITEPATH . '.service';
        if(file_exists($data_file)) {
            $data = explode('|', file_get_contents($data_file));
        }
        if(empty($data) || empty($data[0]) || empty($data[1]) || (!empty($data[0]) && strtotime($data[0]) < strtotime(date('Y-m-d')))) {
            $host = parse_url(base_url(), PHP_URL_HOST);
            
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );
            try {
                /*$url = 'https://www.tiointeractiveasd.pl/services?domain=' . $host;
                $headers = @get_headers($url);
                if($headers !== false) {
                    $r = @file_get_contents('https://www.tiointeractive.pl/services?domain=' . $host, false, stream_context_create($arrContextOptions));
                    write_file($data_file, date('Y-m-d') . '|' . $r);
                }*/
            } catch (Exception $ex) {
                
            }
        } else {
            $r = $data[1];
        }
        if(!empty($r)) {
            return json_decode($r, true);
        }
        
    }
    
}