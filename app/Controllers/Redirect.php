<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Redirect extends Controller
{
    
    public function index($action, $hash)
    {
        $this->db = \Config\Database::connect();
        $this->locale = $this->request->getLocale() == $this->request->getDefaultLocale() ? '' : $this->request->getLocale();
        $lang = $this->db->table('language')->select('id,lang_code')->where('lang_code', $this->request->getLocale())->get()->getRowArray();
        if (empty($lang)) {
            $lang = $this->db->table('language')->select('id,lang_code')->where('default', 1)->first();
        }
        $this->id_lang = $lang['id'];
        $this->lang_code = $lang['lang_code'];
        if (method_exists($this, $action)) {
            return $this->$action($hash);
        }
    }
    
    private function aa($hash)
    {
        $ad = $this->db->table('advertisement a')->join('advertisement_lang al', 'a.id=al.id_advertisement')->select('a.id,a.url,al.id as al_id')->where('a.publish', 1)->where('a.hash', $hash)->where('al.id_lang', $this->id_lang)->get()->getRowArray();
        if(!empty($ad)) {
            $this->db->table('advertisement_lang')->set('clicks', 'clicks+1', false)->where('id', $ad['al_id'])->update();
            if(!empty($ad['url'])) {
                return redirect()->to($ad['url'], NULL, 'location'); 
                exit();
            }
        }
    }
    
}