<?php if (!empty($menu)): ?>
    <div class="dropdown-menu">
        <div id="black">

        </div>
        <div id="white">
            <div class="inside">
                <div class="column"> 
                    <?php foreach ($menu as $k => $item): ?>
                        <h3><a href="<?= $item['url']; ?>"><?= $item['name']; ?></a></h3>
                        <?php if ($k == 4 && !empty($item['submenu'])): ?>
                            <div class="list">
                                <?php foreach ($item['submenu'] as $sub): ?>
                                    <div class="column">
                                        <h4><a href="<?= $sub['url']; ?>"><?= $sub['name']; ?></a></h4>
                                        <?php if (!empty($sub['submenu'])): ?>
                                            <ul>
                                                <?php foreach ($sub['submenu'] as $subs): ?>
                                                    <li><a href="<?= $subs['url']; ?>"><?= $subs['name']; ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>				
                            <?php if (!empty($item['submenu'])): ?>
                                <ul>
                                    <?php foreach ($item['submenu'] as $sub): ?>
                                        <li><a href="<?= $sub['url']; ?>"><?= $sub['name']; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($k == 3 || $k == 6): ?>
                            </div>
                            <div class="column">
                        <?php endif; ?>  
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="button" class="navbar-toggle-close trans400">Zamknij <svg fill="none" height="20" viewBox="0 0 24 24"><path d="M6.2253 4.81108C5.83477 4.42056 5.20161 4.42056 4.81108 4.81108C4.42056 5.20161 4.42056 5.83477 4.81108 6.2253L10.5858 12L4.81114 17.7747C4.42062 18.1652 4.42062 18.7984 4.81114 19.1889C5.20167 19.5794 5.83483 19.5794 6.22535 19.1889L12 13.4142L17.7747 19.1889C18.1652 19.5794 18.7984 19.5794 19.1889 19.1889C19.5794 18.7984 19.5794 18.1652 19.1889 17.7747L13.4142 12L19.189 6.2253C19.5795 5.83477 19.5795 5.20161 19.189 4.81108C18.7985 4.42056 18.1653 4.42056 17.7748 4.81108L12 10.5858L6.2253 4.81108Z" fill="currentColor"/></svg></button>
        </div>
    </div>
<?php endif; ?>	