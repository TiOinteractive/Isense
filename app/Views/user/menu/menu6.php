<?php if (!empty($menu)): ?>	   
    <ul id="stopka">
        <?php foreach ($menu as $men): ?>		
            <li<?php if(isset($men['active']) && $men['active']==1) { echo ' class="active"';}?>> 
                <a href="<?= $men['url']; ?>" title="<?= $men['title']; ?>"<?php if(!empty($men['target'])): ?> target="<?=$men['target']; ?>"<?php endif; ?>><?= $men['name']; ?></a>	
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>	