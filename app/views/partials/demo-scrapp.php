<?php
/**
 * Demo en vivo · SCRAPP POSEEDORES — Gestión de documentos.
 *
 * Calcada de la pantalla real `documentos.php` del repositorio
 * scrappposedores: barra lateral verde, filtros rápidos, tabla con
 * cabecera por bloques de color, botones de acción redondeados,
 * total de registros y paginación.
 *
 * Datos ficticios: nada sale del navegador.
 */
$appMini = $appMini ?? false;
?>
<div class="app app--scrapp<?= $appMini ? ' app--mini' : '' ?>" data-demo="scrapp">
  <div class="skin">
    <!-- Barra lateral -->
    <aside class="skin__side">
      <div class="skin__brand">
        <span class="skin__brandtxt"><b>SCRAPP</b><i>POSEEDORES</i></span>
        <span class="skin__logo" aria-hidden="true">SC<br>RP</span>
      </div>

      <div class="skin__who">
        <small>Bienvenido/a</small>
        <strong>Demo</strong>
        <span class="skin__role">✦ INVITADO</span>
      </div>

      <nav class="skin__nav" aria-label="Menú de la aplicación">
        <button type="button" aria-current="true"><span class="skin__ico" aria-hidden="true">▤</span> Documentos</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">▥</span> Gestionar Documentos</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">◍</span> Gestión Usuarios</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">▣</span> Gestión Poseedores</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">▢</span> Gestión Proveedores</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">◈</span> Seguimiento Poseedores</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">▦</span> Proformas</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">◫</span> Anexos</button>
      </nav>

      <div class="skin__foot">
        <div class="skin__mark"><span>recy</span><em>envases</em></div>
      </div>
    </aside>

    <!-- Contenido -->
    <!-- div, no <main>: la página anfitriona ya tiene el suyo -->
    <div class="skin__main">
      <div class="skin__head">
        <p class="skin__title">Gestión de Documentos</p>
        <div class="skin__acts">
          <button class="sbtn sbtn--orange" type="button" data-descargar>↓ Descargar</button>
          <button class="sbtn sbtn--green" type="button" data-nfiltros>▽ 1 filtro</button>
        </div>
      </div>

      <!-- Filtros rápidos -->
      <div class="scard">
        <div class="scard__head">
          <div>
            <div class="scard__t"><span aria-hidden="true">⚡</span> Filtros rápidos</div>
            <p class="scard__sub">Sincronizados con los filtros de la tabla.</p>
          </div>
          <button class="sbtn sbtn--ghost" type="button" data-limpiar>◌ Limpiar</button>
        </div>

        <div class="sfilters">
          <div class="sfield">
            <label for="dsc-anio">Año</label>
            <select id="dsc-anio" data-f="anio"><option>2026</option><option>2025</option></select>
          </div>
          <div class="sfield">
            <label for="dsc-pago">Pago</label>
            <select id="dsc-pago" data-f="pago">
              <option value="">Todos</option><option value="considerado">considerado</option><option value="pendiente">pendiente</option>
            </select>
          </div>
          <div class="sfield">
            <label for="dsc-arch">Archivo</label>
            <input type="text" id="dsc-arch" placeholder="Buscar archivo…" data-f="archivo">
          </div>
          <div class="sfield">
            <label for="dsc-tipo">Tipo doc.</label>
            <select id="dsc-tipo" data-f="tipo">
              <option value="">Todos</option><option>DI</option><option>NT</option><option>CT</option>
            </select>
          </div>
          <div class="sfield">
            <label for="dsc-di">DI</label>
            <input type="text" id="dsc-di" placeholder="Buscar DI…" data-f="di">
          </div>
          <div class="sfield">
            <label for="dsc-pos">Poseedor</label>
            <select id="dsc-pos" data-f="poseedor"><option value="">Todos</option></select>
          </div>
          <div class="sfield">
            <label for="dsc-mat">Material</label>
            <select id="dsc-mat" data-f="material"><option value="">Todos</option></select>
          </div>
          <div class="sfield">
            <label for="dsc-ler">LER</label>
            <input type="text" id="dsc-ler" placeholder="Buscar LER…" data-f="ler">
          </div>
          <div class="sfield">
            <label for="dsc-cant">Cantidad (Tn)</label>
            <input type="text" id="dsc-cant" placeholder="0,000" data-f="cantidad">
          </div>
        </div>
      </div>

      <!-- Tabla de documentos -->
      <div class="scard">
        <div class="stablewrap">
          <table class="stable">
            <thead>
              <tr>
                <th class="sec-0" scope="col">Documento</th>
                <th class="sec-0" scope="col">Anexo</th>
                <th class="sec-0" scope="col">Acciones</th>
                <th class="sec-1" scope="col">Pago</th>
                <th class="sec-1" scope="col">Observaciones</th>
                <th class="sec-2" scope="col">ID</th>
                <th class="sec-2" scope="col">Archivo</th>
                <th class="sec-3" scope="col">Tipo Doc.</th>
                <th class="sec-3" scope="col">DI</th>
                <th class="sec-4" scope="col">Material</th>
                <th class="sec-4" scope="col">Cantidad</th>
              </tr>
              <!-- Segunda fila de cabecera: filtros por columna, como en la aplicación -->
              <tr class="stable__filters">
                <th class="sec-0"></th>
                <th class="sec-0"></th>
                <th class="sec-0"></th>
                <th class="sec-1"><select data-f="pago" aria-label="Filtrar por pago"><option value="">Todos</option><option value="considerado">considerado</option><option value="pendiente">pendiente</option></select></th>
                <th class="sec-1"></th>
                <th class="sec-2"><input type="text" placeholder="#" data-f="id" aria-label="Filtrar por ID"></th>
                <th class="sec-2"><input type="text" placeholder="Buscar…" data-f="archivo" aria-label="Filtrar por archivo"></th>
                <th class="sec-3"><select data-f="tipo" aria-label="Filtrar por tipo"><option value="">Todos</option><option>DI</option><option>NT</option><option>CT</option></select></th>
                <th class="sec-3"><input type="text" placeholder="Buscar…" data-f="di" aria-label="Filtrar por DI"></th>
                <th class="sec-4"><input type="text" placeholder="Buscar…" data-f="material" aria-label="Filtrar por material"></th>
                <th class="sec-4"></th>
              </tr>
            </thead>
            <tbody data-docrows></tbody>
          </table>
        </div>

        <div class="scard__body">
          <div class="sfootrow">
            <span class="stotal" data-total>Total: 0 registros encontrados</span>
            <div class="spager" data-pager></div>
          </div>
        </div>
      </div>

      <div class="slog" data-log aria-live="polite" aria-label="Registro de actividad"></div>
    </div>

    <div class="skin__demobar">
      <span class="dot" aria-hidden="true"></span>
      <span>Demo del portal <b>SCRAPP Poseedores</b> · datos de ejemplo</span>
      <?php $demoTool = 'scrapp'; require __DIR__ . '/demo-foot-link.php'; ?>
    </div>
  </div>
</div>
