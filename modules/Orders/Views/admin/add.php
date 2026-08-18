<?php
$adm = ($locale ? '/' . $locale : '') . '/' . env('ADMIN_PANEL_SLUG');

/** Jeden wiersz etapu osi czasu (używany też jako szablon nowego wiersza z indeksem __i__). */
$renderStep = static function ($i, $step, $icons) {
    $sel = static fn($a, $b) => ((string) $a === (string) $b) ? ' selected="selected"' : '';
    $n = 'steps[' . $i . ']';
    ?>
    <div data-repeater-row style="border:1px solid #E5E5EA;border-radius:6px;padding:12px 40px 12px 12px;margin-bottom:10px;position:relative;background:#fff;">
        <button type="button" data-repeater-remove title="Usuń etap" style="position:absolute;right:8px;top:8px;cursor:pointer;border:0;background:transparent;font-size:16px;line-height:1;color:#b91c1c;">✕</button>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:0 0 150px;">
                <label style="display:block;font-size:12px;color:#6E6E73;margin-bottom:4px;"><?= lang('Orders.StepIcon'); ?></label>
                <select name="<?= $n; ?>[icon]" style="width:100%;">
                    <?php foreach ($icons as $ic): ?><option value="<?= $ic; ?>"<?= $sel($step['icon'] ?? 'package', $ic); ?>><?= $ic; ?></option><?php endforeach; ?>
                </select>
            </div>
            <div style="flex:1 1 240px;min-width:180px;">
                <label style="display:block;font-size:12px;color:#6E6E73;margin-bottom:4px;"><?= lang('Orders.StepName'); ?></label>
                <input type="text" name="<?= $n; ?>[name]" value="<?= esc($step['name'] ?? '', 'attr'); ?>" placeholder="<?= lang('Orders.StepNamePlaceholder'); ?>" style="width:100%;">
            </div>
            <div style="flex:0 0 180px;">
                <label style="display:block;font-size:12px;color:#6E6E73;margin-bottom:4px;"><?= lang('Orders.StepDate'); ?></label>
                <input type="text" name="<?= $n; ?>[date]" value="<?= esc($step['date'] ?? '', 'attr'); ?>" placeholder="<?= lang('Orders.StepDatePlaceholder'); ?>" style="width:100%;">
            </div>
            <div style="flex:0 0 170px;">
                <label style="display:block;font-size:12px;color:#6E6E73;margin-bottom:4px;"><?= lang('Orders.StepState'); ?></label>
                <select name="<?= $n; ?>[state]" style="width:100%;">
                    <option value="done"<?= $sel($step['state'] ?? 'todo', 'done'); ?>><?= lang('Orders.StateDone'); ?></option>
                    <option value="current"<?= $sel($step['state'] ?? 'todo', 'current'); ?>><?= lang('Orders.StateCurrent'); ?></option>
                    <option value="todo"<?= $sel($step['state'] ?? 'todo', 'todo'); ?>><?= lang('Orders.StateTodo'); ?></option>
                </select>
            </div>
        </div>
    </div>
    <?php
};
?>
<div class="main-cont">
    <?php if (isset($breadcrumbs)) { echo $breadcrumbs; } ?>
    <div class="c">
        <div class="head">
            <?php if (! empty($order['id'])): ?>
                <?= esc($order['number'] ?? ''); ?> <span><?= lang('Orders.OrderEdit'); ?></span>
            <?php else: ?>
                <?= lang('Orders.OrderAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', ['flashdata' => ! empty($flashdata) ? $flashdata : []]); ?>

        <form class="form" method="post" action="<?= $adm; ?>/orders/<?= $action; ?><?= ! empty($order['id']) ? '/' . $order['id'] : ''; ?>">
            <div class="form-row nag"><h3><?= lang('Orders.OrderData'); ?></h3></div>

            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Number'); ?></label></div>
                <div class="form-field">
                    <input type="text" name="number" value="<?= esc($order['number'] ?? '', 'attr'); ?>" placeholder="ORD-12345">
                    <span class="s">(<?= lang('Orders.NumberInfo'); ?>)</span>
                </div>
            </div>
            <div class="form-row nag" style="margin-top:20px;"><h3><?= lang('Orders.CustomerData'); ?></h3></div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Customer'); ?></label></div>
                <div class="form-field"><input type="text" name="customer" value="<?= esc($order['customer'] ?? '', 'attr'); ?>" placeholder="Jan Kowalski"></div>
            </div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Email'); ?></label></div>
                <div class="form-field"><input type="text" name="email" value="<?= esc($order['email'] ?? '', 'attr'); ?>" placeholder="jan@example.com"></div>
            </div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Phone'); ?></label></div>
                <div class="form-field"><input type="text" name="phone" value="<?= esc($order['phone'] ?? '', 'attr'); ?>" placeholder="+48 500 600 700"></div>
            </div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Address'); ?></label></div>
                <div class="form-field">
                    <textarea name="address" rows="3" placeholder="<?= lang('Orders.AddressPlaceholder'); ?>"><?= esc($order['address'] ?? ''); ?></textarea>
                    <span class="s">(<?= lang('Orders.AddressInfo'); ?>)</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field" style="width:100%;">
                    <p class="s" style="margin:0;"><?= lang('Orders.CustomerInfo'); ?></p>
                </div>
            </div>

            <div class="form-row nag" style="margin-top:20px;"><h3><?= lang('Orders.DeviceData'); ?></h3></div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Device'); ?></label></div>
                <div class="form-field"><input type="text" name="device" value="<?= esc($order['device'] ?? '', 'attr'); ?>" placeholder="iPhone 15 Pro"></div>
            </div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Service'); ?></label></div>
                <div class="form-field"><input type="text" name="service" value="<?= esc($order['service'] ?? '', 'attr'); ?>" placeholder="<?= lang('Orders.ServicePlaceholder'); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Estimated'); ?></label></div>
                <div class="form-field">
                    <input type="text" name="estimated" value="<?= esc($order['estimated'] ?? '', 'attr'); ?>" placeholder="26.05.2026">
                    <span class="s">(<?= lang('Orders.EstimatedInfo'); ?>)</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Status'); ?></label></div>
                <div class="form-field">
                    <select name="status">
                        <?php foreach ($statuses as $key => $langKey): ?>
                            <option value="<?= $key; ?>"<?= (($order['status'] ?? 'new') === $key) ? ' selected="selected"' : ''; ?>><?= lang($langKey); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Note'); ?></label></div>
                <div class="form-field"><textarea name="note" rows="3"><?= esc($order['note'] ?? ''); ?></textarea><span class="s">(<?= lang('Orders.NoteInfo'); ?>)</span></div>
            </div>
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Publish'); ?></label></div>
                <div class="form-field">
                    <input type="checkbox" name="publish" value="1"<?= (! isset($order['publish']) || ! empty($order['publish'])) ? ' checked="checked"' : ''; ?>>
                    <span class="s">(<?= lang('Orders.PublishInfo'); ?>)</span>
                </div>
            </div>

            <div class="form-row nag" style="margin-top:20px;"><h3><?= lang('Orders.Timeline'); ?></h3></div>
            <div class="form-row">
                <div class="form-field" style="width:100%;">
                    <p class="s" style="margin:0 0 10px;"><?= lang('Orders.TimelineInfo'); ?></p>
                    <div data-repeater>
                        <div data-repeater-items>
                            <?php foreach ($steps as $i => $s) { $renderStep($i, $s, $icons); } ?>
                        </div>
                        <template data-repeater-template><?php $renderStep('__i__', [], $icons); ?></template>
                        <button type="button" data-repeater-add class="btn"><?= lang('Orders.AddStep'); ?></button>
                    </div>
                </div>
            </div>

            <div class="form-row submit"><button type="submit"><?= lang('Orders.Save'); ?></button></div>
        </form>
    </div>
</div>
