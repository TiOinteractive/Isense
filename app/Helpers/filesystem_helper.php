<?php

if (! function_exists('website_menu')) {
    
    function get_templates_by_dir($source_dir, $directory_depth=1, $hidden=false) {
        $templates = array();
        if (is_dir(ROOTPATH . $source_dir)) {
            $map = directory_map(ROOTPATH . $source_dir, $directory_depth, $hidden);
            if (!empty($map)) {
                asort($map);
                foreach ($map as $k=>$tpl) {
                    if(!is_dir(ROOTPATH . $source_dir . '/' . $tpl)) {
                        $lines = file(ROOTPATH . $source_dir . '/' . $tpl);
                        if (substr($tpl, 0, 1) != '_' && substr($tpl, 0, 2) != 'm_') {
                            $templates[] = array(
                                'file' => $tpl,
                                'name' => $lines[2]
                            );
                        };
                    }
                }
            }
        }
        return $templates;
    }
}