<?php
/**
 * Capa de datos de "Demos" — vídeos y capturas de la aplicación
 * que se muestran en la sección Demo de /scrap.
 */
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

const DEMO_SECCIONES = ['scrap' => 'SCRAP', 'gestores' => 'Gestores'];

function demo_seccion_norm(?string $s): string {
    $s = strtolower(trim((string)$s));
    return isset(DEMO_SECCIONES[$s]) ? $s : 'scrap';
}

/** Todas las demos, opcionalmente filtradas por sección. */
function demos_all(?string $seccion = null): array {
    if ($seccion !== null) {
        return db_fetch_all("SELECT * FROM demos WHERE seccion = ? ORDER BY orden ASC, id DESC", [demo_seccion_norm($seccion)]);
    }
    return db_fetch_all("SELECT * FROM demos ORDER BY seccion ASC, orden ASC, id DESC");
}

function demo_by_id(int $id): ?array {
    return db_fetch("SELECT * FROM demos WHERE id = ? LIMIT 1", [$id]);
}

function demo_crear(array $d): ?int {
    db_exec(
        "INSERT INTO demos (seccion, titulo, descripcion, media_tipo, archivo, url, orden)
         VALUES (?,?,?,?,?,?,?)",
        [demo_seccion_norm($d['seccion'] ?? 'scrap'), $d['titulo'], $d['descripcion'], $d['media_tipo'], $d['archivo'], $d['url'], $d['orden']]
    );
    return db_last_id();
}

function demo_borrar(int $id): void {
    db_exec("DELETE FROM demos WHERE id = ?", [$id]);
}

function demos_count(): int {
    $r = db_fetch("SELECT COUNT(*) c FROM demos");
    return (int)($r['c'] ?? 0);
}

/**
 * Devuelve datos de reproducción para una demo:
 *   ['kind' => 'image'|'video-file'|'embed', 'src' => ...]
 */
function demo_media(array $demo): array {
    $archivo = trim((string)($demo['archivo'] ?? ''));
    $url     = trim((string)($demo['url'] ?? ''));
    $tipo    = $demo['media_tipo'] ?? 'imagen';

    // URL externa
    if ($url !== '') {
        $embed = video_embed_url($url);
        if ($embed) return ['kind' => 'embed', 'src' => $embed];
        // URL directa a un vídeo
        if (preg_match('#\.(mp4|webm|ogg)(\?.*)?$#i', $url)) return ['kind' => 'video-file', 'src' => $url];
        // URL directa a imagen
        return ['kind' => 'image', 'src' => $url];
    }

    // Archivo subido
    if ($archivo !== '') {
        $src = '/uploads/' . $archivo;
        if ($tipo === 'video' || preg_match('#\.(mp4|webm|ogg)$#i', $archivo)) {
            return ['kind' => 'video-file', 'src' => $src];
        }
        return ['kind' => 'image', 'src' => $src];
    }

    return ['kind' => 'image', 'src' => '/imagenes/banner_fusionado.png'];
}

/** Convierte una URL de YouTube/Vimeo en su URL de embed, o null si no aplica. */
function video_embed_url(string $url): ?string {
    if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{6,})#i', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    if (preg_match('#vimeo\.com/(?:video/)?(\d+)#i', $url, $m)) {
        return 'https://player.vimeo.com/video/' . $m[1];
    }
    return null;
}
