document.addEventListener('DOMContentLoaded', () => {
    const collapseTimers = new Map();
    const navStateKey = 'easynet-nav-open-groups';

    const setCollapseState = (target, button, isOpen) => {
        const wrapper = target.classList.contains('detail-row')
            ? target.querySelector('.supplier-accordion-card, .nested-card')
            : target;

        if (!wrapper) {
            if (isOpen) {
                target.removeAttribute('hidden');
            } else {
                target.setAttribute('hidden', 'hidden');
            }
            button?.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            return;
        }

        const existingTimer = collapseTimers.get(target.id);
        if (existingTimer) {
            window.clearTimeout(existingTimer);
            collapseTimers.delete(target.id);
        }

        if (isOpen) {
            target.removeAttribute('hidden');
            target.classList.add('is-open');
            wrapper.classList.add('is-open');
            button?.setAttribute('aria-expanded', 'true');
            wrapper.style.maxHeight = '0px';
            window.requestAnimationFrame(() => {
                wrapper.style.maxHeight = `${wrapper.scrollHeight + 8}px`;
            });
            return;
        }

        target.classList.remove('is-open');
        wrapper.classList.remove('is-open');
        button?.setAttribute('aria-expanded', 'false');
        wrapper.style.maxHeight = '0px';

        const timer = window.setTimeout(() => {
            target.setAttribute('hidden', 'hidden');
            collapseTimers.delete(target.id);
        }, 280);

        collapseTimers.set(target.id, timer);
    };

    const closeCollapseGroup = (group, exceptId) => {
        document.querySelectorAll(`[data-collapse-item="${group}"]`).forEach((item) => {
            if (item.id !== exceptId) {
                const linkedButton = document.querySelector(`.collapse-toggle[data-target="${item.id}"]`);
                setCollapseState(item, linkedButton, false);
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
                setCollapseState(target, button, true);
                return;
            }

            setCollapseState(target, button, false);
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

    const readNavState = () => {
        try {
            const raw = window.localStorage.getItem(navStateKey);
            const parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    };

    const writeNavState = (ids) => {
        try {
            window.localStorage.setItem(navStateKey, JSON.stringify(Array.from(new Set(ids))));
        } catch (error) {
            // Ignore storage errors and keep the UI usable.
        }
    };

    const setNavGroupState = (button, target, wrapper, isOpen) => {
        if (isOpen) {
            target.removeAttribute('hidden');
            target.classList.add('is-open');
            wrapper.classList.add('is-open');
            button.classList.add('active');
            button.setAttribute('aria-expanded', 'true');
            return;
        }

        target.setAttribute('hidden', 'hidden');
        target.classList.remove('is-open');
        wrapper.classList.remove('is-open');
        button.classList.remove('active');
        button.setAttribute('aria-expanded', 'false');
    };

    const openNavGroups = new Set(readNavState());
    const activeNavGroups = new Set();

    const persistNavState = () => {
        writeNavState([...openNavGroups].filter((id) => !activeNavGroups.has(id)));
    };

    document.querySelectorAll('[data-nav-group-toggle]').forEach((button) => {
        const targetId = button.getAttribute('data-target');
        const target = targetId ? document.getElementById(targetId) : null;
        const wrapper = button.closest('.nav-group');

        if (target && wrapper) {
            const hasActiveChild = !!wrapper.querySelector('.nav-subitem.active');
            if (hasActiveChild && targetId) {
                activeNavGroups.add(targetId);
            }
            const shouldOpen = openNavGroups.has(targetId) || hasActiveChild;
            setNavGroupState(button, target, wrapper, shouldOpen);

            if (shouldOpen && targetId) {
                openNavGroups.add(targetId);
            }
        }

        button.addEventListener('click', () => {
            if (!target || !wrapper) {
                return;
            }

            const isOpen = !target.hasAttribute('hidden');
            const isActiveGroup = !!targetId && activeNavGroups.has(targetId);

            if (isOpen) {
                if (isActiveGroup) {
                    return;
                }

                setNavGroupState(button, target, wrapper, false);
                if (targetId) {
                    openNavGroups.delete(targetId);
                    persistNavState();
                }
                return;
            }

            document.querySelectorAll('[data-nav-group-toggle]').forEach((otherButton) => {
                const otherTargetId = otherButton.getAttribute('data-target');
                const otherTarget = otherTargetId ? document.getElementById(otherTargetId) : null;
                const otherWrapper = otherButton.closest('.nav-group');

                if (!otherTarget || !otherWrapper || otherTargetId === targetId || activeNavGroups.has(otherTargetId)) {
                    return;
                }

                const otherIsOpen = !otherTarget.hasAttribute('hidden');
                if (!otherIsOpen) {
                    return;
                }

                setNavGroupState(otherButton, otherTarget, otherWrapper, false);
                openNavGroups.delete(otherTargetId);
            });

            setNavGroupState(button, target, wrapper, true);
            if (targetId) {
                openNavGroups.add(targetId);
                persistNavState();
            }
        });
    });

    persistNavState();

    const notificationShell = document.querySelector('[data-notification-shell]');
    const notificationToggle = document.querySelector('[data-notification-toggle]');
    const notificationDropdown = document.querySelector('[data-notification-dropdown]');

    const closeNotifications = () => {
        if (!notificationToggle || !notificationDropdown) {
            return;
        }

        notificationToggle.setAttribute('aria-expanded', 'false');
        notificationDropdown.setAttribute('hidden', 'hidden');
    };

    const openNotifications = () => {
        if (!notificationToggle || !notificationDropdown) {
            return;
        }

        notificationToggle.setAttribute('aria-expanded', 'true');
        notificationDropdown.removeAttribute('hidden');
    };

    if (notificationToggle && notificationDropdown) {
        notificationToggle.addEventListener('click', (event) => {
            event.stopPropagation();

            if (notificationDropdown.hasAttribute('hidden')) {
                openNotifications();
                return;
            }

            closeNotifications();
        });

        document.addEventListener('click', (event) => {
            if (!notificationShell || notificationShell.contains(event.target)) {
                return;
            }

            closeNotifications();
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
            closeNotifications();
        }
    });
});
