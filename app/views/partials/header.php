<?php
$current = $_GET['p'] ?? 'home';

/* Navegación del portal (orden del wireframe: el CORE primero, perfiles después) */
$nav = [
    'el-core'       => 'El CORE',
    'demos'         => 'Demos',
    'para-tu-scrap' => 'Para tu SCRAP',
    'poseedores'    => 'Poseedores',
    'gestores'      => 'Gestores',
    'normativa'     => 'Normativa',
    'noticias'      => 'Noticias',
];

/* Solo seis páginas montan demos: las demás no cargan sus 33 KB de CSS ni su JS */
const PAGINAS_CON_DEMO = ['home', 'demos', 'el-core', 'para-tu-scrap', 'poseedores', 'gestores'];
$tieneDemo = in_array($current, PAGINAS_CON_DEMO, true);
/* La obertura y el filmstrip solo existen en la portada */
$esPortada = $current === 'home';

/* <title> y meta description propios por página */
$meta = [
    'home' => [
        'La RAP de envases, conectada y bajo control · ScrapEnvases',
        'Portal del sector para la RAP de envases comerciales e industriales: normativa, resolución de dudas y el ecosistema de herramientas —SCRAPP, INPROGEST y PROBATUS— con el que SCRAPs, gestores y poseedores operan el RD 1055/2022.',
    ],
    'el-core' => [
        'El CORE: el ecosistema que ya opera la RAP de envases · ScrapEnvases',
        'PROBATUS homologa, INPROGEST controla la documentación y SCRAPP traza el flujo de documentos e incentivos. Tres herramientas que cubren de punta a punta lo que la RAP de envases exige.',
    ],
    'para-tu-scrap' => [
        'Todo lo que un SCRAP de envases necesita para operar · ScrapEnvases',
        'Homologación de gestores, control documental de cada servicio y gestión de los incentivos del poseedor, en un ecosistema ya operativo y sin construir una plataforma propia desde cero.',
    ],
    'poseedores' => [
        'Tus residuos de envases, en orden y a disposición de tu SCRAP · ScrapEnvases',
        'Organiza la documentación de tus residuos de envases, ponla a disposición de tu SCRAP y cumple el RD 1055/2022 sin cambiar de gestores. Y cobra el incentivo que te corresponde por ley.',
    ],
    'gestores' => [
        'Tu papel en el nuevo modelo de RAP de envases · ScrapEnvases',
        'Homológate una vez, entrega la documentación de cada servicio validada y reporta por apoderamiento desde un único canal, sin multiplicar el trabajo por cada SCRAP.',
    ],
    'contacto' => [
        'Habla con la Oficina Técnica de SCRAPs (OTS) · ScrapEnvases',
        'Dudas sobre tus obligaciones en la RAP de envases, sobre el core o sobre cómo empezar. La OTS acompaña a SCRAPs, gestores y poseedores. Cuéntanos tu caso y te orientamos.',
    ],
    'demos' => [
        'Demos en vivo de PROBATUS, INPROGEST y SCRAPP · ScrapEnvases',
        'Prueba las tres herramientas del core sin registrarte: homologa un gestor, valida la documentación de un servicio y liquida el incentivo de un poseedor.',
    ],
    'normativa' => [
        'Normativa RAP y códigos LER 15 01 · ScrapEnvases',
        'Códigos LER de la familia 15 y obligaciones del RD 1055/2022 por perfil: productor, SCRAP, gestor y poseedor de residuos de envases comerciales e industriales.',
    ],
    'noticias' => [
        'Actualidad de la RAP de envases · ScrapEnvases',
        'Noticias, normativa y análisis del sector de los envases comerciales e industriales y su Responsabilidad Ampliada del Productor.',
    ],
    'aviso-legal'          => ['Aviso legal · ScrapEnvases', 'Aviso legal de scrapenvases.com.'],
    'politica-privacidad'  => ['Política de privacidad · ScrapEnvases', 'Información sobre el tratamiento de datos personales en scrapenvases.com (RGPD y LOPDGDD).'],
    'politica-cookies'     => ['Política de cookies · ScrapEnvases', 'Tipos de cookies que utiliza scrapenvases.com y cómo gestionarlas.'],
    'acceso'               => ['Acceso al panel · ScrapEnvases', 'Área privada del equipo de ScrapEnvases.'],
];

/* El detalle de noticia titula con la propia noticia. Se carga aquí porque
   el <head> se imprime antes de la vista; noticia.php reutiliza este dato. */
if ($current === 'noticia' && isset($_GET['slug'])) {
    require_once __DIR__ . '/../../data/noticias_db.php';
    $slugHead = preg_replace('/[^a-z0-9-]/i', '', (string)$_GET['slug']);
    $GLOBALS['__noticia'] = $slugHead ? noticia_by_slug($slugHead) : null;
    if ($GLOBALS['__noticia'] && $GLOBALS['__noticia']['estado'] === 'publicada') {
        $nHead = $GLOBALS['__noticia'];
        $meta['noticia'] = [
            $nHead['titulo'] . ' · ScrapEnvases',
            $nHead['extracto'] ?: excerpt($nHead['cuerpo'], 155),
        ];
        $ogImage = noticia_img_src($nHead['imagen']);
    }
}

$pageTitle = $meta[$current][0] ?? 'ScrapEnvases — portal de la RAP de envases';
$pageDesc  = $meta[$current][1] ?? 'Portal del sector para la RAP de envases comerciales e industriales: normativa, dudas y el ecosistema de herramientas del core.';
$ogImage   = $ogImage ?? '/imagenes/banner_fusionado.png';
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Antes de cualquier hoja de estilo: marca que hay JS para que las
         entradas por scroll puedan partir de opacidad 0. Sin esta clase
         el contenido se muestra tal cual, nunca en blanco. -->
    <script>document.documentElement.className += ' js';</script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDesc) ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDesc) ?>">
    <meta property="og:type" content="<?= $current === 'noticia' ? 'article' : 'website' ?>">
    <meta property="og:locale" content="es_ES">
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@75..112,400..800&family=Public+Sans:wght@400;500;600;700&family=Spline+Sans+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/base.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/layout.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/pages.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/portal.css') ?>">
<?php if ($tieneDemo): ?>
    <link rel="stylesheet" href="<?= asset('/css/app-demo.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/app-skin.css') ?>">
<?php endif; ?>
<?php if ($esPortada): ?>
    <link rel="stylesheet" href="<?= asset('/css/film.css') ?>">
<?php endif; ?>
    <link rel="stylesheet" href="<?= asset('/css/motion.css') ?>">
    <link rel="icon" href="<?= asset('/imagenes/favicon.svg') ?>" type="image/svg+xml">
    <link rel="alternate icon" href="<?= asset('/imagenes/logoScrap.webp') ?>">
</head>
<body>
<div class="scrollbar-progress" aria-hidden="true"></div>
<a class="skiplink" href="#contenido">Saltar al contenido</a>
<header class="site-header">
  <div class="wrap site-header__bar">
    <a class="site-header__logo" href="/" aria-label="ScrapEnvases — inicio">
      <picture>
        <source srcset="<?= asset('/imagenes/logoScrap.webp') ?>" type="image/webp">
        <img src="<?= asset('/imagenes/logoScrap.png') ?>" alt="ScrapEnvases" width="306" height="38" fetchpriority="high">
      </picture>
    </a>
    <p class="site-header__tagline">Portal de la RAP de envases</p>
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
      <a class="mainnav__cta" href="/contacto"<?= $current === 'contacto' ? ' aria-current="page"' : '' ?>>Contacto · OTS</a>
    </nav>
  </div>
</header>
<main id="contenido">
