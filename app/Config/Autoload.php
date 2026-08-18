<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 * the values in this file will overwrite the framework's values.
 */
class Autoload extends AutoloadConfig {

    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The '/app' and '/system' directories are already mapped for you.
     * you may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * Prototype:
     * ```
     *   $psr4 = [
     *       'CodeIgniter' => SYSTEMPATH,
     *       'App'	       => APPPATH
     *   ];
     * ```
     *
     * @var array<string, string>
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config' => APPPATH . 'Config',
        'Modules' => ROOTPATH . 'modules',
        'Modules\News' => ROOTPATH . 'modules/News',
        'Modules\Slider' => ROOTPATH . 'modules/Slider',
        'Modules\Banner' => ROOTPATH . 'modules/Banner',
        'Modules\Wyswig' => ROOTPATH . 'modules/Wyswig',
        'Modules\Isense' => ROOTPATH . 'modules/Isense',
        'Modules\Orders' => ROOTPATH . 'modules/Orders',
        'Modules\Gallery' => ROOTPATH . 'modules/Gallery',
        'Modules\Download' => ROOTPATH . 'modules/Download',
        'Modules\Form' => ROOTPATH . 'modules/Form',
        'Modules\Redirects' => ROOTPATH . 'modules/Redirects',
        'Modules\Maps' => ROOTPATH . 'modules/Maps',
        'Modules\Newsletter' => ROOTPATH . 'modules/Newsletter',
        'Modules\Translator' => ROOTPATH . 'modules/Translator',
        'Modules\Event' => ROOTPATH . 'modules/Event',
        'Modules\Cinema' => ROOTPATH . 'modules/Cinema',
        'Modules\Comments' => ROOTPATH . 'modules/Comments',
        'Modules\Advertisement' => ROOTPATH . 'modules/Advertisement',
        'Modules\Catalog' => ROOTPATH . 'modules/Catalog',
        'Modules\Survey' => ROOTPATH . 'modules/Survey',
        'Jodit\Connector' => ROOTPATH . 'vendor/jodit/connector',
        'Writable' => WRITEPATH,
        'Modules\Foto' => ROOTPATH . 'modules/Foto',
        'Modules\Flavors' => ROOTPATH . 'modules/Flavors',
        'Modules\Users' => ROOTPATH . 'modules/Users',
        'Modules\Tags' => ROOTPATH . 'modules/Tags',
        'Modules\Shopping' => ROOTPATH . 'modules/Shopping',
        'Modules\Pricing' => ROOTPATH . 'modules/Pricing',
    ];

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     * The class map provides a map of class names and their exact
     * location on the drive. Classes loaded in this manner will have
     * slightly faster performance because they will not have to be
     * searched for within one or more directories as they would if they
     * were being autoloaded through a namespace.
     *
     * Prototype:
     * ```
     *   $classmap = [
     *       'MyClass'   => '/path/to/class/file.php'
     *   ];
     * ```
     *
     * @var array<string, string>
     */
    public $classmap = [];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     * ```
     * 	  $files = [
     * 	 	   '/path/to/my/file.php',
     *    ];
     * ```
     *
     * @var array<int, string>
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public $helpers = [];
}
