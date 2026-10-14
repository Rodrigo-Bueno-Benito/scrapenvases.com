<?php
/**
 * Bloques que se repiten entre páginas. El texto siempre llega desde la
 * página que los usa, copiado literal de los documentos de CORE SCRAP/.
 */

/** Logo de la herramienta; INPROGEST aún no tiene logo y se compone en texto. */
function logo_herramienta(array $h, string $clase = ''): string {
    if ($h['logo']) {
        [$w, $hh] = getimagesize(__DIR__ . '/../public' . $h['logo']) ?: [0, 0];
        return '<img class="tlogo tlogo--' . $h['id'] . ' ' . $clase . '" src="' . e($h['logo']) . '" alt="' . e($h['nombre'])
            . '" width="' . $w . '" height="' . $hh . '" loading="lazy">';
    }
    return '<span class="tlogo tlogo--' . $h['id'] . ' tlogo--txt ' . $clase . '" role="img" aria-label="' . e($h['nombre']) . '">'
        . '<b>INPRO</b>GEST</span>';
}

/** Tarjeta de herramienta del bloque "El core". */
function tarjeta_herramienta(string $id, string $rol, string $texto): void {
    $h = herramienta($id); ?>
    <article class="tool tool--<?= $id ?>">
      <a class="tool__logo" href="<?= e($h['url']) ?>" target="_blank" rel="noopener"><?= logo_herramienta($h) ?></a>
      <h3 class="tool__name"><?= e($h['nombre']) ?></h3>
      <p class="tool__role"><?= e($rol) ?></p>
      <p class="tool__txt"><?= e($texto) ?></p>
      <a class="tool__link" href="<?= e($h['url']) ?>" target="_blank" rel="noopener">→ <?= e($h['dominio']) ?></a>
    </article>
<?php }

/**
 * Bloque necesidad → solución de las páginas de perfil.
 * $enlaces: lista de [id_herramienta, texto_del_enlace].
 */
function necesidad(string $titulo, string $rol, string $contexto, string $solucion, array $enlaces, string $color): void { ?>
    <article class="need need--<?= $color ?>">
      <div class="need__head">
        <h3 class="need__title"><?= e($titulo) ?></h3>
        <p class="need__role"><?= e($rol) ?></p>
      </div>
      <p class="need__ctx"><?= $contexto ?></p>
      <div class="need__sol">
        <p><?= e($solucion) ?></p>
        <?php foreach ($enlaces as [$id, $txt]): $h = herramienta($id); ?>
          <a href="<?= e($h['url']) ?>" target="_blank" rel="noopener"><?= e($txt) ?></a>
        <?php endforeach; ?>
      </div>
    </article>
<?php }

/** Banda de cierre con llamada a la Oficina Técnica de SCRAPs. */
function banda_ots(string $titulo, string $texto, string $boton): void { ?>
    <section class="band-wrap">
      <div class="wrap">
        <div class="band">
          <div class="band__txt">
            <h2><?= e($titulo) ?></h2>
            <p><?= e($texto) ?></p>
          </div>
          <a class="btn btn--primary" href="/contacto"><?= e($boton) ?></a>
        </div>
      </div>
    </section>
<?php }

/** Cabecera de página: kicker + H1 + subtítulo + CTA, con hueco de imagen real. */
function hero(string $pagina, string $kicker, string $h1, string $sub, array $ctas, bool $centrado = false): void {
    $img = $centrado ? null : imagen_portal($pagina); ?>
    <section class="hero<?= $centrado ? ' hero--center' : '' ?><?= $img ? ' hero--img' : '' ?>">
      <div class="wrap hero__in">
        <div class="hero__txt">
          <p class="kicker"><?= e($kicker) ?></p>
          <h1><?= e($h1) ?></h1>
          <p class="hero__sub"><?= e($sub) ?></p>
          <?php if ($ctas): ?>
          <div class="hero__ctas">
            <?php foreach ($ctas as [$txt, $href, $tipo]): ?>
              <a class="btn btn--<?= $tipo ?>" href="<?= e($href) ?>"><?= e($txt) ?></a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php if ($img): ?>
          <figure class="hero__img"><img src="<?= e($img) ?>" alt=""></figure>
        <?php endif; ?>
      </div>
    </section>
<?php }

/** Franja de contexto (fondo diferenciado, ancho de lectura). */
function franja(string $html): void { ?>
    <section class="strip"><div class="wrap"><p><?= $html ?></p></div></section>
<?php }
