<?php
/**
 * Demo en vivo · INPROGEST — Dashboard interactivo.
 *
 * Calcada de `scrapp/dashboard_scrapp.php` de inprogetrecyclia: barra
 * lateral azul→teal con el árbol de menú real, filtros de año/mes/
 * operativa, accesos rápidos, los cinco KPIs, la evolución mensual de
 * Zona Servicios y la distribución de estados.
 *
 * Como en la aplicación, al pulsar un mes se cruza con el resto de
 * gráficos y contadores. Datos ficticios.
 */
?>
<div class="app app--inprogest" data-demo="inprogest">
  <div class="skin skin--ipg">
    <aside class="skin__side">
      <div class="skin__brand">
        <span class="skin__logo" aria-hidden="true">IN<br>PG</span>
        <span class="skin__brandtxt"><b>Inprogest</b><br><i>Gestión documental</i></span>
      </div>

      <div class="skin__who">
        <small>Bienvenido/a</small>
        <strong>Demo</strong>
        <span class="skin__role">✦ INVITADO</span>
      </div>

      <nav class="skin__nav" aria-label="Menú de la aplicación">
        <button type="button"><span class="skin__ico" aria-hidden="true">▤</span> Zona Servicios</button>
        <button type="button"><span class="skin__ico" aria-hidden="true">◈</span> R.A.M.O.N <span class="skin__chev" aria-hidden="true">▾</span></button>
        <button type="button"><span class="skin__ico" aria-hidden="true">▥</span> Documentación <span class="skin__chev" aria-hidden="true">▾</span></button>
        <button type="button"><span class="skin__ico" aria-hidden="true">◍</span> Consultas y reportes <span class="skin__chev" aria-hidden="true">▴</span></button>
        <div class="skin__sub">
          <button type="button" aria-current="true"><span class="skin__ico" aria-hidden="true">▦</span> Dashboard</button>
          <button type="button"><span class="skin__ico" aria-hidden="true">◌</span> Centro de Consultas</button>
          <button type="button"><span class="skin__ico" aria-hidden="true">▢</span> Reporte Proveedores</button>
          <button type="button"><span class="skin__ico" aria-hidden="true">▣</span> Reporte Agrupaciones</button>
        </div>
        <button type="button"><span class="skin__ico" aria-hidden="true">◉</span> Gestión <span class="skin__chev" aria-hidden="true">▾</span></button>
        <button type="button"><span class="skin__ico" aria-hidden="true">◫</span> Control e incidencias <span class="skin__chev" aria-hidden="true">▾</span></button>
        <button type="button"><span class="skin__ico" aria-hidden="true">↓</span> Descargas masivas</button>
      </nav>

      <div class="skin__foot">
        <div class="skin__mark"><span>recy</span><em>envases</em></div>
      </div>
    </aside>

    <!-- div, no <main>: la página anfitriona ya tiene el suyo -->
    <div class="skin__main">
      <div class="skin__head">
        <div>
          <p class="skin__title">Dashboard interactivo</p>
          <p class="scard__sub">Explora Zona Servicios y cruza mes, operativa, proveedor y agrupación sin recargar la página.</p>
        </div>
      </div>

      <!-- Filtros -->
      <div class="scard">
        <div class="sfilters" style="padding-top:1rem">
          <div class="sfield">
            <label for="dip-anio">Año</label>
            <select id="dip-anio" data-f="anio"><option>2026</option><option>2025</option></select>
          </div>
          <div class="sfield">
            <label for="dip-mes">Mes</label>
            <select id="dip-mes" data-f="mes"><option value="">Todos</option></select>
          </div>
          <div class="sfield">
            <label for="dip-oper">Operativas</label>
            <select id="dip-oper" data-f="operativa"><option value="">Todas</option></select>
          </div>
          <div class="sfield">
            <label for="dip-agr">Agrupaciones</label>
            <select id="dip-agr" data-f="agrupacion"><option value="">Todas</option></select>
          </div>
          <div class="sfield">
            <label for="dip-prov">Proveedor</label>
            <select id="dip-prov" data-f="proveedor"><option value="">Todos</option></select>
          </div>
          <div class="sfield">
            <label>&nbsp;</label>
            <button class="sbtn sbtn--ghost" type="button" data-limpiar>↻ Reiniciar</button>
          </div>
        </div>
        <p class="scard__sub" style="padding:0 1rem 1rem" data-alcance>ⓘ Vista general del año seleccionado.</p>
      </div>

      <!-- Accesos rápidos -->
      <div class="scard">
        <div class="scard__head">
          <div>
            <div class="scard__t">Accesos Rápidos</div>
            <p class="scard__sub">Accede a los módulos de trabajo manteniendo el año seleccionado.</p>
          </div>
        </div>
        <div class="squick" data-quick></div>
      </div>

      <!-- KPIs -->
      <div class="skpis">
        <div class="skpi skpi--green">
          <div><div class="skpi__l">TOTAL SERVICIOS</div><div class="skpi__v" data-k-serv>0</div><div class="skpi__h">Servicios del alcance actual</div></div>
          <span class="skpi__i" aria-hidden="true">▤</span>
        </div>
        <div class="skpi skpi--orange">
          <div><div class="skpi__l">% DOCUMENTACIÓN VERIFICADA</div><div class="skpi__v" data-k-verif>0%</div><div class="skpi__h"><span data-k-docn>0</span> documentos verificados</div></div>
          <span class="skpi__i" aria-hidden="true">✓</span>
        </div>
        <div class="skpi">
          <div><div class="skpi__l">PENDIENTES RAMON</div><div class="skpi__v" data-k-ramon>0</div><div class="skpi__h">RAMON + RAMON API del año</div></div>
          <span class="skpi__i" aria-hidden="true">◈</span>
        </div>
        <div class="skpi">
          <div><div class="skpi__l">PUNTOS DE RECOGIDA</div><div class="skpi__v" data-k-puntos>0</div><div class="skpi__h">Puntos distintos en el alcance</div></div>
          <span class="skpi__i" aria-hidden="true">◉</span>
        </div>
        <div class="skpi">
          <div><div class="skpi__l">PROVEEDORES</div><div class="skpi__v" data-k-prov>0</div><div class="skpi__h">Proveedores distintos</div></div>
          <span class="skpi__i" aria-hidden="true">▢</span>
        </div>
      </div>

      <!-- Gráficos -->
      <div class="sgrid2">
        <div class="scard">
          <div class="scard__head">
            <div>
              <div class="scard__t">Evolución mensual de Zona Servicios</div>
              <p class="scard__sub">Selecciona un mes para cruzarlo con el resto de gráficos y contadores.</p>
            </div>
            <span class="chip chip--idle" data-messel>Sin mes seleccionado</span>
          </div>
          <div class="scard__body">
            <div class="schart">
              <div class="sbars" data-bars></div>
              <div class="slegend">
                <span><i style="background:#a7d8e6"></i> Total servicios</span>
                <span><i style="background:#009889"></i> Documentados</span>
              </div>
            </div>
          </div>
        </div>

        <div class="scard">
          <div class="scard__head">
            <div>
              <div class="scard__t">Distribución de estados</div>
              <p class="scard__sub">Estados reales de Zona Servicios en el alcance seleccionado.</p>
            </div>
          </div>
          <div class="scard__body">
            <div class="sdonutwrap">
              <div class="sdonut" data-donut>
                <div class="sdonut__c"><b data-donutp>0%</b><span>documentado</span></div>
              </div>
              <div class="sdlist">
                <div><small>DOCUMENTADOS</small><b data-doc>0</b><em data-docp>0%</em></div>
                <div><small>NO DOCUMENTADOS</small><b data-nodoc>0</b><em data-nodocp>0%</em></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="slog" data-log aria-live="polite" aria-label="Registro de actividad"></div>
    </div>

    <div class="skin__demobar">
      <span class="dot" aria-hidden="true"></span>
      <span>Demo del <b>Dashboard de INPROGEST</b> · datos de ejemplo</span>
      <?php $demoTool = 'inprogest'; require __DIR__ . '/demo-foot-link.php'; ?>
    </div>
  </div>
</div>
