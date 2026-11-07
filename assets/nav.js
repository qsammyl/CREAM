(function () {
  var toggle = document.querySelector('.nav-toggle');
  var panel = document.getElementById('mobileNav');
  if (!toggle || !panel) return;

  toggle.addEventListener('click', function () {
    var open = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!open));
    panel.classList.toggle('open', !open);
    document.body.style.overflow = !open ? 'hidden' : '';
  });

  panel.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      toggle.setAttribute('aria-expanded', 'false');
      panel.classList.remove('open');
      document.body.style.overflow = '';
    });
  });

  window.addEventListener('resize', function () {
    if (window.innerWidth > 1080) {
      toggle.setAttribute('aria-expanded', 'false');
      panel.classList.remove('open');
      document.body.style.overflow = '';
    }
  });
})();
