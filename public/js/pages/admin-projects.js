(() => {
    const templateSelect = document.getElementById('project-template');
    const templateSummary = document.getElementById('project-template-summary');
    const projectTitle = document.getElementById('project-title');
    const projectDescription = document.getElementById('project-description');

    templateSelect?.addEventListener('change', () => {
        const template = templateSelect.selectedOptions[0];

        if (!template?.value) {
            if (templateSummary) {
                templateSummary.textContent = 'Choose an optional starting point; you can edit all generated text.';
            }
            return;
        }

        if (projectTitle) {
            projectTitle.value = template.dataset.title || '';
        }
        if (projectDescription) {
            projectDescription.value = template.dataset.description || '';
        }
        if (templateSummary) {
            templateSummary.textContent = template.dataset.summary || '';
        }
    });

    const modal = document.getElementById('addProjectModal');
    if (modal?.dataset.reopenOnError === 'true' && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modal).show();
    }

    const form = document.getElementById('createProjectForm');
    form?.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');

        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.textContent = 'Starting project…';
    });
})();
