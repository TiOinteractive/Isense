<?php
// Ekran bledu produkcyjnego (publiczny). MUSI byc kuloodporny — blad 500
// bywa spowodowany awaria DB, wiec pobranie logo nie moze rzucic kolejnego bledu.
$logoSvg = '';
try {
    $row = (new \App\Models\SettingsModel())->where('name', 'logo_svg')->first();
    if (!empty($row['value'])) {
        $logoSvg = $row['value'];
    }
} catch (\Throwable $e) {
    $logoSvg = '';
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Błąd serwera</title>
    <style>
        body {margin: 0; font-family: 'Roboto', Arial, sans-serif; color: #1c1d1f; background: #fff;}
        .topbar {background: url('https://www.resinet.pl/assets/gfx/res_top.png') #1c1d1f no-repeat top center; background-size: cover; border-bottom: 8px solid #ffc000; text-align: center; padding: 22px 15px;}
        .topbar .logo {display: inline-block; line-height: 0;}
        .topbar .logo svg, .topbar .logo img {height: 48px; width: auto;}
        .error-box {text-align: center; padding: 60px 15px; min-height: calc(100vh - 92px); display: flex; align-items: center; justify-content: center; box-sizing: border-box;}
        .error-box .inner {max-width: 600px;}
        .error-box .icon {width: 100px; height: 100px; margin: 0 auto 30px; color: #fcbf0b;}
        .error-box .icon svg {width: 100%; height: 100%;}
        .error-box h2 {font-size: 32px; font-weight: 700; margin: 0 0 20px; color: #1c1d1f;}
        .error-box p {font-size: 16px; line-height: 1.6; color: #787878; margin: 0;}
        @media (max-width: 767px) {
            .error-box {padding: 40px 15px;}
            .error-box .icon {width: 80px; height: 80px; margin-bottom: 20px;}
            .error-box h2 {font-size: 24px;}
        }
    </style>
</head>
<body>
    <div class="topbar">
        <a class="logo" href="/" title="RESinet.pl">
            <?php if ($logoSvg !== ''): ?>
                <?= $logoSvg ?>
            <?php else: ?>
                <img src="/assets/gfx/logo_resinet.png" alt="RESinet.pl" width="132" height="26">
            <?php endif; ?>
        </a>
    </div>
    <div class="error-box">
        <div class="inner">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h2>Coś poszło nie tak</h2>
            <p>Przepraszamy za utrudnienia. Pracujemy nad rozwiązaniem problemu.<br>Spróbuj odświeżyć stronę za kilka minut.</p>
        </div>
    </div>
</body>
</html>
