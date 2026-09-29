<?php
/**
 * Bloque "necesidad → herramienta" de las páginas de perfil.
 * Se maqueta en zig-zag: las filas pares invierten las columnas.
 *
 * Espera:
 *   $needsKicker (opcional)  kicker de la sección
 *   $needsTitulo             título de la sección
 *   $needsLead   (opcional)  texto de apoyo
 *   $needs                   array de necesidades:
 *       marca   → 'P' | 'I' | 'S' | '1' | '2' | '3'
 *       titulo  → nombre de la necesidad
 *       tool    → etiqueta de la herramienta ("PROBATUS · homologación")
 *       dolor   → contexto real del problema
 *       solucion→ qué hace la herramienta
 *       enlace  → ['texto' => …, 'url' => …] (opcional)
 */
$needsKicker = $needsKicker ?? 'Necesidades';
$needsLead   = $needsLead ?? null;
?>
<section class="section wrap">
  <div class="sectionhead" data-reveal>
    <div>
      <span class="kicker"><?= e($needsKicker) ?></span>
      <h2><?= e($needsTitulo) ?></h2>
    </div>
    <?php if ($needsLead): ?><p><?= e($needsLead) ?></p><?php endif; ?>
  </div>

  <div class="needgrid">
    <?php foreach ($needs as $n): ?>
      <article class="needcard" data-reveal>
        <header class="needcard__head">
          <span class="needcard__mark" aria-hidden="true"><?= e($n['marca']) ?></span>
          <h3><?= e($n['titulo']) ?></h3>
          <span class="needcard__tool"><?= e($n['tool']) ?></span>
        </header>
        <div class="needcard__body">
          <p class="needcard__pain"><?= e($n['dolor']) ?></p>
          <p class="needcard__fix"><?= e($n['solucion']) ?></p>
          <?php if (!empty($n['enlace'])): ?>
            <a class="needcard__link" href="<?= e($n['enlace']['url']) ?>"<?= str_starts_with($n['enlace']['url'], 'http') ? ' target="_blank" rel="noopener"' : '' ?>>
              <?= e($n['enlace']['texto']) ?> <span class="arrow" aria-hidden="true">→</span>
            </a>
          <?php endif; ?>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>
