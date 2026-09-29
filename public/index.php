<?php
/**
 * Front controller. Enruta ?p=<vista> a app/views/<vista>.php
 * y envuelve con header/footer salvo las vistas de sistema.
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/data/install.php';

// Auto-instalación en primer arranque (idempotente, con centinela de esquema)
db_install();

$page = $_GET['p'] ?? 'home';
$page = preg_replace('/[^a-z0-9_-]/i', '', (string)$page);
if ($page === '') $page = 'home';

/**
 * Rutas antiguas → nuevas. El rediseño del portal renombró /scrap
 * a /para-tu-scrap; se conserva el enlace con un 301 para no perder
 * el posicionamiento ni romper enlaces externos.
 */
const REDIRECCIONES = [
    'scrap'    => '/para-tu-scrap',
    'core'     => '/el-core',
    'el_core'  => '/el-core',
    'ots'      => '/contacto',
];
if (isset(REDIRECCIONES[$page])) {
    header('Location: ' . REDIRECCIONES[$page], true, 301);
    exit;
}

$viewPath = __DIR__ . '/../app/views/' . $page . '.php';
if (!is_file($viewPath)) {
    http_response_code(404);
    $viewPath = __DIR__ . '/../app/views/404.php';
}

// Vistas sin layout público (handlers POST, panel, login…)
$noLayout = str_starts_with($page, 'admin')
    || str_starts_with($page, 'auth-')
    || str_ends_with($page, '-action')
    || in_array($page, ['logout'], true);

if ($noLayout) {
    require $viewPath;
} else {
    require __DIR__ . '/../app/views/partials/header.php';
    require $viewPath;
    require __DIR__ . '/../app/views/partials/footer.php';
}
