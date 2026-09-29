/* ============================================================
   ScrapEnvases — motor de efectos "La RAP conectada"
   Split-text, contadores, header inteligente y barra de progreso.
   Se retiraron la red de nodos, el magnetismo y el tilt 3D: son
   gestos de escaparate y el portal se dirige a grandes cuentas.
   Todo se desactiva con prefers-reduced-motion.
   ============================================================ */
(function () {
  'use strict';

  var REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var FINE_POINTER = window.matchMedia('(pointer: fine)').matches;

  /* ============================================================
     1) Reveals por scroll (elementos sueltos + grupos en cascada)
     ============================================================ */
  var revealables = document.querySelectorAll('[data-reveal], [data-reveal-group]');
  if (REDUCED || !('IntersectionObserver' in window)) {
    revealables.forEach(function (el) { el.classList.add('is-visible'); });
  } else if (revealables.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -4% 0px' });
    revealables.forEach(function (el) { io.observe(el); });
  }

  /* ============================================================
     2) Split de titulares en palabras
     ============================================================ */
  function splitWords(root) {
    var wi = 0;
    function walk(node) {
      var children = Array.prototype.slice.call(node.childNodes);
      children.forEach(function (child) {
        if (child.nodeType === Node.TEXT_NODE) {
          var parts = child.textContent.split(/(\s+)/);
          var frag = document.createDocumentFragment();
          parts.forEach(function (part) {
            if (!part) return;
            if (/^\s+$/.test(part)) {
              frag.appendChild(document.createTextNode(part));
            } else {
              var span = document.createElement('span');
              span.className = 'w';
              span.style.setProperty('--wi', wi++);
              span.textContent = part;
              frag.appendChild(span);
            }
          });
          node.replaceChild(frag, child);
        } else if (child.nodeType === Node.ELEMENT_NODE) {
          if (child.tagName === 'BR' || child.tagName === 'SVG' || child.tagName === 'svg') return;
          if (child.classList.contains('hl')) {
            // El énfasis subrayado entra como una sola unidad
            child.classList.add('w');
            child.style.setProperty('--wi', wi++);
            return;
          }
          walk(child);
        }
      });
    }
    walk(root);
  }

  var splitTargets = document.querySelectorAll('[data-split]');
  if (!REDUCED && splitTargets.length) {
    splitTargets.forEach(splitWords);
    var fireSplit = function () {
      requestAnimationFrame(function () {
        splitTargets.forEach(function (el) { el.classList.add('is-split-in'); });
      });
    };
    if (document.fonts && document.fonts.ready) {
      var done = false;
      var go = function () { if (!done) { done = true; fireSplit(); } };
      document.fonts.ready.then(go);
      setTimeout(go, 450); // red de seguridad si las fuentes tardan
    } else {
      fireSplit();
    }
  } else {
    splitTargets.forEach(function (el) { el.classList.add('is-split-in'); });
  }

  /* ============================================================
     3) Contadores animados [data-count]
     ============================================================ */
  var counters = document.querySelectorAll('[data-count]');
  if (counters.length) {
    var fmtEs = new Intl.NumberFormat('es-ES');
    var runCounter = function (el) {
      var target = parseFloat(el.getAttribute('data-count')) || 0;
      var suffix = el.getAttribute('data-suffix') || '';
      var plain = el.hasAttribute('data-plain');
      var dur = 1500;
      var t0 = null;
      function frame(t) {
        if (t0 === null) t0 = t;
        var p = Math.min((t - t0) / dur, 1);
        var eased = 1 - Math.pow(2, -10 * p); // easeOutExpo
        if (p >= 1) eased = 1;
        var val = Math.round(target * eased);
        el.textContent = (plain ? String(val) : fmtEs.format(val)) + suffix;
        if (p < 1) requestAnimationFrame(frame);
      }
      requestAnimationFrame(frame);
    };
    if (REDUCED || !('IntersectionObserver' in window)) {
      counters.forEach(function (el) {
        var t = parseFloat(el.getAttribute('data-count')) || 0;
        var plain = el.hasAttribute('data-plain');
        el.textContent = (plain ? String(t) : new Intl.NumberFormat('es-ES').format(t)) + (el.getAttribute('data-suffix') || '');
      });
    } else {
      var cio = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            runCounter(entry.target);
            cio.unobserve(entry.target);
          }
        });
      }, { threshold: 0.5 });
      counters.forEach(function (el) { cio.observe(el); });
    }
  }

  /* ============================================================
     5) Header inteligente + barra de progreso
     ============================================================ */
  var header = document.querySelector('.site-header');
  var progress = document.querySelector('.scrollbar-progress');
  var lastY = window.scrollY;
  var ticking = false;
  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(function () {
      var y = window.scrollY;
      if (progress) {
        var max = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.setProperty('--scroll-p', max > 0 ? (y / max).toFixed(4) : 0);
      }
      if (header && !REDUCED) {
        header.classList.toggle('is-scrolled', y > 12);
        var nav = document.getElementById('mainnav');
        var navOpen = nav && nav.classList.contains('is-open');
        if (!navOpen) {
          if (y > lastY && y > 320) header.classList.add('is-hidden');
          else header.classList.remove('is-hidden');
        }
      }
      lastY = y;
      ticking = false;
    });
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ============================================================
     6) Scroll suave — inercia corta, no "scroll de agencia"
     ============================================================
     Un amortiguador sobre el scroll nativo: el salto de la rueda se
     reparte en unos fotogramas. Factor alto (0,14) a propósito —
     lo que se busca es que el movimiento no sea a tirones, no que
     la página persiga al cursor medio segundo después.

     Se desactiva con menos movimiento, con puntero grueso (el
     scroll táctil ya tiene su propia inercia del sistema) y si el
     navegador no trae scrollBehavior, para no quedarnos a medias.
     ============================================================ */
  var suave = !REDUCED && FINE_POINTER && 'scrollBehavior' in document.documentElement.style;
  if (suave) {
    var destino = window.scrollY;
    var animando = false;

    function tope() {
      return document.documentElement.scrollHeight - window.innerHeight;
    }
    /* `behavior: instant` es obligatorio: html lleva scroll-behavior
       smooth, así que un scrollTo normal animaría cada fotograma
       intermedio y se sumarían dos suavizados. */
    function salta(y) { window.scrollTo({ top: y, behavior: 'instant' }); }
    function paso() {
      var actual = window.scrollY;
      var delta = destino - actual;
      if (Math.abs(delta) < 0.5) {
        salta(destino);
        animando = false;
        return;
      }
      salta(actual + delta * 0.14);
      requestAnimationFrame(paso);
    }
    window.addEventListener('wheel', function (e) {
      // Zoom del navegador y desplazamiento horizontal: que pase de largo
      if (e.ctrlKey || e.metaKey || Math.abs(e.deltaX) > Math.abs(e.deltaY)) return;
      // Contenedores con su propio scroll (tablas de las demos) mandan
      var n = e.target;
      while (n && n !== document.body) {
        if (n.scrollHeight - n.clientHeight > 4) {
          var st = getComputedStyle(n).overflowY;
          if (st === 'auto' || st === 'scroll') return;
        }
        n = n.parentElement;
      }
      e.preventDefault();
      var salto = e.deltaMode === 1 ? e.deltaY * 18 : e.deltaMode === 2 ? e.deltaY * window.innerHeight : e.deltaY;
      destino = Math.max(0, Math.min(tope(), destino + salto));
      if (!animando) { animando = true; requestAnimationFrame(paso); }
    }, { passive: false });

    /* Cualquier desplazamiento que no venga de la rueda —una ancla,
       un scrollIntoView, buscar en la página, arrastrar la barra—
       manda: si no estamos animando, el destino se reengancha a
       donde esté la página. Sin esto el amortiguador tira hacia
       atrás de todo salto programático. */
    window.addEventListener('scroll', function () {
      if (!animando) destino = window.scrollY;
    }, { passive: true });
    ['keydown', 'mousedown', 'touchstart'].forEach(function (ev) {
      window.addEventListener(ev, function () { destino = window.scrollY; animando = false; }, { passive: true });
    });
    window.addEventListener('resize', function () { destino = window.scrollY; animando = false; }, { passive: true });
  }

  /* ============================================================
     7) Píldora de contacto permanente
     ============================================================
     Aparece cuando la primera pantalla ya quedó atrás y se retira
     al llegar al pie, que tiene su propia llamada: dos veces la
     misma acción a la vista es una de más.
     ============================================================ */
  var pilote = document.querySelector('[data-pilote]');
  if (pilote) {
    var pie = document.querySelector('.site-footer');
    var visible = false;
    var pTick = false;
    function revisa() {
      pTick = false;
      var pasadaPrimera = window.scrollY > window.innerHeight * 0.75;
      var pieALaVista = pie ? pie.getBoundingClientRect().top < window.innerHeight * 0.9 : false;
      var debe = pasadaPrimera && !pieALaVista;
      if (debe === visible) return;
      visible = debe;
      pilote.classList.toggle('is-on', debe);
    }
    window.addEventListener('scroll', function () {
      if (pTick) return;
      pTick = true;
      requestAnimationFrame(revisa);
    }, { passive: true });
    revisa();
  }

})();
