<?php
/* 
Lista wszystkich restauracji
*/
?>
<div id="cuisine-single"<?php if(!empty($data['list']['filters']['view']) and $data['list']['filters']['view']==2):?> class="view2"<?php endif;?>>
  <header>
    <h1><?=$title;?></h1>
	  <div class="show_on_map"><a href="/rzeszowskie-smaki/mapa-lokali"><svg viewBox="0 0 24 24" height="22"><path d="M12 2C7.589 2 4 5.589 4 9.995 3.971 16.44 11.696 21.784 12 22c0 0 8.029-5.56 8-12 0-4.411-3.589-8-8-8zm0 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"/></svg> zobacz na mapie <i class="fa-solid fa-angle-right"></i></a></div>
  </header>

  <div class="sort sort_select">
        <div class="select">
		 <div>
		 <?=lang('Flavors.ShowOnPage');?>: 
		  <select name="show" class="trans400">
		      <option value="24" <?php if(!empty($data['list']['filters']['show']) and $data['list']['filters']['show']==24):?> selected="selected"<?php endif;?>>36</option>
			  <option value="48" <?php if(!empty($data['list']['filters']['show']) and $data['list']['filters']['show']==48):?> selected="selected"<?php endif;?>>48</option>
			  <option value="64" <?php if(!empty($data['list']['filters']['show']) and $data['list']['filters']['show']==64):?> selected="selected"<?php endif;?>>64</option>
			  <option value="120" <?php if(!empty($data['list']['filters']['show']) and $data['list']['filters']['show']==120):?> selected="selected"<?php endif;?>>120</option>
		  </select>
		</div>
		<div><?=lang('Flavors.Sort');?>: 
		  <select name="sort" class="trans400">
		      <option value="0" <?php if(empty($data['list']['filters']['sort'])):?> selected="selected"<?php endif;?>>-<?=lang('Flavors.choose');?>-</option>
		      <option value="1" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==1):?> selected="selected"<?php endif;?>><?=lang('Flavors.Newest');?></option>
			  <option value="2" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==2):?> selected="selected"<?php endif;?>><?=lang('Flavors.Alphabetical');?></option>
			  <option value="3" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==3):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortRating');?></option>
			  <option value="4" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==4):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortNumberComments');?></option>
			  <option value="5" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==5):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortViews');?></option>
			  <option value="6" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==6):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortNumberRatings');?></option>
			  <option value="7" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==7):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortBestFood');?></option>
			  <option value="8" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==8):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortBestDecor');?></option>
			  <option value="9" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==9):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortBestService');?></option>
			  <option value="10" <?php if(!empty($data['list']['filters']['sort']) and $data['list']['filters']['sort']==10):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortBestPrice');?></option>
		  </select>
		</div>
		<div>
		  <select name="t" class="trans400">
		      <option value="asc" <?php if(!empty($data['list']['filters']['t']) and $data['list']['filters']['t']=='asc'):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortAscending');?></option>
			  <option value="desc" <?php if((!empty($data['list']['filters']['t']) and $data['list']['filters']['t']=='desc') or empty($data['list']['filters']['t'])):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortDescending');?></option>
		  </select>
		</div>
		</div>
		<div class="view">
		  <div class="view_1<?php if((!empty($data['list']['filters']['view']) and $data['list']['filters']['view']==1) or empty($data['list']['filters']['view'])):?> active<?php endif;?>"><svg viewBox="0 0 48 48"><path d="M8 22h10v-12h-10v12zm0 14h10v-12h-10v12zm12 0h10v-12h-10v12zm12 0h10v-12h-10v12zm-12-14h10v-12h-10v12zm12-12v12h10v-12h-10z"/><path d="M0 0h48v48h-48z" fill="none"/></svg></div>
		  <div class="view_2<?php if(!empty($data['list']['filters']['view']) and $data['list']['filters']['view']==2):?> active<?php endif;?>"><svg viewBox="0 0 48 48"><path d="M8 28h8v-8h-8v8zm0 10h8v-8h-8v8zm0-20h8v-8h-8v8zm10 10h24v-8h-24v8zm0 10h24v-8h-24v8zm0-28v8h24v-8h-24z"/><path d="M0 0h48v48h-48z" fill="none"/></svg></div>
		</div>
	 <?php if(!empty($data['pager'])): ?>
			  <?=$data['pager'];?> 
	 <?php endif; ?>
 </div>  
 
     <div class="filters">
    <form method="get"  id="filters" />
	     <input type="hidden" name="show" value="<?php if(!empty($data['list']['filters']['show'])):?><?=$data['list']['filters']['show'];?><?php endif;?>" />
		 <input type="hidden" name="letter" value="<?php if(!empty($data['list']['filters']['letter'])):?><?=$data['list']['filters']['letter'];?><?php endif;?>" />
		 <input type="hidden" name="sort" value="<?php if(!empty($data['list']['filters']['sort'])):?><?=$data['list']['filters']['sort'];?><?php endif;?>" />
		 <input type="hidden" name="t" value="<?php if(!empty($data['list']['filters']['t'])):?><?=$data['list']['filters']['t'];?><?php else:?>desc<?php endif;?>" />
		 	 <input type="hidden" name="view" value="<?php if(!empty($data['list']['filters']['view'])):?><?=$data['list']['filters']['view'];?><?php else:?>1<?php endif;?>" />
    </form>
	<?php if(!empty($data['list']['letters'])):?>
	  <div class="letters" id="filter_letters">
	  <label>Lokale alfabetycznie:</label>
	    <ul>
		  <?php foreach($data['list']['letters'] as $letter=>$count):?>
		    <li<?php if(!empty($data['list']['filters']['letter']) and $data['list']['filters']['letter']==$letter):?> class="active"<?php endif;?>><?php if($count>0):?><a href="#" onclick="filterLetter('<?=$letter;?>');return false;"><?=$letter;?></a><?php else:?><span><?=$letter;?></span><?php endif;?></li>
		  <?php endforeach;?>
		</ul>
	  </div>
	<?php endif;?>
  </div>
   <?php if(!empty($data['list']['filters']['letter'])):?>
  <div class="choosen_filters">
    <div class="title"><h4><?=lang('Flavors.YourChoose');?>:</h4></div>
    
	 <?php if(!empty($data['list']['filters']['letter'])):?>
	 <div><?=lang('Flavors.Letter');?>: <?=$data['list']['filters']['letter'];?> <a href="#" onclick="clearLetter();return false;"><i class="fa-solid fa-xmark"></i></a></div>
	 <?php endif;?>
	 <div><?=lang('Flavors.ClearParameters');?> <a href="#" onclick="clearAllParameters();return false;"><i class="fa-solid fa-xmark"></i></a></div>
  </div>
  <?php endif;?>
  
    <?php if(!empty($data['list']['restaurants'])):?>
		<?= view('\Modules\Flavors\Views/user/restaurants/restaurant_list', array('restaurants'=>$data['list']['restaurants'])); ?>
  <?php else:?>
  
    <?php endif;?>
  
  
<?php if(!empty($data['pager'])): ?>
  <div class="sort">
      <?=$data['pager'];?> 
  </div>				
<?php endif; ?>
</div>  