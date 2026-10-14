<?php
// Página existente que el mapa del portal mantiene ("Ya existe · reforzar"):
// LER 15 01, obligaciones por perfil y plazos. Las claves de la franja salen del copy aprobado.
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
    ], null],
    ['SCRAP', 'Responsabilidades de los sistemas colectivos:', [
        'Organización y financiación del tratamiento de residuos.',
        'Firmar convenios con administraciones y gestores.',
        'Implementación de sistemas SDDR si corresponde.',
        'Informes a comunidades autónomas y transparencia web.',
    ], ['/para-tu-scrap', 'Para tu SCRAP']],
    ['Gestores de residuos', 'Obligaciones de empresas autorizadas:', [
        'Gestión conforme a normativa y buenas prácticas.',
        'Acuerdos con SCRAPs y productores.',
        'Registro y trazabilidad de residuos.',
    ], ['/gestores', 'Gestores']],
    ['Poseedores de residuos', 'Responsabilidades de grandes generadores y operadores:', [
        'Separar y entregar residuos correctamente.',
        'Posibles acuerdos con SCRAPs para facilitar la gestión.',
        'Informes anuales de trazabilidad del producto envasado.',
    ], ['/poseedores', 'Poseedores']],
];

hero('normativa',
    'Marco legal',
    'Normativa RAP y códigos LER',
    'Todo lo que necesitas saber sobre el Real Decreto 1055/2022, de envases y residuos de envases, y la clasificación LER de la familia de envases.',
    [['Ver texto en el BOE ↗', 'https://www.boe.es/buscar/act.php?id=BOE-A-2022-22690', 'ghost']]);
?>

<section class="strip">
  <div class="wrap keys">
    <div class="key">
      <p class="key__dato">1 ene 2025</p>
      <p>Desde el 1 de enero de 2025, la RAP se aplica también a los envases comerciales e industriales.</p>
    </div>
    <div class="key">
      <p class="key__dato">LER 15 01</p>
      <p>La familia LER 15 01 (envases), no los códigos del grupo 20.</p>
    </div>
    <div class="key">
      <p class="key__dato">3–5 €/t</p>
      <p>Una compensación (entre 3 y 5 €/t) por aportar la trazabilidad del residuo. Corresponde por ley al poseedor final, no al gestor.</p>
    </div>
  </div>
</section>

<section class="sec">
  <div class="wrap norm">
    <div class="norm__ler">
      <p class="kicker">Catálogo LER</p>
      <h2>Familia 15 · Envases</h2>
      <p class="norm__sub">Residuos de envases; absorbentes, trapos de limpieza, materiales de filtración y ropas de protección no especificados en otra categoría.</p>
      <table class="ler">
        <thead><tr><th scope="col">Código</th><th scope="col">Descripción</th></tr></thead>
        <tbody>
          <?php foreach ($ler as [$cod, $desc, $pel]): ?>
            <tr<?= $pel ? ' class="ler--pel"' : '' ?>><td><?= e($cod) ?></td><td><?= e($desc) ?></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <p class="norm__foot">* Los códigos marcados con asterisco corresponden a residuos peligrosos.</p>
    </div>

    <div class="norm__oblig">
      <p class="kicker">Obligaciones</p>
      <h2>¿A quién obliga?</h2>
      <p class="norm__sub">Real Decreto 1055/2022, de 27 de diciembre, de envases y residuos de envases.</p>
      <div class="faq">
        <?php foreach ($obligaciones as $i => [$titulo, $intro, $items, $enlace]): ?>
          <details class="faq__item"<?= $i === 0 ? ' open' : '' ?>>
            <summary><?= e($titulo) ?></summary>
            <p><?= e($intro) ?></p>
            <ul>
              <?php foreach ($items as $it): ?><li><?= e($it) ?></li><?php endforeach; ?>
            </ul>
            <?php if ($enlace): ?><a class="faq__more" href="<?= $enlace[0] ?>">→ <?= e($enlace[1]) ?></a><?php endif; ?>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php banda_ots('¿Dudas sobre tus obligaciones?',
    'La Oficina Técnica de SCRAPs (OTS) acompaña a productores, poseedores y gestores en el cumplimiento de la RAP de envases.',
    'Consultar con la OTS'); ?>
