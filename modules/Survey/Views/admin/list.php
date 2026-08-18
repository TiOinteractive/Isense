<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Survey.SurveyList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/survey/add" title=""><?=lang('Survey.AddSurvey');?></a></p>
        <?= view('Modules\Survey\Views\admin\list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Survey.Question');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Survey.Votes');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Survey.Result');?>
                </div>
                <div class="list-col center w200 hide-1200">
                    <?=lang('Survey.SurveyDateRange');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Survey.Edit');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Survey.Publish');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Survey.Delete');?>
                </div>
            </div>
            <?php if(!empty($surveys)): ?>
                <?php foreach($surveys as $survey): ?>
                    <div class="list-row list-row-<?=$survey['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/survey/edit/<?=$survey['id']; ?>" title="<?=$survey['question']; ?>"><?=$survey['question']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <?=!empty($survey['result_count']) ? $survey['result_count'] : 0; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <?php if(!empty($survey['result_count'])): ?>
                                <a class="list-chart-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/survey/result/<?=$survey['id']; ?>" title="<?=lang('Survey.Result');?>"><i class="fa-solid fa-chart-pie fa-xl"></i></a>
                            <?php endif; ?>
                        </div>
                        <div class="list-col center w200 hide-1200">
                            <?=!empty($survey['date']) ? $survey['date'] : ''; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/survey/edit/<?=$survey['id']; ?>" title="<?=lang('Survey.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/survey/publish/<?=$survey['id']; ?>" title="<?=lang('Survey.Publish');?>"><?php if(!empty($survey['publish']) && $survey['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/survey/delete/<?=$survey['id']; ?>" data-title="<?=lang('Survey.DeleteSurvey');?>" data-message="<?=lang('Survey.DeleteConfirm') . ': <b>' . $survey['question'] . '</b>'; ?>" data-btn-ok="<?=lang('Survey.Remove');?>" data-btn-cancel="<?=lang('Survey.Cancel');?>" title="<?=lang('Survey.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
