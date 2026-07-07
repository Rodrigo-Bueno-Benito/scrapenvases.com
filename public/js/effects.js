/* ============================================================
   ScrapEnvases — motor de efectos "La RAP conectada"
   Red de nodos, split-text, contadores, magnetismo, tilt 3D,
   header inteligente y barra de progreso.
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
     4) Motor de interpolación compartido (magnetismo + tilt)
     ============================================================ */
  var lerpItems = [];
  var lerpRunning = false;
  function lerpLoop() {
    var active = false;
    lerpItems.forEach(function (it) {
      var done = true;
      Object.keys(it.target).forEach(function (k) {
        var cur = it.state[k] || 0;
        var next = cur + (it.target[k] - cur) * 0.16;
        if (Math.abs(it.target[k] - next) > 0.01) done = false;
        else next = it.target[k];
        it.state[k] = next;
      });
      it.apply(it.state);
      if (!done) active = true;
    });
    if (active) { requestAnimationFrame(lerpLoop); }
    else { lerpRunning = false; }
  }
  function kickLerp() {
    if (!lerpRunning) { lerpRunning = true; requestAnimationFrame(lerpLoop); }
  }

  /* ---- Botones magnéticos ---- */
  if (!REDUCED && FINE_POINTER) {
    document.querySelectorAll('.btn').forEach(function (btn) {
      var item = {
        state: { x: 0, y: 0 },
        target: { x: 0, y: 0 },
        apply: function (s) {
          btn.style.setProperty('--mag-x', s.x.toFixed(2) + 'px');
          btn.style.setProperty('--mag-y', s.y.toFixed(2) + 'px');
        }
      };
      lerpItems.push(item);
      btn.addEventListener('pointermove', function (e) {
        var r = btn.getBoundingClientRect();
        var dx = e.clientX - (r.left + r.width / 2);
        var dy = e.clientY - (r.top + r.height / 2);
        item.target.x = Math.max(-10, Math.min(10, dx * 0.18));
        item.target.y = Math.max(-8, Math.min(8, dy * 0.3));
        kickLerp();
      });
      btn.addEventListener('pointerleave', function () {
        item.target.x = 0; item.target.y = 0;
        kickLerp();
      });
    });
  }

  /* ---- Tilt 3D con brillo [data-tilt] ---- */
  if (!REDUCED && FINE_POINTER) {
    document.querySelectorAll('[data-tilt]').forEach(function (card) {
      var item = {
        state: { rx: 0, ry: 0 },
        target: { rx: 0, ry: 0 },
        apply: function (s) {
          card.style.setProperty('--rx', s.rx.toFixed(2) + 'deg');
          card.style.setProperty('--ry', s.ry.toFixed(2) + 'deg');
        }
      };
      lerpItems.push(item);
      card.addEventListener('pointermove', function (e) {
        var r = card.getBoundingClientRect();
        var px = (e.clientX - r.left) / r.width;   // 0..1
        var py = (e.clientY - r.top) / r.height;
        item.target.ry = (px - 0.5) * 10;   // gira hacia el cursor
        item.target.rx = (0.5 - py) * 8;
        card.style.setProperty('--glow-x', (px * 100).toFixed(1) + '%');
        card.style.setProperty('--glow-y', (py * 100).toFixed(1) + '%');
        kickLerp();
      });
      card.addEventListener('pointerleave', function () {
        item.target.rx = 0; item.target.ry = 0;
        kickLerp();
      });
    });
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
     6) Red de nodos del hero — los agentes de la RAP conectados
     ============================================================ */
  var canvas = document.querySelector('[data-net]');
  if (canvas && !REDUCED) {
    var ctx = canvas.getContext('2d');
    var host = canvas.parentElement;
    var DPR = Math.min(window.devicePixelRatio || 1, 2);
    var W = 0, H = 0;
    var nodes = [];
    var pointer = { x: -9999, y: -9999 };
    var running = false;
    var rafId = 0;

    var PALETTE = [
      { c: '255,255,255', w: 0.72 },   // blanco: la mayoría
      { c: '255,132,44',  w: 0.16 },   // naranja de marca
      { c: '211,236,229', w: 0.12 }    // mint suave
    ];
    function pickColor() {
      var r = Math.random(), acc = 0;
      for (var i = 0; i < PALETTE.length; i++) {
        acc += PALETTE[i].w;
        if (r <= acc) return PALETTE[i].c;
      }
      return PALETTE[0].c;
    }

    function resize() {
      var rect = host.getBoundingClientRect();
      W = rect.width; H = rect.height;
      canvas.width = Math.round(W * DPR);
      canvas.height = Math.round(H * DPR);
      ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
      var count = Math.round(Math.min(64, Math.max(26, (W * H) / 26000)));
      if (nodes.length !== count) {
        nodes = [];
        for (var i = 0; i < count; i++) {
          nodes.push({
            x: Math.random() * W,
            y: Math.random() * H,
            vx: (Math.random() - 0.5) * 0.35,
            vy: (Math.random() - 0.5) * 0.35,
            r: 1.4 + Math.random() * 2.2,
            c: pickColor()
          });
        }
      }
    }

    var LINK_DIST = 130;
    function frame() {
      ctx.clearRect(0, 0, W, H);

      for (var i = 0; i < nodes.length; i++) {
        var n = nodes[i];
        // Deriva
        n.x += n.vx; n.y += n.vy;
        // El cursor atrae ligeramente los nodos cercanos (la red responde)
        var pdx = pointer.x - n.x, pdy = pointer.y - n.y;
        var pd2 = pdx * pdx + pdy * pdy;
        if (pd2 < 32400 && pd2 > 1) { // < 180px
          var f = 0.012 / Math.max(Math.sqrt(pd2), 20);
          n.vx += pdx * f; n.vy += pdy * f;
        }
        // Límite de velocidad + rebote suave en bordes
        var vmax = 0.55;
        if (n.vx > vmax) n.vx = vmax; if (n.vx < -vmax) n.vx = -vmax;
        if (n.vy > vmax) n.vy = vmax; if (n.vy < -vmax) n.vy = -vmax;
        if (n.x < -20) n.x = W + 20; if (n.x > W + 20) n.x = -20;
        if (n.y < -20) n.y = H + 20; if (n.y > H + 20) n.y = -20;
      }

      // Conexiones
      ctx.lineWidth = 1;
      for (var a = 0; a < nodes.length; a++) {
        for (var b = a + 1; b < nodes.length; b++) {
          var dx = nodes[a].x - nodes[b].x;
          var dy = nodes[a].y - nodes[b].y;
          var d2 = dx * dx + dy * dy;
          if (d2 < LINK_DIST * LINK_DIST) {
            var alpha = (1 - Math.sqrt(d2) / LINK_DIST) * 0.35;
            ctx.strokeStyle = 'rgba(255,255,255,' + alpha.toFixed(3) + ')';
            ctx.beginPath();
            ctx.moveTo(nodes[a].x, nodes[a].y);
            ctx.lineTo(nodes[b].x, nodes[b].y);
            ctx.stroke();
          }
        }
      }

      // Nodos
      for (var k = 0; k < nodes.length; k++) {
        var nd = nodes[k];
        ctx.fillStyle = 'rgba(' + nd.c + ',0.85)';
        ctx.beginPath();
        ctx.arc(nd.x, nd.y, nd.r, 0, Math.PI * 2);
        ctx.fill();
      }

      if (running) rafId = requestAnimationFrame(frame);
    }

    function start() { if (!running) { running = true; rafId = requestAnimationFrame(frame); } }
    function stop() { running = false; cancelAnimationFrame(rafId); }

    resize();
    window.addEventListener('resize', function () { resize(); }, { passive: true });

    host.addEventListener('pointermove', function (e) {
      var r = canvas.getBoundingClientRect();
      pointer.x = e.clientX - r.left;
      pointer.y = e.clientY - r.top;
    });
    host.addEventListener('pointerleave', function () { pointer.x = -9999; pointer.y = -9999; });

    // Solo anima cuando el hero está en pantalla
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { e.isIntersecting ? start() : stop(); });
      }, { threshold: 0.05 }).observe(host);
    } else {
      start();
    }
    document.addEventListener('visibilitychange', function () {
      document.hidden ? stop() : start();
    });
  }
})();
