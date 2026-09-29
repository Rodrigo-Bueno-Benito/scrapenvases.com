<?php
require_once __DIR__ . '/../data/noticias_db.php';
$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9-]/i', '', (string)$_GET['slug']) : '';
// El header ya la cargó para titular la página: se reutiliza en vez de repetir la consulta
$n = $GLOBALS['__noticia'] ?? ($slug ? noticia_by_slug($slug) : null);

if (!$n || $n['estado'] !== 'publicada') {
    http_response_code(404);
    ?>
    <section class="section wrap center">
      <span class="kicker" style="justify-content:center">Error 404</span>
      <h1>Noticia no encontrada</h1>
      <p class="pagehead__lead" style="margin-inline:auto">La noticia que buscas no existe o ya no está disponible.</p>
      <a class="btn btn--primary" href="/noticias">Volver a noticias</a>
    </section>
    <?php
    return;
}
$relacionadas = noticias_relacionadas((int)$n['id'], $n['categoria'], 3);

/* Tiempo de lectura aproximado: ~220 palabras / minuto */
$palabras = str_word_count(strip_tags($n['cuerpo'] ?? ''));
$minLectura = max(1, (int)ceil($palabras / 220));
$fechaTxt = fecha_es($n['fecha_publicacion']);
?>
<article class="article-edi">
  <header class="article-edi__head wrap-narrow">
    <a href="/noticias" class="article-edi__back">← Noticias</a>
    <div class="edi-meta">
      <span class="etag"><?= e($n['categoria']) ?></span>
    </div>
    <h1 class="article-edi__title"><?= e($n['titulo']) ?></h1>
    <?php if (!empty($n['extracto'])): ?>
      <p class="article-edi__lede"><?= e($n['extracto']) ?></p>
    <?php endif; ?>
    <div class="article-edi__byline">
      <span><?= e($fechaTxt) ?></span>
      <span class="dot"></span>
      <span><?= $minLectura ?> min de lectura</span>
    </div>
  </header>

  <figure class="article-edi__hero">
    <img src="<?= e($src = noticia_img_src($n['imagen'])) ?>" alt="<?= e($n['titulo']) ?>"<?= img_attrs($src) ?>>
  </figure>

  <div class="article-edi__body wrap-narrow">
    <?= $n['cuerpo'] /* HTML de confianza: solo lo escribe el admin */ ?>

    <footer class="article-edi__foot">
      <p class="article-edi__signoff">
        <em>Publicado en <strong><?= e($n['categoria']) ?></strong> el <?= e($fechaTxt) ?>.</em>
      </p>
    </footer>
  </div>
</article>

<?php if ($relacionadas): ?>
<section class="section related-edi">
  <div class="wrap">
    <header class="edi-head" data-reveal>
      <div class="edi-head__title">
        <span class="num">§ 02</span>
        <h2>Más en <em><?= e($n['categoria']) ?></em></h2>
      </div>
      <a href="/noticias?cat=<?= urlencode($n['categoria']) ?>" class="link-arrow">Ver sección <span class="arrow">→</span></a>
    </header>
    <div class="edi-cardgrid" data-reveal-group>
      <?php foreach ($relacionadas as $r): ?>
      <article class="edi-card">
        <a class="edi-media" tabindex="-1" aria-hidden="true" href="/noticia/<?= e($r['slug']) ?>">
          <img src="<?= e($src = noticia_img_src($r['imagen'])) ?>" alt="<?= e($r['titulo']) ?>" loading="lazy"<?= img_attrs($src) ?>>
        </a>
        <div class="edi-meta">
          <span class="etag etag--outline"><?= e($r['categoria']) ?></span>
          <span class="dot"></span>
          <span><?= e(fecha_es($r['fecha_publicacion'])) ?></span>
        </div>
        <h3 class="edi-title"><a href="/noticia/<?= e($r['slug']) ?>"><?= e($r['titulo']) ?></a></h3>
        <p class="edi-excerpt"><?= e($r['extracto'] ?: excerpt($r['cuerpo'], 120)) ?></p>
        <div><a href="/noticia/<?= e($r['slug']) ?>" class="link-arrow">Leer <span class="arrow">→</span></a></div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
