<?php require_once __DIR__ . '/../data/demos_db.php'; $demos = demos_all('gestores'); ?>
<section class="pagehero">
  <div class="pagehero__ring" aria-hidden="true"></div>
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">Servicios</span>
    <h1 data-split>Gestores de residuos</h1>
    <p>Con la implantación de la RAP para los envases comerciales e industriales, los gestores de residuos juegan un papel clave en la cadena.</p>
  </div>
</section>

<section class="section wrap">
  <div class="prose" style="max-width:820px; margin-bottom:3rem" data-reveal>
    <p style="font-size:var(--step-1); color:var(--ink-muted)">Los gestores se enfrentan a <b class="coloruno">nuevos requisitos de trazabilidad</b>, <b class="colordos">coordinación con SCRAP y reporting normativo</b>, que exigen una adaptación técnica y operativa. Desde nuestra empresa, ayudamos a los gestores a integrarse de forma eficaz y conforme a la normativa en este nuevo modelo, optimizando su papel dentro del sistema y reforzando su propuesta de valor.</p>
  </div>
  <div class="duo" data-reveal-group>
    <div class="servicecard">
      <div class="servicecard__media"><img src="/imagenes/scrapp-gestores.svg" alt="SCRAPP para gestores"></div>
      <div class="servicecard__body"><span class="servicecard__logo">SCRAPP</span><p>Garantizando una gestión documental fluida, segura y conforme a las exigencias normativas.</p></div>
    </div>
    <div class="servicecard">
      <div class="servicecard__media"><img src="/imagenes/probatus-gestores.svg" alt="PROBATUS para gestores"></div>
      <div class="servicecard__body"><span class="servicecard__logo">PROBATUS</span><p>Utiliza un canal único para operar con distintos SCRAPs de manera ágil y eficiente.</p></div>
    </div>
  </div>
</section>

<!-- DEMO: la aplicación en acción -->
<?php $demoLead = 'Vídeos y capturas de la plataforma para gestores.'; require __DIR__ . '/partials/demo-section.php'; ?>

<section class="section wrap">
  <div class="ctaband" data-reveal>
    <h2>Refuerza tu propuesta de valor</h2>
    <p>Integra tu operativa con SCRAP y productores de forma trazable y conforme.</p>
    <div style="margin-top:1.5rem"><a class="btn btn--light" href="/normativa">Ver obligaciones normativas</a></div>
  </div>
</section>
