<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Images\Handlers\GDHandler;
use CodeIgniter\Images\Handlers\ImageMagickHandler;

class Images extends BaseConfig
{
    /**
     * Default handler used if no other handler is specified.
     *
     * @var string
     */
    public $defaultHandler = 'gd';

    /**
     * The path to the image library.
     * Required for ImageMagick, GraphicsMagick, or NetPBM.
     *
     * @var string
     */
    //public $libraryPath = '/usr/local/bin/convert';
    public $libraryPath = '/usr/lib/x86_64-linux-gnu/';

    /**
     * The available handler classes.
     *
     * @var array<string, string>
     */
    public $handlers = [
        'gd'      => GDHandler::class,
        'imagick' => ImageMagickHandler::class,
    ];
    
    public $maxImageDims = '';
    public $maxFileSize = '';
    public $imageMimeIn = '';
    public $audioMimeIn = '';
    public $videoMimeIn = '';
    public $otherMimeIn = '';
    public $importMimeIn = '';
    public $mimeIn = '';
    public $extIn = '';
}
