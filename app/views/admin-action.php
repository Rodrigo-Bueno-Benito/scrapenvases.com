<?php
require_admin();
require_once __DIR__ . '/../data/noticias_db.php';
require_once __DIR__ . '/../data/demos_db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    flash_set('admin_err', 'Solicitud no válida o sesión expirada.');
    redirect('/admin');
}

$op = $_POST['op'] ?? '';

/* -------- Borrar -------- */
if ($op === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $n = noticia_by_id($id);
        if ($n && !empty($n['imagen'])) borrar_upload($n['imagen']);
        noticia_borrar($id);
        flash_set('admin_ok', 'Noticia eliminada.');
    }
    redirect('/admin');
}

/* -------- Demo: borrar -------- */
if ($op === 'demo_delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $d = demo_by_id($id);
        if ($d && !empty($d['archivo'])) borrar_upload($d['archivo']);
        demo_borrar($id);
        flash_set('admin_ok', 'Elemento de la demo eliminado.');
    }
    redirect('/admin?view=demos');
}

/* -------- Demo: crear -------- */
if ($op === 'demo_create') {
    $titulo = trim($_POST['titulo'] ?? '');
    $url    = trim($_POST['url'] ?? '');
    if ($titulo === '') {
        flash_set('admin_err', 'El título es obligatorio.');
        redirect('/admin?view=demos');
    }

    $subida = procesar_upload_media('archivo');
    if ($subida['error']) {
        flash_set('admin_err', $subida['error']);
        redirect('/admin?view=demos');
    }
    if (!$subida['file'] && $url === '') {
        flash_set('admin_err', 'Sube un archivo o indica una URL de vídeo.');
        redirect('/admin?view=demos');
    }

    demo_crear([
        'seccion'     => $_POST['seccion'] ?? 'scrap',
        'titulo'      => $titulo,
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'media_tipo'  => ($_POST['media_tipo'] ?? 'imagen') === 'video' ? 'video' : 'imagen',
        'archivo'     => $subida['file'],
        'url'         => $url ?: null,
        'orden'       => 0,
    ]);
    flash_set('admin_ok', 'Elemento añadido a la demo.');
    redirect('/admin?view=demos');
}

/* -------- Crear / actualizar -------- */
if ($op === 'create' || $op === 'update') {
    $titulo = trim($_POST['titulo'] ?? '');
    if ($titulo === '') {
        flash_set('admin_err', 'El título es obligatorio.');
        redirect($op === 'update' ? '/admin?view=editor&id=' . (int)($_POST['id'] ?? 0) : '/admin?view=editor');
    }

    $estado    = ($_POST['estado'] ?? 'publicada') === 'borrador' ? 'borrador' : 'publicada';
    $categoria = trim($_POST['categoria'] ?? 'Envases') ?: 'Envases';
    $destacada = isset($_POST['destacada']) ? 1 : 0;
    $extracto  = trim($_POST['extracto'] ?? '');
    $cuerpo    = trim($_POST['cuerpo'] ?? '');

    // Imagen: nueva subida, o la actual si se edita
    $imagen = $_POST['imagen_actual'] ?? '';
    $subida = procesar_upload_imagen('imagen');
    if ($subida['error']) {
        flash_set('admin_err', $subida['error']);
        redirect($op === 'update' ? '/admin?view=editor&id=' . (int)($_POST['id'] ?? 0) : '/admin?view=editor');
    }
    if ($subida['file']) {
        // Si se reemplaza en una edición, borrar la anterior (solo si estaba en /uploads)
        if ($op === 'update' && $imagen) borrar_upload($imagen);
        $imagen = $subida['file'];
    }

    $data = [
        'titulo'    => $titulo,
        'extracto'  => $extracto,
        'cuerpo'    => $cuerpo,
        'imagen'    => $imagen,
        'categoria' => $categoria,
        'estado'    => $estado,
        'destacada' => $destacada,
        'autor_id'  => (int)(current_user()['id'] ?? 0),
    ];

    if ($op === 'create') {
        $id = noticia_crear($data);
        flash_set('admin_ok', $id ? 'Noticia publicada correctamente.' : 'No se pudo guardar (¿BBDD caída?).');
    } else {
        noticia_actualizar((int)$_POST['id'], $data);
        flash_set('admin_ok', 'Cambios guardados.');
    }
    redirect('/admin');
}

redirect('/admin');


/* ================= Helpers de fichero ================= */

function uploads_dir(): string {
    $dir = __DIR__ . '/../../public/uploads';
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    return $dir;
}

/**
 * Procesa la subida del campo $field.
 * @return array{file: ?string, error: ?string}
 */
function procesar_upload_imagen(string $field): array {
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['file' => null, 'error' => null];
    }
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        return ['file' => null, 'error' => 'Error al subir la imagen (código ' . $f['error'] . ').'];
    }
    if ($f['size'] > 6 * 1024 * 1024) {
        return ['file' => null, 'error' => 'La imagen supera el límite de 6 MB.'];
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($f['tmp_name']);
    $extByMime = [
        'image/jpeg' => 'jpg', 'image/png' => 'png',
        'image/webp' => 'webp', 'image/gif' => 'gif',
    ];
    if (!isset($extByMime[$mime])) {
        return ['file' => null, 'error' => 'Formato no permitido. Usa JPG, PNG, WEBP o GIF.'];
    }
    $ext = $extByMime[$mime];
    $name = 'noticia-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = uploads_dir() . '/' . $name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        return ['file' => null, 'error' => 'No se pudo guardar la imagen en el servidor.'];
    }
    return ['file' => $name, 'error' => null];
}

/**
 * Procesa la subida de una imagen O un vídeo (campo $field).
 * @return array{file: ?string, error: ?string}
 */
function procesar_upload_media(string $field): array {
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['file' => null, 'error' => null];
    }
    $f = $_FILES[$field];
    if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE) {
        return ['file' => null, 'error' => 'El archivo supera el tamaño permitido por el servidor. Para vídeos grandes, usa una URL de YouTube/Vimeo.'];
    }
    if ($f['error'] !== UPLOAD_ERR_OK) {
        return ['file' => null, 'error' => 'Error al subir el archivo (código ' . $f['error'] . ').'];
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($f['tmp_name']);
    $extByMime = [
        'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif',
        'video/mp4' => 'mp4', 'video/webm' => 'webm', 'video/ogg' => 'ogv', 'video/quicktime' => 'mp4',
    ];
    if (!isset($extByMime[$mime])) {
        return ['file' => null, 'error' => 'Formato no permitido. Usa JPG, PNG, WEBP, GIF, MP4 o WEBM.'];
    }
    $isVideo = str_starts_with($mime, 'video/');
    $maxBytes = $isVideo ? 200 * 1024 * 1024 : 8 * 1024 * 1024;
    if ($f['size'] > $maxBytes) {
        return ['file' => null, 'error' => 'El archivo es demasiado grande (máx. ' . ($isVideo ? '200 MB vídeo' : '8 MB imagen') . ').'];
    }
    $ext = $extByMime[$mime];
    $name = 'demo-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = uploads_dir() . '/' . $name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        return ['file' => null, 'error' => 'No se pudo guardar el archivo en el servidor.'];
    }
    return ['file' => $name, 'error' => null];
}

/** Borra un fichero de /uploads (nunca toca /imagenes ni URLs externas) */
function borrar_upload(?string $imagen): void {
    $imagen = trim((string)$imagen);
    if ($imagen === '' || preg_match('#^https?://#i', $imagen) || str_contains($imagen, '/')) return;
    $path = uploads_dir() . '/' . basename($imagen);
    if (is_file($path)) @unlink($path);
}
