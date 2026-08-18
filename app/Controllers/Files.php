<?php

namespace App\Controllers;

use CodeIgniter\Controller;


class Files extends Controller
{
    public function index($module, $dir, $file)
    {
        if(file_exists(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
            $file_data = new \CodeIgniter\Files\File(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
            $this->response->setContentType($file_data->getMimeType());
            readfile(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
        }
    }
    
    public function download($id, $module, $dir, $file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
            if($id) {
                switch($module) {
                    case 'download': 
                        $model = new \Modules\Download\Models\DownloadModel();
                        $data = $model->db->table('download_files_lang dfl')->join('download_files df', 'dfl.id_file=df.id')->select('dfl.caption,df.ext,df.basename,df.name')->where('dfl.id', $id)->where('dfl.publish', 1)->get()->getRowArray();
                        if(!empty($data)) {
                            $model->db->table('download_files_lang')->set('downloads', '`downloads`+1', FALSE)->where('id', $id)->update();
                        }
                        break;
                }
            }
            if(!empty($data)) {
                return $this->response->download(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file, null)->setFileName(($data['caption'] ? $data['caption']: $data['name']).'.' . $data['ext']);
            } elseif(empty($id)) {
                return $this->response->download(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file, null);
            }
        }
    }
}