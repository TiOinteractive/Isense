<div class="left-cont">
    <div class="left-menu-toogle-box">
        <button class="left-menu-toogle<?php if(!empty($_COOKIE['LeftMenuM']) && $_COOKIE['LeftMenuM']=="hide") { echo ' toogle'; }; ?>">
            <svg fill="none" viewBox="0 0 48 48"><rect fill="white" fill-opacity="0.01" height="48" width="48"/><path d="M8 10.5H40" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"/><path d="M24 19.5H40" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"/><path d="M24 28.5H40" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"/><path d="M8 37.5H40" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"/><path d="M16 19L8 24L16 29V19Z" fill="none" stroke="#fff" stroke-linejoin="round" stroke-width="3"/></svg>
        </button>
    </div>
    <?= view('admin/page/menu_left_submodule_list', array('left_submodules' => $left_submodules)); ?>
    <?= view('admin/page/menu_left_page_list', array('left_pages' => $left_pages)); ?>
</div>