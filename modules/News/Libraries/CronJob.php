<?php

namespace Modules\News\Libraries;

use App\Libraries\Page;
use Modules\News\Models\NewsModel;
use App\Models\SettingsModel;

class CronJob
{
    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->newsModel = new NewsModel();
        $this->pageClass = new Page();
		ini_set('memory_limit', '2048M');
		ini_set('max_execution_time', '0');
    }

    function initController() {
        
    }
    
   function publishNews() {
	   $date_check=date("Y-m-d H:i",strtotime('-3hour'));
	   $list = $this->newsModel->db->table('news')->select('id')->where('publish',0)->where('publish_date <=',date("Y-m-d H:i"))->where('publish_date >=',$date_check)->get()->getResultArray();
	   foreach($list as $news) {
		   $result = $this->newsModel->db->table('news')->set(array('publish'=>1,'publish_date'=>NULL))->Where('id',$news['id'])->update();
	   }
   }

   function loadInstagram() {
		$username = 'resinet_pl';
		$myUserID = 1345338191;
		$igUserID = 17841401745751020;
		$appID = env('instagram.appId');
		$appSecret = env('instagram.appSecret');
	    $file = file_get_contents(WRITEPATH.'instagram/instagram.txt');
		$file_data = explode(';', $file);
		$appToken = end($file_data);
		if(strtotime($file_data[0] . ' -10 days') <= time()) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $appToken);
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
            $response = json_decode($response);	
			if(!empty($response) && empty($response->error)) {
				$file_data[0] = date('Y-m-d H:i:s', strtotime('+' . $response->expires_in . ' seconds'));
				file_put_contents(WRITEPATH.'instagram/instagram.txt', implode(';', $file_data) . ';' . $response->access_token);
			}
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://graph.instagram.com/v10.0/17841401745751020/media?access_token=IGQVJWNGpTRUpKZAC1XbUc4cE9FdjBQUjNKb2VaMXZAXYzlIVndnVkM3VjlNcGF1a3plbXBTdFRaTGN4TFl4MXVJM29GWE56OTFnUlNNYnhYVWpRelhfQlhHSUZAlTjRIRGp0bWJfUnhn&fields=caption%2Cid%2Cpermalink%2Ctimestamp%2Cthumbnail_url%2Cmedia_url%2Cmedia_type&limit=100");
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close ($ch);
		if($server_output) {
			$server_output = json_decode($server_output);
			$insta_list = array();
			if(!empty($server_output->data)) {
				foreach($server_output->data as $media) {
					if(!empty($media->media_url)) {
					$tmp = explode('/', trim($media->permalink, '/'));
					$insta_list[] = array(
						'link' => $media->permalink,
						'short_code' => end($tmp),
						'created_at'=>str_replace('T',' ',substr($media->timestamp,0,19)),
						'caption' => !empty($media->caption) ? $media->caption : '',
						'likes' => '',
						'comments' => '',
						'photo' => $media->media_url,
						'thumb' => $media->media_url,
						'photo_id' => $media->id,
						'is_video' => $media->media_type == 'VIDEO',
					);
					}
					if(count($insta_list) >= 200) break;
				}
			}
		}
		
		if(!empty($insta_list)) {
		  foreach($insta_list as $inst) {     
			$check = $this->newsModel->db->table('instagram')->select('id')->where('short_code',$inst['short_code'])->get()->getRowArray();
			if(!empty($check['id'])) {
				$result = $this->newsModel->db->table('instagram')->set($inst)->Where('id',$check['id'])->update();
			}
            else {
               $result = $this->newsModel->db->table('instagram')->set($inst)->insert();
			}     
		  }
		}  
   }  
}
