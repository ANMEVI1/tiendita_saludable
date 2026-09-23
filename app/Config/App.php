<?php

namespace App\Config;

class App
{
    /**
     * Obtiene una variable de entorno con un valor por defecto
     */
    public static function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Verifica si la aplicación está en modo desarrollo
     */
    public static function isLocal()
    {
        return self::env('APP_ENV', 'production') === 'development';
    }

    /**
     * Obtiene el nombre de la aplicación
     */
    public static function name()
    {
        return self::env('APP_NAME', 'La Tiendita Saludable');
    }
}
