</main>

<footer class="foot">
  <div class="wrap">
    <div class="foot__top">
      <div class="foot__brand">
        <a href="/" aria-label="scrapenvases.com — inicio"><img src="<?= asset('/imagenes/web/logo-scrapenvases-blanco.png') ?>" alt="SCRAPenvases.com" width="220" height="17" loading="lazy"></a>
        <p>Portal del sector para la RAP de envases comerciales e industriales.</p>
      </div>
      <div class="foot__nav">
        <div class="foot__col foot__col--portal">
          <h2>Portal</h2>
          <a href="/para-tu-scrap">Para tu SCRAP</a>
          <a href="/poseedores">Poseedores</a>
          <a href="/gestores">Gestores</a>
          <a href="/normativa">Normativa</a>
        </div>
        <div class="foot__col foot__col--tools">
          <h2>Herramientas</h2>
          <?php foreach (['scrapp', 'inprogest', 'probatus'] as $id): $h = herramienta($id); ?>
            <a class="foot__tool foot__tool--<?= $id ?>" href="<?= e($h['url']) ?>" target="_blank" rel="noopener"><?= e($h['nombre']) ?></a>
          <?php endforeach; ?>
        </div>
        <div class="foot__col foot__col--legal">
          <h2>Legal</h2>
          <a href="/aviso-legal">Aviso legal</a>
          <a href="/politica-privacidad">Política de privacidad</a>
          <a href="/politica-cookies">Política de cookies</a>
          <a href="/contacto">Contacto · OTS</a>
        </div>
      </div>
    </div>
    <div class="foot__legal">
      <span>SCRAP ENVASES ESTÁ DESARROLLADA POR INPRONET SOLUTIONS S.L.</span>
      <span>© <?= date('Y') ?> scrapenvases.com</span>
    </div>
  </div>
</footer>
</body>
</html>
