(() => {
    const hiddenClass = 'admin-managed-column';
    const excludedHeader = /^(actions?|options?|select|checkbox|operations?)$/i;
    const interactiveColumn = 'input, select, textarea, button, form';

    const initializeColumnManagers = () => {
        const tables = Array.from(document.querySelectorAll('#main-content table.table'));
        const userId = document.body.dataset.adminUserId || 'admin';

        tables.forEach((table, tableIndex) => {
            if (table.dataset.columnManager === 'off' || !table.tHead?.rows.length) {
                return;
            }

            const headerCells = Array.from(table.tHead.rows[table.tHead.rows.length - 1].cells);
            const bodyRows = table.tBodies.length ? Array.from(table.tBodies).flatMap((body) => Array.from(body.rows)) : [];

            if (headerCells.length < 2) {
                return;
            }

            const headerOccurrences = new Map();
            const columns = headerCells.map((header, index) => {
                const label = header.textContent.replace(/\s+/g, ' ').trim();
                const occurrence = (headerOccurrences.get(label) || 0) + 1;
                headerOccurrences.set(label, occurrence);

                return {
                    index,
                    label,
                    key: `${label}:${occurrence}`,
                    manageable: Boolean(label)
                        && !excludedHeader.test(label)
                        && !header.querySelector(interactiveColumn)
                        && !bodyRows.some((row) => row.cells[index]?.querySelector(interactiveColumn)),
                };
            }).filter((column) => column.manageable);

            if (columns.length < 2) {
                return;
            }

            const tableName = table.dataset.columnManagerKey || table.id || `table-${tableIndex + 1}`;
            const storageKey = [
                'kso-admin-columns',
                userId,
                window.location.pathname,
                tableName,
                columns.map((column) => column.key).join('|'),
            ].join(':');

            let hiddenKeys = [];
            try {
                const saved = JSON.parse(window.localStorage.getItem(storageKey) || '[]');
                if (Array.isArray(saved)) {
                    hiddenKeys = saved.filter((key) => columns.some((column) => column.key === key));
                }
            } catch {
                hiddenKeys = [];
            }

            const wrapper = table.closest('.table-responsive') || table;
            const toolbar = document.createElement('div');
            toolbar.className = 'admin-column-manager-toolbar';

            const manager = document.createElement('details');
            manager.className = 'admin-column-manager';

            const trigger = document.createElement('summary');
            trigger.setAttribute('aria-label', 'Choose visible table columns');

            const triggerIcon = document.createElement('i');
            triggerIcon.className = 'fa-solid fa-table-columns';
            triggerIcon.setAttribute('aria-hidden', 'true');
            trigger.append(triggerIcon, document.createTextNode(' Columns'));

            const panel = document.createElement('div');
            panel.className = 'admin-column-manager-panel';
            panel.setAttribute('role', 'group');
            panel.setAttribute('aria-label', 'Visible columns');

            const heading = document.createElement('div');
            heading.className = 'admin-column-manager-heading';
            const headingText = document.createElement('span');
            headingText.textContent = 'Toggle columns';
            const reset = document.createElement('button');
            reset.className = 'admin-column-manager-reset';
            reset.type = 'button';
            reset.textContent = 'Reset';
            reset.setAttribute('aria-label', 'Show all table columns');
            heading.append(headingText, reset);
            panel.append(heading);

            const checkboxes = new Map();

            const applyVisibility = () => {
                const visibleColumns = columns.filter((column) => !hiddenKeys.includes(column.key));

                columns.forEach((column) => {
                    const hidden = hiddenKeys.includes(column.key);
                    const header = headerCells[column.index];
                    header.classList.add(hiddenClass);
                    header.hidden = hidden;

                    bodyRows.forEach((row) => {
                        const cell = row.cells[column.index];
                        if (cell && row.cells.length === headerCells.length) {
                            cell.classList.add(hiddenClass);
                            cell.hidden = hidden;
                        }
                    });
                });

                bodyRows.forEach((row) => {
                    if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                        row.cells[0].colSpan = Math.max(1, visibleColumns.length + (headerCells.length - columns.length));
                    }
                });

                checkboxes.forEach((checkbox, key) => {
                    checkbox.checked = !hiddenKeys.includes(key);
                    checkbox.disabled = checkbox.checked && visibleColumns.length === 1;
                });

                try {
                    window.localStorage.setItem(storageKey, JSON.stringify(hiddenKeys));
                } catch {
                    // Keep current-session visibility when browser storage is unavailable.
                }
            };

            columns.forEach((column) => {
                const label = document.createElement('label');
                label.className = 'admin-column-manager-option';

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.checked = !hiddenKeys.includes(column.key);
                checkbox.addEventListener('change', () => {
                    if (!checkbox.checked && columns.filter((item) => !hiddenKeys.includes(item.key)).length <= 1) {
                        checkbox.checked = true;
                        return;
                    }

                    hiddenKeys = checkbox.checked
                        ? hiddenKeys.filter((key) => key !== column.key)
                        : [...hiddenKeys, column.key];
                    applyVisibility();
                });

                const text = document.createElement('span');
                text.textContent = column.label;
                label.append(checkbox, text);
                panel.append(label);
                checkboxes.set(column.key, checkbox);
            });

            reset.addEventListener('click', () => {
                hiddenKeys = [];
                applyVisibility();
            });

            manager.append(trigger, panel);
            toolbar.append(manager);
            wrapper.parentNode.insertBefore(toolbar, wrapper);
            applyVisibility();
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeColumnManagers, { once: true });
    } else {
        initializeColumnManagers();
    }
})();
