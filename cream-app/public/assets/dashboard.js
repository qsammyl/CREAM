/*
 * Staff dashboard behaviours.
 *
 * The staff Content-Security-Policy is strict (script-src 'self'; no
 * 'unsafe-inline' / 'unsafe-hashes'), so inline on* handlers are blocked.
 * These two delegated listeners replace them:
 *
 *   data-confirm="message"  on a <form>, <button> or link  -> confirm() guard
 *   data-autosubmit         on a control (select/checkbox)  -> submit its form on change
 */
(function () {
    'use strict';

    // Confirmation guard on form submission (covers Enter-key submits too).
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        var msg = form.getAttribute('data-confirm');
        if (msg && !window.confirm(msg)) {
            e.preventDefault();
        }
    }, true);

    // Confirmation guard on buttons/links that carry their own data-confirm
    // (e.g. a submit button bound to another form via the `form` attribute).
    document.addEventListener('click', function (e) {
        var el = e.target.closest ? e.target.closest('[data-confirm]') : null;
        if (!el || el.tagName === 'FORM') return;
        var msg = el.getAttribute('data-confirm');
        if (msg && !window.confirm(msg)) {
            e.preventDefault();
        }
    }, true);

    // Auto-submit a control's form when it changes (filter selects/checkboxes).
    document.addEventListener('change', function (e) {
        var el = e.target.closest ? e.target.closest('[data-autosubmit]') : null;
        if (!el) return;
        var form = el.form || (el.closest ? el.closest('form') : null);
        if (!form) return;
        if (typeof form.requestSubmit === 'function') {
            form.requestSubmit();
        } else {
            form.submit();
        }
    });
})();
