<?php
/**
 * Panel interno de la OTS: /acceso (login), /admin (consultas recibidas), /salir.
 * Las consultas se guardan aunque el aviso por correo falle; aquí no se pierde ninguna.
 */
require_once __DIR__ . '/consultas.php';

session_open();
header('X-Robots-Tag: noindex');

$usuario = $_SESSION['ots_user'] ?? null;

if ($ruta === 'salir') {
    $_SESSION = [];
    session_destroy();
    redirect('/acceso');
}

if ($ruta === 'acceso') {
    if ($usuario) redirect('/admin');
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $espera = ($_SESSION['_login_fallos'] ?? 0) >= 5 ? 2 : 0;
        sleep($espera);
        $row = csrf_ok()
            ? db_fetch('SELECT * FROM usuarios WHERE LOWER(correo) = ? AND rol = 1 LIMIT 1', [strtolower(trim($_POST['correo'] ?? ''))])
            : null;
        if ($row && password_verify((string)($_POST['contrasena'] ?? ''), $row['contrasena'])) {
            session_regenerate_id(true);
            $_SESSION['ots_user'] = ['id' => (int)$row['id'], 'nombre' => $row['nombre']];
            unset($_SESSION['_login_fallos']);
            redirect('/admin');
        }
        $_SESSION['_login_fallos'] = ($_SESSION['_login_fallos'] ?? 0) + 1;
        $error = 'Correo o contraseña incorrectos.';
    }
    admin_cabecera('Acceso · Panel OTS'); ?>
    <section class="sec"><div class="wrap wrap--narrow admin-login">
      <p class="kicker">Panel interno</p>
      <h1>Consultas de la OTS</h1>
      <?php if ($error): ?><div class="alert alert--err" role="alert"><?= e($error) ?></div><?php endif; ?>
      <form class="form" method="post" action="/acceso">
        <?= csrf_field() ?>
        <label class="field"><span>Correo</span><input type="email" name="correo" autocomplete="username" required></label>
        <label class="field"><span>Contraseña</span><input type="password" name="contrasena" autocomplete="current-password" required></label>
        <button class="btn btn--primary" type="submit">Entrar</button>
      </form>
    </div></section>
    <?php admin_pie();
    return;
}

// /admin
if (!$usuario) redirect('/acceso');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok()) {
    $id = (int)($_POST['id'] ?? 0);
    match ($_POST['accion'] ?? '') {
        'atendida' => db_exec("UPDATE consultas SET estado = 'atendida' WHERE id = ?", [$id]),
        'nueva'    => db_exec("UPDATE consultas SET estado = 'nueva' WHERE id = ?", [$id]),
        'borrar'   => db_exec('DELETE FROM consultas WHERE id = ?', [$id]),
        default    => null,
    };
    redirect('/admin');
}

$consultas = db_fetch_all('SELECT * FROM consultas ORDER BY creado_en DESC, id DESC');
$nuevas = count(array_filter($consultas, fn($c) => $c['estado'] === 'nueva'));

admin_cabecera('Consultas · Panel OTS'); ?>
<section class="sec"><div class="wrap">
  <div class="admin-head">
    <div>
      <p class="kicker">Panel interno · <?= e($usuario['nombre']) ?></p>
      <h1>Consultas recibidas</h1>
      <p><?= count($consultas) ?> en total · <?= $nuevas ?> sin atender</p>
    </div>
    <a class="btn btn--ghost" href="/salir">Cerrar sesión</a>
  </div>

  <?php if (!$consultas): ?>
    <p class="admin-empty">Todavía no ha llegado ninguna consulta.</p>
  <?php endif; ?>

  <?php foreach ($consultas as $c): ?>
    <article class="consulta consulta--<?= e($c['estado']) ?>">
      <header>
        <span class="consulta__estado"><?= $c['estado'] === 'nueva' ? 'Nueva' : 'Atendida' ?></span>
        <strong><?= e($c['nombre']) ?></strong> · <?= e($c['empresa']) ?> · <?= e(PERFILES[$c['perfil']] ?? 'Otro') ?>
        <time><?= e($c['creado_en']) ?></time>
      </header>
      <p class="consulta__datos">
        <a href="mailto:<?= e($c['email']) ?>"><?= e($c['email']) ?></a>
        <?php if ($c['telefono']): ?> · <?= e($c['telefono']) ?><?php endif; ?>
        · RGPD aceptado <?= e($c['rgpd_en'] ?? '—') ?>
      </p>
      <p class="consulta__msg"><?= nl2br(e($c['mensaje'])) ?></p>
      <form method="post" action="/admin" class="consulta__acciones">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
        <?php if ($c['estado'] === 'nueva'): ?>
          <button class="btn btn--primary btn--sm" name="accion" value="atendida">Marcar atendida</button>
        <?php else: ?>
          <button class="btn btn--ghost btn--sm" name="accion" value="nueva">Marcar como nueva</button>
        <?php endif; ?>
        <button class="btn btn--ghost btn--sm" name="accion" value="borrar" onclick="return confirm('¿Borrar esta consulta definitivamente?')">Borrar</button>
      </form>
    </article>
  <?php endforeach; ?>
</div></section>
<?php admin_pie();

function admin_cabecera(string $titulo): void { ?><!doctype html>
<html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex"><title><?= e($titulo) ?></title>
<link rel="icon" href="/favicon.ico" sizes="48x48">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap">
<link rel="stylesheet" href="<?= asset('/css/site.css') ?>">
</head><body class="p-admin">
<header class="nav"><div class="wrap nav__in">
  <a class="nav__logo" href="/"><img src="<?= asset('/imagenes/web/logo-scrapenvases.png') ?>" alt="SCRAPenvases.com" width="260" height="20"></a>
</div></header><main>
<?php }

function admin_pie(): void { ?></main></body></html><?php }
