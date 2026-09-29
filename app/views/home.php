<?php
require_once __DIR__ . '/../data/noticias_db.php';
$destacada = noticia_destacada();
$ultimas = noticias_publicadas(5);
?>
<!-- ============================================================
     OBERTURA — una pantalla, un mensaje
     ============================================================ -->
<section class="overture">
  <div class="wrap overture__inner">
    <span class="overture__eyebrow">RAP de envases comerciales e industriales · RD 1055/2022</span>

    <h1 class="overture__title" data-split>La RAP de envases, <span class="hl">bajo control.</span></h1>

    <p class="overture__lead" data-reveal style="--reveal-delay:.45s">
      Homologar gestores, controlar la documentación de cada servicio y liquidar los
      incentivos del poseedor. Las tres cosas que la norma exige hacer bien, en un
      ecosistema que ya opera.
    </p>

    <div class="overture__actions" data-reveal style="--reveal-delay:.56s">
      <a class="btn btn--primary btn--lg" href="/demos">Probar las demos</a>
      <a class="btn btn--light" href="/el-core">Ver el CORE</a>
    </div>

    <dl class="overture__facts" data-reveal style="--reveal-delay:.68s">
      <div>
        <dt>Obligatoriedad</dt>
        <dd><span class="v">1 ENE 2025</span><span class="s">Envases comerciales e industriales</span></dd>
      </div>
      <div>
        <dt>Herramientas operativas</dt>
        <dd><span class="v" data-count="3">0</span><span class="s">PROBATUS · INPROGEST · SCRAPP</span></dd>
      </div>
      <div>
        <dt>Codificación</dt>
        <dd><span class="v">LER 15 01</span><span class="s">Único código válido del residuo de envase</span></dd>
      </div>
    </dl>

    <p class="overture__cue"><i aria-hidden="true"></i> Recorre el core</p>
  </div>
</section>

<!-- ============================================================
     FILMSTRIP — los tres actos del core, anclados al scroll
     Un solo juego de demos: el mismo conmutador de /demos, con las
     pestañas gobernadas por el recorrido además de por el clic.
     ============================================================ -->
<section class="film" data-film aria-label="Las tres piezas del core, en funcionamiento">
  <div class="film__track">
    <div class="film__stage" data-acto="0">

      <div class="film__hud">
        <span>El core en funcionamiento · datos de ejemplo</span>
        <span class="film__count"><b data-film-num>01</b> / 03</span>
      </div>

      <div class="film__titles">
        <p class="film__t" data-acto="0">Homologa <em>PROBATUS puntúa a cada gestor sobre requisitos con vigencia, y la puntuación caduca sola.</em></p>
        <p class="film__t" data-acto="1">Controla <em>INPROGEST cruza cada servicio con su documentación y concilia los traslados contra RAMON.</em></p>
        <p class="film__t" data-acto="2">Liquida <em>SCRAPP ordena el documento de cada poseedor y calcula el incentivo que le corresponde.</em></p>
      </div>

      <div class="film__win">
        <?php require __DIR__ . '/partials/demo-switch.php'; ?>
      </div>

      <div class="film__foot">
        <span class="film__bar" aria-hidden="true"><i></i><i></i><i></i></span>
        <a href="/demos">Abrir las demos completas <span aria-hidden="true">→</span></a>
      </div>

    </div>
  </div>
</section>

<!-- DÍPTICO — las dos mitades de la misma obligación -->
<section class="diptico section--alt" aria-label="El marco en una frase">
  <div class="diptico__grid" data-reveal>
    <p class="diptico__w">Trazar</p>
    <div class="diptico__mid">
      <span class="kicker">Desde el 1 de enero de 2025</span>
      <p>La RAP se aplica también a los envases comerciales e industriales. Un marco que
        exige trazabilidad, homologación y control documental a todos los actores del
        sistema — y que hay que poder sostener delante de una inspección.</p>
    </div>
    <p class="diptico__w diptico__w--soft diptico__w--end">Demostrar</p>
  </div>
</section>

<!-- FICHA TÉCNICA DEL MARCO -->
<section class="section wrap" aria-label="Marco normativo de referencia">
  <dl class="ficha" data-reveal-group>
    <div>
      <dt>Obligatoriedad</dt>
      <dd>1 ENE 2025<small>Para envases comerciales e industriales, sin periodo transitorio.</small></dd>
    </div>
    <div>
      <dt>Norma</dt>
      <dd>RD 1055/2022<small>De envases y residuos de envases. Traspone la RAP al ordenamiento español.</small></dd>
    </div>
    <div>
      <dt>Codificación</dt>
      <dd>LER 15 01<small>Único código válido del residuo de envase. Cualquier otro rompe la trazabilidad.</small></dd>
    </div>
    <div>
      <dt>Reporte</dt>
      <dd>Doble<small>Al SCRAP y al MITECO, con conciliación de los traslados contra RAMON.</small></dd>
    </div>
  </dl>
</section>

<!-- EL CORE — índice de las tres piezas -->
<section class="section wrap" id="core">
  <div class="sectionhead" data-reveal>
    <div>
      <span class="kicker">El core</span>
      <h2>El core: tres piezas para las tres necesidades de la RAP</h2>
    </div>
    <p>Homologar a los gestores, controlar la documentación de cada servicio y ordenar los documentos e incentivos de los poseedores. Tres necesidades del sistema, cubiertas por tres herramientas de INPRONET Solutions que encajan entre sí.</p>
  </div>

  <?php $coreOrden = 'home'; require __DIR__ . '/partials/core-tools.php'; ?>

  <div class="center" style="margin-top:var(--space-xl)" data-reveal>
    <a class="link-arrow" href="/el-core">Ver cómo encajan las tres piezas <span class="arrow">→</span></a>
  </div>
</section>

<!-- PERFILES -->
<section class="section section--alt">
  <div class="wrap">
    <div class="sectionhead" data-reveal>
      <div>
        <span class="kicker">Perfiles</span>
        <h2>Elige tu papel en el sistema</h2>
      </div>
      <p>Cada actor tiene obligaciones distintas y una pieza del core que las resuelve.</p>
    </div>

    <div class="rolegrid" data-reveal-group>
      <a class="rolecard rolecard--lead" href="/para-tu-scrap">
        <span class="rolecard__tag">Perfil prioritario</span>
        <h3>SCRAP</h3>
        <p>Homologa, controla la documentación y gestiona los incentivos con el respaldo del core.</p>
        <span class="rolecard__go">Para tu SCRAP <span class="arrow" aria-hidden="true">→</span></span>
      </a>
      <a class="rolecard" href="/gestores">
        <h3>Gestor</h3>
        <p>Intégrate en el sistema y aporta tu trazabilidad de forma homogénea.</p>
        <span class="rolecard__go">Gestores <span class="arrow" aria-hidden="true">→</span></span>
      </a>
      <a class="rolecard" href="/poseedores">
        <h3>Poseedor</h3>
        <p>Ordena la documentación de tus residuos de envases y cumple sin fricción.</p>
        <span class="rolecard__go">Poseedores <span class="arrow" aria-hidden="true">→</span></span>
      </a>
    </div>
  </div>
</section>

<!-- CAPACIDAD Y GARANTÍAS — lo que revisa una gran cuenta -->
<section class="section wrap">
  <div class="sectionhead" data-reveal>
    <div>
      <span class="kicker">Para grandes cuentas</span>
      <h2>Lo que revisa una organización antes de decidir</h2>
    </div>
    <p>El core está en producción con volúmenes reales. Estos son los puntos que suele auditar un comité antes de aprobar un proveedor.</p>
  </div>

  <dl class="assur" data-reveal-group>
    <div class="assur__i">
      <dt>Volumen y multicentro</dt>
      <dd>Carteras de cientos de gestores y poseedores con varios centros y NIMA por empresa, cada uno con su tarifa y sus topes. El sistema opera hoy con decenas de miles de servicios al año.</dd>
    </div>
    <div class="assur__i">
      <dt>Documentación defendible</dt>
      <dd>Cada servicio conserva su juego completo —documento de identificación, nota de traslado, certificado de tratamiento y ticket de pesaje— con su trazabilidad y su conservación. Lo que se reporta se puede sostener en una inspección.</dd>
    </div>
    <div class="assur__i">
      <dt>Reporte por duplicado</dt>
      <dd>Al SCRAP y al MITECO, con conciliación de traslados contra RAMON y control de las incidencias que no cuadran, en lugar de darlas por buenas.</dd>
    </div>
    <div class="assur__i">
      <dt>Protección de datos</dt>
      <dd>Tratamiento conforme al RGPD y a la LOPDGDD, con registro del consentimiento y acceso segregado por entidad: cada organización ve su información y solo la suya.</dd>
    </div>
    <div class="assur__i">
      <dt>Integración</dt>
      <dd>Carga masiva, exportación a Excel y descarga documental por lotes. Las tres piezas comparten el mismo dato, así que no hay que introducirlo dos veces.</dd>
    </div>
    <div class="assur__i">
      <dt>Interlocución técnica</dt>
      <dd>La Oficina Técnica de SCRAPs acompaña la implantación y el día a día, con criterio normativo y no solo soporte de aplicación.</dd>
    </div>
  </dl>
</section>

<!-- NOTICIAS -->
<?php if ($destacada): ?>
<section class="section">
  <div class="wrap">
    <header class="edi-head" data-reveal>
      <div class="edi-head__title">
        <span class="num">§ 01</span>
        <h2>En <em>portada</em></h2>
      </div>
      <a href="/noticias" class="link-arrow">Todas las noticias <span class="arrow">→</span></a>
    </header>

    <?php $resto = array_slice(array_values(array_filter($ultimas, fn($n) => $n['id'] !== $destacada['id'])), 0, 3); ?>
    <div class="edi-grid" <?= !$resto ? 'style="grid-template-columns:1fr"' : '' ?>>
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

      <?php if ($resto): ?>
      <div class="edi-list" data-reveal-group>
        <?php foreach ($resto as $n): ?>
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
  </div>
</section>
<?php endif; ?>

<!-- CIERRE · OTS -->
<?php require __DIR__ . '/partials/ots-band.php'; ?>
