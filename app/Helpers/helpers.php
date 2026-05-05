<?php

use App\Core\App;

if (!function_exists('app')) {
    function app(?string $key = null, mixed $default = null): mixed
    {
        return App::get($key, $default);
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        return App::config($key, $default);
    }
}

if (!function_exists('base_url')) {
    function normalized_base_path(?string $scriptName = null): string
    {
        $scriptName = str_replace('\\', '/', (string) ($scriptName ?? ($_SERVER['SCRIPT_NAME'] ?? '')));
        $directory = str_replace('\\', '/', dirname($scriptName));
        $directory = trim($directory, " \t\n\r\0\x0B/.");
        $directory = preg_replace('#/public$#', '', '/' . $directory);
        $directory = trim((string) $directory, " \t\n\r\0\x0B/.");

        return $directory === '' ? '' : '/' . $directory;
    }

    function is_public_document_root(): bool
    {
        $documentRoot = str_replace('\\', '/', (string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
        $documentRoot = rtrim($documentRoot, '/');

        return $documentRoot !== '' && basename($documentRoot) === 'public';
    }

    function detected_base_url(): string
    {
        $scheme = 'http';
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $scheme = 'https';
        }

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $basePath = normalized_base_path();

        return $scheme . '://' . $host . $basePath;
    }

    function base_url(string $path = ''): string
    {
        $configuredBaseUrl = rtrim((string) config('app.base_url', ''), '/');
        $appEnv = (string) getenv('APP_ENV');

        if ($appEnv === 'local' && isset($_SERVER['HTTP_HOST'])) {
            $baseUrl = rtrim(detected_base_url(), '/');
        } else {
            $baseUrl = $configuredBaseUrl !== '' ? $configuredBaseUrl : rtrim(detected_base_url(), '/');
        }

        $path = ltrim($path, '/');

        return $path === '' ? $baseUrl : $baseUrl . '/' . $path;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $path = ltrim($path, '/');

        if (is_public_document_root()) {
            return base_url($path);
        }

        return base_url('public/' . $path);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): never
    {
        header('Location: ' . (str_starts_with($path, 'http') ? $path : base_url($path)));
        exit;
    }
}

if (!function_exists('session_get')) {
    function session_get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }
}

if (!function_exists('session_flash')) {
    function session_flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }
}

if (!function_exists('session_old')) {
    function session_old(string $key, mixed $default = null): mixed
    {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
