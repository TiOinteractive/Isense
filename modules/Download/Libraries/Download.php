<?php

namespace Modules\Download\Libraries;

use Modules\Download\Models\DownloadModel;

class Download
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->downloadModel = new DownloadModel();
    }
    
    public function index($content, $id_lang, $slug='') 
    {
        switch($slug) {
            case 'single':
                return $this->single($content, $id_lang);
                break;
            case 'list':
            default:
                return $this->list($content, $id_lang);
                break;
        }
    }
    
    private function single($content, $id_lang) 
    {
        $download = $this->downloadModel
                ->select('id,template')
                ->where('publish', 1)
                ->where('id_page_cont', $content['id'])
                ->first();
        if(!empty($download)) {
            $download['files'] = $this->downloadModel->db
                    ->table('download_files df')
                    ->join('download_files_lang dfl', 'df.id=dfl.id_file', 'left')
                    ->select('df.id,df.path,df.name,df.basename,df.ext,df.mime,dfl.id as id_dfl,dfl.caption,dfl.downloads')
                    ->where('dfl.publish', 1)
                    ->where('dfl.id_lang', $id_lang)
                    ->where('dfl.id_download', $download['id'])
                    ->orderBy('dfl.order', 'ASC')
                    ->get()
                    ->getResultArray();
        }
        return $download;
    }
    
    private function list($content, $id_lang) 
    {

        $categories = $this->downloadModel->db
                ->table('download_cat dc')
                ->join('download_cat_lang dcl', 'dc.id=dcl.id_cat')
                ->select('dc.id,dcl.name')
                ->where('dc.publish', 1)
                ->where('dcl.id_lang', $id_lang)
                ->where('dc.id_page_cont', $content['id'])
                ->orderBy('dc.order', 'ASC')
                ->get()
                ->getResultArray();
        if(!empty($categories)) {
            foreach($categories as $k=>$cat) {
                $categories[$k]['files'] = $this->downloadModel->db
                    ->table('download_files df')
                    ->join('download_files_lang dfl', 'df.id=dfl.id_file', 'left')
                    ->select('df.id,df.path,df.name,df.basename,df.ext,df.mime,dfl.id as id_dfl,dfl.caption,dfl.downloads')
                    ->where('dfl.publish', 1)
                    ->where('dfl.id_lang', $id_lang)
                    ->where('dfl.id_cat', $cat['id'])
                    ->orderBy('dfl.order', 'ASC')
                    ->get()
                    ->getResultArray();
					
				if(!empty($categories[$k]['files'])) {
                   foreach($categories[$k]['files'] as $f=>$file) {
					if(file_exists(WRITEPATH . 'uploads/'.$file['path'])) { 
					 $categories[$k]['files'][$f]['file_size']=filesize(WRITEPATH . 'uploads/'.$file['path']);  
					}   
				   }	   
                }						
            }
        }
        return array(
            'list' => $categories
        );
    }
	
	public function assets($slug='', $template='', $id_banner=0, $data=array()) {
        $assets = array(
            'js' => array(),
            'css' => array()
        );
        switch($slug) {
            default :
                $assets['js'][] = '/assets/js/page.js';
                break;
        }
        return $assets;
    }
}