<?php require_once __DIR__ . '/../data/demos_db.php'; $demos = demos_all('gestores'); ?>
<!-- 1 · HERO — ángulo de oportunidad y servicio, no de miedo -->
<section class="pagehero">
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">Para gestores de residuos</span>
    <h1 data-split>Tu papel en el nuevo modelo de RAP de envases</h1>
    <p>El sistema te pide entregar la documentación de cada servicio en el formato que necesitan los SCRAP y reportar su trazabilidad. scrapenvases.com te ayuda a homologarte, integrarte y reportar sin multiplicar el trabajo por cada SCRAP.</p>
    <div class="pagehero__actions">
      <a class="btn btn--primary btn--lg" href="/contacto">Hablar con la OTS</a>
      <a class="btn btn--light" href="/el-core">Ver el CORE</a>
    </div>
  </div>
</section>

<!-- 2 · CONTEXTO — la documentación limpia como ventaja competitiva -->
<section class="contextband">
  <div class="wrap-narrow" data-reveal>
    <p>Con la RAP de envases comerciales e industriales, tus clientes poseedores necesitan de ti una documentación limpia —documento de identificación, ficha de seguimiento, certificado de reciclado efectivo— para reportar al SCRAP y cobrar su incentivo. Entregarla bien y reportar por apoderamiento <strong>refuerza tu relación con el cliente</strong> y tu posición frente a gestores no homologados.</p>
  </div>
</section>

<!-- 3 · TRES NECESIDADES → HERRAMIENTA -->
<?php
$needsKicker = 'Tu integración en el sistema';
$needsTitulo = 'Homológate, entrega y reporta';
$needsLead   = 'Lo que el nuevo modelo te pide, y cómo hacerlo una sola vez.';
$needs = [
    [
        'marca'    => 'P',
        'titulo'   => 'Homológate una vez',
        'tool'     => 'PROBATUS · homologación',
        'dolor'    => 'Cada SCRAP homologa a los gestores con los que trabaja: requisitos legales, técnicos, ambientales y administrativos. Repetir ese proceso, uno por uno, multiplica el trabajo.',
        'solucion' => 'Homológate de forma homogénea y mantén tu documentación al día en un solo sistema.',
        'enlace'   => ['texto' => 'vía PROBATUS', 'url' => 'https://probatus.es/'],
    ],
    [
        'marca'    => 'I',
        'titulo'   => 'Entrega la documentación limpia',
        'tool'     => 'INPROGEST · control documental',
        'dolor'    => 'La documentación de cada servicio tiene que llegar bien codificada (LER 15 01) y en el formato que el SCRAP puede procesar. Los errores generan devoluciones y retrasos.',
        'solucion' => 'Genera y entrega la documentación de cada servicio, validada y lista para el SCRAP.',
        'enlace'   => ['texto' => 'vía INPROGEST', 'url' => 'https://inprogest.com/'],
    ],
    [
        'marca'    => 'S',
        'titulo'   => 'Reporta por apoderamiento',
        'tool'     => 'INPROGEST · SCRAPP · reporte',
        'dolor'    => 'Puedes ofrecer a tus clientes reportar su trazabilidad al SCRAP en su nombre, como servicio de valor. Hoy eso implica operar en una plataforma distinta por cada SCRAP.',
        'solucion' => 'Reporta en nombre de tus clientes desde un único canal, sin duplicar homologaciones ni plataformas.',
        'enlace'   => ['texto' => 'vía SCRAPP', 'url' => 'https://scrapp.es/'],
    ],
];
require __DIR__ . '/partials/needs.php';
?>

<!-- DEMO EN VIVO -->
<section class="section demoshow">
  <div class="wrap">
    <div class="sectionhead" data-reveal>
      <div>
        <span class="kicker">Demo en vivo</span>
        <h2>Mira cómo te homologa un SCRAP</h2>
      </div>
      <p>Este es el expediente que el SCRAP abre sobre ti: requisitos, vigencias y puntuación. Ábrelo y comprueba qué se revisa.</p>
    </div>
    <div data-reveal>
      <?php require __DIR__ . '/partials/demo-probatus.php'; ?>
      <p class="demonote">Simulación con datos de ejemplo. Nada de lo que hagas aquí sale de tu navegador.</p>
    </div>
  </div>
</section>

<!-- DEMO: la aplicación en acción -->
<?php $demoLead = 'Vídeos y capturas de la plataforma para gestores.'; require __DIR__ . '/partials/demo-section.php'; ?>

<!-- 4 · CIERRE · OTS -->
<?php
$otsTitulo = 'Un solo sistema, no uno por cada SCRAP';
$otsTexto  = 'Con el acompañamiento técnico de la Oficina Técnica de SCRAPs (OTS).';
$otsCta    = 'Hablar con la OTS';
require __DIR__ . '/partials/ots-band.php';
?>
