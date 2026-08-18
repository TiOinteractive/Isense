<?php
$adm = ($locale ? '/' . $locale : '') . '/' . env('ADMIN_PANEL_SLUG');
$badge = static function ($status) {
    $map = [
        'new'       => ['#e8f0fe', '#1a56db'],
        'diagnosis' => ['#fff7e6', '#b45309'],
        'repair'    => ['#eef2ff', '#4338ca'],
        'ready'     => ['#ecfdf5', '#047857'],
        'done'      => ['#f1f5f9', '#475569'],
        'cancelled' => ['#fef2f2', '#b91c1c'],
    ];
    return $map[$status] ?? ['#f1f5f9', '#475569'];
};
$fmtDate = static function ($v) {
    if (empty($v)) {
        return '—';
    }
    $ts = strtotime($v);
    return $ts ? date('d.m.Y', $ts) . '<span style="color:#8a8a8a;margin-left:6px;">' . date('H:i', $ts) . '</span>' : esc($v);
};
$hasFilters = ! empty($filters['q']) || ! empty($filters['status']);
?>
<div class="main-cont">
    <?php if (isset($breadcrumbs)) { echo $breadcrumbs; } ?>
    <div class="c">
        <div class="head"><?= lang('Orders.OrdersList'); ?></div>
        <p><a class="btn" href="<?= $adm; ?>/orders/add"><?= lang('Orders.OrderAdd'); ?></a></p>

        <form class="form filters" method="get" action="<?= $adm; ?>/orders" style="margin-bottom:14px;">
            <input type="hidden" name="order" value="<?= esc($filters['order'] ?? '', 'attr'); ?>">
            <input type="hidden" name="on_page" value="<?= esc($filters['on_page'] ?? '', 'attr'); ?>">
            <div class="form-row">
                <div class="form-label"><label><?= lang('Orders.Search'); ?></label></div>
                <div class="form-field" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                    <input type="text" name="q" value="<?= esc($filters['q'] ?? '', 'attr'); ?>" placeholder="<?= lang('Orders.SearchPlaceholder'); ?>" style="max-width:300px;">
                    <select name="status" style="max-width:220px;">
                        <option value=""><?= lang('Orders.AllStatuses'); ?></option>
                        <?php foreach ($statuses as $key => $langKey): ?>
                            <option value="<?= $key; ?>"<?= (($filters['status'] ?? '') === $key) ? ' selected="selected"' : ''; ?>><?= lang($langKey); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn"><?= lang('Orders.Filter'); ?></button>
                    <?php if ($hasFilters): ?>
                        <a href="<?= $adm; ?>/orders" class="btn" style="background:#8a8a8a;"><?= lang('Orders.Clear'); ?></a>
                        <span class="s" style="margin-left:6px;"><?= lang('Orders.Found'); ?>: <strong><?= (int) ($total ?? 0); ?></strong></span>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <?= view('admin/order_and_pagination', ['pager' => $pager, 'order_list' => $order_list, 'on_page_list' => $on_page_list, 'filters' => $filters]); ?>

        <div class="list">
            <div class="list-row list-head">
                <div class="list-col w150"><?= lang('Orders.Number'); ?></div>
                <div class="list-col"><?= lang('Orders.Customer'); ?></div>
                <div class="list-col hide-1200"><?= lang('Orders.Device'); ?></div>
                <div class="list-col hide-1200"><?= lang('Orders.Service'); ?></div>
                <div class="list-col center w150"><?= lang('Orders.CreatedAt'); ?></div>
                <div class="list-col center w150"><?= lang('Orders.Status'); ?></div>
                <div class="list-col center w100 hide-1200"><?= lang('Orders.Edit'); ?></div>
                <div class="list-col center w100 hide-500"><?= lang('Orders.Publish'); ?></div>
                <div class="list-col center w100"><?= lang('Orders.Delete'); ?></div>
            </div>
            <?php if (! empty($orders)): ?>
                <?php foreach ($orders as $o): [$bgc, $fgc] = $badge($o['status']); ?>
                    <div class="list-row list-row-<?= $o['id']; ?>">
                        <div class="list-col w150"><strong><?= esc($o['number']); ?></strong></div>
                        <div class="list-col">
                            <?php if (! empty($o['customer'])): ?><div><?= esc($o['customer']); ?></div><?php endif; ?>
                            <?php if (! empty($o['email'])): ?><div style="font-size:12px;color:#8a8a8a;"><a href="mailto:<?= esc($o['email'], 'attr'); ?>" style="color:#8a8a8a;"><?= esc($o['email']); ?></a></div><?php endif; ?>
                            <?php if (! empty($o['phone'])): ?><div style="font-size:12px;color:#8a8a8a;"><a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $o['phone']), 'attr'); ?>" style="color:#8a8a8a;"><?= esc($o['phone']); ?></a></div><?php endif; ?>
                            <?php if (empty($o['customer']) && empty($o['email']) && empty($o['phone'])): ?><span style="color:#b0b0b0;">—</span><?php endif; ?>
                        </div>
                        <div class="list-col hide-1200"><?= esc($o['device']); ?></div>
                        <div class="list-col hide-1200"><?= esc($o['service']); ?></div>
                        <div class="list-col center w150" style="white-space:nowrap;"><?= $fmtDate($o['created_at']); ?></div>
                        <div class="list-col center w150">
                            <span style="display:inline-block;padding:3px 10px;border-radius:999px;font-size:12px;background:<?= $bgc; ?>;color:<?= $fgc; ?>;white-space:nowrap;"><?= lang($statuses[$o['status']] ?? 'Orders.StatusNew'); ?></span>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?= $adm; ?>/orders/edit/<?= $o['id']; ?>" title="<?= lang('Orders.Edit'); ?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?= $adm; ?>/orders/publish/<?= $o['id']; ?>" title="<?= lang('Orders.Publish'); ?>"><?php if (! empty($o['publish'])): ?><i class="fa-solid fa-square-check fa-2x"></i><?php else: ?><i class="fa-regular fa-square fa-2x"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                            <?php if (isset($_SESSION['role']) and ! in_array($_SESSION['role'], ['editor', 'contributor'])): ?>
                                <a class="list-remove-btn" href="<?= $adm; ?>/orders/delete/<?= $o['id']; ?>" data-title="<?= lang('Orders.DeleteOrder'); ?>" data-message="<?= lang('Orders.DeleteConfirm') . ': <b>' . esc($o['number']) . '</b>'; ?>" data-btn-ok="<?= lang('Orders.Remove'); ?>" data-btn-cancel="<?= lang('Orders.Cancel'); ?>" title="<?= lang('Orders.Delete'); ?>"><i class="fa-solid fa-trash-can fa-2x"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-row"><div class="list-col"><?= $hasFilters ? lang('Orders.NoResults') : lang('Orders.NoOrders'); ?></div></div>
            <?php endif; ?>
        </div>

        <?= view('admin/order_and_pagination', ['pager' => $pager, 'order_list' => $order_list, 'on_page_list' => $on_page_list, 'filters' => $filters]); ?>
    </div>
</div>
