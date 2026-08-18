<?php if (!empty($menu)): ?>	   
    <div class="stopka_menu">
        <ul id="foot_menu">
            <?php foreach ($menu as $men): ?>		
                <li<?php if (isset($men['active']) && $men['active'] == 1) {
            echo ' class="active"';
        } ?>> 
                    <a href="<?= $men['url']; ?>" title="<?= $men['title']; ?>"><?= $men['name']; ?></a>	
                </li>
    <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>