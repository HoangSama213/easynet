document.addEventListener('DOMContentLoaded', () => {
    const page = document.querySelector('[data-product-relations-page]');
    if (!page) {
        return;
    }

    const apiUrl = page.dataset.apiUrl || '';
    const listUrl = page.dataset.listUrl || '';
    const manageUrl = page.dataset.manageUrl || '';
    const pageMode = page.dataset.pageMode || 'list';
    const selectedId = Number(page.dataset.selectedId || 0);
    const categoryId = Number(page.dataset.categoryId || 0);
    const defaultType = page.dataset.defaultType || 'cross_sell';
    const csrfToken = page.dataset.csrf || '';

    const formatMoney = (value) => `${Number(value || 0).toLocaleString('vi-VN')} đ`;
    const discountText = (retail, bundle) => {
        if (bundle === '' || bundle === null || Number.isNaN(Number(bundle)) || retail <= 0) {
            return '—';
        }

        const discount = ((retail - Number(bundle)) / retail) * 100;
        return `${discount.toFixed(2).replace('.', ',')}%`;
    };

    const hideResults = (results) => {
        if (!results) {
            return;
        }

        results.hidden = true;
        results.innerHTML = '';
    };

    const renderSearchResults = (results, items) => {
        if (!results) {
            return;
        }

        if (!Array.isArray(items) || items.length === 0) {
            results.hidden = false;
            results.innerHTML = '<div class="relation-search-empty">Không tìm thấy sản phẩm phù hợp.</div>';
            return;
        }

        results.hidden = false;
        results.innerHTML = items.map((item) => `
            <button type="button" class="relation-search-item" data-id="${item.id}">
                <strong>${item.ten}</strong>
                <span>${item.sku || 'Không có SKU'} · ${item.danh_muc}</span>
            </button>
        `).join('');
    };

    const request = (action, payload = {}, method = 'POST') => {
        const options = {
            method,
            credentials: 'same-origin',
            headers: {},
        };

        if (method === 'POST') {
            options.headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
            options.body = new URLSearchParams({
                ...payload,
                _token: csrfToken,
            }).toString();
        }

        const query = method === 'GET'
            ? `?${new URLSearchParams({ action, ...payload }).toString()}`
            : `?action=${encodeURIComponent(action)}`;

        return fetch(`${apiUrl}${query}`, options).then(async (response) => {
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Không thể xử lý yêu cầu.');
            }

            return data;
        });
    };

    const bindRootSearch = () => {
        const shell = page.querySelector('[data-root-search-shell]');
        const input = page.querySelector('[data-root-search-input]');
        const results = page.querySelector('[data-root-search-results]');
        if (!shell || !input || !results) {
            return;
        }

        const redirectBase = pageMode === 'manage' ? manageUrl : listUrl;
        let controller = null;

        input.addEventListener('input', () => {
            const keyword = input.value.trim();
            if (controller) {
                controller.abort();
            }

            if (keyword.length < 2) {
                hideResults(results);
                return;
            }

            controller = new AbortController();
            fetch(`${apiUrl}?action=search&q=${encodeURIComponent(keyword)}`, {
                signal: controller.signal,
                credentials: 'same-origin',
            })
                .then((response) => response.json())
                .then((payload) => renderSearchResults(results, payload.data || []))
                .catch((error) => {
                    if (error.name !== 'AbortError') {
                        hideResults(results);
                    }
                });
        });

        results.addEventListener('click', (event) => {
            const button = event.target.closest('.relation-search-item');
            if (!button) {
                return;
            }

            window.location.href = `${redirectBase}?chi_tiet_id=${encodeURIComponent(button.dataset.id)}`;
        });

        document.addEventListener('click', (event) => {
            if (!shell.contains(event.target)) {
                hideResults(results);
            }
        });
    };

    const clearEmptyRow = (tbody) => {
        const emptyRow = tbody.querySelector('.relation-empty-row');
        if (emptyRow) {
            emptyRow.remove();
        }
    };

    const renderUpSellRow = (row) => {
        const diff = Number(row.chenh_lech_gia || 0);
        const diffClass = diff > 0 ? 'is-positive' : (diff < 0 ? 'is-negative' : '');
        return `
            <tr data-relation-row="${row.id}" data-type="up_sell">
                <td>
                    <strong>${row.ten}</strong>
                    <div class="relation-cell-sub">${row.sku || ''}</div>
                </td>
                <td>${formatMoney(row.gia_ban_le)}</td>
                <td class="${diffClass}">${formatMoney(diff)}</td>
                <td>
                    <input class="relation-table-input" type="text" value="${row.note || ''}" data-update-note="${row.id}">
                </td>
                <td>
                    <label class="relation-switch ${row.co_the_kich_hoat ? '' : 'is-disabled'}">
                        <input type="checkbox" ${row.chi_kich_hoat_khi_con_hang ? 'checked' : ''} ${row.co_the_kich_hoat ? '' : 'disabled'} data-update-up-sell="${row.id}">
                        <span class="relation-switch-track"></span>
                    </label>
                </td>
                <td>
                    <button type="button" class="btn btn-danger relation-delete-btn" data-delete-relation="${row.id}">Xóa</button>
                </td>
            </tr>
        `;
    };

    const renderCrossSellRow = (row) => `
        <tr data-relation-row="${row.id}" data-type="cross_sell">
            <td>
                <strong>${row.ten}</strong>
                <div class="relation-cell-sub">${row.sku || ''}</div>
            </td>
            <td data-retail-price="${row.gia_ban_le}">${formatMoney(row.gia_ban_le)}</td>
            <td>
                <input class="relation-table-input" type="number" min="0" step="1000" value="${row.gia_mua_kem ?? ''}" data-update-bundle-price="${row.id}">
            </td>
            <td data-discount-cell>${row.phan_tram_giam !== null ? discountText(Number(row.gia_ban_le), Number(row.gia_mua_kem)) : '—'}</td>
            <td>
                <input class="relation-table-input" type="number" min="1" step="1" value="${row.so_luong_toi_da || 1}" data-update-max-qty="${row.id}">
            </td>
            <td>
                <button type="button" class="btn btn-danger relation-delete-btn" data-delete-relation="${row.id}">Xóa</button>
            </td>
        </tr>
    `;

    const setType = (type) => {
        const panelMap = {
            cross_sell: page.querySelector('[data-step-panel="cross_sell"]'),
            up_sell: page.querySelector('[data-step-panel="up_sell"]'),
        };

        Object.entries(panelMap).forEach(([key, panel]) => {
            if (panel) {
                panel.hidden = key !== type;
            }
        });

        page.querySelectorAll('.relation-radio-card').forEach((card) => {
            const input = card.querySelector('input[type="radio"]');
            card.classList.toggle('is-selected', !!input && input.value === type);
        });
    };

    const bindAddSearch = (type) => {
        const shell = page.querySelector(`[data-add-search-shell="${type}"]`);
        if (!shell) {
            return;
        }

        const input = page.querySelector(`[data-add-search-input="${type}"]`);
        const results = page.querySelector(`[data-add-search-results="${type}"]`);
        const trigger = page.querySelector(`[data-trigger-search="${type}"]`);
        if (!input || !results) {
            return;
        }

        let controller = null;

        trigger?.addEventListener('click', () => input.focus());

        input.addEventListener('input', () => {
            const keyword = input.value.trim();
            if (controller) {
                controller.abort();
            }

            if (keyword.length < 2) {
                hideResults(results);
                return;
            }

            controller = new AbortController();
            const params = new URLSearchParams({
                action: 'search',
                q: keyword,
            });

            if (type === 'up_sell' && categoryId > 0) {
                params.set('category_id', String(categoryId));
            }

            fetch(`${apiUrl}?${params.toString()}`, {
                signal: controller.signal,
                credentials: 'same-origin',
            })
                .then((response) => response.json())
                .then((payload) => {
                    const filtered = (payload.data || []).filter((item) => Number(item.id) !== selectedId);
                    renderSearchResults(results, filtered);
                })
                .catch((error) => {
                    if (error.name !== 'AbortError') {
                        hideResults(results);
                    }
                });
        });

        results.addEventListener('click', (event) => {
            const button = event.target.closest('.relation-search-item');
            if (!button) {
                return;
            }

            request('add', {
                source_chi_tiet_id: selectedId,
                target_chi_tiet_id: button.dataset.id,
                relation_type: type,
            }).then((payload) => {
                const row = payload.data;
                const tbody = document.getElementById(type === 'up_sell' ? 'up-sell-body' : 'cross-sell-body');
                clearEmptyRow(tbody);
                tbody.insertAdjacentHTML('beforeend', type === 'up_sell' ? renderUpSellRow(row) : renderCrossSellRow(row));
                input.value = '';
                hideResults(results);
            }).catch((error) => {
                window.alert(error.message);
            });
        });

        document.addEventListener('click', (event) => {
            if (!shell.contains(event.target)) {
                hideResults(results);
            }
        });
    };

    bindRootSearch();

    if (pageMode !== 'manage' || selectedId <= 0) {
        return;
    }

    page.querySelectorAll('input[name="relation_type"]').forEach((radio) => {
        radio.addEventListener('change', () => setType(radio.value));
    });
    setType(defaultType);

    bindAddSearch('up_sell');
    bindAddSearch('cross_sell');

    page.addEventListener('click', (event) => {
        const deleteButton = event.target.closest('[data-delete-relation]');
        if (!deleteButton) {
            return;
        }

        const relationId = deleteButton.dataset.deleteRelation;
        request('delete', { id: relationId })
            .then(() => {
                const row = page.querySelector(`[data-relation-row="${relationId}"]`);
                row?.remove();
            })
            .catch((error) => {
                window.alert(error.message);
            });
    });

    page.addEventListener('change', (event) => {
        const noteInput = event.target.closest('[data-update-note]');
        if (noteInput) {
            request('update_note', {
                id: noteInput.dataset.updateNote,
                note: noteInput.value,
            }).catch((error) => window.alert(error.message));
            return;
        }

        const toggle = event.target.closest('[data-update-up-sell]');
        if (toggle) {
            request('update_up_sell', {
                id: toggle.dataset.updateUpSell,
                chi_kich_hoat_khi_con_hang: toggle.checked ? 1 : 0,
            }).catch((error) => window.alert(error.message));
            return;
        }

        const bundleInput = event.target.closest('[data-update-bundle-price]');
        if (bundleInput) {
            const row = bundleInput.closest('tr');
            const maxQtyInput = row.querySelector('[data-update-max-qty]');
            request('update_cross_sell', {
                id: bundleInput.dataset.updateBundlePrice,
                gia_mua_kem: bundleInput.value,
                so_luong_toi_da: maxQtyInput.value,
            }).then((payload) => {
                row.querySelector('[data-discount-cell]').textContent = payload.data.phan_tram_giam !== null
                    ? discountText(Number(payload.data.gia_ban_le), Number(payload.data.gia_mua_kem))
                    : '—';
            }).catch((error) => window.alert(error.message));
            return;
        }

        const maxQtyInput = event.target.closest('[data-update-max-qty]');
        if (maxQtyInput) {
            const row = maxQtyInput.closest('tr');
            const bundleInput = row.querySelector('[data-update-bundle-price]');
            request('update_cross_sell', {
                id: maxQtyInput.dataset.updateMaxQty,
                gia_mua_kem: bundleInput.value,
                so_luong_toi_da: maxQtyInput.value,
            }).then((payload) => {
                row.querySelector('[data-discount-cell]').textContent = payload.data.phan_tram_giam !== null
                    ? discountText(Number(payload.data.gia_ban_le), Number(payload.data.gia_mua_kem))
                    : '—';
            }).catch((error) => window.alert(error.message));
        }
    });
});
