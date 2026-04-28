document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-product-draft-form]');
    const draftKey = form?.getAttribute('data-product-draft-key') || 'supplier-product-create-draft';

    if (!form) {
        return;
    }

    const fieldNames = [
        'ncc_id',
        'san_pham_id',
        'ma_sku',
        'ten_chi_tiet',
        'thuong_hieu',
        'hinh_anh_san_pham',
        'bao_hanh',
        'mo_ta_ngan',
        'dac_diem',
        'thong_so_ky_thuat',
        'tinh_nang',
        'giai_phap_lien_quan',
        'du_an_lien_quan',
        'gia',
        'trang_thai',
        'ton_kho',
        'price_note',
    ];

    const hasCurrentData = () => {
        return fieldNames.some((name) => {
            const field = form.elements.namedItem(name);
            return field && String(field.value || '').trim() !== '';
        });
    };

    const saveDraft = () => {
        const draft = { fields: {} };

        fieldNames.forEach((name) => {
            const field = form.elements.namedItem(name);
            draft.fields[name] = field ? field.value : '';
        });

        window.sessionStorage.setItem(draftKey, JSON.stringify(draft));
    };

    const restoreDraft = () => {
        const rawDraft = window.sessionStorage.getItem(draftKey);
        if (!rawDraft || hasCurrentData()) {
            return;
        }

        try {
            const draft = JSON.parse(rawDraft);

            fieldNames.forEach((name) => {
                const field = form.elements.namedItem(name);

                if (field && draft.fields && typeof draft.fields[name] !== 'undefined') {
                    field.value = draft.fields[name];
                }
            });
        } catch (error) {
            window.sessionStorage.removeItem(draftKey);
        }
    };

    restoreDraft();

    form.addEventListener('input', saveDraft);
    form.addEventListener('change', saveDraft);
    form.addEventListener('submit', () => {
        window.sessionStorage.removeItem(draftKey);
    });

    document.querySelectorAll('[data-clear-product-draft]').forEach((link) => {
        link.addEventListener('click', () => {
            window.sessionStorage.removeItem(draftKey);
        });
    });
});
