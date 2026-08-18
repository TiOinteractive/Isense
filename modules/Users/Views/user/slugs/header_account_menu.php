<div class="user-menu">
    <div class="menu-item<?php if(empty($data['link'])): ?> active<?php endif; ?>">
        <a href="/<?=$global_links['client_account']; ?>" title="<?=lang('Users.account.YourData'); ?>">
            <svg viewBox="0 0 32 32"><path d="M16,20a8,8,0,1,1,8-8A8,8,0,0,1,16,20ZM16,6a6,6,0,1,0,6,6A6,6,0,0,0,16,6Z"></path><path d="M30,32H28A12,12,0,0,0,4,32H2a14,14,0,0,1,28,0Z"></path></svg>
            <strong><?=lang('Users.account.YourData'); ?></strong>
        </a>
    </div>
    <div class="menu-item<?php if(!empty($data['link']) && $data['link'] == 'pass'): ?> active<?php endif; ?>">
        <a href="/<?=$global_links['client_account']; ?>/g/pass" title="<?=lang('Users.account.ChangePassword'); ?>">
            <svg viewBox="0 0 32 32"><g><path d="M25,31H7a3,3,0,0,1-3-3V17a3,3,0,0,1,3-3H25a3,3,0,0,1,3,3V28A3,3,0,0,1,25,31ZM7,16a1,1,0,0,0-1,1V28a1,1,0,0,0,1,1H25a1,1,0,0,0,1-1V17a1,1,0,0,0-1-1Z"/><path d="M24,16H8a1,1,0,0,1-1-1V9a8,8,0,0,1,8-8h2a8,8,0,0,1,8,8v6A1,1,0,0,1,24,16ZM9,14H23V9a6,6,0,0,0-6-6H15A6,6,0,0,0,9,9Z"/><path d="M16,23a2,2,0,1,1,2-2A2,2,0,0,1,16,23Zm0-2Z"/><rect height="4" width="2" x="15" y="22"/></g></svg>
            <strong><?=lang('Users.account.ChangePassword'); ?></strong>
        </a>
    </div>
    <div class="menu-item logout">
        <a href="/<?=$global_links['client_account']; ?>?log-out" title="<?=lang('Users.account.LogOut'); ?>">
            <svg viewBox="0 0 32 32"><g><line class="logout-1" x1="15.92" x2="28.92" y1="16" y2="16"/><path d="M23.93,25v3h-16V4h16V7h2V3a1,1,0,0,0-1-1h-18a1,1,0,0,0-1,1V29a1,1,0,0,0,1,1h18a1,1,0,0,0,1-1V25Z"/><line class="logout-1" x1="28.92" x2="24.92" y1="16" y2="20"/><line class="logout-1" x1="28.92" x2="24.92" y1="16" y2="12"/><line class="logout-1" x1="24.92" x2="24.92" y1="8.09" y2="6.09"/><line class="logout-1" x1="24.92" x2="24.92" y1="26" y2="24"/></g></svg>
            <strong><?=lang('Users.account.LogOut'); ?></strong>
        </a>
    </div>
</div>