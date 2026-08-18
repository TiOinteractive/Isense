<?php

namespace App\Libraries;


class Assets
{
    private $css_files = array();
    private $css_footer = array();
    private $css_code = '';
    private $js_files = array();
    private $js_ready = array();
    private $js_load = array();
    private $js_code = '';

    public function __construct($assets=array()) {
        if(!empty($assets)) {
            $this->addAssets($assets);
        }
    }
    
    public function addCss($path) 
    {
        if(!empty($path) && !in_array($path, $this->css_files)) {
            $this->css_files[] = $path;
        }
    }
    
    public function addCssFooter($path) 
    {
        if(!empty($path) && !in_array($path, $this->css_footer)) {
            $this->css_footer[] = $path;
        }
    }
    
    public function addCssCode($css) 
    {
        if(!empty($css)) {
            $this->css_code .= $css;
        }
    }
    
    public function addJs($path) 
    {
        if(!empty($path) && !in_array($path, $this->js_files)) {
            $this->js_files[] = $path;
        }
    }
    
    public function addJsReady($js) 
    {
        if(!empty($js) && !in_array($js, $this->js_ready)) {
            $this->js_ready[] = $js;
        }
    }
    
    public function addJsLoad($js) 
    {
        if(!empty($js) && !in_array($js, $this->js_load)) {
            $this->js_load[] = $js;
        }
    }
    
    public function addJsCode($js) 
    {
        if(!empty($js)) {
            $this->js_code .= $js;
        }
    }
    
    public function addAssets($assets) 
    {
        if(!empty($assets['css'])) {
            foreach($assets['css'] as $css) {
                $this->addCss($css);
            }
        }
        if(!empty($assets['css_footer'])) {
            foreach($assets['css_footer'] as $css) {
                $this->addCssFooter($css);
            }
        }
        if(!empty($assets['css_code'])) {
            $this->addCssCode($assets['css_code']);
        }
        if(!empty($assets['js'])) {
            foreach($assets['js'] as $js) {
                $this->addJs($js);
            }
        }
        if(!empty($assets['js_ready'])) {
            foreach($assets['js_ready'] as $js) {
                $this->addJsReady($js);
            }
        }
        if(!empty($assets['js_load'])) {
            foreach($assets['js_load'] as $js) {
                $this->addJsLoad($js);
            }
        }
        if(!empty($assets['js_code'])) {
            $this->addJsCode($assets['js_code']);
        }
    }
    
    public function getCss() 
    {
        return $this->css_files;
    }
    
    public function getCssFooter() 
    {
        return $this->css_footer;
    }
    
    public function getCssCode() 
    {
        return $this->css_code;
    }
    
    public function getAllCss() 
    {
        return array(
            'css' => $this->css_files,
            'css_footer' => $this->css_footer,
            'css_code' => $this->css_code,
        );
    }
    
    public function getJs() 
    {
        return $this->js_files;
    }
    
    public function getJsReady() 
    {
        return $this->js_ready;
    }
    
    public function getJsLoad() 
    {
        return $this->js_load;
    }
    
    public function getJscode() 
    {
        return $this->js_code;
    }
    
    public function getAllJs() 
    {
        return array(
            'js' => $this->js_files,
            'js_ready' => $this->js_ready,
            'js_load' => $this->js_load,
            'js_code' => $this->js_code,
        );
    }
    
    public function getAssets() 
    {
        return array(
            'css' => $this->css_files,
            'css_footer' => $this->css_footer,
            'css_code' => $this->css_code,
            'js' => $this->js_files,
            'js_ready' => $this->js_ready,
            'js_load' => $this->js_load,
            'js_code' => $this->js_code,
        );
    }
}