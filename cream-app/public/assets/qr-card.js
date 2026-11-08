// Printable feedback QR card — wire the print button without inline handlers
// (the staff CSP disallows inline scripts).
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('printBtn');
    if (btn) {
        btn.addEventListener('click', function () {
            window.print();
        });
    }
});
