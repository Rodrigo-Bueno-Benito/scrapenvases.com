</main>
<footer class="site-footer">
  <div class="wrap site-footer__grid">
    <div>
      <div class="site-footer__logo">
        <picture>
          <source srcset="<?= asset('/imagenes/logoFooterBlanco.webp') ?>" type="image/webp">
          <img src="<?= asset('/imagenes/logoFooterBlanco.png') ?>" alt="ScrapEnvases" width="165" height="42" loading="lazy">
        </picture>
      </div>
      <p>Portal del sector para la RAP de envases comerciales e industriales. Reúne la normativa, la resolución de dudas y el ecosistema de herramientas del core.</p>
      <p class="site-footer__ots">
        <a class="btn btn--light btn--sm" href="/contacto">Consultar con la OTS</a>
      </p>
    </div>
    <div>
      <h2>Portal</h2>
      <ul class="footer-links">
        <li><a href="/el-core">El CORE</a></li>
        <li><a href="/demos">Demos en vivo</a></li>
        <li><a href="/para-tu-scrap">Para tu SCRAP</a></li>
        <li><a href="/poseedores">Poseedores</a></li>
        <li><a href="/gestores">Gestores</a></li>
        <li><a href="/normativa">Normativa y LER</a></li>
        <li><a href="/noticias">Noticias</a></li>
      </ul>
    </div>
    <div>
      <h2>Herramientas</h2>
      <ul class="footer-links">
        <li><a href="https://scrapp.es/" target="_blank" rel="noopener">SCRAPP ↗</a></li>
        <li><a href="https://inprogest.com/" target="_blank" rel="noopener">INPROGEST ↗</a></li>
        <li><a href="https://probatus.es/" target="_blank" rel="noopener">PROBATUS ↗</a></li>
      </ul>
      <h2 style="margin-top:1.5rem">Legal</h2>
      <ul class="footer-links">
        <li><a href="/aviso-legal">Aviso legal</a></li>
        <li><a href="/politica-privacidad">Política de privacidad</a></li>
        <li><a href="/politica-cookies">Política de cookies</a></li>
        <li><a href="/acceso">Acceso panel</a></li>
      </ul>
    </div>
  </div>
  <div class="wrap site-footer__bottom">
    <span>© <?= date('Y') ?> ScrapEnvases</span>
    <span>Desarrollada por Inpronet Solutions S.L.</span>
  </div>
</footer>

<!-- Acompaña todo el recorrido; el JS la muestra pasada la primera pantalla -->
<a class="pilote" data-pilote href="/contacto">
  Hablar con la OTS <span aria-hidden="true">↗</span>
</a>

<div class="cookiebar hidden" role="dialog" aria-label="Aviso de cookies">
  <p>Usamos cookies propias y de terceros para mejorar tu experiencia. Consulta nuestra <a href="/politica-cookies">política de cookies</a>.</p>
  <div class="cookiebar__actions">
    <a class="btn-sm" href="/politica-cookies">Más info</a>
    <button class="btn btn--primary" data-cookie-accept>Aceptar</button>
  </div>
</div>

<script src="<?= asset('/js/main.js') ?>" defer></script>
<script src="<?= asset('/js/effects.js') ?>" defer></script>
<?php if (!empty($tieneDemo)): ?>
<script src="<?= asset('/js/demos.js') ?>" defer></script>
<?php endif; ?>
<?php if (!empty($esPortada)): ?>
<script src="<?= asset('/js/film.js') ?>" defer></script>
<?php endif; ?>
</body>
</html>
