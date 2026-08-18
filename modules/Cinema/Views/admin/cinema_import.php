<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs; } ?>
    <div class="c">
        <div class="head">
            <?= lang('Cinema.CalendarImport'); ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <?= view('Modules\Cinema\Views\admin\cinema_statistics', array('statistics' => !empty($flashdata) && !empty($flashdata['statistics']) ? $flashdata['statistics'] : array())); ?>
        <form class="form cinema-import" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/cinema/<?php echo $action; ?>" method="post" data-title="<?=lang('Cinema.RepertoireSaving'); ?>" data-message="<?=lang('Cinema.RepertoireSavingInfo'); ?>" data-btn-close="<?=lang('Cinema.Close'); ?>" data-btn-cancel="<?=lang('Cinema.Cancel'); ?>">
            <div class="form-row nag">
                <h3><?= lang('Cinema.ImportData'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.ImportFile'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list"></div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="import" data-field="import" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/import" >
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.ImportTeplate'); ?></label>
                </div>
                <div class="form-field">
                    <select name="template" class="cinema-import-template">
                        <option value=""></option>
                        <option value="helios"><?=lang('Cinema.import.Helios'); ?> (CSV, XLS, XML)</option>
                        <option value="multikino"><?=lang('Cinema.import.Multikino'); ?> (CSV, XLSX)</option>
                        <option value="zorza"><?=lang('Cinema.import.Zorza'); ?> (CSV, XML)</option>
                        <option value="kzrcafe"><?=lang('Cinema.import.KzRCafe'); ?> (CSV, XLSX)</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.CinemaPlace'); ?></label>
                </div>
                <div class="form-field">
                    <select class="cinema-place" name="id_place">
                        <option value=""></option>
                        <?php if(!empty($places)): ?>
                            <?php foreach($places as $place): ?>
                                <option value="<?= $place['id']; ?>"><?= $place['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.ImportDateStart'); ?></label>
                </div>
                <div class="form-field">
                    <input class="cinema-import-date datepicker-date" type="text" value="<?=date('d.m.Y', strtotime('next friday')); ?>" />
                </div>
            </div>
            <div class="import-content">
                
            </div>
            
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Cinema.Import'); ?></button>
            </div>
        </form>
    </div>
</div>