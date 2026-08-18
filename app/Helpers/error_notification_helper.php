<?php

/**
 * Sprawdza czy powiadomienie o błędzie powinno zostać wysłane (rate limiting).
 * Używa file cache aby zapobiec zalewaniu skrzynki mailowej tym samym błędem.
 */
function shouldSendErrorNotification(string $errorKey, int $cooldownSeconds = 3600): bool
{
    $cache = \App\Libraries\CacheSubdir::get('errors');
    $cacheKey = 'error_notif_' . $errorKey;

    if ($cache->get($cacheKey) !== null) {
        return false;
    }

    $cache->save($cacheKey, time(), $cooldownSeconds);
    return true;
}

/**
 * Zwraca domenę serwisu do użycia w tematach i nagłówkach maili.
 */
function getErrorDomain(): string
{
    if (!empty($_SERVER['SERVER_NAME'])) {
        return $_SERVER['SERVER_NAME'];
    }
    if (!empty($_SERVER['HTTP_HOST'])) {
        return preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']);
    }
    $baseURL = env('app.baseURL', '');
    if ($baseURL !== '') {
        $parsed = parse_url($baseURL, PHP_URL_HOST);
        if ($parsed) {
            return $parsed;
        }
    }
    return php_uname('n');
}

/**
 * Buduje sformatowaną treść maila HTML z informacjami o błędzie (styl CI4).
 */
function buildErrorEmailBody(\Throwable $e, string $context, array $extraInfo = []): string
{
    $domain = esc(getErrorDomain());
    $date = esc(date('Y-m-d H:i:s'));
    $server = esc($_SERVER['SERVER_NAME'] ?? php_uname('n'));
    $exceptionClass = esc(get_class($e));
    $exceptionCode = $e->getCode() ? ' #' . esc((string) $e->getCode()) : '';
    $message = esc($e->getMessage());
    $file = esc($e->getFile());
    $line = esc((string) $e->getLine());
    $trace = esc($e->getTraceAsString());
    $contextEsc = esc($context);
    $phpVersion = esc(PHP_VERSION);

    // Info rows
    $infoRows = '';
    foreach ($extraInfo as $label => $value) {
        $infoRows .= '<tr><td style="padding:6px 12px;font-weight:bold;white-space:nowrap;border-bottom:1px solid #e7e7e7;">' . esc($label) . '</td>'
            . '<td style="padding:6px 12px;border-bottom:1px solid #e7e7e7;">' . esc($value) . '</td></tr>';
    }
    $infoRows .= '<tr><td style="padding:6px 12px;font-weight:bold;white-space:nowrap;border-bottom:1px solid #e7e7e7;">Data</td>'
        . '<td style="padding:6px 12px;border-bottom:1px solid #e7e7e7;">' . $date . '</td></tr>';
    $infoRows .= '<tr><td style="padding:6px 12px;font-weight:bold;white-space:nowrap;border-bottom:1px solid #e7e7e7;">Serwer</td>'
        . '<td style="padding:6px 12px;border-bottom:1px solid #e7e7e7;">' . $server . '</td></tr>';
    $infoRows .= '<tr><td style="padding:6px 12px;font-weight:bold;white-space:nowrap;border-bottom:1px solid #e7e7e7;">PHP</td>'
        . '<td style="padding:6px 12px;border-bottom:1px solid #e7e7e7;">' . $phpVersion . '</td></tr>';

    // Previous exceptions chain
    $causedBy = '';
    $prev = $e->getPrevious();
    while ($prev) {
        $prevClass = esc(get_class($prev));
        $prevCode = $prev->getCode() ? ' #' . esc((string) $prev->getCode()) : '';
        $prevMsg = esc($prev->getMessage());
        $prevFile = esc($prev->getFile());
        $prevLine = esc((string) $prev->getLine());
        $causedBy .= <<<HTML
        <div style="margin-top:16px;padding:12px 16px;background:#fff3cd;border-left:4px solid #ffc107;border-radius:4px;">
            <strong>Caused by: {$prevClass}{$prevCode}</strong><br>
            {$prevMsg}<br>
            <span style="color:#666;font-size:13px;">{$prevFile}:{$prevLine}</span>
        </div>
        HTML;
        $prev = $prev->getPrevious();
    }

    // Source code context
    $sourceHtml = '';
    $filePath = $e->getFile();
    $errorLine = $e->getLine();
    if (is_file($filePath) && is_readable($filePath)) {
        $lines = @file($filePath);
        if ($lines !== false) {
            $start = max(0, $errorLine - 8);
            $end = min(count($lines), $errorLine + 7);
            $sourceLines = '';
            for ($i = $start; $i < $end; $i++) {
                $num = $i + 1;
                $lineContent = esc(rtrim($lines[$i]));
                $numPadded = str_pad((string) $num, 4, ' ', STR_PAD_LEFT);
                if ($num === $errorLine) {
                    $sourceLines .= '<span style="display:block;background:#222;color:#fff;">' . $numPadded . ' ' . $lineContent . '</span>';
                } else {
                    $sourceLines .= '<span style="display:block;">' . $numPadded . ' ' . $lineContent . '</span>';
                }
            }
            $sourceHtml = <<<HTML
            <div style="margin:16px 0;">
                <p style="margin:0 0 8px 0;font-size:14px;"><strong>{$file}</strong> w linii <strong>{$line}</strong></p>
                <pre style="background:#343434;color:#c7c7c7;padding:12px 16px;border-radius:5px;font-family:Menlo,Monaco,Consolas,'Courier New',monospace;font-size:13px;line-height:1.5;overflow-x:auto;margin:0;">{$sourceLines}</pre>
            </div>
            HTML;
        }
    }

    return <<<HTML
    <div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;color:#555;margin:0;padding:0;max-width:900px;">
        <!-- Orange header bar -->
        <div style="background:#DC4814;color:#fff;padding:10px 20px;text-align:center;font-size:13px;border-radius:5px 5px 0 0;">
            {$domain} &mdash; {$contextEsc} &mdash; {$date} &mdash; PHP {$phpVersion}
        </div>

        <!-- Exception title -->
        <div style="background:#ededee;padding:20px;margin:0;">
            <h1 style="margin:0;font-size:22px;font-weight:500;color:#222;">{$exceptionClass}{$exceptionCode}</h1>
            <p style="margin:8px 0 0 0;font-size:15px;color:#555;line-height:1.5;">{$message}</p>
        </div>

        <!-- Details table -->
        <div style="padding:16px 20px;">
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                {$infoRows}
            </table>
        </div>

        <!-- Source code -->
        <div style="padding:0 20px;">
            {$sourceHtml}
        </div>

        {$causedBy}

        <!-- Stack trace -->
        <div style="padding:16px 20px;">
            <h3 style="margin:0 0 8px 0;font-size:15px;color:#222;">Stack Trace</h3>
            <pre style="background:#343434;color:#c7c7c7;padding:12px 16px;border-radius:5px;font-family:Menlo,Monaco,Consolas,'Courier New',monospace;font-size:12px;line-height:1.6;overflow-x:auto;margin:0;white-space:pre-wrap;word-wrap:break-word;">{$trace}</pre>
        </div>
    </div>
    HTML;
}

/**
 * Wysyła mail z powiadomieniem o błędzie do administratorów technicznych.
 */
function sendErrorNotificationEmail(string $subject, string $body): bool
{
    try {
        $emailService = \Config\Services::email();
        $emailService->clear(true);

        $domain = getErrorDomain();
        $emailService->setFrom(
            env('email.fromEmail', 'powiadomienia@resinet.pl'),
            $domain . ' — Error — resinet.pl'
        );

        $adminModel = new \App\Models\AdminModel();
        $recipients = $adminModel->getTechnicalAdminEmails();
        if (empty($recipients)) {
            // Brak adminów technicznych (technical_admin=1, active=1) — nie wysyłamy maila.
            return false;
        }

        $emailService->setTo($recipients);
        $emailService->setSubject($subject);
        $emailService->setMailType('html');
        $emailService->setMessage($body);

        return $emailService->send();
    } catch (\Throwable $mailError) {
        log_message('critical', 'Error notification email failed: {message}', [
            'message' => $mailError->getMessage(),
        ]);
        return false;
    }
}
