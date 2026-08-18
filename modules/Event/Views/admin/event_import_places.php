<?php if(!empty($data['places'])): ?>
    <table>
        <tr>
            <th class="no"></th>
            <th class="name"><?=lang('Event.Name'); ?></th>
            <th class="type"><?=lang('Event.Type'); ?></th>
        </tr>
        <?php $i=1; foreach($data['places'] as $k=>$p): ?>
            <tr class="tr tr-<?=$k; ?>">
                <td class="no"><strong><?=$i; ?></strong></td>
                <td class="name"><?=$p['name']; ?>, <?=$p['address']; ?></td>
                <td class="type">
                    <select name="assign_places[<?=$p['name']; ?>]">
                        <option value=""></option>
                        <option value="del">### <?=lang('Event.Remove'); ?> ###</option>
                        <?php if(!empty($places)): ?>
                            <?php foreach($places as $place): ?>
                                <option value="<?=$place['id']; ?>"<?php if(!empty($external_places) && !empty($external_places[$p['name']]) && $external_places[$p['name']] == $place['id']): ?> selected="selected"<?php endif; ?>><?=$place['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
        <?php $i++; endforeach; ?>
    </table>
<?php endif; ?>
