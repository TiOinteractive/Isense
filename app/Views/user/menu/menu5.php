<?php if (!empty($menu)): ?>	   
    <ul id="stopka_oferujemy">
        <?php foreach ($menu as $men): ?>		
            <li<?php if(isset($men['active'])  and $men['active']==1) { echo ' class="active"';}?>> 
                <a href="<?= $men['url']; ?>" title="<?= $men['title']; ?>"><?= $men['name']; ?></a>	
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>	