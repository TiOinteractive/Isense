<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= lang('Admin.TioCMS'); ?></title>
        <link href="/adm/css/singin.css" rel="stylesheet" type="text/css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    </head>
    <body class="signin-panel">
        <div class="container">
            <div class="center-box">
                <div class="center-box-cont">
                    <div class="logo">
                        <a href="/<?=getenv('ADMIN_PANEL_SLUG'); ?>" id="logo">
                            <svg viewBox="0 0 695.91 257.77"><defs><style>.cls-1{fill:#fff;}.cls-2{letter-spacing:-.06em;}.cls-3{fill:url(#Gradient_bez_nazwy_165);}.cls-4{fill:url(#Gradient_bez_nazwy_167);}.cls-5{letter-spacing:-.01em;}.cls-6{font-family:MyriadPro-Semibold, 'Roboto';font-size:32.06px;font-weight:600;}.cls-7{fill:#f4832c;}</style><linearGradient id="Gradient_bez_nazwy_167" x1="195.18" y1="51.5" x2="195.18" y2="2.28" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f08102"/><stop offset=".74" stop-color="#f59a00"/><stop offset="1" stop-color="#f7a400"/></linearGradient><linearGradient id="Gradient_bez_nazwy_165" x1="567.36" y1="143.37" x2="567.36" y2="87.46" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f08102"/><stop offset=".74" stop-color="#f59a00"/><stop offset="1" stop-color="#f7a400"/></linearGradient></defs><g id="Warstwa_1-2"><path class="cls-1" d="M385.06,79.09c-7.26-6.88-15.5-10.3-24.82-10.3h-81.47c-9.65,0-18.05,3.42-25.23,10.3-7.14,6.84-10.72,14.67-10.72,23.45v30.55c0,8.93,3.57,16.76,10.72,23.48,7.41,6.73,15.81,10.07,25.23,10.07h81.47c9.39,0,17.63-3.34,24.82-10.07,7.29-6.73,10.94-14.55,10.94-23.48v-30.55c0-8.78-3.65-16.61-10.94-23.45Zm-44.5,54c0,3.19-1.33,6-3.99,8.44-2.66,2.43-5.66,3.65-8.97,3.65h-16.38c-3.38,0-6.42-1.21-9.08-3.65-2.66-2.43-3.99-5.25-3.99-8.44v-30.55c0-3.08,1.33-5.93,3.99-8.51,2.58-2.51,5.62-3.76,9.08-3.76h16.38c3.38,0,6.38,1.25,8.97,3.76,2.66,2.58,3.99,5.43,3.99,8.51v30.55Z"/><rect class="cls-1" x="168.13" y="68.79" width="54.11" height="97.85"/><path class="cls-1" d="M102.5,145.42c-3.16,0-6.13-1.26-8.84-3.79-2.6-2.45-3.9-5.28-3.9-8.44v-42.33h47.94v-21.44h-47.94v-30.06H35.6v30.06H0v21.44H35.6v42.33c0,9.03,3.53,16.8,10.52,23.38,7.14,6.73,15.35,10.07,24.68,10.07h82.8v-21.22h-51.1Z"/><circle class="cls-4" cx="195.18" cy="27.5" r="27.5"/><g><g><path d="M511.88,163.5c-4.58,2.29-13.73,4.58-25.46,4.58-27.17,0-47.62-17.16-47.62-48.76s20.45-50.62,50.34-50.62c12.01,0,19.59,2.57,22.88,4.29l-3,10.15c-4.72-2.29-11.44-4-19.45-4-22.59,0-37.61,14.44-37.61,39.76,0,23.6,13.59,38.75,37.04,38.75,7.58,0,15.3-1.57,20.31-4l2.57,9.87Z"/><path d="M608.69,124.32c-.71-13.44-1.57-29.6-1.43-41.61h-.43c-3.29,11.3-7.29,23.31-12.16,36.61l-17.02,46.76h-9.44l-15.59-45.9c-4.58-13.59-8.44-26.03-11.15-37.47h-.29c-.29,12.01-1,28.17-1.86,42.62l-2.57,41.33h-11.87l6.72-96.39h15.87l16.45,46.62c4,11.87,7.29,22.45,9.72,32.46h.43c2.43-9.72,5.86-20.31,10.15-32.46l17.16-46.62h15.87l6.01,96.39h-12.16l-2.43-42.33Z"/><path d="M640.71,151.49c5.58,3.43,13.73,6.29,22.31,6.29,12.73,0,20.16-6.72,20.16-16.45,0-9.01-5.15-14.16-18.16-19.16-15.73-5.58-25.46-13.73-25.46-27.31,0-15.02,12.44-26.17,31.18-26.17,9.87,0,17.02,2.29,21.31,4.72l-3.43,10.15c-3.15-1.72-9.58-4.58-18.3-4.58-13.16,0-18.16,7.87-18.16,14.44,0,9.01,5.86,13.44,19.16,18.59,16.3,6.29,24.6,14.16,24.6,28.32,0,14.87-11.01,27.74-33.75,27.74-9.3,0-19.45-2.72-24.6-6.15l3.15-10.44Z"/></g><g><path class="cls-3" d="M511.88,163.5c-4.58,2.29-13.73,4.58-25.46,4.58-27.17,0-47.62-17.16-47.62-48.76s20.45-50.62,50.34-50.62c12.01,0,19.59,2.57,22.88,4.29l-3,10.15c-4.72-2.29-11.44-4-19.45-4-22.59,0-37.61,14.44-37.61,39.76,0,23.6,13.59,38.75,37.04,38.75,7.58,0,15.3-1.57,20.31-4l2.57,9.87Z"/><path class="cls-3" d="M608.69,124.32c-.71-13.44-1.57-29.6-1.43-41.61h-.43c-3.29,11.3-7.29,23.31-12.16,36.61l-17.02,46.76h-9.44l-15.59-45.9c-4.58-13.59-8.44-26.03-11.15-37.47h-.29c-.29,12.01-1,28.17-1.86,42.62l-2.57,41.33h-11.87l6.72-96.39h15.87l16.45,46.62c4,11.87,7.29,22.45,9.72,32.46h.43c2.43-9.72,5.86-20.31,10.15-32.46l17.16-46.62h15.87l6.01,96.39h-12.16l-2.43-42.33Z"/><path class="cls-3" d="M640.71,151.49c5.58,3.43,13.73,6.29,22.31,6.29,12.73,0,20.16-6.72,20.16-16.45,0-9.01-5.15-14.16-18.16-19.16-15.73-5.58-25.46-13.73-25.46-27.31,0-15.02,12.44-26.17,31.18-26.17,9.87,0,17.02,2.29,21.31,4.72l-3.43,10.15c-3.15-1.72-9.58-4.58-18.3-4.58-13.16,0-18.16,7.87-18.16,14.44,0,9.01,5.86,13.44,19.16,18.59,16.3,6.29,24.6,14.16,24.6,28.32,0,14.87-11.01,27.74-33.75,27.74-9.3,0-19.45-2.72-24.6-6.15l3.15-10.44Z"/></g></g><rect class="cls-7" x="526.95" y="198.79" width="168.26" height="58.98" rx="29.49" ry="29.49"/><text class="cls-6" transform="translate(554.4 239.15)"><tspan class="cls-5" x="0" y="0">v</tspan><tspan x="18.34" y="0">e</tspan><tspan class="cls-2" x="37.47" y="0">r</tspan><tspan x="48.47" y="0">. 8.0</tspan></text></g></svg>
                        </a>
                    </div>
                    <div class="inside">
                        <form action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/remindPass" method="post">
                            <div class="before"></div>
                            <h1><?=lang('Signin.PasswordRecovery'); ?></h1>
                            <?php if (session()->getFlashdata('msg-send')): ?>   
                                <div class="alert alert-ok">
                                    <?= session()->getFlashdata('msg-send') ?>
                                </div>	
                            <?php else: ?>	
                                <?php if (session()->getFlashdata('msg')): ?>
                                    <div class="alert alert-warning">
                                        <?= session()->getFlashdata('msg') ?>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label><?= lang('Signin.EmailAddress'); ?>:</label>
                                    <input name="email" value="<?= set_value('email') ?>" placeholder="<?= lang('Signin.emaillostpassword'); ?>" class="form-control trans200" >
                                </div>
                                <div class="center">
                                    <button type="submit" class="btn"><?= lang('Signin.lostpasswordsend'); ?></button>
                                </div>   
                            <?php endif; ?>
                            <div class="after"></div>  
                        </form>
                    </div>  
                </div>
            </div>
            <?= $this->include('admin/signin-footer') ?>
        </div>
    </body>
</html>