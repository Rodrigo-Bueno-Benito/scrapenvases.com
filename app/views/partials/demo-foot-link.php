<?php
/**
 * Pie de una demo: acceso a la aplicación real.
 *
 * Espera $demoTool ('scrapp' | 'inprogest' | 'probatus').
 *
 * - Si hay DEMO_<TOOL> en .env (entorno de demostración con datos
 *   ficticios), se ofrece como «probar la aplicación real».
 * - Si no, se enlaza el sitio de producto. Nunca la instancia de un
 *   cliente: es su panel de producción, no una demo.
 */
$demoReal = plataforma_demo_url($demoTool);
$sitio    = plataforma_url($demoTool);
$nombre   = strtoupper($demoTool);
?>
<?php if ($demoReal): ?>
  <a class="applink applink--go" href="<?= e($demoReal) ?>" target="_blank" rel="noopener">
    Abrir <?= e($nombre) ?> real <span aria-hidden="true">↗</span>
  </a>
<?php elseif ($sitio): ?>
  <a class="applink" href="<?= e($sitio) ?>" target="_blank" rel="noopener">
    <?= e($nombre) ?> <span aria-hidden="true">↗</span>
  </a>
<?php endif; ?>
