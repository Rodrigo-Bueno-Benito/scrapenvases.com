<?php
/**
 * Helpers de presentación y utilidades comunes.
 */

/** Escapar salida HTML */
function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** URL base de la app (para links absolutos internos) */
function url(string $path = ''): string {
    return '/' . ltrim($path, '/');
}

/**
 * URL de un fichero estático con su marca de tiempo:
 *   asset('/css/base.css') → /css/base.css?v=1770000000
 *
 * Así el navegador recoge la versión nueva en cuanto se edita el fichero,
 * sin dejar de cachearlo agresivamente el resto del tiempo.
 */
function asset(string $path): string {
    $path = '/' . ltrim($path, '/');
    $file = __DIR__ . '/../public' . $path;
    $v = is_file($file) ? filemtime($file) : null;
    return $v ? $path . '?v=' . $v : $path;
}

/** Slug amigable para URLs */
function slugify(string $text): string {
    $text = trim($text);
    // Transliteración directa de caracteres españoles (evita artefactos de iconv)
    $map = [
        'á'=>'a','à'=>'a','ä'=>'a','â'=>'a','ã'=>'a','é'=>'e','è'=>'e','ë'=>'e','ê'=>'e',
        'í'=>'i','ì'=>'i','ï'=>'i','î'=>'i','ó'=>'o','ò'=>'o','ö'=>'o','ô'=>'o','õ'=>'o',
        'ú'=>'u','ù'=>'u','ü'=>'u','û'=>'u','ñ'=>'n','ç'=>'c',
        'Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u','Ñ'=>'n','Ü'=>'u',
    ];
    $text = strtr($text, $map);
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text !== '' ? $text : 'noticia';
}

/** Fecha legible en español */
function fecha_es(?string $fecha): string {
    if (!$fecha) return '';
    $ts = strtotime($fecha);
    if ($ts === false) return '';
    $meses = [1=>'enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    return date('j', $ts) . ' de ' . $meses[(int)date('n', $ts)] . ' de ' . date('Y', $ts);
}

/** Recorte de texto para extractos */
function excerpt(?string $html, int $len = 160): string {
    $text = trim(preg_replace('/\s+/', ' ', strip_tags((string)$html)));
    if (mb_strlen($text) <= $len) return $text;
    return mb_substr($text, 0, $len) . '…';
}

/**
 * Resuelve la ruta pública de la imagen de una noticia.
 * - Vacío           -> placeholder de marca
 * - URL absoluta    -> tal cual
 * - Fichero subido  -> /uploads/<archivo>
 * - Semilla/genérica -> /imagenes/<archivo> (si existe) o /uploads/<archivo>
 */
function noticia_img_src(?string $imagen): string {
    $imagen = trim((string)$imagen);
    if ($imagen === '') return '/imagenes/banner_fusionado.png';
    if (preg_match('#^https?://#i', $imagen)) return $imagen;
    if (str_starts_with($imagen, '/')) return $imagen;

    $pubRoot = __DIR__ . '/../public';
    if (is_file($pubRoot . '/uploads/' . $imagen)) return '/uploads/' . $imagen;
    if (is_file($pubRoot . '/imagenes/' . $imagen)) return '/imagenes/' . $imagen;
    return '/uploads/' . $imagen;
}

/**
 * Dimensiones intrínsecas de una imagen local, como atributos HTML.
 *
 * Reservar el hueco antes de que cargue evita que el texto salte
 * (CLS). Devuelve cadena vacía para imágenes remotas o ilegibles:
 * el `aspect-ratio` de la hoja de estilos sigue cubriendo ese caso.
 */
function img_attrs(string $src): string {
    static $cache = [];
    if (array_key_exists($src, $cache)) return $cache[$src];

    $r = '';
    if ($src !== '' && $src[0] === '/' && !str_contains($src, '..')) {
        $ruta = __DIR__ . '/../public' . $src;
        if (is_file($ruta)) {
            $d = @getimagesize($ruta);
            if ($d && $d[0] > 0 && $d[1] > 0) {
                $r = ' width="' . (int)$d[0] . '" height="' . (int)$d[1] . '"';
            }
        }
    }
    return $cache[$src] = $r;
}

/**
 * URL pública de cada herramienta del core.
 *
 * Por defecto apunta al dominio de producto. Si en `.env` defines
 * URL_SCRAPP / URL_INPROGEST / URL_PROBATUS —por ejemplo un entorno de
 * demostración con datos ficticios— se usa ese valor.
 *
 * No pongas aquí la instancia de un cliente: es su panel de producción.
 */
function plataforma_url(string $tool): ?string {
    $porDefecto = [
        'scrapp'    => 'https://scrapp.es/',
        'inprogest' => 'https://inprogest.com/',
        'probatus'  => 'https://probatus.es/',
    ];
    $tool = strtolower($tool);
    if (!isset($porDefecto[$tool])) return null;
    return env('URL_' . strtoupper($tool), $porDefecto[$tool]);
}

/**
 * ¿Hay un entorno de demostración real configurado para esta herramienta?
 * Solo entonces se ofrece el acceso «probar la aplicación de verdad».
 */
function plataforma_demo_url(string $tool): ?string {
    $v = env('DEMO_' . strtoupper($tool));
    return $v ?: null;
}

/** Token CSRF por sesión */
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): bool {
    return isset($_POST['_csrf'])
        && is_string($_POST['_csrf'])
        && hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf']);
}
