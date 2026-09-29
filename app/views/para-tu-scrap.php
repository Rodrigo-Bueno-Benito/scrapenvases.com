<!-- 1 · HERO — perfil prioritario -->
<section class="pagehero">
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">Para Sistemas Colectivos (SCRAP)</span>
    <h1 data-split>Todo lo que un SCRAP de envases necesita para operar</h1>
    <p>El core reúne las tres funciones críticas de un SCRAP —homologación de gestores, control documental e incentivos del poseedor— en un ecosistema ya operativo, sin necesidad de construir una plataforma propia desde cero.</p>
    <div class="pagehero__actions">
      <a class="btn btn--primary btn--lg" href="/contacto">Hablar con la OTS</a>
      <a class="btn btn--light" href="/el-core">Ver el CORE</a>
    </div>
  </div>
</section>

<!-- 2 · CONTEXTO -->
<section class="contextband">
  <div class="wrap-narrow" data-reveal>
    <p>Un SCRAP de envases comerciales e industriales coordina a muchos actores, maneja grandes volúmenes de documentación y responde ante la Administración. El core cubre esas tres necesidades con herramientas que ya funcionan juntas.</p>
  </div>
</section>

<!-- 3 · TRES NECESIDADES → HERRAMIENTA -->
<?php
$needsKicker = 'Las tres funciones críticas';
$needsTitulo = 'Homologar, controlar documentación y gestionar incentivos';
$needsLead   = 'Cada necesidad, el contexto real que la genera y la pieza del core que la resuelve.';
$needs = [
    [
        'marca'    => 'P',
        'titulo'   => 'Homologar y controlar a tus gestores',
        'tool'     => 'PROBATUS · homologación',
        'dolor'    => 'Un SCRAP trabaja con decenas o cientos de gestores, cada uno con su propia documentación. Homologarlos y auditarlos de forma homogénea —y que el dato reportado sea defendible ante inspección— es un trabajo continuo.',
        'solucion' => 'PROBATUS evalúa, homologa y hace seguimiento de proveedores de forma homogénea, transparente y auditable.',
        'enlace'   => ['texto' => 'probatus.es', 'url' => 'https://probatus.es/'],
    ],
    [
        'marca'    => 'I',
        'titulo'   => 'Controlar la documentación de cada servicio',
        'tool'     => 'INPROGEST · control documental',
        'dolor'    => 'Cada servicio genera documentación —documento de identificación, ficha de seguimiento, certificado de reciclado efectivo— que hay que recopilar, validar, conservar y, además, reportar por partida doble: al propio SCRAP y al MITECO.',
        'solucion' => 'INPROGEST coordina a los agentes implicados y centraliza el control documental de los servicios contratados.',
        'enlace'   => ['texto' => 'inprogest.com', 'url' => 'https://inprogest.com/'],
    ],
    [
        'marca'    => 'S',
        'titulo'   => 'Gestionar documentos e incentivos del poseedor',
        'tool'     => 'SCRAPP · trazabilidad',
        'dolor'    => 'El incentivo por trazabilidad corresponde por ley al poseedor final, mientras que la documentación la genera el gestor. Ordenar ese flujo —miles de veces al año y con calidad auditable— es el núcleo operativo del sistema.',
        'solucion' => 'SCRAPP traza el flujo entre poseedor, gestor y SCRAP y ordena la documentación y los incentivos.',
        'enlace'   => ['texto' => 'scrapp.es', 'url' => 'https://scrapp.es/'],
    ],
];
require __DIR__ . '/partials/needs.php';
?>

<!-- DEMO EN VIVO — las tres, porque el SCRAP usa las tres -->
<section class="section demoshow">
  <div class="wrap">
    <div class="sectionhead" data-reveal>
      <div>
        <span class="kicker">Demos en vivo</span>
        <h2>Las tres funciones, funcionando</h2>
      </div>
      <p>Homologa un gestor, cierra el juego documental de un servicio y comprueba lo que ve tu poseedor. Es la operativa diaria de un SCRAP, aquí mismo.</p>
    </div>
    <div data-reveal>
      <?php require __DIR__ . '/partials/demo-switch.php'; ?>
    </div>
  </div>
</section>

<!-- 4 · CIERRE · OTS -->
<?php
$otsTitulo = 'Tres herramientas, un mismo ecosistema';
$otsTexto  = 'Con el acompañamiento técnico de la Oficina Técnica de SCRAPs (OTS).';
$otsCta    = 'Hablar con la OTS';
require __DIR__ . '/partials/ots-band.php';
?>
