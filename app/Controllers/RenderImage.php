<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Files\File;

class RenderImage extends Controller {

    private bool $proxyEnabled = false;
    private string $fallbackBaseUrl = 'https://www.isense.pl/';

    public function __construct()
    {
        // Na produkcji (gdy biezacy host = serwer docelowy) proxy jest
        // wylaczone, zeby serwer nie pobieral grafik sam od siebie.
        $fallbackHost = parse_url($this->fallbackBaseUrl, PHP_URL_HOST);
        $currentHost  = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
        if ($fallbackHost && $currentHost && strcasecmp($fallbackHost, $currentHost) === 0) {
            $this->proxyEnabled = false;
        }
    }

    public function index($module, $dir, $file) {
        if (!empty($this->request->getGet()['crop'])) {
            if (!is_file(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
                $this->notFound();
            }
            if (!is_dir(WRITEPATH . 'cache/crop/' . $module)) {
                mkdir(WRITEPATH . 'cache/crop/' . $module);
            }
            if (!is_dir(WRITEPATH . 'cache/crop/' . $module . '/' . $dir)) {
                mkdir(WRITEPATH . 'cache/crop/' . $module . '/' . $dir);
            }
            $imagick = new \Imagick();
            $imagick->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
            $imagick->cropImage($this->request->getGet()['w'], $this->request->getGet()['h'], $this->request->getGet()['x'], $this->request->getGet()['y']);
            $imagick->setImageFormat("webp");
            $imagick->stripImage();
            $imagick->writeImage(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $this->request->getGet()['x'] . $this->request->getGet()['y'] . $this->request->getGet()['w'] . $this->request->getGet()['h'] . '_' . $file . '.webp');
            $imagick->clear();
            $image_cont = file_get_contents(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $this->request->getGet()['x'] . $this->request->getGet()['y'] . $this->request->getGet()['w'] . $this->request->getGet()['h'] . '_' . $file . '.webp');
            $file = new File(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $this->request->getGet()['x'] . $this->request->getGet()['y'] . $this->request->getGet()['w'] . $this->request->getGet()['h'] . '_' . $file . '.webp');
            $this->sendImage($file->getMimeType(), $image_cont);
        } else {
            if (is_dir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . '.webp')) {
                $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . '.webp');
                $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . '.webp');
                $this->sendImage($file->getMimeType(), $image_cont);
            } elseif (is_dir(WRITEPATH . 'uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
                if (!is_dir(WRITEPATH . 'cache/uploads')) {
                    mkdir(WRITEPATH . 'cache/uploads');
                }
                if (!is_dir(WRITEPATH . 'cache/uploads/' . $module)) {
                    mkdir(WRITEPATH . 'cache/uploads/' . $module);
                }
                if (!is_dir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir)) {
                    mkdir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir);
                }

                if (extension_loaded('imagick')) {
                    $this->imagick($module, $dir, $file);
                } else {
                    $image = \Config\Services::image();

                    $info = getimagesize(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                    $image->withFile(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                    @$image->resize($info[0] - 1, $info[1] - 1, true);
                    $image->resize($info[0], $info[1], true);
                    $image->convert(IMAGETYPE_WEBP)->save(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . '.webp');
                }

                $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . '.webp');
                $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . '.webp');
                $this->sendImage($file->getMimeType(), $image_cont);
            } else {
                $this->notFound();
            }
        }
    }

    public function indexSaveExt($module, $dir, $file) {
        $mime = mime_content_type(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
        if (is_dir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . ($mime == 'image/webp' ? '.png' : ''))) {
            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . ($mime == 'image/webp' ? '.png' : ''));
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . ($mime == 'image/webp' ? '.png' : ''));
            $this->sendImage($file->getMimeType(), $image_cont);
        } elseif (is_dir(WRITEPATH . 'uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
            if (!is_dir(WRITEPATH . 'cache/uploads')) {
                    mkdir(WRITEPATH . 'cache/uploads');
                }
                if (!is_dir(WRITEPATH . 'cache/uploads/' . $module)) {
                    mkdir(WRITEPATH . 'cache/uploads/' . $module);
                }
                if (!is_dir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir)) {
                    mkdir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir);
                }
                
            if (extension_loaded('imagick')) {
                $this->imagick($module, $dir, $file, true);
            } else {
                $image = \Config\Services::image();
                $info = getimagesize(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                $image->withFile(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                @$image->resize($info[0] - 1, $info[1] - 1, true);
                $image->resize($info[0], $info[1], true);
                $image->save(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file);
            }
            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . ($mime == 'image/webp' ? '.png' : ''));
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file);
            $this->sendImage($file->getMimeType(), $image_cont);
        } else {
            $this->notFound();
        }
    }

    public function original($module, $dir, $file) {
        $path = WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file;
        if (!is_file($path) || ($image = file_get_contents($path)) === FALSE) {
            $this->notFound();
        }

        $file = new File($path);
        $this->sendImage($file->getMimeType(), $image);
    }

    public function cache($method, $x, $y, $module, $dir, $file) {
        switch ($method) {
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

        if (is_dir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp')) {
            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp');
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . '.webp');
            $this->sendImage($file->getMimeType(), $image_cont);
        } elseif (is_dir(WRITEPATH . 'uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
            if (!is_dir(WRITEPATH . 'cache/uploads')) {
                mkdir(WRITEPATH . 'cache/uploads');
            }
            if (!is_dir(WRITEPATH . 'cache/uploads/' . $module)) {
                mkdir(WRITEPATH . 'cache/uploads/' . $module);
            }
            if (!is_dir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir)) {
                mkdir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir);
            }
            $mime = mime_content_type(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
            if (extension_loaded('imagick')) {
                $this->imagickCache($method, $x, $y, $module, $dir, $file);
            } else {
                $image = \Config\Services::image();
                $image->withFile(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                switch ($method) {
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
                $image->convert(IMAGETYPE_WEBP)->save(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . ($mime != 'image/webp' ? '.webp' : ''));
            }

            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . ($mime != 'image/webp' ? '.webp' : ''));
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . ($mime != 'image/webp' ? '.webp' : ''));
            $this->sendImage($file->getMimeType(), $image_cont);
        } else {
            $this->notFound();
        }
    }
    
    public function saveExt($method, $x, $y, $module, $dir, $file) {
        switch ($method) {
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
        $mime = mime_content_type(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
        if (is_dir(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . ($mime == 'image/webp' ? '.png' : ''))) {
            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . ($mime == 'image/webp' ? '.png' : ''));
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . ($mime == 'image/webp' ? '.png' : ''));
            $this->sendImage($file->getMimeType(), $image_cont);
        } elseif (is_dir(WRITEPATH . 'uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
            if (extension_loaded('imagick')) {
                $image_cont = $this->imagickCache($method, $x, $y, $module, $dir, $file, true);
            } else {
                $image = \Config\Services::image();
                $image->withFile(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                switch ($method) {
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
                $image->save(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file);
                $image->clear();
            }
            $image_cont = file_get_contents(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file);
            $file = new File(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file);
            $this->sendImage($file->getMimeType(), $image_cont);
        } else {
            $this->notFound();
        }
    }

    private function sendImage(string $contentType, string $body): void
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        http_response_code(200);
        header('Content-Type: ' . $contentType, true);
        header('Cache-Control: public, max-age=86400', true);
        header_remove('Pragma');
        echo $body;
        exit;
    }

    /**
     * Grafika nie istnieje lokalnie: jesli proxy jest aktywne, pobierz ja
     * z serwera zewnetrznego (produkcji); w przeciwnym razie zwroc 404.
     */
    private function notFound(): void
    {
        if ($this->proxyEnabled) {
            $this->proxyFromProduction();
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    /**
     * Pobiera grafike z serwera zewnetrznego pod tym samym URI co biezace
     * zadanie i serwuje ja klientowi. Przy bledzie/braku grafiki rzuca 404.
     */
    private function proxyFromProduction(): void
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $url = rtrim($this->fallbackBaseUrl, '/') . '/' . ltrim($uri, '/');

        $context = stream_context_create([
            'http' => ['timeout' => 5, 'ignore_errors' => true],
            'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
        ]);
        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $statusOk    = false;
        $contentType = '';
        foreach ($http_response_header ?? [] as $header) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $header, $m)) {
                $statusOk = ((int) $m[1] >= 200 && (int) $m[1] < 300);
            } elseif (stripos($header, 'Content-Type:') === 0) {
                $contentType = trim(substr($header, strlen('Content-Type:')));
            }
        }

        if (!$statusOk || stripos($contentType, 'image/') !== 0) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->sendImage($contentType, $body);
    }

    private function imagick($module, $dir, $file, $save_ext=false) {
        $imagick = new \Imagick();
        $imagick->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
        $imagick->stripImage();
        $mime = mime_content_type(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
        if(!$save_ext) {
            $imagick->setImageFormat("webp");
            // index() zawsze czyta/sprawdza cache pod nazwa cache_<file>.webp
            // (linie 46/47/73), wiec zawsze zapisujemy z koncowka .webp - takze
            // gdy zrodlo jest juz w formacie webp. Inaczej cache nigdy nie trafia
            // i kazde zadanie obrazka webp przelicza go od nowa + rzuca 500.
            $imagick->writeImage(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . '.webp');
        } else {
            if($mime == 'image/webp') {
                $imagick->setImageFormat("png");
            }
            $imagick->writeImage(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $file . ($mime == 'image/webp' ? '.png' : ''));
        }
        $imagick->clear();
    }

    private function imagickCache($method, $x, $y, $module, $dir, $file, $save_ext=false) {
        $imagick = new \Imagick();
        $image_size = getimagesize(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
        switch ($method) {
            case 'ratio_bottom':
            case 'ratio':
                if ($image_size['mime'] == 'image/jpeg')
                    $imagick->newImage($x, $y, new \ImagickPixel('white'));
                else
                    $imagick->newImage($x, $y, new \ImagickPixel('transparent'));
				$this->checkImagickImageOrientation($imagick);
                $imagick2 = new \Imagick();
                $imagick2->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                $imagick2->scaleImage($x, $y, \Imagick::FILTER_LANCZOS, 1);
                $si = $imagick2->getImageGeometry();
                if ($method == 'ratio_bottom') {
                    $imagick->compositeImage($imagick2, \Imagick::COMPOSITE_COPY, floor(($x - $si['width']) / 2), floor($y - $si['height']));
                } else {
                    $imagick->compositeImage($imagick2, \Imagick::COMPOSITE_COPY, floor(($x - $si['width']) / 2), floor(($y - $si['height']) / 2));
                }
                $imagick2->clear();
                break;
            case 'crop':
                $imagick->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                $this->checkImagickImageOrientation($imagick);
                $imagick->cropThumbnailImage($x, $y);
                break;
            case 'resize':
            default:
                $x2 = $x;
                $y2 = $y;
                if ($x > $image_size[0])
                    $x2 = $image_size[0];
                if ($y > $image_size[1])
                    $y2 = $image_size[1];
                $imagick->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
				$this->checkImagickImageOrientation($imagick);
                $imagick->scaleImage($x2, $y2, \Imagick::FILTER_LANCZOS, 1);
                $si = $imagick->getImageGeometry();
                break;
        }
        $imagick->stripImage();
        $mime = mime_content_type(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
        if(!$save_ext) {
            $imagick->setImageFormat("webp");
            $imagick->writeImage(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . ($mime != 'image/webp' ? '.webp' : ''));
        } else {
            if($mime == 'image/webp') {
                $imagick->setImageFormat("png");
            }
            $imagick->writeImage(WRITEPATH . 'cache/uploads/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $file . ($mime == 'image/webp' ? '.png' : ''));
        }
        $imagick->clear();
    }
	
	public function checkImagickImageOrientation($imagick) {
		$orientation = $imagick->getImageOrientation();
		switch ($orientation) {
			case \Imagick::ORIENTATION_BOTTOMRIGHT:
				$imagick->rotateimage("#000", 180);
				break;
			case \Imagick::ORIENTATION_RIGHTTOP:
				$imagick->rotateimage("#000", 90);
				break;
			case \Imagick::ORIENTATION_LEFTBOTTOM:
				$imagick->rotateimage("#000", -90);
				break;
		}
		return $imagick;
	}

    public function crop($method, $x, $y, $crop_width, $crop_height, $crop_x, $crop_y, $module, $dir, $file) {
        switch ($method) {
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

        if (is_dir(WRITEPATH . 'cache/crop/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp')) {
            $image_cont = file_get_contents(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
            $file = new File(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
            $this->sendImage($file->getMimeType(), $image_cont);
        } elseif (is_dir(WRITEPATH . 'uploads/' . $module . '/' . $dir) && file_exists(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file)) {
            if (!is_dir(WRITEPATH . 'cache/crop')) {
                mkdir(WRITEPATH . 'cache/crop');
            }
            if (!is_dir(WRITEPATH . 'cache/crop/' . $module)) {
                mkdir(WRITEPATH . 'cache/crop/' . $module);
            }
            if (!is_dir(WRITEPATH . 'cache/crop/' . $module . '/' . $dir)) {
                mkdir(WRITEPATH . 'cache/crop/' . $module . '/' . $dir);
            }
            if (extension_loaded('imagick')) {
                $imagick = new \Imagick();
                if (!file_exists(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp')) {
                    $imagick->readImage(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                    $imagick->cropImage($crop_width, $crop_height, $crop_x, $crop_y);
                    $imagick->setImageFormat("webp");
                    $imagick->stripImage();
                    $imagick->writeImage(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
                }
                $image_size = getimagesize(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
                switch ($method) {
                    case 'ratio_bottom':
                    case 'ratio':
                        if ($image_size['mime'] == 'image/jpeg')
                            $imagick->newImage($x, $y, new \ImagickPixel('white'));
                        else
                            $imagick->newImage($x, $y, new \ImagickPixel('transparent'));
                        $imagick2 = new \Imagick();
                        $imagick2->readImage(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
                        $imagick2->scaleImage($x, $y, \Imagick::FILTER_LANCZOS, 1);
                        $si = $imagick2->getImageGeometry();
                        if ($method == 'ratio_bottom') {
                            $imagick->compositeImage($imagick2, \Imagick::COMPOSITE_COPY, floor(($x - $si['width']) / 2), floor($y - $si['height']));
                        } else {
                            $imagick->compositeImage($imagick2, \Imagick::COMPOSITE_COPY, floor(($x - $si['width']) / 2), floor(($y - $si['height']) / 2));
                        }
                        $imagick2->clear();
                        break;
                    case 'crop':
                        $imagick->readImage(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
                        $imagick->cropThumbnailImage($x, $y);
                        break;
                    case 'resize':
                    default:
                        $x2 = $x;
                        $y2 = $y;
                        if ($x > $image_size[0])
                            $x2 = $image_size[0];
                        if ($y > $image_size[1])
                            $y2 = $image_size[1];
                        $imagick->readImage(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
                        $imagick->scaleImage($x2, $y2, \Imagick::FILTER_LANCZOS, 1);
                        $si = $imagick->getImageGeometry();
                        break;
                }
                $imagick->setImageFormat("webp");
                $imagick->stripImage();
                $imagick->writeImage(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
                $imagick->clear();
            } else {
                // Fallback GD (np. lokalne dev bez rozszerzenia imagick):
                // wytnij prostokat ze zrodla, dopasuj do docelowych x/y i zapisz webp.
                $image = \Config\Services::image();
                $image->withFile(WRITEPATH . 'uploads/' . $module . '/' . $dir . '/' . $file);
                $image->crop($crop_width, $crop_height, $crop_x, $crop_y);
                switch ($method) {
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
                $image->convert(IMAGETYPE_WEBP)->save(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
            }
            $file = new File(WRITEPATH . 'cache/crop/' . $module . '/' . $dir . '/cache_' . $method . '_' . $x . '_' . $y . '_' . $crop_x . $crop_y . $crop_width . $crop_height . '_' . $file . '.webp');
            $image_cont = file_get_contents($file);
            $this->sendImage($file->getMimeType(), $image_cont);
        } else {
            $this->notFound();
        }
    }

}
