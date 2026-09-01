<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Generuje zoptymalizowane warianty WebP grafik motywu iSense
 * (public/assets/isense/img) do podkatalogu `opt/` + manifest.json.
 *
 * Źródła zostają nietknięte — są materiałem wyjściowym. Front serwuje
 * warianty przez helper isense_img(), który czyta manifest i buduje srcset.
 *
 *   php spark isense:images            # tylko brakujące/nieaktualne
 *   php spark isense:images --force    # przelicz wszystko od nowa
 */
class OptimizeIsenseImages extends BaseCommand
{
    protected $group       = 'iSense';
    protected $name        = 'isense:images';
    protected $description = 'Generuje warianty WebP (srcset) dla grafik motywu iSense.';
    protected $usage       = 'isense:images [--force]';
    protected $options     = ['--force' => 'Przelicz wszystkie warianty, nawet aktualne.'];

    /** Szerokości wariantów; generowane tylko te <= szerokości źródła. */
    private const WIDTHS = [320, 640, 960, 1440, 1920];

    /**
     * Jakość WebP: pierwszy stopień to wartość domyślna, kolejne wchodzą tylko
     * dla wariantów, które przy niej przekraczają BUDGET_BYTES (zaszumione
     * zdjęcia potrafią wyjść na kilkaset kB i zjeść cały zysk z konwersji).
     */
    private const QUALITY_STEPS = [82, 68, 58];

    private const BUDGET_BYTES = 250 * 1024;

    /**
     * Górny limit wysokości wariantu. Pionowe zdjęcia (np. 1600x2133 pod tło
     * hero) mają przy pełnej szerokości absurdalnie dużo pikseli, choć w
     * layoucie i tak są przycinane przez background-size: cover.
     */
    private const MAX_HEIGHT = 1400;

    public function run(array $params)
    {
        $force  = array_key_exists('force', $params) || CLI::getOption('force');
        $srcDir = FCPATH . 'assets/isense/img';
        $optDir = $srcDir . '/opt';

        if (! is_dir($srcDir)) {
            CLI::error('Brak katalogu ' . $srcDir);
            return EXIT_ERROR;
        }
        if (! is_dir($optDir) && ! mkdir($optDir, 0775, true) && ! is_dir($optDir)) {
            CLI::error('Nie mogę utworzyć ' . $optDir);
            return EXIT_ERROR;
        }

        $manifest   = [];
        $bytesIn    = 0;
        $bytesOutMax = 0;

        foreach (glob($srcDir . '/*.{png,jpg,jpeg}', GLOB_BRACE) as $path) {
            $file = basename($path);
            $size = @getimagesize($path);
            if ($size === false) {
                CLI::write('pomijam (nie obraz): ' . $file, 'yellow');
                continue;
            }
            [$w, $h] = $size;
            $name    = pathinfo($file, PATHINFO_FILENAME);

            // Największy wariant: szerokość źródła, ale nie więcej niż wynika
            // z limitu wysokości (patrz MAX_HEIGHT).
            $maxW = $h > self::MAX_HEIGHT ? (int) round($w * self::MAX_HEIGHT / $h) : $w;

            $widths = array_values(array_filter(self::WIDTHS, static fn ($x) => $x < $maxW));
            $widths[] = $maxW;

            $entry = ['w' => $w, 'h' => $h, 'srcset' => []];
            foreach ($widths as $tw) {
                $out = $optDir . '/' . $name . '-' . $tw . '.webp';
                if ($force || ! is_file($out) || filemtime($out) < filemtime($path)) {
                    if (! $this->resizeToWebp($path, $out, $tw, (int) round($h * $tw / $w))) {
                        CLI::error('  nie udało się przetworzyć ' . $file . ' @' . $tw);
                        continue;
                    }
                }
                $entry['srcset'][] = [$tw, 'opt/' . $name . '-' . $tw . '.webp'];
            }

            if ($entry['srcset'] === []) {
                continue;
            }

            $manifest[$file] = $entry;
            $bytesIn        += filesize($path);
            $bytesOutMax    += filesize($optDir . '/' . $name . '-' . end($widths) . '.webp');

            CLI::write(sprintf(
                '%-28s %5dx%-5d %7s → %d wariantów, największy %s',
                $file,
                $w,
                $h,
                $this->human(filesize($path)),
                count($entry['srcset']),
                $this->human(filesize($optDir . '/' . $name . '-' . end($widths) . '.webp'))
            ));
        }

        ksort($manifest);
        file_put_contents(
            $optDir . '/manifest.json',
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        CLI::newLine();
        CLI::write(sprintf(
            'Gotowe: %d grafik. Źródła %s → największe warianty %s.',
            count($manifest),
            $this->human($bytesIn),
            $this->human($bytesOutMax)
        ), 'green');

        return EXIT_SUCCESS;
    }

    /**
     * Zapisuje wariant WebP, schodząc z jakością dopóki plik nie zmieści się
     * w budżecie (albo nie skończą się stopnie jakości).
     */
    private function resizeToWebp(string $src, string $dest, int $w, int $h): bool
    {
        foreach (self::QUALITY_STEPS as $q) {
            if (! $this->encodeWebp($src, $dest, $w, $h, $q)) {
                return false;
            }
            clearstatcache(true, $dest);
            if (filesize($dest) <= self::BUDGET_BYTES) {
                break;
            }
        }

        return true;
    }

    /**
     * Skaluje obraz do zadanych wymiarów i zapisuje jako WebP w danej jakości.
     * Imagick jeśli dostępny (lepszy filtr), w przeciwnym razie GD.
     */
    private function encodeWebp(string $src, string $dest, int $w, int $h, int $quality): bool
    {
        if (extension_loaded('imagick')) {
            try {
                $im = new \Imagick();
                $im->readImage($src);
                $im->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);
                $im->resizeImage($w, $h, \Imagick::FILTER_LANCZOS, 1);
                $im->stripImage();
                $im->setImageFormat('webp');
                $im->setImageCompressionQuality($quality);
                $ok = $im->writeImage($dest);
                $im->clear();

                return (bool) $ok;
            } catch (\Throwable $e) {
                CLI::error('  imagick: ' . $e->getMessage());

                return false;
            }
        }

        $data = @file_get_contents($src);
        if ($data === false || ($img = @imagecreatefromstring($data)) === false) {
            return false;
        }

        $dst = imagecreatetruecolor($w, $h);
        // Kanał alfa tylko dla źródeł, które go mają (PNG) — dokładanie go do
        // zdjęć JPEG puchnie plik WebP o kilkadziesiąt procent bez powodu.
        if (str_ends_with(strtolower($src), '.png')) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        } else {
            imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
        }
        imagecopyresampled($dst, $img, 0, 0, 0, 0, $w, $h, imagesx($img), imagesy($img));

        return imagewebp($dst, $dest, $quality);
    }

    private function human(int $bytes): string
    {
        return $bytes >= 1048576
            ? round($bytes / 1048576, 2) . ' MB'
            : round($bytes / 1024) . ' KB';
    }
}