(function () {
    'use strict';

    // Bootstrap form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Toast helper
    window.showToast = function (message, variant = 'primary') {
        const container = document.getElementById('toast-container');
        if (!container) {
            return;
        }
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${variant} border-0 show`;
        toast.role = 'alert';
        toast.ariaLive = 'assertive';
        toast.ariaAtomic = 'true';
        toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    };
})();
