<?php /* Espera $n = fila de noticia */ ?>
<a class="newscard" href="/noticia/<?= e($n['slug']) ?>">
  <div class="newscard__media">
    <img src="<?= e(noticia_img_src($n['imagen'])) ?>" alt="<?= e($n['titulo']) ?>" loading="lazy">
  </div>
  <div class="newscard__body">
    <div class="newscard__cat"><?= e($n['categoria']) ?></div>
    <h3 class="newscard__title"><?= e($n['titulo']) ?></h3>
    <p class="newscard__excerpt"><?= e($n['extracto'] ?: excerpt($n['cuerpo'])) ?></p>
    <span class="newscard__date"><?= e(fecha_es($n['fecha_publicacion'])) ?></span>
  </div>
</a>
