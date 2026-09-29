<?php
require_once __DIR__ . '/../data/noticias_db.php';
$destacada = noticia_destacada();
$ultimas = noticias_publicadas(5);
?>
<!-- HERO: la red de la RAP, viva -->
<section class="hero">
  <canvas class="hero__net" data-net aria-hidden="true"></canvas>
  <div class="hero__ring" aria-hidden="true"><span class="hero__ring-sat"></span></div>

  <div class="wrap hero__inner">
    <div>
      <span class="kicker">Responsabilidad Ampliada del Productor</span>
      <h1 class="hero__title" data-split>Dos soluciones,<br>una misión: <span class="acento">la RAP conectada y</span> <span class="hl">bajo control.<svg viewBox="0 0 200 12" preserveAspectRatio="none" aria-hidden="true"><path d="M2 9 C 55 3.5, 130 3, 198 6.5" pathLength="1"/></svg></span></h1>
      <p class="hero__lead" data-reveal style="--reveal-delay:.55s">Acompañamos a productores, SCRAP, gestores y poseedores en la nueva era de los envases comerciales e industriales. Digitalización, trazabilidad y cumplimiento, sin fricciones.</p>
      <div class="hero__actions" data-reveal style="--reveal-delay:.7s">
        <a class="btn btn--primary btn--lg" href="/normativa">Conoce la normativa</a>
        <a class="btn btn--light" href="/noticias">Ver actualidad</a>
      </div>
      <div class="hero__trust" data-reveal style="--reveal-delay:.85s">
        <div><strong>RAP</strong> Envases comerciales<br>e industriales</div>
        <div><strong data-count="2">0</strong> plataformas<br>digitales</div>
        <div><strong data-count="360" data-suffix="°">0°</strong> Trazabilidad<br>de extremo a extremo</div>
      </div>
    </div>

    <div class="hero__media" data-reveal="zoom" style="--reveal-delay:.35s">
      <div class="hero__card" data-tilt>
        <div class="row">
          <img src="/imagenes/logoScrapp.png" alt="SCRAPP">
          <span class="tag-on">Trazabilidad</span>
        </div>
        <p>Coordinación con todos los agentes y documentación centralizada para el cumplimiento legal.</p>
        <div class="row" style="margin-top:.5rem">
          <img src="/imagenes/logoProbatus.png" alt="PROBATUS">
          <span class="tag-on">Homologación</span>
        </div>
        <p>Evaluación, homologación y seguimiento de proveedores de forma homogénea y transparente.</p>
      </div>
    </div>
  </div>
  <div class="hero__wave" aria-hidden="true">
    <svg viewBox="0 0 1440 60" preserveAspectRatio="none"><path fill="var(--paper)" d="M0,32L120,29.3C240,27,480,21,720,26.7C960,32,1200,48,1320,56L1440,64L1440,64L0,64Z"></path></svg>
  </div>
</section>

<!-- POR QUÉ + PLATAFORMAS (fusionadas) -->
<section class="section wrap">
  <div class="sectionhead" data-reveal>
    <div>
      <span class="kicker">Por qué ScrapEnvases</span>
      <h2>Convertimos la norma en eficiencia</h2>
    </div>
    <p>Todo lo que necesitas para cumplir la RAP de envases y ganar control operativo.</p>
  </div>

  <div class="why-grid">
    <!-- Izquierda: los tres pilares numerados -->
    <div class="steps" data-reveal-group>
      <div class="step">
        <span class="step__num">01</span>
        <div>
          <h3>Trazabilidad total</h3>
          <p>Sigue cada residuo de extremo a extremo y ten la documentación siempre lista para auditoría.</p>
        </div>
        <span class="step__go" aria-hidden="true">→</span>
      </div>
      <div class="step">
        <span class="step__num">02</span>
        <div>
          <h3>Cumplimiento sin fricción</h3>
          <p>Registro, declaraciones y reporting normativo alineados con el RD 1055/2022, automatizados.</p>
        </div>
        <span class="step__go" aria-hidden="true">→</span>
      </div>
      <div class="step">
        <span class="step__num">03</span>
        <div>
          <h3>Todos conectados</h3>
          <p>Un único canal para coordinar productores, SCRAP, gestores y poseedores en tiempo real.</p>
        </div>
        <span class="step__go" aria-hidden="true">→</span>
      </div>
    </div>

    <!-- Derecha: las herramientas, una encima de otra -->
    <aside class="why-tools" data-reveal-group>
      <span class="why-tools__label">Nuestras plataformas</span>
      <a class="pcard" href="https://scrapp.es/" target="_blank" rel="noopener" data-tilt>
        <img class="pcard__logo" src="/imagenes/logoScrapp.png" alt="SCRAPP">
        <p>Trazabilidad inteligente para SCRAPs exigentes. La herramienta que convierte la gestión de la RAP en eficiencia operativa.</p>
        <span class="pcard__more">Descubrir SCRAPP →</span>
      </a>
      <a class="pcard" href="https://probatus.es/" target="_blank" rel="noopener" data-tilt>
        <img class="pcard__logo" src="/imagenes/logoProbatus.png" alt="PROBATUS">
        <p>Plataforma digital especializada en la homologación, evaluación y seguimiento de proveedores.</p>
        <span class="pcard__more">Descubrir PROBATUS →</span>
      </a>
    </aside>
  </div>
</section>

<!-- MÉTRICAS -->
<section class="wrap" style="padding-bottom:clamp(3rem,8vw,7rem)">
  <div class="statsband" data-reveal="zoom">
    <div class="s"><strong data-count="2022" data-plain>0</strong><span>RD 1055 de envases</span></div>
    <div class="s"><strong data-count="12">0</strong><span>códigos LER familia 15</span></div>
    <div class="s"><strong data-count="4">0</strong><span>agentes conectados</span></div>
    <div class="s"><strong data-count="100" data-suffix="%">0%</strong><span>documentación trazable</span></div>
  </div>
</section>

<!-- NOTICIAS: bloque editorial (estilo PDR) -->
<?php if ($destacada): ?>
<section class="section">
  <div class="wrap">
    <header class="edi-head" data-reveal>
      <div class="edi-head__title">
        <span class="num">§ 01</span>
        <h2>En <em>portada</em></h2>
      </div>
      <a href="/noticias" class="link-arrow">Todas las noticias <span class="arrow">→</span></a>
    </header>

    <?php $resto = array_slice(array_values(array_filter($ultimas, fn($n) => $n['id'] !== $destacada['id'])), 0, 3); ?>
    <div class="edi-grid" <?= !$resto ? 'style="grid-template-columns:1fr"' : '' ?>>
      <!-- Noticia de portada -->
      <article class="edi-lead" data-reveal>
        <a class="edi-media" href="/noticia/<?= e($destacada['slug']) ?>">
          <img src="<?= e(noticia_img_src($destacada['imagen'])) ?>" alt="<?= e($destacada['titulo']) ?>">
        </a>
        <div class="edi-meta">
          <span class="etag"><?= e($destacada['categoria']) ?></span>
          <span class="dot"></span>
          <span><?= e(fecha_es($destacada['fecha_publicacion'])) ?></span>
        </div>
        <h3 class="edi-title"><a href="/noticia/<?= e($destacada['slug']) ?>"><?= e($destacada['titulo']) ?></a></h3>
        <p class="edi-excerpt"><?= e($destacada['extracto'] ?: excerpt($destacada['cuerpo'], 240)) ?></p>
        <div><a href="/noticia/<?= e($destacada['slug']) ?>" class="link-arrow">Leer artículo <span class="arrow">→</span></a></div>
      </article>

      <!-- Tendencias -->
      <?php if ($resto): ?>
      <div class="edi-list" data-reveal-group>
        <?php foreach ($resto as $n): ?>
        <article class="edi-item">
          <div>
            <div class="edi-meta">
              <span class="etag etag--outline"><?= e($n['categoria']) ?></span>
              <span class="dot"></span>
              <span><?= e(fecha_es($n['fecha_publicacion'])) ?></span>
            </div>
            <h4 class="edi-title"><a href="/noticia/<?= e($n['slug']) ?>"><?= e($n['titulo']) ?></a></h4>
          </div>
          <a class="edi-media" href="/noticia/<?= e($n['slug']) ?>">
            <img src="<?= e(noticia_img_src($n['imagen'])) ?>" alt="" loading="lazy">
          </a>
        </article>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>
