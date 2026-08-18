<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Translator.LanguageVersions');?></div>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Translator.Language');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Translator.Edit');?>
                </div>
            </div>
            <?php if(!empty($languages)): ?>
                <?php foreach($languages as $language): ?>
                    <div class="list-row list-row-<?=$language['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/translator/edit/<?=$language['id']; ?>" title="<?=$language['name']; ?>"><?=$language['name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/translator/edit/<?=$language['id']; ?>" title="<?=lang('Translator.Edit');?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
