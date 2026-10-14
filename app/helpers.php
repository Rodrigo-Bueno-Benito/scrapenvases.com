<?php
require_once __DIR__ . '/env.php';

function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Ruta de un estático con marca de versión, para invalidar caché al editarlo. */
function asset(string $path): string {
    $path = '/' . ltrim($path, '/');
    $file = __DIR__ . '/../public' . $path;
    return is_file($file) ? $path . '?v=' . filemtime($file) : $path;
}

/** Las tres herramientas del core y su web (sobrescribible en .env). */
function herramienta(string $id): array {
    $h = [
        'probatus'  => ['nombre' => 'PROBATUS',  'dominio' => 'probatus.es',   'url' => 'https://probatus.es/',   'logo' => '/imagenes/web/logo-probatus.png'],
        'inprogest' => ['nombre' => 'INPROGEST', 'dominio' => 'inprogest.com', 'url' => 'https://inprogest.com/', 'logo' => null],
        'scrapp'    => ['nombre' => 'SCRAPP',    'dominio' => 'scrapp.es',     'url' => 'https://scrapp.es/',     'logo' => '/imagenes/web/logo-scrapp.png'],
    ][$id];
    $h['id']  = $id;
    $h['url'] = env('URL_' . strtoupper($id), $h['url']);
    return $h;
}

/** Imagen real de cabecera de una página, si ya se ha subido a /imagenes/portal/. */
function imagen_portal(string $pagina): ?string {
    foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
        $rel = "/imagenes/portal/$pagina.$ext";
        if (is_file(__DIR__ . '/../public' . $rel)) return $rel;
    }
    return null;
}

/* ---- Sesión (solo se abre en las páginas que la necesitan) ---- */

function session_open(): void {
    if (session_status() !== PHP_SESSION_NONE) return;
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'path'     => '/',
    ]);
    session_start();
}

function flash_set(string $k, $v): void { session_open(); $_SESSION['_flash'][$k] = $v; }

function flash_get(string $k, $default = null) {
    session_open();
    $v = $_SESSION['_flash'][$k] ?? $default;
    unset($_SESSION['_flash'][$k]);
    return $v;
}

function redirect(string $url): never {
    header('Location: ' . $url, true, 303);
    exit;
}

function csrf_field(): string {
    session_open();
    $_SESSION['_csrf'] ??= bin2hex(random_bytes(32));
    return '<input type="hidden" name="_csrf" value="' . e($_SESSION['_csrf']) . '">';
}

function csrf_ok(): bool {
    session_open();
    return is_string($_POST['_csrf'] ?? null) && hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf']);
}
