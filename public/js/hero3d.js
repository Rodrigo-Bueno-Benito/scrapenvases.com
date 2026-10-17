/**
 * Portada en 3D: el favicon aparece, de su "S" y su "e" se despliega el logo de
 * SCRAPenvases (letras extruidas + red de nodos) y suben las placas 3D de las tres
 * herramientas del core. Con el scroll el conjunto se recoloca y deja paso al titular.
 *
 * Mejora progresiva: si no hay WebGL o se pide reducir movimiento, la portada queda
 * en su versión estática (logo plano, texto y core en HTML). El texto de la página no
 * vive aquí: el lienzo es decorativo y las etiquetas del core son los enlaces del HTML.
 */
import * as THREE from 'three';
import { SVGLoader } from 'three/addons/loaders/SVGLoader.js';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';

const stage = document.querySelector('[data-stage]');
const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
const lerp = (a, b, t) => a + (b - a) * t;
const ramp = (t, a, b) => clamp((t - a) / (b - a));
const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);
const easeInOutCubic = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);
const easeOutBack = (t) => { const c1 = 1.5, c3 = c1 + 1; return 1 + c3 * Math.pow(t - 1, 3) + c1 * Math.pow(t - 1, 2); };

function webglAvailable() {
  try {
    const c = document.createElement('canvas');
    return !!(window.WebGL2RenderingContext && c.getContext('webgl2')) || !!c.getContext('webgl');
  } catch (e) {
    return false;
  }
}

if (stage && !prefersReduced && webglAvailable()) {
  init().catch((err) => {
    console.warn('[hero3d] se mantiene la portada estática:', err);
    stage.classList.remove('is-3d');
  });
}

async function init() {
  const base = '/media/3d/';
  const [logoTxt, red, ...toolTxts] = await Promise.all([
    fetch(base + 'scrapenvases.svg').then((r) => r.text()),
    fetch(base + 'red.json').then((r) => r.json()),
    fetch(base + 'probatus.svg').then((r) => r.text()),
    fetch(base + 'inprogest.svg').then((r) => r.text()),
    fetch(base + 'scrapp.svg').then((r) => r.text()),
  ]);
  const loader = new SVGLoader();

  /* ---------- Render, cámara y luz ---------- */
  const canvas = document.createElement('canvas');
  canvas.className = 'stage__canvas';
  canvas.setAttribute('aria-hidden', 'true');
  stage.querySelector('.stage').prepend(canvas);
  stage.querySelector('.stage__bg')?.after(canvas);

  const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  renderer.toneMapping = THREE.NeutralToneMapping;   // respeta los colores de marca
  renderer.toneMappingExposure = 1.0;
  renderer.outputColorSpace = THREE.SRGBColorSpace;

  const scene = new THREE.Scene();
  const pmrem = new THREE.PMREMGenerator(renderer);
  scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;

  const camera = new THREE.PerspectiveCamera(30, 1, 0.1, 200);
  const key = new THREE.DirectionalLight(0xffffff, 1.6);
  key.position.set(-6, 8, 10);
  const rim = new THREE.DirectionalLight(0x4fd8c6, 2.2);
  rim.position.set(7, 3, -6);
  const fill = new THREE.DirectionalLight(0x6aa8ff, 0.6);
  fill.position.set(-8, -4, 4);
  scene.add(key, rim, fill, new THREE.AmbientLight(0xffffff, 0.15));

  /* ---------- Logo de SCRAPenvases (espacio del SVG, y hacia abajo) ---------- */
  const W = red.w, H = red.h;
  const S = 10 / W;                       // unidades de mundo por píxel del trazado
  const logo = new THREE.Group();         // se coloca en el mundo
  const logoSvg = new THREE.Group();      // espacio del SVG: escala (S, -S, S)
  logoSvg.scale.set(S, -S, S);
  logoSvg.position.set(-W / 2 * S, H / 2 * S, 0);
  logo.add(logoSvg);
  scene.add(logo);

  const glossWhite = new THREE.MeshPhysicalMaterial({ color: 0xffffff, roughness: 0.26, metalness: 0, clearcoat: 1, clearcoatRoughness: 0.1 });
  const LETTER_DEPTH = 0.24 / S;
  const BEVEL = 0.025 / S;

  function extrude(path, depth, bevel = 0, curveSegments = 10) {
    const shapes = SVGLoader.createShapes(path);
    const geo = new THREE.ExtrudeGeometry(shapes, {
      depth, curveSegments,
      bevelEnabled: bevel > 0, bevelThickness: bevel, bevelSize: bevel * 0.7, bevelSegments: 3,
    });
    geo.computeBoundingBox();
    const c = new THREE.Vector3();
    geo.boundingBox.getCenter(c);
    geo.translate(-c.x, -c.y, -depth / 2);
    return { geo, center: c };
  }

  const parsed = loader.parse(logoTxt);
  const letters = [];
  let tagline = null;
  for (const p of parsed.paths) {
    const id = p.userData.node.id;
    if (id === 'tagline') {
      const { geo, center } = extrude(p, 0.03 / S, 0, 6);
      tagline = new THREE.Mesh(geo, new THREE.MeshStandardMaterial({ color: 0xcfd6dc, roughness: 0.55, transparent: true, opacity: 0 }));
      tagline.position.set(center.x, center.y, 0);
      tagline.userData.final = center.clone();
      logoSvg.add(tagline);
    } else {
      const { geo, center } = extrude(p, LETTER_DEPTH, BEVEL);
      const m = new THREE.Mesh(geo, glossWhite);
      m.userData.final = new THREE.Vector3(center.x, center.y, 0);
      m.userData.index = Number(id.slice(1));
      m.userData.size = new THREE.Vector3();
      geo.boundingBox.getSize(m.userData.size);
      letters.push(m);
      logoSvg.add(m);
    }
  }
  letters.sort((a, b) => a.userData.index - b.userData.index);
  const glyphS = letters[0];
  const glyphE = letters[5];

  /* ---------- Red de nodos de la comunidad ---------- */
  const net = new THREE.Group();
  logoSvg.add(net);
  const nodeMat = new THREE.MeshStandardMaterial({ color: 0xa9b6c2, roughness: 0.25, metalness: 0.7 });
  const edgeMat = new THREE.MeshStandardMaterial({ color: 0x8e9cab, roughness: 0.35, metalness: 0.6, transparent: true, opacity: 0.8 });
  const zOff = { A: 0.35, B: -0.7, C: -0.55, D: 0.55, E: 0.5, F: -0.45, G: -0.6, H: 0.4, I: -1.1, J: -1.3, K: -1.0 };
  const nodePos = red.nodes.map((n) => new THREE.Vector3(n.x, n.y, (zOff[n.id] ?? 0) / S));
  const nodes = red.nodes.map((n, i) => {
    const mesh = new THREE.Mesh(new THREE.SphereGeometry(Math.max(n.r, 14), 24, 16), nodeMat);
    mesh.position.copy(nodePos[i]);
    mesh.scale.setScalar(0);
    const d = Math.hypot(n.x - W / 2, n.y - H / 2) / (W / 2);
    mesh.userData.delay = d;
    net.add(mesh);
    return mesh;
  });
  const up = new THREE.Vector3(0, 1, 0);
  const edges = red.edges.map(([a, b]) => {
    const pa = nodePos[a], pb = nodePos[b];
    const dir = new THREE.Vector3().subVectors(pb, pa);
    const len = dir.length();
    const geo = new THREE.CylinderGeometry(5, 5, 1, 8, 1, true);
    geo.translate(0, 0.5, 0);           // nace en el nodo A y crece hacia B
    const mesh = new THREE.Mesh(geo, edgeMat);
    mesh.position.copy(pa);
    mesh.quaternion.setFromUnitVectors(up, dir.clone().normalize());
    mesh.scale.set(1, 0.0001, 1);
    mesh.userData.len = len;
    mesh.userData.delay = Math.min(nodes[a].userData.delay, nodes[b].userData.delay);
    net.add(mesh);
    return { mesh, pa, pb, len };
  });
  // Pulsos de luz que viajan por la red
  const pulseMat = new THREE.MeshBasicMaterial({ color: 0x5ef0d6, transparent: true, opacity: 0, blending: THREE.AdditiveBlending, depthWrite: false });
  const pulses = Array.from({ length: 7 }, (_, i) => {
    const m = new THREE.Mesh(new THREE.SphereGeometry(11, 12, 8), pulseMat);
    m.userData.edge = (i * 3) % edges.length;
    m.userData.t = Math.random();
    m.userData.speed = 0.25 + Math.random() * 0.25;
    net.add(m);
    return m;
  });

  /* ---------- Favicon: placa con degradado azul → teal y "Se" ---------- */
  const favicon = new THREE.Group();
  favicon.position.set(W / 2, H / 2, 0);
  logoSvg.add(favicon);
  const TILE = 2.3 / S, TILE_D = 0.5 / S;
  const tileGeo = new THREE.ExtrudeGeometry(roundedRect(TILE, TILE, TILE * 0.22), {
    depth: TILE_D, curveSegments: 16, bevelEnabled: true, bevelThickness: 0.08 / S, bevelSize: 0.06 / S, bevelSegments: 4,
  });
  tileGeo.translate(0, 0, -TILE_D / 2);
  paintGradient(tileGeo, new THREE.Color('#136cae'), new THREE.Color('#0f9a84'));
  const tile = new THREE.Mesh(tileGeo, new THREE.MeshPhysicalMaterial({ vertexColors: true, roughness: 0.34, clearcoat: 0.7, clearcoatRoughness: 0.12, envMapIntensity: 0.55 }));
  favicon.add(tile);
  // "S" y "e" del favicon: clones de las letras del logo, más grandes, sobre la placa
  const K = (TILE * 0.5) / glyphS.userData.size.y;
  const mid = new THREE.Vector3().addVectors(glyphS.userData.final, glyphE.userData.final).multiplyScalar(0.5);
  const faviconLetters = [glyphS, glyphE].map((g) => {
    const c = new THREE.Mesh(g.geometry, glossWhite);
    const rel = new THREE.Vector3().subVectors(g.userData.final, mid);
    // en el favicon la "S" y la "e" van juntas: se acerca su separación horizontal
    rel.x = (g === glyphS ? -1 : 1) * (glyphS.userData.size.x + glyphE.userData.size.x) * 0.27;
    c.userData.onTile = new THREE.Vector3(rel.x * K, rel.y * K, TILE_D / 2 + 0.08 / S + LETTER_DEPTH * K / 2);
    c.userData.target = g;
    c.position.copy(c.userData.onTile);
    c.scale.setScalar(K);
    favicon.add(c);
    return c;
  });

  /* ---------- Logos 3D de las herramientas (con reborde blanco) ---------- */
  const tools = ['probatus', 'inprogest', 'scrapp'];
  const pieces = [...stage.querySelectorAll('.core-diagram__piece')];
  // Los tres se igualan por la altura de su palabra principal (MAIN_H) y se centran en ella,
  // no en su caja completa: SCRAPP y PROBATUS llevan una segunda línea debajo e INPROGEST no.
  // ART_W limita el ancho (INPROGEST es muy alargado).
  const MAIN_H = 0.42;
  const ART_W = { probatus: 3.1, inprogest: 3.7, scrapp: 3.1 };
  const badges = toolTxts.map((txt, i) => {
    const group = new THREE.Group();
    const data = loader.parse(txt);
    const vb = data.xml.getAttribute('viewBox').split(/\s+/).map(Number);
    const sw = vb[2], sh = vb[3];
    const [m0, m1] = (data.xml.getAttribute('data-main') || `0 ${sh}`).split(' ').map(Number);
    const [ix0, iy0, ix1, iy1] = (data.xml.getAttribute('data-ink') || `0 0 ${sw} ${sh}`).split(' ').map(Number);
    const ls = Math.min(ART_W[tools[i]] / (ix1 - ix0), MAIN_H / (m1 - m0));
    const mid = (m0 + m1) / 2;
    const art = new THREE.Group();
    art.scale.set(ls, -ls, ls);
    art.position.set(-(ix0 + ix1) / 2 * ls, mid * ls, 0);
    const mats = [];
    for (const p of data.paths) {
      // Cara frontal con el tono original exacto (sin luz ni mapeo de tono que lo aclare);
      // los laterales, el mismo color oscurecido y con luz, para que se lea el volumen
      const color = new THREE.Color(p.userData.style.fill || '#000');
      const face = new THREE.MeshBasicMaterial({ color, toneMapped: false, transparent: true, opacity: 1 });
      const side = new THREE.MeshStandardMaterial({ color: color.clone().multiplyScalar(0.45), roughness: 0.55, metalness: 0.1, transparent: true, opacity: 1 });
      mats.push(face, side);
      const outline = p.userData.node.id === 'contorno';
      const geo = new THREE.ExtrudeGeometry(SVGLoader.createShapes(p), {
        depth: (outline ? 0.05 : 0.1) / ls, curveSegments: 8,
        bevelEnabled: true, bevelThickness: 0.01 / ls, bevelSize: 0.004 / ls, bevelSegments: 2,
      });
      art.add(new THREE.Mesh(geo, [face, side]));
    }
    group.add(art);
    group.userData = {
      id: tools[i], mats, hover: 0, hoverT: 0,
      w: (ix1 - ix0) * ls, h: (iy1 - iy0) * ls,
      above: (mid - iy0) * ls, below: (iy1 - mid) * ls,   // tinta por encima y por debajo del centro
    };
    group.scale.setScalar(0.0001);
    scene.add(group);
    return group;
  });

  // Interacción: el logo bajo el ratón se adelanta
  pieces.forEach((el, i) => {
    el.addEventListener('pointerenter', () => { badges[i].userData.hoverT = 1; });
    el.addEventListener('pointerleave', () => { badges[i].userData.hoverT = 0; });
    el.addEventListener('focus', () => { badges[i].userData.hoverT = 1; });
    el.addEventListener('blur', () => { badges[i].userData.hoverT = 0; });
  });

  /* ---------- Composición según el formato de pantalla ---------- */
  let layout = {};
  function resize() {
    const w = stage.querySelector('.stage').clientWidth;
    const h = stage.querySelector('.stage').clientHeight;
    renderer.setSize(w, h, false);
    camera.aspect = w / h;
    const portrait = w / h < 0.9;
    const tanH = Math.tan(THREE.MathUtils.degToRad(camera.fov / 2));
    const fitW = portrait ? 11.2 : 13.5;
    const fitH = portrait ? 15.5 : 9.6;
    const dist = Math.max(fitW / (2 * tanH * camera.aspect), fitH / (2 * tanH));
    camera.position.set(0, 0, dist);
    camera.updateProjectionMatrix();
    const viewH = 2 * tanH * dist;
    const pxToWorld = viewH / h;
    layout = portrait
      ? {
          portrait, viewH, pxToWorld,
          logoY0: viewH * 0.2, logoY1: viewH * 0.36, logoS1: 0.62,
          badge: [[0, viewH * 0.035], [0, -viewH * 0.15], [0, -viewH * 0.335]], badgeScale: 1.45,
        }
      : {
          portrait, viewH, pxToWorld, badgeScale: 1,
          logoY0: viewH * 0.12, logoY1: viewH * 0.3, logoS1: 0.48,
          badge: [[-4.0, -viewH * 0.23], [0, -viewH * 0.23], [4.0, -viewH * 0.23]],
          badgeY1: -viewH * 0.355,
        };
  }
  resize();
  window.addEventListener('resize', resize);

  stage.classList.add('is-3d');
  window.dispatchEvent(new Event('resize'));   // site.js activa el escenario fijo

  /* ---------- Animación ---------- */
  const pointer = { x: 0, y: 0, tx: 0, ty: 0 };
  window.addEventListener('pointermove', (e) => {
    pointer.tx = (e.clientX / window.innerWidth) * 2 - 1;
    pointer.ty = (e.clientY / window.innerHeight) * 2 - 1;
  }, { passive: true });

  let visible = true;
  new IntersectionObserver(([en]) => { visible = en.isIntersecting; }).observe(stage);

  const clock = new THREE.Clock();
  const t0 = performance.now() + 250;
  const proj = new THREE.Vector3();
  const tmp = new THREE.Vector3();

  function toScreen(v) {
    proj.copy(v).project(camera);
    const rect = canvas.getBoundingClientRect();
    return { x: (proj.x * 0.5 + 0.5) * rect.width, y: (-proj.y * 0.5 + 0.5) * rect.height };
  }

  function frame() {
    requestAnimationFrame(frame);
    if (!visible) return;
    const dt = Math.min(clock.getDelta(), 0.05);
    const t = Math.max(0, (performance.now() - t0) / 1000);
    const p = parseFloat(stage.style.getPropertyValue('--p')) || 0;
    pointer.x = lerp(pointer.x, pointer.tx, 0.05);
    pointer.y = lerp(pointer.y, pointer.ty, 0.05);

    // 1 · El favicon aparece (0 → 1 s)
    const a1 = ramp(t, 0, 1.0);
    favicon.scale.setScalar(Math.max(0.0001, easeOutBack(a1)));
    favicon.rotation.y = (1 - easeOutCubic(a1)) * -1.4 + Math.sin(t * 2.2) * 0.06 * (1 - ramp(t, 1.4, 1.8));
    favicon.rotation.x = (1 - easeOutCubic(a1)) * 0.5;

    // 2 · La "S" y la "e" saltan a su sitio en el logo y la placa se pliega (1.5 → 2.6 s)
    const a2 = easeInOutCubic(ramp(t, 1.5, 2.5));
    tile.scale.setScalar(Math.max(0.0001, 1 - easeInOutCubic(ramp(t, 1.5, 2.2))));
    tile.rotation.x = easeInOutCubic(ramp(t, 1.5, 2.2)) * 1.6;
    faviconLetters.forEach((c) => {
      const g = c.userData.target;
      tmp.copy(g.userData.final).sub(favicon.position);
      c.position.lerpVectors(c.userData.onTile, tmp, a2);
      c.position.z += Math.sin(a2 * Math.PI) * 0.9 / S;
      c.scale.setScalar(lerp(K, 1, a2));
      c.visible = t < 2.5;
    });
    glyphS.visible = glyphE.visible = t >= 2.5;

    // 3 · El resto de letras se despliega desde la S y la e (2.0 → 3.3 s)
    letters.forEach((m) => {
      if (m === glyphS || m === glyphE) { m.position.copy(m.userData.final); return; }
      const i = m.userData.index;
      const from = i < 5 ? glyphS.userData.final : glyphE.userData.final;
      const k = easeOutCubic(ramp(t, 2.0 + Math.abs(i - (i < 5 ? 0 : 5)) * 0.07, 2.75 + Math.abs(i - (i < 5 ? 0 : 5)) * 0.07));
      m.position.lerpVectors(from, m.userData.final, k);
      m.position.z = (1 - k) * 0.8 / S;
      m.rotation.y = (1 - k) * (i < 5 ? -1.2 : 1.2);
      m.scale.setScalar(Math.max(0.0001, k));
    });

    // 4 · La red se dibuja y empieza a latir (2.4 → 4 s)
    nodes.forEach((n) => {
      const k = easeOutBack(ramp(t, 2.4 + n.userData.delay * 0.7, 2.9 + n.userData.delay * 0.7));
      n.scale.setScalar(Math.max(0.0001, k));
    });
    edges.forEach((e) => {
      const k = easeInOutCubic(ramp(t, 2.6 + e.mesh.userData.delay * 0.7, 3.5 + e.mesh.userData.delay * 0.7));
      e.mesh.scale.set(1, Math.max(0.0001, e.len * k), 1);
    });
    const netOn = ramp(t, 3.6, 4.2);
    pulseMat.opacity = netOn * 0.95;
    pulses.forEach((m) => {
      m.userData.t += dt * m.userData.speed;
      if (m.userData.t > 1) { m.userData.t = 0; m.userData.edge = Math.floor(Math.random() * edges.length); }
      const e = edges[m.userData.edge];
      m.position.lerpVectors(e.pa, e.pb, m.userData.t);
    });
    if (tagline) {
      const k = easeOutCubic(ramp(t, 3.0, 3.8));
      tagline.material.opacity = k;
      tagline.position.y = tagline.userData.final.y + (1 - k) * 40;
    }

    // Logo en el mundo: flota y, con el scroll, sube, encoge y gira mostrando su volumen
    const ps = easeInOutCubic(clamp(p / 0.6));
    logo.position.y = lerp(layout.logoY0, layout.logoY1, ps) + Math.sin(t * 0.8) * 0.06;
    logo.scale.setScalar(lerp(1, layout.logoS1, ps));
    logo.rotation.y = Math.sin(p * Math.PI) * 0.45 + pointer.x * 0.12;
    logo.rotation.x = -p * 0.18 + pointer.y * 0.06;
    net.position.z = -Math.sin(p * Math.PI) * 0.6 / S;

    // 5 · El core: los logos suben y giran (3.2 → 4.6 s)
    const coreFade = layout.portrait ? 1 - ramp(p, 0.15, 0.4) : 1;
    badges.forEach((b, i) => {
      const k = easeOutBack(ramp(t, 3.3 + i * 0.16, 4.2 + i * 0.16));
      const [bx, by] = layout.badge[i];
      const ud = b.userData;
      ud.hover = lerp(ud.hover, ud.hoverT, 0.12);
      const dropY = layout.portrait ? by + (1 - coreFade) * -2 : lerp(by, layout.badgeY1, ps);
      b.position.set(bx * lerp(1, 1.06, ps), lerp(by - 2.2, dropY, clamp(k)), ud.hover * 0.6);
      b.scale.setScalar(Math.max(0.0001, k * layout.badgeScale * (layout.portrait ? 1 : lerp(1, 0.86, ps)) * (1 + ud.hover * 0.06)));
      b.rotation.x = (1 - clamp(k)) * 1.3 + Math.sin(t * 1.1 + i) * 0.04 - ud.hover * 0.1;
      b.rotation.y = Math.sin(p * Math.PI * 2 + i * 0.6) * 0.35 * clamp(p * 4) + pointer.x * 0.12 + (1 - clamp(k)) * (i - 1) * 0.6;
      ud.mats.forEach((m) => { m.opacity = coreFade; });
      b.visible = coreFade > 0.01;
    });

    // Cámara: leve paralaje con el ratón
    camera.position.x = pointer.x * 0.35;
    camera.position.y = -pointer.y * 0.25;
    camera.lookAt(0, 0, 0);
    renderer.render(scene, camera);

    // Etiquetas HTML (enlaces del core) pegadas a sus logos, todas a la misma altura
    const labelsOn = ramp(t, 3.9, 4.6) * coreFade;
    const above = Math.max(...badges.map((b) => b.userData.above));
    const below = Math.max(...badges.map((b) => b.userData.below));
    badges.forEach((b, i) => {
      const el = pieces[i];
      if (!el) return;
      const sc = b.scale.x;
      const hw = (b.userData.w / 2) * sc;
      const tl = toScreen(tmp.set(b.position.x - hw, b.position.y + above * sc, b.position.z));
      const br = toScreen(tmp.set(b.position.x + hw, b.position.y - below * sc, b.position.z));
      el.style.transform = `translate(${tl.x.toFixed(1)}px, ${tl.y.toFixed(1)}px)`;
      el.style.width = `${Math.max(0, br.x - tl.x).toFixed(1)}px`;
      el.style.height = `${Math.max(0, br.y - tl.y).toFixed(1)}px`;
      el.style.setProperty('--on', labelsOn.toFixed(3));
    });
    stage.classList.toggle('core-on', labelsOn > 0.5);
    stage.classList.toggle('intro-done', t > 4.2);
  }
  requestAnimationFrame(frame);
}

/* ---------- Utilidades de geometría ---------- */
function roundedRect(w, h, r) {
  const s = new THREE.Shape();
  const x = -w / 2, y = -h / 2;
  s.moveTo(x + r, y);
  s.lineTo(x + w - r, y);
  s.quadraticCurveTo(x + w, y, x + w, y + r);
  s.lineTo(x + w, y + h - r);
  s.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
  s.lineTo(x + r, y + h);
  s.quadraticCurveTo(x, y + h, x, y + h - r);
  s.lineTo(x, y + r);
  s.quadraticCurveTo(x, y, x + r, y);
  return s;
}

/** Degradado diagonal (arriba-izquierda → abajo-derecha) como colores de vértice. */
function paintGradient(geo, from, to) {
  geo.computeBoundingBox();
  const { min, max } = geo.boundingBox;
  const pos = geo.attributes.position;
  const colors = new Float32Array(pos.count * 3);
  const c = new THREE.Color();
  for (let i = 0; i < pos.count; i++) {
    const u = (pos.getX(i) - min.x) / (max.x - min.x);
    const v = (pos.getY(i) - min.y) / (max.y - min.y);   // en el espacio del SVG la y crece hacia abajo
    c.copy(from).lerp(to, clamp((u + v) / 2));
    colors.set([c.r, c.g, c.b], i * 3);
  }
  geo.setAttribute('color', new THREE.BufferAttribute(colors, 3));
}
