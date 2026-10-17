<?php
// Copy: CORE SCRAP/Copy_scrapenvases_Home_ParaTuSCRAP.docx · página A
// Estructura: CORE SCRAP/wireframe-home.html
$img = imagen_portal('home');
?>
<section class="hero--home" data-stage>
  <div class="stage">
    <picture class="stage__bg">
      <source media="(orientation: portrait)" srcset="<?= asset('/media/envases-v.webp') ?>">
      <img src="<?= asset('/media/envases.webp') ?>" srcset="<?= asset('/media/envases-1280.webp') ?> 1280w, <?= asset('/media/envases.webp') ?> 2560w" sizes="100vw" width="2560" height="1440" alt="" fetchpriority="high">
    </picture>
    <div class="stage__shade" aria-hidden="true"></div>
    <?php if ($img): ?>
      <img class="stage__logo" src="<?= e($img) ?>" alt="">
    <?php else: ?>
      <img class="stage__logo" src="<?= asset('/imagenes/web/logo-scrapenvases-red.webp') ?>" width="1448" height="486" alt="">
    <?php endif; ?>

    <div class="wrap stage__txt hero__txt">
      <h1 class="stage__title"><span class="stage__title-a">La RAP de envases,</span> <span class="stage__title-b">conectada y bajo control</span></h1>
      <p class="hero__sub">El portal del sector que reúne la normativa, la resolución de dudas y el ecosistema de herramientas —SCRAPP, INPROGEST y PROBATUS— con el que SCRAPs, gestores y poseedores operan la responsabilidad ampliada del productor.</p>
    </div>

    <div class="stage__core">
      <div class="core-diagram" role="group" aria-label="El core: PROBATUS, INPROGEST y SCRAPP">
        <p class="core-diagram__label">El core</p>
        <span class="core-diagram__wire" aria-hidden="true"></span>
        <?php foreach ([['probatus', 'Homologación de proveedores'], ['inprogest', 'Control documental'], ['scrapp', 'Trazabilidad de la RAP']] as [$id, $rol]): $h = herramienta($id); ?>
          <a class="core-diagram__piece core-diagram__piece--<?= $id ?>" href="<?= e($h['url']) ?>" target="_blank" rel="noopener">
            <?= logo_herramienta($h) ?>
            <span><?= e($rol) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <span class="stage__cue" aria-hidden="true"></span>
  </div>
</section>

<?php franja('Desde el <strong>1 de enero de 2025</strong>, la RAP se aplica también a los envases comerciales e industriales. Un marco nuevo que exige trazabilidad, homologación y control documental a todos los actores del sistema. scrapenvases.com reúne en un solo lugar lo que el sector necesita para cumplir: la normativa aplicable, un canal para resolver dudas y el ecosistema de herramientas que ya lo hace operativo.'); ?>

<div class="chevrons" data-chevrons aria-hidden="true">
  <div class="chevrons__track">
    <?php foreach (['almacen', 'comercial', 'planta', 'electrodomesticos', 'patio', 'operaciones'] as $f): ?>
      <span class="chevron"><?= foto($f, 'chevron__img', '(min-width: 961px) 34vw, 70vw') ?></span>
    <?php endforeach; ?>
  </div>
</div>

<div class="core-apps" data-opcion="<?= opcion_apps() ?>">
  <section class="sec core-apps__head">
    <div class="wrap">
      <header class="sec__head">
        <h2 class="core-head__title"><span class="core-head__a">El core:</span> <span class="core-head__b">tres piezas para las tres necesidades de la RAP</span></h2>
        <p>Homologar a los gestores, controlar la documentación de cada servicio y ordenar los documentos e incentivos de los poseedores. Tres necesidades del sistema, cubiertas por tres herramientas de INPRONET Solutions que encajan entre sí.</p>
      </header>
    </div>
  </section>
  <?php
  tarjeta_herramienta('probatus',
      'Evalúa, homologa y hace seguimiento de gestores y proveedores de forma homogénea, transparente y auditable.');
  tarjeta_herramienta('inprogest',
      'Coordina a los agentes implicados y centraliza la documentación de cada servicio para el cumplimiento legal.');
  tarjeta_herramienta('scrapp',
      'Conecta a poseedores, gestores y SCRAP y ordena el flujo de documentos e incentivos con la trazabilidad que exige la norma.');
  cierre_core();
  ?>
</div>

<section class="sec sec--roles">
  <div class="wrap">
    <header class="sec__head">
      <h2><?= resaltar('Elige tu papel en el sistema', 'tu papel') ?></h2>
    </header>
    <div class="roles" data-roles>
      <a class="role role--main" href="/para-tu-scrap" style="--c: #45d69e">
        <?= foto('operaciones', 'role__img', '(min-width: 961px) 45vw, 100vw') ?>
        <h3>SCRAP</h3>
        <p>Homologa, controla la documentación y gestiona los incentivos con el respaldo del core.</p>
        <span class="role__go">→ Para tu SCRAP</span>
      </a>
      <a class="role" href="/gestores" style="--c: #3cc4f2">
        <?= foto('planta', 'role__img', '(min-width: 961px) 45vw, 100vw') ?>
        <h3>Gestor</h3>
        <p>Intégrate en el sistema y aporta tu trazabilidad de forma homogénea.</p>
        <span class="role__go">→ Gestores</span>
      </a>
      <a class="role" href="/poseedores" style="--c: #ffa25a">
        <?= foto('almacen', 'role__img', '(min-width: 961px) 45vw, 100vw') ?>
        <h3>Poseedor</h3>
        <p>Ordena la documentación de tus residuos de envases y cumple sin fricción.</p>
        <span class="role__go">→ Poseedores</span>
      </a>
    </div>
  </div>
</section>

<?php banda_ots('¿Dudas sobre tus obligaciones?',
    'La Oficina Técnica de SCRAPs (OTS) acompaña a productores, poseedores y gestores en el cumplimiento de la RAP de envases.',
    'Consultar con la OTS'); ?>
