<?php

namespace App\Controllers;

use App\Models\ModuleModel;
use App\Models\SettingsModel;
use App\Models\LanguageModel;
use App\Libraries\Link;
use App\Libraries\Page;
use App\Libraries\Assets;

class Errors extends BaseController
{
    public function __construct() 
    {
        $this->session = session();
        $this->languageModel = new LanguageModel();
        $this->pageClass = new Page();
		$this->assetsClass = new Assets(array(
            'css' => array('/adm/third-party/font-awasome/css/all.min.css', '/assets/css/header.css', '/assets/css/style.css', '/assets/css/foto.css', '/assets/css/tiolightbox.css'),
            'js' => array('/assets/js/jquery.min.js', '/assets/js/javascript.js', '/assets/js/social.js', '/assets/js/newsletter.js','/assets/js/jquery.touchSwipe.min.js','/assets/js/tiolightbox.js', '/assets/js/jquery-confirm.min.js', '/assets/js/event.js','/assets/js/jquery.raty.js', '/assets/js/page_wojtek.js', '/assets/js/header.js'),
            'css_footer' => array('/assets/css/jquery-confirm.min.css'),
            'js_ready' => array('$("a[rel=lightbox]").TiO_lightbox();')
        ));
    }
    
    public function show404()
    {
		$linkClass = new Link(); 
        $language = $this->pageClass->checkLanguage();
        if(empty($language)) {
            $lang = $this->pageClass->getPrimaryLang();
            $this->request->setLocale($lang['lang_code']);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            exit();
        }
        $this->request->setLocale($language['lang_code']);
        $id_lang = $language['id'];
        $languages = $this->pageClass->getLanguages();
        $settings = $this->pageClass->getSettings($id_lang);
        $metatags = $this->pageClass->getDefaultMetatags($settings, $language);
        $agent = $this->request->getUserAgent();
        $is_mobile = $agent->isMobile();
		$global_links = $linkClass->getGlobalLinks($id_lang, $language['slug']);
        $global_links = array_merge($global_links, $this->pageClass->getSpecialWebsites($id_lang, $language['slug']));
        $global_links = array_merge($global_links, $this->pageClass->getDirectWebsites($id_lang, $language['slug']));
		$footer_flashdata[] = $this->session->getFlashdata('newsletter_action');
        // Widok 404 w theme iSense jest samodzielnym dokumentem (własny <head>, header/footer, JS iSense).
        echo view('user/errors/404', array('metatags' => $metatags, 'mobile' => $is_mobile, 'global_links' => $global_links, 'settings' => $settings, 'languages' => $languages, 'locale' => $language['slug'], 'id_lang' => $id_lang));
		
		
    }
}