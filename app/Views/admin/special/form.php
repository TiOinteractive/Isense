<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=lang('Admin.special_website.SpecialWebsiteSettings'); ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form direct-links-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/special-website-settings" method="post">
            
            <div class="form-row nag">
                <h3><?=lang('Admin.special_website.ClientAccountDirectLinks'); ?></h3>
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
                                    <label><?=lang('Admin.client_account.Login');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="links[users][<?=$lang['id']; ?>][login]" value="<?=!empty($links[$lang['id']]['login']) ? esc($links[$lang['id']]['login']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.client_account.RemindPassword');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="links[users][<?=$lang['id']; ?>][remind_password]" value="<?=!empty($links[$lang['id']]['remind_password']) ? esc($links[$lang['id']]['remind_password']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.client_account.Registration');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="links[users][<?=$lang['id']; ?>][registration]" value="<?=!empty($links[$lang['id']]['registration']) ? esc($links[$lang['id']]['registration']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.client_account.MyAccount');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="links[users][<?=$lang['id']; ?>][client_account]" value="<?=!empty($links[$lang['id']]['client_account']) ? esc($links[$lang['id']]['client_account']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.client_account.UserPhoto');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="links[foto][<?=$lang['id']; ?>][user_photo]" value="<?=!empty($links[$lang['id']]['user_photo']) ? esc($links[$lang['id']]['user_photo']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.SearchUrl');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="links[<?=$lang['id']; ?>][search]" value="<?=!empty($links[$lang['id']]['search']) ? esc($links[$lang['id']]['search']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.TagsUrl');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="links[tags][<?=$lang['id']; ?>][search_tags]" value="<?=!empty($links[$lang['id']]['search_tags']) ? esc($links[$lang['id']]['search_tags']) : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.special_website.OtherDirectLinks'); ?></h3>
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
                                    <label><?=lang('Admin.special_website.Calendar');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="direct[calendar][<?=$lang['id']; ?>]]" value="<?=!empty($direct['calendar'][$lang['id']]) ? esc($direct['calendar'][$lang['id']]) : ''; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.special_website.Place');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="direct[place][<?=$lang['id']; ?>]]" value="<?=!empty($direct['place'][$lang['id']]) ? esc($direct['place'][$lang['id']]) : ''; ?>" />
                                </div>
                            </div>
                        </div>
						<div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.special_website.Flavors');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="direct[flavors][<?=$lang['id']; ?>]]" value="<?=!empty($direct['flavors'][$lang['id']]) ? esc($direct['flavors'][$lang['id']]) : ''; ?>" />
                                </div>
                            </div>
                        </div>
						<div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.special_website.Cuisine');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="direct[cuisine][<?=$lang['id']; ?>]]" value="<?=!empty($direct['cuisine'][$lang['id']]) ? esc($direct['cuisine'][$lang['id']]) : ''; ?>" />
                                </div>
                            </div>
                        </div>
						<div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.special_website.Restaurant');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="direct[restaurant][<?=$lang['id']; ?>]]" value="<?=!empty($direct['restaurant'][$lang['id']]) ? esc($direct['restaurant'][$lang['id']]) : ''; ?>" />
                                </div>
                            </div>
                        </div>
						<div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.special_website.GalleryPhoto');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="direct[gallery_photo][<?=$lang['id']; ?>]]" value="<?=!empty($direct['gallery_photo'][$lang['id']]) ? esc($direct['gallery_photo'][$lang['id']]) : ''; ?>" />
                                </div>
                            </div>
                        </div>
						<div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.special_website.SinglePhoto');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-field" type="text" name="direct[single_photo][<?=$lang['id']; ?>]]" value="<?=!empty($direct['single_photo'][$lang['id']]) ? esc($direct['single_photo'][$lang['id']]) : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.special_website.OtherSpecialWebsites'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.special_website.Rules');?></label>
                </div>
                <div class="form-field">
                    <select name="special[shop_rules]">
                        <option value="0"></option>
                        <?php if(!empty($pages)): ?>
                            <?php foreach($pages as $k=>$p): ?>
                                <?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($specials['shop_rules']) ? $specials['shop_rules'] : 0, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.special_website.PrivacyPolicy');?></label>
                </div>
                <div class="form-field">
                    <select name="special[privacy_policy]">
                        <option value="0"></option>
                        <?php if(!empty($pages)): ?>
                            <?php foreach($pages as $k=>$p): ?>
                                <?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($specials['privacy_policy']) ? $specials['privacy_policy'] : 0, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.special_website.CookiesPolicy');?></label>
                </div>
                <div class="form-field">
                    <select name="special[cookies_policy]">
                        <option value="0"></option>
                        <?php if(!empty($pages)): ?>
                            <?php foreach($pages as $k=>$p): ?>
                                <?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($specials['cookies_policy']) ? $specials['cookies_policy'] : 0, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            
            <div class="form-row submit">
                 <button type="submit" class=""><?=lang('Admin.settings.Save'); ?></button>
            </div>     
        </form>
    </div>
</div>
