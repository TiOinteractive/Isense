<?php
// Ekran bledu produkcyjnego (publiczny). MUSI byc kuloodporny — blad 500 bywa
// spowodowany awaria DB, wiec ta strona nie odpytuje bazy, nie laduje partiali
// iSense ani zadnego zewnetrznego zasobu. Wszystko inline, logo z pliku statycznego.
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Błąd serwera</title>
    <style>
        body {margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; color: #1D1D1F; background: #fff; -webkit-font-smoothing: antialiased;}
        .topbar {background: #fff; border-bottom: 1px solid #E5E5EA; text-align: center; padding: 20px 15px;}
        .topbar .logo {display: inline-block; line-height: 0;}
        .topbar .logo img {height: 40px; width: auto;}
        .error-box {text-align: center; padding: 60px 15px; min-height: calc(100vh - 81px); display: flex; align-items: center; justify-content: center; box-sizing: border-box; background: #F5F5F7;}
        .error-box .inner {max-width: 600px;}
        .error-box .icon {width: 96px; height: 96px; margin: 0 auto 30px; color: #3b81f7;}
        .error-box .icon svg {width: 100%; height: 100%;}
        .error-box h1 {font-size: 32px; font-weight: 700; margin: 0 0 20px; color: #1D1D1F;}
        .error-box p {font-size: 16px; line-height: 1.6; color: #6E6E73; margin: 0;}
        .error-box .back {display: inline-block; margin-top: 32px; background: #3b81f7; color: #fff; text-decoration: none; padding: 15px 32px; border-radius: 4px; font-size: 16px; font-weight: 500;}
        .error-box .back:hover {background: #2563eb;}
        @media (max-width: 767px) {
            .error-box {padding: 40px 15px;}
            .error-box .icon {width: 80px; height: 80px; margin-bottom: 20px;}
            .error-box h1 {font-size: 24px;}
        }
    </style>
</head>
<body>
    <div class="topbar">
        <a class="logo" href="/" title="iSense">
            <img src="/assets/isense/img/opt/logo-640.webp" alt="iSense" width="640" height="156">
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
            <h1>Coś poszło nie tak</h1>
            <p>Przepraszamy za utrudnienia. Pracujemy nad rozwiązaniem problemu.<br>Spróbuj odświeżyć stronę za kilka minut.</p>
            <a class="back" href="/">Wróć na stronę główną</a>
        </div>
    </div>
</body>
</html>
