<?php
/**
 * Auth: sesión + usuarios (tabla `usuarios`) + roles.
 * Roles: 1 = admin, 2 = editor.
 */
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => (($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? '') !== 'off'),
        'path'     => '/',
    ]);
    session_start();
}

const ROL_ADMIN  = 1;
const ROL_EDITOR = 2;

function user_row_to_array(array $row): array {
    return [
        'id'       => (int) $row['id'],
        'nombre'   => $row['nombre'],
        'correo'   => $row['correo'],
        'rol'      => (int) $row['rol'],
        'rol_str'  => ((int)$row['rol'] === ROL_ADMIN) ? 'admin' : 'editor',
    ];
}

function find_user_by_email(string $email): ?array {
    $email = strtolower(trim($email));
    $row = db_fetch("SELECT * FROM usuarios WHERE LOWER(correo) = ? LIMIT 1", [$email]);
    return $row ?: null;
}

function find_user_by_id(int $id): ?array {
    $row = db_fetch("SELECT * FROM usuarios WHERE id = ? LIMIT 1", [$id]);
    return $row ? user_row_to_array($row) : null;
}

function attempt_login(string $email, string $password): ?array {
    $row = find_user_by_email($email);
    if (!$row) return null;
    if (!password_verify($password, $row['contrasena'])) return null;
    return user_row_to_array($row);
}

/* ---- Sesión ---- */
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}
function is_logged_in(): bool {
    return current_user() !== null;
}
function is_admin(): bool {
    $u = current_user();
    return $u !== null && (int)($u['rol'] ?? 0) === ROL_ADMIN;
}
function login_user(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user'] = $user;
}
function logout_user(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/* ---- Flash ---- */
function flash_set(string $k, $v): void { $_SESSION['_flash'][$k] = $v; }
function flash_get(string $k, $default = null) {
    $v = $_SESSION['_flash'][$k] ?? $default;
    unset($_SESSION['_flash'][$k]);
    return $v;
}

/* ---- Redirección / guardias ---- */
function redirect(string $url): void { header('Location: ' . $url); exit; }

/** Exige sesión activa (cualquier rol) */
function require_login(): void {
    if (!is_logged_in()) {
        flash_set('login_error', 'Inicia sesión para acceder al panel.');
        redirect('/acceso');
    }
}

/** Exige sesión activa CON rol de administrador */
function require_admin(): void {
    require_login();
    if (!is_admin()) {
        flash_set('login_error', 'Tu usuario no tiene permisos de administración.');
        logout_user();
        redirect('/acceso');
    }
}
