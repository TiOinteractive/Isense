<?php

namespace Modules\Shopping\Libraries;
use App\Models\PageContentModel;

class Shopping {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->db_shopping = \Config\Database::connect('shopping');
    }

    public function index($content, $id_lang, $slug = '', $link = array()) {
        $config = new \Config\Pager;
		$list=array();
        switch ($slug) {
            case 'products_list':
            default:
				// Zabezpieczenie: baza 'shopping' bywa niedostepna (zdalny host).
				// Przy bledzie polaczenia/zapytania zwracamy pusta liste zamiast
				// przerywac renderowanie calej strony.
				try {
					$query=$this->db_shopping->table('xml_produkty xp')->join('sklepy s', 'xp.id_sklep=s.id')->select('xp.nazwa,xp.zdjecie_glowne,xp.cena,xp.cena_old,xp.id_link,xp.id_sklep,s.nazwa as nazwa_sklep,s.id_link as sklep_link')->Limit($content['config']['no']);
					if(!empty($content['config']['shops'])) {
						$shops=explode(',',$content['config']['shops']);
						$query->WhereIn('id_sklep',$shops);
					}
					switch ($content['config']['order']) {
						case 'random':
							$query->orderBy('xp.id', 'RANDOM');
							break;
						case 'latest':
							$query->orderBy('xp.id', 'DESC');
							break;
					}
					$list=$query->get()->getResultArray();
					if(!empty($list)) {
					   foreach($list as $k=>$el) {
							$list[$k]['product_link']=$this->db_shopping->table('linki')->select('link')->Where('id',$el['id_link'])->get()->getRowArray();
					        $list[$k]['shop_link']=$this->db_shopping->table('linki')->select('link')->Where('id',$el['sklep_link'])->get()->getRowArray();
					   }
					}
				} catch (\Throwable $e) {
					log_message('error', 'Modul Shopping - baza niedostepna: {msg}', ['msg' => $e->getMessage()]);
					$list = array();
				}
				break;
        }
        
        return array(
            'views_dir' => 'products_list',
			'list'=>$list
        );
    }

    
}