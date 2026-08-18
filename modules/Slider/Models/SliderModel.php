<?php

namespace Modules\Slider\Models;  
use CodeIgniter\Model;

class SliderModel extends Model{

    protected $table = 'slider';
    
    protected $allowedFields = [
        'order',
        'publish',
        'archive',
        'edited_at',
        'created_at'
    ];
    
    public function getSliderById($id, $id_lang, $archive=0) 
    {
        $slider = $this->where('id', $id)->first();
        if(!empty($slider)) {
            $slider['lang'] = $this->getSliderLang($id);
            if(!empty($slider['lang']) && !empty($slider['lang'][$id_lang]) && !empty($slider['lang'][$id_lang]['name'])) {
                $slider['name'] = $slider['lang'][$id_lang]['name'];
            } else {
                $slider['name'] = '';
            }
            $slider['slide'] = $this->getSliderSlides($id, $archive);
        }
        return $slider;
    }
    
    public function getSliderLang($id) 
    {
        $langs = array();
        $data = $this->db->table('slider_lang')->where('id_slider', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = array(
                    'name' => $d['name']
                );
            }
        }
        return $langs;
    }
    
    public function getSliderSlides($id, $archive=0) 
    {
        $slides = $this->db->table('slider_slide')->where('id_slider', $id)->orderBy('order', 'ASC')->where('archive', $archive)->get()->getResultArray();
        if(!empty($slides)) {
            foreach($slides as $k=>$s) {
                $slides[$k]['lang'] = $this->getSliderSlideLang($s['id']);
            }
        }
        return $slides;
    }
    
    public function getSliderSlideLang($id_slide) 
    {
        $langs = array();
        $data = $this->db->table('slider_slide_lang')->where('id_slide', $id_slide)->orderBy('id_lang', 'ASC')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                if(!empty($d['id_photo'])) {
                    $d['photo'] = $this->db->table('tio_files')->where('id', $d['id_photo'])->limit(1)->get()->getRowArray();
                }
                if(!empty($d['id_m_photo'])) {
                    $d['m_photo'] = $this->db->table('tio_files')->where('id', $d['id_m_photo'])->limit(1)->get()->getRowArray();
                }
                if(!empty($d['id_video'])) {
                    $d['video'] = $this->db->table('tio_files')->where('id', $d['id_video'])->limit(1)->get()->getRowArray();
                    $d['video_radio'] = 'id-video';
                } elseif(!empty($d['video_url'])) {
                    $d['video_radio'] = 'video-url';
                }
                if (!empty($d['time_start']) && !empty($d['time_end'])) {
                    $d['time'] = date('d.m.Y H:i', strtotime($d['time_start'])) . ' - ' . date('d.m.Y H:i', strtotime($d['time_end']));
                } else {
                    $d['time'] = '';
                }
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function saveSlider($id, $post, $archive=0) 
    {
        if(empty($post)) return false;
        $this->db->transStart();
        if(!$archive) {
            $data = array(
                'publish' => !empty($post['publish']) ? $post['publish'] : 0
            );
            if($id) {
                $result = $this->set($data)->where('id', $id)->update();
                $this->id = $id;
            } else {
                $result = $this->insert($data);
                $this->id = $this->getInsertID();
            }
            $this->saveSliderLang($this->id, $post['lang']);
        } else {
            $this->id = $id;
        }
        $this->saveSliderSlides($this->id, $post['slide'], $archive);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveSliderLang($id_slider, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_slider' => $id_slider,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                );
                $slider = $this->db->table('slider_lang')->select('id')->where('id_slider', $id_slider)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($slider) && !empty($slider['id'])) {
                    $result = $this->db->table('slider_lang')->set($data)->where('id_slider', $id_slider)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('slider_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveSliderSlides($id, $slides, $archive=0) 
    {
        $ids = array();
        if(!empty($slides)) {
            foreach($slides as $slide) {
                $data = array(
                    'id_slider' => $id,
                    'order' => $slide['order'],
                    'archive' => !empty($slide['archive']) ? $slide['archive'] : 0
                );
                if($slide['id'] && !empty($this->db->table('slider_slide')->select('id')->where('id', $slide['id'])->get()->getResultArray())) {
                    $result = $this->db->table('slider_slide')->set($data)->where('id', $slide['id'])->update();
                    $id_slide = $slide['id'];
                } else {
                    $result = $this->db->table('slider_slide')->insert($data);
                    $id_slide = $this->db->insertID();
                }
                $ids[] = $id_slide;
                $this->saveSliderSlideLang($id_slide, $slide['lang']);
            }
        }
        $query = $this->db->table('slider_slide')->select('id')->where('id_slider', $id)->where('archive', $archive);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $slide_list = $query->get()->getResultArray();
        if(!empty($slide_list)) {
            foreach($slide_list as $slide) {
                $this->db->table('slider_slide_lang')->where('id_slide', $slide['id'])->delete();
                $this->db->table('slider_slide')->where('id', $slide['id'])->delete();
            }
        }
    }
    
    private function saveSliderSlideLang($id_slide, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                if (!empty($lang['time'])) {
                    $tmp = explode('-', $lang['time']);
                    $lang['time_start'] = !empty($tmp) && !empty($tmp[0]) ? $tmp[0] : '';
                    $lang['time_end'] = !empty($tmp) && !empty($tmp[1]) ? $tmp[1] : '';
                }
                $data = array(
                    'id_slide' => $id_slide,
                    'id_lang' => $id_lang,
                    'id_photo' => !empty($lang['photo']) && !empty($lang['photo']['id']) ? $lang['photo']['id'] : 0,
                    'id_m_photo' => !empty($lang['m_photo']) && !empty($lang['m_photo']['id']) ? $lang['m_photo']['id'] : 0,
                    'title' => $lang['title'],
                    'caption' => $lang['caption'],
                    'description' => $lang['description'],
                    'url' => $lang['url'],
                    'publish' => !empty($lang['publish']) ? $lang['publish'] : 0,
                    'video_url' => $lang['video_radio'] == 'video-url' ? $lang['video_url'] : '',
                    'id_video' => $lang['video_radio'] == 'id-video' && !empty($lang['video']) && !empty($lang['video']['id']) ? $lang['video']['id'] : 0,
                    'time_start' => !empty($lang['time_start']) ? date('Y-m-d H:i:s', strtotime($lang['time_start'])) : null,
                    'time_end' => !empty($lang['time_end']) ? date('Y-m-d H:i:s', strtotime($lang['time_end'])) : null,
                );
                $slide = $this->db->table('slider_slide_lang')->select('id')->where('id_slide', $id_slide)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($slide) && !empty($slide['id'])) {
                    $result = $this->db->table('slider_slide_lang')->set($data)->where('id_slide', $id_slide)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('slider_slide_lang')->insert($data);
                }
            }
        }
    }
    
    public function deleteSlider($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $slide_list = $this->db->table('slider_slide')->select('id')->where('id_slider', $id)->get()->getResultArray();
        if(!empty($slide_list)) {
            foreach($slide_list as $slide) {
                $this->db->table('slider_slide_lang')->where('id_slide', $slide['id'])->delete();
                $this->db->table('slider_slide')->where('id', $slide['id'])->delete();
            }
        }
        $this->db->table('slider_lang')->where('id_slider', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getElementPhotosForSitemap($id)
    {
        $photos = array();
        $slider = $this->select('id')->where('id', $id)->where('publish', 1)->first();
        if(empty($slider)) return array();
        $slides = $this->db->table('slider_slide ss')->select('ss.id')->where('ss.id_slider', $id)->get()->getResultArray();
        if(!empty($slides)) {
            foreach($slides as $slide) {
                $lang = $this->db->table('slider_slide_lang ssl')->join('files f1', 'f1.id=ssl.id_photo', 'left')->join('files f2', 'f2.id=ssl.id_m_photo', 'left')->select('ssl.title,f1.path as path1,f2.path as path2')->where('ssl.id_slide', $slide['id'])->where('ssl.publish', 1)->get()->getResultArray();
                if(!empty($lang)) {
                    foreach($lang as $l) {
                        if(!empty($l['path1'])) {
                            $photos[] = array(
                                'path' => $l['path1'],
                                'caption' => $l['title']
                            );
                        }
                        if(!empty($l['path2'])) {
                            $photos[] = array(
                                'path' => $l['path2'],
                                'caption' => $l['title']
                            );
                        }
                    }
                }
            }
        }
        return $photos;
    }
}

