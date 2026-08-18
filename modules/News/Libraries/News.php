<?php

namespace Modules\News\Libraries;

use Modules\News\Models\NewsModel;
use Modules\Advertisement\Models\AdvertisementModel;
use HungCP\PhpSimpleHtmlDom\HtmlDomParser;

class News {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->newsModel = new NewsModel();
    }

    public function index($content, $id_lang, $slug = '', $link = array()) {
        helper('text');
        $config = new \Config\Pager;
        switch ($slug) {
            case 'most_read':
                $query = $this->newsModel
                        ->join('news_lang nl', 'news.id=nl.id_news')
                        ->join('links l', 'l.id=nl.id_link')
                        ->select('news.id,news.date,nl.title,nl.header,nl.introduction,nl.author,l.link,l.redirect as link_redirect,nl.views')
                        ->where('news.publish', 1)
                        ->where('nl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang)
                        ->orderBy('views', 'DESC')
                ;
                if (!empty($content['config']['no'])) {
                    $query->limit(intval($content['config']['no']));
                }
                if (!empty($content['config']['mostreaddays'])) {
                    $data = date("Y-m-d", strtotime("-" . $content['config']['mostreaddays'] . " days"));
                    $query->Where('news.created_at>=', $data);
                }
				if(!empty($content['config']['option']) and $content['config']['option']=='home') {
				   $query->Where('news.home', 1);	
				}	
                $list = $query->get()->getResultArray();
                return array(
                    'views_dir' => 'list',
                    'list' => $list
                );
                break;
            case 'instagram':
                $lists = $query = $this->newsModel->db->table('instagram')->Select('link,likes,comments,thumb,photo,caption')->OrderBy('created_at', 'DESC')->Limit($content['config']['no'])->get()->getResultArray();
                return array(
                    'views_dir' => 'instagram',
                    'list' => $lists
                );
                break;
            case 'selected':
                $query = $this->newsModel;
                if (empty($content['config']) || empty($content['config']['pagination']) || !$content['config']['pagination']) {
                    $query->db->table('news');
                }
                $query->join('news_lang nl', 'news.id=nl.id_news')
                        ->join('links l', 'l.id=nl.id_link')
                        ->select('news.id,news.id_page_cont,news.date,news.edited_at,nl.title,nl.header,nl.introduction,nl.author,l.link,l.redirect as link_redirect,show_in_box')
                        ->where('news.publish', 1)
                        ->where('nl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang);

                if (empty($content['config']['order'])) {
                    $content['config']['order'] = '';
                }
                if (!empty($content['config']['option'])) {
                    switch ($content['config']['option']) {
                        case 'home': $query->where('news.home', 1);
                            break;
                        case 'investments': $query->where('news.investments', 1);
                            break;
                        case 'slider': $query->where('news.slider', 1);
                            break;
                        case 'notslider': $query->where('news.slider', 0);
                            break;
                    }
                }

                if (!empty($content['config']['lists']) && !in_array(0, $content['config']['lists'])) {
                    $query->whereIn('news.id_page_cont', $content['config']['lists']);
                }

                if (!empty($content['config']['tags'])) {
                    $query->groupStart();
                    foreach ($content['config']['tags'] as $id_tag) {
                        $query->orLike('nl.tags', ',' . intval($id_tag) . ',');
                    }
                    $query->groupEnd();
                }

                if (empty($content['config']['order'])) {
                    $content['config']['order'] = '';
                }
                switch ($content['config']['order']) {
                    case 'random':
                        $query->orderBy('news.order', 'RANDOM');
                        break;
                    case 'date':
                        $query->orderBy('news.date', 'DESC');
                        break;
                    case 'latest':
                        $query->orderBy('news.date', 'DESC')->orderBy('id', 'DESC');
                        break;
                    case 'latest_sponsored':
                        $query->orderBy('news.date', 'DESC');
                        $query->orderBy('FIELD(tio_news.id_page_cont,13)', 'ASC', false);
                        break;
                    default: $query->orderBy('news.order', 'ASC');
                        break;
                }
                if (empty($content['config']) || empty($content['config']['pagination']) || !$content['config']['pagination']) {
                    $news = $query->limit(!empty($content['config']) && !empty($content['config']['no']) ? intval($content['config']['no']) : $config->perPage)->get()->getResultArray();
                } else {
                    $news = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'news-' . $content['id']);
                }
                if (!empty($news)) {
                    foreach ($news as $k => $n) {
                        $news[$k]['photo'] = $this->newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->select('nf.path,nf.crop_dimension,nfl.caption,nfl.author')->where('nf.id_news', $n['id'])->where('nf.publish', 1)->where('nf.field', 'photo')->where('nfl.id_lang', $id_lang)->get()->getRowArray();
                        $news[$k]['link'] = (!$n['link_redirect'] && $this->locale ? $this->locale . '/' : '') . $n['link'];
                        $news[$k]['link_external'] = $n['link_redirect'] && str_contains($n['link'], 'http');
                        if (empty($n['header'])) {
                            $news[$k]['info_page'] = $this->newsModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->select('p.id,pl.name,pl.header,l.link')->where('pc.id', $n['id_page_cont'])->where('pl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->where('p.publish', 1)->get()->getRowArray();
                        }
                    }
                }
                return array(
                    'views_dir' => 'list',
                    'list' => $news,
                    'pager' => !empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination'] ? $this->newsModel->pager : null,
                );
                break;
            case 'list':
            default :
                $query = $this->newsModel;
                if (empty($content['config']) || empty($content['config']['pagination']) || !$content['config']['pagination']) {
                    $query->db->table('news');
                }
                $query->join('news_lang nl', 'news.id=nl.id_news')
                        ->join('links l', 'l.id=nl.id_link')
                        ->select('news.id,news.id_page_cont,news.date,nl.title,nl.header,nl.introduction,nl.author,l.link,l.redirect as link_redirect')
                        ->where('news.publish', 1)
                        ->where('nl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang)
                        ->where('news.id_page_cont', $content['id']);
                if (empty($content['config']) || empty($content['config']['order'])) {
                    $content['config']['order'] = '';
                }
                switch ($content['config']['order']) {
                    case 'random':
                        $query->orderBy('news.order', 'RANDOM');
                        break;
                    case 'date':
                        $query->orderBy('news.date', 'DESC');
                        break;
                    case 'latest':
                    default: $query->orderBy('news.date', 'DESC')->orderBy('news.id', 'DESC');
                        break;
                }
                if (empty($content['config']) || empty($content['config']['pagination']) || !$content['config']['pagination']) {
                    $news = $query->limit(!empty($content['config']) && !empty($content['config']['no']) ? intval($content['config']['no']) : $config->perPage)->get()->getResultArray();
                } else {
                    $news = $query->paginate(!empty($content['config']) && !empty($content['config']['no']) ? $content['config']['no'] : $config->perPage, 'news-' . $content['id']);
                }

                if (!empty($news)) {
                    foreach ($news as $k => $n) {
                        $news[$k]['photo'] = $this->newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->select('nf.path,nf.crop_dimension,nfl.caption,nfl.author')->where('nf.id_news', $n['id'])->where('nf.publish', 1)->where('nf.field', 'photo')->where('nfl.id_lang', $id_lang)->get()->getRowArray();
                        $news[$k]['link'] = (!$n['link_redirect'] && $this->locale ? $this->locale . '/' : '') . $n['link'];
                        $news[$k]['link_external'] = $n['link_redirect'] && str_contains($n['link'], 'http');
                        if (empty($n['header'])) {
                            $news[$k]['info_page'] = $this->newsModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->select('p.id,pl.name,pl.header,l.link')->where('pc.id', $n['id_page_cont'])->where('pl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->where('p.publish', 1)->get()->getRowArray();
                        }
                    }
                    $pagination = array(
                        'first' => base_url() . $link['link'],
                        'prev' => $this->newsModel->pager->getCurrentPage('news-' . $content['id']) == 2 ? base_url() . $link['link'] : $this->newsModel->pager->getPreviousPageURI('news-' . $content['id']),
                        'next' => $this->newsModel->pager->getNextPageURI('news-' . $content['id']),
                        'last' => base_url() . $link['link'] . '?page_news-' . $content['id'] . '=' . $this->newsModel->pager->getPageCount('news-' . $content['id']),
                    );
                    if ($this->newsModel->pager->getCurrentPage('news-' . $content['id']) > 1) {
                        $pagination['canonical'] = base_url() . $link['link'] . '?page_news-' . $content['id'] . '=' . $this->newsModel->pager->getCurrentPage('news-' . $content['id']);
                    }
                }
                return array(
                    'list' => $news,
                    'pager' => !empty($content['config']) && !empty($content['config']['pagination']) && $content['config']['pagination'] ? $this->newsModel->pager : null,
                    'pagination' => isset($pagination) ? $pagination : null,
                );
                break;
        }
    }

    public function assets($slug = '', $template = '', $id_news = 0, $data = array()) {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'js_ready' => array()
        );
        switch ($slug) {
            case 'single_element':
                $assets['js'][] = '/assets/js/jquery.touchSwipe.min.js';
                $assets['js'][] = '/assets/js/tiolightbox.js';
                $assets['js'][] = '/assets/js/page.js';
                $assets['css'][] = '/assets/css/tiolightbox.css';
                $assets['js_ready'][] = '$(".photos a").TiO_lightbox({});';
                $assets['js_ready'][] = 'updateViews();';
                if(!empty($data['script'])) {
                    $assets['js_ready'][] = $data['script'];
                }
                break;
            case 'selected':
                if ($template == 'entertainment_slider') {
                    $assets['js'][] = '/assets/js/event.js';
                    if (!empty($data) && !empty($data['list']) && count($data['list']) > 1) {
                        $assets['css'][] = '/assets/css/slick.css';
                        $assets['js'][] = '/assets/js/slick.min.js';
                        $assets['js_ready'][] = "$('.entertainment-boxes .entertainment-news-slider .list').slick({
                                autoplay: true,
                                pauseOnFocus: true,
                                pauseOnHover: true,
                                autoplaySpeed: 5000,
                                fade: true,
                                arrows: false,
                                dots: false,
                                speed: 800
                          });";
                    }
                }
                $assets['js'][] = '/assets/js/page.js';
                break;
            case 'list':
            default :
                $assets['js'][] = '/assets/js/page.js';
                break;
        }
        return $assets;
    }

    private function newsCode($code, $id_lang) {
        $params = array();
        $code = str_replace('[news ', '', substr(str_replace(PHP_EOL, '', $code), 0, -1));
        $tmp = explode(' ', $code);
        if (!empty($tmp)) {
            foreach ($tmp as $t) {
                $t = explode('=', $t);
                if (!empty($t) && !empty($t[1])) {
                    $params[$t[0]] = trim($t[1], '"');
                }
            }
        }
        if (!empty($params) && !empty($params['id'])) {
            $news = $this->newsModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->join('news_files nf', 'nf.id_news=n.id AND nf.field="photo"', 'left')->select('n.id,nl.title,nl.introduction,nl.content,l.link,nf.path')->where('n.publish', 1)->whereIn('n.id', explode(',', $params['id']))->where('nl.id_lang', $id_lang)->get()->getResultArray();
            if (!empty($news)) {
                foreach ($news as $k => $n) {
                    $news[$k]['link'] = ($this->locale ? $this->locale . '/' : '') . $n['link'];
                    if (!empty($n['introduction'])) {
                        $news[$k]['introduction'] = character_limiter($n['introduction'], 140, '...');
                    } else {
                        $news[$k]['introduction'] = character_limiter(strip_tags($n['content']), 140, '...');
                    }
                }
            }
            return view('Modules\News\Views\user\link_in_content', array('news' => $news));
        }
        return '';
    }

    public function getSingleByLinkId($id_link, $id_lang, $link) {
        helper('text');
        $news = $this->newsModel->db->table('news')
                        ->join('news_lang nl', 'news.id=nl.id_news')
                        ->join('page_content pc', 'news.id_page_cont=pc.id')
                        ->join('links l', 'l.id=nl.id_link')
                        ->select('pc.id_page,news.id,news.id_page_cont,news.template,news.date,news.edited_at,news.order,news.comment,news.script,nl.title,nl.subtitle,nl.header,nl.introduction,nl.content,nl.source,nl.id_link,nl.author,nl.tags,nl.views,l.link')
                        ->where('news.publish', 1)
                        ->where('nl.id_lang', $id_lang)
                        ->where('nl.id_link', $id_link)
                        ->get()->getRowArray();
        if (!empty($news)) {
            $news['info_page'] = $this->newsModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->select('p.id,pl.name,pl.header,l.link')->where('pc.id', $news['id_page_cont'])->where('pl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->where('p.publish', 1)->get()->getRowArray();
            $this->id_lang = $id_lang;
            if(in_array($news['id_page_cont'], array(10, 11,13)) && strlen($news['content']) > 1500) {
                $news['original_content'] = $news['content'];
                $advertisementModel = new AdvertisementModel();
                $advertisement = $advertisementModel->getAdvertisement(10, $id_lang);
                if(!empty($advertisement) && !empty($advertisement['template'])) {
                    $content_length = strlen(strip_tags($news['content']));
                    $is = false;
                    $ad_content = '';
                    $dom = HtmlDomParser::str_get_html($news['content']);
                    $ret = $dom->find('p, h1, h2, h3, h4, h5, h6, pre, ul, ol, table');
                    if(!empty($ret)) {
                        $content = '';
                        foreach($ret as $k=>$r){
                            $content .= $r->outertext;
                            if($k + 1 >= count($ret) / 2 && !$is) {
                                $ad_content = view('Modules\Advertisement\Views\user\\' . $advertisement['template'], array('data' => $advertisement, 'locale' => $this->locale));
                                $content .= $ad_content;
                                $is = true;
                            }
                        }
                        if(abs($content_length - (strlen(strip_tags($content)) - strlen(strip_tags($ad_content)))) < 200) {
                            $news['content'] = $content;
                        } else {
                            $is = false;
                        }
                    }
                    if(!$is) {
                        $characters_b = 1200;
                        $content = '';
                        $is = false;
                        if (strlen(strip_tags($news['content'])) > $characters_b) {
                            $content_tab = explode('<br />', $news['content']);
                            $characters_t = 0;
                            $characters_b = strlen(strip_tags($news['content'])) / 2;
                            foreach ($content_tab as $content_row) {
                                if ($characters_t < $characters_b || $is) {
                                    $content .= $content_row . '<br />';
                                    $characters_t = $characters_t + strlen(strip_tags($content_row));
                                } else {
                                    $content .= $content_row . '' . view('Modules\Advertisement\Views\user\\' . $advertisement['template'], array('data' => $advertisement, 'locale' => $this->locale));
                                    $is = true;
                                }
                            }
                            $news['content'] = $content;
                        }
                    }
                }
            }
            $news['content'] = preg_replace_callback_array([
                '/\<p\>\<span(.*?)\>\[news (.*?)\]\<\/span\>\<\/p\>/s' => function ($matches) {
                    return $this->newsCode(strip_tags($matches[0]), $this->id_lang);
                },
                '/\<p\>\[news (.*?)\]\<\/p\>/s' => function ($matches) {
                    return $this->newsCode(strip_tags($matches[0]), $this->id_lang);
                },
                '/\[news (.*?)\]/s' => function ($matches) {
                    return $this->newsCode(strip_tags($matches[0]), $this->id_lang);
                },
            ], $news['content']);
            
            $news['photo'] = $this->newsModel->db
                    ->table('news_files nf')
                    ->join('news_files_lang nfl', 'nf.id=nfl.id_file')
                    ->select('nf.path,nf.mime,nfl.caption,nfl.author,nf.crop_dimension')
                    ->where('nf.id_news', $news['id'])
                    ->where('nf.field', 'photo')
                    ->where('nfl.id_lang', $id_lang)
                    ->where('nf.publish', 1)
                    ->get()
                    ->getRowArray();
            $news['photos'] = $this->newsModel->db
                    ->table('news_files nf')
                    ->join('news_files_lang nfl', 'nf.id=nfl.id_file')
                    ->select('nf.path,nf.mime,nfl.caption,nfl.author')
                    ->where('nf.id_news', $news['id'])
                    ->where('nf.field', 'photos')
                    ->where('nfl.id_lang', $id_lang)
                    ->where('nf.publish', 1)
                    ->orderBy('nf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $news['audio'] = $this->newsModel->db
                    ->table('news_files nf')
                    ->join('news_files_lang nfl', 'nf.id=nfl.id_file')
                    ->select('nf.path,nf.mime,nfl.caption,nfl.author')
                    ->where('nf.id_news', $news['id'])
                    ->where('nf.field', 'audio')
                    ->where('nfl.id_lang', $id_lang)
                    ->where('nf.publish', 1)
                    ->orderBy('nf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $news['video'] = $this->newsModel->db
                    ->table('news_files nf')
                    ->join('news_files_lang nfl', 'nf.id=nfl.id_file')
                    ->select('nf.path,nf.mime,nfl.caption,nfl.author')
                    ->where('nf.id_news', $news['id'])
                    ->where('nf.field', 'video')
                    ->where('nfl.id_lang', $id_lang)
                    ->where('nf.publish', 1)
                    ->orderBy('nf.order', 'ASC')
                    ->get()
                    ->getResultArray();
            $news['tags'] = explode(',', trim($news['tags'], ','));
            if (!empty($news['tags'])) {
                $news['tags'] = $this->newsModel->db->table('tags t')->select('t.id,t.tag')->where('t.id_lang', $id_lang)->whereIn('t.id', $news['tags'])->get()->getResultArray();
            }
            //$news['next'] = $this->getNextNews($news['id_page_cont'], $news['order'], $id_lang);
            //$news['prev'] = $this->getPrevNews($news['id_page_cont'], $news['order'], $id_lang);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            exit();
        }
        return $news;
    }

    private function getPrevNews($id_content, $order, $id_lang) {
        $news = $this->newsModel->select('news.id,nl.title,l.link,l.link as link_redirect')
                ->join('news_lang nl', 'news.id=nl.id_news')
                ->join('links l', 'l.id=nl.id_link')
                ->where('news.id_page_cont', $id_content)
                ->where('news.order >', $order)
                ->where('news.publish', 1)
                ->where('nl.id_lang', $id_lang)
                ->orderBy('news.order', 'ASC')
                ->first();
        if (!empty($news)) {
            $news['link'] = (!$news['link_redirect'] && $this->locale ? $this->locale . '/' : '') . $news['link'];
            $news['link_external'] = $news['link_redirect'] && str_contains($news['link'], 'http');
        }
        return $news;
    }

    private function getNextNews($id_content, $order, $id_lang) {
        $news = $this->newsModel->select('news.id,nl.title,l.link,l.redirect as link_redirect')
                ->join('news_lang nl', 'news.id=nl.id_news')
                ->join('links l', 'l.id=nl.id_link')
                ->where('news.id_page_cont', $id_content)
                ->where('news.order <', $order)
                ->where('news.publish', 1)
                ->where('nl.id_lang', $id_lang)
                ->orderBy('news.order', 'DESC')
                ->first();
        if (!empty($news)) {
            $news['link'] = (!$news['link_redirect'] && $this->locale ? $this->locale . '/' : '') . $news['link'];
            $news['link_external'] = $news['link_redirect'] && str_contains($news['link'], 'http');
        }
        return $news;
    }

    public function getBreadcramb($news, $locale, $id_lang, $link) {
        if (!empty($news)) {
            return array(array(
                    'id' => $news['id'],
                    'name' => $news['title'],
                    'link' => ($locale ? '/' . $locale : '') . '/' . $news['link'],
            ));
        }
    }
    
    public function ajax($post, $id_lang, $module_data, $link) {
        if(!empty($post['action'])) {
            switch($post['action']) {
                case 'views':
                    $this->newsModel->db->table('news_lang')->where('id_link', $link['id'])->where('id_lang', $id_lang)->set('views', 'views+1', false)->update();
                    return $this->response->setJSON(array());
                    break;
            }
        }
    }

    public function getMetaTags($id_news, $id_lang, $link, $data=array()) {
        helper('text');
        $meta = $this->newsModel->join('news_lang nl', 'news.id=nl.id_news')->join('news_meta_lang nml', 'news.id=nml.id_news', 'left')->select('nl.title,nl.introduction,nl.tags,nml.title as meta_title,nml.description as meta_desc,nml.keywords as meta_keys')->where('news.id', $id_news)->where('nl.id_lang', $id_lang)->where('nml.id_lang', $id_lang)->first();
        if (!empty($meta)) {
            $meta['image'] = $this->newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file', 'left')->select('nf.path,nf.ext,nfl.caption')->where('nf.id_news', $id_news)->where('nf.field', 'photo')->where('nfl.id_lang', $id_lang)->get()->getRowArray();
            if (!empty($meta['meta_title'])) {
                $metatags['title'] = $meta['meta_title'];
                $metatags['image_alt'] = $meta['meta_title'];
            } elseif (!empty($meta['title'])) {
                $metatags['title'] = $meta['title'];
                $metatags['image_alt'] = $meta['title'];
            }
            if (!empty($meta['meta_desc'])) {
                $metatags['description'] = $meta['meta_desc'];
            } elseif (!empty($meta['introduction'])) {
                $metatags['description'] = character_limiter($meta['introduction'], 160);
            }
            if (!empty($meta['meta_keys'])) {
                $metatags['keywords'] = $meta['meta_keys'];
            } elseif (!empty($meta['tags'])) {
                $meta['tags'] = explode(',', trim($meta['tags'], ','));
                if (!empty($meta['tags'])) {
                    $tags = $this->newsModel->db->table('tags t')->select('t.id,t.tag')->where('t.id_lang', $id_lang)->whereIn('t.id', $meta['tags'])->get()->getResultArray();
                    if (!empty($tags)) {
                        $key_tag = array();
                        foreach ($tags as $tag) {
                            if (!empty($tag['tag'])) {
                                $key_tag[] = $tag['tag'];
                            }
                        }
                        if (!empty($key_tag)) {
                            $metatags['keywords'] = implode(', ', $key_tag);
                        }
                    }
                }
            }
            if (!empty($meta['image']) && !empty($meta['image']['path']) && file_exists(WRITEPATH . 'uploads/' . $meta['image']['path'])) {
                $size = getimagesize(WRITEPATH . 'uploads/' . $meta['image']['path']);
                $metatags['image'] = base_url() . 'image/' . $meta['image']['path'];
                $metatags['image_width'] = $size[0];
                $metatags['image_height'] = $size[1];
                $metatags['image_type'] = $meta['image']['ext'];
                if (!empty($meta['image']['caption'])) {
                    $metatags['image_alt'] = $meta['image']['caption'];
                }
            }
            
            if(!empty($data)) {
                $images = [];
                if(!empty($data['photo']) && file_exists(WRITEPATH . 'uploads/' . $data['photo']['path'])) {
                    $photo_info = getimagesize(WRITEPATH . 'uploads/' . $data['photo']['path']);
                    $images[] = [
                        '@type' => 'ImageObject',
                        '@id' => base_url() . 'image/' . $data['photo']['path'],
                        'url' => base_url() . 'image/' . $data['photo']['path'],
                        'width' => $photo_info[0],
                        'height' => $photo_info[1],
                        'caption' => $data['photo']['caption'],
                    ];
                }
                if(!empty($data['photos'])) {
                    foreach($data['photos'] as $photo) {
                        if(file_exists(WRITEPATH . 'uploads/' . $photo['path'])) {
                            $photo_info = getimagesize(WRITEPATH . 'uploads/' . $photo['path']);
                            $images[] = [
                                '@type' => 'ImageObject',
                                '@id' => base_url() . 'image/' . $photo['path'],
                                'url' => base_url() . 'image/' . $photo['path'],
                                'width' => $photo_info[0],
                                'height' => $photo_info[1],
                                'caption' => $photo['caption'],
                            ];
                        }
                    }
                }
                $metatags['microdata']['single'] = [
                    '@type' => 'NewsArticle',
                    '@id' => !empty($data['link']) ? $data['link'] : '',
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => !empty($data['link']) ? $data['link'] : '',
                    ],
                    'headline' => !empty($data['title']) ? esc($data['title']) : '',
                    'alternativeHeadline' => !empty($data['subtitle']) ? esc($data['subtitle']) : '',
                    'description' => !empty($data['introduction']) ? esc($data['introduction']) : '',
                    'url' => !empty($data['link']) ? $data['link'] : '',
                    'datePublished' => date('Y-m-d', strtotime($data['date'])) . 'T' . date('H:i:s', strtotime($data['date'])) . 'Z',
                    'dateModified' => !empty($data['edited_at']) ? date('Y-m-d', strtotime($data['edited_at'])) . 'T' . date('H:i:s', strtotime($data['edited_at'])) . 'Z' : '',
                    'author' => [
                        '@type' => 'Person',
                        'name' => !empty($data['author']) ? esc($data['author']) : 'Redakcja',
                    ],
                    'publisher' => [
                        '@id' => base_url() . '#organization',
                    ],
                    'image' => $images,
                    'articleSection' => !empty($data['info_page']) && !empty($data['info_page']['name']) ? $data['info_page']['name'] : '',
                    'articleBody' => !empty($data['original_content']) ? esc($data['original_content']) : esc($data['content']),
                    'isAccessibleForFree' => true,
                ];
            }
        }
        return $metatags;
    }

    public function getContentMetaTags($content, $metatags, $data, $settings, $language) {
        if (empty($data)) {
            return $metatags;
        }
        $main_entity = [];
        if(!empty($data['list'])) {
            $list = array();
            foreach($data['list'] as $k=>$l) {
                if(!empty($l['photo']) && !empty($l['photo']['path']) && file_exists(WRITEPATH . 'uploads/' . $l['photo']['path'])) {
                    $l['photo']['info'] = getimagesize(WRITEPATH . 'uploads/' . $l['photo']['path']);
                }
                $list[] = array(
                    '@type' => 'NewsArticle',
                    'position' => $k + 1,
                    '@id' => base_url() . $l['link'],
                    'headline' => esc($l['title']),
                    'alternativeHeadline' => esc($l['header']),
                    'url' => base_url() . $l['link'],
                    'datePublished' => date('Y-m-d', strtotime($l['date'])) . 'T' . date('H:i:s', strtotime($l['date'])) . 'Z',
                    'dateModified' => !empty($l['edited_at']) ? date('Y-m-d', strtotime($l['edited_at'])) . 'T' . date('H:i:s', strtotime($l['edited_at'])) . 'Z' : '',
                    'author' => array(
                        array(
                            '@type' => 'Person',
                            'name' => !empty($l['author']) ? $l['author'] : 'Redakcja',
                            'url' => ''
                        )
                    ),
                    'publisher' => array(
                        '@id' => base_url() . '#organization'
                    ),
                    'image' => array(
                        array(
                            '@type' => 'ImageObject',
                            'url' => !empty($l['photo']) && !empty($l['photo']['path']) ? base_url() . 'image/' . $l['photo']['path'] : '',
                            'width' => !empty($l['photo']) && !empty($l['photo']['info']) ? $l['photo']['info'][0] : '',
                            'height' => !empty($l['photo']) && !empty($l['photo']['info']) ? $l['photo']['info'][1] : '',
                            'caption' => esc(!empty($l['photo']) && !empty($l['photo']['caption']) ? $l['photo']['caption'] : $l['title'])
                        )
                    ),
                    'articleSection' => !empty($l['info_page']) && !empty($l['info_page']['name']) ? $l['info_page']['name'] : '',
                    'articleBody' => '',
                    'wordCount' => '',
                    'description' => esc($l['introduction']),
                    'keywords' => '',
                    'inLanguage' => !empty($language['iso_code']) ? $language['iso_code'] : $language['lang_code']
                );
            }
            
            $main_entity[] = [
                '@type' => 'ItemList',
                'numberOfItems' => count($list),
                'itemListElement' => $list
            ];
            
        }
        
        
        if(!empty($main_entity)) {
            if(empty($metatags['microdata']['collection_page'])) {
                $metatags['microdata']['collection_page'] = [
                    '@type' => 'CollectionPage',
                    '@id' => $metatags['canonical'] . '#collectionpage',
                    'url' => $metatags['canonical'],
                    'name' => $metatags['title'],
                    'description' => $metatags['description'],
                    'inLanguage' => !empty($language['iso_code']) ? $language['iso_code'] : $language['lang_code'],
                    'isPartOf' => [
                        '@id' => base_url() . '#website'
                    ],
                    'mainEntity' => $main_entity
                ];
            } else {
                $metatags['microdata']['collection_page']['mainEntity'] = array_merge($metatags['microdata']['collection_page']['mainEntity'], $main_entity);
            }
        }
        
        return $metatags;
    }

    public function getLanguageLinks($id_news) {
        $links = array();
        $list = $this->newsModel->db->table('news_lang nl')->join('links l', 'l.id=nl.id_link')->select('nl.id_lang,l.link')->where('id_news', $id_news)->orderBy('id_lang', 'ASC')->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                $links[$l['id_lang']] = $l['link'];
            }
        }
        return $links;
    }

    public function getNewsBigBox($id_lang, $locale, $id_page, $template, $config) {
        $lists = array();
        for ($i = 0; $i <= 5; $i++) {
            $query = $this->newsModel
                    ->join('news_lang nl', 'news.id=nl.id_news')
                    ->join('links l', 'l.id=nl.id_link')
                    ->select('news.id,news.id_page_cont,news.date,nl.title,nl.header,nl.introduction,nl.author,l.link,l.redirect as link_redirect')
                    ->where('news.publish', 1)
                    ->where('nl.id_lang', $id_lang)
                    ->where('l.id_lang', $id_lang);
            $query->OrderBy("news.id", 'RANDOM');
            $query->where('news.show_in_box', ($i + 1))->limit(1);
            $box = $query->get()->getRowArray();
            if (!empty($box)) {
                $news_box[$i] = $box;
            }
        }

        if (!empty($id_page)) {
            $page_ids = array();
            $page_ids[] = $id_page;
            $sub_page = $this->newsModel->db->table('page')->Select('id')->Where('re_id', $id_page)->Where('publish', 1)->get()->getResultArray();
            if (!empty($sub_page)) {
                foreach ($sub_page as $sub) {
                    if ($sub['id'] != 13) {
                        $page_ids[] = $sub['id'];
                    }
                }
            }
            $sub_cnt = $this->newsModel->db->table('page_content')->Select('id')->WhereIn('id_page', $page_ids)->Where('publish', 1)->get()->getResultArray();
            if (!empty($sub_cnt)) {
                $page_ids = array();
                foreach ($sub_page as $sub) {
                    if ($sub['id'] != 13) {
                        $page_ids[] = $sub['id'];
                    }
                }
            }
        }
        if (!empty($page_ids)) {
            $query = $this->newsModel
                ->join('news_lang nl', 'news.id=nl.id_news')
                ->join('links l', 'l.id=nl.id_link')
                ->select('news.id,news.date,news.id_page_cont,nl.title,nl.header,nl.introduction,nl.author,l.link,l.redirect as link_redirect')
                ->where('news.publish', 1)
                ->where('nl.id_lang', $id_lang)
                ->where('l.id_lang', $id_lang)
                ->limit(intval($config['no']))
                ->whereIn('news.id_page_cont', $page_ids)
                ->where('news.home', 1);
            $query->groupStart();
            $query->where('show_in_box', NULL);
            $query->orwhere('show_in_box', 0);
            $query->groupEnd();
            $query->OrderBy('date', 'DESC');
            $query->OrderBy('news.id', 'DESC');
            $lists_others = $query->get()->getResultArray();
        }


        $query = $this->newsModel
                ->join('news_lang nl', 'news.id=nl.id_news')
                ->join('links l', 'l.id=nl.id_link')
                ->select('news.id,news.date,news.id_page_cont,nl.title,nl.header,nl.introduction,nl.author,l.link,l.redirect as link_redirect')
                ->where('news.publish', 1)
                ->where('nl.id_lang', $id_lang)
                ->where('l.id_lang', $id_lang)
                ->limit(intval($config['no']))
                ->where('news.id_page_cont', 13);
        $query->where('home', 1);
        $query->OrderBy('date', 'DESC');
        $query->OrderBy('news.id', 'DESC');
        $lists_sponsored = $query->get()->getResultArray();

        if (empty($news_box)) {
            $lists = $lists_others;
        } else {
            for ($i = 0; $i <= 5; $i++) {
                if (!empty($news_box[$i])) {
                    $lists_join[] = $news_box[$i];
                } elseif(!empty($lists_others)) {
                    $key = key($lists_others);
                    $lists_join[] = $lists_others[$key];
                    unset($lists_others[$key]);
                }
            }
            $lists = array_merge($lists_join, $lists_others);
        }
        if (!empty($lists)) {
            if (!empty($lists_sponsored)) {
                foreach ($lists as $k => $v) {
                    if ($k == 7) {
                        foreach ($lists_sponsored as $spons_item) {
                            $new_list[] = $spons_item;
                        }
                        $new_list[] = $v;
                    } else {
                        $new_list[] = $v;
                    }
                }
                $lists = $new_list;
            }
            foreach ($lists as $k => $n) {
                $lists[$k]['photo'] = $this->newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->select('nf.path,nf.crop_dimension,nfl.caption,nfl.author')->where('nf.id_news', $n['id'])->where('nf.publish', 1)->where('nf.field', 'photo')->where('nfl.id_lang', $id_lang)->get()->getRowArray();
                $lists[$k]['link'] = (!$n['link_redirect'] && $locale ? $locale . '/' : '') . $n['link'];
                $list[$k]['link_external'] = $n['link_redirect'] && str_contains($n['link'], 'http');
                $lists[$k]['info_page'] = $this->newsModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->Select('p.id,pl.name,pl.header,l.link')->Where('pc.id', $n['id_page_cont'])->where('pl.id_lang', $id_lang)
                                ->where('l.id_lang', $id_lang)->where('p.publish', 1)->get()->getRowArray();
            }
            $lists = array_slice($lists, 0, $config['no']);
        }
        
        return view('Modules\News\Views\user\home\/' . $template, array('news' => $lists));
    }

    public function showMostReadList($id_lang, $locale, $config=array(), $template = 'mostread_list.php') {
        $query = $this->newsModel
                ->join('news_lang nl', 'news.id=nl.id_news')
                ->join('links l', 'l.id=nl.id_link')
                ->select('news.id,news.date,nl.title,nl.header,nl.introduction,nl.author,l.link,l.redirect as link_redirect,nl.views')
                ->where('news.publish', 1)
                ->where('nl.id_lang', $id_lang)
                ->where('l.id_lang', $id_lang)
                ->orderBy('views', 'DESC')
        ;
        if (!empty($config['no'])) {
            $query->limit(intval($config['no']));
        }
        if (!empty($config['mostreaddays'])) {
            $data = date("Y-m-d", strtotime("-" . $config['mostreaddays'] . " days"));
            $query->Where('news.created_at>=', $data);
        }
        if(!empty($config['option']) && $config['option']=='home') {
            $query->Where('news.home', 1);	
        }	
        $list = $query->get()->getResultArray();
        $html = view('\Modules\News\Views\user/list/' . $template, array('title' => 'NAJCHĘTNIEJ CZYTANE', 'url' => '/wiadomosci', 'data' => array('id_lang' => $id_lang, 'locale' => $locale, 'list' => $list)));
        return $html;
    }
    
    public function showOtherNewsList($id_page_cont, $id_lang, $locale, $exclude = array(), $template = '_other_list.php') {
        $query = $this->newsModel
                ->join('news_lang nl', 'news.id=nl.id_news')
                ->join('links l', 'l.id=nl.id_link')
                ->select('news.id,news.id_page_cont,news.date,nl.title,nl.header,nl.introduction,nl.author,l.link,l.redirect as link_redirect')
                ->where('news.publish', 1)
                ->where('nl.id_lang', $id_lang)
                ->where('l.id_lang', $id_lang)
                ->where('news.id_page_cont', $id_page_cont);
        if (!empty($exclude)) {
            $query->whereNotIn('news.id', $exclude);
        }
        $news = $query->orderBy('news.order', 'ASC')
                ->paginate(8, 'news-' . $id_page_cont);
        $info_page = $this->newsModel->db->table('page_content pc')->join('page p', 'p.id=pc.id_page')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->select('p.id,pl.name,pl.header,l.link')->where('pc.id', $id_page_cont)->where('pl.id_lang', $id_lang)->where('l.id_lang', $id_lang)->where('p.publish', 1)->get()->getRowArray();
        if (!empty($news)) {
            foreach ($news as $k => $n) {
                $news[$k]['photo'] = $this->newsModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->select('nf.path,nf.crop_dimension,nfl.caption,nfl.author')->where('nf.id_news', $n['id'])->where('nf.publish', 1)->where('nf.field', 'photo')->where('nfl.id_lang', $id_lang)->get()->getRowArray();
                $news[$k]['link'] = (!$n['link_redirect'] && $locale ? $locale . '/' : '') . $n['link'];
                $news[$k]['link_external'] = $n['link_redirect'] && str_contains($n['link'], 'http');
                if (empty($n['header'])) {
                    $news[$k]['info_page'] = $info_page;
                }
            }
        }
        $html = view('\Modules\News\Views\user/single/' . $template, array('id_lang' => $id_lang, 'locale' => $locale, 'news' => $news, 'id_page_cont' => $id_page_cont, 'pager' => $this->newsModel->pager, 'info_page' => $info_page));
        return $html;
        return '';
    }
	  public function ajaxSlugs($post, $id_lang, $module_data, $link) {
		   if (!empty($post['action'])) {
            switch ($post['action']) {
                case 'count':
					$this->newsModel->db->table('news_lang')->where('id_news', $post['id'])->where('id_lang', $id_lang)->set('views', 'views+1', false)->update();
                    return $this->response->setJSON(array());
				break;
			}	
		   } 
      }		  
}
