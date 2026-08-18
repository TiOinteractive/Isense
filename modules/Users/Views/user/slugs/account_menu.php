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
    <?php /*
    <div class="menu-item<?php if(!empty($data['link']) && $data['link'] == 'comments'): ?> active<?php endif; ?>">
        <a href="/<?=$global_links['client_account']; ?>/g/comments" title="<?=lang('Users.account.YourComments'); ?>"><?=lang('Users.account.YourComments'); ?></a>
    </div>
     */ ?>
    <div class="menu-item<?php if(!empty($data['link']) && $data['link'] == 'copy'): ?> active<?php endif; ?>">
        <a href="/<?=$global_links['client_account']; ?>/g/copy" title="<?=lang('Users.account.DataCopy'); ?>">
            <svg viewBox="0 0 64 64"><g/><g><path d="M51.588,8.008H21.569c-2.414,0-4.379,1.964-4.379,4.379v4.815h-4.818c-2.413,0-4.376,1.963-4.376,4.376v30.024   c0,2.413,1.963,4.376,4.376,4.376h30.024c2.413,0,4.376-1.963,4.376-4.376v-4.818h4.815c2.414,0,4.379-1.965,4.379-4.379V12.387   C55.967,9.972,54.002,8.008,51.588,8.008z M44.772,51.602c0,1.311-1.065,2.376-2.376,2.376H12.372   c-1.311,0-2.376-1.065-2.376-2.376V21.578c0-1.311,1.065-2.376,2.376-2.376h30.024c1.311,0,2.376,1.065,2.376,2.376V51.602z    M53.967,42.405c0,1.312-1.067,2.379-2.379,2.379h-4.815V21.578c0-2.413-1.963-4.376-4.376-4.376H19.19v-4.815   c0-1.312,1.067-2.379,2.379-2.379h30.019c1.312,0,2.379,1.067,2.379,2.379V42.405z"/></g></svg>
            <strong><?=lang('Users.account.DataCopy'); ?></strong>
        </a>
    </div>
    <div class="menu-item<?php if(!empty($data['link']) && $data['link'] == 'del'): ?> active<?php endif; ?>">
        <a href="/<?=$global_links['client_account']; ?>/g/del" title="<?=lang('Users.account.DeleteAccount'); ?>">
            <svg viewBox="0 0 50 50"><rect fill="none"/><path d="M19,6V3c0-1.104,0.896-2,2-2  h8c1.104,0,2,0.896,2,2v3" fill="none" stroke="#000000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/><path d="M40,6l-3.693,41.426  C36.229,48.299,35.469,49,34.608,49H15.391c-0.86,0-1.621-0.701-1.699-1.574L10,6" fill="none" stroke="#000000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/><line fill="none" stroke="#000000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" x1="8" x2="42" y1="6" y2="6"/><line fill="none" stroke="#000000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" x1="25" x2="25" y1="11" y2="44"/><line fill="none" stroke="#000000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" x1="31" x2="31" y1="11" y2="44"/><line fill="none" stroke="#000000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" x1="19" x2="19" y1="11" y2="44"/></svg>
            <strong><?=lang('Users.account.DeleteAccount'); ?></strong>
        </a>
    </div>
    <div class="menu-item logout">
        <a href="/<?=$global_links['client_account']; ?>?log-out" title="<?=lang('Users.account.LogOut'); ?>">
            <svg viewBox="0 0 32 32"><g><line class="logout-1" x1="15.92" x2="28.92" y1="16" y2="16"/><path d="M23.93,25v3h-16V4h16V7h2V3a1,1,0,0,0-1-1h-18a1,1,0,0,0-1,1V29a1,1,0,0,0,1,1h18a1,1,0,0,0,1-1V25Z"/><line class="logout-1" x1="28.92" x2="24.92" y1="16" y2="20"/><line class="logout-1" x1="28.92" x2="24.92" y1="16" y2="12"/><line class="logout-1" x1="24.92" x2="24.92" y1="8.09" y2="6.09"/><line class="logout-1" x1="24.92" x2="24.92" y1="26" y2="24"/></g></svg>
            <strong><?=lang('Users.account.LogOut'); ?></strong>
        </a>
    </div>
</div>