<?php
/**
 * Variables de entorno desde .env (sin dependencias).
 */
function env_load(): void {
    static $loaded = false;
    if ($loaded) return;
    $loaded = true;

    $file = __DIR__ . '/../.env';
    if (!is_file($file)) return;

    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        if (strlen($v) >= 2 && ($v[0] === '"' || $v[0] === "'") && $v[-1] === $v[0]) {
            $v = substr($v, 1, -1);
        }
        if (getenv($k) === false) {
            putenv("$k=$v");
            $_ENV[$k] = $v;
        }
    }
}

function env(string $key, ?string $default = null): ?string {
    env_load();
    // $_ENV además de getenv(): hay hostings con putenv() deshabilitado.
    $v = getenv($key);
    if ($v === false || $v === '') $v = $_ENV[$key] ?? false;
    return ($v === false || $v === '') ? $default : $v;
}
