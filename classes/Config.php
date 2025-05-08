<?php
namespace classes;
class Config
{
    public static function get($path = null)
    {
        if ($path) {
            $config = $GLOBALS["config"];
            $path = explode('/', $path);
            foreach ($path as $bit) {
                if (isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }

            return $config;
        }

        // If we don't have a path given get method return false
        return false;
    }
}

