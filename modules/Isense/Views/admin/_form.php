<?php
/*
 * Generyczny renderer formularza sekcji iSense.
 * Oczekuje w zasięgu: $form_data, $languages oraz $cfg:
 *   $cfg = [
 *     'title'  => 'Isense.SectionX',
 *     'fields' => [ ['name'=>..,'label'=>..,'type'=>'text|textarea|select','options'=>[val=>label]], ... ],
 *     'lists'  => [ ['key'=>..,'label'=>..,'add'=>..,'item'=>[ ['name'=>..,'label'=>..,'type'=>..,'options'=>..], ... ] ], ... ],
 *   ]
 */
$fd = $form_data['lang'] ?? [];

$renderField = function ($name, $meta, $value) {
    $type = $meta['type'] ?? 'text';
    echo '<div class="form-row"><div class="form-label"><label>' . esc($meta['label'] ?? $name) . '</label></div><div class="form-field">';
    if ($type === 'textarea') {
        echo '<textarea name="' . $name . '">' . esc($value ?? '') . '</textarea>';
    } elseif ($type === 'select') {
        echo '<select name="' . $name . '">';
        foreach (($meta['options'] ?? []) as $ov => $ol) {
            echo '<option value="' . esc($ov, 'attr') . '"' . ((string)$value === (string)$ov ? ' selected' : '') . '>' . esc($ol) . '</option>';
        }
        echo '</select>';
    } else {
        echo '<input type="text" name="' . $name . '" value="' . esc($value ?? '', 'attr') . '">';
    }
    echo '</div></div>';
};

$renderRow = function ($lid, $list, $i, $item) use ($renderField) {
    echo '<div class="isense-rep-row" data-repeater-row style="border:1px solid #E5E5EA;border-radius:6px;padding:12px;margin-bottom:10px;position:relative;">';
    echo '<button type="button" data-repeater-remove title="Usuń" style="position:absolute;right:8px;top:8px;cursor:pointer;">✕</button>';
    foreach ($list['item'] as $fld) {
        $name = 'form_data[lang][' . $lid . '][' . $list['key'] . '][' . $i . '][' . $fld['name'] . ']';
        $renderField($name, $fld, $item[$fld['name']] ?? '');
    }
    echo '</div>';
};
?>
<div class="form-row nag"><h3><?= lang($cfg['title']) ?></h3></div>
<div class="tabs">
    <?php if (!empty($languages) && count($languages) > 1): ?>
        <div class="tabs-head"><?php $l = 0; foreach ($languages as $lg): ?><div class="tab<?= $l == 0 ? ' active' : '' ?>"><span class="name"><?= $lg['name'] ?></span><span class="short-name"><?= $lg['short_name'] ?></span></div><?php ++$l; endforeach; ?></div>
        <div class="tabs-content">
    <?php endif; ?>
    <?php $l = 0; foreach ($languages as $lg): $f = $fd[$lg['id']] ?? []; $lid = $lg['id']; ?>
        <div class="tab-item<?= $l == 0 ? ' active' : '' ?>">
            <?php foreach (($cfg['fields'] ?? []) as $fld): ?>
                <?php $renderField('form_data[lang][' . $lid . '][' . $fld['name'] . ']', $fld, $f[$fld['name']] ?? ''); ?>
            <?php endforeach; ?>

            <?php foreach (($cfg['lists'] ?? []) as $list): ?>
                <div class="form-row nag" style="margin-top:16px;"><h4><?= esc($list['label']) ?></h4></div>
                <div data-repeater>
                    <div data-repeater-items>
                        <?php foreach (($f[$list['key']] ?? []) as $i => $item) $renderRow($lid, $list, $i, $item); ?>
                    </div>
                    <template data-repeater-template><?php $renderRow($lid, $list, '__i__', []); ?></template>
                    <button type="button" data-repeater-add class="button" style="margin-top:6px;"><?= esc($list['add'] ?? '+ dodaj') ?></button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php ++$l; endforeach; ?>
    <?php if (!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
</div>
