<?php
/**
 * Recepción de consultas de la Oficina Técnica de SCRAPs (OTS).
 * Guarda la consulta con el consentimiento RGPD (fecha/hora + IP) y avisa
 * por correo si OTS_EMAIL está configurado en .env.
 */
require_once __DIR__ . '/../data/consultas_db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    flash_set('consulta_err', 'Solicitud no válida o sesión expirada. Vuelve a enviar el formulario.');
    redirect('/contacto');
}

// Trampa para bots: si viene rellena, se descarta sin más ruido
if (trim($_POST['web_url'] ?? '') !== '') {
    flash_set('consulta_ok', true);
    redirect('/contacto');
}

// Límite básico de envíos por sesión (evita el envío repetido accidental y el spam simple)
$ultimo = $_SESSION['_consulta_ts'] ?? 0;
if (time() - (int)$ultimo < 30) {
    flash_set('consulta_err', 'Acabas de enviarnos una consulta. Espera unos segundos antes de enviar otra.');
    redirect('/contacto');
}

$d = [
    'nombre'   => trim($_POST['nombre'] ?? ''),
    'empresa'  => trim($_POST['empresa'] ?? ''),
    'email'    => trim($_POST['email'] ?? ''),
    'telefono' => trim($_POST['telefono'] ?? ''),
    'perfil'   => $_POST['perfil'] ?? 'otro',
    'mensaje'  => trim($_POST['mensaje'] ?? ''),
];

/* ---- Validación ---- */
$errores = [];
if ($d['nombre'] === '')  $errores[] = 'el nombre';
if ($d['empresa'] === '') $errores[] = 'la empresa';
if ($d['email'] === '' || !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $errores[] = 'un email válido';
if ($d['mensaje'] === '') $errores[] = 'tu consulta';

if ($errores) {
    flash_set('consulta_err', 'Necesitamos ' . implode(', ', $errores) . ' para poder responderte.');
    flash_set('consulta_old', $d);
    redirect('/contacto');
}

if (empty($_POST['rgpd'])) {
    flash_set('consulta_err', 'Para poder tratar tu consulta necesitamos que aceptes la política de privacidad.');
    flash_set('consulta_old', $d);
    redirect('/contacto');
}

// Recortes defensivos a lo que admite el esquema
$d['nombre']   = mb_substr($d['nombre'], 0, 160);
$d['empresa']  = mb_substr($d['empresa'], 0, 190);
$d['email']    = mb_substr($d['email'], 0, 190);
$d['telefono'] = mb_substr($d['telefono'], 0, 40);
$d['mensaje']  = mb_substr($d['mensaje'], 0, 4000);

// Prueba del consentimiento
$d['rgpd']    = 1;
$d['rgpd_en'] = date('c');
$d['ip']      = $_SERVER['REMOTE_ADDR'] ?? null;

$id = consulta_crear($d);
if (!$id) {
    flash_set('consulta_err', 'No hemos podido registrar tu consulta. Escríbenos directamente por correo, por favor.');
    flash_set('consulta_old', $d);
    redirect('/contacto');
}

$_SESSION['_consulta_ts'] = time();
consulta_notificar($d);

flash_set('consulta_ok', true);
redirect('/contacto');
