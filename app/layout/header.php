<?php
/** @var string $ruta  @var array $pagina */
require_once __DIR__ . '/../partials.php';

$nav = [
    'el-core'       => 'El CORE',
    'para-tu-scrap' => 'Para tu SCRAP',
    'poseedores'    => 'Poseedores',
    'gestores'      => 'Gestores',
    'normativa'     => 'Normativa',
    'contacto'      => 'Contacto · OTS',
];
$canonical = 'https://scrapenvases.com' . ($ruta === 'home' ? '/' : '/' . $ruta);
?><!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pagina['title']) ?></title>
<?php if ($pagina['desc'] !== ''): ?>
<meta name="description" content="<?= e($pagina['desc']) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="scrapenvases.com">
<meta property="og:title" content="<?= e($pagina['title']) ?>">
<meta property="og:description" content="<?= e($pagina['desc']) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<?php else: ?>
<meta name="robots" content="noindex">
<?php endif; ?>
<link rel="icon" href="/imagenes/favicon.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&family=IBM+Plex+Sans:ital,wght@0,400;0,500;0,600;1,400&family=IBM+Plex+Mono:wght@500;600&display=swap">
<link rel="stylesheet" href="<?= asset('/css/site.css') ?>">
<script src="<?= asset('/js/site.js') ?>" defer></script>
</head>
<body class="p-<?= e($ruta) ?>">
<a class="skip" href="#contenido">Saltar al contenido</a>

<header class="nav">
  <div class="wrap nav__in">
    <a class="nav__logo" href="/" aria-label="scrapenvases.com — inicio">
      <img src="/imagenes/web/logo-scrapenvases.png" alt="SCRAPenvases.com" width="260" height="33">
    </a>
    <button class="nav__toggle" type="button" aria-expanded="false" aria-controls="menu">
      <span class="nav__bars" aria-hidden="true"></span><span class="sr">Menú</span>
    </button>
    <nav id="menu" class="nav__menu" aria-label="Principal">
      <ul>
        <?php foreach ($nav as $slug => $txt): ?>
          <li><a href="/<?= $slug ?>"<?= $ruta === $slug ? ' aria-current="page"' : '' ?>><?= e($txt) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <a class="btn btn--ghost nav__cta" href="/contacto">Contactar</a>
    </nav>
  </div>
</header>

<main id="contenido">
