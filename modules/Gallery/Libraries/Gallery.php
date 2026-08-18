<?php

namespace Modules\Gallery\Libraries;

use Modules\Gallery\Models\GalleryModel;

class Gallery {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->galleryModel = new GalleryModel();
    }

    public function index($content, $id_lang, $slug = '') {
        switch ($slug) {
            case 'photos':
                return $this->photos($content, $id_lang);
                break;
            case 'single':
                return $this->single($content, $id_lang);
                break;
            case 'selected':
                return $this->selected($content, $id_lang);
                break;
            case 'list':
            default:
                return $this->list($content, $id_lang);
                break;
        }
    }

    public function assets($slug = '', $template = '', $id_gallery = 0, $data = array()) {
        $assets = array(
            'js' => array(),
            'css' => array()
        );
        switch ($slug) {
            case 'single_element':
                $assets['js'][] = '/assets/js/jquery.touchSwipe.min.js';
                $assets['js'][] = '/assets/js/tiolightbox.js';
                $assets['js'][] = '/assets/js/page.js';
                $assets['css'][] = '/assets/css/tiolightbox.css';
                $assets['js_ready'][] = '$(".photos a").TiO_lightbox({});';
                break;
            case 'single':
                break;
            case 'selected':
                $assets['css'][] = '/assets/css/slick.css';
                $assets['js'][] = '/assets/js/slick.min.js';
                $assets['js'][] = '/assets/js/page.js';
                break;
            case 'list':
                $assets['js'][] = '/assets/js/page.js';
            default :
                break;
        }
        return $assets;
    }

    private function single($content, $id_lang) {
        $gallery = $this->galleryModel
                ->join('gallery_lang gl', 'gallery.id=gl.id_gallery')
                ->select('gallery.id,gallery.template,gl.name,gl.introduction,gl.content')
                ->where('gallery.publish', 1)
                ->where('gl.id_lang', $id_lang)
                ->where('gallery.id_page_cont', $content['id'])
                ->first();
        if (!empty($gallery)) {
            $gallery['photo'] = $this->galleryModel->db
                    ->table('gallery_files gf')
                    ->join('gallery_files_lang gfl', 'gf.id=gfl.id_file', 'left')
                    ->select('gf.path,gf.mime,gfl.caption,gfl.author')
                    ->where('gf.publish', 1)
                    ->where('gfl.id_lang', $id_lang)
                    ->where('gf.id_gallery', $gallery['id'])
                    ->where('gf.field', 'photo')
                    ->get()
                    ->getRowArray();
            $gallery['photos'] = $this->galleryModel->db
                    ->table('gallery_files gf')
                    ->join('gallery_files_lang gfl', 'gf.id=gfl.id_file', 'left')
                    ->select('gf.path,gf.mime,gfl.caption,gfl.author')
                    ->where('gf.publish', 1)
                    ->where('gfl.id_lang', $id_lang)
                    ->where('gf.id_gallery', $gallery['id'])
                    ->where('gf.field', 'photos')
                    ->orderBy('gf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $gallery['audio'] = $this->galleryModel->db
                    ->table('gallery_files gf')
                    ->join('gallery_files_lang gfl', 'gf.id=gfl.id_file', 'left')
                    ->select('gf.path,gf.mime,gfl.caption,gfl.author')
                    ->where('gf.publish', 1)
                    ->where('gfl.id_lang', $id_lang)
                    ->where('gf.id_gallery', $gallery['id'])
                    ->where('gf.field', 'audio')
                    ->orderBy('gf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $gallery['video'] = $this->galleryModel->db
                    ->table('gallery_files gf')
                    ->join('gallery_files_lang gfl', 'gf.id=gfl.id_file', 'left')
                    ->select('gf.path,gf.mime,gfl.caption,gfl.author')
                    ->where('gf.publish', 1)
                    ->where('gfl.id_lang', $id_lang)
                    ->where('gf.id_gallery', $gallery['id'])
                    ->where('gf.field', 'video')
                    ->orderBy('gf.order', 'ASC')
                    ->get()
                    ->getResultArray();
        }
        return $gallery;
    }

    private function list($content, $id_lang) {
        $galleries = $this->galleryModel
                ->join('gallery_lang gl', 'gallery.id=gl.id_gallery')
                ->join('links l', 'l.id=gl.id_link')
                ->join('gallery_files gf', 'gf.id_gallery=gallery.id', 'left')
                ->join('gallery_files_lang gfl', 'gfl.id_file=gf.id', 'left')
                ->select('gallery.id,gallery.template,gl.name,gl.introduction,l.link,gf.path as photo,gfl.caption as photo_caption,gfl.author as photo_author')
                ->where('gallery.publish', 1)
                ->where('gl.id_lang', $id_lang)
                ->where('l.id_lang', $id_lang)
                ->where('gf.publish', 1)
                ->groupStart()
                ->where('gfl.id_lang', $id_lang)
                ->orWhere('gfl.id_lang', null)
                ->groupEnd()
                ->where('gallery.id_page_cont', $content['id'])
                ->groupStart()
                ->where('gf.field', 'photo')
                ->orWhere('gf.field', null)
                ->groupEnd()
                ->orderBy('gallery.order', 'ASC')
                ->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'gallery-' . $content['id']);
        if (!empty($galleries) && !empty($this->locale)) {
            foreach ($galleries as $k => $g) {
                $galleries[$k]['link'] = ($this->locale ? $this->locale . '/' : '') . $g['link'];
            }
        }
        return array(
            'list' => $galleries,
            'pager' => $this->galleryModel->pager,
        );
    }

    private function photos($content, $id_lang) {

        $config = new \Config\Pager;
        $query = $this->galleryModel
                ->join('gallery_files gf', 'gallery.id=gf.id_gallery')
                ->join('gallery_files_lang gfl', 'gf.id=gfl.id_file')
                ->join('gallery_lang gl', 'gallery.id=gl.id_gallery')
                ->join('page_content pc', 'pc.id=gallery.id_page_cont')
                ->join('page p', 'p.id=pc.id_page')
                ->join('page_lang pl', 'p.id=pl.id_page')
                ->join('links l', 'gl.id_link=l.id', 'left')
                ->join('links l2', 'pl.id_link=l2.id', 'left')
                ->select('gf.path,gf.mime,gfl.caption,gfl.author,l.link,gl.name as gallery_name,l2.link as page_link')
                ->where('gallery.publish', 1)
                ->where('p.publish', 1)
                ->where('pc.publish', 1)
                ->groupStart()
                    ->where('l.id_lang', $id_lang)
                    ->orWhere('l.id_lang', NULL)
                ->groupEnd()
                ->where('pl.id_lang', $id_lang)
                ->where('gfl.id_lang', $id_lang);

        if (!empty($content['config']['lists']) && !in_array(0, $content['config']['lists'])) {
            $query->whereIn('gallery.id', $content['config']['lists']);
        }
        if (empty($content['config']['order'])) {
            $content['config']['order'] = '';
        }
        switch ($content['config']['order']) {
            case 'random':
                $query->orderBy('gf.order', 'RANDOM');
                break;
            case 'name':
                $query->orderBy('gf.name', 'ASC');
                break;
            case 'latest':
            default: $query->orderBy('gf.created_at', 'DESC');
                break;
        }
        $photos = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'gallery-photos-' . $content['id']);
        if (!empty($photos)) {
            foreach ($photos as $k => $p) {
                if (empty($p['link'])) {
                    $p['link'] = $p['page_link'];
                }
                unset($photos[$k]['page_link']);
                $photos[$k]['link'] = (!empty($this->locale) && $this->locale ? $this->locale . '/' : '') . $p['link'];
            }
        }
        return array(
            'photos' => $photos,
            'pager' => $this->galleryModel->pager,
        );
    }

    private function selected($content, $id_lang) {

        $config = new \Config\Pager;
        $query = $this->galleryModel
                ->join('gallery_lang gl', 'gallery.id=gl.id_gallery')
                ->join('links l', 'l.id=gl.id_link')
                ->join('gallery_files gf', 'gf.id_gallery=gallery.id', 'left')
                ->join('gallery_files_lang gfl', 'gfl.id_file=gf.id', 'left')
                ->select('gallery.id,gl.name,gl.introduction,l.link,gf.path as photo,gfl.caption as photo_caption,gfl.author as photo_author')
                ->where('gallery.publish', 1)
                ->where('gl.id_lang', $id_lang)
                ->where('l.id_lang', $id_lang)
                ->where('gf.publish', 1)
                ->groupStart()
                ->where('gfl.id_lang', $id_lang)
                ->orWhere('gfl.id_lang', null)
                ->groupEnd()
                ->groupStart()
                ->where('gf.field', 'photo')
                ->orWhere('gf.field', null)
                ->groupEnd();

        if (empty($content['config']['option'])) {
            $content['config']['order'] = '';
        }
        switch ($content['config']['option']) {
            case 'home': $query->where('gallery.home', 1);
                break;
        }
        if (!empty($content['config']['lists']) && !in_array(0, $content['config']['lists'])) {
            $query->whereIn('gallery.id_page_cont', $content['config']['lists']);
        }
        if (empty($content['config']['order'])) {
            $content['config']['order'] = '';
        }
        switch ($content['config']['order']) {
            case 'random':
                $query->orderBy('gallery.order', 'RANDOM');
                break;
            case 'name':
                $query->orderBy('gl.name', 'ASC');
                break;
            case 'latest':
            default: $query->orderBy('gallery.order', 'ASC');
                break;
        }
        $galleries = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'gallery-' . $content['id']);
        if (!empty($galleries) && !empty($this->locale)) {
            foreach ($galleries as $k => $g) {
                $galleries[$k]['link'] = ($this->locale ? $this->locale . '/' : '') . $g['link'];
            }
        }
        return array(
            'list' => $galleries,
            'pager' => $this->galleryModel->pager,
            'views_dir' => 'list'
        );
    }

    public function getSingleByLinkId($id_link, $id_lang, $action) {
        $gallery = $this->galleryModel
                ->join('gallery_lang gl', 'gallery.id=gl.id_gallery')
                ->join('page_content pc', 'gallery.id_page_cont=pc.id')
                ->join('links l', 'l.id=gl.id_link')
                ->select('pc.id_page,gallery.id,gallery.id_page_cont,gallery.template,gallery.order,gl.name,gl.introduction,gl.content,l.link')
                ->where('gallery.publish', 1)
                ->where('gl.id_lang', $id_lang)
                ->where('gl.id_link', $id_link)
                ->first();
        if (!empty($gallery)) {
            $gallery['photo'] = $this->galleryModel->db
                    ->table('gallery_files gf')
                    ->join('gallery_files_lang gfl', 'gf.id=gfl.id_file')
                    ->select('gf.path,gf.mime,gfl.caption,gfl.author')
                    ->where('gf.id_gallery', $gallery['id'])
                    ->where('gf.field', 'photo')
                    ->where('gfl.id_lang', $id_lang)
                    ->where('gf.publish', 1)
                    ->get()
                    ->getRowArray();
            $gallery['photos'] = $this->galleryModel->db
                    ->table('gallery_files gf')
                    ->join('gallery_files_lang gfl', 'gf.id=gfl.id_file')
                    ->select('gf.path,gf.mime,gfl.caption,gfl.author')
                    ->where('gf.id_gallery', $gallery['id'])
                    ->where('gf.field', 'photos')
                    ->where('gfl.id_lang', $id_lang)
                    ->where('gf.publish', 1)
                    ->orderBy('gf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $gallery['audio'] = $this->galleryModel->db
                    ->table('gallery_files gf')
                    ->join('gallery_files_lang gfl', 'gf.id=gfl.id_file')
                    ->select('gf.path,gf.mime,gfl.caption,gfl.author')
                    ->where('gf.id_gallery', $gallery['id'])
                    ->where('gf.field', 'audio')
                    ->where('gfl.id_lang', $id_lang)
                    ->where('gf.publish', 1)
                    ->orderBy('gf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $gallery['video'] = $this->galleryModel->db
                    ->table('gallery_files gf')
                    ->join('gallery_files_lang gfl', 'gf.id=gfl.id_file')
                    ->select('gf.path,gf.mime,gfl.caption,gfl.author')
                    ->where('gf.id_gallery', $gallery['id'])
                    ->where('gf.field', 'video')
                    ->where('gfl.id_lang', $id_lang)
                    ->where('gf.publish', 1)
                    ->orderBy('gf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $gallery['next'] = $this->getNextGallery($gallery['id_page_cont'], $gallery['order'], $id_lang);
            $gallery['prev'] = $this->getPrevGallery($gallery['id_page_cont'], $gallery['order'], $id_lang);
        }
        return $gallery;
    }

    private function getPrevGallery($id_content, $order, $id_lang) {
        $gallery = $this->galleryModel->select('gallery.id,gl.name,l.link')
                ->join('gallery_lang gl', 'gallery.id=gl.id_gallery')
                ->join('links l', 'l.id=gl.id_link')
                ->where('gallery.id_page_cont', $id_content)
                ->where('gallery.order >', $order)
                ->where('gallery.publish', 1)
                ->where('gl.id_lang', $id_lang)
                ->orderBy('gallery.order', 'ASC')
                ->first();
        if (!empty($gallery)) {
            $gallery['link'] = ($this->locale ? $this->locale . '/' : '') . $gallery['link'];
        }
        return $gallery;
    }

    private function getNextGallery($id_content, $order, $id_lang) {
        $gallery = $this->galleryModel->select('gallery.id,gl.name,l.link')
                ->join('gallery_lang gl', 'gallery.id=gl.id_gallery')
                ->join('links l', 'l.id=gl.id_link')
                ->where('gallery.id_page_cont', $id_content)
                ->where('gallery.order <', $order)
                ->where('gallery.publish', 1)
                ->where('gl.id_lang', $id_lang)
                ->orderBy('gallery.order', 'DESC')
                ->first();
        if (!empty($gallery)) {
            $gallery['link'] = ($this->locale ? $this->locale . '/' : '') . $gallery['link'];
        }
        return $gallery;
    }

    public function getBreadcramb($gallery, $locale, $id_lang) {
        if (!empty($gallery)) {
            return array(array(
                'id' => $gallery['id'],
                'name' => $gallery['name'],
                'link' => ($locale ? '/' . $locale : '') . '/' . $gallery['link'],
            ));
        }
    }

    public function getMetaTags($id_gallery, $id_lang, $data=array()) {
        helper('text');
        $meta_tags = array();
        $meta = $this->galleryModel->join('gallery_lang gl', 'gallery.id=gl.id_gallery')->join('gallery_meta_lang gml', 'gallery.id=gml.id_gallery', 'left')->select('gl.name,gl.introduction,gml.title as meta_title,gml.description as meta_desc,gml.keywords as meta_keys')->where('gallery.id', $id_gallery)->where('gl.id_lang', $id_lang)->where('gml.id_lang', $id_lang)->first();
        if (!empty($meta)) {
            $meta['image'] = $this->galleryModel->db->table('gallery_files gf')->join('gallery_files_lang gfl', 'gf.id=gfl.id_file', 'left')->select('gf.path,gf.ext,gfl.caption')->where('gf.id_gallery', $id_gallery)->where('gf.field', 'photo')->where('gfl.id_lang', $id_lang)->get()->getRowArray();
            if (!empty($meta['meta_title'])) {
                $meta_tags['title'] = $meta['meta_title'];
                $meta_tags['image_alt'] = $meta['meta_title'];
            } elseif (!empty($meta['name'])) {
                $meta_tags['title'] = $meta['name'];
                $meta_tags['image_alt'] = $meta['name'];
            }
            if (!empty($meta['meta_desc'])) {
                $meta_tags['description'] = $meta['meta_desc'];
            } elseif (!empty($meta['introduction'])) {
                $meta_tags['description'] = character_limiter($meta['introduction'], 160);
            }
            if (!empty($meta['meta_keys'])) {
                $meta_tags['keywords'] = $meta['meta_keys'];
            }
            if (!empty($meta['image']) && !empty($meta['image']['path']) && file_exists(WRITEPATH . 'uploads/' . $meta['image']['path'])) {
                $size = getimagesize(WRITEPATH . 'uploads/' . $meta['image']['path']);
                $meta_tags['image'] = base_url() . 'image/' . $meta['image']['path'];
                $meta_tags['image_width'] = $size[0];
                $meta_tags['image_height'] = $size[1];
                $meta_tags['image_type'] = $meta['image']['ext'];
                if (!empty($meta['image']['caption'])) {
                    $meta_tags['image_alt'] = $meta['image']['caption'];
                }
            }
        }
        return $meta_tags;
    }

    public function getLanguageLinks($id_gallery) {
        $links = array();
        $list = $this->galleryModel->db->table('gallery_lang gl')->join('links l', 'l.id=gl.id_link')->select('gl.id_lang,l.link')->where('id_gallery', $id_gallery)->orderBy('id_lang', 'ASC')->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                $links[$l['id_lang']] = $l['link'];
            }
        }
        return $links;
    }

}
