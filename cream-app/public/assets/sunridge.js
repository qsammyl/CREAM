/* SUNRIDGE project landing page — interactions (CSP-safe, no inline JS).
   Scope: only runs when the SUNRIDGE page markup is present. */
(function () {
  'use strict';
  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function () {
    var root = document.querySelector('[data-sunridge]');
    if (!root) return;

    /* ---------- Sticky offset: pin the section nav just below the fixed site header ---------- */
    var siteHeader = document.getElementById('siteHeader');
    function setHeaderOffset() {
      if (!siteHeader) return;
      root.style.setProperty('--sr-header', Math.round(siteHeader.getBoundingClientRect().height) + 'px');
    }
    setHeaderOffset();
    window.addEventListener('resize', setHeaderOffset);
    window.addEventListener('load', setHeaderOffset);

    /* ---------- Unit-type tabs ---------- */
    var tabs = Array.prototype.slice.call(root.querySelectorAll('[data-unit-tab]'));
    var panels = Array.prototype.slice.call(root.querySelectorAll('[data-unit-panel]'));
    function selectUnit(key) {
      tabs.forEach(function (t) {
        var on = t.getAttribute('data-unit-tab') === key;
        t.classList.toggle('active', on);
        t.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      panels.forEach(function (p) {
        p.hidden = p.getAttribute('data-unit-panel') !== key;
      });
    }
    tabs.forEach(function (t) {
      t.addEventListener('click', function () { selectUnit(t.getAttribute('data-unit-tab')); });
      t.addEventListener('keydown', function (e) {
        if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
        e.preventDefault();
        var i = tabs.indexOf(t);
        var n = e.key === 'ArrowRight' ? (i + 1) % tabs.length : (i - 1 + tabs.length) % tabs.length;
        tabs[n].focus();
        selectUnit(tabs[n].getAttribute('data-unit-tab'));
      });
    });

    /* ---------- Lightbox gallery ---------- */
    var triggers = Array.prototype.slice.call(root.querySelectorAll('[data-lightbox]'));
    var lb = document.getElementById('srLightbox');
    if (lb && triggers.length) {
      var lbImg = lb.querySelector('.srlb-img');
      var lbCap = lb.querySelector('.srlb-cap');
      var lbCount = lb.querySelector('.srlb-count');
      var current = -1;
      function show(i) {
        current = (i + triggers.length) % triggers.length;
        var el = triggers[current];
        var full = el.getAttribute('data-full') || el.getAttribute('src');
        lbImg.setAttribute('src', full);
        lbImg.setAttribute('alt', el.getAttribute('alt') || '');
        if (lbCap) lbCap.textContent = el.getAttribute('data-caption') || el.getAttribute('alt') || '';
        if (lbCount) lbCount.textContent = (current + 1) + ' / ' + triggers.length;
      }
      function open(i) { show(i); lb.classList.add('open'); lb.setAttribute('aria-hidden', 'false'); document.body.style.overflow = 'hidden'; }
      function close() { lb.classList.remove('open'); lb.setAttribute('aria-hidden', 'true'); document.body.style.overflow = ''; }
      triggers.forEach(function (el, i) {
        el.addEventListener('click', function () { open(i); });
        el.style.cursor = 'zoom-in';
      });
      lb.addEventListener('click', function (e) {
        var act = e.target.getAttribute && e.target.getAttribute('data-lb');
        if (act === 'close' || e.target === lb) close();
        else if (act === 'next') show(current + 1);
        else if (act === 'prev') show(current - 1);
      });
      document.addEventListener('keydown', function (e) {
        if (!lb.classList.contains('open')) return;
        if (e.key === 'Escape') close();
        else if (e.key === 'ArrowRight') show(current + 1);
        else if (e.key === 'ArrowLeft') show(current - 1);
      });
    }

    /* ---------- FAQ accordion ---------- */
    Array.prototype.slice.call(root.querySelectorAll('[data-faq-q]')).forEach(function (q) {
      q.addEventListener('click', function () {
        var item = q.closest('.sr-faq-item');
        var open = item.classList.toggle('open');
        q.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    });

    /* ---------- Stat counters ---------- */
    function animate(el) {
      var target = parseFloat(el.getAttribute('data-count'));
      var dec = (el.getAttribute('data-count').split('.')[1] || '').length;
      var dur = 1200, start = null;
      function step(ts) {
        if (!start) start = ts;
        var p = Math.min((ts - start) / dur, 1);
        var eased = 1 - Math.pow(1 - p, 3);
        el.textContent = (target * eased).toFixed(dec);
        if (p < 1) requestAnimationFrame(step);
        else el.textContent = target.toFixed(dec);
      }
      requestAnimationFrame(step);
    }

    /* ---------- Scroll reveal + counters via IntersectionObserver ---------- */
    var reveals = Array.prototype.slice.call(root.querySelectorAll('.reveal'));
    var counters = Array.prototype.slice.call(root.querySelectorAll('[data-count]'));
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (!en.isIntersecting) return;
          en.target.classList.add('in');
          if (en.target.hasAttribute('data-count')) animate(en.target);
          io.unobserve(en.target);
        });
      }, { threshold: 0.18, rootMargin: '0px 0px -8% 0px' });
      reveals.forEach(function (el) { io.observe(el); });
      counters.forEach(function (el) { io.observe(el); });
    } else {
      reveals.forEach(function (el) { el.classList.add('in'); });
      counters.forEach(function (el) { el.textContent = parseFloat(el.getAttribute('data-count')).toString(); });
    }

    /* ---------- Sticky section nav + scrollspy ---------- */
    var subnav = root.querySelector('[data-subnav]');
    if (subnav) {
      var links = Array.prototype.slice.call(subnav.querySelectorAll('a[href^="#"]'));
      var map = links.map(function (a) {
        return { link: a, section: document.querySelector(a.getAttribute('href')) };
      }).filter(function (m) { return m.section; });
      var hero = root.querySelector('[data-hero]');

      // Scroll-based scrollspy: robust for tall sections (threshold-based
      // observers never fire when a section is taller than the viewport).
      var ticking = false;
      function updateSpy() {
        ticking = false;
        var headerH = parseInt(getComputedStyle(root).getPropertyValue('--sr-header'), 10) || 108;
        var line = headerH + 72; // just below the sticky section bar
        var currentLink = map.length ? map[0].link : null;
        for (var i = 0; i < map.length; i++) {
          if (map[i].section.getBoundingClientRect().top <= line) {
            currentLink = map[i].link;
          }
        }
        for (var j = 0; j < map.length; j++) {
          map[j].link.classList.toggle('active', map[j].link === currentLink);
        }
        if (hero) {
          subnav.classList.toggle('stuck', hero.getBoundingClientRect().bottom <= headerH + 1);
        }
      }
      function onSpyScroll() {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(updateSpy);
      }
      window.addEventListener('scroll', onSpyScroll, { passive: true });
      window.addEventListener('resize', onSpyScroll, { passive: true });
      updateSpy();
    }
  });
})();
