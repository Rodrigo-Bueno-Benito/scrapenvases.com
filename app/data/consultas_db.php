<?php
/**
 * Capa de datos de las consultas recibidas por la Oficina Técnica de SCRAPs (OTS).
 *
 * Se guarda el consentimiento RGPD con su marca de tiempo e IP, tal y como
 * pide la nota de maquetación del copy de /contacto.
 */
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

const CONSULTA_PERFILES = [
    'scrap'      => 'SCRAP',
    'gestor'     => 'Gestor',
    'poseedor'   => 'Poseedor',
    'productor'  => 'Productor',
    'otro'       => 'Otro',
];

function consulta_perfil_norm(?string $p): string {
    $p = strtolower(trim((string)$p));
    return isset(CONSULTA_PERFILES[$p]) ? $p : 'otro';
}

function consulta_perfil_label(?string $p): string {
    return CONSULTA_PERFILES[consulta_perfil_norm($p)];
}

function consulta_crear(array $d): ?int {
    db_exec(
        "INSERT INTO consultas (nombre, empresa, email, telefono, perfil, mensaje, rgpd, rgpd_en, ip, estado)
         VALUES (?,?,?,?,?,?,?,?,?,?)",
        [
            $d['nombre'], $d['empresa'], $d['email'], $d['telefono'] ?: null,
            consulta_perfil_norm($d['perfil'] ?? 'otro'), $d['mensaje'],
            !empty($d['rgpd']) ? 1 : 0, $d['rgpd_en'] ?? null, $d['ip'] ?? null, 'nueva',
        ]
    );
    return db_last_id();
}

function consultas_all(): array {
    return db_fetch_all("SELECT * FROM consultas ORDER BY creado_en DESC, id DESC");
}

function consulta_by_id(int $id): ?array {
    return db_fetch("SELECT * FROM consultas WHERE id = ? LIMIT 1", [$id]);
}

function consulta_marcar(int $id, string $estado): void {
    $estado = in_array($estado, ['nueva', 'atendida'], true) ? $estado : 'nueva';
    db_exec("UPDATE consultas SET estado = ? WHERE id = ?", [$estado, $id]);
}

function consulta_borrar(int $id): void {
    db_exec("DELETE FROM consultas WHERE id = ?", [$id]);
}

function consultas_stats(): array {
    $total    = db_fetch("SELECT COUNT(*) c FROM consultas");
    $nuevas   = db_fetch("SELECT COUNT(*) c FROM consultas WHERE estado = 'nueva'");
    return [
        'total'  => (int)($total['c'] ?? 0),
        'nuevas' => (int)($nuevas['c'] ?? 0),
    ];
}

/**
 * Avisa por correo a la OTS de una consulta nueva.
 * Silencioso a propósito: si el servidor no tiene salida de correo, la
 * consulta ya está guardada en el panel y no se pierde nada.
 */
function consulta_notificar(array $d): void {
    $to = env('OTS_EMAIL');
    if (!$to || !function_exists('mail')) return;

    $asunto = 'Consulta OTS · ' . consulta_perfil_label($d['perfil'] ?? 'otro') . ' · ' . $d['empresa'];
    $cuerpo = "Nueva consulta recibida en scrapenvases.com\n\n"
        . "Nombre:   {$d['nombre']}\n"
        . "Empresa:  {$d['empresa']}\n"
        . "Email:    {$d['email']}\n"
        . "Teléfono: " . ($d['telefono'] ?: '—') . "\n"
        . "Perfil:   " . consulta_perfil_label($d['perfil'] ?? 'otro') . "\n\n"
        . "Consulta:\n{$d['mensaje']}\n\n"
        . "RGPD aceptado: " . ($d['rgpd_en'] ?? '—') . "\n";

    $headers = "From: ScrapEnvases <no-reply@scrapenvases.com>\r\n"
        . "Reply-To: {$d['email']}\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n";

    @mail($to, '=?UTF-8?B?' . base64_encode($asunto) . '?=', $cuerpo, $headers);
}
