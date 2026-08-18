<?php

namespace App\Controllers;

use App\Libraries\Page;
use App\Models\SettingsModel;
use App\Models\PageModel;


class Robots extends BaseController {
    
    public function index() 
    {
        $disallow = array(
            '/load',
            '/scripts',
        );
        $pageClass = new Page();
        $settingsModel = new SettingsModel();
        $pageModel = new PageModel();
        $languages = $pageClass->getLanguages();
        $index = $settingsModel->select('value')->where('name', 'index_website')->first();
        if(!empty($index['value'])) {
            $pages = $pageModel->select('id')->where('publish', 1)->where('no_index', 1)->findAll();
            if(!empty($pages)) {
                foreach($pages as $p) {
                    $langs = $pageModel->db->table('page_lang pl')->join('links l', 'l.id=pl.id_link')->select('l.link,pl.id_lang')->where('pl.id_page', $p['id'])->get()->getResultArray();
                    if(!empty($langs)) {
                        foreach($langs as $lang) {
                            $disallow[] = (!empty($languages) && !empty($languages[$lang['id_lang']]) && $languages[$lang['id_lang']]['slug'] ? '/' . $languages[$lang['id_lang']]['slug'] : '') . '/' . $lang['link'];
                        }
                    }
                }
            }
        }
        $this->response->setContentType('text/plain');
        echo view('robots', array('index' => !empty($index['value']) ? $index['value'] : '', 'disallow' => $disallow));
    }
}