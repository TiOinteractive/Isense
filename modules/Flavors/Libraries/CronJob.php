<?php

namespace Modules\Flavors\Libraries;

use App\Libraries\Page;
use Modules\Flavors\Models\FlavorsInstagramModel;
use App\Models\SettingsModel;

class CronJob
{
    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->flavorsInstaModel = new FlavorsInstagramModel();
        $this->pageClass = new Page();
		ini_set('memory_limit', '2048M');
		ini_set('max_execution_time', '0');
    }

    function initController() {
        
    }
    
   function loadInstagram() {


		$username = 'rzeszowskiesmaki';
		$myUserID = 1412550606;
		$igUserID = 17841401745751020;
		$appID = env('instagram.appId');
		$appSecret = env('instagram.appSecret');

		$file = file_get_contents('instagram.txt');
		$file_data = explode(';', $file);
		$appToken = end($file_data);
		if(strtotime($file_data[0] . ' -10 days') <= time()) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $appToken);
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			if($response) {
				$response = json_decode($response);
				$file_data[0] = date('Y-m-d H:i:s', strtotime('+' . $response->expires_in . ' seconds'));
				file_put_contents('instagram.txt', implode(';', $file_data) . ';' . $response->access_token);
			}
		}


		/*
		https://api.instagram.com/oauth/authorize?client_id=3962198020501700&redirect_uri=https://www.resinet.pl/&scope=user_profile,user_media&response_type=code
	

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://graph.instagram.com/me/media?fields=caption,id,permalink,timestamp,thumbnail_url,media_url,media_type&limit=100&access_token=" . $appToken);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close ($ch);
		$server_output = json_decode($server_output);		
         echo '<pre>';
		 print_r($server_output);
		 echo '</pre>';

*/

      exit();
   }   
}
