<div class="main-cont">
    <?php if (isset($breadcrumbs)) {
        echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
        <?php if (!empty($gallery['lang'][$id_lang]['name'])): ?><?= $gallery['lang'][$id_lang]['name']; ?><?php endif; ?>
            <span><?= lang('Foto.GalleryEdit'); ?></span>
        </div>
<?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form news-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/foto/<?php echo $action; ?>/<?= $id_content; ?><?= !empty($gallery['id']) ? '/' . $gallery['id'] : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('News.BasicInformation'); ?></h3>
            </div>
            <div class="tabs">
                    <?php if (!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l = 0;
                        foreach ($languages as $lang): ?>
                            <div class="tab<?= $l == 0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
                            <?php ++$l;
                        endforeach; ?>
                    </div>
                    <div class="tabs-content">
<?php endif; ?>
<?php $l = 0;
foreach ($languages as $lang): ?>
                        <div class="link-box lang-<?= $lang['id']; ?> tab-item<?= $l == 0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?= lang('Foto.GalleryName'); ?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?= $lang['id']; ?>][name]" value="<?= !empty($gallery['lang']) ? esc($gallery['lang'][$lang['id']]['name']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?= lang('News.DirectLink'); ?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-page-id" type="hidden" name="lang[<?= $lang['id']; ?>][id_page]" value="<?= !empty($page) ? $page['id_page'] : ''; ?>" />
                                    <input class="link-id" type="hidden" name="lang[<?= $lang['id']; ?>][id_link]" value="<?= !empty($gallery['lang']) ? $gallery['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                    <input class="link-id-lang" type="hidden" value="<?= $lang['id']; ?>" />
                                    <input class="link-field" type="text" name="lang[<?= $lang['id']; ?>][link]" value="<?= !empty($gallery['lang']) ? esc($gallery['lang'][$lang['id']]['link']) : ''; ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?= lang('News.Content'); ?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?= $lang['id']; ?>][description]"><?= !empty($gallery['lang']) ? $gallery['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?= lang('Foto.Keywords'); ?></label>
                                </div>
                                <div class="form-field">
                                    <input class="tags_input" name="lang[<?= $lang['id']; ?>][keywords]" value="<?= !empty($gallery['lang']) ? esc($gallery['lang'][$lang['id']]['keywords']) : ''; ?>" />
                                </div>
                            </div>
                        </div>
    <?php ++$l;
endforeach; ?>
<?php if (!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row nag with-btn">
                <h3><?= lang('Foto.GalleryRelated'); ?></h3>
                <a href="/<?= env('ADMIN_PANEL_SLUG'); ?>/foto/add-related-gallery/<?= $gallery['id']; ?>" class="btn add-related-gallery" title="" data-title="<?= lang('Foto.AddRelated'); ?>" data-btn-ok="<?= lang('Foto.Add'); ?>" data-btn-cancel="<?= lang('Foto.Cancel'); ?>" data-btn-close="<?= lang('Foto.Close'); ?>"><?= lang('Foto.AddRelated'); ?></a>
            </div>
            <div class="list">
                <div class="list-row list-head">
                    <div class="list-col w100">&nbsp;</div>
                    <div class="list-col">
                        <?= lang('Foto.GalleryName'); ?>
                    </div>
                    <div class="list-col w200">
                        <?= lang('Foto.GalleryCreatedDate'); ?>
                    </div>
                    <div class="list-col w200 center">
                    <?= lang('Gallery.Edit'); ?>
                    </div>
                    <div class="list-col center w100">
                    <?= lang('Gallery.Delete'); ?>
                    </div>
                </div>
                <div class="related-box">
<?php if (!empty($gallery['related'])): ?>
    <?php foreach ($gallery['related'] as $rel): ?>
        <?= view('Modules\Foto\Views\admin\related_gallery_list_item_save', array('product' => $rel)); ?>
    <?php endforeach; ?>
<?php endif; ?>
                </div>            
            </div>
            <div class="form-row nag">
                <h3><?= lang('Foto.GallerySettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Foto.GalleryCategory'); ?></label>
                </div>
                <div class="form-field">
                    <select name="id_category" class="link-category-id">
                        <option value="0">(<?= lang('Foto.NoSelectedCategory'); ?>)</option>
<?php if (!empty($pages)): ?>
    <?php foreach ($pages as $k => $p): ?>
        <?= view('admin/page/select_parents', array('page' => $p, 're_id' => !empty($gallery['id_category']) ? $gallery['id_category'] : 0, 'count' => count($pages), 'item_no' => $k + 1)); ?>
    <?php endforeach; ?>
<?php endif; ?>
                    </select>
                </div>
            </div>


            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('News.SelectAsHome'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="home" <?php if (!empty($gallery['home']) && $gallery['home']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('News.Publish'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if (!empty($gallery['publish']) && $gallery['publish']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('News.Investments'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="investments" <?php if (!empty($gallery['investments']) && $gallery['investments']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('News.Save'); ?></button>
            </div>
            <div class="form-row nag">
                <h3><?= lang('Foto.PhotosList'); ?></h3>
            </div>
                        <?php if (!empty($gallery['photos'])): ?>
                <div class="form-row">
                    <div class="form-field order-sortable" style="width:100%;">
                        <div class="gallery-photo-list files-list order-list"> 
                <?php foreach ($gallery['photos'] as $photo): ?>
                    <?= view('Modules\Foto\Views\admin\photo_item', array('file' => $photo)); ?>
                <?php endforeach; ?>
                        </div> 
                    </div>  
                </div> 
<?php else: ?>
                <p><?= lang('Foto.NoPhotoInGallery'); ?></p>
<?php endif; ?>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('News.Save'); ?></button>
            </div>
        </form>	
    </div>
</div>