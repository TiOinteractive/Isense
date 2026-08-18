<?php
/* 
Menu category
*/	
?>
<?php if (!empty($data)): ?>	   
    <div class="scroll-menu">
        <div class="container">
            <ul id="scroll-menu" class="scroll-tabs">
                <?php foreach ($data as $men): ?>		
                    <li> 
                        <a href="<?= $men['url']; ?>" title="<?= $men['title']; ?>"><?= $men['name']; ?></a>	
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
<?php endif; ?>