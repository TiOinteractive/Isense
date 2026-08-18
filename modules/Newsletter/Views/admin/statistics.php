<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=$newsletter['subject']; ?>
            <span><?=lang('Newsletter.Statistics');; ?></span>
        </div>
        <?=view('Modules\Newsletter\Views\admin\tabs'); ?>
        <?= view('Modules\Newsletter\Views\admin\statistics_filters', array()); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Newsletter.Email');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Newsletter.Status');?>
                </div>
                <div class="list-col center w200">
                    <?=lang('Newsletter.Date');?>
                </div>
            </div>
            <?php if(!empty($statistics)): ?>
                <?php foreach($statistics as $stat): ?>
                    <div class="list-row list-row-<?=$stat['id']; ?>">
                        <div class="list-col">
                            <?=$stat['email']; ?>
                        </div>
                        <div class="list-col center w100">
                            <?=lang('Newsletter.status.' . $stat['status']); ?>
                        </div>
                        <div class="list-col center w200">
                            <?=date('d.m.Y H:i:s', strtotime(!empty($stat['edited_at']) && $stat['edited_at'] != '0000-00-00 00:00:00' ? $stat['edited_at'] : $stat['created_at'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </div>
        
    </div>
</div>