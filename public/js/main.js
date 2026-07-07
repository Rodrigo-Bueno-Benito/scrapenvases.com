/* Interacciones editoriales: nav móvil, reveal on scroll, cookies, preview de imagen */
(function () {
  'use strict';

  // ---- Nav móvil ----
  var toggle = document.querySelector('.navtoggle');
  var nav = document.getElementById('mainnav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    nav.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () { nav.classList.remove('is-open'); });
    });
  }

  // (El reveal por scroll vive ahora en effects.js)

  // ---- Cookie banner ----
  var bar = document.querySelector('.cookiebar');
  if (bar) {
    try {
      if (localStorage.getItem('se_cookies_ok') === '1') {
        bar.classList.add('hidden');
      } else {
        bar.classList.remove('hidden');
      }
    } catch (e) { /* localStorage bloqueado */ }
    var accept = bar.querySelector('[data-cookie-accept]');
    if (accept) {
      accept.addEventListener('click', function () {
        try { localStorage.setItem('se_cookies_ok', '1'); } catch (e) {}
        bar.classList.add('hidden');
      });
    }
  }

  // ---- Calculadora de contribución (poseedores) ----
  var calc = document.querySelector('[data-calc]');
  if (calc) {
    var eur = new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' });
    var rows = calc.querySelectorAll('.calc__row');
    var totalEl = calc.querySelector('[data-total]');
    var totalBox = calc.querySelector('.calc__total');
    var lastTotal = null;

    function recalc() {
      var total = 0;
      rows.forEach(function (row) {
        var rate = parseFloat(row.getAttribute('data-rate')) || 0;
        var input = row.querySelector('input');
        var qty = parseFloat((input.value || '').replace(',', '.'));
        if (isNaN(qty) || qty < 0) qty = 0;
        var sub = qty * rate;
        total += sub;
        row.querySelector('[data-sub]').textContent = eur.format(sub);
      });
      if (totalEl) totalEl.textContent = eur.format(total);
      // Pulso del total cuando cambia el importe
      if (totalBox && lastTotal !== null && total !== lastTotal) {
        totalBox.classList.remove('is-pulse');
        void totalBox.offsetWidth; // reinicia la animación
        totalBox.classList.add('is-pulse');
      }
      lastTotal = total;
    }

    calc.addEventListener('input', function (e) {
      if (e.target && e.target.matches('input')) recalc();
    });
    recalc();
  }

  // ---- Preview de imagen en admin ----
  var fileInput = document.querySelector('[data-imgfield]');
  var preview = document.querySelector('[data-imgpreview]');
  if (fileInput && preview) {
    fileInput.addEventListener('change', function () {
      var file = fileInput.files && fileInput.files[0];
      if (!file) return;
      var reader = new FileReader();
      reader.onload = function (e) {
        preview.innerHTML = '<img src="' + e.target.result + '" alt="Vista previa">';
      };
      reader.readAsDataURL(file);
    });
  }
})();
