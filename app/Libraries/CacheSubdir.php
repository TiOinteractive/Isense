<?php

namespace App\Libraries;

use CodeIgniter\Cache\Handlers\FileHandler;
use Config\Cache as CacheConfig;

class CacheSubdir
{
    private static array $instances = [];

    public static function get(string $subdir): FileHandler
    {
        if (!isset(self::$instances[$subdir])) {
            $config = new CacheConfig();
            $config->file['storePath'] = WRITEPATH . 'cache/' . $subdir;
            if (!is_dir($config->file['storePath'])) {
                @mkdir($config->file['storePath'], 0775, true);
            }
            $handler = new FileHandler($config);
            $handler->initialize();
            self::$instances[$subdir] = $handler;
        }
        return self::$instances[$subdir];
    }
}
