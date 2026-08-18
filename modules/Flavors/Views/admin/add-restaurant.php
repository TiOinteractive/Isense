<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($restaurant) && !empty($restaurant['id'])): ?>
                <?=$restaurant['name']; ?>
                <span><?=lang('Flavors.RestaurantEdit'); ?></span>
            <?php else: ?>
                <?=lang('Flavors.AddRestaurant'); ?>
            <?php endif; ?>
        </div>
		<?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
		 <form class="form restaurant-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/<?php echo $action; ?><?=!empty($restaurant['id']) ? '/' . $restaurant['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Flavors.BasicInformation'); ?></h3>
            </div>
			<div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab<?=$l==0 ? ' active' : ''; ?>"><span class="name"><?=$lang['name']; ?></span><span class="short-name"><?=$lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
			      <div class="tabs-content">
                <?php endif; ?>
			    <?php $l=0; foreach($languages as $lang): ?>
			
			       <div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantName');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['name']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.DirectLink');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-direct-links" type="hidden" value="<?=$direct_links[$lang['id']];?>">
                                    <input class="link-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_link]" value="<?=!empty($restaurant['lang']) ? $restaurant['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                    <input class="link-id-lang" type="hidden" value="<?=$lang['id']; ?>" />
                                    <input class="link-field" type="text" name="lang[<?=$lang['id']; ?>][link]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['link']) : ''; ?>" readonly="readonly" />
                                </div>
                            </div>
							 <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantAdditionalName');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][name2]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['name2']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantEmail');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][email]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['email']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantWWW');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][www]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['www']) : ''; ?>" />
                                </div>
                            </div>
								<div class="form-row">
                                <div class="form-label">
                                    <label>Media społecznościowe <br />(facebook, instagram, twitter)</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][social_link]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['social_link']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantStreet');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][address]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['address']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantCity');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][city]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['city']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantPhone');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][phone]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['phone']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantReservation');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][reservation]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['reservation']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantWorkingHours');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="lang[<?=$lang['id']; ?>][working_hours]"><?=!empty($restaurant['lang']) ? esc(str_replace("<br />", "", $restaurant['lang'][$lang['id']]['working_hours'])) : ''; ?></textarea>
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantChef');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][chef]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['chef']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantOpeningYear');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][opening_year]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['opening_year']) : date("Y"); ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantTableNumbers');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][table_numbers]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['table_numbers']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantSpeciality');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][speciality]" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['speciality']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantTags');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][tags]" class="tags_input" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/restaurantsearchtags" value="<?=!empty($restaurant['lang']) ? esc($restaurant['lang'][$lang['id']]['tags']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantIntroduction');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][introduction]"><?=!empty($restaurant['lang']) ? $restaurant['lang'][$lang['id']]['introduction'] : ''; ?></textarea>
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][description]"><?=!empty($restaurant['lang']) ? $restaurant['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantDelivery');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][delivery]"><?=!empty($restaurant['lang']) ? $restaurant['lang'][$lang['id']]['delivery'] : ''; ?></textarea>
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.RestaurantMenu');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][menu]"><?=!empty($restaurant['lang']) ? $restaurant['lang'][$lang['id']]['menu'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
			 <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.RestaurantCategory');?></label>
                </div>
                <div class="form-field">
                    <select name="id_category" class="link-category-id">
                        <option value="0">(<?=lang('Foto.NoSelectedCategory'); ?>)</option>
                        <?php if(!empty($pages)): ?>
                            <?php foreach($pages as $k=>$p): ?>
                                <?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($restaurant['id_category']) ? $restaurant['id_category'] : 0, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.RestaurantAdditionalCategory');?></label>
                </div>
                <div class="form-field additional-categories">
				   <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/add-additional-categories" class="btn small add-additional-categories" data-title="<?=lang('Flavors.RestaurantAddAdditionalCategory');?>" data-btn-ok="<?=lang('Flavors.Save');?>" data-btn-cancel="<?=lang('Flavors.Cancel');?>"><?=lang('Flavors.RestaurantAddAdditionalCategory');?></a>
                   <div class="additional-list"><?php if(!empty($restaurant['additional_category'])):?><?php foreach($restaurant['additional_category'] as $kat):?><p><input type="hidden" name="categories[<?=$kat['id'];?>]" value="<?=$kat['id'];?>"><b> <?=$kat['name'];?></b></p><?php endforeach;?><?php endif;?></div>
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.RestaurantCuisineType');?></label>
                </div>
                <div class="form-field cuisine-type">
				   <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/add-cuisine" class="btn small add-restaurant-cuisine"  data-title="<?=lang('Flavors.RestaurantAddCuisineType');?>" data-btn-ok="<?=lang('Flavors.Save');?>" data-btn-cancel="<?=lang('Flavors.Cancel');?>"><?=lang('Flavors.RestaurantAddCuisineType');?></a>
                   <div class="additional-list"><?php if(!empty($restaurant['cuisine_type'])):?><?php foreach($restaurant['cuisine_type'] as $kat):?><p><input type="hidden" name="cuisine[<?=$kat['id'];?>]" value="<?=$kat['id'];?>"><b> <?=$kat['name'];?></b></p><?php endforeach;?><?php endif;?></div>
                </div>
            </div>
			 <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.AddRestaurantMenu');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($restaurant['menu'])): ?>
                            <?php foreach($restaurant['menu'] as $k=>$photo): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'menu', 'file' => $photo, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="menu" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
			
			
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.DishesType');?></label>
                </div>
                <div class="form-field">
                    <p style="margin-top:0px;"><input type="checkbox" id="type_1" name="dish_type[]" value="1" <?php if(!empty($restaurant['dish_type']) and in_array(1,$restaurant['dish_type'])):?> checked="checked"<?php endif;?> /> <label for="type_1" style="cursor:pointer;">Mięsne</label></p>
					<p style="margin-top:0px;"><input type="checkbox" id="type_2" name="dish_type[]" value="2" <?php if(!empty($restaurant['dish_type']) and in_array(2,$restaurant['dish_type'])):?> checked="checked"<?php endif;?> /> <label for="type_2" style="cursor:pointer;">Wegańskie</label></p>
					<p style="margin-top:0px;"><input type="checkbox" id="type_3" name="dish_type[]" value="3" <?php if(!empty($restaurant['dish_type']) and in_array(3,$restaurant['dish_type'])):?> checked="checked"<?php endif;?>  /> <label for="type_3" style="cursor:pointer;">Wegetariańskie</label></p>
                </div>
            </div>
			
			
			
            <div class="form-row nag">
                <h3><?=lang('News.Metatags'); ?></h3>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab<?=$l==0 ? ' active' : ''; ?>"><span class="name"><?=$lang['name']; ?></span><span class="short-name"><?=$lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                    <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.MetaTitle');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="meta[lang][<?=$lang['id']; ?>][title]" value="<?=!empty($restaurant['meta']['lang']) ? $restaurant['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.MetaDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][description]"><?=!empty($restaurant['meta']['lang']) ? $restaurant['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.MetaKeywords');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][keywords]"><?=!empty($restaurant['meta']['lang']) ? $restaurant['meta']['lang'][$lang['id']]['keywords'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row nag">
                <h3><?=lang('News.NewsMedia'); ?></h3>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.RestaurantLogo');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($restaurant['logo'])): ?>
                            <?=view('admin/filemenager/upload_file', array('field' => 'logo', 'file' => $restaurant['logo'], 'multi' => false)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="logo" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.PrimaryPhoto');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($restaurant['photo'])): ?>
                            <?=view('admin/filemenager/upload_file', array('field' => 'photo', 'file' => $restaurant['photo'], 'multi' => false,'crop'=>true)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photo" data-crop="true" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Photos');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($restaurant['photos'])): ?>
                            <?php foreach($restaurant['photos'] as $k=>$photo): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'photos', 'file' => $photo, 'multi' => true, 'no' => $k,'crop'=>false)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photos" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
			<div class="form-row nag">
                <h3><?=lang('Flavors.RestaurantParameters'); ?></h3>
            </div>
			<?php if(!empty($parameters)):?>
			   <div class="restaurant_parameters">
			        <?php foreach($parameters as $parameter): ?>
			            <div class="form-row">
							<div class="form-label">
								<label><?=$parameter['name'];?></label>
							</div>
							<div class="form-field">
							 <?php if(!empty($restaurant['parameters'][$parameter['id']])): ?>
                                <?php foreach($restaurant['parameters'][$parameter['id']] as $id_val=>$param):?>	
								<div class="flex param_<?=$parameter['id'];?>">
							      <div class="paramater_value">
								    <input type="text" name="parameter[<?=$parameter['id'];?>][]" value="<?=$param;?>" readonly="readonly" placeholder="<?=lang('Flavors.ParameterPlaceholderRestaurant');?>" />
								  </div>
								  <div class="paramater_select">
								   <select name="parameter_select[<?=$parameter['id'];?>][]" onchange="selectRestaurantParameter(this,<?=$parameter['id'];?>);">
								     <option value="0"><?=lang('Flavors.Choose');?></option>
								    <?php if(!empty($parameter['values'])):?>
									    <?php foreach($parameter['values'] as $value):?>
										   <option value="<?=$value['id'];?>" <?php if($value['id']==$id_val):?> selected="selected"<?php endif;?>><?=$value['value'];?></option> 
									    <?php endforeach; ?>
								    <?php endif;?>
									</select>
									<div><a class="btn" href="javascript:void(0);" title="<?=lang('Flavors.AddParameter');?>" onclick="addRestaurantParameter(this,<?=$parameter['id'];?>)"><i class="fa-solid fa-plus"></i></a></div>
									<div><a class="list-remove-btn" href="javascript:void(0);" onclick="removeRestaurantParameter(this,<?=$parameter['id'];?>)" title="<?=lang('Flavors.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a></div>
								  </div>
							   </div>
								<?php endforeach;?>							
							 <?php else: ?>   
							   <div class="flex param_<?=$parameter['id'];?>">
							      <div class="paramater_value">
								    <input type="text" name="parameter[<?=$parameter['id'];?>][]" value="" placeholder="<?=lang('Flavors.ParameterPlaceholderRestaurant');?>" />
								  </div>
								  <div class="paramater_select">
								   <select name="parameter_select[<?=$parameter['id'];?>][]" onchange="selectRestaurantParameter(this,<?=$parameter['id'];?>);">
								     <option value="0"><?=lang('Flavors.Choose');?></option>
								    <?php if(!empty($parameter['values'])):?>
									    <?php foreach($parameter['values'] as $value):?>
										   <option value="<?=$value['id'];?>"><?=$value['value'];?></option> 
									    <?php endforeach; ?>
								    <?php endif;?>
									</select>
									<div><a class="btn" href="javascript:void(0);" title="<?=lang('Flavors.AddParameter');?>" onclick="addRestaurantParameter(this,<?=$parameter['id'];?>)"><i class="fa-solid fa-plus"></i></a></div>
									<div><a class="list-remove-btn" href="javascript:void(0);" onclick="removeRestaurantParameter(this,<?=$parameter['id'];?>)" title="<?=lang('Flavors.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a></div>
								  </div>
							   </div>
							 <?php endif; ?>  
			                </div>
						</div>	
			        <?php endforeach;?>
			   </div>
			<?php else:?>
			  <div class="form-row-space"><p><?=lang('Flavors.EmptyParameters'); ?></p></div>
			<?php endif;?>
			<div class="form-row nag">
                <h3><?=lang('Flavors.AssignNews'); ?></h3>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.AssignNews'); ?></label>
                </div>
                <div class="form-field news">
				   <a href="/tiocms/flavors/add-news" class="btn small add-restaurant-news" data-title="<?=lang('Flavors.AssignNews'); ?>" data-btn-ok="<?=lang('Flavors.Save');?>" data-btn-cancel="<?=lang('Flavors.Cancel');?>"><?=lang('Flavors.AssignNews'); ?></a>
                   <div class="additional-news">
				      <div class="list">
						<div class="list-row list-head">
							<div class="list-col w50">Id</div>
							<div class="list-col">Tytuł</div>
							<div class="list-col w200">Data dodania</div>
							<div class="list-col center w100">Usuwanie</div>
						</div>
						<div class="news-box">
						<?php if(!empty($restaurant['news'])): ?>
                            <?=view('\Modules/Flavors/Views/admin/add_related_news_list', array('related_news' => $restaurant['news'])); ?>
                        <?php endif; ?>
						
						</div>
				      </div>
				   </div>
                </div>
            </div>
			<div class="form-row nag">
                <h3><?=lang('Flavors.RestaurantSettings'); ?></h3>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.Coordinates');?></label>
                </div>
                <div class="form-field">
                    <input  type="text" name="coordinates" placeholder="<?=lang('Flavors.CoordinatesPlaceHolder');?>" value="<?=!empty($restaurant['coordinates_array']) ? esc($restaurant['coordinates_array'][0]).','.esc($restaurant['coordinates_array'][1]) : ''; ?>" />
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.RestaurantPaidEntry');?></label>
                </div>
                <div class="form-field">
                    <input class="datepicker-range" type="text" name="paid_entry_range" value="<?=!empty($restaurant['paid_entry_range']) ? esc($restaurant['paid_entry_range']) : ''; ?>" />
                </div>
            </div>
			 <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($restaurant['publish']) && $restaurant['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
				 <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.RestaurantArchive');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="archives" <?php if(!empty($restaurant['archives']) && $restaurant['archives']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
		    <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.RestaurantAwarded');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="awarded" <?php if(!empty($restaurant['awarded']) && $restaurant['awarded']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
		    <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.RestaurantRecommended');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="recommended" <?php if(!empty($restaurant['recommended']) && $restaurant['recommended']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
		    <div class="form-row submit">
                <button type="submit" class=""><?=lang('Admin.page.Save'); ?></button>
            </div>  
		 </form>	
	</div>
</div>	