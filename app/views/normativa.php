<?php
$ler = [
  ['15 01 01', 'Envases de papel y cartón', false],
  ['15 01 02', 'Envases de plástico', false],
  ['15 01 03', 'Envases de madera', false],
  ['15 01 04', 'Envases metálicos', false],
  ['15 01 05', 'Envases compuestos', false],
  ['15 01 06', 'Envases mezclados', false],
  ['15 01 07', 'Envases de vidrio', false],
  ['15 01 09', 'Envases textiles', false],
  ['15 01 10*', 'Envases que contienen restos de sustancias peligrosas o están contaminados por ellas', true],
  ['15 01 11*', 'Envases metálicos, incluidos los recipientes a presión vacíos, que contienen una matriz sólida y porosa peligrosa (p. ej., amianto)', true],
  ['15 02 02*', 'Absorbentes, materiales de filtración (incl. filtros de aceite), trapos de limpieza y ropas protectoras contaminados por sustancias peligrosas', true],
  ['15 02 03', 'Absorbentes, materiales de filtración, trapos de limpieza y ropas protectoras distintos de los del código 15 02 02', false],
];
$obligaciones = [
  ['Productores de producto', 'Obligaciones de quienes envasan o importan productos:', [
    'Registro en el RPP del MITECO.',
    'Adhesión a un SCRAP o creación de SIRAP antes del 31/12/2024.',
    'Declaración anual de envases puestos en el mercado.',
    'Contribución financiera a la gestión de residuos.',
    'Diseño para facilitar el reciclado y marcado informativo.',
    'Inclusión del número de envasador en facturas.',
  ]],
  ['SCRAP', 'Responsabilidades de los sistemas colectivos:', [
    'Organización y financiación del tratamiento de residuos.',
    'Firmar convenios con administraciones y gestores.',
    'Implementación de sistemas SDDR si corresponde.',
    'Informes a comunidades autónomas y transparencia web.',
  ]],
  ['Gestores de residuos', 'Obligaciones de empresas autorizadas:', [
    'Gestión conforme a normativa y buenas prácticas.',
    'Acuerdos con SCRAPs y productores.',
    'Registro y trazabilidad de residuos.',
  ]],
  ['Poseedores de residuos', 'Responsabilidades de grandes generadores y operadores:', [
    'Separar y entregar residuos correctamente.',
    'Posibles acuerdos con SCRAPs para facilitar la gestión.',
    'Informes anuales de trazabilidad del producto envasado.',
  ]],
];
?>
<section class="pagehero">
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">Marco legal</span>
    <h1 data-split>Normativa RAP y códigos LER</h1>
    <p>Todo lo que necesitas saber sobre el Real Decreto 1055/2022, de envases y residuos de envases, y la clasificación LER de la familia de envases.</p>
  </div>
</section>

<section class="section wrap">
  <div class="normativa-grid">
    <!-- LER -->
    <div class="ler-panel" data-reveal>
      <span class="kicker">Catálogo LER</span>
      <h2>Familia 15 · Envases</h2>
      <p class="panel-sub">Residuos de envases; absorbentes, trapos de limpieza, materiales de filtración y ropas de protección no especificados en otra categoría.</p>
      <ul class="ler-list">
        <?php foreach ($ler as [$cod, $desc, $pel]): ?>
          <li class="<?= $pel ? 'peligroso' : '' ?>"><strong><?= e($cod) ?></strong> <span><?= e($desc) ?></span></li>
        <?php endforeach; ?>
      </ul>
      <p class="ler-foot">* Los códigos marcados con asterisco corresponden a residuos peligrosos.</p>
    </div>

    <!-- OBLIGACIONES -->
    <div class="oblig-panel" data-reveal>
      <span class="kicker">Obligaciones</span>
      <h2>¿A quién obliga?</h2>
      <p class="panel-sub">Real Decreto 1055/2022, de 27 de diciembre, de envases y residuos de envases.</p>
      <a class="boe-link btn btn--ghost" href="https://www.boe.es/buscar/act.php?id=BOE-A-2022-22690" target="_blank" rel="noopener">Ver texto en el BOE ↗</a>
      <div class="oblig-accordion">
        <?php foreach ($obligaciones as $i => [$titulo, $intro, $items]): ?>
          <details class="oblig-item"<?= $i === 0 ? ' open' : '' ?>>
            <summary><?= e($titulo) ?></summary>
            <div class="oblig-body">
              <p><?= e($intro) ?></p>
              <ul>
                <?php foreach ($items as $it): ?><li><?= e($it) ?></li><?php endforeach; ?>
              </ul>
            </div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
