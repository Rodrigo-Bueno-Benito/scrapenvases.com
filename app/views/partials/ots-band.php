<?php
/**
 * Banda de cierre con la Oficina Técnica de SCRAPs (OTS).
 * Es el cierre común de todas las páginas del portal.
 *
 * Variables opcionales: $otsTitulo, $otsTexto, $otsCta
 */
$otsTitulo = $otsTitulo ?? '¿Dudas sobre tus obligaciones?';
$otsTexto  = $otsTexto  ?? 'La Oficina Técnica de SCRAPs (OTS) acompaña a productores, poseedores y gestores en el cumplimiento de la RAP de envases.';
$otsCta    = $otsCta    ?? 'Consultar con la OTS';
?>
<section class="section wrap">
  <div class="ctaband" data-reveal>
    <h2><?= e($otsTitulo) ?></h2>
    <p><?= e($otsTexto) ?></p>
    <div style="margin-top:1.5rem"><a class="btn btn--light" href="/contacto"><?= e($otsCta) ?></a></div>
  </div>
</section>
