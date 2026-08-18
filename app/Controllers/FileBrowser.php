<?php

namespace App\Controllers;


class FileBrowser extends BaseController {

    public function index() {
        $session = session();
        $config = [
            'datetimeFormat' => 'm/d/Y g:i A',
            'quality' => 90,
            'defaultPermission' => 0775,

            'sources' => [
                'default' => [],
            ],

            'createThumb' => true,
            'thumbFolderName' => '_thumbs',
            'excludeDirectoryNames' => ['.tmb', '.quarantine'],
            'maxFileSize' => '50mb',

            'allowCrossOrigin' => false,
            'allowReplaceSourceFile' => false,

            'baseurl' => '/files/',
            'root' => ROOTPATH . 'public/files/',
            'extensions' => [
                'jpg',
                'png',
                'gif',
                'jpeg',
                'ico',
                'jpeg',
                'svg',
                'ttf',
                'tif',
                'txt',
                'zip',
                'rar',
                '7z',
                'gz',
                'tar',
                'pps',
                'ppt',
                'pptx',
                'odp',
                'xls',
                'xlsx',
                'csv',
                'doc',
                'docx',
                'pdf',
                'rtf',
                'mp4'
            ],
            'imageExtensions' => ['jpg', 'png', 'gif', 'jpeg'],

                'debug' => false,
                'accessControl' => []
        ];


        $config['roleSessionVar'] = 'JoditUserRole';

        $config['accessControl'][] = array(
                'role'                => '*',

                'extensions'          => '*',
                'path'                => '/files/',

                'FILES'               => true,
                'FILE_MOVE'           => true,
                'FILE_UPLOAD'         => true,
                'FILE_UPLOAD_REMOTE'  => true,
                'FILE_REMOVE'         => true,
                'FILE_RENAME'         => true,

                'FOLDERS'             => true,
                'FOLDER_MOVE'         => true,
                'FOLDER_REMOVE'       => true,
                'FOLDER_RENAME'       => true,

                'IMAGE_RESIZE'        => true,
                'IMAGE_CROP'          => true,
        );

        $config['accessControl'][] = array(
                'role'                => '*',

                'extensions'          => 'exe,bat,com,sh,swf',

                'FILE_MOVE'           => false,
                'FILE_UPLOAD'         => false,
                'FILE_UPLOAD_REMOTE'  => false,
                'FILE_RENAME'         => false,
        );
        $fileBrowser = new JoditRestApplication($config);
        try {
            $fileBrowser->checkAuthentication();
            $fileBrowser->execute();
        } catch(\ErrorException $e) {
            $fileBrowser->exceptionHandler($e);
        }
    }
    
}