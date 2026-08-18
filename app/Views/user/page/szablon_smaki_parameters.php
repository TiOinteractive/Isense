<?php
/* 
RzeszowskieSmaki - podstrona z parametrami
*/

if(!empty($data['page_content']['cuisine']['parameters']) and !empty($data['page_content']['cuisine'])) {
	$data['page_content']['category']['parameters']=$data['page_content']['cuisine']['parameters'];	
}
if(!empty($data['page_content']['cuisine']['filters'])) {
	$data['page_content']['category']['filters']=$data['page_content']['cuisine']['filters'];
}
?>
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header_flavor.php') ? 'user/m_header_flavor' : 'user/header_flavor'); ?>

<div id="flavors">
<?php
    if(isset($breadcrumbs)) {
        echo view('user/breadcrumbs_flavor',array('bread'=>$breadcrumbs));
    }
?>
<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 17, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
<div id="flavors_page">
<div class="container">
<div id="column">
   <div class="left-sidebar left-parameters">
      <?= view_cell('\Modules\Flavors\Libraries\Flavors::FlavorMenu',['id_lang'=>$id_lang,'active'=>!empty($data['page_content']['category']['id']) ? $data['page_content']['category']['id'] : '']); ?>
	  <?= view_cell('\Modules\Flavors\Libraries\Flavors::CuisineMenu',['id_lang'=>$id_lang,'active'=>!empty($data['page_content']['cuisine']['id']) ? $data['page_content']['cuisine']['id'] : '']); ?>
	  <?php if(!empty($data['page_content']['category']['parameters'])):?>
	    <div id="leftParameters" class="filters">
	      <form method="get"  id="filters" />
		  <div class="parameters">
		    <?php foreach($data['page_content']['category']['parameters'] as $parameter):?>
              <?php if($parameter['value_count']>0):?>		       
			   <div class="parameter">
			     <h4><?=$parameter['filter_name'];?> <i class="fa-solid fa-chevron-right"></i></h4>
					<ul class="trans400">
				       <?php foreach($parameter['value_list'] as $val): ?>
					     <?php if($val['count']>0):?>
							 <li>
							   <input <?php if(isset($val['count_filter']) and $val['count_filter']<1):?> disabled="disabled" <?php endif;?>type="checkbox" name="f[<?=$parameter['id'];?>][<?=$val['id'];?>]" value="<?=$val['id'];?>" id="p_<?=$parameter['id'];?>_<?=$val['id'];?>" <?php if(empty($mobile)):?>onchange="$('#filters').submit();"<?php endif;?> <?php if(!empty($data['page_content']['category']['filters']['f'][$parameter['id']][$val['id']])):?> checked="checked"<?php endif;?>/> 
							   <label for="p_<?=$parameter['id'];?>_<?=$val['id'];?>"><?=$val['value'];?> <span>(<?php if(isset($val['count_filter'])):?><?=$val['count_filter'];?><?php else:?><?=$val['count'];?><?php endif;?>)</span></label>
							 </li>
						 <?php endif;?>
					   <?php endforeach;?>
					</ul>
			   </div>
			   <?php endif;?>
		    <?php endforeach;?>
	     </div>	
				<input type="hidden" name="show" value="<?php if(!empty($data['page_content']['category']['filters']['show'])):?><?=$data['page_content']['category']['filters']['show'];?><?php endif;?>" />
				<input type="hidden" name="letter" value="<?php if(!empty($data['page_content']['category']['filters']['letter'])):?><?=$data['page_content']['category']['filters']['letter'];?><?php endif;?>" />
				<input type="hidden" name="sort" value="<?php if(!empty($data['page_content']['category']['filters']['sort'])):?><?=$data['page_content']['category']['filters']['sort'];?><?php endif;?>" />
				<input type="hidden" name="t" value="<?php if(!empty($data['page_content']['category']['filters']['t'])):?><?=$data['page_content']['category']['filters']['t'];?><?php else:?>desc<?php endif;?>" />
				<input type="hidden" name="view" value="<?php if(!empty($data['page_content']['category']['filters']['view'])):?><?=$data['page_content']['category']['filters']['view'];?><?php else:?>1<?php endif;?>" />
				
				<?php if(!empty($mobile)):?>
				  <div class="options-mobile">
				     <input type="reset" value="Wyczyść" onclick="clearAllParameters();">
					 <input type="submit" name="send" value="Pokaż wyniki" />
				  </div>
				<?php endif;?>
		  </form>
	    </div>
	  <?php endif;?>
   </div>
   <div class="center-column">
        <?php if(!empty($data['template'])) { 
			echo view($data['template'],['id_lang'=>$id_lang,'data'=>$data]);
		}
		?>
   </div>
</div>
</div>
</div>
</div>
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer_flavor' : 'user/footer_flavor'); ?>