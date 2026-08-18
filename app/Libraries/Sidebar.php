<?php

namespace App\Libraries;

use App\Models\SidebarModel;
use App\Models\ModuleModel;
use App\Libraries\Assets;

class Sidebar
{
    public function __construct() 
    {
        $this->sidebarModel = new SidebarModel();
    }
    
    public function showSidebar($id, $id_lang, $locale, $sidebar_template='default.php') {
        $moduleModel = new ModuleModel();
        $sidebar_content = $this->sidebarModel->getSidebarContentForHomeById($id, $id_lang);
        $is_mobile = false;
        if(!empty($sidebar_content)) {
            foreach($sidebar_content as $k=>$content) {
                $module_data = $moduleModel
                        ->join('module_element me', 'module.id=me.id_module')
                        ->select('module.slug,module.separate,me.slug as element_slug')
                        ->where('me.id', $content['id_module_element'])
                        ->where('module.publish', 1)
                        ->first();
                $class = '\App\Libraries\\' . ucfirst($module_data['slug']);
                $template = '\App\Views/user/' . (!empty($module_data['element_slug']) ? $module_data['element_slug'] . '/' : '');
                if(!class_exists($class)) {
                    $class = '\Modules\\' . ucfirst($module_data['slug']) . '\Libraries\\' . ucfirst($module_data['slug']);
                    $template = '\Modules\\' . ucfirst($module_data['slug']) . '\Views/user/' . (!empty($module_data['element_slug']) ? $module_data['element_slug'] . '/' : '');
                    if(!is_dir(ROOTPATH . 'modules/' . ucfirst($module_data['slug']))) {
                        $class = null;
                    }
                }
                if(!empty($class) && class_exists($class)) {
                    $module = new $class();
                    $module->locale = $locale;
                    if(method_exists($module, 'index')) {
                        $sidebar_content[$k]['data'] = $module->index($content, $id_lang, $module_data['element_slug'], null);
                        if(empty($sidebar_content[$k]['data'])) {
                            unset($sidebar_content[$k]);
                            continue;
                        } elseif(!empty($sidebar_content[$k]['data']['views_dir'])) {
                            $template = '\Modules\\' . ucfirst($module_data['slug']) . '\Views/user/' . $sidebar_content[$k]['data']['views_dir'] . '/';
                        }
                    }
                } else {
                    unset($sidebar_content[$k]);
                    continue;
                }
                $mobile_template = $template;
                if(!empty($sidebar_content[$k]['data']['template'])) {
                    $template .= $sidebar_content[$k]['data']['template'];
                    $mobile_template .= 'm_' . $sidebar_content[$k]['data']['template'];
                } elseif(!empty($content['template'])) {
                    $template .= $content['template'];
                    $mobile_template .= 'm_' . $content['template'];
                } else {
                    unset($sidebar_content[$k]);
                    continue;
                }
                /*
                if(method_exists($module, 'assets')) {
                    $assets = $module->assets($module_data['element_slug'], !empty($template) ? substr($template, strrpos($template, '/') + 1, -4) : '', !empty($sidebar_content[$k]['data']) && !empty($sidebar_content[$k]['data']['id']) ? $sidebar_content[$k]['data']['id'] : 0, !empty($sidebar_content[$k]['data']) ? $sidebar_content[$k]['data'] : array());
                    $this->assetsClass->addAssets($assets);
                }*/
                if($is_mobile && file_exists(ROOTPATH . lcfirst(str_replace('\\', '/', substr($mobile_template, 1))))) {
                    $sidebar_content[$k]['template'] = $mobile_template;
                } elseif(file_exists(ROOTPATH . lcfirst(str_replace('\\', '/', substr($template, 1))))) {
                    $sidebar_content[$k]['template'] = $template;
                } else {
                    $sidebar_content[$k]['template'] = '';
                }
            }
        }
        return view($is_mobile && file_exists(APPPATH . 'Views/user/sidebar/m_' . $sidebar_template) ? 'user/sidebar/m_' . $sidebar_template : 'user/sidebar/' . $sidebar_template, array('content' => $sidebar_content, 'id_sidebar' => $id));
    }
}