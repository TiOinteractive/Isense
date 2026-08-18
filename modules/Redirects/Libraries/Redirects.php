<?php

namespace Modules\Redirects\Libraries;

use Modules\Redirects\Models\RedirectsModel;

class Redirects
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->redirectsModel = new RedirectsModel();
    }
    
    public function checkRedirectsForUrl($from) 
    {
        if(empty($from)) return null;
        $redirect = $this->redirectsModel->db->table('redirects')->select('from,to,type,short')->where('publish', 1)->where('from', $from)->get()->getRowArray();
        return $redirect;
    }
}