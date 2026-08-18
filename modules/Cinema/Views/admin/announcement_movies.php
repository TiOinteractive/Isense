<?php if(!empty($movies)): ?>
    <?php foreach($movies as $movie): ?>
        <div class="item">
            <input type="radio" name="id_movie" id="cinema-movie-<?=$movie['id']; ?>" value="<?=$movie['id']; ?>" <?= !empty($announcement) && !empty($announcement['id_movie']) && $announcement['id_movie'] == $movie['id'] ? ' selected="selected"' : ''; ?> /><label for="cinema-movie-<?=$movie['id']; ?>"><?=$movie['title']; ?> <span><?=$movie['original']; ?></span></label>
        </div>
    <?php endforeach; ?>
<?php endif; ?>