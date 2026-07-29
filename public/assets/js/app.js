document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.site-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', nav.classList.contains('open'));
        });

        document.addEventListener('click', function (e) {
            if (!toggle.contains(e.target) && !nav.contains(e.target)) {
                nav.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.querySelectorAll('.flash').forEach(function (flash) {
        const close = document.createElement('button');
        close.className = 'flash-close';
        close.setAttribute('aria-label', 'Fermer');
        close.textContent = '×';
        close.addEventListener('click', function () {
            flash.remove();
        });
        flash.appendChild(close);

        setTimeout(function () {
            flash.style.transition = 'opacity 0.3s, transform 0.3s';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-8px)';
            setTimeout(function () { flash.remove(); }, 300);
        }, 5000);
    });
});
