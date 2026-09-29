<?php
/* Sección Demo reutilizable. Espera:
   $demos    = array de filas de demos (ya filtradas por sección)
   $demoLead = (opcional) texto bajo el título                       */
require_once __DIR__ . '/../../data/demos_db.php';
$demoLead = $demoLead ?? 'Vídeos y capturas de la plataforma.';
?>
<section class="section section--alt" id="demo">
  <div class="wrap">
    <div class="sectionhead" data-reveal>
      <div>
        <span class="kicker">Demo</span>
        <h2>La aplicación en acción</h2>
      </div>
      <p><?= e($demoLead) ?></p>
    </div>

    <?php if (empty($demos)): ?>
      <div class="empty" data-reveal>
        <h3>Próximamente</h3>
        <p>Estamos preparando la demo. Muy pronto podrás ver aquí vídeos y capturas de la aplicación.</p>
      </div>
    <?php else: ?>
      <div class="demogrid" data-reveal-group>
        <?php foreach ($demos as $d): $m = demo_media($d); ?>
          <figure class="demo-item">
            <div class="demo-item__media demo-item__media--<?= $m['kind'] === 'image' ? 'img' : 'video' ?>">
              <?php if ($m['kind'] === 'embed'): ?>
                <iframe src="<?= e($m['src']) ?>" title="<?= e($d['titulo']) ?>" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              <?php elseif ($m['kind'] === 'video-file'): ?>
                <video controls preload="metadata" src="<?= e($m['src']) ?>"></video>
              <?php else: ?>
                <a href="<?= e($m['src']) ?>" target="_blank" rel="noopener"><img src="<?= e($m['src']) ?>" alt="<?= e($d['titulo']) ?>" loading="lazy"<?= img_attrs($m['src']) ?>></a>
              <?php endif; ?>
            </div>
            <figcaption class="demo-item__cap">
              <strong><?= e($d['titulo']) ?></strong>
              <?php if (!empty($d['descripcion'])): ?><span><?= e($d['descripcion']) ?></span><?php endif; ?>
            </figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
