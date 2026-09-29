<?php
require_once __DIR__ . '/../data/demos_db.php';
// La demo es la prueba de que el ecosistema ya opera: su sitio natural
// es esta página, no las de perfil.
$demos = demos_all('scrap');
?>
<!-- 1 · HERO centrado — "ya existe, ya funciona" -->
<section class="pagehero pagehero--center">
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">El ecosistema del core</span>
    <h1 data-split>El core: el ecosistema que ya opera la RAP de envases</h1>
    <p>Tres herramientas que encajan entre sí —SCRAPP, INPROGEST y PROBATUS— para cubrir, de punta a punta, lo que la RAP de envases comerciales e industriales exige a SCRAPs, gestores y poseedores.</p>
    <div class="pagehero__actions">
      <a class="btn btn--primary btn--lg" href="/contacto">Consultar con la OTS</a>
    </div>
  </div>
</section>

<!-- 2 · CONTEXTO — no es una promesa, es un sistema en marcha -->
<section class="contextband">
  <div class="wrap-narrow" data-reveal>
    <p>El core no es un producto nuevo ni una promesa de futuro: es un conjunto de herramientas de INPRONET Solutions que <strong>ya está en marcha</strong> y resuelve las tres tareas que la RAP obliga a hacer bien —homologar a quien interviene, controlar la documentación de cada servicio y trazar el flujo de residuos e incentivos. scrapenvases.com es su escaparate y su puerta de entrada.</p>
  </div>
</section>

<!-- 3 · UNA NECESIDAD, UNA PIEZA — orden de flujo P → I → S -->
<section class="section wrap">
  <div class="sectionhead" data-reveal>
    <div>
      <span class="kicker">Las tres piezas</span>
      <h2>Una necesidad, una pieza</h2>
    </div>
    <p>Cada herramienta cubre una de las tres tareas que la norma exige hacer bien, y las tres comparten la misma trazabilidad.</p>
  </div>

  <?php $coreOrden = 'flujo'; $coreAmbito = true; $coreLayout = 'bento'; require __DIR__ . '/partials/core-tools.php'; ?>
</section>

<!-- 4 · CÓMO ENCAJAN · flujo 01 → 02 → 03 -->
<section class="section section--alt">
  <div class="wrap">
    <div class="sectionhead" data-reveal>
      <div>
        <span class="kicker">Cómo encajan</span>
        <h2>Una sola trazabilidad, compartida</h2>
      </div>
      <p>El dato se introduce una vez y sirve a las tres piezas: el poseedor genera la documentación, el gestor homologado la entrega limpia y el SCRAP la controla, reporta y libera el incentivo.</p>
    </div>

    <ol class="coreflow" data-reveal-group>
      <li class="coreflow__step">
        <span class="coreflow__num">01</span>
        <h3>Homologar</h3>
        <p>Gestores y proveedores verificados y auditables.</p>
        <span class="coreflow__tool">PROBATUS</span>
      </li>
      <li class="coreflow__step">
        <span class="coreflow__num">02</span>
        <h3>Documentar</h3>
        <p>DI, fichas y certificados centralizados y validados.</p>
        <span class="coreflow__tool">INPROGEST</span>
      </li>
      <li class="coreflow__step">
        <span class="coreflow__num">03</span>
        <h3>Trazar y liquidar</h3>
        <p>Trazabilidad completa e incentivo al poseedor.</p>
        <span class="coreflow__tool">SCRAPP</span>
      </li>
    </ol>
  </div>
</section>

<!-- DEMO EN VIVO — la prueba de que ya opera -->
<section class="section demoshow">
  <div class="wrap">
    <div class="sectionhead" data-reveal>
      <div>
        <span class="kicker">La prueba</span>
        <h2>El core, en marcha ahora mismo</h2>
      </div>
      <p>No es una maqueta ni un vídeo: son las pantallas de los portales que ya están en producción, con datos de ejemplo. Pruébalas.</p>
    </div>
    <div data-reveal>
      <?php require __DIR__ . '/partials/demo-switch.php'; ?>
    </div>
  </div>
</section>

<!-- 5 · QUÉ APORTA A CADA ACTOR — matriz -->
<section class="section wrap">
  <div class="sectionhead" data-reveal>
    <div>
      <span class="kicker">Por actor</span>
      <h2>Qué aporta a cada actor</h2>
    </div>
    <p>El mismo ecosistema, leído desde la obligación de cada uno.</p>
  </div>

  <dl class="actormatrix" data-reveal-group>
    <div class="actormatrix__row">
      <dt>SCRAP</dt>
      <dd>Homologación de gestores, control documental y gestión de incentivos, sin montar una plataforma propia desde cero.</dd>
    </div>
    <div class="actormatrix__row">
      <dt>Gestor</dt>
      <dd>Una homologación y un canal de reporte, en lugar de uno por cada SCRAP.</dd>
    </div>
    <div class="actormatrix__row">
      <dt>Poseedor</dt>
      <dd>Documentación ordenada, a disposición de su SCRAP, y su incentivo cobrado.</dd>
    </div>
  </dl>
</section>

<!-- DEMO: la prueba de que ya opera -->
<?php $demoLead = 'Vídeos y capturas de las herramientas del core en funcionamiento.'; require __DIR__ . '/partials/demo-section.php'; ?>

<!-- 6 · CIERRE · OTS -->
<?php
$otsTitulo = 'El core ya funciona';
$otsTexto  = 'scrapenvases.com es la puerta para conocerlo y empezar a usarlo, con el acompañamiento de la Oficina Técnica de SCRAPs (OTS).';
require __DIR__ . '/partials/ots-band.php';
?>
