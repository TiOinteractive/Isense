<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Templates
     * --------------------------------------------------------------------------
     *
     * Pagination links are rendered out using views to configure their
     * appearance. This array contains aliases and the view names to
     * use when rendering the links.
     *
     * Within each view, the Pager object will be available as $pager,
     * and the desired group as $pagerGroup;
     *
     * @var array<string, string>
     */
    public $templates = [
        //'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_full'   => 'App\Views\admin\pager\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',
        'front_full'     => 'App\Views\user\pager\default_full',
        'front_entertainment'     => 'App\Views\user\pager\entertainment_full',
		'usergallery_full'     => 'App\Views\user\pager\usergallery_full',
		'userphoto_full'     => 'App\Views\user\pager\userphoto_full',
		'flavors_full'     => 'App\Views\user\pager\flavors_full',
    ];

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     *
     * @var int
     */
    public $perPage = 20;
}
