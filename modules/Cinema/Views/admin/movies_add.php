<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
        <?= lang('Cinema.NewCinemaMassMoviesAdd'); ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form cinema-movie-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/cinema/<?php echo $action; ?>/<?=$id_content; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Cinema.CinemaMovieSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Template'); ?></label>
                </div>
                <div class="form-field">
                    <select name="template">
                        <?php if(!empty($templates)): ?>
                            <?php foreach($templates as $template): ?>
                                <option value="<?= $template['file']; ?>"<?= !empty($movie) && $movie['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?= $template['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row nag">
                <h3><?= lang('Cinema.CinemaMedia'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Poster'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($movies)): ?>
                            <?php foreach($movies as $k => $movie): ?>
                                <?= view('Modules\Cinema\Views\admin\upload_movies', array('genres' => $genres, 'movie' => $movie, 'field' => 'movies', 'file' => $movie, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="movies" data-option="movies" data-module="cinema" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Cinema.Save'); ?></button>
            </div>
        </form>
    </div>
</div>