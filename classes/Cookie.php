<?php

namespace classes;

class Cookie {
    public static function exists($name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public static function get($name) {
        return $_COOKIE[$name];
    }

    public static function put($name, $value, $expiry): bool
    {
        return setcookie($name, $value, time() + $expiry, '/');
    }

    public static function delete($name): void
    {
        self::put($name, '', time() - 1);
    }
}