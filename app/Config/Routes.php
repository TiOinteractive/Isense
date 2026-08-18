<?php

use CodeIgniter\Router\RouteCollection;

$routes->set404Override('App\Controllers\Errors::show404');
//$routes->setAutoRoute(true);
/**
 * @var RouteCollection $routes
 */

$admin_slug = env('ADMIN_PANEL_SLUG');
//var_dump(env('ADMIN_PANEL_SLUG'));

//$routes->get('/', 'Home::index');
$routes->get('/', 'Home::index'); // strona główna iSense = strona CMS (link root '') w theme iSense
//$routes->get('/', 'Front::index'); // (etap 1) statyczny front iSense — zachowany jako fallback
$routes->post('/isense/form-submit', 'Front::formSubmit'); // formularze iSense (naprawa z odbiorem / kontakt)
$routes->get('/isense/status', 'Front::orderStatus'); // wyszukiwanie statusu zlecenia (AJAX)
$routes->get('naprawy/(:segment)/(:segment)', 'Front::modelPricing/$1/$2'); // strona modelu (cennik) iSense
$routes->get('/robots.txt', 'Robots::index');
$routes->get('/sitemap.xml', 'Sitemap::sitemaps');
$routes->get('/sitemap-(:segment)-(:num).xml', 'Sitemap::singleSitemap/$1/$2');
$routes->get('/sitemap-(:segment).xml', 'Sitemap::singleSitemap/$1');
$routes->get('/tio.css', 'Assets::prepareCss');
$routes->get('/tio.js', 'Assets::prepareJs');
$routes->get('/file/(:segment)/(:segment)/(:segment)', 'Files::index/$1/$2/$3');
$routes->get('/audio/(:segment)/(:segment)/(:segment)', 'Files::index/$1/$2/$3');
$routes->get('/video/(:segment)/(:segment)/(:segment)', 'Files::index/$1/$2/$3');
$routes->get('/download/(:num)/(:segment)/(:segment)/(:segment)', 'Files::download/$1/$2/$3/$4');
$routes->get('/download/(:segment)/(:segment)/(:segment)', 'Files::download/0/$1/$2/$3');
$routes->match(['GET', 'HEAD'], '/image/ext/(:segment)/(:segment)/(:segment)', 'RenderImage::indexSaveExt/$1/$2/$3');
$routes->match(['GET', 'HEAD'], '/image/ext/(:segment)/(:num)/(:num)/(:segment)/(:segment)/(:segment)', 'RenderImage::saveExt/$1/$2/$3/$4/$5/$6');
$routes->match(['GET', 'HEAD'], '/image/(:segment)/(:num)/(:num)/(:segment)/(:segment)/(:segment)', 'RenderImage::cache/$1/$2/$3/$4/$5/$6');
$routes->match(['GET', 'HEAD'], '/image/original/(:segment)/(:segment)/(:segment)', 'RenderImage::original/$1/$2/$3');
$routes->match(['GET', 'HEAD'], '/image/(:segment)/(:segment)/(:segment)', 'RenderImage::index/$1/$2/$3');
$routes->match(['GET', 'HEAD'], '/image/(:segment)/(:num)/(:num)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)', 'RenderImage::crop/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10');
$routes->get('/nosession/(:segment)/(:segment)/(:segment)', 'NoSession::index/$1/$2/$3');
$routes->match(['GET', 'POST'], '/' . $admin_slug . '/file-menager/(:segment)', 'FileMenager::index/$1', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/' . $admin_slug . '/file-menager/(:segment)/(:segment)', 'FileMenager::index/$1/$2', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/' . $admin_slug . '/file-menager/(:segment)/(:segment)/(:num)', 'FileMenager::index/$1/$2/$3', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/' . $admin_slug . '/filebrowser', 'FileBrowser::index', ['filter' => 'authGuard']);
//$routes->get('/newsletter-action/send-at', '\Modules\Newsletter\Libraries\Newsletter::sendAt');
$routes->get('/newsletter-action/send-at', 'Newsletter::sendAt');
$routes->match(['GET', 'POST'], '/newsletter-action/(:segment)', 'Newsletter::newsletterActions/$1');
$routes->match(['GET', 'POST'], '/newsletter-action/(:segment)/(:hash)', 'Newsletter::newsletterActions/$1/$2');
$routes->match(['GET', 'POST'], '/newsletter-action/(:segment)/(:hash)/(:hash)', 'Newsletter::newsletterActions/$1/$2/$3');
$routes->match(['GET', 'POST'], '/comments-action', '\Modules\Comments\Libraries\Comments::commentsActions');
$routes->match(['GET', 'POST'], '/comments-action/(:segment)', '\Modules\Comments\Libraries\Comments::commentsActions/$1');
$routes->match(['GET', 'POST'], '/comments-action/(:segment)/(:num)', '\Modules\Comments\Libraries\Comments::commentsActions/$1/$2');
$routes->get('/redirect/(:segment)/(:hash)', 'Redirect::index/$1/$2');
$routes->get('/{locale}/newsletter-action/(:segment)/(:hash)', 'Newsletter::newsletterActions/$1/$2');
$routes->get('/' . $admin_slug . '/logout', 'Admin::logout');
$routes->get('/' . $admin_slug . '/lost-password', 'Admin::lostPassword');
$routes->get('/' . $admin_slug . '/new-password/(:hash)', 'Admin::newPassword/$1');
$routes->get('/' . $admin_slug . '/new-password/(:hash)/ok', 'Admin::newPassword/$1/ok');
$routes->post('/' . $admin_slug . '/remindPass', 'Admin::remindPass');
$routes->post('/' . $admin_slug . '/new-password/(:hash)', 'Admin::newPassword/$1');
$routes->post('/' . $admin_slug . '/loginAuth', 'Admin::loginAuth');
$routes->get('/' . $admin_slug, 'Admin::index');
$routes->get('/scripts/(:segment)/(:segment)', '\App\Libraries\Scripts::index/$1/$2', ['filter' => 'authGuard']);
$routes->get('/search/(:segment)', '\App\Libraries\Search::index/$1', ['filter' => 'authGuard']);
$routes->get('/load/(:segment)', '\App\Libraries\Load::index/$1');
$routes->match(['GET', 'POST'], '/' . $admin_slug . '/(:segment)', 'Admin::index/$1', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/' . $admin_slug . '/(:segment)/(:segment)', 'Admin::index/$1/$2', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/' . $admin_slug . '/(:segment)/(:segment)/(:num)', 'Admin::index/$1/$2/$3', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/' . $admin_slug . '/(:segment)/(:segment)/(:num)/(:num)', 'Admin::index/$1/$2/$3/$4', ['filter' => 'authGuard']);

/* locale rules start */
$routes->match(['GET', 'HEAD', 'POST'], '/{locale}/image/(:segment)/(:num)/(:num)/(:segment)/(:segment)/(:segment)', 'RenderImage::cache/$1/$2/$3/$4/$5/$6');
$routes->match(['GET', 'HEAD', 'POST'],'/{locale}/image/(:segment)/(:num)/(:num)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)', 'RenderImage::crop/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10');
$routes->match(['GET', 'HEAD', 'POST'], '/{locale}/image/(:segment)/(:segment)/(:segment)', 'RenderImage::index/$1/$2/$3');
$routes->match(['GET', 'POST'], '/{locale}/' . $admin_slug . '/file-menager/(:segment)', 'FileMenager::index/$1', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/{locale}/' . $admin_slug . '/file-menager/(:segment)/(:segment)', 'FileMenager::index/$1/$2', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/{locale}/' . $admin_slug . '/file-menager/(:segment)/(:segment)/(:num)', 'FileMenager::index/$1/$2/$3', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/{locale}/comments-action', '\Modules\Comments\Libraries\Comments::commentsActions');
$routes->match(['GET', 'POST'], '/{locale}/comments-action/(:segment)', '\Modules\Comments\Libraries\Comments::commentsActions/$1');
$routes->get('/{locale}/' . $admin_slug . '/logout', 'Admin::logout');
$routes->get('/{locale}/' . $admin_slug . '/lost-password', 'Admin::lostPassword');
$routes->get('/{locale}/' . $admin_slug . '/new-password/(:hash)', 'Admin::newPassword/$1');
$routes->get('/{locale}/' . $admin_slug . '/new-password/(:hash)/ok', 'Admin::newPassword/$1/ok');
$routes->post('/{locale}/' . $admin_slug . '/remindPass', 'Admin::remindPass');
$routes->post('/{locale}/' . $admin_slug . '/new-password/(:hash)', 'Admin::newPassword/$1');
$routes->post('/{locale}/' . $admin_slug . '/loginAuth', 'Admin::loginAuth');
$routes->get('/{locale}/' . $admin_slug, 'Admin::index');
$routes->match(['GET', 'POST'], '/{locale}/' . $admin_slug . '/(:segment)', 'Admin::index/$1', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/{locale}/' . $admin_slug . '/(:segment)/(:segment)', 'Admin::index/$1/$2', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/{locale}/' . $admin_slug . '/(:segment)/(:segment)/(:num)', 'Admin::index/$1/$2/$3', ['filter' => 'authGuard']);
$routes->match(['GET', 'POST'], '/{locale}/' . $admin_slug . '/(:segment)/(:segment)/(:num)/(:num)', 'Admin::index/$1/$2/$3/$4', ['filter' => 'authGuard']);
/* locale rules end */

//$routes->get('/{locale}/', 'Home::index');
//$routes->match(['GET', 'POST'], '/{locale}/(:any)', 'Home::index');
$routes->match(['GET', 'POST'], '/cron/(:segment)/(:segment)', 'Cron::index/$1/$2');
$routes->match(['GET', 'HEAD', 'POST'], '/(:any)', 'Home::index');

