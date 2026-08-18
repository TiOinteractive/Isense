<?php
/* 
BigBox BigNews
*/

$config=array();
if(!empty($content['cont_'.$id_cont]['config'])) {$config=$content['cont_'.$id_cont]['config'];}
?>
 <?= view_cell('\Modules\News\Libraries\News::getNewsBigBox', ['id_lang' => $id_lang, 'locale' => $locale,'id_page'=>9,'template' => 'news-box-bignews','config'=>$config]) ?>