document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', () => {
  // Menú móvil
  const nav = document.querySelector('.nav');
  const toggle = document.querySelector('.nav__toggle');
  if (nav && toggle) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('nav--open');
      toggle.setAttribute('aria-expanded', String(open));
    });
    document.addEventListener('keydown', (ev) => {
      if (ev.key === 'Escape' && nav.classList.contains('nav--open')) {
        nav.classList.remove('nav--open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.focus();
      }
    });
  }

  const motion = window.matchMedia('(prefers-reduced-motion: no-preference)');
  const wide = window.matchMedia('(min-width: 961px)');
  const clamp = (v) => Math.min(1, Math.max(0, v));

  // Frases que se "encienden" palabra a palabra al leerlas: se envuelve cada palabra
  // en un <span> sin tocar el texto (las negritas y enlaces se conservan).
  const reveals = motion.matches ? [...document.querySelectorAll('[data-reveal]')] : [];
  reveals.forEach((el) => {
    const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT);
    const nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);
    nodes.forEach((node) => {
      const frag = document.createDocumentFragment();
      node.textContent.split(/(\s+)/).forEach((part) => {
        if (!part) return;
        if (/^\s+$/.test(part)) {
          frag.appendChild(document.createTextNode(part));
        } else {
          const w = document.createElement('span');
          w.className = 'w';
          w.textContent = part;
          frag.appendChild(w);
        }
      });
      node.replaceWith(frag);
    });
    el.words = [...el.querySelectorAll('.w')];
    el.lit = -1;
    el.classList.add('is-split');
  });

  // Titulares en dos voces (portada y páginas de perfil): la primera entra letra a letra.
  // Cada palabra va en un bloque que no se parte; el titular conserva su texto para lectores.
  const splitTitle = (title, aSel, bSel, wordClass) => {
    const first = title && title.querySelector(aSel);
    if (!first) return;
    title.setAttribute('aria-label', title.textContent.replace(/\s+/g, ' ').trim());
    const words = first.textContent.trim().split(/\s+/);
    first.textContent = '';
    first.setAttribute('aria-hidden', 'true');
    const second = title.querySelector(bSel);
    if (second) second.setAttribute('aria-hidden', 'true');
    let n = 0;
    words.forEach((word, k) => {
      if (k) first.append(' ');
      const w = document.createElement('span');
      w.className = wordClass;
      [...word].forEach((ch) => {
        const l = document.createElement('i');
        l.textContent = ch;
        l.style.setProperty('--i', n++);
        w.append(l);
      });
      first.append(w);
    });
  };
  splitTitle(document.querySelector('.stage__title'), '.stage__title-a', '.stage__title-b', 'stage__word');
  splitTitle(document.querySelector('.phero__title'), '.phero__a', '.phero__b', 'phero__word');

  // Páginas de perfil: la cabecera entra al cargar, ya con las fuentes listas
  const phero = document.querySelector('[data-phero]');
  if (phero && motion.matches) {
    phero.classList.add('is-anim');
    const fonts = document.fonts ? document.fonts.ready : Promise.resolve();
    Promise.race([fonts, new Promise((r) => setTimeout(r, 700))]).then(() => {
      void phero.offsetWidth;   // que el estado inicial se pinte antes de animar
      setTimeout(() => phero.classList.add('is-on'), 40);
    });
  }

  // Portada: escenario fijo que avanza con el scroll. Con la escena 3D (hero3d.js) se usa
  // en cualquier pantalla; sin ella, solo en pantalla ancha. Nunca si se pide reducir movimiento.
  const stage = document.querySelector('[data-stage]');
  const syncStage = () => {
    if (stage) stage.classList.toggle('is-staged', motion.matches && (wide.matches || stage.classList.contains('is-3d')));
  };
  syncStage();

  const photos = motion.matches ? [...document.querySelectorAll('[data-photo]')] : [];
  const needs = motion.matches ? [...document.querySelectorAll('[data-need]')] : [];
  const chevrons = motion.matches ? document.querySelector('[data-chevrons]') : null;
  const apps = [...document.querySelectorAll('[data-app]')];
  // Pila de apartados de las herramientas: cada uno se queda fijo y el siguiente sube por
  // encima. Si un apartado es más alto que la pantalla, se fija por abajo para leerlo entero.
  // Con el viaje 3D (journey.js, que se ejecuta antes) no hay pila: el viaje lleva los apartados.
  const coreApps = motion.matches ? document.querySelector('.core-apps') : null;
  const inJourney = () => !!coreApps && coreApps.classList.contains('is-journey');
  const stackWrap = coreApps && !inJourney() ? coreApps : null;
  const stack = stackWrap ? [...stackWrap.children] : [];
  const syncStack = () => {
    const vh = window.innerHeight;
    stack.forEach((el) => el.style.setProperty('--stick', Math.min(0, vh - el.offsetHeight) + 'px'));
  };
  if (stackWrap) {
    stackWrap.classList.add('is-stacked');
    syncStack();
  }
  const playing = new WeakMap();
  const setVideo = (sec, on) => {
    if (playing.get(sec) === on) return;
    playing.set(sec, on);
    sec.querySelectorAll('[data-app-video]').forEach((video) => {
      if (on) { const pr = video.play(); if (pr) pr.catch(() => {}); } else video.pause();
    });
  };

  let ticking = false;
  const update = () => {
    ticking = false;
    const vh = window.innerHeight;

    // Sobre la escena oscura de la portada la barra sigue transparente; en la del perfil, hasta
    // que el titular (que sube con el scroll) llega a ella
    const navH = nav ? nav.offsetHeight : 0;
    const solidAt = stage ? stage.offsetHeight - navH
      : phero ? Math.max(8, phero.querySelector('.phero__txt').offsetTop - navH - 48) : 24;
    if (nav) nav.classList.toggle('nav--solid', window.scrollY > solidAt);

    if (stage && stage.classList.contains('is-staged')) {
      const r = stage.getBoundingClientRect();
      const p = clamp(-r.top / Math.max(1, r.height - vh));
      stage.style.setProperty('--p', p.toFixed(4));
      stage.classList.toggle('txt-on', p > 0.32);
    }

    reveals.forEach((el) => {
      const r = el.getBoundingClientRect();
      const p = clamp((vh * 0.9 - r.top) / (r.height + vh * 0.35));
      const lit = Math.round(p * el.words.length);
      if (lit !== el.lit) {
        el.words.forEach((w, i) => w.classList.toggle('on', i < lit));
        el.lit = lit;
      }
    });

    if (phero && motion.matches) {
      const r = phero.getBoundingClientRect();
      phero.style.setProperty('--p', clamp(-r.top / Math.max(1, r.height)).toFixed(4));
      // si la cabecera no cabe en pantalla (Normativa en el móvil), su texto no se desvanece al leerlo
      phero.classList.toggle('is-tall', r.height > vh * 1.1);
    }

    // Paneles de necesidad apilados: cuánto tapa a cada uno el siguiente
    needs.forEach((el, i) => {
      const next = needs[i + 1];
      const cover = next ? clamp((el.getBoundingClientRect().bottom - next.getBoundingClientRect().top) / Math.max(1, el.offsetHeight)) : 0;
      el.style.setProperty('--cover', cover.toFixed(3));
    });

    photos.forEach((f) => {
      const r = f.getBoundingClientRect();
      f.style.setProperty('--p', clamp((vh - r.top) / (vh * 0.85)).toFixed(3));
    });

    if (chevrons) {
      const r = chevrons.getBoundingClientRect();
      chevrons.style.setProperty('--p', clamp((vh - r.top) / (vh + r.height)).toFixed(3));
    }

    // Apartados de las herramientas: cuánto tapa a cada uno el siguiente (--cover), progreso
    // de su paso por la pantalla (--p) y su vídeo, que solo corre mientras se ve.
    // Con el viaje 3D (journey.js) es él quien lleva los apartados y sus vídeos.
    if (motion.matches && !inJourney()) {
      const covers = new Map();
      stack.forEach((el, i) => {
        const next = stack[i + 1];
        let cover = 0;
        if (next) {
          const r = el.getBoundingClientRect();
          cover = clamp((r.bottom - next.getBoundingClientRect().top) / Math.max(1, Math.min(vh, r.height)));
        }
        covers.set(el, cover);
        el.style.setProperty('--cover', cover.toFixed(3));
      });
      apps.forEach((sec) => {
        const r = sec.getBoundingClientRect();
        const cover = covers.get(sec) || 0;
        setVideo(sec, sec.classList.contains('is-in') && r.top < vh && r.bottom > 0 && cover < 0.95);
        if (r.bottom < -vh || r.top > vh * 2) return;
        sec.style.setProperty('--p', clamp((vh - r.top) / (vh + r.height)).toFixed(3));
      });
    }
  };
  const onScroll = () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', () => { syncStage(); syncStack(); onScroll(); });
  window.addEventListener('load', () => { syncStack(); onScroll(); });
  if (wide.addEventListener) wide.addEventListener('change', () => { syncStage(); onScroll(); });
  update();

  // Apartados de las herramientas: al entrar arrancan sus animaciones y su pantalla
  // (el fragmento del vídeo); con "reducir movimiento", todo quieto y sin vídeo.
  if (apps.length) {
    if (!motion.matches || !('IntersectionObserver' in window)) {
      apps.forEach((sec) => sec.classList.add('is-in'));
      onScroll();
    } else {
      const io = new IntersectionObserver((entries) => {
        entries.forEach((en) => {
          if (!en.isIntersecting) return;
          en.target.classList.add('is-in');
          io.unobserve(en.target);
          onScroll();
        });
      }, { threshold: 0.3 });
      apps.forEach((sec) => io.observe(sec));
    }
  }

  // Tu papel en el sistema: las tarjetas suben escalonadas al llegar
  const roles = document.querySelector('[data-roles]');
  if (roles) {
    if (!motion.matches || !('IntersectionObserver' in window)) {
      roles.classList.add('is-in');
    } else {
      const io = new IntersectionObserver((entries) => {
        if (entries.some((en) => en.isIntersecting)) { roles.classList.add('is-in'); io.disconnect(); }
      }, { threshold: 0.2 });
      io.observe(roles);
    }
  }

  // Paneles de necesidad: su foto se asienta y el texto sube al llegar a cada uno
  const needEls = [...document.querySelectorAll('[data-need]')];
  if (needEls.length) {
    if (!motion.matches || !('IntersectionObserver' in window)) {
      needEls.forEach((el) => el.classList.add('is-in'));
    } else {
      const io = new IntersectionObserver((entries) => {
        entries.forEach((en) => {
          if (!en.isIntersecting) return;
          en.target.classList.add('is-in');
          io.unobserve(en.target);
        });
      }, { threshold: 0.3 });
      needEls.forEach((el) => io.observe(el));
    }
  }
});
