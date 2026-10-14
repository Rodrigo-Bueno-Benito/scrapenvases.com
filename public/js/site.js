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

  // Flujo 01 → 02 → 03 de El CORE: aparece paso a paso al llegar a él
  const flow = document.querySelector('[data-flow]');
  if (flow) {
    if (!('IntersectionObserver' in window)) {
      flow.classList.add('is-visible');
    } else {
      const io = new IntersectionObserver((entries) => {
        if (entries.some((en) => en.isIntersecting)) {
          flow.classList.add('is-visible');
          io.disconnect();
        }
      }, { threshold: 0.25 });
      io.observe(flow);
    }
  }
});
