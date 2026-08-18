<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Admin.administration.Administration');?></div>
        <div class="list list-boxes">
            <?php if(!empty($administrations)): ?>
                <?php foreach($administrations as $administration): ?>
                    <div class="list-box">
                        <div class="bc trans400">
                            <div class="ico"><a href="<?=$locale ? '/' . $locale : ''; ?><?=$administration['link']; ?>" title="<?=$administration['name']; ?>"><?=$administration['ico']; ?><span><?=$administration['name']; ?></span></a></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>