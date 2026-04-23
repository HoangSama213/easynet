document.addEventListener('DOMContentLoaded', () => {
    const closeCollapseGroup = (group, exceptId) => {
        document.querySelectorAll(`[data-collapse-item="${group}"]`).forEach((item) => {
            if (item.id !== exceptId) {
                item.setAttribute('hidden', 'hidden');
            }
        });

        document.querySelectorAll(`.collapse-toggle[data-collapse-group="${group}"]`).forEach((button) => {
            const targetId = button.getAttribute('data-target');

            if (targetId !== exceptId) {
                button.setAttribute('aria-expanded', 'false');
            }
        });
    };

    document.querySelectorAll('.collapse-toggle[data-target]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const group = button.getAttribute('data-collapse-group') || 'default';
            const target = document.getElementById(targetId);

            if (!target) {
                return;
            }

            const isHidden = target.hasAttribute('hidden');
            closeCollapseGroup(group, targetId);

            if (isHidden) {
                target.removeAttribute('hidden');
                button.setAttribute('aria-expanded', 'true');
                return;
            }

            target.setAttribute('hidden', 'hidden');
            button.setAttribute('aria-expanded', 'false');
        });
    });

    const body = document.body;
    const sidebar = document.querySelector('[data-sidebar]');
    const navBackdrop = document.querySelector('[data-nav-backdrop]');

    const closeNav = () => {
        body.classList.remove('nav-open');
    };

    const openNav = () => {
        body.classList.add('nav-open');
    };

    document.querySelectorAll('[data-nav-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            if (body.classList.contains('nav-open')) {
                closeNav();
                return;
            }

            openNav();
        });
    });

    document.querySelectorAll('[data-nav-close]').forEach((button) => {
        button.addEventListener('click', closeNav);
    });

    if (navBackdrop) {
        navBackdrop.addEventListener('click', closeNav);
    }

    if (sidebar) {
        sidebar.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeNav);
        });
    }

    document.querySelectorAll('[data-modal-open]').forEach((openButton) => {
        openButton.addEventListener('click', () => {
            const modal = document.getElementById(openButton.getAttribute('data-modal-open'));

            if (!modal) {
                return;
            }

            modal.removeAttribute('hidden');
            body.classList.add('modal-open');
        });
    });

    const closeModal = (modal) => {
        modal.setAttribute('hidden', 'hidden');
        body.classList.remove('modal-open');
    };

    document.querySelectorAll('[data-modal-close]').forEach((closeButton) => {
        closeButton.addEventListener('click', () => {
            const modal = closeButton.closest('.modal-backdrop');

            if (modal) {
                closeModal(modal);
            }
        });
    });

    document.querySelectorAll('.modal-backdrop').forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal(modal);
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal-backdrop:not([hidden])').forEach((modal) => {
                closeModal(modal);
            });
            closeNav();
        }
    });
});
