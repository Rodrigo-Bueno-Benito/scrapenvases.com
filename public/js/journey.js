/**
 * El viaje del core, con escenas reales (fotos y vídeo generados con la IA de Freepik):
 *   mapa aéreo del circuito → oficina del gestor (PROBATUS) → caseta de la báscula
 *   (INPROGEST) → el mismo camión llega al muelle del almacén del poseedor → dentro, SCRAPP
 *   → el mapa con la red iluminada.
 * La cámara se mueve con el scroll sobre cada foto; para pasar de PROBATUS a INPROGEST entra
 * en la pantalla del portátil y sale por la del siguiente. La pantalla real de cada herramienta
 * (su vídeo) se encaja en el portátil de la foto con una transformación de perspectiva.
 * Los textos son los del HTML (los apartados .app y su cabecera): aquí solo se colocan sobre
 * la escena y aparecen en su parada. Con "reducir movimiento" no se activa y los apartados se
 * quedan apilados como siempre.
 */
const wrap = document.querySelector('.core-apps');
const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
const lerp = (a, b, t) => a + (b - a) * t;
const ramp = (t, a, b) => clamp((t - a) / (b - a));
const easeIn = (t) => t * t * t;
const easeOut = (t) => 1 - Math.pow(1 - t, 3);
const easeInOut = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);
const easeOutBack = (t) => { const c1 = 1.6, c3 = c1 + 1; return 1 + c3 * Math.pow(t - 1, 3) + c1 * Math.pow(t - 1, 2); };

const BASE = '/media/viaje/';
const AR = 2688 / 1520;                        // proporción de las fotos
const ACT_T = [0.215, 0.51, 0.855];            // centro de cada parada en el recorrido (0 → 1)
const ROUTE_FRAMES = 35;                       // el camión, ya cerca del almacén, hasta el muelle (media/viaje/ruta/)
const DOOR = [0.73, 0.49];                     // puerta del muelle en el último fotograma de la ruta
// El guion está escrito en una escala fija (t del guion, 0 → 1). Cada tramo pide más o menos
// scroll (peso): la transición INPROGEST → SCRAPP va rápida y el cierre "Trazabilidad total"
// se queda más tiempo en pantalla.
const SEGMENTS = [[0, 0.56, 1], [0.56, 0.8, 0.5], [0.8, 0.95, 1], [0.95, 1, 3]];
const SPAN = SEGMENTS.reduce((a, [s0, s1, w]) => a + (s1 - s0) * w, 0);   // × 680vh = alto del viaje
const toScript = (u) => {      // fracción del scroll → t del guion
  let x = u * SPAN;
  for (const [s0, s1, w] of SEGMENTS) {
    const len = (s1 - s0) * w;
    if (x <= len) return s0 + x / w;
    x -= len;
  }
  return 1;
};
const fromScript = (t) => {    // t del guion → fracción del scroll
  let x = 0;
  for (const [s0, s1, w] of SEGMENTS) {
    if (t <= s1) return (x + (Math.max(t, s0) - s0) * w) / SPAN;
    x += (s1 - s0) * w;
  }
  return 1;
};
const LIVE_DOTS = 5;           // datos que circulan por la red en el cierre
// Esquinas de la pantalla del portátil en cada foto (sup. izq., sup. dcha., inf. dcha., inf. izq.)
const QUAD = {
  probatus: [[0.67572, 0.42853], [0.94469, 0.42344], [0.93584, 0.72228], [0.65735, 0.69906]],
  inprogest: [[0.15327, 0.5687], [0.36926, 0.55707], [0.379, 0.7478], [0.15415, 0.78975]],
  scrapp: [[0.7009, 0.52641], [0.90661, 0.5359], [0.90139, 0.75139], [0.69227, 0.73055]],
};
// Puntos de cada foto: paradas en el mapa, gestores que se homologan, el camión, la bala
const HOT = { probatus: [0.275, 0.44], inprogest: [0.533, 0.245], scrapp: [0.75, 0.53] };
const SEALS = [[0.485, 0.41], [0.53, 0.354], [0.70, 0.265], [0.82, 0.33], [0.92, 0.243]];
const TRUCK = [0.75, 0.39];
const LABEL = [0.64, 0.173];
const ROUTE = 'M275 249 L330 290 L412 300 L540 330 L540 200 L533 139 L600 128 L680 150 L750 300 L700 420 L560 520 L430 480 L330 400 Z';
const GLOW = { probatus: '#45d69e', inprogest: '#3cc4f2', scrapp: '#ffa25a' };
const SCREEN_W = 1600, SCREEN_H = 1000;        // pantalla (16:10) antes de encajarla

const center = (q) => [(q[0][0] + q[1][0] + q[2][0] + q[3][0]) / 4, (q[0][1] + q[1][1] + q[2][1] + q[3][1]) / 4];

// Homografía: rectángulo (0,0)-(w,h) → cuadrilátero (px), como matrix3d de CSS
function matrix3d(w, h, p) {
  const src = [[0, 0], [w, 0], [w, h], [0, h]];
  const A = [], b = [];
  for (let i = 0; i < 4; i++) {
    const [x, y] = src[i], [u, v] = p[i];
    A.push([x, y, 1, 0, 0, 0, -u * x, -u * y]); b.push(u);
    A.push([0, 0, 0, x, y, 1, -v * x, -v * y]); b.push(v);
  }
  for (let c = 0; c < 8; c++) {           // eliminación de Gauss con pivote
    let m = c;
    for (let r = c + 1; r < 8; r++) if (Math.abs(A[r][c]) > Math.abs(A[m][c])) m = r;
    [A[c], A[m]] = [A[m], A[c]]; [b[c], b[m]] = [b[m], b[c]];
    for (let r = 0; r < 8; r++) {
      if (r === c) continue;
      const f = A[r][c] / A[c][c];
      for (let k = c; k < 8; k++) A[r][k] -= f * A[c][k];
      b[r] -= f * b[c];
    }
  }
  const [a, bb, c, d, e, f, g, hh] = b.map((v, i) => v / A[i][i]);
  return `matrix3d(${[a, d, 0, g, bb, e, 0, hh, 0, 0, 1, 0, c, f, 0, 1].map((n) => +n.toFixed(9)).join(',')})`;
}

function init() {
  const ids = ['probatus', 'inprogest', 'scrapp'];
  const sections = ids.map((id) => wrap.querySelector('.app--' + id));
  if (sections.some((s) => !s)) return;
  const head = wrap.querySelector('.core-apps__head');
  const closing = wrap.querySelector('.core-apps__end');
  const el = (tag, cls, parent) => { const n = document.createElement(tag); if (cls) n.className = cls; if (parent) parent.append(n); return n; };

  /* ---------- Escenas: foto + cámara (una caja con la proporción de la foto) ---------- */
  const journey = el('div', 'journey');
  const stage = el('div', 'journey__stage', journey);
  const scenes = {};
  ['mapa', ...ids].forEach((name) => {
    const sc = el('div', `jr-scene jr-scene--${name}`, stage);
    sc.setAttribute('aria-hidden', 'true');
    const cam = el('div', 'jr-cam', sc);
    const img = el('img', 'jr-img', cam);
    img.alt = ''; img.decoding = 'async';
    img.sizes = '(max-aspect-ratio: 2688/1520) 177vh, 100vw';
    img.srcset = `${BASE}${name}-1280.webp 1280w, ${BASE}${name}.webp 2560w`;
    img.src = `${BASE}${name}.webp`;
    scenes[name] = { sc, cam };
  });

  // Mapa: la red que une las tres paradas y sus puntos
  const svgNS = 'http://www.w3.org/2000/svg';
  const svg = document.createElementNS(svgNS, 'svg');
  svg.setAttribute('class', 'jr-map');
  svg.setAttribute('viewBox', `0 0 1000 ${(1000 / AR).toFixed(1)}`);
  svg.setAttribute('preserveAspectRatio', 'none');
  svg.innerHTML = `<path class="jr-map__route" d="${ROUTE}"/><path class="jr-map__lit" d="${ROUTE}" pathLength="1"/>`
    + Array.from({ length: LIVE_DOTS }, (_, k) => `<circle class="jr-map__flow" r="5"><animateMotion dur="7s" repeatCount="indefinite" begin="${(-k * 7 / LIVE_DOTS).toFixed(2)}s" path="${ROUTE}"/></circle>`).join('')
    + ids.map((id) => `<g class="jr-map__hot" style="--c:${GLOW[id]}" transform="translate(${HOT[id][0] * 1000} ${(HOT[id][1] * 1000 / AR).toFixed(1)})"><circle class="jr-map__pulse" r="16"/><circle class="jr-map__dot" r="7"/></g>`).join('');
  scenes.mapa.cam.append(svg);
  const lit = svg.querySelector('.jr-map__lit');

  // Pantallas: el vídeo de cada apartado se encaja en el portátil de su foto
  const screens = {};
  ids.forEach((id, i) => {
    const holder = el('div', 'jr-screen', scenes[id].cam);
    holder.style.width = SCREEN_W + 'px';
    holder.style.height = SCREEN_H + 'px';
    const fig = sections[i].querySelector('.app__screen');
    if (fig) holder.append(fig);
    el('span', 'jr-screen__gloss', holder);
    screens[id] = { holder, video: fig && fig.querySelector('video') };
  });

  // PROBATUS: un haz recorre la planta y cada gestor recibe su sello de homologado
  const scan = el('div', 'jr-scan', scenes.probatus.cam);
  const seals = SEALS.map(([x, y]) => {
    const s = el('span', 'jr-seal', scenes.probatus.cam);
    s.style.left = x * 100 + '%'; s.style.top = y * 100 + '%';
    s.style.setProperty('--c', GLOW.probatus);
    return s;
  });
  // INPROGEST: documentos del servicio del camión al portátil
  const docs = Array.from({ length: 6 }, () => el('span', 'jr-doc', scenes.inprogest.cam));
  // SCRAPP: la etiqueta de la bala se valida
  const ripple = el('span', 'jr-ripple', scenes.scrapp.cam);
  ripple.style.left = LABEL[0] * 100 + '%'; ripple.style.top = LABEL[1] * 100 + '%';
  const labelSeal = el('span', 'jr-seal jr-seal--big', scenes.scrapp.cam);
  labelSeal.style.left = LABEL[0] * 100 + '%'; labelSeal.style.top = (LABEL[1] - 0.07) * 100 + '%';
  labelSeal.style.setProperty('--c', GLOW.scrapp);

  // Fotogramas que avanzan con el scroll: el camión llega al muelle del almacén
  const sequence = (dir, n, canvas) => {
    const ctx = canvas.getContext('2d');
    const frames = [];
    let shown = -1, shownFx = -1;
    const geo = { x: 0, w: 1 };   // dónde quedó dibujado el fotograma (para centrar el empuje)
    return {
      geo,
      load() {
        if (frames.length) return;
        for (let i = 0; i < n; i++) {
          const im = new Image();
          im.decoding = 'async';
          im.src = `${BASE}${dir}/${String(i).padStart(2, '0')}.webp`;
          frames.push(im);
        }
      },
      reset() { shown = -1; },
      draw(k, fx = 0.5) {   // k: 0 → 1; fx: punto del fotograma que se centra si hay que recortar
        fx = Math.round(fx * 200) / 200;
        if (!frames.length) return;
        let i = Math.round(clamp(k) * (n - 1));
        while (i > 0 && !(frames[i].complete && frames[i].naturalWidth)) i--;
        const im = frames[i];
        if ((i === shown && fx === shownFx) || !im.complete || !im.naturalWidth) return;
        const cw = canvas.width, ch = canvas.height;
        const sc = Math.max(cw / im.naturalWidth, ch / im.naturalHeight);
        const w = im.naturalWidth * sc, h = im.naturalHeight * sc;
        const x = clamp(cw / 2 - fx * w, cw - w, 0);
        ctx.drawImage(im, x, (ch - h) / 2, w, h);
        geo.x = x / cw; geo.w = w / cw;
        shown = i; shownFx = fx;
      },
    };
  };
  const route = el('canvas', 'jr-route', stage);
  route.setAttribute('aria-hidden', 'true');
  const ride = sequence('ruta', ROUTE_FRAMES, route);

  // Capas de lectura, textos (los del HTML), destello y carril de progreso
  const shadeC = el('div', 'journey__shade journey__shade--c', stage);
  const shadeL = el('div', 'journey__shade journey__shade--l', stage);
  const shadeR = el('div', 'journey__shade journey__shade--r', stage);
  if (head) {
    stage.append(head);
    // la primera voz del titular entra letra a letra (el h2 conserva su texto para lectores)
    const title = head.querySelector('.core-head__title');
    const word = head.querySelector('.core-head__a');
    if (title && word) {
      title.setAttribute('aria-label', title.textContent.replace(/\s+/g, ' ').trim());
      const text = word.textContent;
      word.textContent = '';
      word.setAttribute('aria-hidden', 'true');
      head.querySelector('.core-head__b')?.setAttribute('aria-hidden', 'true');
      let n = 0;
      text.split(' ').forEach((w, k) => {
        if (k) word.append(' ');
        const box = el('span', 'core-head__word', word);
        [...w].forEach((ch) => { const l = el('i', '', box); l.textContent = ch; l.style.setProperty('--i', n++); });
      });
    }
  }
  sections.forEach((s) => stage.append(s));
  if (closing) {
    stage.append(closing);
    // "TRAZABILIDAD" entra letra a letra: el titular conserva su texto para lectores de pantalla
    const title = closing.querySelector('.core-end__title');
    const word = closing.querySelector('.core-end__a');
    if (title && word) {
      title.setAttribute('aria-label', title.textContent.replace(/\s+/g, ' ').trim());
      const letters = [...word.textContent];
      word.textContent = '';
      word.setAttribute('aria-hidden', 'true');
      letters.forEach((ch, i) => { const l = el('i', '', word); l.textContent = ch; l.style.setProperty('--i', i); });
      closing.querySelector('.core-end__b')?.setAttribute('aria-hidden', 'true');
    }
  }
  const flash = el('div', 'journey__flash', stage);
  const rail = el('div', 'journey__rail', stage);
  rail.setAttribute('aria-hidden', 'true');
  rail.innerHTML = '<i></i>' + ACT_T.map((t) => `<b style="top:${(fromScript(t) * 100).toFixed(1)}%"></b>`).join('');
  const railFill = rail.querySelector('i');
  const railDots = [...rail.querySelectorAll('b')];
  wrap.append(journey);
  wrap.classList.remove('is-stacked');
  wrap.classList.add('is-journey');

  // Con el teclado: al entrar en los enlaces de una parada, el recorrido salta a ella
  sections.forEach((s, i) => s.addEventListener('focusin', () => {
    const top = window.scrollY + journey.getBoundingClientRect().top;
    const y = top + fromScript(ACT_T[i]) * (journey.offsetHeight - window.innerHeight);
    if (Math.abs(window.scrollY - y) > window.innerHeight * 0.25) window.scrollTo({ top: y, behavior: 'auto' });
  }));

  /* ---------- Medidas: la caja de cada foto cubre la pantalla ---------- */
  const narrow = window.matchMedia('(max-width: 960px)');
  let W = 1, H = 1, bw = 1, bh = 1, portrait = false;
  function resize() {
    W = stage.clientWidth; H = stage.clientHeight;
    if (!W || !H) return;
    portrait = W / H < 0.9 || narrow.matches;
    bw = Math.max(W, H * AR); bh = bw / AR;
    Object.values(scenes).forEach(({ cam }) => { cam.style.width = bw + 'px'; cam.style.height = bh + 'px'; });
    ids.forEach((id) => {
      const q = QUAD[id].map(([x, y]) => [x * bw, y * bh]);
      screens[id].holder.style.transform = matrix3d(SCREEN_W, SCREEN_H, q);
    });
    const dpr = Math.min(window.devicePixelRatio || 1, 1.5);
    route.width = Math.round(W * dpr); route.height = Math.round(H * dpr);
    ride.reset();
  }
  new ResizeObserver(resize).observe(stage);

  /* ---------- Planos: punto de la foto (fx, fy) colocado en (vx, vy) de la pantalla ---------- */
  const shot = (s, fx, fy, vx = 0.5, vy = 0.5) => ({ s, fx, fy, vx, vy });
  const mix = (a, b, k) => shot(Math.exp(lerp(Math.log(a.s), Math.log(b.s), k)), lerp(a.fx, b.fx, k), lerp(a.fy, b.fy, k), lerp(a.vx, b.vx, k), lerp(a.vy, b.vy, k));
  const place = (cam, sh) => {
    const s = Math.max(1, sh.s);
    const left = clamp(sh.vx * W - sh.fx * bw * s, W - bw * s, 0);
    const top = clamp(sh.vy * H - sh.fy * bh * s, H - bh * s, 0);
    cam.style.transform = `translate3d(${left.toFixed(1)}px, ${top.toFixed(1)}px, 0) scale(${s.toFixed(4)})`;
  };
  const fillScreen = (id) => {   // plano que llena la pantalla con la del portátil
    const q = QUAD[id];
    const qw = (Math.max(q[1][0], q[2][0]) - Math.min(q[0][0], q[3][0])) * bw;
    const qh = (Math.max(q[2][1], q[3][1]) - Math.min(q[0][1], q[1][1])) * bh;
    const [cx, cy] = center(q);
    return shot(Math.max(W / qw, H / qh) * 1.12, cx, cy);
  };
  const hold = (id, d) => {      // plano de la parada: el portátil a un lado, el texto al otro
    const [cx, cy] = center(QUAD[id]);
    // En vertical la foto apenas puede bajar: se acerca lo justo para dejar el portátil bajo el texto
    if (portrait) return shot(Math.max(1.05, (0.74 * H) / (cy * bh) * 1.02) + d * 0.04, cx, cy, 0.5, 0.74);
    const side = id === 'inprogest' ? -1 : 1;
    return shot(1.04 + d * 0.06, cx, cy, 0.5 + side * (0.22 - d * 0.015), 0.56);
  };
  const mapWhole = () => shot(1, 0.5, 0.5);
  const mapAt = (id, s) => shot(s, HOT[id][0], HOT[id][1]);

  /* ---------- Fotograma ---------- */
  const cache = new WeakMap();
  const setStyle = (node, prop, val) => {
    const m = cache.get(node) || {};
    if (m[prop] !== val) { node.style[prop] = val; m[prop] = val; cache.set(node, m); }
  };
  const layer = (node, o, z) => {
    setStyle(node, 'opacity', o.toFixed(3));
    setStyle(node, 'visibility', o > 0.001 ? 'visible' : 'hidden');
    if (z !== undefined) setStyle(node, 'zIndex', String(z));
  };
  const showText = (node, o) => {
    setStyle(node, 'opacity', o.toFixed(3));
    setStyle(node, 'transform', `translateY(${((1 - o) * 26).toFixed(1)}px)`);
    node.classList.toggle('is-live', o > 0.5);
  };
  let tS = -1, last = performance.now();

  function frame(now) {
    const dt = Math.min(0.05, (now - last) / 1000);
    last = now;
    const time = now / 1000;
    const r = journey.getBoundingClientRect();
    const vh = window.innerHeight;
    const tRaw = clamp(-r.top / Math.max(1, r.height - vh));
    tS = tS < 0 ? tRaw : tS + (tRaw - tS) * (1 - Math.exp(-dt * 7));
    if (Math.abs(tRaw - tS) < 1e-4) tS = tRaw;
    const t = toScript(tS);

    // Capas: cada escena entra por encima de la anterior
    const end = ramp(t, 0.925, 0.955);
    layer(scenes.mapa.sc, t < 0.14 ? 1 : end, t > 0.8 ? 6 : 1);
    layer(scenes.probatus.sc, t < 0.36 ? ramp(t, 0.105, 0.135) : 0, 2);
    layer(scenes.inprogest.sc, t >= 0.36 && t < 0.64 ? 1 : 0, 3);
    layer(route, t < 0.8 ? ramp(t, 0.6, 0.63) : 0, 4);
    layer(scenes.scrapp.sc, t < 0.96 ? ramp(t, 0.765, 0.795) : 0, 5);

    // Cámara en cada escena
    if (t < 0.5) place(scenes.mapa.cam, mix(mapWhole(), mapAt('probatus', 3.4), easeInOut(ramp(t, 0.05, 0.14))));
    else place(scenes.mapa.cam, mix(mapAt('scrapp', 3.0), mapWhole(), easeInOut(ramp(t, 0.925, 0.975))));
    if (t < 0.36) {
      let sh;
      if (t < 0.16) sh = mix(shot(1.45, ...center(QUAD.probatus), 0.5, 0.5), hold('probatus', 0), easeOut(ramp(t, 0.105, 0.16)));
      else if (t < 0.30) sh = hold('probatus', ramp(t, 0.16, 0.30));
      else sh = mix(hold('probatus', 1), fillScreen('probatus'), easeIn(ramp(t, 0.30, 0.36)));
      place(scenes.probatus.cam, sh);
    }
    if (t >= 0.36 && t < 0.64) {
      let sh;
      if (t < 0.42) sh = mix(fillScreen('inprogest'), hold('inprogest', 0), easeOut(ramp(t, 0.36, 0.42)));
      else if (t < 0.57) sh = hold('inprogest', ramp(t, 0.42, 0.57));
      // la cámara se acerca al camión de la ventana y pasa a él, ya llegando al almacén
      else sh = mix(hold('inprogest', 1), shot(1.4, 0.76, 0.41), easeInOut(ramp(t, 0.57, 0.63)));
      place(scenes.inprogest.cam, sh);
    }
    if (t >= 0.765 && t < 0.96) {
      // se entra por la puerta del muelle: dentro están la bala y el operario
      const sh = t < 0.82
        ? mix(shot(1.55, 0.56, 0.36, 0.5, 0.5), hold('scrapp', 0), easeOut(ramp(t, 0.765, 0.82)))
        : hold('scrapp', ramp(t, 0.82, 0.92));
      place(scenes.scrapp.cam, sh);
    }

    // PROBATUS: el haz y los sellos
    const sweep = ramp(t, 0.17, 0.27);
    setStyle(scan, 'left', (lerp(0.43, 0.98, sweep) * 100).toFixed(2) + '%');
    setStyle(scan, 'opacity', (Math.sin(Math.PI * sweep)).toFixed(3));
    seals.forEach((s, i) => {
      const k = ramp(sweep, (SEALS[i][0] - 0.43) / 0.55 - 0.02, (SEALS[i][0] - 0.43) / 0.55 + 0.1);
      setStyle(s, 'transform', `translate(-50%, -50%) scale(${(k > 0 ? Math.max(0, easeOutBack(k)) : 0).toFixed(3)})`);
    });

    // INPROGEST: los documentos del servicio vuelan del camión al portátil
    const actI = ramp(t, 0.40, 0.44) * (1 - ramp(t, 0.545, 0.57));
    const [sx, sy] = center(QUAD.inprogest);
    docs.forEach((d, k) => {
      const u = (time / 2.6 + k / docs.length) % 1;
      const e = easeInOut(u);
      const x = lerp(TRUCK[0], sx, e), y = lerp(TRUCK[1], sy, e) - Math.sin(Math.PI * e) * 0.16;
      setStyle(d, 'left', (x * 100).toFixed(2) + '%');
      setStyle(d, 'top', (y * 100).toFixed(2) + '%');
      setStyle(d, 'transform', `translate(-50%, -50%) rotate(${(Math.sin(time * 2 + k) * 14).toFixed(1)}deg) scale(${(actI * Math.sin(Math.PI * u) * 1.1).toFixed(3)})`);
    });

    // SCRAPP: la etiqueta de la bala queda validada
    const rk = ramp(t, 0.82, 0.88);
    setStyle(ripple, 'transform', `translate(-50%, -50%) scale(${lerp(0.4, 2.4, rk).toFixed(3)})`);
    setStyle(ripple, 'opacity', (Math.sin(Math.PI * rk)).toFixed(3));
    const lk = ramp(t, 0.84, 0.88);
    setStyle(labelSeal, 'transform', `translate(-50%, -50%) scale(${(lk > 0 ? Math.max(0, easeOutBack(lk)) : 0).toFixed(3)})`);

    // El camión llega al muelle y se entra por la puerta
    if (t > 0.45) ride.load();
    // en vertical el fotograma se recorta: primero se sigue al camión y al llegar, a la puerta
    if (t > 0.6 && t < 0.8) ride.draw(ramp(t, 0.62, 0.765), lerp(0.5, DOOR[0], easeInOut(ramp(t, 0.725, 0.77))));
    const push = easeIn(ramp(t, 0.75, 0.795));
    setStyle(route, 'transformOrigin', `${((ride.geo.x + DOOR[0] * ride.geo.w) * 100).toFixed(1)}% ${(DOOR[1] * 100).toFixed(1)}%`);
    setStyle(route, 'transform', `scale(${(1 + push * 1.4).toFixed(3)})`);

    // Mapa final: la red se ilumina entera
    setStyle(lit, 'strokeDashoffset', (1 - ramp(t, 0.94, 0.975)).toFixed(4));
    svg.classList.toggle('is-live', t > 0.965);

    // Textos de cada parada (del HTML) y sombreado para leerlos
    const oHead = 1 - ramp(t, 0.025, 0.06);
    const o1 = ramp(t, 0.13, 0.16) * (1 - ramp(t, 0.28, 0.31));
    const o2 = ramp(t, 0.42, 0.45) * (1 - ramp(t, 0.535, 0.565));
    const o3 = ramp(t, 0.80, 0.83) * (1 - ramp(t, 0.915, 0.945));
    const oEnd = ramp(t, 0.965, 0.982);
    if (head) {
      showText(head, oHead);
      head.classList.toggle('is-on', oHead > 0.5 && r.top < vh * 0.55);   // al llegar al viaje
    }
    if (closing) {
      setStyle(closing, 'opacity', Math.min(1, oEnd * 3).toFixed(3));
      closing.classList.toggle('is-on', oEnd > 0.3);
    }
    [o1, o2, o3].forEach((o, i) => {
      showText(sections[i], o);
      sections[i].classList.toggle('is-in', o > 0.5);
    });
    setStyle(shadeC, 'opacity', Math.max(oHead, oEnd).toFixed(3));
    setStyle(shadeL, 'opacity', (portrait ? Math.max(o1, o2, o3) : Math.max(o1, o3)).toFixed(3));
    setStyle(shadeR, 'opacity', (portrait ? 0 : o2).toFixed(3));
    setStyle(flash, 'opacity', clamp(1 - Math.abs(t - 0.36) / 0.016).toFixed(3));
    setStyle(railFill, 'transform', `scaleY(${tS.toFixed(4)})`);
    railDots.forEach((d, i) => d.classList.toggle('is-on', [o1, o2, o3][i] > 0.5));
    setStyle(rail, 'opacity', clamp(ramp(t, 0.05, 0.1) * (1 - ramp(t, 0.95, 0.99))).toFixed(3));

    // Vídeos: solo corre el de la parada que se ve
    const want = { probatus: t > 0.1 && t < 0.37, inprogest: t > 0.35 && t < 0.64, scrapp: t > 0.76 && t < 0.96 };
    ids.forEach((id) => {
      const v = screens[id].video;
      if (!v) return;
      if (want[id]) {
        if (v.preload !== 'auto') v.preload = 'auto';
        if (v.paused) { const pr = v.play(); if (pr) pr.catch(() => {}); }
      } else if (!v.paused) v.pause();
    });
  }

  // Solo se anima mientras el viaje está en pantalla
  let visible = false, running = false;
  const loop = (now) => {
    if (!visible) { running = false; ids.forEach((id) => screens[id].video && screens[id].video.pause()); return; }
    frame(now);
    requestAnimationFrame(loop);
  };
  new IntersectionObserver((entries) => {
    visible = entries.some((en) => en.isIntersecting);
    if (visible && !running) { running = true; last = performance.now(); requestAnimationFrame(loop); }
  }, { rootMargin: '120px 0px' }).observe(journey);
  resize();
  frame(performance.now());
}

if (wrap && !reduced) {
  try { init(); } catch (err) { console.warn('[journey] se quedan los apartados apilados:', err); }
}
