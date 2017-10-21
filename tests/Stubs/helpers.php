<?php

class OldValuesStorage
{
    protected static $values = [];

    public static function set(string $key, $value)
    {
        static::$values[$key] = $value;
    }

    public static function has(string $key)
    {
        return isset(static::$values[$key]);
    }

    public static function get(string $key, $default = null)
    {
        return static::has($key) ? static::$values[$key] : $default;
    }

    public static function forget(string $key)
    {
        unset(static::$values[$key]);
    }

    public static function flush()
    {
        static::$values = [];
    }
}

function old($key, $default = null)
{
    return OldValuesStorage::get($key, $default);
}
