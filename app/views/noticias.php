<?php
require_once __DIR__ . '/../data/noticias_db.php';
$catFiltro = isset($_GET['cat']) ? trim((string)$_GET['cat']) : null;
$categorias = noticias_categorias();
$destacada = $catFiltro ? null : noticia_destacada();
$todas = noticias_publicadas(100, $catFiltro);
if ($destacada) {
    $todas = array_values(array_filter($todas, fn($n) => $n['id'] !== $destacada['id']));
}
/* Las 3 primeras acompañan a la portada como "tendencias"; el resto va a la hemeroteca */
$tendencias = $destacada ? array_slice($todas, 0, 3) : [];
$hemeroteca = $destacada ? array_slice($todas, 3) : $todas;
?>
<section class="pagehero">
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">Actualidad</span>
    <h1 data-split>Noticias del mundo de los envases</h1>
    <p>Normativa, digitalización, gestión y economía circular. La actualidad de la RAP de envases comerciales e industriales.</p>
  </div>
</section>

<section class="section wrap">
  <?php if ($categorias): ?>
  <nav class="catfilter" aria-label="Filtrar por categoría">
    <a class="catfilter__item<?= !$catFiltro ? ' is-active' : '' ?>" href="/noticias"<?= !$catFiltro ? ' aria-current="page"' : '' ?>>Todas</a>
    <?php foreach ($categorias as $c): ?>
      <a class="catfilter__item<?= $catFiltro === $c ? ' is-active' : '' ?>" href="/noticias?cat=<?= urlencode($c) ?>"<?= $catFiltro === $c ? ' aria-current="page"' : '' ?>><?= e($c) ?></a>
    <?php endforeach; ?>
  </nav>
  <?php endif; ?>

  <?php if (db_is_down()): ?>
    <div class="alert alert--error">La base de datos no está disponible ahora mismo. Inténtalo de nuevo en unos minutos.</div>
  <?php endif; ?>

  <?php if ($destacada): ?>
  <!-- Portada + tendencias (estilo PDR) -->
  <header class="edi-head" data-reveal>
    <div class="edi-head__title">
      <span class="num">§ 01</span>
      <h2>En <em>portada</em></h2>
    </div>
  </header>

  <div class="edi-grid" style="margin-bottom:clamp(3rem,6vw,4.5rem);<?= !$tendencias ? 'grid-template-columns:1fr' : '' ?>">
    <article class="edi-lead" data-reveal>
      <a class="edi-media" tabindex="-1" aria-hidden="true" href="/noticia/<?= e($destacada['slug']) ?>">
        <img src="<?= e($src = noticia_img_src($destacada['imagen'])) ?>" alt="<?= e($destacada['titulo']) ?>"<?= img_attrs($src) ?>>
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

    <?php if ($tendencias): ?>
    <div class="edi-list" data-reveal-group>
      <?php foreach ($tendencias as $n): ?>
      <article class="edi-item">
        <div>
          <div class="edi-meta">
            <span class="etag etag--outline"><?= e($n['categoria']) ?></span>
            <span class="dot"></span>
            <span><?= e(fecha_es($n['fecha_publicacion'])) ?></span>
          </div>
          <h3 class="edi-title"><a href="/noticia/<?= e($n['slug']) ?>"><?= e($n['titulo']) ?></a></h3>
        </div>
        <a class="edi-media" tabindex="-1" aria-hidden="true" href="/noticia/<?= e($n['slug']) ?>">
          <img src="<?= e($src = noticia_img_src($n['imagen'])) ?>" alt="" loading="lazy"<?= img_attrs($src) ?>>
        </a>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <?php if ($hemeroteca): ?>
  <!-- Hemeroteca -->
  <header class="edi-head" data-reveal>
    <div class="edi-head__title">
      <span class="num">§ <?= $destacada ? '02' : '01' ?></span>
      <h2><?= $catFiltro ? 'En <em>' . e($catFiltro) . '</em>' : 'Todas las <em>noticias</em>' ?></h2>
    </div>
  </header>
  <div class="edi-cardgrid" data-reveal-group>
    <?php foreach ($hemeroteca as $n): ?>
    <article class="edi-card">
      <a class="edi-media" tabindex="-1" aria-hidden="true" href="/noticia/<?= e($n['slug']) ?>">
        <img src="<?= e($src = noticia_img_src($n['imagen'])) ?>" alt="<?= e($n['titulo']) ?>" loading="lazy"<?= img_attrs($src) ?>>
      </a>
      <div class="edi-meta">
        <span class="etag etag--outline"><?= e($n['categoria']) ?></span>
        <span class="dot"></span>
        <span><?= e(fecha_es($n['fecha_publicacion'])) ?></span>
      </div>
      <h3 class="edi-title"><a href="/noticia/<?= e($n['slug']) ?>"><?= e($n['titulo']) ?></a></h3>
      <p class="edi-excerpt"><?= e($n['extracto'] ?: excerpt($n['cuerpo'], 120)) ?></p>
      <div><a href="/noticia/<?= e($n['slug']) ?>" class="link-arrow">Leer <span class="arrow">→</span></a></div>
    </article>
    <?php endforeach; ?>
  </div>
  <?php elseif (!$destacada): ?>
    <div class="empty">
      <h3>Aún no hay noticias publicadas</h3>
      <p>Vuelve pronto: iremos publicando la actualidad del sector de los envases.</p>
    </div>
  <?php endif; ?>
</section>
