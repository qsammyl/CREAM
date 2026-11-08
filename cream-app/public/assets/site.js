/* =========================================================
   CREDENCE — shared behaviour. Loaded on every generated page.
   Vanilla JS, no dependencies. Degrades gracefully: all links
   are real <a href> so navigation works with JS disabled.
   ========================================================= */
(function () {
  'use strict';

  /* ---------- Sticky header: solid on scroll (overlay pages only) ---------- */
  var hdr = document.getElementById('siteHeader');
  if (hdr && hdr.hasAttribute('data-overlay')) {
    var onScroll = function () {
      if (window.scrollY > 60) hdr.classList.add('solid');
      else hdr.classList.remove('solid');
    };
    document.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------- Desktop dropdowns / mega-menu ---------- */
  var dropWraps = Array.prototype.slice.call(document.querySelectorAll('.has-mega, .has-drop'));
  function closeAllDrops(except) {
    dropWraps.forEach(function (w) {
      if (w === except) return;
      var btn = w.querySelector('.navitem');
      var panel = w.querySelector('.mega, .drop');
      if (btn) btn.setAttribute('aria-expanded', 'false');
      if (panel) panel.classList.remove('open');
    });
  }
  dropWraps.forEach(function (w) {
    var btn = w.querySelector('.navitem');
    var panel = w.querySelector('.mega, .drop');
    if (!btn || !panel) return;
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var open = btn.getAttribute('aria-expanded') === 'true';
      closeAllDrops(w);
      btn.setAttribute('aria-expanded', String(!open));
      panel.classList.toggle('open', !open);
      if (!open) { var first = panel.querySelector('a'); if (first) first.focus(); }
    });
  });
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.has-mega, .has-drop')) closeAllDrops(null);
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeAllDrops(null);
      closeDrawer();
      closePopup();
    }
  });

  /* ---------- Mobile drawer ---------- */
  var toggle = document.querySelector('.nav-toggle');
  var drawer = document.getElementById('mobileDrawer');
  var scrim = document.getElementById('drawerScrim');
  var lastFocus = null;
  function openDrawer() {
    if (!drawer) return;
    lastFocus = document.activeElement;
    drawer.classList.add('open');
    if (scrim) scrim.classList.add('open');
    if (toggle) toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    var c = drawer.querySelector('.d-close'); if (c) c.focus();
  }
  function closeDrawer() {
    if (!drawer || !drawer.classList.contains('open')) return;
    drawer.classList.remove('open');
    if (scrim) scrim.classList.remove('open');
    if (toggle) { toggle.setAttribute('aria-expanded', 'false'); }
    document.body.style.overflow = '';
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  }
  if (toggle) toggle.addEventListener('click', function () {
    if (drawer && drawer.classList.contains('open')) closeDrawer(); else openDrawer();
  });
  if (scrim) scrim.addEventListener('click', closeDrawer);
  if (drawer) {
    var dClose = drawer.querySelector('.d-close');
    if (dClose) dClose.addEventListener('click', closeDrawer);
    drawer.querySelectorAll('a.d-link, .d-panel a, .d-cta, .d-util a').forEach(function (a) {
      a.addEventListener('click', closeDrawer);
    });
    drawer.querySelectorAll('.d-acc').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var panel = document.getElementById(btn.getAttribute('aria-controls'));
        var open = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!open));
        if (panel) panel.classList.toggle('open', !open);
      });
    });
  }
  window.addEventListener('resize', function () {
    if (window.innerWidth > 1080) closeDrawer();
  });

  /* ---------- Grid filters ---------- */
  document.querySelectorAll('[data-filterbar]').forEach(function (bar) {
    var scope = document.querySelector(bar.getAttribute('data-target') || '.cr-grid');
    if (!scope) return;
    var cards = Array.prototype.slice.call(scope.querySelectorAll('.cr-card'));
    var countEl = document.querySelector(bar.getAttribute('data-count') || '');
    var emptyEl = scope.parentNode.querySelector('.cr-empty');
    var activeType = 'all', activeArea = 'all';
    function apply() {
      var shown = 0;
      cards.forEach(function (c) {
        var tags = (c.getAttribute('data-tags') || '').split(' ');
        var area = c.getAttribute('data-area') || '';
        var isSold = c.getAttribute('data-sold') === '1';
        var areaOk = activeArea === 'all' || area === activeArea;
        var typeOk;
        if (activeType === 'recently-sold') {
          typeOk = isSold;                                   // only sold/let
        } else {
          typeOk = !isSold && (activeType === 'all' || tags.indexOf(activeType) !== -1);
        }
        var ok = typeOk && areaOk;
        c.style.display = ok ? '' : 'none';
        if (ok) shown++;
      });
      if (countEl) countEl.textContent = shown;
      if (emptyEl) emptyEl.style.display = shown ? 'none' : '';
    }
    bar.querySelectorAll('[data-f]').forEach(function (chip) {
      chip.addEventListener('click', function () {
        bar.querySelectorAll('[data-f]').forEach(function (x) { x.classList.remove('on'); x.setAttribute('aria-pressed', 'false'); });
        chip.classList.add('on'); chip.setAttribute('aria-pressed', 'true');
        activeType = chip.getAttribute('data-f');
        apply();
      });
    });
    var areaSel = bar.querySelector('[data-area-select]');
    if (areaSel) areaSel.addEventListener('change', function () { activeArea = this.value; apply(); });
    apply();
  });

  /* ---------- Preselect contact reason from ?reason= ---------- */
  (function () {
    var params = new URLSearchParams(window.location.search);
    var reason = params.get('reason');
    if (!reason) return;
    var map = {
      'development': 'Build with Credence', 'construction': 'Build with Credence',
      'sales': 'Buy a Property', 'buy': 'Buy a Property', 'sell': 'Sell a Property',
      'management': 'Property Management', 'interiors': 'Interior Decor',
      'land': 'Land & Due Diligence', 'diaspora': 'Diaspora Programme'
    };
    var want = map[reason.toLowerCase()] || reason;
    var chips = document.querySelectorAll('#topics .topic-chip');
    var hidden = document.getElementById('topicInput');
    chips.forEach(function (c) {
      if (c.textContent.trim().toLowerCase() === want.toLowerCase()) {
        chips.forEach(function (x) { x.classList.remove('on'); });
        c.classList.add('on');
        if (hidden) hidden.value = c.textContent.trim();
      }
    });
  })();

  /* ---------- Topic chips (contact-style forms) ---------- */
  document.querySelectorAll('#topics').forEach(function (topics) {
    var hidden = document.getElementById('topicInput');
    topics.querySelectorAll('.topic-chip').forEach(function (c) {
      c.addEventListener('click', function () {
        topics.querySelectorAll('.topic-chip').forEach(function (x) { x.classList.remove('on'); });
        c.classList.add('on');
        if (hidden) hidden.value = c.textContent.trim();
      });
    });
  });

  /* ---------- Web3Forms AJAX submit (progressive enhancement) ---------- */
  document.querySelectorAll('form[data-web3]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var btn = form.querySelector('[type="submit"]');
      var msg = form.querySelector('.form-msg');
      if (!msg) return; // fall back to native POST
      e.preventDefault();
      if (btn) { btn.disabled = true; btn.dataset.label = btn.textContent; btn.textContent = 'Sending…'; }
      fetch('https://api.web3forms.com/submit', { method: 'POST', body: new FormData(form) })
        .then(function (r) { return r.json(); })
        .then(function (json) {
          if (json.success) {
            form.reset();
            msg.style.cssText = 'display:block;margin-top:12px;padding:14px 16px;border-radius:10px;font-size:13.5px;line-height:1.5;background:#F0FAF4;border:1px solid #B6DFC7;color:#1A5C38';
            msg.innerHTML = '<strong>Message sent.</strong> A member of our team will be in touch within one business day.';
            if (btn) btn.style.display = 'none';
          } else { throw new Error(json.message || 'Failed'); }
        })
        .catch(function () {
          msg.style.cssText = 'display:block;margin-top:12px;padding:14px 16px;border-radius:10px;font-size:13.5px;line-height:1.5;background:#FEF2F2;border:1px solid #FECACA;color:#991B1B';
          msg.innerHTML = 'Something went wrong — please try again or reach us on WhatsApp or at info@credencerealestateandassetmgt.com.';
          if (btn) { btn.disabled = false; btn.textContent = btn.dataset.label || 'Send Message'; }
        });
    });
  });

  /* ---------- New-projects popup ---------- */
  var popup = document.getElementById('crPopup');
  var popupPrevFocus = null;
  function closePopup() {
    if (!popup || !popup.classList.contains('open')) return;
    popup.classList.remove('open');
    document.body.style.overflow = '';
    document.removeEventListener('keydown', trapPopup, true);
    if (popupPrevFocus && popupPrevFocus.focus) popupPrevFocus.focus();
  }
  function trapPopup(e) {
    if (e.key !== 'Tab') return;
    var f = popup.querySelectorAll('a[href],button:not([disabled])');
    if (!f.length) return;
    var first = f[0], last = f[f.length - 1];
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  }
  function showPopup() {
    if (!popup) return;
    popupPrevFocus = document.activeElement;
    popup.classList.add('open');
    document.body.style.overflow = 'hidden';
    var c = popup.querySelector('.cr-close'); if (c) c.focus();
    document.addEventListener('keydown', trapPopup, true);
    try { localStorage.setItem('cr_popup_seen', String(Date.now())); } catch (err) {}
  }
  if (popup) {
    var closeBtn = popup.querySelector('.cr-close');
    if (closeBtn) closeBtn.addEventListener('click', closePopup);
    popup.addEventListener('click', function (e) { if (e.target === popup) closePopup(); });

    var seen = false;
    try {
      var ts = parseInt(localStorage.getItem('cr_popup_seen') || '0', 10);
      if (ts && (Date.now() - ts) < 14 * 24 * 60 * 60 * 1000) seen = true;
    } catch (err) {}

    if (!seen) {
      var fired = false, start = Date.now();
      var trigger = function () {
        if (fired) return;
        if (Date.now() - start < 3000) return;            // never in first 3s
        var ae = document.activeElement;
        if (ae && /^(INPUT|TEXTAREA|SELECT)$/.test(ae.tagName)) return; // not while a form field is focused
        fired = true;
        window.removeEventListener('scroll', onScrollPop);
        showPopup();
      };
      setTimeout(trigger, 8000);                           // after 8s
      var onScrollPop = function () {
        var h = document.documentElement;
        var pct = (h.scrollTop) / (h.scrollHeight - h.clientHeight || 1);
        if (pct >= 0.5) trigger();                         // or 50% scroll
      };
      window.addEventListener('scroll', onScrollPop, { passive: true });
    }
  }

  // expose for inline callers if needed
  window.CRclosePopup = closePopup;

  /* ---------- Home hero slideshow + walk-through reels ---------- */
  (function () {
    var slides = document.querySelectorAll('#heroBg .slide');
    var dots = document.querySelectorAll('#heroDots .d');
    var cur = 0;
    if (slides.length > 1) {
      setInterval(function () {
        cur = (cur + 1) % slides.length;
        slides.forEach(function (s, i) { s.classList.toggle('is-active', i === cur); });
        dots.forEach(function (d, i) { d.classList.toggle('on', i === cur); });
      }, 6000);
    }
    document.querySelectorAll('.reel').forEach(function (r) {
      var v = r.querySelector('video');
      if (!v) return;
      r.addEventListener('mouseenter', function () { v.play().catch(function () {}); });
      r.addEventListener('mouseleave', function () { v.pause(); v.currentTime = 0; });
    });
  })();
})();
