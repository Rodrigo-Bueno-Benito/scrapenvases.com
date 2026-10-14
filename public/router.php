<?php
/**
 * Router para el servidor embebido de PHP:
 *   php -S localhost:8010 -t public public/router.php
 * Sirve los estáticos tal cual y pasa el resto a index.php.
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($uri !== '/' && is_file(__DIR__ . $uri)) {
    return false;
}
require __DIR__ . '/index.php';
