<?php
// Copy: CORE SCRAP/Copy_scrapenvases_El_CORE.docx · página E
// Estructura: CORE SCRAP/wireframe-el-core.html
hero('el-core',
    'El ecosistema del core',
    'El core: el ecosistema que ya opera la RAP de envases',
    'Tres herramientas que encajan entre sí —SCRAPP, INPROGEST y PROBATUS— para cubrir, de punta a punta, lo que la RAP de envases comerciales e industriales exige a SCRAPs, gestores y poseedores.',
    [['Consultar con la OTS', '/contacto', 'primary']],
    true);

franja('El core no es un producto nuevo ni una promesa de futuro: es un conjunto de herramientas de INPRONET Solutions que <strong>ya está en marcha</strong> y resuelve las tres tareas que la RAP obliga a hacer bien —homologar a quien interviene, controlar la documentación de cada servicio y trazar el flujo de residuos e incentivos. scrapenvases.com es su escaparate y su puerta de entrada.');
?>

<section class="sec">
  <div class="wrap">
    <header class="sec__head">
      <h2>Una necesidad, una pieza</h2>
    </header>
    <div class="tools">
      <?php
      tarjeta_herramienta('probatus', 'Homologación · SCRAP y gestores',
          'Evalúa, homologa y hace seguimiento de gestores y proveedores de forma homogénea, transparente y auditable.');
      tarjeta_herramienta('inprogest', 'Control documental · SCRAP y gestores',
          'Coordina a los agentes implicados y centraliza la documentación de cada servicio para el cumplimiento legal.');
      tarjeta_herramienta('scrapp', 'Trazabilidad · SCRAP y poseedores',
          'Conecta a poseedores, gestores y SCRAP y ordena el flujo de documentos e incentivos con la trazabilidad que exige la norma.');
      ?>
    </div>
  </div>
</section>

<section class="sec sec--alt">
  <div class="wrap">
    <header class="sec__head">
      <h2>Una sola trazabilidad, compartida</h2>
      <p>El dato se introduce una vez y sirve a las tres piezas: el poseedor genera la documentación, el gestor homologado la entrega limpia y el SCRAP la controla, reporta y libera el incentivo.</p>
    </header>
    <ol class="flow" data-flow>
      <?php foreach ([
          ['01', 'Homologar', 'Gestores verificados y auditables.', 'probatus'],
          ['02', 'Documentar', 'DI, fichas y certificados validados.', 'inprogest'],
          ['03', 'Trazar y liquidar', 'Trazabilidad e incentivo al poseedor.', 'scrapp'],
      ] as [$n, $paso, $txt, $id]): $h = herramienta($id); ?>
        <li class="flow__step flow__step--<?= $id ?>">
          <span class="flow__n"><?= $n ?></span>
          <h3><?= e($paso) ?></h3>
          <p><?= e($txt) ?></p>
          <a class="flow__tool" href="<?= e($h['url']) ?>" target="_blank" rel="noopener"><?= e($h['nombre']) ?></a>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <header class="sec__head">
      <h2>Qué aporta a cada actor</h2>
    </header>
    <dl class="matrix">
      <div class="matrix__row">
        <dt><a href="/para-tu-scrap">SCRAP</a></dt>
        <dd>Homologación de gestores, control documental y gestión de incentivos, sin montar una plataforma propia desde cero.</dd>
      </div>
      <div class="matrix__row">
        <dt><a href="/gestores">Gestor</a></dt>
        <dd>Una homologación y un canal de reporte, en lugar de uno por cada SCRAP.</dd>
      </div>
      <div class="matrix__row">
        <dt><a href="/poseedores">Poseedor</a></dt>
        <dd>Documentación ordenada, a disposición de su SCRAP, y su incentivo cobrado.</dd>
      </div>
    </dl>
  </div>
</section>

<?php banda_ots('El core ya funciona.',
    'scrapenvases.com es la puerta para conocerlo y empezar a usarlo, con el acompañamiento de la Oficina Técnica de SCRAPs (OTS).',
    'Consultar con la OTS'); ?>
