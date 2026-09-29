<?php
/**
 * Las tres piezas del core, en tarjeta. Reutilizable en Home y /el-core.
 *
 * Variables opcionales:
 *   $coreOrden  'home'  → SCRAPP · INPROGEST · PROBATUS (orden del copy de Home)
 *               'flujo' → PROBATUS · INPROGEST · SCRAPP (orden del flujo 01→02→03)
 *   $coreAmbito true    → añade el "· para SCRAP y gestores" de la página El CORE
 *   $coreLayout 'rows'  → índice técnico en filas (Home)
 *               'bento' → bento asimétrico, primera pieza destacada (/el-core)
 *
 * Las tres piezas se identifican con marca tipográfica, no con sus logos:
 * los archivos tienen proporciones muy distintas (SCRAPP cuadrado, PROBATUS
 * lockup horizontal) y juntos quedan descompensados. Los logos reales se
 * usan en el panel del hero y en el pie. INPROGEST no tiene logo todavía.
 */
$coreOrden  = $coreOrden ?? 'home';
$coreAmbito = $coreAmbito ?? false;
$coreLayout = $coreLayout ?? 'rows';

$tools = [
    'scrapp' => [
        'nombre'  => 'SCRAPP',
        'rol'     => 'Trazabilidad de la RAP',
        'ambito'  => 'para SCRAP y poseedores',
        'texto'   => 'Conecta a poseedores, gestores y SCRAP y ordena el flujo de documentos e incentivos con la trazabilidad que exige la norma.',
        'url'     => 'https://scrapp.es/',
        'dominio' => 'scrapp.es',
        'logo'    => '/imagenes/logoScrapp.png',
        'inicial' => 'S',
    ],
    'inprogest' => [
        'nombre'  => 'INPROGEST',
        'rol'     => 'Control documental',
        'ambito'  => 'para SCRAP y gestores',
        'texto'   => 'Coordina a los agentes implicados y centraliza la documentación de cada servicio para el cumplimiento legal.',
        'url'     => 'https://inprogest.com/',
        'dominio' => 'inprogest.com',
        'logo'    => null,
        'inicial' => 'I',
    ],
    'probatus' => [
        'nombre'  => 'PROBATUS',
        'rol'     => 'Homologación de proveedores',
        'ambito'  => 'para SCRAP y gestores',
        'texto'   => 'Evalúa, homologa y hace seguimiento de gestores y proveedores de forma homogénea, transparente y auditable.',
        'url'     => 'https://probatus.es/',
        'dominio' => 'probatus.es',
        'logo'    => '/imagenes/logoProbatus.png',
        'inicial' => 'P',
    ],
];

$orden = $coreOrden === 'flujo'
    ? ['probatus', 'inprogest', 'scrapp']
    : ['scrapp', 'inprogest', 'probatus'];
?>
<div class="coregrid<?= $coreLayout === 'bento' ? ' coregrid--bento' : '' ?>" data-reveal-group>
  <?php foreach ($orden as $k): $t = $tools[$k]; ?>
    <a class="toolcard toolcard--<?= e($k) ?>" href="<?= e($t['url']) ?>" target="_blank" rel="noopener">
      <div class="toolcard__head">
        <span class="toolcard__wordmark toolcard__wordmark--<?= e($k) ?>" aria-hidden="true"><?= e($t['inicial']) ?></span>
        <span class="toolcard__name"><?= e($t['nombre']) ?></span>
      </div>
      <p class="toolcard__rol">
        <?= e($t['rol']) ?><?php if ($coreAmbito): ?> <span class="toolcard__ambito">· <?= e($t['ambito']) ?></span><?php endif; ?>
      </p>
      <p class="toolcard__text"><?= e($t['texto']) ?></p>
      <span class="toolcard__more"><?= e($t['dominio']) ?> <span class="arrow" aria-hidden="true">→</span></span>
    </a>
  <?php endforeach; ?>
</div>
