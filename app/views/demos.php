<?php
require_once __DIR__ . '/../data/demos_db.php';
// Vídeos y capturas que suba el equipo desde el panel, si hay
$demos = demos_all();
?>
<section class="pagehero">
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">Demos en vivo</span>
    <h1 data-split>Evalúa las herramientas por tu cuenta</h1>
    <p>Las pantallas reales de las tres herramientas, funcionando aquí mismo: homologa un gestor, concilia un traslado con RAMON y comprueba cómo el tope de tu tarifa cambia lo que se te liquida. Sin registro y sin comercial de por medio.</p>
  </div>
</section>

<section class="section wrap">
  <div class="sectionhead" data-reveal>
    <div>
      <span class="kicker">Las tres piezas</span>
      <h2>Elige una herramienta y pruébala</h2>
    </div>
    <p>No son maquetas: reproducen las pantallas de los portales que ya están en producción —el del SCRAP y el del poseedor— con datos de ejemplo.</p>
  </div>

  <div data-reveal>
    <?php require __DIR__ . '/partials/demo-switch.php'; ?>
  </div>
</section>

<!-- Qué acabas de probar -->
<section class="section section--alt">
  <div class="wrap">
    <div class="sectionhead" data-reveal>
      <div>
        <span class="kicker">Lo que acabas de hacer</span>
        <h2>Las tres tareas que la RAP obliga a hacer bien</h2>
      </div>
      <p>El mismo dato recorre las tres piezas: se introduce una vez y sirve para homologar, documentar y liquidar.</p>
    </div>

    <dl class="actormatrix" data-reveal-group>
      <div class="actormatrix__row">
        <dt>Homologar</dt>
        <dd>Has abierto el expediente de un gestor, revisado sus requisitos y lo has homologado con una puntuación defendible ante inspección. En PROBATUS eso mismo se hace sobre cientos de gestores.</dd>
      </div>
      <div class="actormatrix__row">
        <dt>Documentar</dt>
        <dd>Has abierto un servicio de recogida y completado su juego documental —DI, nota de traslado, certificado de tratamiento y ticket de pesaje—, conciliando con RAMON lo que estaba pendiente. Es la pantalla del portal del SCRAP.</dd>
      </div>
      <div class="actormatrix__row">
        <dt>Trazar y liquidar</dt>
        <dd>Has cambiado de centro, editado toneladas y visto el tope de cada material consumirse. Lo que pasa del tope aparece como excedente: se recoge y se traza, pero no entra en la liquidación. Es la pantalla del portal del poseedor.</dd>
      </div>
    </dl>
  </div>
</section>

<!-- Vídeos y capturas subidos desde el panel -->
<?php if (!empty($demos)): ?>
  <?php $demoLead = 'Grabaciones y capturas de las herramientas en uso real.'; require __DIR__ . '/partials/demo-section.php'; ?>
<?php endif; ?>

<?php
$otsTitulo = '¿Lo quieres sobre tus datos?';
$otsTexto  = 'La Oficina Técnica de SCRAPs te monta una sesión con tu cartera de gestores, tus servicios y tus toneladas reales.';
$otsCta    = 'Pedir una demo con mis datos';
require __DIR__ . '/partials/ots-band.php';
?>
