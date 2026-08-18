<?php
/* 
BigBox Duży/2 małe
*/


$config=array();
if(!empty($content['cont_'.$id_cont]['config'])) {$config=$content['cont_'.$id_cont]['config'];}
?>
 <?= view_cell('\Modules\News\Libraries\News::getNewsBigBox', ['id_lang' => $id_lang, 'locale' => $locale,'id_page'=>9,'template' => $mobile ? 'm_news-box' : 'news-box-big_small','config'=>$config]) ?>