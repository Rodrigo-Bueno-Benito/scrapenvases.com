<?php
/** @var string $ruta  @var array $pagina */
require_once __DIR__ . '/../partials.php';

$nav = [
    'para-tu-scrap' => 'Para tu SCRAP',
    'poseedores'    => 'Poseedores',
    'gestores'      => 'Gestores',
    'normativa'     => 'Normativa',
    'contacto'      => 'Contacto · OTS',
];
// Páginas que empiezan con una escena oscura a pantalla completa: la barra flota transparente encima
$oscura = in_array($ruta, ['home', 'para-tu-scrap', 'poseedores', 'gestores', 'normativa'], true);
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
<link rel="icon" href="/favicon.ico" sizes="48x48">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,400..800&family=Instrument+Serif:ital@1&family=IBM+Plex+Mono:wght@500&display=swap">
<link rel="stylesheet" href="<?= asset('/css/site.css') ?>">
<script src="<?= asset('/js/site.js') ?>" defer></script>
<?php if ($ruta === 'home'): ?>
<script type="importmap">{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.170.0/build/three.module.min.js","three/addons/":"https://cdn.jsdelivr.net/npm/three@0.170.0/examples/jsm/"}}</script>
<script type="module" src="<?= asset('/js/hero3d.js') ?>"></script>
<script type="module" src="<?= asset('/js/apps3d.js') ?>"></script>
<script type="module" src="<?= asset('/js/journey.js') ?>"></script>
<?php endif; ?>
</head>
<body class="p-<?= e($ruta) ?><?= $oscura ? ' top-dark' : '' ?>">
<a class="skip" href="#contenido">Saltar al contenido</a>

<header class="nav">
  <div class="wrap nav__in">
    <a class="nav__logo" href="/" aria-label="scrapenvases.com — inicio">
      <img class="logo--dark" src="<?= asset('/imagenes/web/logo-scrapenvases.png') ?>" alt="SCRAPenvases.com" width="260" height="20">
      <?php if ($oscura): ?><img class="logo--light" src="<?= asset('/imagenes/web/logo-scrapenvases-blanco.png') ?>" alt="" width="260" height="20"><?php endif; ?>
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
