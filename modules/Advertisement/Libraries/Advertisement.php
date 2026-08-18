<?php

namespace Modules\Advertisement\Libraries;

use Modules\Advertisement\Models\AdvertisementModel;
use App\Libraries\Link;
use App\Libraries\Page;

class Advertisement {

    public function __construct() {
        $this->pageClass = new Page();
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->advertisementModel = new AdvertisementModel();
    }
    
    public function index($content, $id_lang, $slug='') 
    {
        if(empty($content['id_element'])) {
            return null;
        }
        $advertisement = $this->getAdvertisement($content['id_element'], $id_lang);
        return $advertisement;
    }
    
    public function showAdvertisement($id, $id_lang, $locale) 
    {
        $advertisement = $this->getAdvertisement($id, $id_lang);
        if(!empty($advertisement) && !empty($advertisement['template'])) {
            return view('Modules\Advertisement\Views\user\\' . $advertisement['template'], array('data' => $advertisement, 'locale' => $locale));
        }
    }
    
    private function getAdvertisement($id, $id_lang) 
    {
        $advertisement = $this->advertisementModel->getAdvertisement($id, $id_lang);
        return $advertisement;
    }
}