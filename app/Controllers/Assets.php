<?php

namespace App\Controllers;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class Assets extends BaseController {

    private function getLocalFileContent(string $path): string {
        // Czytamy z dysku zamiast przez HTTP od samego siebie - zapytania
        // HTTP-do-siebie zajmowaly kolejne workery PHP-FPM i pod obciazeniem
        // zakleszczaly cala pule.
        $localPath = FCPATH . ltrim($path, '/');
        if (is_file($localPath)) {
            return file_get_contents($localPath);
        }
        return '';
    }

    private function getRemoteFileContent(string $url): string {
        $ctx = stream_context_create([
            'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
            'http' => ['timeout' => 5],
        ]);
        $content = @file_get_contents($url, false, $ctx);
        if ($content === false) {
            log_message('warning', "Assets: failed to fetch remote file: {$url}");
            return '';
        }
        return $content;
    }

    private function getCacheKey(string $type, array $files): string {
        // Klucz cache z listy plikow + najnowszego czasu modyfikacji.
        $maxMtime = 0;
        foreach ($files as $f) {
            if (strpos($f, 'http') === false) {
                $localPath = FCPATH . ltrim($f, '/');
                if (is_file($localPath)) {
                    $mtime = filemtime($localPath);
                    if ($mtime > $maxMtime) {
                        $maxMtime = $mtime;
                    }
                }
            }
        }
        // Mtime kontrolera, by cache odswiezyl sie po zmianie logiki minifikacji.
        $controllerMtime = filemtime(__FILE__);
        return $type . '_' . md5(implode(',', $files)) . '_' . max($maxMtime, $controllerMtime);
    }

    public function prepareCss() {
        $this->response->setContentType('text/css');
        $this->response->setHeader('Cache-Control', 'public, max-age=31536000, immutable');
        $files = $this->request->getGet('files');
        if (empty($files)) {
            return;
        }

        $fileList = explode(',', $files);
        $cacheKey = $this->getCacheKey('css', $fileList);
        $cache = \App\Libraries\CacheSubdir::get('assets');

        $cached = $cache->get($cacheKey);
        if ($cached !== null) {
            echo $cached;
            return;
        }

        $minified = '';
        $toMinify = '';
        foreach ($fileList as $file) {
            if (strpos($file, 'http') !== false) {
                $content = $this->getRemoteFileContent($file);
            } else {
                $content = $this->getLocalFileContent($file);
            }
            if (str_ends_with($file, '.min.css')) {
                $minified .= $content . "\n";
            } else {
                $toMinify .= $content . "\n";
            }
        }

        if ($toMinify !== '') {
            $minifier = new CSS();
            $minifier->add($toMinify);
            $minified .= $minifier->minify();
        }

        $cache->save($cacheKey, $minified, 31536000); // 1 rok
        echo $minified;
    }

    public function prepareJs() {
        $this->response->setContentType('application/javascript');
        $this->response->setHeader('Cache-Control', 'public, max-age=31536000, immutable');
        $files = $this->request->getGet('files');
        if (empty($files)) {
            return;
        }

        $fileList = explode(',', $files);
        $cacheKey = $this->getCacheKey('js', $fileList);
        $cache = \App\Libraries\CacheSubdir::get('assets');

        $cached = $cache->get($cacheKey);
        if ($cached !== null) {
            echo $cached;
            return;
        }

        $minified = '';
        $toMinify = '';
        foreach ($fileList as $file) {
            if (strpos($file, 'http') !== false) {
                $content = $this->getRemoteFileContent($file);
            } else {
                $content = $this->getLocalFileContent($file);
            }
            if (str_ends_with($file, '.min.js')) {
                $minified .= $content . ";\n";
            } else {
                $toMinify .= $content . ";\n";
            }
        }

        if ($toMinify !== '') {
            $minifier = new JS();
            $minifier->add($toMinify);
            $minified .= $minifier->minify();
        }

        $cache->save($cacheKey, $minified, 31536000); // 1 rok
        echo $minified;
    }
}