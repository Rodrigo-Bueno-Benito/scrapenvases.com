<?php
/**
 * Bloques que se repiten entre páginas. El texto siempre llega desde la
 * página que los usa, copiado literal de los documentos de CORE SCRAP/.
 */

/** Foto de cabecera de cada página (public/media/, generadas para el portal). */
const FOTOS_HERO = [
    'para-tu-scrap' => 'operaciones',
    'poseedores'    => 'almacen',
    'gestores'      => 'planta',
    'normativa'     => 'documentos',
];

/** Icono de trazo (24 px): la flecha necesidad → solución. */
function icono(string $nombre): string {
    $trazos = [
        'flecha' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
    ];
    return '<svg class="ico" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
        . $trazos[$nombre] . '</svg>';
}

/**
 * Foto decorativa de public/media/ con sus dos tamaños (960 px y completo).
 * $sizes describe el ancho con que se muestra; $prioridad la carga antes que el resto.
 */
function foto(string $nombre, string $clase, string $sizes = '100vw', bool $prioridad = false): string {
    $pequena = asset("/media/$nombre-960.webp");
    $grande  = asset("/media/$nombre.webp");
    return '<img class="' . $clase . '" src="' . $grande . '" srcset="' . $pequena . ' 960w, ' . $grande . ' 1920w"'
        . ' sizes="' . $sizes . '" alt="" decoding="async"'
        . ($prioridad ? ' fetchpriority="high"' : ' loading="lazy"') . '>';
}

/** Escapa un titular y, si se indica, pone en segundo tono el fragmento que se resalta. */
function resaltar(string $texto, ?string $parte = null): string {
    $i = $parte === null ? false : mb_strpos($texto, $parte);
    if ($i === false) return e($texto);
    return e(mb_substr($texto, 0, $i)) . '<span class="hl">' . e($parte) . '</span>' . e(mb_substr($texto, $i + mb_strlen($parte)));
}

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

/**
 * Opción de fondo y animación de los apartados de las herramientas (para comparar):
 * 1 = "Mundo del vídeo" (oscuro, motivos del vídeo de lanzamiento) · 2 = "Luz de marca" (claro).
 */
function opcion_apps(): int {
    return ($_GET['opcion'] ?? '') === '2' ? 2 : 1;
}

/**
 * Apartado propio de cada herramienta: su color, su texto y su pantalla real
 * (fragmento del vídeo de lanzamiento, aplanado). Con WebGL, journey.js los convierte en
 * las tres paradas del viaje 3D; sin él, se apilan uno tras otro.
 */
function tarjeta_herramienta(string $id, string $texto): void {
    $h = herramienta($id); ?>
    <section class="app app--<?= $id ?>" data-app>
      <div class="app__bg" aria-hidden="true">
        <span class="app__aurora"></span>
        <span class="app__word" data-word="<?= e($h['nombre']) ?>"></span>
      </div>
      <div class="wrap app__in">
        <div class="app__txt">
          <a class="app__logo" href="<?= e($h['url']) ?>" target="_blank" rel="noopener" data-logo3d="<?= $id ?>"><?= logo_herramienta($h) ?></a>
          <h3 class="app__name sr"><?= e($h['nombre']) ?></h3>
          <p class="app__desc"><?= e($texto) ?></p>
          <a class="app__link" href="<?= e($h['url']) ?>" target="_blank" rel="noopener">→ <?= e($h['dominio']) ?></a>
        </div>
        <figure class="app__screen" aria-hidden="true">
          <span class="app__bar"><i></i><i></i><i></i></span>
          <video muted loop playsinline preload="none" poster="<?= asset("/media/apps/$id.webp") ?>" data-app-video>
            <source src="<?= asset("/media/apps/$id.webm") ?>" type="video/webm">
            <source src="<?= asset("/media/apps/$id.mp4") ?>" type="video/mp4">
          </video>
        </figure>
      </div>
    </section>
<?php }

/** Cierre del core: la red completa. En el viaje 3D aparece sobre el mapa final iluminado. */
function cierre_core(): void { ?>
    <section class="core-apps__end">
      <div class="wrap">
        <h2 class="core-end__title"><span class="core-end__a">Trazabilidad</span> <span class="core-end__b">total</span></h2>
        <p class="core-end__sub">SCRAP, gestores y poseedores conectados en una misma red: los datos de toda la RAP de envases, en vivo.</p>
      </div>
    </section>
<?php }

/**
 * Bloque necesidad → solución de las páginas de perfil: un panel con la foto de su escena
 * ($foto, de public/media/; perfil/* son las del viaje de la portada con la pantalla real de
 * la herramienta en el portátil) y el texto encima.
 * $enlaces: lista de [id_herramienta, texto_del_enlace].
 */
function necesidad(string $titulo, string $rol, string $contexto, string $solucion, array $enlaces, string $color, string $foto): void { ?>
    <article class="need need--<?= $color ?>" data-need>
      <?= foto($foto, 'need__img', '(min-width: 1280px) 1200px, 100vw') ?>
      <div class="need__in">
        <div class="need__head">
          <h3 class="need__title"><?= e($titulo) ?></h3>
          <p class="need__role"><?= e($rol) ?></p>
        </div>
        <p class="need__ctx"><?= $contexto ?></p>
        <div class="need__sol">
          <span class="need__arrow" aria-hidden="true"><?= icono('flecha') ?></span>
          <p><?= e($solucion) ?></p>
          <div class="need__links">
            <?php foreach ($enlaces as [$id, $txt]): $h = herramienta($id); ?>
              <a href="<?= e($h['url']) ?>" target="_blank" rel="noopener"><?= e($txt) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </article>
<?php }

/** Banda de cierre con llamada a la Oficina Técnica de SCRAPs: su equipo asesorando a productor, poseedor y gestor. */
function banda_ots(string $titulo, string $texto, string $boton): void { ?>
    <section class="band-wrap">
      <?= foto('ots', 'band-wrap__bg') ?>
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

/**
 * Cabecera de las páginas interiores, como la portada: su foto a pantalla completa, oscurecida,
 * y el titular en dos voces ($a en mayúsculas expandidas, $b en cursiva con el color de la
 * página). El titular es $a . ' ' . $b: el texto del documento de copy, sin cambios.
 * $cta: [texto, enlace, tipo de botón] o vacío; $extra pinta contenido bajo el botón. Si se sube
 * una foto real a /imagenes/portal/, sustituye a la del portal.
 */
function cabecera(string $pagina, string $a, string $b, string $sub, array $cta = [], ?Closure $extra = null): void {
    $real = imagen_portal($pagina); ?>
    <section class="phero" data-phero>
      <div class="phero__media" aria-hidden="true">
        <?= $real ? '<img class="phero__img" src="' . e($real) . '" alt="" fetchpriority="high">' : foto(FOTOS_HERO[$pagina], 'phero__img', '100vw', true) ?>
      </div>
      <div class="wrap phero__txt">
        <h1 class="phero__title"><span class="phero__a"><?= e($a) ?></span> <span class="phero__b"><?= e($b) ?></span></h1>
        <p class="phero__sub"><?= e($sub) ?></p>
        <?php if ($cta): ?>
          <a class="btn btn--<?= $cta[2] ?? 'primary' ?> phero__cta" href="<?= e($cta[1]) ?>"><?= e($cta[0]) ?></a>
        <?php endif; ?>
        <?php if ($extra): ?><div class="phero__extra"><?php $extra(); ?></div><?php endif; ?>
      </div>
      <span class="phero__cue" aria-hidden="true"></span>
    </section>
<?php }

/** Franja de contexto: una frase grande que se "enciende" palabra a palabra al leerla. */
function franja(string $html): void { ?>
    <section class="strip"><div class="wrap"><p data-reveal><?= $html ?></p></div></section>
<?php }
