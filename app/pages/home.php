<?php
// Copy: CORE SCRAP/Copy_scrapenvases_Home_ParaTuSCRAP.docx · página A
// Estructura: CORE SCRAP/wireframe-home.html
$img = imagen_portal('home');
?>
<section class="hero hero--home<?= $img ? ' hero--img' : '' ?>">
  <div class="wrap hero__in">
    <div class="hero__txt">
      <p class="kicker">RAP de envases comerciales e industriales · RD 1055/2022</p>
      <h1>La RAP de envases, conectada y bajo control</h1>
      <p class="hero__sub">El portal del sector que reúne la normativa, la resolución de dudas y el ecosistema de herramientas —SCRAPP, INPROGEST y PROBATUS— con el que SCRAPs, gestores y poseedores operan la responsabilidad ampliada del productor.</p>
      <div class="hero__ctas">
        <a class="btn btn--primary" href="/el-core">Conoce el CORE</a>
        <a class="btn btn--ghost" href="/contacto">Consultar con la OTS</a>
      </div>
    </div>
    <?php if ($img): ?>
      <figure class="hero__img"><img src="<?= e($img) ?>" alt=""></figure>
    <?php else: ?>
      <div class="core-diagram" role="group" aria-label="El core: PROBATUS, INPROGEST y SCRAPP">
        <p class="core-diagram__label">El core</p>
        <?php foreach ([['probatus', 'Homologación de proveedores'], ['inprogest', 'Control documental'], ['scrapp', 'Trazabilidad de la RAP']] as [$id, $rol]): $h = herramienta($id); ?>
          <a class="core-diagram__piece core-diagram__piece--<?= $id ?>" href="<?= e($h['url']) ?>" target="_blank" rel="noopener">
            <?= logo_herramienta($h) ?>
            <span><?= e($rol) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php franja('Desde el <strong>1 de enero de 2025</strong>, la RAP se aplica también a los envases comerciales e industriales. Un marco nuevo que exige trazabilidad, homologación y control documental a todos los actores del sistema. scrapenvases.com reúne en un solo lugar lo que el sector necesita para cumplir: la normativa aplicable, un canal para resolver dudas y el ecosistema de herramientas que ya lo hace operativo.'); ?>

<section class="sec">
  <div class="wrap">
    <header class="sec__head">
      <h2>El core: tres piezas para las tres necesidades de la RAP</h2>
      <p>Homologar a los gestores, controlar la documentación de cada servicio y ordenar los documentos e incentivos de los poseedores. Tres necesidades del sistema, cubiertas por tres herramientas de INPRONET Solutions que encajan entre sí.</p>
    </header>
    <div class="tools">
      <?php
      tarjeta_herramienta('scrapp', 'Trazabilidad de la RAP',
          'Conecta a poseedores, gestores y SCRAP y ordena el flujo de documentos e incentivos con la trazabilidad que exige la norma.');
      tarjeta_herramienta('inprogest', 'Control documental',
          'Coordina a los agentes implicados y centraliza la documentación de cada servicio para el cumplimiento legal.');
      tarjeta_herramienta('probatus', 'Homologación de proveedores',
          'Evalúa, homologa y hace seguimiento de gestores y proveedores de forma homogénea, transparente y auditable.');
      ?>
    </div>
  </div>
</section>

<section class="sec sec--alt">
  <div class="wrap">
    <header class="sec__head">
      <h2>Elige tu papel en el sistema</h2>
    </header>
    <div class="roles">
      <a class="role role--main" href="/para-tu-scrap">
        <h3>SCRAP</h3>
        <p>Homologa, controla la documentación y gestiona los incentivos con el respaldo del core.</p>
        <span class="role__go">→ Para tu SCRAP</span>
      </a>
      <a class="role" href="/gestores">
        <h3>Gestor</h3>
        <p>Intégrate en el sistema y aporta tu trazabilidad de forma homogénea.</p>
        <span class="role__go">→ Gestores</span>
      </a>
      <a class="role" href="/poseedores">
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
