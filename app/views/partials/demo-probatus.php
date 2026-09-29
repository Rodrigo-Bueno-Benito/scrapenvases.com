<?php
/**
 * Demo en vivo · PROBATUS — Homologación de gestores.
 *
 * Comparte la piel de las aplicaciones del grupo (barra lateral verde,
 * KPIs, tabla con cabecera por bloques y botones de acción) para que
 * las tres demos se lean como el mismo ecosistema.
 *
 * PROBATUS no tiene repositorio disponible, así que el contenido es una
 * reconstrucción del flujo de homologación. Datos ficticios.
 *
 * $appMini = true → versión reducida para el hero de la portada.
 */
$appMini = $appMini ?? false;
?>
<div class="app app--probatus<?= $appMini ? ' app--mini' : '' ?>" data-demo="probatus">
  <div class="skin">
    <?php if (!$appMini): ?>
    <aside class="skin__side">
      <div class="skin__brand">
        <span class="skin__brandtxt"><b>PRO</b><i>BATUS</i></span>
        <span class="skin__logo" aria-hidden="true">PRO<br>BT</span>
      </div>

      <div class="skin__who">
        <small>Bienvenido/a</small>
        <strong>Demo</strong>
        <span class="skin__role">✦ INVITADO</span>
      </div>

      <nav class="skin__nav" aria-label="Menú de la aplicación">
        <button type="button" aria-current="true"><span class="skin__ico" aria-hidden="true">▤</span> Cartera de gestores</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">✓</span> Homologaciones</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">◫</span> Requisitos y vigencias</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">▦</span> Auditorías</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">◈</span> Requerimientos</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">▢</span> Proveedores</button>
      </nav>

      <div class="skin__foot">
        <div class="skin__mark"><span>recy</span><em>envases</em></div>
      </div>
    </aside>
    <?php endif; ?>

    <!-- div, no <main>: la página anfitriona ya tiene el suyo -->
    <div class="skin__main">
      <div class="skin__head">
        <p class="skin__title"><?= $appMini ? 'PROBATUS · Gestores' : 'Cartera de Gestores' ?></p>
        <?php if (!$appMini): ?>
        <div class="skin__acts">
          <button class="sbtn sbtn--orange" type="button" data-descargar>↓ Descargar Excel</button>
          <button class="sbtn sbtn--green" type="button" data-nfiltros>▽ 1T 2026</button>
        </div>
        <?php endif; ?>
      </div>

      <div class="skpis">
        <div class="skpi skpi--green">
          <div><div class="skpi__l">Homologados</div><div class="skpi__v" data-m-ok>0</div><?php if (!$appMini): ?><div class="skpi__h">Vigentes</div><?php endif; ?></div>
          <span class="skpi__i" aria-hidden="true">✓</span>
        </div>
        <div class="skpi skpi--orange">
          <div><div class="skpi__l">Requieren acción</div><div class="skpi__v" data-m-warn>0</div><?php if (!$appMini): ?><div class="skpi__h">Vigencias por caducar</div><?php endif; ?></div>
          <span class="skpi__i" aria-hidden="true">◈</span>
        </div>
        <?php if (!$appMini): ?>
        <div class="skpi">
          <div><div class="skpi__l">Pendientes</div><div class="skpi__v" data-m-pend>0</div><div class="skpi__h">Sin homologar</div></div>
          <span class="skpi__i" aria-hidden="true">▢</span>
        </div>
        <div class="skpi">
          <div><div class="skpi__l">Familia LER</div><div class="skpi__v">15 01</div><div class="skpi__h">Envases</div></div>
          <span class="skpi__i" aria-hidden="true">▣</span>
        </div>
        <?php endif; ?>
      </div>

      <div class="scard">
        <?php if (!$appMini): ?>
        <div class="scard__head">
          <div>
            <div class="scard__t"><span aria-hidden="true">⚡</span> Gestores en seguimiento</div>
            <p class="scard__sub">Selecciona un gestor para abrir su expediente.</p>
          </div>
          <button class="sbtn sbtn--ghost" type="button" data-reset>◌ Reiniciar demo</button>
        </div>
        <?php endif; ?>

        <div class="stablewrap">
          <table class="stable">
            <thead>
              <tr>
                <th class="sec-0" scope="col">Gestor</th>
                <?php if (!$appMini): ?>
                  <th class="sec-1" scope="col">NIMA</th>
                  <th class="sec-1" scope="col">Provincia</th>
                <?php endif; ?>
                <th class="sec-2" scope="col">Estado</th>
                <?php if (!$appMini): ?>
                  <th class="sec-3" scope="col">Punt.</th>
                  <th class="sec-4" scope="col">Acciones</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody data-rows></tbody>
          </table>
        </div>

        <?php if (!$appMini): ?>
        <div class="scard__body">
          <div class="sfootrow">
            <span class="stotal" data-total>Total: 0 gestores</span>
            <div class="spager" data-pager></div>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <?php if (!$appMini): ?>
      <div class="scard">
        <div class="scard__head">
          <div>
            <div class="scard__t"><span aria-hidden="true">▥</span> Expediente de homologación</div>
            <p class="scard__sub" data-gsub>Ningún gestor seleccionado</p>
          </div>
        </div>
        <div class="scard__body" data-panel>
          <div class="sempty">
            <span>Selecciona un gestor de la tabla</span>
            <kbd>clic en una fila</kbd>
          </div>
        </div>
      </div>

      <div class="slog" data-log aria-live="polite" aria-label="Registro de actividad"></div>
      <?php endif; ?>
    </div>

    <div class="skin__demobar">
      <span class="dot" aria-hidden="true"></span>
      <?php if ($appMini): ?>
        <span>Demo real · datos de ejemplo</span>
        <a href="/demos">Probar entera →</a>
      <?php else: ?>
        <span>Demo de <b>PROBATUS</b> · datos de ejemplo</span>
        <?php $demoTool = 'probatus'; require __DIR__ . '/demo-foot-link.php'; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
