/* ============================================================
   ScrapEnvases — motor del filmstrip de portada
   ============================================================
   El bloque .film mide varias pantallas; dentro, el escenario se
   queda pegado y el recorrido se publica en --p (0 → 1). De ahí
   salen el acto visible y la entrada de la ventana.

   Principio: la mejora es opcional. El atributo data-film-listo
   solo se pone cuando el anclado tiene sentido (hay JS, hay sitio
   y no se ha pedido menos movimiento). Sin él, el CSS deja los
   tres actos en vertical y la página se lee igual.
   ============================================================ */
(function () {
  'use strict';

  var film = document.querySelector('[data-film]');
  if (!film) return;

  var pista  = film.querySelector('.film__track');
  var escena = film.querySelector('.film__stage');
  var num    = film.querySelector('[data-film-num]');
  var tabs   = film.querySelectorAll('[role="tab"]');
  if (!pista || !escena) return;

  var ACTOS = tabs.length || 3;
  /* Antes del primer acto se reserva un tramo para que la ventana
     entre: si empezara ya en el acto 0, el visitante no vería el
     gesto de llegada. */
  var ENTRADA = 0.14;

  var menosMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)');
  var sitioSuficiente = window.matchMedia('(min-width: 880px) and (min-height: 620px)');

  var anclado = false;
  var actoActual = -1;

  function aplica() {
    if (!anclado) return;

    var caja = pista.getBoundingClientRect();
    var recorrido = pista.offsetHeight - escena.offsetHeight;
    if (recorrido <= 0) return;

    var p = Math.min(1, Math.max(0, -caja.top / recorrido));
    escena.style.setProperty('--p', p.toFixed(4));

    /* El tramo de entrada no cuenta como acto */
    var q = (p - ENTRADA) / (1 - ENTRADA);
    var acto = Math.min(ACTOS - 1, Math.max(0, Math.floor(q * ACTOS)));
    if (acto === actoActual) return;

    actoActual = acto;
    escena.dataset.acto = String(acto);
    if (num) num.textContent = '0' + (acto + 1);
    /* La pestaña es la que monta la demo: se pulsa, no se duplica
       la lógica de arranque que ya vive en demos.js. */
    if (tabs[acto] && tabs[acto].getAttribute('aria-selected') !== 'true') tabs[acto].click();
  }

  /* El recorrido se lee en bucle mientras el bloque está en pantalla,
     no en el evento de scroll: los eventos se agrupan y el scroll
     suave mueve la página entre ellos, así que atarse a ellos deja
     el acto desfasado. El bucle solo vive mientras hace falta. */
  var vivo = false;
  function bucle() {
    if (!vivo) return;
    aplica();
    requestAnimationFrame(bucle);
  }
  function arranca() { if (vivo || !anclado) return; vivo = true; requestAnimationFrame(bucle); }
  function para()    { vivo = false; }

  /* Y además en el propio evento de scroll: cuando el navegador
     limita requestAnimationFrame (pestaña en segundo plano, ahorro
     de energía) el bucle se queda a dos fotogramas por segundo y el
     acto se desincroniza de lo que se ve. aplica() es idempotente,
     llamarla de más no cuesta nada. */
  window.addEventListener('scroll', function () { if (anclado) aplica(); }, { passive: true });

  function evalua() {
    var procede = sitioSuficiente.matches && !menosMovimiento.matches;
    if (procede === anclado) return;

    anclado = procede;
    if (anclado) {
      film.setAttribute('data-film-listo', '');
      actoActual = -1;
      arranca();
    } else {
      para();
      film.removeAttribute('data-film-listo');
      escena.style.removeProperty('--p');
      escena.dataset.acto = '0';
      actoActual = -1;
    }
  }

  /* Fuera de pantalla no hay nada que calcular */
  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (e) {
      e[0].isIntersecting ? arranca() : para();
    }, { rootMargin: '10% 0px' }).observe(pista);
  }

  /* Al pulsar una pestaña a mano, el recorrido salta a ese acto en
     vez de pelearse con el visitante en el siguiente scroll. */
  Array.prototype.forEach.call(tabs, function (t, i) {
    t.addEventListener('click', function () {
      if (!anclado) return;
      actoActual = i;
      escena.dataset.acto = String(i);
      if (num) num.textContent = '0' + (i + 1);
    });
  });

  evalua();
  window.addEventListener('resize', function () { evalua(); arranca(); }, { passive: true });
  if (sitioSuficiente.addEventListener) sitioSuficiente.addEventListener('change', evalua);
  if (menosMovimiento.addEventListener) menosMovimiento.addEventListener('change', evalua);
})();
