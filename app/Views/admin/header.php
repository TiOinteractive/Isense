<!doctype html>
<html lang="<?= $lang_code; ?>">
    <head>
        <title><?= lang('Admin.meta.Title'); ?></title>
        <meta charset="UTF-8" />
        <meta name="description" content="<?= lang('Admin.meta.Description'); ?>" />
        <meta name="keywords" content="<?= lang('Admin.meta.Keywords'); ?>" />
        <meta accesskey=""ame="author" content="TiO interactive :: e-business solutions - www.tiointeractive.pl"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="theme-color" content="#1a1d21">
        <link rel="shortcut icon" href="<?= base_url(); ?>adm/img/favicon.ico" />
        <link rel="apple-touch-icon" href="<?= base_url(); ?>adm/img/appleicon.png">
        <?php if (!empty($css_files)): ?>
            <?php foreach ($css_files as $css_file): ?>
                <link rel="stylesheet" href="<?= $css_file; ?>">
            <?php endforeach; ?>
        <?php endif; ?>
    </head>
    <body>
        <div class="top-black">
            <div class="top-gray">
                <div class="flex">
                    <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/modules" title="<?= lang('Admin.modules.Modules'); ?>" class="btn">
                        <svg class="bi bi-boxes" fill="currentColor" viewBox="0 0 16 16" ><path d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434L7.752.066ZM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567L4.25 7.504ZM7.5 9.933l-2.75 1.571v3.134l2.75-1.571V9.933Zm1 3.134 2.75 1.571v-3.134L8.5 9.933v3.134Zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567-2.742 1.567Zm2.242-2.433V3.504L8.5 5.076V8.21l2.75-1.572ZM7.5 8.21V5.076L4.75 3.504v3.134L7.5 8.21ZM5.258 2.643 8 4.21l2.742-1.567L8 1.076 5.258 2.643ZM15 9.933l-2.75 1.571v3.134L15 13.067V9.933ZM3.75 14.638v-3.134L1 9.933v3.134l2.75 1.571Z"/></svg>
                        <?= lang('Admin.modules.Modules'); ?></a>
                    <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/administration" title="<?= lang('Admin.administration.Administration'); ?>" class="btn">
                        <svg viewBox="0 0 256 256" fill="currentColor"><rect fill="none" /><path d="M199.9,194.9a7.8,7.8,0,0,1,1.1,8.5,7.9,7.9,0,0,1-7.2,4.6H22.2a7.9,7.9,0,0,1-7.2-4.6,7.8,7.8,0,0,1,1.1-8.5,118.4,118.4,0,0,1,55.8-37.3,68,68,0,1,1,72.2,0A118.4,118.4,0,0,1,199.9,194.9ZM251.2,154a8,8,0,0,1-7,4,7.6,7.6,0,0,1-4-1.1l-4.6-2.7a24,24,0,0,1-7.6,4.4V164a8,8,0,0,1-16,0v-5.4a24,24,0,0,1-7.6-4.4l-4.6,2.7a7.6,7.6,0,0,1-4,1.1,8,8,0,0,1-4-14.9l4.6-2.7a24.4,24.4,0,0,1,0-8.8l-4.6-2.7a7.9,7.9,0,0,1-3-10.9,8.1,8.1,0,0,1,11-2.9l4.6,2.7a24,24,0,0,1,7.6-4.4V108a8,8,0,0,1,16,0v5.4a24,24,0,0,1,7.6,4.4l4.6-2.7a8.1,8.1,0,0,1,11,2.9,7.9,7.9,0,0,1-3,10.9l-4.6,2.7a24.4,24.4,0,0,1,0,8.8l4.6,2.7A7.9,7.9,0,0,1,251.2,154ZM220,144a8,8,0,1,0-8-8A8,8,0,0,0,220,144Z"/></svg>
                        <?= lang('Admin.administration.Administration'); ?></a>
                    <div class="info">
                        <div class="flex">
                            <div class="service-box">
                                <div class="guarantee<?= !empty($service) && !empty($service['guarantee_date']) && strtotime(date('Y-m-d')) <= strtotime($service['guarantee_date']) ? ' yes' : ' no' ?>"><span class="ico"></span> <?= lang('Admin.Guarantee'); ?>: <?php if (!empty($service) && !empty($service['guarantee_date']) && strtotime(date('Y-m-d')) <= strtotime($service['guarantee_date'])): ?><strong><?= lang('Admin.GuaranteeYes'); ?></strong><span class="i"><?= lang('Admin.ServiceTo') . ' ' . date('d.m.Y', strtotime($service['guarantee_date'])); ?></span><?php else: ?><strong><?= lang('Admin.GuaranteeNo'); ?></strong><?php if (!empty($service['guarantee_date'])): ?><span class="i"><?= lang('Admin.GuaranteePeriodExpired') . ' ' . date('d.m.Y', strtotime($service['guarantee_date'])); ?></span><?php endif; ?><?php endif; ?></div>
                                <div class="support<?= !empty($service) && !empty($service['support_date']) && strtotime(date('Y-m-d')) <= strtotime($service['support_date']) ? ' yes' : ' no'; ?>"><span class="ico"></span> <?= lang('Admin.Support'); ?>: <?php if (!empty($service) && !empty($service['support_date']) && strtotime(date('Y-m-d')) <= strtotime($service['support_date'])): ?><strong><?= lang('Admin.SupportYes'); ?></strong><span class="i"><?= lang('Admin.ServiceTo') . ' ' . date('d.m.Y', strtotime($service['support_date'])); ?><?= !empty($service['support_policy']) ? '<br />' . $service['support_policy'] : ''; ?></span><?php else: ?><strong><?= lang('Admin.SupportNo'); ?></strong><?php if (!empty($service['support_url'])): ?><span class="i"><a href="<?= $service['support_url']; ?>" title="Support techniczny" target="_blank"><?= lang('Admin.SupportUrlTitle'); ?></a></span><?php endif; ?><?php endif; ?></div>
                            </div>
                            <i class="fa-solid fa-user"></i>&nbsp;&nbsp;<?= lang('Admin.LoggedInAs'); ?>: <strong><?= $admin['name']; ?></strong> 
                            &nbsp;&nbsp;/&nbsp;&nbsp;<a class="signout" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/logout" title="Wyloguj"><?= lang('Admin.LogOut'); ?></a>
                            <?php if (!empty($languages) && count($languages) > 1): ?>
                                <div class="lang-switch">
                                    <?php foreach ($languages as $lang): ?>
                                        <a <?php if ($lang['slug'] == $locale): ?>class="active"<?php endif; ?>href="<?= $lang['link']; ?>" title="<?= $lang['name']; ?>"><?= $lang['short_name']; ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="adm-container">
                <div class="left">
                    <div class="logo">
                        <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>" title="<?= lang('Admin.TioCms'); ?>">
                            <svg viewBox="0 0 695.91 257.77"><defs><style>.cls-1{fill:#fff;}.cls-2{letter-spacing:-.06em;}.cls-3{fill:url(#Gradient_bez_nazwy_165);}.cls-4{fill:url(#Gradient_bez_nazwy_167);}.cls-5{letter-spacing:-.01em;}.cls-6{font-family:MyriadPro-Semibold, 'Roboto';font-size:32.06px;font-weight:600;}.cls-7{fill:#f4832c;}</style><linearGradient id="Gradient_bez_nazwy_167" x1="195.18" y1="51.5" x2="195.18" y2="2.28" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f08102"/><stop offset=".74" stop-color="#f59a00"/><stop offset="1" stop-color="#f7a400"/></linearGradient><linearGradient id="Gradient_bez_nazwy_165" x1="567.36" y1="143.37" x2="567.36" y2="87.46" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f08102"/><stop offset=".74" stop-color="#f59a00"/><stop offset="1" stop-color="#f7a400"/></linearGradient></defs><g id="Warstwa_1-2"><path class="cls-1" d="M385.06,79.09c-7.26-6.88-15.5-10.3-24.82-10.3h-81.47c-9.65,0-18.05,3.42-25.23,10.3-7.14,6.84-10.72,14.67-10.72,23.45v30.55c0,8.93,3.57,16.76,10.72,23.48,7.41,6.73,15.81,10.07,25.23,10.07h81.47c9.39,0,17.63-3.34,24.82-10.07,7.29-6.73,10.94-14.55,10.94-23.48v-30.55c0-8.78-3.65-16.61-10.94-23.45Zm-44.5,54c0,3.19-1.33,6-3.99,8.44-2.66,2.43-5.66,3.65-8.97,3.65h-16.38c-3.38,0-6.42-1.21-9.08-3.65-2.66-2.43-3.99-5.25-3.99-8.44v-30.55c0-3.08,1.33-5.93,3.99-8.51,2.58-2.51,5.62-3.76,9.08-3.76h16.38c3.38,0,6.38,1.25,8.97,3.76,2.66,2.58,3.99,5.43,3.99,8.51v30.55Z"/><rect class="cls-1" x="168.13" y="68.79" width="54.11" height="97.85"/><path class="cls-1" d="M102.5,145.42c-3.16,0-6.13-1.26-8.84-3.79-2.6-2.45-3.9-5.28-3.9-8.44v-42.33h47.94v-21.44h-47.94v-30.06H35.6v30.06H0v21.44H35.6v42.33c0,9.03,3.53,16.8,10.52,23.38,7.14,6.73,15.35,10.07,24.68,10.07h82.8v-21.22h-51.1Z"/><circle class="cls-4" cx="195.18" cy="27.5" r="27.5"/><g><g><path d="M511.88,163.5c-4.58,2.29-13.73,4.58-25.46,4.58-27.17,0-47.62-17.16-47.62-48.76s20.45-50.62,50.34-50.62c12.01,0,19.59,2.57,22.88,4.29l-3,10.15c-4.72-2.29-11.44-4-19.45-4-22.59,0-37.61,14.44-37.61,39.76,0,23.6,13.59,38.75,37.04,38.75,7.58,0,15.3-1.57,20.31-4l2.57,9.87Z"/><path d="M608.69,124.32c-.71-13.44-1.57-29.6-1.43-41.61h-.43c-3.29,11.3-7.29,23.31-12.16,36.61l-17.02,46.76h-9.44l-15.59-45.9c-4.58-13.59-8.44-26.03-11.15-37.47h-.29c-.29,12.01-1,28.17-1.86,42.62l-2.57,41.33h-11.87l6.72-96.39h15.87l16.45,46.62c4,11.87,7.29,22.45,9.72,32.46h.43c2.43-9.72,5.86-20.31,10.15-32.46l17.16-46.62h15.87l6.01,96.39h-12.16l-2.43-42.33Z"/><path d="M640.71,151.49c5.58,3.43,13.73,6.29,22.31,6.29,12.73,0,20.16-6.72,20.16-16.45,0-9.01-5.15-14.16-18.16-19.16-15.73-5.58-25.46-13.73-25.46-27.31,0-15.02,12.44-26.17,31.18-26.17,9.87,0,17.02,2.29,21.31,4.72l-3.43,10.15c-3.15-1.72-9.58-4.58-18.3-4.58-13.16,0-18.16,7.87-18.16,14.44,0,9.01,5.86,13.44,19.16,18.59,16.3,6.29,24.6,14.16,24.6,28.32,0,14.87-11.01,27.74-33.75,27.74-9.3,0-19.45-2.72-24.6-6.15l3.15-10.44Z"/></g><g><path class="cls-3" d="M511.88,163.5c-4.58,2.29-13.73,4.58-25.46,4.58-27.17,0-47.62-17.16-47.62-48.76s20.45-50.62,50.34-50.62c12.01,0,19.59,2.57,22.88,4.29l-3,10.15c-4.72-2.29-11.44-4-19.45-4-22.59,0-37.61,14.44-37.61,39.76,0,23.6,13.59,38.75,37.04,38.75,7.58,0,15.3-1.57,20.31-4l2.57,9.87Z"/><path class="cls-3" d="M608.69,124.32c-.71-13.44-1.57-29.6-1.43-41.61h-.43c-3.29,11.3-7.29,23.31-12.16,36.61l-17.02,46.76h-9.44l-15.59-45.9c-4.58-13.59-8.44-26.03-11.15-37.47h-.29c-.29,12.01-1,28.17-1.86,42.62l-2.57,41.33h-11.87l6.72-96.39h15.87l16.45,46.62c4,11.87,7.29,22.45,9.72,32.46h.43c2.43-9.72,5.86-20.31,10.15-32.46l17.16-46.62h15.87l6.01,96.39h-12.16l-2.43-42.33Z"/><path class="cls-3" d="M640.71,151.49c5.58,3.43,13.73,6.29,22.31,6.29,12.73,0,20.16-6.72,20.16-16.45,0-9.01-5.15-14.16-18.16-19.16-15.73-5.58-25.46-13.73-25.46-27.31,0-15.02,12.44-26.17,31.18-26.17,9.87,0,17.02,2.29,21.31,4.72l-3.43,10.15c-3.15-1.72-9.58-4.58-18.3-4.58-13.16,0-18.16,7.87-18.16,14.44,0,9.01,5.86,13.44,19.16,18.59,16.3,6.29,24.6,14.16,24.6,28.32,0,14.87-11.01,27.74-33.75,27.74-9.3,0-19.45-2.72-24.6-6.15l3.15-10.44Z"/></g></g><rect class="cls-7" x="526.95" y="198.79" width="168.26" height="58.98" rx="29.49" ry="29.49"/><text class="cls-6" transform="translate(554.4 239.15)"><tspan class="cls-5" x="0" y="0">v</tspan><tspan x="18.34" y="0">e</tspan><tspan class="cls-2" x="37.47" y="0">r</tspan><tspan x="48.47" y="0">. 8.0</tspan></text></g></svg>
                        </a>
                    </div>
                </div>
                <div class="top-modules">
                    <div class="flex">
                        <?php if (!empty($main_modules)): ?>
                            <?php foreach ($main_modules as $m_module): ?>
                                <div class="module<?php if(!empty($url_array[1]) and $url_array[1]==strtolower($m_module['slug'])):?> active<?php endif;?>">
                                    <div class="bc">
                                        <div class="ico"><a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/<?= strtolower($m_module['slug']); ?>" title="<?= $m_module['name']; ?>"><?= $m_module['ico']; ?></a></div>
                                        <h3><a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/<?= strtolower($m_module['slug']); ?>" title="<?= $m_module['name']; ?>"><?= $m_module['name']; ?></a></h3>
										<?php if(!empty($m_module['comments'])):?>
										  <div class="cnt"><a href="<?=$m_module['comments_link'];?>"><?=$m_module['comments'];?></a></div>
										<?php endif;?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="adm-container">
                <div class="flex">