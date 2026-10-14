<?php
/**
 * Front controller del portal scrapenvases.com.
 * Mapa del portal (CORE SCRAP/README.md) + páginas legales y panel de consultas.
 */
require_once __DIR__ . '/../app/helpers.php';

const PAGINAS = [
    'home' => [
        'title' => 'La RAP de envases, conectada y bajo control · scrapenvases.com',
        'desc'  => 'El portal del sector que reúne la normativa, la resolución de dudas y el ecosistema de herramientas —SCRAPP, INPROGEST y PROBATUS— con el que SCRAPs, gestores y poseedores operan la responsabilidad ampliada del productor.',
    ],
    'el-core' => [
        'title' => 'El CORE: el ecosistema que ya opera la RAP de envases · scrapenvases.com',
        'desc'  => 'Tres herramientas que encajan entre sí —SCRAPP, INPROGEST y PROBATUS— para cubrir, de punta a punta, lo que la RAP de envases comerciales e industriales exige a SCRAPs, gestores y poseedores.',
    ],
    'para-tu-scrap' => [
        'title' => 'Para tu SCRAP · scrapenvases.com',
        'desc'  => 'El core reúne las tres funciones críticas de un SCRAP —homologación de gestores, control documental e incentivos del poseedor— en un ecosistema ya operativo.',
    ],
    'poseedores' => [
        'title' => 'Poseedores de residuos de envases · scrapenvases.com',
        'desc'  => 'Organiza la documentación de tus residuos de envases, ponla a disposición de tu SCRAP y cumple el RD 1055/2022 —sin cambiar de gestores.',
    ],
    'gestores' => [
        'title' => 'Gestores de residuos · scrapenvases.com',
        'desc'  => 'Homológate, intégrate y reporta sin multiplicar el trabajo por cada SCRAP.',
    ],
    'normativa' => [
        'title' => 'Normativa RAP y códigos LER · scrapenvases.com',
        'desc'  => 'Real Decreto 1055/2022, de envases y residuos de envases: obligaciones por perfil y clasificación LER de la familia 15 01.',
    ],
    'contacto' => [
        'title' => 'Contacto · Oficina Técnica de SCRAPs (OTS) · scrapenvases.com',
        'desc'  => '¿Dudas sobre tus obligaciones en la RAP de envases, sobre el core o sobre cómo empezar? La OTS acompaña a SCRAPs, gestores y poseedores.',
    ],
    'aviso-legal'         => ['title' => 'Aviso legal · scrapenvases.com', 'desc' => 'Aviso legal de scrapenvases.com.', 'legal' => true],
    'politica-privacidad' => ['title' => 'Política de privacidad · scrapenvases.com', 'desc' => 'Política de privacidad de scrapenvases.com.', 'legal' => true],
    'politica-cookies'    => ['title' => 'Política de cookies · scrapenvases.com', 'desc' => 'Política de cookies de scrapenvases.com.', 'legal' => true],
];

/** Rutas anteriores al rediseño: se conservan con 301 para no romper enlaces. */
const REDIRECCIONES = [
    'scrap'    => '/para-tu-scrap',
    'core'     => '/el-core',
    'ots'      => '/contacto',
    'noticias' => '/',
    'noticia'  => '/',
    'demos'    => '/el-core',
];

$ruta = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');
$ruta = $ruta === '' || $ruta === 'index.php' ? 'home' : strtolower($ruta);
$base = explode('/', $ruta)[0];

if (isset(REDIRECCIONES[$base])) {
    header('Location: ' . REDIRECCIONES[$base], true, 301);
    exit;
}

if ($ruta === 'contacto' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/../app/consultas.php';
    consulta_recibir();
}

if (in_array($ruta, ['admin', 'acceso', 'salir'], true)) {
    require __DIR__ . '/../app/admin.php';
    exit;
}

$pagina = PAGINAS[$ruta] ?? null;
if ($pagina === null) {
    http_response_code(404);
    $ruta = '404';
    $pagina = ['title' => 'Página no encontrada · scrapenvases.com', 'desc' => ''];
}

require __DIR__ . '/../app/layout/header.php';
require __DIR__ . '/../app/pages/' . $ruta . '.php';
require __DIR__ . '/../app/layout/footer.php';
