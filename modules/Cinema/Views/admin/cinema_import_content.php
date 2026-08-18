<?php if(!empty($import_data)): ?>
    <table>
        <tr>
            <th class="no"></th>
            <th class="import"><input class="change-all-import" type="checkbox" value="1" /></th>
            <th class="title"><?=lang('Cinema.Title'); ?></th>
            <th class="date"><?=lang('Cinema.CinemaDate'); ?></th>
            <th class="movie"><?=lang('Cinema.Movie'); ?></th>
            <th class="type"><?=lang('Cinema.Type'); ?></th>
            <th class="options"><?=lang('Cinema.Options'); ?></th>
        </tr>
        <?php foreach($import_data as $k=>$data): ?>
            <tr class="tr tr-<?=$k; ?>">
                <td class="no"><strong><?=$k; ?></strong></td>
                <td class="import">
                    <input class="is" type="checkbox" name="movie[<?=$k; ?>][import]" value="1" checked="checked" />
                    <input class="tr" type="hidden" name="movie[<?=$k; ?>][tr]" value="<?=$k; ?>" />
                    <input class="title" type="hidden" name="movie[<?=$k; ?>][title]" value="<?=$data['title']; ?>" />
                </td>
                <td class="title">
                    <p><?=$data['title']; ?></p>
                </td>
                <td class="date">
                    <input class="datepicker-date time" type="text" name="movie[<?=$k; ?>][date]" value="<?=$data['date']; ?>" />
                </td>
                <td class="movie<?php if(empty($data['id_movie'])): ?> warning<?php endif; ?>">
                    <select name="movie[<?=$k; ?>][id]">
                        <option value=""></option>
                        <?php if(!empty($movies)): ?>
                            <?php foreach($movies as $movie): ?>
                                <option value="<?= $movie['id']; ?>"<?php if(!empty($data['id_movie']) && $data['id_movie'] == $movie['id']): ?> selected="selected"<?php endif; ?>><?= $movie['title']; ?><?php if(!empty($movie['original'])): ?> (<?= $movie['original']; ?>)<?php endif; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
                <td class="type<?php if(empty($data['types'])): ?> warning<?php endif; ?>">
                    <select name="movie[<?=$k; ?>][type]" multiple="multiple">
                        <option value=""></option>
                        <?php if(!empty($types)): ?>
                            <?php
                                $types_ordered = array_replace(array_flip($data['types']), $types);
                            ?>
                            <?php foreach($types_ordered as $type): ?>
                                <option value="<?= $type['id']; ?>"<?php if(!empty($data['types']) && in_array($type['id'], $data['types'])): ?> selected="selected"<?php endif; ?>><?= $type['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
                <td class="options">
                    <p class="option">
                        <input class="special" id="movie-<?=$k; ?>-special" type="checkbox" name="movie[<?=$k; ?>][special]" <?php if(!empty($data) && !empty($data['special']) && $data['special']): ?>checked="checked"<?php endif; ?>  value="1" >
                        <label for="movie-<?=$k; ?>-special"><?= lang('Cinema.SpecialShow'); ?></label>
                    </p>
                    <p class="option">
                        <input class="surprise" id="movie-<?=$k; ?>-surprise" type="checkbox" name="movie[<?=$k; ?>][surprise]" <?php if(!empty($data) && !empty($data['surprise']) && $data['surprise']): ?>checked="checked"<?php endif; ?>  value="1" >
                        <label for="movie-<?=$k; ?>-surprise"><?= lang('Cinema.SessionWithSurprises'); ?></label>
                    </p>
                    <p class="option">
                        <input class="premiere" id="movie-<?=$k; ?>-premiere" type="checkbox" name="movie[<?=$k; ?>][premiere]" <?php if(!empty($data) && !empty($data['premiere']) && $data['premiere']): ?>checked="checked"<?php endif; ?>  value="1" >
                        <label for="movie-<?=$k; ?>-premiere"><?= lang('Cinema.Premiere'); ?></label>
                    </p>
                    <p class="option">
                        <input class="pre-premiere" id="movie-<?=$k; ?>-pre-premiere" type="checkbox" name="movie[<?=$k; ?>][pre-premiere]" <?php if(!empty($data) && !empty($data['pre-premiere']) && $data['pre-premiere']): ?>checked="checked"<?php endif; ?>  value="1" >
                        <label for="movie-<?=$k; ?>-pre-premiere"><?= lang('Cinema.Prepremiere'); ?></label>
                    </p>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
