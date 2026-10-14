<?php
/**
 * Consultas a la Oficina Técnica de SCRAPs (OTS) desde /contacto.
 * Se guarda cada consulta con la prueba del consentimiento RGPD (fecha/hora e IP)
 * y se avisa por correo a la bandeja de la OTS si OTS_EMAIL está configurado.
 */
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

const PERFILES = [
    'scrap'     => 'SCRAP',
    'gestor'    => 'Gestor',
    'poseedor'  => 'Poseedor',
    'productor' => 'Productor',
    'otro'      => 'Otro',
];

/** Procesa el POST del formulario y redirige de vuelta a /contacto. */
function consulta_recibir(): never {
    if (!csrf_ok()) {
        flash_set('consulta_err', 'La sesión ha caducado. Vuelve a enviar el formulario, por favor.');
        redirect('/contacto#consulta');
    }
    // Campo trampa para bots
    if (trim($_POST['web'] ?? '') !== '') {
        flash_set('consulta_ok', true);
        redirect('/contacto#consulta');
    }
    if (time() - (int)($_SESSION['_consulta_ts'] ?? 0) < 30) {
        flash_set('consulta_err', 'Acabamos de recibir tu consulta. Espera unos segundos antes de enviar otra.');
        redirect('/contacto#consulta');
    }

    $d = [
        'nombre'   => mb_substr(trim($_POST['nombre'] ?? ''), 0, 160),
        'empresa'  => mb_substr(trim($_POST['empresa'] ?? ''), 0, 190),
        'email'    => mb_substr(trim($_POST['email'] ?? ''), 0, 190),
        'telefono' => mb_substr(trim($_POST['telefono'] ?? ''), 0, 40),
        'perfil'   => array_key_exists($_POST['perfil'] ?? '', PERFILES) ? $_POST['perfil'] : '',
        'mensaje'  => mb_substr(trim($_POST['mensaje'] ?? ''), 0, 4000),
    ];

    $faltan = [];
    if ($d['nombre'] === '')  $faltan['nombre'] = 'tu nombre';
    if ($d['empresa'] === '') $faltan['empresa'] = 'la empresa';
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $faltan['email'] = 'un email válido';
    if ($d['perfil'] === '')  $faltan['perfil'] = 'qué perfil tienes';
    if ($d['mensaje'] === '') $faltan['mensaje'] = 'tu consulta';

    if ($faltan) {
        flash_set('consulta_err', 'Necesitamos ' . implode(', ', $faltan) . ' para poder responderte.');
        flash_set('consulta_campos', array_keys($faltan));
        flash_set('consulta_old', $d);
        redirect('/contacto#consulta');
    }
    if (empty($_POST['rgpd'])) {
        flash_set('consulta_err', 'Para tratar tu consulta necesitamos que aceptes la política de privacidad.');
        flash_set('consulta_campos', ['rgpd']);
        flash_set('consulta_old', $d);
        redirect('/contacto#consulta');
    }

    $d['rgpd_en'] = date('c');
    $d['ip']      = $_SERVER['REMOTE_ADDR'] ?? null;

    $ok = db_exec(
        'INSERT INTO consultas (nombre, empresa, email, telefono, perfil, mensaje, rgpd, rgpd_en, ip, estado)
         VALUES (?,?,?,?,?,?,1,?,?,?)',
        [$d['nombre'], $d['empresa'], $d['email'], $d['telefono'] ?: null, $d['perfil'],
         $d['mensaje'], $d['rgpd_en'], $d['ip'], 'nueva']
    );
    $avisada = consulta_notificar($d);

    if (!$ok && !$avisada) {
        flash_set('consulta_err', 'No hemos podido registrar tu consulta. Inténtalo de nuevo en unos minutos.');
        flash_set('consulta_old', $d);
        redirect('/contacto#consulta');
    }

    $_SESSION['_consulta_ts'] = time();
    flash_set('consulta_ok', true);
    redirect('/contacto#consulta');
}

/** Aviso por correo a la OTS. Devuelve si se entregó al sistema de correo. */
function consulta_notificar(array $d): bool {
    $to = env('OTS_EMAIL');
    if (!$to || !function_exists('mail')) return false;

    $perfil = PERFILES[$d['perfil']] ?? 'Otro';
    $asunto = "Consulta OTS · $perfil · {$d['empresa']}";
    $cuerpo = "Nueva consulta recibida en scrapenvases.com\n\n"
        . "Nombre:   {$d['nombre']}\n"
        . "Empresa:  {$d['empresa']}\n"
        . "Email:    {$d['email']}\n"
        . 'Teléfono: ' . ($d['telefono'] ?: '—') . "\n"
        . "Soy:      $perfil\n\n"
        . "Consulta:\n{$d['mensaje']}\n\n"
        . "Consentimiento RGPD: {$d['rgpd_en']} · IP {$d['ip']}\n";

    $from = env('MAIL_FROM', 'no-reply@scrapenvases.com');
    $replyTo = str_replace(["\r", "\n"], '', $d['email']);
    $headers = "From: scrapenvases.com <$from>\r\nReply-To: $replyTo\r\nContent-Type: text/plain; charset=UTF-8\r\n";

    return @mail($to, '=?UTF-8?B?' . base64_encode($asunto) . '?=', $cuerpo, $headers);
}
