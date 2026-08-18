<?php if(!empty($data['types'])): ?>
    <table>
        <tr>
            <th class="no"></th>
            <th class="name"><?=lang('Event.Name'); ?></th>
            <th class="type"><?=lang('Event.Type'); ?></th>
        </tr>
        <?php $i=1; foreach($data['types'] as $k=>$t): ?>
            <tr class="tr tr-<?=$k; ?>">
                <td class="no"><strong><?=$i; ?></strong></td>
                <td class="name"><?=$t; ?></td>
                <td class="type">
                    <select name="assign_types[<?=$k; ?>]">
                        <option value=""></option>
                        <?php if(!empty($types)): ?>
                            <?php foreach($types as $type): ?>
                                <option value="<?=$type['id']; ?>"<?php if(!empty($external_types) && !empty($external_types[$k]) && $external_types[$k] == $type['id']): ?> selected="selected"<?php endif; ?>><?=$type['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
        <?php $i++; endforeach; ?>
    </table>
<?php endif; ?>
