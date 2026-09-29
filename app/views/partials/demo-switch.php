<?php
/**
 * Conmutador de las tres demos en vivo.
 * Cada demo arranca cuando se abre su pestaña, no antes.
 */
?>
<div data-demotabs>
  <div class="demotabs" role="tablist" aria-label="Herramientas del core">
    <button class="demotab" role="tab" id="tab-probatus" aria-controls="pan-probatus" aria-selected="true" type="button">
      <span class="demotab__k" aria-hidden="true">P</span> PROBATUS
    </button>
    <button class="demotab" role="tab" id="tab-inprogest" aria-controls="pan-inprogest" aria-selected="false" type="button">
      <span class="demotab__k" aria-hidden="true">I</span> INPROGEST
    </button>
    <button class="demotab" role="tab" id="tab-scrapp" aria-controls="pan-scrapp" aria-selected="false" type="button">
      <span class="demotab__k" aria-hidden="true">S</span> SCRAPP
    </button>
  </div>

  <div class="demopanel" role="tabpanel" id="pan-probatus" aria-labelledby="tab-probatus">
    <?php require __DIR__ . '/demo-probatus.php'; ?>
  </div>
  <div class="demopanel" role="tabpanel" id="pan-inprogest" aria-labelledby="tab-inprogest" hidden>
    <?php require __DIR__ . '/demo-inprogest.php'; ?>
  </div>
  <div class="demopanel" role="tabpanel" id="pan-scrapp" aria-labelledby="tab-scrapp" hidden>
    <?php require __DIR__ . '/demo-scrapp.php'; ?>
  </div>

  <p class="demonote">Simulación con datos de ejemplo. Nada de lo que hagas aquí sale de tu navegador.</p>
</div>
