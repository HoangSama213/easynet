(function () {
    const storageKey = 'easynet-theme';
    const root = document.documentElement;

    const normalizeTheme = (value) => (value === 'dark' ? 'dark' : 'light');

    const applyTheme = (theme) => {
        const normalizedTheme = normalizeTheme(theme);
        root.setAttribute('data-theme', normalizedTheme);
        return normalizedTheme;
    };

    const readStoredTheme = () => {
        try {
            return normalizeTheme(window.localStorage.getItem(storageKey) || 'light');
        } catch (error) {
            return 'light';
        }
    };

    const writeStoredTheme = (theme) => {
        try {
            window.localStorage.setItem(storageKey, normalizeTheme(theme));
        } catch (error) {
            // Ignore storage errors to keep the page usable.
        }
    };

    const updateToggleLabels = (theme) => {
        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            const isDark = theme === 'dark';
            button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            button.setAttribute('aria-label', isDark ? 'Chuyển sang chế độ sáng' : 'Chuyển sang chế độ tối');

            const icon = button.querySelector('[data-theme-icon]');
            if (icon) {
                icon.textContent = isDark ? '☀️' : '🌙';
            }
        });
    };

    let currentTheme = applyTheme(readStoredTheme());

    document.addEventListener('DOMContentLoaded', () => {
        updateToggleLabels(currentTheme);

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                currentTheme = applyTheme(currentTheme);
                writeStoredTheme(currentTheme);
                updateToggleLabels(currentTheme);
            });
        });
    });
})();
