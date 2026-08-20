<?php

namespace App\Libraries;

class ClearCache
{
    
    public function __construct() {
        helper('filesystem');
        $date_file = WRITEPATH . '.clear';
        if(file_exists($date_file)) {
            $data = explode(PHP_EOL, file_get_contents($date_file));
        }
        if(empty($data) || empty($data[0]) || (!empty($data[0]) && strtotime($data[0]) < strtotime(date('Y-m-d')))) {
            $this->clearTmpFolder();
            $this->clearFormUploads();
            write_file($date_file, date('Y-m-d') . PHP_EOL . (!empty($data[1]) ? $data[1] : ''));
        }
        if(empty($data) || empty($data[1]) || (!empty($data[1]) && strtotime($data[1]) < strtotime('-30 days'))) {
            $this->clearCache();
            write_file($date_file, (!empty($data[0]) ? $data[0] : date('Y-m-d')) . PHP_EOL . date('Y-m-d'));
        }
    }
    
    private function clearTmpFolder() 
    {
        if(is_dir(WRITEPATH . 'uploads/tmp')) {
            $map = directory_map(WRITEPATH . 'uploads/tmp', 1, true);
            if(!empty($map)) {
                foreach($map as $m) {
                    if(is_dir(WRITEPATH . 'uploads/tmp/' . $m)) {
                        if(intval(trim($m, '/')) < intval(date('Ymd'))) {
                            $r = delete_files(WRITEPATH . 'uploads/tmp/' . $m, true);
                            if($r) {
                                @rmdir(WRITEPATH . 'uploads/tmp/' . $m);
                            }
                        }
                    } else {
                        @unlink(WRITEPATH . 'uploads/tmp/' . $m);
                    }
                }
            }
        }
    }
    
    /**
     * Retencja zdjec przeslanych przez formularze kontaktowe (modul Form).
     * Katalogi maja postac writable/uploads/form/RRRRMM — kasujemy te starsze
     * niz form.uploadsKeepMonths (domyslnie 12 miesiecy).
     */
    private function clearFormUploads()
    {
        $base = WRITEPATH . 'uploads/form';
        if(!is_dir($base)) {
            return;
        }
        $keep_months = (int) (env('form.uploadsKeepMonths') ?: 12);
        $cut = date('Ym', strtotime('-' . $keep_months . ' months'));
        $map = directory_map($base, 1, true);
        if(empty($map)) {
            return;
        }
        foreach($map as $m) {
            $name = trim($m, '/\\');
            if(!preg_match('/^\d{6}$/', $name) || !is_dir($base . '/' . $name)) {
                continue;
            }
            if($name < $cut) {
                if(delete_files($base . '/' . $name, true)) {
                    @rmdir($base . '/' . $name);
                }
            }
        }
    }

    private function clearCache()
    {
        if(is_dir(WRITEPATH . 'cache/uploads')) {
            $map = directory_map(WRITEPATH . 'cache/uploads');
            if(!empty($map)) {
                foreach($map as $k=>$m) {
                    if(is_array($m)) {
                        $this->checkDir(WRITEPATH . 'cache/uploads/' . $k, $m);
                    } else {
                        $info = get_file_info(WRITEPATH . 'cache/uploads/' . $m);
                        if(empty($info['date']) || $info['date'] < strtotime('-10days')) {
                            @unlink(WRITEPATH . 'cache/uploads/' . $m);
                        }
                    }
                }
            }
        }
    }
    
    private function checkDir($dir, $map) {
        if(!empty($map)) {
            foreach($map as $k=>$m) {
                if(is_array($m)) {
                    $this->checkDir($dir . $k, $m);
                } else {
                    $info = get_file_info($dir . $m);
                    if(empty($info['date']) || $info['date'] < strtotime('-30days')) {
                        @unlink($dir . $m);
                    }
                }
            }
        }
        if(empty(directory_map($dir, true))) {
            @rmdir($dir);
        }
    }
}