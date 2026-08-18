<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Debug\BaseExceptionHandler;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Paths;
use Throwable;

class NotifyingExceptionHandler extends BaseExceptionHandler implements ExceptionHandlerInterface
{
    use ResponseTrait;

    private ?RequestInterface $request = null;
    private ?ResponseInterface $response = null;

    public function handle(
        Throwable $exception,
        RequestInterface $request,
        ResponseInterface $response,
        int $statusCode,
        int $exitCode,
    ): void {
        $this->request  = $request;
        $this->response = $response;

        // Wysyłka maila przed renderowaniem strony błędu
        if ($statusCode >= 500) {
            $this->sendErrorEmail($exception, $request, $statusCode);
        }

        // --- Standardowa logika ExceptionHandler z CI4 ---
        if ($request instanceof IncomingRequest) {
            try {
                $response->setStatusCode($statusCode);
            } catch (HTTPException) {
                $statusCode = 500;
                $response->setStatusCode($statusCode);
            }

            if (! headers_sent()) {
                header(
                    sprintf(
                        'HTTP/%s %s %s',
                        $request->getProtocolVersion(),
                        $response->getStatusCode(),
                        $response->getReasonPhrase(),
                    ),
                    true,
                    $statusCode,
                );
                header('Content-Type: text/html; charset=UTF-8');
            }

            if (! str_contains($request->getHeaderLine('accept'), 'text/html')) {
                $data = $this->isDisplayErrorsEnabled()
                    ? $this->collectVars($exception, $statusCode)
                    : '';

                $this->respond($data, $statusCode)->send();

                if (ENVIRONMENT !== 'testing') {
                    exit($exitCode);
                }

                return;
            }
        }

        $addPath = ($request instanceof IncomingRequest ? 'html' : 'cli') . DIRECTORY_SEPARATOR;
        $path    = $this->viewPath . $addPath;
        $altPath = rtrim((new Paths())->viewDirectory, '\\/ ')
            . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . $addPath;

        $view    = $this->determineView($exception, $path, $statusCode);
        $altView = $this->determineView($exception, $altPath, $statusCode);

        $viewFile = null;
        if (is_file($path . $view)) {
            $viewFile = $path . $view;
        } elseif (is_file($altPath . $altView)) {
            $viewFile = $altPath . $altView;
        }

        $this->render($exception, $statusCode, $viewFile);

        if (ENVIRONMENT !== 'testing') {
            exit($exitCode);
        }
    }

    private function sendErrorEmail(Throwable $exception, RequestInterface $request, int $statusCode): void
    {
        try {
            helper('error_notification');

            $errorKey = md5(get_class($exception) . $exception->getFile() . $exception->getLine());

            if (! shouldSendErrorNotification($errorKey, 3600)) {
                return;
            }

            $url = ($request instanceof IncomingRequest)
                ? (string) $request->getUri()
                : 'CLI';
            $method = ($request instanceof IncomingRequest)
                ? $request->getMethod()
                : 'CLI';

            $subject = "HTTP {$statusCode}: " . mb_substr($exception->getMessage(), 0, 80);

            $body = buildErrorEmailBody($exception, "Błąd HTTP {$statusCode}", [
                'URL'    => $url,
                'Metoda' => $method,
            ]);

            sendErrorNotificationEmail($subject, $body);
        } catch (Throwable $emailError) {
            log_message('critical', 'Error notification email failed during exception handling: {message}', [
                'message' => $emailError->getMessage(),
            ]);
        }
    }

    protected function determineView(
        Throwable $exception,
        string $templatePath,
        int $statusCode = 500,
    ): string {
        $view = 'production.php';

        if ($this->isDisplayErrorsEnabled()) {
            $view = 'error_exception.php';
        }

        if ($exception instanceof PageNotFoundException) {
            return 'error_404.php';
        }

        $templatePath = rtrim($templatePath, '\\/ ') . DIRECTORY_SEPARATOR;

        if (is_file($templatePath . 'error_' . $statusCode . '.php')) {
            return 'error_' . $statusCode . '.php';
        }

        return $view;
    }

    private function isDisplayErrorsEnabled(): bool
    {
        return in_array(
            strtolower(ini_get('display_errors')),
            ['1', 'true', 'on', 'yes'],
            true,
        );
    }
}
