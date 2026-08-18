<div class="form-row">
    <div class="form-label">
        <label><?= lang('News.Template'); ?></label>
    </div>
    <div class="form-field">
        <?php if (!empty($templates)): ?>
            <div class="templates-box">
                <?php foreach ($templates as $k => $template): ?>
                    <div class="template-item">
                        <input id="template-<?= $k; ?>" type="radio" name="template" value="<?= $template['file']; ?>"<?= !empty($page_content['template']) && $page_content['template'] == $template['file'] ? ' checked="checked"' : ''; ?> />
                        <label for="template-<?= $k; ?>"><img src="/adm/img/bigbox/<?= $template['file']; ?>.png" alt="<?= $template['name']; ?>" /><?= $template['name']; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?= lang('News.ItemsPerPage'); ?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no]" min="0" max="100" value="<?= !empty($page_content['config']) && isset($page_content['config']['no']) ? $page_content['config']['no'] : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label>Przypisz do boxu 1</label>
    </div>
    <div class="form-field">
        <div class="bigbox_1 list" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/save_bigbox/1">
            <?= view('Modules\News\Views\admin\bigbox_list', array('box_list' => $big_box_list[1], 'locale' => $locale)); ?>
        </div>
        <p><a class="btn small add-bigbox" id="bigbox_1" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/add_bigbox/1" data-title="Dodaj newsy do boxu 1" data-btn-ok="Dodaj" data-btn-cancel="Anuluj" data-btn-close="Zamknij">Dodaj newsy</a></p>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label>Przypisz do boxu 2</label>
    </div>
    <div class="form-field">
        <div class="bigbox_2 list" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/save_bigbox/2">
            <?= view('Modules\News\Views\admin\bigbox_list', array('box_list' => $big_box_list[2], 'locale' => $locale)); ?>
        </div>

        <p><a class="btn small add-bigbox" id="bigbox_2" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/add_bigbox/2" data-title="Dodaj newsy do boxu 2" data-btn-ok="Dodaj" data-btn-cancel="Anuluj" data-btn-close="Zamknij">Dodaj newsy</a></p>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label>Przypisz do boxu 3</label>
    </div>
    <div class="form-field">
        <div class="bigbox_3 list" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/save_bigbox/3">
            <?= view('Modules\News\Views\admin\bigbox_list', array('box_list' => $big_box_list[3], 'locale' => $locale)); ?>
        </div>

        <p><a class="btn small add-bigbox" id="bigbox_3" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/add_bigbox/3" data-title="Dodaj newsy do boxu 3" data-btn-ok="Dodaj" data-btn-cancel="Anuluj" data-btn-close="Zamknij">Dodaj newsy</a></p>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label>Przypisz do boxu 4</label>
    </div>
    <div class="form-field">
        <div class="bigbox_4 list" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/save_bigbox/4">
            <?= view('Modules\News\Views\admin\bigbox_list', array('box_list' => $big_box_list[4], 'locale' => $locale)); ?>
        </div>
        <p><a class="btn small add-bigbox" id="bigbox_4" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/add_bigbox/4" data-title="Dodaj newsy do boxu 4" data-btn-ok="Dodaj" data-btn-cancel="Anuluj" data-btn-close="Zamknij">Dodaj newsy</a></p>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label>Przypisz do boxu 5</label>
    </div>
    <div class="form-field">
        <div class="bigbox_5 list" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/save_bigbox/5">
            <?= view('Modules\News\Views\admin\bigbox_list', array('box_list' => $big_box_list[5], 'locale' => $locale)); ?>
        </div>
        <p><a class="btn small add-bigbox" id="bigbox_5" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/add_bigbox/5" data-title="Dodaj newsy do boxu 5" data-btn-ok="Dodaj" data-btn-cancel="Anuluj" data-btn-close="Zamknij">Dodaj newsy</a></p>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label>Przypisz do boxu 6</label>
    </div>
    <div class="form-field">
        <div class="bigbox_6 list" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/save_bigbox/6">
            <?= view('Modules\News\Views\admin\bigbox_list', array('box_list' => $big_box_list[6], 'locale' => $locale)); ?>
        </div>
        <p><a class="btn small add-bigbox" id="bigbox_6" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/add_bigbox/6" data-title="Dodaj newsy do boxu 6" data-btn-ok="Dodaj" data-btn-cancel="Anuluj" data-btn-close="Zamknij">Dodaj newsy</a></p>
    </div>
</div>