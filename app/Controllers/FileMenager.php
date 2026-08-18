<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Files\File;
use App\Models\LanguageModel;
use App\Models\FileModel;
use App\Libraries\UploadHandler;

class FileMenager extends BaseController {

    public function index($action = '', $module = '', $id = 0) {
        $session = session();
        $languageModel = new LanguageModel();
        $this->languages = $languageModel->select('id,name,short_name,slug,default')->where('publish', 1)->orderBy('default', 'DESC')->findAll();
        $this->locale = $this->request->getLocale() == $this->request->getDefaultLocale() ? '' : $this->request->getLocale();
        $lang = $languageModel->select('id,lang_code')->where('lang_code', $this->request->getLocale())->first();
        if (empty($lang)) {
            $lang = $languageModel->select('id,lang_code')->where('default', 1)->first();
        }
        $this->id_lang = $lang['id'];
        $this->lang_code = $lang['lang_code'];
        if ($this->request->isAJAX()) {
            return $this->ajax($action, $module, $id);
        } elseif($module == 'newsletter') {
            return $this->newsletter($action, $id);
        }
    }

    public function ajax($action = '', $module = '', $id = 0) {
        if (!empty($action)) {
            switch ($action) {
                case 'open': return $this->openModal($module);
                    break;
                case 'add': return $this->addFile();
                    break;
                case 'upload': return $this->uploadFile($module);
                    break;
                case 'delete_file': return $this->deleteFile($module, $id);
                    break;
                case 'filter-files': return $this->filterFles($module, $id);
                    break;
                case 'load-more': return $this->loadMoreFiles($module, $id);
                    break;
				case 'crop': return $this->cropModal();
                    break;	
				case 'crop_save': return $this->cropSave();
                 break;		
            }
        }
    }

    private function openModal($module) 
    {
        $fileModel = new FileModel();
        $post = $this->request->getPost();
        $multi = false;
        $files = array();
        $count = 0;
        if(!empty($post)) {
            if(!empty($post['multi'])) {
                $multi = true;
            }
        }
        $query = $fileModel->select('id');
        $query = $this->prepareFilterQuery($query, $module, $post);
        $count_all = $query->countAllResults();
        
        if($count_all) {
            $query = $fileModel->orderBy('created_at', 'DESC');
            $query = $this->prepareFilterQuery($query, $module, $post);
            $files = $query->limit(30)->find();
            $count = count($files);
        }
        $html = view('admin/filemenager/modal', array('files' => $files, 'module' => $module, 'locale' => $this->locale, 'multi' => $multi, 'type' => !empty($post['type']) ? $post['type'] : '', 'count' => $count, 'count_all' => $count_all));
        return $this->response->setJSON(array(
            'status' => true,
            'count' => $count,
            'count_all' => $count_all,
            'html' => base64_encode(urlencode($html))
        ));
    }

    private function filterFles($module, $id) 
    {
        $fileModel = new FileModel();
        $post = $this->request->getPost();
        $multi = false;
        $files = array();
        $count = 0;
        if(!empty($post) && !empty($post['filters'])) {
            if(!empty($post['filters']['multi'])) {
                $multi = true;
            }
        }
        
        $query = $fileModel->select('id');
        $query = $this->prepareFilterQuery($query, $module, !empty($post['filters']) ? $post['filters'] : array());
        $count_all = $query->countAllResults();
        if($count_all) {
            $query = $fileModel->orderBy('created_at', 'DESC');
            $query = $this->prepareFilterQuery($query, $module, !empty($post['filters']) ? $post['filters'] : array());
            $files = $query->limit(30)->find();
            $count = count($files);
        }
        $html = '';
        if(!empty($files)) {
            foreach($files as $file) {
                $html .= view('admin/filemenager/upload_success', array('file' => $file, 'module' => $module, 'locale' => $this->locale, 'multi' => $multi, 'type' => !empty($post['type']) ? $post['type'] : '', 'server' => true));
            }
        } else {
            $html = '<p class="no-files">' . lang('Admin.file-menager.NoFiles') . '</p>';
        }
        
        return $this->response->setJSON(array(
            'status' => true,
            'count' => $count,
            'count_all' => $count_all,
            'count_info' => lang('Admin.file-menager.CountDisplayedFiles', [$count, $count_all]),
            'html' => base64_encode(urlencode($html))
        ));
    }
    
    private function loadMoreFiles($module, $id) 
    {
        $fileModel = new FileModel();
        $post = $this->request->getPost();
        $multi = false;
        $files = array();
        $count = 0;
        $limit = 30;
        if(!empty($post)) {
            if(!empty($post['count'])) {
                $count = $post['count'];
            }
            if(!empty($post['filters']) && !empty($post['filters']['multi'])) {
                $multi = true;
            }
        }
        $query = $fileModel->select('id');
        $query = $this->prepareFilterQuery($query, $module, !empty($post['filters']) ? $post['filters'] : array());
        $count_all = $query->countAllResults();
        if($count_all) {
            if($count && $count % $limit != 0) {
                $limit = $limit + ($limit - ($count % $limit));
            }
            $query = $fileModel->orderBy('created_at', 'DESC');
            $query = $this->prepareFilterQuery($query, $module, !empty($post['filters']) ? $post['filters'] : array());
            $files = $query->limit($limit, intval($count))->find();
            $count += count($files);
        }
        $html = '';
        if(!empty($files)) {
            foreach($files as $file) {
                $html .= view('admin/filemenager/upload_success', array('file' => $file, 'module' => $module, 'locale' => $this->locale, 'multi' => $multi, 'type' => !empty($post['type']) ? $post['type'] : '', 'server' => true));
            }
        }
        
        return $this->response->setJSON(array(
            'status' => true,
            'count' => $count,
            'count_all' => $count_all,
            'count_info' => lang('Admin.file-menager.CountDisplayedFiles', [$count, $count_all]),
            'html' => base64_encode(urlencode($html))
        ));
    }
    
    private function prepareFilterQuery($query, $module, $filters) 
    {
        if(!empty($filters)) {
            if(!empty($filters['type'])) {
                switch($filters['type']) {
                    case 'other': $query->whereNotIn('type', array('image', 'audio', 'video', 'document', 'spreadsheet', 'presentation', 'archive'));
                        break;
                    case 'detached': 
                        break;
                    default: $query->where('type', $filters['type']);
                        break;
                }
            }
            if(!empty($filters['date'])) {
                $date_range = explode('-', $filters['date']);
                if(!empty($date_range[0])) {
                    $query->where('created_at>=', date('Y-m-d', strtotime($date_range[0])));
                }
                if(!empty($date_range[1])) {
                    $query->where('created_at<=', date('Y-m-d', strtotime($date_range[1])) . ' 23:59:59');
                }
            }
            if(!empty($filters['search'])) {
                $query->like('basename', $post['filters']['search']);
            }
        }
        return $query;
    }

    private function addFile() {
        $post = $this->request->getPost();
        $fileModel = new FileModel();
        if(empty($post) || empty($post['module'])) {
            $post['module'] = '';
        }
        switch($post['module']) {
            case 'newsletter': 
                $file = $fileModel->whereIn('id', $post['files'])->orderBy('created_at', 'DESC')->first();
                $html = view('admin/filemenager/files_list_newsletter', array('file' => $file, 'name' => $post['name'], 'key_name' => $post['key_name']));
                
                $response = array(
                    'status' => true,
                    'path' => base_url() . 'image/ext/' . $file['path'],
                    'html' => base64_encode(urlencode($html))
                );
                break;
            default: 
                $files = $fileModel->whereIn('id', $post['files'])->orderBy('created_at', 'DESC')->findAll();
                $html = view('admin/filemenager/files_list', array('files' => $files, 'name' => $post['name'], 'key_name' => $post['key_name']));
                $response = array(
                    'status' => true,
                    'html' => base64_encode(urlencode($html))
                );
                break;
        }
        return $this->response->setJSON($response);
    }

    private function uploadFile($module = '') {
        helper(['form', 'url', 'file', 'filesystem']);
        $config = new \Config\Images();
        $post = $this->request->getPost();
        $multi = !empty($post['multi']) ? $post['multi'] : false;
		$crop = !empty($post['crop']) ? $post['crop'] : false;
		$choose_main= !empty($post['choose_main']) ? $post['choose_main'] : false;
        $list_type = !empty($post['filter_type']) ? $post['filter_type'] : '';
        $count = 0;
        $count_all = 0;
        if(!empty($post)) {
            if(!empty($post['count'])) {
                $count = $post['count'];
            }
            if (empty($post['type'])) {
                $post['type'] = '';
            }
        }
        if($module == 'import') {
            $map = scandir(WRITEPATH . 'uploads/' . $module);
            if(!empty($map)) {
                foreach($map as $d) {
                    if(!in_array($d, array('.', '..'))) {
                        if(filectime(WRITEPATH . 'uploads/' . $module . '/' . $d) < strtotime('- 7 days')) {
                            delete_files(WRITEPATH . 'uploads/' . $module . '/' . $d, true);
                        }
                    }
                }
            }
        }
        switch ($post['type']) {
            case 'image':
                $validationRule = [
                    'file' => [
                        'label' => 'File',
                        'rules' => 'uploaded[file]'
                        . '|mime_in[file,' . $config->imageMimeIn . ']'
                        . '|max_size[file,' . $config->maxFileSize . ']'
                        . '|max_dims[file,' . $config->maxImageDims . ']'
                        . '|is_image[file]',
                        'errors' => [
                            'uploaded' => lang('Admin.file-menager.NotValidUploaded'),
                            'mime_in' => lang('Admin.file-menager.WrongFileType'),
                            'max_size' => lang('Admin.file-menager.FileIsToBig'),
                            'max_dims' => lang('Admin.file-menager.FileDimsAreToBig'),
                            'is_image' => lang('Admin.file-menager.FileIsNotImage'),
                        ],
                    ],
                ];
                break;
            case 'audio':
                $validationRule = [
                    'file' => [
                        'label' => 'File',
                        'rules' => 'uploaded[file]'
                        . '|mime_in[file,' . $config->audioMimeIn . ']'
                        . '|max_size[file,' . $config->maxFileSize . ']',
                        'errors' => [
                            'uploaded' => lang('Admin.file-menager.NotValidUploaded'),
                            'mime_in' => lang('Admin.file-menager.WrongFileType'),
                            'max_size' => lang('Admin.file-menager.FileIsToBig'),
                        ],
                    ],
                ];
                break;
            case 'video':
                $validationRule = [
                    'file' => [
                        'label' => 'File',
                        'rules' => 'uploaded[file]'
                        . '|mime_in[file,' . $config->videoMimeIn . ']'
                        . '|max_size[file,' . $config->maxFileSize . ']',
                        'errors' => [
                            'uploaded' => lang('Admin.file-menager.NotValidUploaded'),
                            'mime_in' => lang('Admin.file-menager.WrongFileType'),
                            'max_size' => lang('Admin.file-menager.FileIsToBig'),
                        ],
                    ],
                ];
                break;
            case 'import':
                $validationRule = [
                    'file' => [
                        'label' => 'File',
                        'rules' => 'uploaded[file]'
                     //   . '|mime_in[file,text/csv,text/xml]'
                        . '|ext_in[file,csv,xls,xlsx,xml]'
                        . '|max_size[file,' . $config->maxFileSize . ']',
                        'errors' => [
                            'uploaded' => lang('Admin.file-menager.NotValidUploaded'),
                     //       'mime_in' => lang('Admin.file-menager.WrongFileType'),
                            'ext_in' => lang('Admin.file-menager.WrongFileExtension'),
                            'max_size' => lang('Admin.file-menager.FileIsToBig'),
                        ],
                    ],
                ];
                break;
            default:
                $validationRule = [
                    'file' => [
                        'label' => 'File',
                        'rules' => 'uploaded[file]'
                        . '|mime_in[file,' . $config->mimeIn . ']'
                        . '|max_size[file,' . $config->maxFileSize . ']',
                        'errors' => [
                            'uploaded' => lang('Admin.file-menager.NotValidUploaded'),
                            'mime_in' => lang('Admin.file-menager.WrongFileType'),
                            'max_size' => lang('Admin.file-menager.FileIsToBig'),
                        ],
                    ],
                ];
                break;
        }
        if (empty($module)) {
            $module = 'filemenager';
        }
        
        $fileModel = new FileModel();
        $file_data = array();
        $response = [
            'success' => false,
            'save' => false,
            'data' => array(),
            'msg' => lang('Admin.file-menager.FileCouldNotUpload')
        ];
        $file = $this->request->getFile('file');
        if (!empty($file)) {
            if (!$this->validate($validationRule)) {
                $file_data = array(
                    'name' => $file->getClientName(),
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'error' => $this->validator->getError('file')
                );
                $response = [
                    'success' => false,
                    'save' => false,
                    'data' => [],
                    'files' => $file_data,
                    'html' => '',
                    'msg' => ""
                ];
            } else {
                if (!$file->hasMoved()) {
                    $ext = pathinfo($file->getClientName(), PATHINFO_EXTENSION);
                    $name = pathinfo($file->getClientName(), PATHINFO_FILENAME);
                    $mb_client_name = mb_url_title($name, '-') . '.' . $ext;
                    if(in_array($module, array('import'))) {
                        $file->move(WRITEPATH . 'uploads/' . $module . '/' . date('Ymd'), $mb_client_name, true);
                        $file_path =  $module . '/' . date('Ymd') . '/' . $mb_client_name;
                    } elseif (in_array($module, array('filemenager', 'download'))) {
                        $file_path = $file->store($module . '/' . date('Ymd'), $mb_client_name);
                    } else {
                        $file_path = $file->store($module . '/' . date('Ymd'), $mb_client_name);
                    }
                    $file_info = pathinfo($file_path);
                    $data = [
                        'name' => $name,
                        'basename' => $mb_client_name,//$file->getClientName(),
                        'path' => $file_path,
                        'mime' => $file->getClientMimeType(),
                        'type' => file_type($file->getClientMimeType()),
                        'ext' => $ext,
                        'publish' => 1,
                        'count' => $count,
                        'count_all' => 0,
                        'count_info' => '',
                    ];
                    $file_data[] = array(
                        'thumbnailUrl' => '/image/c/250/250/' . $file_path,
                        'name' => $file->getClientName(),
                        'url' => '/image/' . $file_path,
                        'type' => $data['type'],
                        'ext' => $ext,
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize()
                    );
                    switch($module) {
                        case 'download':
                            $save = $fileModel->db->table('download_files')->insert($data);
                            $data['id'] = $fileModel->db->insertID();
                            $html = !$list_type || $list_type == $data['type'] ? view('admin/filemenager/upload_success', array('file' => $data, 'module' => $module, 'multi' => $multi, 'locale' => $this->locale)) : '';
                            
                            $query = $fileModel->db->table('download_files')->select('id');
                            $query = $this->prepareFilterQuery($query, $module, array('type' => $list_type));
                            $data['count_all'] = $query->countAllResults();
                            $data['count_info'] = lang('Admin.file-menager.CountDisplayedFiles', [$count, $data['count_all']]);
                            if(!$list_type || $list_type == $data['type']) {
                                ++$data['count'];
                            }
                            $response = [
                                'success' => true,
                                'save' => $save,
                                'data' => $data,
                                'files' => $file_data,
                                'html' => base64_encode(urlencode($html)),
                                'msg' => lang('Admin.file-menager.FileSuccessfullyUploaded')
                            ];
                            break;
                        case 'filemenager':
                            $save = $fileModel->insert($data);
                            $data['id'] = $fileModel->insertID();
                            $html = !$list_type || $list_type == $data['type'] ? view('admin/filemenager/upload_success', array('file' => $data, 'module' => $module, 'multi' => $multi, 'locale' => $this->locale)) : '';

                            $query = $fileModel->select('id');
                            $query = $this->prepareFilterQuery($query, $module, array('type' => $list_type));
                            $data['count_all'] = $query->countAllResults();
                            if(!$list_type || $list_type == $data['type']) {
                                ++$data['count'];
                            }
                            $data['count_info'] = lang('Admin.file-menager.CountDisplayedFiles', [$data['count'], $data['count_all']]);
                            $response = [
                                'success' => true,
                                'save' => $save,
                                'data' => $data,
                                'files' => $file_data,
                                'html' => base64_encode(urlencode($html)),
                                'msg' => lang('Admin.file-menager.FileSuccessfullyUploaded')
                            ];
                            break;
                        case 'import':
                            $html = !$list_type || $list_type == $data['type'] ? view('admin/filemenager/upload_import', array('file' => $data, 'field' => $post['field'], 'multi' => $multi, 'no' => uniqid(), 'languages' => $this->languages)) : '';
                            $response = [
                                'success' => true,
                                'save' => false,
                                'data' => $data,
                                'files' => $file_data,
                                'html' => base64_encode(urlencode($html)),
                                'msg' => lang('Admin.file-menager.FileSuccessfullyUploaded')
                            ];
                            break;
                        default:
                            if(!empty($post['module']) && $post['module'] != 'undefined') {
                                $class = '\Modules\\' . ucfirst($post['module']) . '\Controllers\\' . ucfirst($post['module']) . 'Admin';
                                $module2 = new $class();
                                $module2->languages = $this->languages;
                                $module2->locale = $this->locale;
                                $module2->id_lang = $this->id_lang;
                                if (method_exists($module2, 'getFileUploadHtml')) {
                                    $html = $module2->getFileUploadHtml($post, $data);
                                }
                            }
                            if(empty($html)) {
                                $html = !$list_type || $list_type == $data['type'] ? view('admin/filemenager/upload_' . (!empty($post['option']) && is_file(APPPATH . 'Views/admin/filemenager/upload_' . $post['option'] . '.php') ? $post['option'] : 'file'), array('file' => $data, 'field' => $post['field'], 'multi' => $multi,'crop'=>$crop,'choose_main'=>$choose_main, 'no' => uniqid(), 'languages' => $this->languages)) : '';
                            }
                            $response = [
                                'success' => true,
                                'save' => false,
                                'data' => $data,
                                'files' => $file_data,
                                'html' => base64_encode(urlencode($html)),
                                'msg' => lang('Admin.file-menager.FileSuccessfullyUploaded')
                            ];
                            break;
                    }
                } else {
                    $response = [
                        'success' => false,
                        'save' => false,
                        'data' => $data,
                        'msg' => lang('Admin.file-menager.TheFileHasAlreadyBeenMoved')
                    ];
                }
            }
            return $this->response->setJSON($response);
        }
    }

    function deleteFile($module, $id) {
        $post = $this->request->getPost();
        $count = 0;
        $count_all = 0;
        $multi = false;
        if(!empty($post)) {
            if(!empty($post['count'])) {
                $count = $post['count'];
            }
            if(!empty($post['filters']) && !empty($post['filters']['multi'])) {
                $multi = true;
            }
        }
        $fileModel = new FileModel();
        $fileModel->db->transStart();
        switch ($module) {
            case 'download':
                $file = $fileModel->db->table('download_files')->select('path')->where('id', $id)->get()->getRowArray();
                if (file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
                    @unlink(WRITEPATH . 'uploads/' . $file['path']);
                }
                $fileModel->db->table('download_files')->where('id', $id)->delete();
                break;
            case 'filemenager':
            default:
                $file = $fileModel->db->table('files')->select('path')->where('id', $id)->get()->getRowArray();
                if (file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
                    @unlink(WRITEPATH . 'uploads/' . $file['path']);
                }
                $fileModel->db->table('files')->where('id', $id)->delete();
                $query = $fileModel->select('id');
                $query = $this->prepareFilterQuery($query, $module, !empty($post['filters']) ? $post['filters'] : array());
                $count_all = $query->countAllResults();
                break;
        }
        $fileModel->db->transComplete();
        $result = $fileModel->db->transStatus();
        if ($result) {
            --$count;
            $response = [
                'success' => true,
                'msg' => lang('Admin.file-menager.FileSuccessfullyDeleted'),
                'count' => $count,
                'count_all' => $count_all,
                'count_info' => lang('Admin.file-menager.CountDisplayedFiles', [$count, $count_all]),
            ];
        } else {
            $response = [
                'success' => false,
                'msg' => lang('Admin.file-menager.FileNotDeleted'),
                'count' => $count,
                'count_all' => $count_all,
                'count_info' => lang('Admin.file-menager.CountDisplayedFiles', [$count, $count_all]),
            ];
        }

        return $this->response->setJSON($response);
    }
    
    private function newsletter($action, $id) {
        helper(['url' ,'file']);
         if (!empty($action)) {
            $post = $this->request->getPost();
            switch ($action) {
                case 'async': 
                    $values = $post['slim'];
                    if ($values === false) {
                        return false;
                    }
                    $images = array();
                    if (!is_array($values)) {
                        $values = array($values);
                    }
                    foreach ($values as $value) {
                        $inputValue = $this->parseInput($value);
                        if ($inputValue) {
                            array_push($images, $inputValue);
                        }
                    }
                    $image = array_shift($images);
                    if (isset($image['output']['data'])) {
                        $path = WRITEPATH . 'uploads/newsletter/';
                        $dir = date('Ymd');
                        $name = sanitize_file_name($image['output']['name']);
                        $tmp = explode('.', $name);
                        $ext = end($tmp);
                        unset($tmp[count($tmp)-1]);
                        $name = implode('.', $tmp);
                        $data = $image['output']['data'];
                        $mb_client_name =  uniqid() . '-' . mb_url_title($name, '-') . '.' . $ext;
                        $path .= $dir . '/';
                        if(!is_dir($path)){
                            mkdir($path, 0755, true);
                        }
                        $path .= $mb_client_name;
                        $r = file_put_contents($path, $data);
                    }
                    
                    $response = [
                        'status' => 'success',
                        'file' => $mb_client_name,
                        'path' => base_url() . 'file/newsletter/' . $dir . '/' . $mb_client_name,
                        'write_path' => $path,
                    ];
                    break;
            }
            return $this->response->setJSON($response);
        }
    }
    private static function parseInput($value) {

        // if no json received, exit, don't handle empty input values.
        if (empty($value)) {return null;}

        // The data is posted as a JSON String so to be used it needs to be deserialized first
        $data = json_decode($value);

        // shortcut
        $input = null;
        $actions = null;
        $output = null;
        $meta = null;

        if (isset ($data->input)) {

            $inputData = null;
            if (isset($data->input->image)) {
                $inputData = Slim::getBase64Data($data->input->image);
            }
            else if (isset($data->input->field)) {
                $filename = $_FILES[$data->input->field]['tmp_name'];
                if ($filename) {
                    $inputData = file_get_contents($filename);
                }
            }

            $input = array(
                'data' => $inputData,
                'name' => $data->input->name,
                'type' => $data->input->type,
                'size' => $data->input->size,
                'width' => $data->input->width,
                'height' => $data->input->height,
            );

        }

        if (isset($data->output)) {
            
            $outputDate = null;
            if (isset($data->output->image)) {
                $outputData = Slim::getBase64Data($data->output->image);
            }
            else if (isset ($data->output->field)) {
                $filename = $_FILES[$data->output->field]['tmp_name'];
                if ($filename) {
                    $outputData = file_get_contents($filename);
                }
            }
            
            $output = array(
                'data' => $outputData,
                'name' => $data->output->name,
                'type' => $data->output->type,
                'width' => $data->output->width,
                'height' => $data->output->height
            );
        }

        if (isset($data->actions)) {
            $actions = array(
                'crop' => $data->actions->crop ? array(
                    'x' => $data->actions->crop->x,
                    'y' => $data->actions->crop->y,
                    'width' => $data->actions->crop->width,
                    'height' => $data->actions->crop->height,
                    'type' => $data->actions->crop->type
                ) : null,
                'size' => $data->actions->size ? array(
                    'width' => $data->actions->size->width,
                    'height' => $data->actions->size->height
                ) : null,
                'rotation' => $data->actions->rotation,
                'filters' => $data->actions->filters ? array(
                    'sharpen' => $data->actions->filters->sharpen
                ) : null
            );
        }

        if (isset($data->meta)) {
            $meta = $data->meta;
        }

        // We've sanitized the base64data and will now return the clean file object
        return array(
            'input' => $input,
            'output' => $output,
            'actions' => $actions,
            'meta' => $meta
        );
    }
	
	 private function cropModal() 
    {
        $post = $this->request->getPost();
        $html = view('admin/filemenager/modal_crop', array('post' => $post));
        return $this->response->setJSON(array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        ));
    }
	
	  private function cropSave() {
        $post = $this->request->getPost();
        $html = view('admin/filemenager/crop_save', array('post' => $post));
        return $this->response->setJSON(array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        ));
    }
}
