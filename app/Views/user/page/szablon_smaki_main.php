<?php
/* 
RzeszowskieSmaki - strona główna
*/

?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header_flavor.php') ? 'user/m_header_flavor' : 'user/header_flavor'); ?>


<div id="flavors">
<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 17, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
<div id="flavors_main">
<div class="container">
<div id="column">
   <div class="left-sidebar">
      <?= view_cell('\Modules\Flavors\Libraries\Flavors::FlavorMenu',['id_lang'=>$id_lang,'active'=>'']); ?>
	  <?= view_cell('\Modules\Flavors\Libraries\Flavors::CuisineMenu',['id_lang'=>$id_lang,'active'=>!empty($data['cuisine']['id']) ? $data['cuisine']['id'] : '']); ?>
	  <?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 20, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
   </div>
   <div class="center-column">
	 <?php
	   if(empty($content['cont_39']['data'])) {$newest_news=array();} else {$newest_news=$content['cont_39']['data'];}
	   echo view_cell('\Modules\Flavors\Libraries\Flavors::NewsBox',['id_lang'=>$id_lang,'id_page_cont'=>38,'news'=>$newest_news]); 
	 ?>
	 
	<div class="image_home_box">
	<h2>NA CO MASZ DZIŚ OCHOTĘ?</h2>
	<div class="list">
	   <div class="box">
	     <div class="inside">
	     <figure>
		   <a href="/rzeszowskie-smaki/lokal/pizzerie"><img src="/assets/gfx/home_pizza.jpg" alt="Pizza" class="img-responsive" /></a>
		   <h3><a href="/rzeszowskie-smaki/lokal/pizzerie">Pizza</a></h3>
		 </figure>
		 </div>
	   </div>
	    <div class="box">
		<div class="inside">
	     <figure>
		   <a href="/rzeszowskie-smaki/lokal/burgerownie"><img src="/assets/gfx/home_burgery.jpg" alt="Burgery" class="img-responsive" /></a>
		   <h3><a href="/rzeszowskie-smaki/lokal/burgerownie">Burgery</a></h3>
		 </figure>
		 </div>
	   </div>
	 <div class="box">
		<div class="inside">
	     <figure>
		   <a href="/rzeszowskie-smaki/lokal/lodziarnie"><img src="/assets/gfx/home_lody.jpg" alt="Lody" class="img-responsive" /></a>
		   <h3><a href="/rzeszowskie-smaki/lokal/lodziarnie">Lody</a></h3>
		 </figure>
	   </div>
	   </div>
	   <div class="box">
		<div class="inside">
	     <figure>
		   <a href="/rzeszowskie-smaki/lokal/kawiarnie"><img src="/assets/gfx/home_kawiarnie.jpg" alt="Kawa i ciasto" class="img-responsive" /></a>
		   <h3><a href="/rzeszowskie-smaki/lokal/kawiarnie">Kawa i ciasto</a></h3>
		 </figure>
	   </div>
	   </div>
	   <div class="box">
		<div class="inside">
	     <figure>
		   <a href="/rzeszowskie-smaki/lokal/restauracje"><img src="/assets/gfx/home_danie.jpg" alt="Danie główne" class="img-responsive" /></a>
		   <h3><a href="/rzeszowskie-smaki/lokal/restauracje">Danie główne</a></h3>
		 </figure>
	   </div>
	   </div>
	   <div class="box">
		<div class="inside">
	     <figure>
		   <a href="/rzeszowskie-smaki/lokal/kebab"><img src="/assets/gfx/home_kebab.jpg" alt="Kebab" class="img-responsive" /></a>
		   <h3><a href="/rzeszowskie-smaki/lokal/kebab">Kebab</a></h3>
		 </figure>
	   </div>
	   </div>
	   
	</div>
	</div>
	 
	 
     <?php if(!empty($content['cont_43'])): ?>
      <?= view($content['cont_43']['template'], array('id_cont' => $content['cont_43']['id'], 'title' => $content['cont_43']['title'], 'subtitle' => $content['cont_43']['subtitle'], 'data' => $content['cont_43']['data'])); ?>
	 <?php endif;?>
     <?php if(!empty($content['cont_42'])): ?>
      <?= view($content['cont_42']['template'], array('id_cont' => $content['cont_42']['id'], 'title' => $content['cont_42']['title'], 'subtitle' => $content['cont_42']['subtitle'], 'data' => $content['cont_42']['data'])); ?>
	 <?php endif;?>
     <?php if(!empty($content['cont_41'])): ?>
      <?= view($content['cont_41']['template'], array('id_cont' => $content['cont_41']['id'], 'title' => $content['cont_41']['title'], 'subtitle' => $content['cont_41']['subtitle'], 'data' => $content['cont_41']['data'])); ?>
	 <?php endif;?>
	 
	 
	 
	<div id="restaurants_map" class="home-map">
	    <div class="left">
		  <?= view_cell('\Modules\Flavors\Libraries\Flavors::RestaurantsMap',['id_lang'=>$id_lang]); ?>
		</div>  
	    <div class="right">
		<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 7, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
		</div>  
	</div> 
	<div class="home-news-list"> 
	 <?php if(!empty($content['cont_39'])): ?>
      <?= view($content['cont_39']['template'], array('id_cont' => $content['cont_39']['id'], 'title' => $content['cont_39']['title'], 'subtitle' => $content['cont_39']['subtitle'], 'data' => $content['cont_39']['data'])); ?>
	 <?php endif;?>
	 <div class="rating_column">
	 
	 <?php if(!empty($content['cont_111'])): ?>
      <?= view($content['cont_111']['template'], array('id_cont' => $content['cont_111']['id'], 'title' => $content['cont_111']['title'], 'subtitle' => $content['cont_111']['subtitle'], 'data' => $content['cont_111']['data'])); ?>
	 <?php endif;?>
	 
	 
	 
	 
	  <?= view_cell('\Modules\Flavors\Libraries\Flavors::HomeRating',['id_lang'=>$id_lang,'show'=>8]); ?>
	  </div>
	</div> 
   </div>
</div>
</div>
</div>
</div>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer_flavor' : 'user/footer_flavor'); ?>