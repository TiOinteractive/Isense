<?php

namespace Modules\Wyswig\Libraries;

use Modules\Wyswig\Models\WyswigModel;

class Wyswig
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->wyswigModel = new WyswigModel();
    }
    
    public function index($content, $id_lang, $slug='') 
    {
        $wyswig = $this->wyswigModel
                ->join('wyswig_lang wl', 'wyswig.id=wl.id_wyswig')
                ->select('wyswig.id,wl.content')
                ->where('wyswig.id_page_cont', $content['id'])
                ->where('wl.id_lang', $id_lang)
                ->first();
        return $wyswig;
    }
    
    
}