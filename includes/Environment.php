<?php
class Environment
{
    private static $variables = [];

    public static function load()
    {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    self::$variables[trim($key)] = trim($value);
                }
            }
        }
    }

    public static function get($key, $default = null)
    {
        return isset(self::$variables[$key]) ? self::$variables[$key] : $default;
    }
}
