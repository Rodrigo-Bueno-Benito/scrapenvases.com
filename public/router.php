<?php
/**
 * Router para el servidor embebido de PHP:
 *   php -S localhost:8000 -t public public/router.php
 * Sirve ficheros estáticos tal cual y enruta el resto a index.php,
 * traduciendo /noticias, /scrap, etc. a ?p=<vista>.
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Fichero estático existente → servirlo directamente
$file = __DIR__ . $uri;
if ($uri !== '/' && is_file($file)) {
    return false;
}

// Rutas limpias -> parámetro p
$path = trim($uri, '/');
if ($path === '' || $path === 'index.php') {
    $_GET['p'] = 'home';
} elseif (preg_match('#^noticia/([a-z0-9-]+)$#i', $path, $m)) {
    $_GET['p'] = 'noticia';
    $_GET['slug'] = $m[1];
} else {
    $_GET['p'] = preg_replace('/[^a-z0-9_-]/i', '', $path);
}

require __DIR__ . '/index.php';
