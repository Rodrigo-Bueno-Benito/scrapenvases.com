<?php require_once __DIR__ . '/../data/demos_db.php'; $demos = demos_all('scrap'); ?>
<section class="pagehero">
  <div class="pagehero__ring" aria-hidden="true"></div>
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">Servicios</span>
    <h1 data-split>Servicios para SCRAP</h1>
    <p>En el nuevo contexto normativo de la Responsabilidad Ampliada del Productor (RAP), los SCRAP de envases comerciales e industriales se enfrentan a grandes retos técnicos, logísticos y administrativos.</p>
  </div>
</section>

<section class="section wrap">
  <div class="prose" style="max-width:820px; margin-bottom:3rem" data-reveal>
    <p style="font-size:var(--step-1); color:var(--ink-muted)">Nuestro objetivo es convertirnos en su <b>socio estratégico</b>, aportando soluciones que les permitan cumplir con sus obligaciones, <b class="colordos">optimizar sus procesos y generar valor para sus productores adheridos y poseedores finales</b>. Ofrecemos un <b class="coloruno">acompañamiento especializado y flexible</b>, adaptado a las particularidades de cada SCRAP y al entorno cambiante del sector.</p>
  </div>
  <div class="duo" data-reveal-group>
    <div class="servicecard">
      <div class="servicecard__media"><img src="/imagenes/scrapp-scrap.svg" alt="SCRAPP para SCRAP"></div>
      <div class="servicecard__body"><span class="servicecard__logo">SCRAPP</span><p>Automatiza la coordinación con todos los agentes implicados y centraliza la información y documentación necesaria para el cumplimiento legal.</p></div>
    </div>
    <div class="servicecard">
      <div class="servicecard__media"><img src="/imagenes/probatus-scrap.svg" alt="PROBATUS para SCRAP"></div>
      <div class="servicecard__body"><span class="servicecard__logo">PROBATUS</span><p>Evalúa, homologa y audita a tus proveedores de forma homogénea y transparente.</p></div>
    </div>
  </div>
</section>

<!-- DEMO: la aplicación en acción -->
<?php $demoLead = 'Vídeos y capturas de la plataforma para SCRAP.'; require __DIR__ . '/partials/demo-section.php'; ?>

<section class="section wrap">
  <div class="ctaband" data-reveal>
    <h2>¿Diriges un SCRAP?</h2>
    <p>Hablemos de cómo digitalizar tu operativa y cumplir la RAP sin fricciones.</p>
    <div style="margin-top:1.5rem"><a class="btn btn--light" href="/noticias">Ver actualidad del sector</a></div>
  </div>
</section>
