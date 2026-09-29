<?php
require_once __DIR__ . '/../data/consultas_db.php';

$enviado = flash_get('consulta_ok');
$error   = flash_get('consulta_err');
$old     = flash_get('consulta_old', []);
$otsMail = env('OTS_EMAIL');   // si no está en .env, se muestra "por confirmar"
$otsTel  = env('OTS_TEL');
?>
<!-- 1 · HERO centrado — tono de servicio -->
<section class="pagehero pagehero--center">
  <div class="wrap pagehero__inner" data-reveal>
    <span class="kicker">Oficina Técnica de SCRAPs (OTS)</span>
    <h1 data-split>Habla con la Oficina Técnica de SCRAPs</h1>
    <p>¿Dudas sobre tus obligaciones en la RAP de envases, sobre el core o sobre cómo empezar? La OTS acompaña a SCRAPs, gestores y poseedores. Cuéntanos tu caso y te orientamos.</p>
  </div>
</section>

<section class="contextband">
  <div class="wrap-narrow" data-reveal>
    <p>La RAP de envases cambia rápido y cada actor tiene dudas distintas. La Oficina Técnica de SCRAPs es el acompañamiento humano del portal: un punto único para resolver dudas técnicas, normativas y operativas, y para dar el primer paso con las herramientas del core.</p>
  </div>
</section>

<!-- 2 · FORMULARIO + CONTACTO DIRECTO -->
<section class="section wrap">
  <div class="contact-grid">

    <div class="contact-form" data-reveal>
      <?php /* El resultado llega tras recargar: hay que anunciarlo y llevar el foco,
               o quien use lector de pantalla no se entera de que falló el envío. */ ?>
      <?php if ($enviado): ?>
        <div class="alert alert--ok" role="status" tabindex="-1" id="form-aviso">
          <strong>Consulta enviada.</strong> Gracias por escribirnos: la OTS te responderá al correo que nos has indicado.
        </div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert--error" role="alert" tabindex="-1" id="form-aviso"><?= e($error) ?></div>
      <?php endif; ?>

      <h2>Cuéntanos tu caso</h2>
      <form method="post" action="/contacto-action" novalidate>
        <?= csrf_field() ?>
        <div class="field-row">
          <div class="field">
            <label for="nombre">Nombre <span aria-hidden="true">*</span></label>
            <input type="text" id="nombre" name="nombre" required maxlength="160"
                   placeholder="Nombre y apellidos" autocomplete="name"
                   value="<?= e($old['nombre'] ?? '') ?>">
          </div>
          <div class="field">
            <label for="empresa">Empresa <span aria-hidden="true">*</span></label>
            <input type="text" id="empresa" name="empresa" required maxlength="190"
                   placeholder="Razón social" autocomplete="organization"
                   value="<?= e($old['empresa'] ?? '') ?>">
          </div>
        </div>

        <div class="field-row">
          <div class="field">
            <label for="email">Email <span aria-hidden="true">*</span></label>
            <input type="email" id="email" name="email" required maxlength="190"
                   placeholder="Correo de contacto" autocomplete="email"
                   value="<?= e($old['email'] ?? '') ?>">
          </div>
          <div class="field">
            <label for="telefono">Teléfono</label>
            <input type="tel" id="telefono" name="telefono" maxlength="40"
                   placeholder="Opcional" autocomplete="tel"
                   value="<?= e($old['telefono'] ?? '') ?>">
          </div>
        </div>

        <div class="field">
          <label for="perfil">Soy… <span aria-hidden="true">*</span></label>
          <select id="perfil" name="perfil" required>
            <?php foreach (CONSULTA_PERFILES as $val => $label): ?>
              <option value="<?= e($val) ?>"<?= ($old['perfil'] ?? '') === $val ? ' selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label for="mensaje">Tu consulta <span aria-hidden="true">*</span></label>
          <textarea id="mensaje" name="mensaje" rows="6" required maxlength="4000"
                    placeholder="Cuéntanos brevemente tu caso o tu duda"><?= e($old['mensaje'] ?? '') ?></textarea>
        </div>

        <div class="field field--check">
          <label>
            <input type="checkbox" name="rgpd" value="1" required>
            <span>Acepto la <a href="/politica-privacidad">política de privacidad</a> y el tratamiento de mis datos (RGPD).</span>
          </label>
        </div>

        <!-- Trampa simple para bots: un humano no rellena este campo -->
        <div class="field-trap" aria-hidden="true">
          <label for="web_url">No rellenar</label>
          <input type="text" id="web_url" name="web_url" tabindex="-1" autocomplete="off">
        </div>

        <button class="btn btn--primary btn--lg" type="submit">Enviar consulta</button>
      </form>
    </div>

    <aside class="contact-side" data-reveal>
      <div class="contact-box">
        <h3>Contacto directo</h3>
        <ul class="contact-list">
          <li>
            <span>Email</span>
            <?php if ($otsMail): ?>
              <a href="mailto:<?= e($otsMail) ?>"><?= e($otsMail) ?></a>
            <?php else: ?>
              <strong>ots@scrapenvases.com</strong> <em class="pending">(por confirmar)</em>
            <?php endif; ?>
          </li>
          <li>
            <span>Teléfono</span>
            <?php if ($otsTel): ?>
              <a href="tel:<?= e(preg_replace('/\s+/', '', $otsTel)) ?>"><?= e($otsTel) ?></a>
            <?php else: ?>
              <em class="pending">(por confirmar)</em>
            <?php endif; ?>
          </li>
          <li>
            <span>Horario</span>
            <strong>L–V, horario de oficina</strong>
          </li>
        </ul>
      </div>

      <div class="contact-box">
        <h3>Quién te atiende</h3>
        <!-- Sustituir los avatares por las fotografías reales cuando estén. -->
        <div class="person">
          <span class="person__avatar" aria-hidden="true">MI</span>
          <div>
            <strong>María Izquierdo</strong>
            <span>Directora General de INPROECO</span>
          </div>
        </div>
        <div class="person">
          <span class="person__avatar" aria-hidden="true">RB</span>
          <div>
            <strong>Rodrigo Bueno</strong>
            <span>Responsable de Desarrollo · INPRONET Solutions</span>
          </div>
        </div>
      </div>
    </aside>
  </div>
</section>

<?php if ($enviado || $error): ?>
<script>
  // Llevar el foco al aviso al volver del envío
  (function () {
    var a = document.getElementById('form-aviso');
    if (!a) return;
    a.focus({ preventScroll: true });
    a.scrollIntoView({ block: 'center', behavior: 'smooth' });
  })();
</script>
<?php endif; ?>

<!-- 3 · FAQ de apoyo -->
<section class="section section--alt">
  <div class="wrap-narrow">
    <div class="sectionhead sectionhead--stack" data-reveal>
      <div>
        <span class="kicker">Dudas frecuentes</span>
        <h2>Preguntas frecuentes</h2>
      </div>
    </div>

    <div class="oblig-accordion" data-reveal-group>
      <details class="oblig-item">
        <summary>¿Estoy obligado a estar en un SCRAP?</summary>
        <div class="oblig-panel">
          <p>Si pones envases comerciales o industriales en el mercado, desde el 1 de enero de 2025 debes adherirte a un SCRAP o constituir un sistema individual.</p>
        </div>
      </details>
      <details class="oblig-item">
        <summary>¿Qué es el incentivo y a quién le corresponde?</summary>
        <div class="oblig-panel">
          <p>Una compensación (entre 3 y 5 €/t) por aportar la trazabilidad del residuo. Corresponde por ley al poseedor final, no al gestor.</p>
        </div>
      </details>
      <details class="oblig-item">
        <summary>¿Qué código LER uso para mis residuos de envase?</summary>
        <div class="oblig-panel">
          <p>La familia LER 15 01 (envases), no los códigos del grupo 20. Puedes consultar el listado completo en <a href="/normativa">Normativa</a>.</p>
        </div>
      </details>
      <details class="oblig-item">
        <summary>Soy poseedor, ¿la RAP me quita obligaciones?</summary>
        <div class="oblig-panel">
          <p>No. Las nuevas obligaciones del productor no te eximen de las tuyas como poseedor: conviven.</p>
        </div>
      </details>
    </div>

    <p class="contact-close" data-reveal>Escríbenos y te respondemos con criterio, sin compromiso.</p>
  </div>
</section>
