<div id="cuisine-single"<?php if(!empty($data['page_content']['cuisine']['filters']['view']) and $data['page_content']['cuisine']['filters']['view']==2):?> class="view2"<?php endif;?>>
  <header>
    <h1><?=lang('Flavors.Cuisine');?>: <?=$data['page_content']['cuisine']['name'];?></h1>
	<?php if(!empty($data['page_content']['cuisine']['id'])):?>
	  <div class="show_on_map"><a href="/rzeszowskie-smaki/mapa-lokali?cuisine=<?=$data['page_content']['cuisine']['id'];?>"><svg viewBox="0 0 24 24" height="22"><path d="M12 2C7.589 2 4 5.589 4 9.995 3.971 16.44 11.696 21.784 12 22c0 0 8.029-5.56 8-12 0-4.411-3.589-8-8-8zm0 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"/></svg> zobacz na mapie <i class="fa-solid fa-angle-right"></i></a></div>
	<?php endif;?>
  </header>
  <?php if(!empty($data['page_content']['cuisine']['description'])): ?>
   <div class="description">
     <?=$data['page_content']['cuisine']['description'];?>
   </div>
  <?php endif;?>
  <div class="sort sort_select">
		
	 <?php if(empty($mobile)):?> 	
		<div class="view">
		  <div class="view_1<?php if((!empty($data['page_content']['cuisine']['filters']['view']) and $data['page_content']['cuisine']['filters']['view']==1) or empty($data['page_content']['cuisine']['filters']['view'])):?> active<?php endif;?>"><svg viewBox="0 0 48 48"><path d="M8 22h10v-12h-10v12zm0 14h10v-12h-10v12zm12 0h10v-12h-10v12zm12 0h10v-12h-10v12zm-12-14h10v-12h-10v12zm12-12v12h10v-12h-10z"/><path d="M0 0h48v48h-48z" fill="none"/></svg></div>
		  <div class="view_2<?php if(!empty($data['page_content']['cuisine']['filters']['view']) and $data['page_content']['cuisine']['filters']['view']==2):?> active<?php endif;?>"><svg viewBox="0 0 48 48"><path d="M8 28h8v-8h-8v8zm0 10h8v-8h-8v8zm0-20h8v-8h-8v8zm10 10h24v-8h-24v8zm0 10h24v-8h-24v8zm0-28v8h24v-8h-24z"/><path d="M0 0h48v48h-48z" fill="none"/></svg></div>
		</div>
        <div class="select">
		 <div>
		 <?=lang('Flavors.ShowOnPage');?>: 
		  <select name="show" class="trans400">
		      <option value="36" <?php if(!empty($data['page_content']['cuisine']['filters']['show']) and $data['page_content']['cuisine']['filters']['show']==36):?> selected="selected"<?php endif;?>>36 <?=lang('Flavors.SLokale');?></option>
			  <option value="48" <?php if(!empty($data['page_content']['cuisine']['filters']['show']) and $data['page_content']['cuisine']['filters']['show']==48):?> selected="selected"<?php endif;?>>48 <?=lang('Flavors.SLokali');?></option>
			  <option value="64" <?php if(!empty($data['page_content']['cuisine']['filters']['show']) and $data['page_content']['cuisine']['filters']['show']==64):?> selected="selected"<?php endif;?>>64 <?=lang('Flavors.SLokali');?></option>
			  <option value="120" <?php if(!empty($data['page_content']['cuisine']['filters']['show']) and $data['page_content']['cuisine']['filters']['show']==120):?> selected="selected"<?php endif;?>>120 <?=lang('Flavors.SLokali');?></option>
		  </select>
		</div>
		<div><?=lang('Flavors.Sort');?>: 
		  <select name="sort" class="trans400">
		      <option value="0" <?php if(empty($data['page_content']['cuisine']['filters']['sort'])):?> selected="selected"<?php endif;?>>-<?=lang('Flavors.choose');?>-</option>
		      <option value="1" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==1):?> selected="selected"<?php endif;?>><?=lang('Flavors.Newest');?></option>
			  <option value="2" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==2):?> selected="selected"<?php endif;?>><?=lang('Flavors.Alphabetical');?></option>
			  <option value="3" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==3):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortRating');?></option>
			  <option value="4" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==4):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortNumberComments');?></option>
			  <option value="5" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==5):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortViews');?></option>
			  <option value="6" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==6):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortNumberRatings');?></option>
			  <option value="7" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==7):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortBestFood');?></option>
			  <option value="8" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==8):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortBestDecor');?></option>
			  <option value="9" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==9):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortBestService');?></option>
			  <option value="10" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==10):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortBestPrice');?></option>
		  </select>
		</div>
		<div>
		  <select name="t" class="trans400">
		      <option value="asc" <?php if(!empty($data['page_content']['cuisine']['filters']['t']) and $data['page_content']['cuisine']['filters']['t']=='asc'):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortAscending');?></option>
			  <option value="desc" <?php if((!empty($data['page_content']['cuisine']['filters']['t']) and $data['page_content']['cuisine']['filters']['t']=='desc') or empty($data['page_content']['cuisine']['filters']['t'])):?> selected="selected"<?php endif;?>><?=lang('Flavors.SortDescending');?></option>
		  </select>
		</div>
		</div>
		
		<?php else: ?>	
		<div class="mobile-sort">
		   <div class="box">
		     <h4>Po dacie dodania</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="1" id="s_1_asc" onclick="FlavorSortMobile(1,'desc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==1 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>> 
					<label for="s_1_asc">Od najnowszych</label>
				</li>
				<li>
					<input type="radio" name="sort" value="1" id="s_1_desc" onclick="FlavorSortMobile(1,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==1 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_1_desc">Od najstarszych</label>
				</li>
			</ul>			 
		   </div>
		 <div class="box">
		     <h4>Po nazwie</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="2" id="s_2_asc" onclick="FlavorSortMobile(2,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==2 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_2_asc">Od "A" do "Z"</label>
				</li>
				<li>
					<input type="radio" name="sort" value="2" id="s_2_desc" onclick="FlavorSortMobile(2,'desc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==2 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>> 
					<label for="s_2_desc">Od "Z" do "A"</label>
				</li>
			</ul>			 
		   </div>
		   <div class="box">
		     <h4>Najlepiej oceniane</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="3" id="s_3_asc" onclick="FlavorSortMobile(3,'desc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==3 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>> 
					<label for="s_3_asc">Od najwyżej ocenianych</label>
				</li>
				<li>
					<input type="radio" name="sort" value="3" id="s_3_desc" onclick="FlavorSortMobile(3,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==3 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_3_desc">Od najniżej ocenianych</label>
				</li>
			</ul>			 
		   </div> 
		     <div class="box">
		     <h4>Po ilości komentarzy</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="3" id="s_4_asc" onclick="FlavorSortMobile(4,'desc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==4 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>> 
					<label for="s_4_asc">Od najwięcej komentowanych</label>
				</li>
				<li>
					<input type="radio" name="sort" value="3" id="s_4_desc" onclick="FlavorSortMobile(4,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==4 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_4_desc">Od najmniej komentowanych</label>
				</li>
			</ul>			 
		   </div> 
		   <div class="box">
		     <h4>Po ilości wyświetleń</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="5" id="s_5_asc" onclick="FlavorSortMobile(5,'desc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==5 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>> 
					<label for="s_5_asc">Od najwięcej oglądanych</label>
				</li>
				<li>
					<input type="radio" name="sort" value="5" id="s_5_desc" onclick="FlavorSortMobile(5,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==5 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_5_desc">Od najmniej oglądanych</label>
				</li>
			</ul>			 
		   </div> 
		   <div class="box">
		     <h4>Po ilości ocen</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="6" id="s_6_asc" onclick="FlavorSortMobile(6,'desc'); <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==6 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>"> 
					<label for="s_6_asc">Od najczęśćiej ocenianych</label>
				</li>
				<li>
					<input type="radio" name="sort" value="6" id="s_6_desc" onclick="FlavorSortMobile(6,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==6 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_6_desc">Od najrzadziej ocenianych</label>
				</li>
			</ul>			 
		   </div> 
		      <div class="box">
		     <h4>Po ocenie jedzenia</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="7" id="s_7_asc" onclick="FlavorSortMobile(7,'desc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==7 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>> 
					<label for="s_7_asc">Od najwyżscyh ocen</label>
				</li>
				<li>
					<input type="radio" name="sort" value="7" id="s_7_desc" onclick="FlavorSortMobile(7,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==7 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_7_desc">Od najniższych ocen</label>
				</li>
			</ul>			 
		   </div> 
		      <div class="box">
		     <h4>Po ocenie wystroju</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="8" id="s_8_asc" onclick="FlavorSortMobile(8,'desc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==8 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>> 
					<label for="s_8_asc">Od najwyżscyh ocen</label>
				</li>
				<li>
					<input type="radio" name="sort" value="8" id="s_8_desc" onclick="FlavorSortMobile(8,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==8 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_8_desc">Od najniższych ocen</label>
				</li>
			</ul>			 
		   </div> 
		     <div class="box">
		     <h4>Po ocenie obługi</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="9" id="s_9_asc" onclick="FlavorSortMobile(9,'desc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==9 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>> 
					<label for="s_9_asc">Od najwyżscyh ocen</label>
				</li>
				<li>
					<input type="radio" name="sort" value="9" id="s_9_desc" onclick="FlavorSortMobile(9,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==9 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_9_desc">Od najniższych ocen</label>
				</li>
			</ul>			 
		   </div> 
		    <div class="box">
		     <h4>Po ocenie poziomu cen</h4>
			 <ul class="trans400">
				<li>
					<input type="radio" name="sort" value="10" id="s_10_asc" onclick="FlavorSortMobile(10,'desc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==10 and $data['page_content']['cuisine']['filters']['t']=='desc'):?>checked="checked"<?php endif;?>> 
					<label for="s_10_asc">Od najwyżscyh ocen</label>
				</li>
				<li>
					<input type="radio" name="sort" value="10" id="s_10_desc" onclick="FlavorSortMobile(10,'asc');" <?php if(!empty($data['page_content']['cuisine']['filters']['sort']) and $data['page_content']['cuisine']['filters']['sort']==10 and $data['page_content']['cuisine']['filters']['t']=='asc'):?>checked="checked"<?php endif;?>> 
					<label for="s_10_desc">Od najniższych ocen</label>
				</li>
			</ul>			 
		   </div>
		</div>
		<div class="view"> <span>Wybierz widok:</span>
		  <div class="view_1<?php if((!empty($data['page_content']['cuisine']['filters']['view']) and $data['page_content']['cuisine']['filters']['view']==1) or empty($data['page_content']['cuisine']['filters']['view'])):?> active<?php endif;?>"><svg viewBox="0 0 48 48"><path d="M8 22h10v-12h-10v12zm0 14h10v-12h-10v12zm12 0h10v-12h-10v12zm12 0h10v-12h-10v12zm-12-14h10v-12h-10v12zm12-12v12h10v-12h-10z"/><path d="M0 0h48v48h-48z" fill="none"/></svg></div>
		  <div class="view_2<?php if(!empty($data['page_content']['cuisine']['filters']['view']) and $data['page_content']['cuisine']['filters']['view']==2):?> active<?php endif;?>"><svg viewBox="0 0 48 48"><path d="M8 28h8v-8h-8v8zm0 10h8v-8h-8v8zm0-20h8v-8h-8v8zm10 10h24v-8h-24v8zm0 10h24v-8h-24v8zm0-28v8h24v-8h-24z"/><path d="M0 0h48v48h-48z" fill="none"/></svg></div>
		</div>
		<div class="mobile_btn">
		   <div class="show" onclick="ShowMobileFParameters();">Wybierz parametry</div>
		   <div class="show" onclick="ShowMobileFSort();">Sortuj wyniki</div>
		</div>
	<?php endif;?>	
		
		
	 <?php if(!empty($data['pager'])): ?>
			  <?=$data['pager'];?> 
	 <?php endif; ?>
 </div>
   <div class="filters">
    <form method="get"  id="filters" />
	     <input type="hidden" name="show" value="<?php if(!empty($data['page_content']['cuisine']['filters']['show'])):?><?=$data['page_content']['cuisine']['filters']['show'];?><?php endif;?>" />
		 <input type="hidden" name="letter" value="<?php if(!empty($data['page_content']['cuisine']['filters']['letter'])):?><?=$data['page_content']['cuisine']['filters']['letter'];?><?php endif;?>" />
		 <input type="hidden" name="sort" value="<?php if(!empty($data['page_content']['cuisine']['filters']['sort'])):?><?=$data['page_content']['cuisine']['filters']['sort'];?><?php endif;?>" />
		 <input type="hidden" name="t" value="<?php if(!empty($data['page_content']['cuisine']['filters']['t'])):?><?=$data['page_content']['cuisine']['filters']['t'];?><?php else:?>desc<?php endif;?>" />
		 	 <input type="hidden" name="view" value="<?php if(!empty($data['page_content']['cuisine']['filters']['view'])):?><?=$data['page_content']['cuisine']['filters']['view'];?><?php else:?>1<?php endif;?>" />
    </form>
		<?php if(!empty($data['page_content']['cuisine']['letters'])):?>
	  <div class="letters" id="filter_letters">
	  <label>Lokale alfabetycznie:</label>
	    <ul>
		  <?php foreach($data['page_content']['cuisine']['letters'] as $letter=>$count):?>
		    <li<?php if(!empty($data['page_content']['cuisine']['filters']['letter']) and $data['page_content']['cuisine']['filters']['letter']==$letter):?> class="active"<?php endif;?>><?php if($count>0):?><a href="#" onclick="filterLetter('<?=$letter;?>');return false;"><?=$letter;?></a><?php else:?><span><?=$letter;?></span><?php endif;?></li>
		  <?php endforeach;?>
		</ul>
	  </div>
	<?php endif;?>
  </div>
    <?php if(!empty($data['page_content']['cuisine']['filters']['letter']) or !empty($data['page_content']['cuisine']['choosen_parameters'])):?>
  <div class="choosen_filters">
    <div class="title"><h4><?=lang('Flavors.YourChoose');?>:</h4></div>
     <?php if(!empty($data['page_content']['cuisine']['choosen_parameters'])):?>
	   <?php foreach($data['page_content']['cuisine']['choosen_parameters'] as $ch):?>
	       	 <div><?=$ch['param_name'];?>: <?=$ch['name'];?> <a href="#" onclick="clearParameter(<?=$ch['param_id'];?>,<?=$ch['id'];?>);return false;"><i class="fa-solid fa-xmark"></i></a></div>
	   <?php endforeach;?>
     <?php endif;?>
	 <?php if(!empty($data['page_content']['cuisine']['filters']['letter'])):?>
	 <div><?=lang('Flavors.Letter');?>: <?=$data['page_content']['cuisine']['filters']['letter'];?> <a href="#" onclick="clearLetter();return false;"><i class="fa-solid fa-xmark"></i></a></div>
	 <?php endif;?>
	 <div><?=lang('Flavors.ClearParameters');?> <a href="#" onclick="clearAllParameters();return false;"><i class="fa-solid fa-xmark"></i></a></div>
  </div>
  <?php endif;?>

  <?php if(!empty($data['page_content']['cuisine']['restaurants'])):?>
  
     <?= view('\Modules\Flavors\Views/user/restaurants/restaurant_list', array('restaurants'=>$data['page_content']['cuisine']['restaurants'])); ?>
  
  <?php else:?>
  

  <?php endif;?>
 <?php if(!empty($data['pager'])): ?>
  <div class="sort">
      <?=$data['pager'];?> 
  </div>				
 <?php endif; ?>
  
</div>




