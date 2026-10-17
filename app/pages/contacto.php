<?php
// Copy: CORE SCRAP/Copy_scrapenvases_Contacto_OTS.docx · página F
// Estructura: CORE SCRAP/wireframe-contacto-ots.html
require_once __DIR__ . '/../consultas.php';

$ok    = flash_get('consulta_ok', false);
$error = flash_get('consulta_err');
$old   = flash_get('consulta_old', []);
$campos = flash_get('consulta_campos', []);
$v = fn(string $k) => e($old[$k] ?? '');
// Marca los campos con error y pone el foco en el primero
$inv = fn(string $k) => in_array($k, $campos, true)
    ? ' aria-invalid="true" aria-describedby="consulta-error"' . ($k === $campos[0] ? ' autofocus' : '')
    : '';

$otsEmail = env('OTS_EMAIL_PUBLICO', env('OTS_EMAIL', 'ots@scrapenvases.com'));
$otsTel   = env('OTS_TEL');

?>
<h1 class="sr">Habla con la Oficina Técnica de SCRAPs</h1>
<?php
franja('La RAP de envases cambia rápido y cada actor tiene dudas distintas. La Oficina Técnica de SCRAPs es el acompañamiento humano del portal: un punto único para resolver dudas técnicas, normativas y operativas, y para dar el primer paso con las herramientas del core.');
?>

<section class="sec sec--light" id="consulta">
  <div class="wrap contact">
    <div class="contact__form">
      <?php if ($ok): ?>
        <div class="alert alert--ok" role="status">
          <strong>Consulta enviada.</strong> La Oficina Técnica de SCRAPs la ha recibido y te responderá por correo.
        </div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert--err" id="consulta-error" role="alert"><?= e($error) ?></div>
      <?php endif; ?>

      <form class="form" method="post" action="/contacto#consulta">
        <?= csrf_field() ?>
        <div class="form__trap" aria-hidden="true">
          <label>No rellenar <input type="text" name="web" tabindex="-1" autocomplete="off"></label>
        </div>
        <div class="form__two">
          <label class="field">
            <span>Nombre <abbr title="obligatorio">*</abbr></span>
            <input type="text" name="nombre" placeholder="Nombre y apellidos" autocomplete="name" required value="<?= $v('nombre') ?>"<?= $inv('nombre') ?>>
          </label>
          <label class="field">
            <span>Empresa <abbr title="obligatorio">*</abbr></span>
            <input type="text" name="empresa" placeholder="Razón social" autocomplete="organization" required value="<?= $v('empresa') ?>"<?= $inv('empresa') ?>>
          </label>
        </div>
        <div class="form__two">
          <label class="field">
            <span>Email <abbr title="obligatorio">*</abbr></span>
            <input type="email" name="email" placeholder="Correo de contacto" autocomplete="email" required value="<?= $v('email') ?>"<?= $inv('email') ?>>
          </label>
          <label class="field">
            <span>Teléfono</span>
            <input type="tel" name="telefono" placeholder="Opcional" autocomplete="tel" value="<?= $v('telefono') ?>">
          </label>
        </div>
        <label class="field">
          <span>Soy… <abbr title="obligatorio">*</abbr></span>
          <select name="perfil" required<?= $inv('perfil') ?>>
            <option value="" disabled<?= empty($old['perfil']) ? ' selected' : '' ?>>SCRAP · Gestor · Poseedor · Productor · Otro</option>
            <?php foreach (PERFILES as $k => $txt): ?>
              <option value="<?= $k ?>"<?= ($old['perfil'] ?? '') === $k ? ' selected' : '' ?>><?= e($txt) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="field">
          <span>Tu consulta <abbr title="obligatorio">*</abbr></span>
          <textarea name="mensaje" rows="6" placeholder="Cuéntanos brevemente tu caso o tu duda" required<?= $inv('mensaje') ?>><?= $v('mensaje') ?></textarea>
        </label>
        <label class="check">
          <input type="checkbox" name="rgpd" value="1" required<?= $inv('rgpd') ?>>
          <span>Acepto la <a href="/politica-privacidad" target="_blank">política de privacidad</a> y el tratamiento de mis datos (RGPD).</span>
        </label>
        <button class="btn btn--primary" type="submit">Enviar consulta</button>
      </form>
    </div>

    <aside class="contact__aside">
      <div class="cbox cbox--dark">
        <h2>Contacto directo</h2>
        <dl class="cbox__lines">
          <div><dt>Email:</dt><dd><a href="mailto:<?= e($otsEmail) ?>"><?= e($otsEmail) ?></a></dd></div>
          <?php if ($otsTel): ?>
            <div><dt>Teléfono:</dt><dd><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $otsTel)) ?>"><?= e($otsTel) ?></a></dd></div>
          <?php endif; ?>
          <div><dt>Horario:</dt><dd>L–V, horario de oficina</dd></div>
        </dl>
      </div>
      <div class="cbox">
        <h2>Quién te atiende</h2>
        <?php foreach ([
            ['María Izquierdo', 'Directora General de INPROECO', 'maria-izquierdo', 'MI'],
            ['Rodrigo Bueno', 'Responsable de Desarrollo · INPRONET Solutions', 'rodrigo-bueno', 'RB'],
        ] as [$nombre, $cargo, $foto, $ini]): $src = imagen_portal($foto); ?>
          <div class="person">
            <?php if ($src): ?>
              <img class="person__av" src="<?= e($src) ?>" alt="" width="52" height="52">
            <?php else: ?>
              <span class="person__av" aria-hidden="true"><?= $ini ?></span>
            <?php endif; ?>
            <div>
              <p class="person__name"><?= e($nombre) ?></p>
              <p class="person__role"><?= e($cargo) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </aside>
  </div>
</section>

<section class="sec">
  <div class="wrap wrap--narrow">
    <header class="sec__head">
      <h2><?= resaltar('Preguntas frecuentes', 'frecuentes') ?></h2>
    </header>
    <div class="faq">
      <?php foreach ([
          ['¿Estoy obligado a estar en un SCRAP?',
           'Si pones envases comerciales o industriales en el mercado, desde el 1 de enero de 2025 debes adherirte a un SCRAP o constituir un sistema individual.'],
          ['¿Qué es el incentivo y a quién le corresponde?',
           'Una compensación (entre 3 y 5 €/t) por aportar la trazabilidad del residuo. Corresponde por ley al poseedor final, no al gestor.'],
          ['¿Qué código LER uso para mis residuos de envase?',
           'La familia LER 15 01 (envases), no los códigos del grupo 20.'],
          ['Soy poseedor, ¿la RAP me quita obligaciones?',
           'No. Las nuevas obligaciones del productor no te eximen de las tuyas como poseedor: conviven.'],
      ] as [$q, $a]): ?>
        <details class="faq__item">
          <summary><?= e($q) ?></summary>
          <p><?= e($a) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="sec sec--closing">
  <div class="wrap wrap--narrow">
    <p class="closing">La OTS es el acompañamiento de <strong>Partner Waste</strong> dentro del ecosistema del grupo. Escríbenos y te respondemos con criterio, sin compromiso.</p>
  </div>
</section>
