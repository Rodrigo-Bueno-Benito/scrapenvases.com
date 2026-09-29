<?php
$current = $_GET['p'] ?? 'home';
$nav = [
    'scrap'      => 'SCRAP',
    'gestores'   => 'Gestores',
    'poseedores' => 'Poseedores',
    'normativa'  => 'Normativa',
    'noticias'   => 'Noticias',
];
$titles = [
    'home' => 'ScrapEnvases — La solución digital ante un nuevo reto',
    'scrap' => 'Servicios para SCRAP · ScrapEnvases',
    'gestores' => 'Gestores de residuos · ScrapEnvases',
    'poseedores' => 'Poseedores de residuos · ScrapEnvases',
    'normativa' => 'Normativa RAP y códigos LER · ScrapEnvases',
    'noticias' => 'Noticias del mundo de los envases · ScrapEnvases',
];
$pageTitle = $titles[$current] ?? 'ScrapEnvases';
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="Soluciones digitales para la Responsabilidad Ampliada del Productor (RAP) en envases comerciales e industriales: SCRAP, gestores, poseedores y normativa.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Hanken+Grotesk:wght@400;500;700;800&family=Barlow+Condensed:wght@600;700&family=Dancing+Script:wght@600&family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/tokens.css">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="/css/components.css">
    <link rel="stylesheet" href="/css/pages.css">
    <link rel="icon" href="/imagenes/logoScrap.png">
</head>
<body>
<div class="scrollbar-progress" aria-hidden="true"></div>
<header class="site-header">
  <div class="wrap site-header__bar">
    <a class="site-header__logo" href="/" aria-label="ScrapEnvases — inicio">
      <img src="/imagenes/logoScrap.png" alt="ScrapEnvases">
    </a>
    <p class="site-header__tagline">La solución digital ante un nuevo reto</p>
    <button class="navtoggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="mainnav">
      <span></span><span></span><span></span>
    </button>
    <nav class="mainnav" id="mainnav" aria-label="Navegación principal">
      <?php foreach ($nav as $slug => $label): ?>
        <a href="/<?= e($slug) ?>"<?= $current === $slug ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
      <?php if (is_admin()): ?>
        <a href="/admin">Panel</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main id="contenido">
