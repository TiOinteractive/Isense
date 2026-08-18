
<?php if (!empty($data['search_engine'])): ?>
    <div class="search-engine-2 hight-fields">
        <form action="/<?= $data['form_url']; ?>" method="get" class="catalog-form">
            <div class="search-top">
                 <?php if(!empty($mobile)):?>
				
				<div class="filter-box filter-cat">
                    <select class="field">
                        <option value="">Wybierz kategorię</option>
                        <?= view_cell('\App\Libraries\Page::showMenu', ['id_menu' => 4, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'catalog_mobile', 'submenu_levels' => 0, 'options' => []]) ?>
                    </select>
                </div>
				
				<?php endif;?>
				
				<div class="filter-box filter-order">
                    <select class="field" name="o">
                        <option value=""><?=lang('Catalog.user.OrderDefault'); ?></option>
                        <option value="a-z"<?php if(!empty($data['filters']) && !empty($data['filters']['o']) && $data['filters']['o'] == 'a-z'): ?> selected="selected"<?php endif; ?>><?=lang('Catalog.user.OrderAlphabeticalAZ'); ?></option>
                        <option value="z-a"<?php if(!empty($data['filters']) && !empty($data['filters']['o']) && $data['filters']['o'] == 'z-a'): ?> selected="selected"<?php endif; ?>><?=lang('Catalog.user.OrderAlphabeticalZA'); ?></option>
                        <option value="p"<?php if(!empty($data['filters']) && !empty($data['filters']['o']) && $data['filters']['o'] == 'p'): ?> selected="selected"<?php endif; ?>><?=lang('Catalog.user.OrderMostPopular'); ?></option>
                        <option value="l"<?php if(!empty($data['filters']) && !empty($data['filters']['o']) && $data['filters']['o'] == 'l'): ?> selected="selected"<?php endif; ?>><?=lang('Catalog.user.OrderLatest'); ?></option>
                    </select>
                </div>
                <div class="filter-box filter-search">
                    <input class="field" type="text" name="s" value="<?=!empty($data['filters']['s']) ? $data['filters']['s'] : ''; ?>" placeholder="<?=lang('Catalog.user.FindCatalog'); ?>" autocomplete="off" />
                    <button><svg viewBox="0 0 512 512"><path d="M456.69,421.39,362.6,327.3a173.81,173.81,0,0,0,34.84-104.58C397.44,126.38,319.06,48,222.72,48S48,126.38,48,222.72s78.38,174.72,174.72,174.72A173.81,173.81,0,0,0,327.3,362.6l94.09,94.09a25,25,0,0,0,35.3-35.3ZM97.92,222.72a124.8,124.8,0,1,1,124.8,124.8A124.95,124.95,0,0,1,97.92,222.72Z"/></svg></button>
                </div>
            </div>
            <div class="clear"></div>
        </form>
    </div>
<?php endif; ?>