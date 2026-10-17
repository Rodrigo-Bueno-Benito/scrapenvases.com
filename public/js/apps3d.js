/**
 * Logos 3D de los apartados de PROBATUS, INPROGEST y SCRAPP: el mismo logo extruido de la
 * portada (cara con el tono original exacto, laterales del mismo color oscurecido). Gira al
 * llegar su apartado, flota y se inclina hacia el ratón.
 *
 * Mejora progresiva: sin WebGL o con "reducir movimiento" se queda el logo plano del HTML,
 * que sigue siendo el enlace y el texto alternativo también con el lienzo 3D delante.
 */
import * as THREE from 'three';
import { SVGLoader } from 'three/addons/loaders/SVGLoader.js';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';

const holders = [...document.querySelectorAll('[data-logo3d]')];
const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
const lerp = (a, b, t) => a + (b - a) * t;
const easeOutBack = (t) => { const c1 = 1.4, c3 = c1 + 1; return 1 + c3 * Math.pow(t - 1, 3) + c1 * Math.pow(t - 1, 2); };
const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

// Igual que en la portada: los tres se igualan por la altura de su palabra principal
// y ART_W limita el ancho (INPROGEST es muy alargado). VIEW_W es el ancho visible del lienzo.
const MAIN_H = 0.42;
const ART_W = { probatus: 3.1, inprogest: 3.7, scrapp: 3.1 };
const VIEW_W = 4.1;
const GLOW = { probatus: 0x45d69e, inprogest: 0x3cc4f2, scrapp: 0xffa25a };

function webglAvailable() {
  try {
    const c = document.createElement('canvas');
    return !!(window.WebGL2RenderingContext && c.getContext('webgl2')) || !!c.getContext('webgl');
  } catch (e) {
    return false;
  }
}

if (holders.length && !prefersReduced && webglAvailable()) {
  holders.forEach((holder) => {
    mount(holder).catch((err) => {
      console.warn('[apps3d] se mantiene el logo plano:', err);
      holder.classList.remove('is-3d');
    });
  });
}

async function mount(holder) {
  const id = holder.dataset.logo3d;
  const section = holder.closest('[data-app]') || holder;
  const svg = await fetch('/media/3d/' + id + '.svg').then((r) => {
    if (!r.ok) throw new Error(r.status);
    return r.text();
  });

  const canvas = document.createElement('canvas');
  canvas.setAttribute('aria-hidden', 'true');
  holder.prepend(canvas);
  holder.classList.add('is-3d');

  const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  renderer.toneMapping = THREE.NeutralToneMapping;   // respeta los colores de marca
  renderer.outputColorSpace = THREE.SRGBColorSpace;

  const scene = new THREE.Scene();
  const pmrem = new THREE.PMREMGenerator(renderer);
  scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
  const key = new THREE.DirectionalLight(0xffffff, 1.6);
  key.position.set(-5, 6, 10);
  const rim = new THREE.DirectionalLight(GLOW[id] || 0xffffff, 2.4);
  rim.position.set(6, 2, -6);
  scene.add(key, rim, new THREE.AmbientLight(0xffffff, 0.2));
  const camera = new THREE.PerspectiveCamera(22, 1, 0.1, 100);

  /* ---------- Logo extruido (espacio del SVG, y hacia abajo) ---------- */
  const data = new SVGLoader().parse(svg);
  const vb = data.xml.getAttribute('viewBox').split(/\s+/).map(Number);
  const [m0, m1] = (data.xml.getAttribute('data-main') || `0 ${vb[3]}`).split(' ').map(Number);
  const [ix0, iy0, ix1, iy1] = (data.xml.getAttribute('data-ink') || `0 0 ${vb[2]} ${vb[3]}`).split(' ').map(Number);
  const ls = Math.min(ART_W[id] / (ix1 - ix0), MAIN_H / (m1 - m0));
  const mid = (m0 + m1) / 2;

  const art = new THREE.Group();
  art.scale.set(ls, -ls, ls);
  art.position.set(-(ix0 + ix1) / 2 * ls, mid * ls, 0);
  const mats = [];
  for (const p of data.paths) {
    const color = new THREE.Color(p.userData.style.fill || '#000');
    const face = new THREE.MeshBasicMaterial({ color, toneMapped: false, transparent: true, opacity: 0 });
    const side = new THREE.MeshStandardMaterial({ color: color.clone().multiplyScalar(0.45), roughness: 0.55, metalness: 0.1, transparent: true, opacity: 0 });
    mats.push(face, side);
    const geo = new THREE.ExtrudeGeometry(SVGLoader.createShapes(p), {
      depth: 0.1 / ls, curveSegments: 8,
      bevelEnabled: true, bevelThickness: 0.01 / ls, bevelSize: 0.004 / ls, bevelSegments: 2,
    });
    art.add(new THREE.Mesh(geo, [face, side]));
  }
  const pivot = new THREE.Group();   // gira sobre el centro de la palabra principal
  pivot.add(art);
  scene.add(pivot);
  const inkW = (ix1 - ix0) * ls;

  /* ---------- Tamaño: el logo arranca en el borde izquierdo, como el texto ---------- */
  let aspect = 1;
  function resize() {
    const w = holder.clientWidth, h = holder.clientHeight;
    if (!w || !h) return;
    renderer.setSize(w, h, false);
    aspect = w / h;
    camera.aspect = aspect;
    const viewH = VIEW_W / aspect;
    camera.position.set(0, 0, viewH / 2 / Math.tan(THREE.MathUtils.degToRad(camera.fov / 2)));
    camera.updateProjectionMatrix();
    pivot.position.x = -VIEW_W / 2 + inkW / 2 + 0.06;
    draw();
  }
  new ResizeObserver(resize).observe(holder);

  /* ---------- Entrada, flotación y ratón ---------- */
  let visible = false;
  let enterAt = 0;
  const pointer = { x: 0, y: 0, tx: 0, ty: 0 };
  section.addEventListener('pointermove', (ev) => {
    const r = section.getBoundingClientRect();
    pointer.tx = clamp((ev.clientX - r.left) / r.width * 2 - 1, -1, 1);
    pointer.ty = clamp((ev.clientY - r.top) / r.height * 2 - 1, -1, 1);
  });
  section.addEventListener('pointerleave', () => { pointer.tx = 0; pointer.ty = 0; });
  holder.addEventListener('pointerenter', () => { pointer.hover = 1; });
  holder.addEventListener('pointerleave', () => { pointer.hover = 0; });

  let hoverT = 0;
  function draw(now = performance.now()) {
    const t = now / 1000;
    const e = enterAt ? clamp((now - enterAt) / 1500) : 0;
    pointer.x = lerp(pointer.x, pointer.tx, 0.06);
    pointer.y = lerp(pointer.y, pointer.ty, 0.06);
    hoverT = lerp(hoverT, pointer.hover || 0, 0.12);

    const op = easeOutCubic(clamp(e * 1.6));
    mats.forEach((m) => { m.opacity = op; });
    pivot.rotation.y = (1 - easeOutBack(e)) * -1.5 + Math.sin(t * 0.55) * 0.12 + pointer.x * 0.32;
    pivot.rotation.x = Math.sin(t * 0.8) * 0.05 + pointer.y * 0.16 - hoverT * 0.08;
    pivot.rotation.z = Math.sin(t * 0.45) * 0.015;
    pivot.position.y = Math.sin(t * 1.1) * 0.04;
    pivot.position.z = hoverT * 0.25;
    pivot.scale.setScalar(lerp(0.7, 1, easeOutCubic(e)));
    renderer.render(scene, camera);
  }

  // Solo se dibuja mientras el apartado se ve y está activo (clase is-in, que pone site.js al
  // llegar a él o journey.js en cada parada del viaje); gira cada vez que se activa.
  let active = section.classList.contains('is-in');
  let running = false;
  if (active) enterAt = performance.now();
  function loop(now) {
    if (!visible || !active) { running = false; return; }
    draw(now);
    requestAnimationFrame(loop);
  }
  const kick = () => {
    if (visible && active && !running) { running = true; requestAnimationFrame(loop); }
  };
  new MutationObserver(() => {
    const now = section.classList.contains('is-in');
    if (now && !active) enterAt = performance.now();
    active = now;
    kick();
  }).observe(section, { attributes: true, attributeFilter: ['class'] });
  new IntersectionObserver((entries) => {
    entries.forEach((en) => { visible = en.isIntersecting; kick(); });
  }).observe(section);
  resize();
}
