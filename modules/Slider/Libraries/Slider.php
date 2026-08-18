<?php

namespace Modules\Slider\Libraries;

use Modules\Slider\Models\SliderModel;

class Slider
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->sliderModel = new SliderModel();
    }
    
    public function index($content, $id_lang, $slug='') 
    {
        if(!empty($content['id_element'])) {
            $slider = $this->sliderModel
                    ->join('slider_lang sl', 'slider.id=sl.id_slider')
                    ->select('slider.id,sl.name')
                    ->where('slider.id', $content['id_element'])
                    ->where('slider.publish', 1)
                    ->where('sl.id_lang', $id_lang)
                    ->first();
            if(!empty($slider)) {
                $slider['slides'] = $this->sliderModel->db
                        ->table('slider_slide ss')
                        ->join('slider_slide_lang ssl', 'ss.id=ssl.id_slide')
                        ->join('files f1', 'f1.id=ssl.id_photo', 'left')
                        ->join('files f2', 'f2.id=ssl.id_m_photo', 'left')
                        ->join('files f3', 'f3.id=ssl.id_video', 'left')
                        ->select('ss.id,ssl.title,ssl.caption,ssl.description,ssl.url,ssl.video_url,f1.path as photo,f2.path as mphoto,f3.path as video,f3.mime as video_mime')
                        ->where('ssl.publish', 1)
                        ->where('ss.archive', 0)
                        ->groupStart()
                            ->groupStart()
                                ->where('ssl.time_start<=', date('Y-m-d H:i:s'))
                                ->orWhere('ssl.time_start', null)
                            ->groupEnd()
                            ->groupStart()
                                ->where('ssl.time_end>=', date('Y-m-d H:i:s'))
                                ->orWhere('ssl.time_end', null)
                            ->groupEnd()
                        ->groupEnd()
                        ->where('ss.id_slider', $slider['id'])
                        ->where('ssl.id_lang', $id_lang)
                        ->orderBy('ss.order', 'ASC')
                        ->get()
                        ->getResultArray();
                if(!empty($slider['slides'])) {
                    foreach($slider['slides'] as $k=>$slide) {
                        if(!empty($slide['video_url'])) {
                            preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $slide['video_url'], $matches);
                            if(!empty($matches)) {
                                $slider['slides'][$k]['external_id'] = $matches[0];
                                $slider['slides'][$k]['video_source'] = 'youtube';
                            } else {
                                preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $slide['video_url'], $matches);
                                if(!empty($matches) && !empty($matches[3])) {
                                    $slider['slides'][$k]['external_id'] = $matches[3];
                                    $slider['slides'][$k]['video_source'] = 'vimeo';
                                }
                                preg_match('!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!', $slide['video_url'], $matches);
                                if(!empty($matches)) {
                                    if(isset($matches[6])) {
                                        $slider['slides'][$k]['external_id'] = $matches[6];
                                        $slider['slides'][$k]['video_source'] = 'dailymotion';
                                    } elseif(isset($matches[4])) {
                                        $slider['slides'][$k]['external_id'] = $matches[4];
                                        $slider['slides'][$k]['video_source'] = 'dailymotion';
                                    } elseif(isset($matches[2])) {
                                        $slider['slides'][$k]['external_id'] = $matches[2];
                                        $slider['slides'][$k]['video_source'] = 'dailymotion';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $slider;
        }
        return null;
    }
    
    public function assets($slug='', $template='', $id_slider=0, $data=array()) {
        $assets = array(
            'js' => array(),
            'css' => array()
        );
        switch($slug) {
            default :
                $assets['css'][] = '/assets/css/slick.css';
                $assets['js'][] = '/assets/js/video.js';
                $assets['js'][] = '/assets/js/slick.min.js';
                $assets['js'][] = '/assets/js/page.js';
                break;
        }
        return $assets;
    }
    
    public function getContentMetaTags($content, $metatags, $data, $settings, $language) {
        if (empty($data)) {
            return $metatags;
        }
        
        
        
        return $metatags;
    }
    
}