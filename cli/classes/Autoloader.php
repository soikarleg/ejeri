<?php
// classes/Autoloader.php
class Autoloader
{
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }
    public static function autoload($class)
    {
        $paths = [
            __DIR__ . '/../../shared/pdf/' . $class . '.php',
            __DIR__ . '/../controllers/' . $class . '.php',
            __DIR__ . '/../models/' . $class . '.php',
            __DIR__ . '/' . $class . '.php',
        ];
        foreach ($paths as $file) {
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
}
