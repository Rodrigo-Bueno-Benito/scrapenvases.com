/* ============================================================
   DEMOS EN VIVO — PROBATUS · INPROGEST · SCRAPP
   Reproducen las pantallas de los portales en producción.
   Sin dependencias. Datos de ejemplo, nada sale del navegador.
   ============================================================ */
(function () {
  'use strict';

  var REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------------- utilidades ---------------- */

  function el(sel, root) { return (root || document).querySelector(sel); }
  function els(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  function num(n, d) {
    return n.toLocaleString('es-ES', { minimumFractionDigits: d || 0, maximumFractionDigits: d || 0 });
  }
  function hora() {
    var d = new Date();
    return String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0') + ':' + String(d.getSeconds()).padStart(2, '0');
  }

  /** Escribe una línea en la consola de sucesos */
  function log(root, texto, tipo) {
    var box = el('[data-log]', root);
    if (!box) return;
    var p = document.createElement('p');
    var t = document.createElement('time');
    t.textContent = hora();
    var s = document.createElement('span');
    if (tipo) s.className = 'lg-' + tipo;
    s.textContent = texto;
    p.appendChild(t);
    p.appendChild(s);
    box.appendChild(p);
    box.scrollTop = box.scrollHeight;
    while (box.children.length > 40) box.removeChild(box.firstChild);
  }

  /** Cuenta un número hasta su valor final */
  function tick(node, desde, hasta, fmt, ms) {
    return new Promise(function (resolve) {
      if (REDUCED) { node.textContent = fmt(hasta); resolve(); return; }
      var t0 = performance.now();
      var dur = ms || 600;
      function paso(t) {
        var p = Math.min((t - t0) / dur, 1);
        var e = 1 - Math.pow(1 - p, 4);
        node.textContent = fmt(desde + (hasta - desde) * e);
        if (p < 1) requestAnimationFrame(paso); else resolve();
      }
      requestAnimationFrame(paso);
    });
  }

  function espera(ms) {
    return new Promise(function (r) { setTimeout(r, REDUCED ? Math.min(ms, 60) : ms); });
  }

  /** Paginación, como en las aplicaciones */
  function pager(root, paginas) {
    var box = el('[data-pager]', root);
    if (!box) return;
    var html = '';
    for (var i = 1; i <= paginas; i++) {
      html += '<button type="button" aria-current="' + (i === 1 ? 'true' : 'false') + '">' + i + '</button>';
    }
    html += '<button type="button" aria-label="Página siguiente">»</button>';
    box.innerHTML = html;
  }

  /** Los botones de la barra dejan constancia en el registro */
  function decorativos(root, nombre) {
    var d = el('[data-descargar]', root);
    if (d) d.addEventListener('click', function () {
      log(root, 'Exportación de ' + nombre + ' solicitada · en la demo no se genera el fichero', 'warn');
    });
    var f = el('[data-nfiltros]', root);
    if (f) f.addEventListener('click', function () {
      log(root, 'Panel de filtros avanzados · disponible en la aplicación', null);
    });
  }

  /* ============================================================
     1 · PROBATUS — homologación de gestores
     ============================================================ */

  var GESTORES_BASE = [
    { id: 'G-4417', nombre: 'Recuperaciones Ebro',        nima: '5000012487', prov: 'Zaragoza',  estado: 'ok',   score: 94,
      reqs: [['Autorización de gestor (NIMA)', 1, 'vigente'], ['Seguro de RC ambiental', 1, '1.200.000 €'], ['ISO 14001', 1, 'hasta 03/2028'], ['Auditoría de instalaciones', 1, '11/2025'], ['Declaración anual de residuos', 1, 'presentada']] },
    { id: 'G-2903', nombre: 'Metales del Sur',            nima: '4100007621', prov: 'Sevilla',   estado: 'warn', score: 71,
      reqs: [['Autorización de gestor (NIMA)', 1, 'vigente'], ['Seguro de RC ambiental', 'warn', 'caduca en 12 días'], ['ISO 14001', 1, 'hasta 09/2027'], ['Auditoría de instalaciones', 'warn', 'pendiente de firma'], ['Declaración anual de residuos', 1, 'presentada']] },
    { id: 'G-5581', nombre: 'Gestión Integral del Vallès', nima: '0800022441', prov: 'Barcelona', estado: 'idle', score: 0,
      reqs: [['Autorización de gestor (NIMA)', 1, 'vigente'], ['Seguro de RC ambiental', 1, '900.000 €'], ['ISO 14001', 1, 'hasta 01/2029'], ['Auditoría de instalaciones', 1, '02/2026'], ['Declaración anual de residuos', 1, 'presentada']] },
    { id: 'G-1136', nombre: 'Valorización Cantábrica',    nima: '3900004182', prov: 'Santander', estado: 'ok',   score: 88,
      reqs: [['Autorización de gestor (NIMA)', 1, 'vigente'], ['Seguro de RC ambiental', 1, '1.000.000 €'], ['ISO 14001', 1, 'hasta 06/2027'], ['Auditoría de instalaciones', 1, '09/2025'], ['Declaración anual de residuos', 1, 'presentada']] },
    { id: 'G-7724', nombre: 'Transportes Aljarafe',       nima: '4100019053', prov: 'Sevilla',   estado: 'bad',  score: 38,
      reqs: [['Autorización de gestor (NIMA)', 1, 'vigente'], ['Seguro de RC ambiental', 0, 'no aportado'], ['ISO 14001', 0, 'no aportado'], ['Auditoría de instalaciones', 'warn', 'con salvedades'], ['Declaración anual de residuos', 1, 'presentada']] },
    { id: 'G-6052', nombre: 'Ecogestión Levante',         nima: '4600033718', prov: 'Valencia',  estado: 'idle', score: 0,
      reqs: [['Autorización de gestor (NIMA)', 1, 'vigente'], ['Seguro de RC ambiental', 1, '750.000 €'], ['ISO 14001', 'warn', 'en renovación'], ['Auditoría de instalaciones', 1, '01/2026'], ['Declaración anual de residuos', 1, 'presentada']] }
  ];

  var EST_G = {
    ok:   ['chip--ok', 'Homologado'],
    warn: ['chip--warn', 'Requiere acción'],
    bad:  ['chip--bad', 'Rechazado'],
    idle: ['chip--idle', 'Pendiente']
  };

  function initProbatus(root) {
    var datos = JSON.parse(JSON.stringify(GESTORES_BASE));
    var sel = null;
    var mini = root.classList.contains('app--mini');
    var tbody = el('[data-rows]', root);
    var panel = el('[data-panel]', root);

    function pinta() {
      tbody.innerHTML = '';
      datos.forEach(function (g, i) {
        if (mini && i > 2) return;
        var tr = document.createElement('tr');
        tr.style.setProperty('--i', i);
        tr.dataset.pick = '1';
        if (!mini) tr.tabIndex = 0;
        if (sel === g.id) tr.className = 'is-sel';
        var e = EST_G[g.estado];
        var html = '<td><span class="strong">' + g.nombre + '</span><span class="sub">' + g.id + '</span></td>';
        if (!mini) {
          html += '<td class="n">' + g.nima + '</td><td>' + g.prov + '</td>';
        }
        html += '<td><span class="chip ' + e[0] + '">' + e[1] + '</span></td>';
        if (!mini) {
          html += '<td class="n">' + (g.score ? g.score : '—') + '</td>' +
                  '<td><span class="pills">' +
                    '<button class="pill pill--validate" type="button" title="Homologar"' + (g.estado === 'idle' ? '' : ' disabled') + ' data-act="ok" data-g="' + g.id + '">✓</button>' +
                    '<button class="pill pill--edit" type="button" title="Requerir documentación" data-act="req" data-g="' + g.id + '">✎</button>' +
                    '<button class="pill pill--anexo" type="button" title="Ver expediente" data-act="ver" data-g="' + g.id + '">▤</button>' +
                  '</span></td>';
        }
        tr.innerHTML = html;
        if (!mini) {
          tr.addEventListener('click', function (ev) {
            if (ev.target.closest && ev.target.closest('[data-act]')) return;
            abre(g.id);
          });
          tr.addEventListener('keydown', function (ev) {
            if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); abre(g.id); }
          });
        }
        tbody.appendChild(tr);
      });

      els('[data-act]', tbody).forEach(function (b) {
        b.addEventListener('click', function (ev) {
          ev.stopPropagation();
          var g = datos.filter(function (x) { return x.id === b.dataset.g; })[0];
          if (b.dataset.act === 'req') {
            log(root, 'Requerimiento enviado a ' + g.nombre + ' · plazo 10 días hábiles', 'warn');
            return;
          }
          if (b.dataset.act === 'ver') { abre(g.id); return; }
          abre(g.id);
          espera(280).then(function () {
            var boton = el('[data-homologar]', panel);
            if (boton) homologa(g, boton);
          });
        });
      });

      metricas();
      var tot = el('[data-total]', root);
      if (tot) tot.textContent = 'Total: ' + datos.length + ' gestores en seguimiento';
    }

    function metricas() {
      var ok = datos.filter(function (g) { return g.estado === 'ok'; }).length;
      var warn = datos.filter(function (g) { return g.estado === 'warn'; }).length;
      var pend = datos.filter(function (g) { return g.estado === 'idle'; }).length;
      var mOk = el('[data-m-ok]', root), mWarn = el('[data-m-warn]', root), mPend = el('[data-m-pend]', root);
      if (mOk) tick(mOk, parseInt(mOk.textContent, 10) || 0, ok, function (v) { return String(Math.round(v)); }, 450);
      if (mWarn) mWarn.textContent = warn;
      if (mPend) mPend.textContent = pend;
    }

    function abre(id) {
      if (!panel) return;
      sel = id;
      var g = datos.filter(function (x) { return x.id === id; })[0];

      var pintaPanel = function () {
        var conformes = g.reqs.filter(function (r) { return r[1] === 1; }).length;
        var e = EST_G[g.estado];
        var sub = el('[data-gsub]', root);
        if (sub) sub.textContent = 'Expediente ' + g.id + ' · ' + g.nombre;

        panel.innerHTML =
          '<div class="sdetail">' +
            '<dl class="sdl">' +
              '<div><dt>Gestor</dt><dd><b>' + g.nombre + '</b></dd></div>' +
              '<div><dt>NIMA</dt><dd>' + g.nima + '</dd></div>' +
              '<div><dt>Provincia</dt><dd>' + g.prov + '</dd></div>' +
              '<div><dt>Estado</dt><dd><span class="chip ' + e[0] + '">' + e[1] + '</span></dd></div>' +
              '<div><dt>Puntuación</dt><dd><b data-score>' + (g.score || '—') + '</b> / 100 · ' + conformes + ' de ' + g.reqs.length + ' requisitos conformes</dd></div>' +
            '</dl>' +
            '<ul class="sdocs">' + g.reqs.map(function (r) {
              var st = r[1] === 1 ? 'ok' : r[1] === 'warn' ? 'warn' : 'bad';
              var chip = r[1] === 1 ? 'chip--ok' : r[1] === 'warn' ? 'chip--warn' : 'chip--bad';
              return '<li data-st="' + st + '">' +
                       '<span class="sd-box" aria-hidden="true">✓</span>' +
                       '<span class="sd-n"><b>' + r[0] + '</b></span>' +
                       '<span class="chip ' + chip + '">' + r[2] + '</span>' +
                     '</li>';
            }).join('') + '</ul>' +
            (g.estado === 'idle'
              ? '<button class="sbtn sbtn--green" type="button" data-homologar>✓ Homologar gestor</button>'
              : g.estado === 'ok'
                ? '<button class="sbtn sbtn--ghost" type="button" data-auditar>▦ Programar auditoría</button>'
                : '<button class="sbtn sbtn--orange" type="button" data-requerir>✎ Requerir documentación</button>') +
          '</div>';

        var bH = el('[data-homologar]', panel);
        if (bH) bH.addEventListener('click', function () { homologa(g, bH); });
        var bR = el('[data-requerir]', panel);
        if (bR) bR.addEventListener('click', function () {
          bR.disabled = true;
          bR.textContent = 'Requerimiento enviado';
          log(root, 'Requerimiento enviado a ' + g.nombre + ' · plazo 10 días hábiles', 'warn');
        });
        var bA = el('[data-auditar]', panel);
        if (bA) bA.addEventListener('click', function () {
          bA.disabled = true;
          bA.textContent = 'Auditoría programada';
          log(root, 'Auditoría de ' + g.nombre + ' programada para el próximo trimestre', 'ok');
        });
      };

      if (document.startViewTransition && !REDUCED) {
        var vt = document.startViewTransition(function () { pintaPanel(); pinta(); });
        vt.finished.catch(function () {});
        vt.ready.catch(function () {});
        vt.updateCallbackDone.catch(function () {});
      } else {
        pintaPanel();
        pinta();
      }
      log(root, 'Expediente ' + g.id + ' · ' + g.nombre, 'acc');
    }

    function homologa(g, boton) {
      boton.disabled = true;
      boton.textContent = 'Verificando…';
      var pasos = [
        ['Comprobando autorización en el registro de gestores…', null],
        ['NIMA ' + g.nima + ' verificado', 'ok'],
        ['Contrastando pólizas y vigencias…', null],
        ['Certificados conformes', 'ok'],
        ['Calculando puntuación de homologación…', null]
      ];
      var seq = Promise.resolve();
      pasos.forEach(function (p) {
        seq = seq.then(function () { return espera(320); }).then(function () { log(root, p[0], p[1]); });
      });
      seq.then(function () { return espera(280); }).then(function () {
        var salvedades = g.reqs.some(function (r) { return r[1] !== 1; });
        g.score = salvedades ? 76 : 91;
        g.estado = salvedades ? 'warn' : 'ok';
        var n = el('[data-score]', panel);
        if (n) tick(n, 0, g.score, function (v) { return String(Math.round(v)); }, 700);
        log(root, g.nombre + ' homologado · puntuación ' + g.score + '/100' + (salvedades ? ' (con salvedades)' : ''), salvedades ? 'warn' : 'ok');
        return espera(600);
      }).then(function () { abre(g.id); });
    }

    var reset = el('[data-reset]', root);
    if (reset) reset.addEventListener('click', function () {
      datos = JSON.parse(JSON.stringify(GESTORES_BASE));
      sel = null;
      if (panel) panel.innerHTML = '<div class="sempty"><span>Selecciona un gestor de la tabla</span><kbd>clic en una fila</kbd></div>';
      var sub = el('[data-gsub]', root);
      if (sub) sub.textContent = 'Ningún gestor seleccionado';
      pinta();
      log(root, 'Demo reiniciada · 6 gestores cargados', null);
    });

    decorativos(root, 'la cartera de gestores');
    pager(root, 1);
    pinta();
    log(root, 'Cartera cargada · 6 gestores en seguimiento', null);
  }

  /* ============================================================
     2 · INPROGEST — Dashboard interactivo
     Calcado de scrapp/dashboard_scrapp.php: accesos rápidos, cinco
     KPIs, evolución mensual y distribución de estados. Al pulsar un
     mes se cruza con todo lo demás, como en la aplicación.
     ============================================================ */

  var MESES = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

  /* Serie mensual: total de servicios y cuántos van documentados */
  var SERIE = [
    { total: 6890, doc: 3120 }, { total: 6510, doc: 2980 }, { total: 7240, doc: 3410 },
    { total: 5980, doc: 2510 }, { total: 6120, doc: 2660 }, { total: 5410, doc: 2180 },
    { total: 4980, doc: 1870 }, { total: 3120, doc: 1010 }, { total: 1740, doc: 620 },
    { total: 1420, doc: 480 }, { total: 980, doc: 340 }, { total: 400, doc: 130 }
  ];

  var OPERATIVAS = ['Recogida programada', 'Recogida a demanda', 'Retirada extraordinaria'];
  var AGRUPACIONES = ['RESTO', 'LEROY MERLIN', 'LIDL', 'Agrupación Ebro', 'Agrupación Norte'];
  var PROVEEDORES_IPG = ['RECICLADO DE COMPONENTES S.L.', 'INDUMETAL RECYCLING, S.A.',
    'RECYBERICA AMBIENTAL, S.L.', 'REELCAN - TENERIFE', 'CONSENUR SANITARIOS, S.L.'];

  var ACCESOS = [
    { i: '▤', t: 'Zona Servicios',       n: 89117, s: 'Servicios cargados' },
    { i: '▥', t: 'Documentación DI',     n: 22769, s: 'Registros DI' },
    { i: '▦', t: 'Documentación NT',     n: 3781,  s: 'NT verificadas' },
    { i: '◫', t: 'Documentación CT',     n: 62,    s: 'CT verificados' },
    { i: '◉', t: 'Documentación Ticket', n: 97,    s: 'Tickets verificados' },
    { i: '◈', t: 'R.A.M.O.N DI',         n: 11936, s: 'Pendientes DI' },
    { i: '◇', t: 'R.A.M.O.N DI API',     n: 8398,  s: 'Pendientes API' },
    { i: '◆', t: 'R.A.M.O.N NT',         n: 8634,  s: 'NT pendientes' }
  ];

  function initInprogest(root) {
    var mesSel = null;          // null = todo el año
    var barsBox = el('[data-bars]', root);

    /* Un filtro de operativa/agrupación/proveedor recorta el alcance:
       se aplica un factor estable para que los números sean coherentes. */
    var filtros = { operativa: '', agrupacion: '', proveedor: '' };
    function factor() {
      var f = 1;
      if (filtros.operativa) f *= 0.42;
      if (filtros.agrupacion) f *= 0.31;
      if (filtros.proveedor) f *= 0.18;
      return f;
    }

    function alcance() {
      var base = mesSel === null ? SERIE : [SERIE[mesSel]];
      var f = factor();
      var total = 0, doc = 0;
      base.forEach(function (m) { total += m.total; doc += m.doc; });
      return {
        total: Math.round(total * f),
        doc: Math.round(doc * f),
        nodoc: Math.round(total * f) - Math.round(doc * f)
      };
    }

    function pintaAccesos() {
      var box = el('[data-quick]', root);
      if (!box) return;
      var f = factor();
      box.innerHTML = ACCESOS.map(function (a) {
        return '<button class="squick__c" type="button" data-q="' + a.t + '">' +
                 '<span class="squick__i" aria-hidden="true">' + a.i + '</span>' +
                 '<span><span class="squick__t">' + a.t + '</span>' +
                 '<span class="squick__n">' + num(Math.round(a.n * f), 0) + '</span>' +
                 '<span class="squick__s">' + a.s + '</span></span>' +
               '</button>';
      }).join('');
      els('[data-q]', box).forEach(function (b) {
        b.addEventListener('click', function () {
          log(root, 'Abriendo módulo «' + b.dataset.q + '» con el año seleccionado', 'acc');
        });
      });
    }

    function pintaBarras() {
      var max = Math.max.apply(null, SERIE.map(function (m) { return m.total; }));
      barsBox.className = 'sbars' + (mesSel === null ? '' : ' has-sel');
      barsBox.innerHTML = SERIE.map(function (m, i) {
        var pct = Math.round((m.doc / m.total) * 100);
        return '<button class="sbar" type="button" aria-pressed="' + (mesSel === i ? 'true' : 'false') + '" data-mes="' + i + '" ' +
                 'title="' + MESES[i] + ': ' + num(m.total, 0) + ' servicios · ' + pct + '% documentado">' +
                 '<span class="sbar__cols">' +
                   '<span class="sbar__pct">' + pct + '%</span>' +
                   '<span class="sbar__a" style="height:' + Math.round((m.total / max) * 100) + '%"></span>' +
                   '<span class="sbar__b" style="height:' + Math.round((m.doc / max) * 100) + '%"></span>' +
                 '</span>' +
                 '<span class="sbar__m">' + MESES[i] + '</span>' +
               '</button>';
      }).join('');

      els('[data-mes]', barsBox).forEach(function (b) {
        b.addEventListener('click', function () {
          var i = +b.dataset.mes;
          mesSel = (mesSel === i) ? null : i;
          var selMes = el('[data-f="mes"]', root);
          if (selMes) selMes.value = mesSel === null ? '' : String(mesSel);
          pintaTodo();
          log(root, mesSel === null ? 'Mes deseleccionado · vista del año completo'
                                    : 'Cruzando por ' + MESES[mesSel] + ' · ' + num(alcance().total, 0) + ' servicios', 'acc');
        });
      });
    }

    function pintaKpis() {
      var a = alcance();
      var pct = a.total ? Math.round((a.doc / a.total) * 1000) / 10 : 0;
      var kS = el('[data-k-serv]', root);
      if (kS) tick(kS, 0, a.total, function (v) { return num(Math.round(v), 0); }, 650);
      var kV = el('[data-k-verif]', root);
      if (kV) tick(kV, 0, pct, function (v) { return num(Math.round(v * 10) / 10, 1) + '%'; }, 650);
      var kD = el('[data-k-docn]', root);
      if (kD) kD.textContent = num(a.doc, 0);
      var kR = el('[data-k-ramon]', root);
      if (kR) tick(kR, 0, Math.round(a.nodoc * 0.71), function (v) { return num(Math.round(v), 0); }, 650);
      var kP = el('[data-k-puntos]', root);
      if (kP) tick(kP, 0, Math.round(a.total * 0.338), function (v) { return num(Math.round(v), 0); }, 650);
      var kPr = el('[data-k-prov]', root);
      if (kPr) kPr.textContent = filtros.proveedor ? 1 : (filtros.agrupacion ? 14 : 48);
    }

    function pintaDonut() {
      var a = alcance();
      var pct = a.total ? Math.round((a.doc / a.total) * 1000) / 10 : 0;
      var d = el('[data-donut]', root);
      if (d) d.style.setProperty('--p', pct);
      var dp = el('[data-donutp]', root);
      if (dp) dp.textContent = num(pct, 1) + '%';
      var doc = el('[data-doc]', root), nodoc = el('[data-nodoc]', root);
      if (doc) doc.textContent = num(a.doc, 0);
      if (nodoc) nodoc.textContent = num(a.nodoc, 0);
      var docp = el('[data-docp]', root), nodocp = el('[data-nodocp]', root);
      if (docp) docp.textContent = num(pct, 1) + '%';
      if (nodocp) nodocp.textContent = num(Math.round((100 - pct) * 10) / 10, 1) + '%';
    }

    function pintaAlcance() {
      var partes = [];
      if (mesSel !== null) partes.push(MESES[mesSel]);
      if (filtros.operativa) partes.push(filtros.operativa);
      if (filtros.agrupacion) partes.push(filtros.agrupacion);
      if (filtros.proveedor) partes.push(filtros.proveedor);
      var txt = el('[data-alcance]', root);
      if (txt) {
        txt.textContent = partes.length
          ? 'ⓘ Alcance: ' + partes.join(' · ')
          : 'ⓘ Vista general del año seleccionado.';
      }
      var chip = el('[data-messel]', root);
      if (chip) {
        chip.textContent = mesSel === null ? 'Sin mes seleccionado' : MESES[mesSel] + ' seleccionado';
        chip.className = 'chip ' + (mesSel === null ? 'chip--idle' : 'chip--ok');
      }
    }

    function pintaTodo() { pintaBarras(); pintaKpis(); pintaDonut(); pintaAccesos(); pintaAlcance(); }

    /* Rellenar los selectores con los valores del sistema */
    function opciones(sel, lista, todos) {
      var s = el('[data-f="' + sel + '"]', root);
      if (!s) return;
      s.innerHTML = '<option value="">' + todos + '</option>' +
        lista.map(function (v, i) { return '<option value="' + (sel === 'mes' ? i : v) + '">' + v + '</option>'; }).join('');
    }
    opciones('mes', MESES, 'Todos');
    opciones('operativa', OPERATIVAS, 'Todas');
    opciones('agrupacion', AGRUPACIONES, 'Todas');
    opciones('proveedor', PROVEEDORES_IPG, 'Todos');

    els('[data-f]', root).forEach(function (campo) {
      campo.addEventListener('change', function () {
        var k = campo.dataset.f;
        if (k === 'anio') { log(root, 'Año ' + campo.value, null); return; }
        if (k === 'mes') {
          mesSel = campo.value === '' ? null : +campo.value;
        } else {
          filtros[k] = campo.value;
        }
        pintaTodo();
        log(root, campo.value ? 'Filtro ' + k + ' = «' + (k === 'mes' ? MESES[+campo.value] : campo.value) + '»'
                              : 'Filtro ' + k + ' quitado', 'acc');
      });
    });

    var limpiar = el('[data-limpiar]', root);
    if (limpiar) limpiar.addEventListener('click', function () {
      mesSel = null;
      filtros = { operativa: '', agrupacion: '', proveedor: '' };
      els('[data-f]', root).forEach(function (c) { if (c.dataset.f !== 'anio') c.value = ''; });
      pintaTodo();
      log(root, 'Filtros reiniciados · vista general de 2026', null);
    });

    pintaTodo();
    log(root, 'Dashboard cargado · ' + num(alcance().total, 0) + ' servicios en 2026', null);
  }

  /* ============================================================
     3 · SCRAPP POSEEDORES — gestión de documentos
     Calcada de documentos.php: filtros rápidos que filtran de verdad,
     acciones por fila y total de registros.
     ============================================================ */

  var MATERIALES_DOC = ['Cartón', 'Plástico', 'Madera', 'Metal', 'Vidrio'];
  var LER_DE = { 'Cartón': '15 01 01', 'Plástico': '15 01 02', 'Madera': '15 01 03', 'Metal': '15 01 04', 'Vidrio': '15 01 07' };

  /* Tabla estable y creíble: los mismos datos en cada carga */
  function documentosDemo() {
    var poseedores = ['Envasados del Jalón S.L.', 'Distribuciones Norte S.A.', 'Bebidas del Sur S.L.'];
    var out = [];
    for (var i = 0; i < 24; i++) {
      var m = MATERIALES_DOC[i % MATERIALES_DOC.length];
      var dia = String((i % 27) + 1).padStart(2, '0');
      out.push({
        id: 8600 - i,
        archivo: dia + '022026.pdf',
        tipo: i % 7 === 3 ? 'NT' : i % 11 === 5 ? 'CT' : 'DI',
        di: 'DCS' + (20280 + i),
        poseedor: poseedores[i % 3],
        material: m,
        ler: LER_DE[m],
        cantidad: Math.round((1.2 + (i * 1.37) % 18) * 1000) / 1000,
        pago: i % 5 === 0 ? 'pendiente' : 'considerado',
        obs: i % 4 === 0 ? 1 : 0,
        estado: i % 9 === 0 ? 'fact' : i % 3 === 0 ? 'idle' : 'ok'
      });
    }
    return out;
  }

  var EST_POSDOC = {
    ok:   ['chip--ok', 'Verificado'],
    fact: ['chip--fact', 'Facturado'],
    idle: ['chip--idle', 'Pendiente']
  };

  function initScrapp(root) {
    var todos = documentosDemo();
    var tbody = el('[data-docrows]', root);
    var filtros = {};
    var POR_PAGINA = 8;

    function coincide(d) {
      for (var k in filtros) {
        var v = (filtros[k] || '').toString().trim().toLowerCase();
        if (!v) continue;
        var mapa = {
          id: String(d.id), pago: d.pago, archivo: d.archivo, tipo: d.tipo, di: d.di,
          poseedor: d.poseedor, material: d.material, ler: d.ler,
          cantidad: String(d.cantidad).replace('.', ',')
        };
        if (!(k in mapa)) continue;
        if (String(mapa[k]).toLowerCase().indexOf(v) === -1) return false;
      }
      return true;
    }

    function pinta() {
      var lista = todos.filter(coincide);
      tbody.innerHTML = lista.slice(0, POR_PAGINA).map(function (d, i) {
        var e = EST_POSDOC[d.estado];
        return '<tr style="--i:' + i + '">' +
          '<td><span class="pill pill--doc" title="' + d.tipo + '" aria-hidden="true">▤</span></td>' +
          '<td><span class="pill pill--clip" title="Anexo" aria-hidden="true">◫</span></td>' +
          '<td><span class="pills">' +
            '<button class="pill pill--validate" type="button" title="Validar" data-v="' + d.id + '">✓</button>' +
            '<button class="pill pill--edit" type="button" title="Editar" data-e="' + d.id + '">✎</button>' +
            '<button class="pill pill--delete" type="button" title="Eliminar" data-d="' + d.id + '">×</button>' +
            '<button class="pill pill--anexo" type="button" title="Descargar" data-x="' + d.id + '">↓</button>' +
          '</span></td>' +
          '<td>' + d.pago + '</td>' +
          '<td>' + (d.obs ? '<span class="chip chip--obs">1 observación</span>' : '<span class="sub">—</span>') + '</td>' +
          '<td class="n">' + d.id + '</td>' +
          '<td class="n">' + d.archivo + '</td>' +
          '<td class="n">' + d.tipo + '</td>' +
          '<td class="n">' + d.di + '</td>' +
          '<td>' + d.material + '<span class="sub">' + d.ler + '</span></td>' +
          '<td class="n">' + num(d.cantidad, 3) + ' t<span class="sub"><span class="chip ' + e[0] + '">' + e[1] + '</span></span></td>' +
        '</tr>';
      }).join('');

      els('[data-v]', tbody).forEach(function (b) {
        b.addEventListener('click', function () {
          var d = todos.filter(function (x) { return x.id === +b.dataset.v; })[0];
          if (d.estado === 'ok' || d.estado === 'fact') {
            log(root, 'El documento ' + d.id + ' ya estaba verificado', null);
            return;
          }
          d.estado = 'ok';
          log(root, 'Documento ' + d.id + ' (' + d.archivo + ') validado · LER ' + d.ler, 'ok');
          pinta();
        });
      });
      els('[data-e]', tbody).forEach(function (b) {
        b.addEventListener('click', function () {
          log(root, 'Edición del documento ' + b.dataset.e + ' · el formulario se abre en la aplicación', null);
        });
      });
      els('[data-d]', tbody).forEach(function (b) {
        b.addEventListener('click', function () {
          var id = +b.dataset.d;
          todos = todos.filter(function (x) { return x.id !== id; });
          log(root, 'Documento ' + id + ' eliminado del listado', 'bad');
          pinta();
        });
      });
      els('[data-x]', tbody).forEach(function (b) {
        b.addEventListener('click', function () {
          var d = todos.filter(function (x) { return x.id === +b.dataset.x; })[0];
          log(root, 'Descarga de ' + d.archivo + ' solicitada · en la demo no se genera el fichero', 'warn');
        });
      });

      var tot = el('[data-total]', root);
      if (tot) tot.textContent = 'Total: ' + lista.length + ' registros encontrados';
      pager(root, Math.max(Math.ceil(lista.length / POR_PAGINA), 1));
    }

    function rellenaSelects() {
      var sp = el('[data-f="poseedor"]', root);
      if (sp) {
        var vistos = [];
        todos.forEach(function (d) { if (vistos.indexOf(d.poseedor) === -1) vistos.push(d.poseedor); });
        sp.innerHTML = '<option value="">Todos</option>' + vistos.map(function (p) { return '<option>' + p + '</option>'; }).join('');
      }
      var sm = el('[data-f="material"]', root);
      if (sm) sm.innerHTML = '<option value="">Todos</option>' + MATERIALES_DOC.map(function (m) { return '<option>' + m + '</option>'; }).join('');
    }

    function cuentaFiltros() {
      var n = Object.keys(filtros).filter(function (k) { return (filtros[k] || '').toString().trim() !== ''; }).length;
      var b = el('[data-nfiltros]', root);
      if (b) b.textContent = '▽ ' + n + (n === 1 ? ' filtro' : ' filtros');
    }

    /* Un mismo filtro aparece arriba y en la cabecera de la tabla: al
       cambiar uno se refleja en el otro, como dice la propia pantalla. */
    function sincroniza(k, valor, origen) {
      els('[data-f="' + k + '"]', root).forEach(function (c) {
        if (c !== origen && c.value !== valor) c.value = valor;
      });
    }

    els('[data-f]', root).forEach(function (campo) {
      var ev = campo.tagName === 'SELECT' ? 'change' : 'input';
      campo.addEventListener(ev, function () {
        var k = campo.dataset.f;
        if (k === 'anio') { log(root, 'Periodo ' + campo.value, null); return; }
        filtros[k] = campo.value;
        sincroniza(k, campo.value, campo);
        cuentaFiltros();
        pinta();
        if (campo.value) log(root, 'Filtro ' + k + ' = "' + campo.value + '"', 'acc');
      });
    });

    var limpiar = el('[data-limpiar]', root);
    if (limpiar) limpiar.addEventListener('click', function () {
      filtros = {};
      els('[data-f]', root).forEach(function (c) { if (c.dataset.f !== 'anio') c.value = ''; });
      cuentaFiltros();
      pinta();
      log(root, 'Filtros limpiados', null);
    });

    var desc = el('[data-descargar]', root);
    if (desc) desc.addEventListener('click', function () {
      log(root, 'Exportación a Excel solicitada · en la demo no se genera el fichero', 'warn');
    });
    var nf = el('[data-nfiltros]', root);
    if (nf) nf.addEventListener('click', function () {
      var act = Object.keys(filtros).filter(function (k) { return filtros[k]; });
      log(root, 'Filtros activos: ' + (act.join(', ') || 'ninguno'), null);
    });

    rellenaSelects();
    cuentaFiltros();
    pinta();
    log(root, 'Gestión de documentos · ' + todos.length + ' registros del periodo 2026', null);
  }

  /* ============================================================
     Conmutador de demos + arranque diferido
     ============================================================ */

  var ARRANQUE = { probatus: initProbatus, inprogest: initInprogest, scrapp: initScrapp };

  function arranca(root) {
    if (root.dataset.ready === '1') return;
    root.dataset.ready = '1';
    var fn = ARRANQUE[root.dataset.demo];
    if (fn) fn(root);
  }

  function initTabs(grupo) {
    var tabs = els('[role="tab"]', grupo);
    var paneles = els('[role="tabpanel"]', grupo);
    function abre(i) {
      tabs.forEach(function (t, j) {
        t.setAttribute('aria-selected', i === j ? 'true' : 'false');
        t.tabIndex = i === j ? 0 : -1;
      });
      paneles.forEach(function (p, j) {
        p.hidden = i !== j;
        if (i === j) { var d = el('[data-demo]', p); if (d) arranca(d); }
      });
    }
    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { abre(i); });
      t.addEventListener('keydown', function (ev) {
        var n = ev.key === 'ArrowRight' ? i + 1 : ev.key === 'ArrowLeft' ? i - 1 : -1;
        if (n >= 0 && n < tabs.length) { ev.preventDefault(); tabs[n].focus(); abre(n); }
      });
    });
    abre(0);
  }

  function boot() {
    els('[data-demotabs]').forEach(initTabs);

    var sueltas = els('[data-demo]').filter(function (d) { return !d.closest('[role="tabpanel"]'); });
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entradas) {
        entradas.forEach(function (e) {
          if (e.isIntersecting) { arranca(e.target); io.unobserve(e.target); }
        });
      }, { rootMargin: '200px' });
      sueltas.forEach(function (d) { io.observe(d); });
    } else {
      sueltas.forEach(arranca);
    }
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
