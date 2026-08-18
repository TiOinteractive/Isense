<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Files\File;

class RenderImage extends Controller
{
    public function index($module, $dir, $file)
    {
        if(is_dir(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir) && file_exists(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . '.webp')) {
            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . '.webp');
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $file . '.webp');
            $this->response->setStatusCode(200)->setContentType($file->getMimeType())->setBody($image_cont)->send();
        } elseif(is_dir(WRITEPATH . 'uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
            if(!is_dir(WRITEPATH . 'cache/uploads')) {
                mkdir(WRITEPATH . 'cache/uploads');
            }
            if(!is_dir(WRITEPATH . 'cache/uploads/' . $module)) {
                mkdir(WRITEPATH . 'cache/uploads/' . $module);
            }
            if(!is_dir(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir)) {
                mkdir(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir);
            }
            
            if(extension_loaded('imagick')) {
                $this->imagick($module, $dir, $file);
            } else {
                $image = \Config\Services::image();
                
                $info = getimagesize(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                $image->withFile(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                @$image->resize($info[0] - 1, $info[1] - 1, true);
                $image->resize($info[0], $info[1], true);
                $image->convert(IMAGETYPE_WEBP)->save(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $file . '.webp');
            }
            
            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $file . '.webp');
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $file . '.webp');
            $this->response->setStatusCode(200)->setContentType($file->getMimeType())->setBody($image_cont)->send();
        }
    }
    
    public function original($module, $dir, $file)
    {
        $path = WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file;
        if(($image = file_get_contents($path)) === FALSE)
            show_404();

        $file = new File($path);
        $this->response->setStatusCode(200)->setContentType($file->getMimeType())->setBody($image)->send();
    }
    
    public function cache($method, $x, $y, $module, $dir, $file) 
    {
        switch($method) {
            case 'crop':
            case 'c': $method = 'crop';
                break;
            case 'ratio':
            case 'ra': $method = 'ratio';
                break;
            case 'ratio-bottom':
            case 'ra-b': $method = 'ratio_bottom';
                break;
            case 'resize':
            case 'r':
            default: $method = 'resize';
                break;
        }
        
        if(is_dir(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir) && file_exists(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp')) {
            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp');
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp');
            $this->response->setStatusCode(200)->setContentType($file->getMimeType())->setBody($image_cont)->send();
        } elseif(is_dir(WRITEPATH . 'uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
            if(!is_dir(WRITEPATH . 'cache/uploads')) {
                mkdir(WRITEPATH . 'cache/uploads');
            }
            if(!is_dir(WRITEPATH . 'cache/uploads/' . $module)) {
                mkdir(WRITEPATH . 'cache/uploads/' . $module);
            }
            if(!is_dir(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir)) {
                mkdir(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir);
            }
            
            if(extension_loaded('imagick')) {
                $this->imagickCache($method, $x, $y, $module, $dir, $file);
            } else {
                $image = \Config\Services::image();
                $image->withFile(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                switch($method) {
                    case 'ratio_bottom':
                        @$image->fit($x, $y, 'bottom');
                        break;
                    case 'ratio':
                    case 'crop':
                        @$image->fit($x, $y, 'center');
                        break;
                    case 'resize':
                    default:
                        @$image->resize($x, $y, true);
                        break;
                }
                $image->convert(IMAGETYPE_WEBP)->save(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp');
            }
            
            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp');
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp');
            $this->response->setStatusCode(200)->setContentType($file->getMimeType())->setBody($image_cont)->send();
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    private function imagick($module, $dir, $file) {
        $imagick = new \Imagick();
        $imagick->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
        $imagick->setImageFormat("webp");
        $imagick->stripImage();
        $imagick->writeImage(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $file . '.webp');
        $imagick->clear();
    }
    
    private function imagickCache($method, $x, $y, $module, $dir, $file) {
        $imagick = new \Imagick();
        $image_size = getimagesize(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
        switch($method) {
            case 'ratio_bottom':
            case 'ratio':
                if($image_size['mime'] == 'image/jpeg') $imagick->newImage($x, $y, new \ImagickPixel('white'));
                else $imagick->newImage($x, $y, new \ImagickPixel('transparent'));
                $imagick2 = new \Imagick();
                $imagick2->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                $imagick2->scaleImage($x, $y, \Imagick::FILTER_LANCZOS, 1);
                $si = $imagick2->getImageGeometry();
                if($method == 'ratio_bottom') {
                    $imagick->compositeImage($imagick2, \Imagick::COMPOSITE_COPY, floor(($x-$si['width'])/2), floor($y-$si['height']));
                } else {
                    $imagick->compositeImage($imagick2, \Imagick::COMPOSITE_COPY, floor(($x-$si['width'])/2), floor(($y-$si['height'])/2));
                }
                $imagick2->clear();
                break;
            case 'crop':
                $imagick->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                $imagick->cropThumbnailImage($x, $y);
                break;
            case 'resize':
            default:
                $x2 = $x;
                $y2 = $y;
                if($x > $image_size[0]) $x2 = $image_size[0];
                if($y > $image_size[1]) $y2 = $image_size[1];
                $imagick->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                $imagick->scaleImage($x2, $y2, \Imagick::FILTER_LANCZOS, 1);
                $si = $imagick->getImageGeometry();
                break;
        }
        $imagick->setImageFormat("webp");
        $imagick->stripImage();
        $imagick->writeImage(WRITEPATH . 'cache/uploads/' . $module . '/'  . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp');
        $imagick->clear();
    }

}