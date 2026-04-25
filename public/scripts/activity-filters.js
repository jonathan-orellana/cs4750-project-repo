function initializeActivityFilters() {
    const activityCard = document.querySelector('.activity-card');
    const filterToggle = document.querySelector('[data-filter-toggle]');
    const filterForm = document.querySelector('[data-filter-form]');
    const filterActions = document.querySelectorAll('.filter-action');
    const filterFields = document.querySelectorAll('[data-filter-form] input:not([type="hidden"]), [data-filter-form] select');
    const clearLink = document.querySelector('.clear-link');

    if (!activityCard || !filterForm) {
        return;
    }

    if (filterToggle) {
        filterToggle.addEventListener('click', () => {
            filterToggle.hidden = true;
            filterActions.forEach((action) => {
                action.hidden = false;
            });
            filterFields.forEach((field) => {
                field.disabled = false;
            });
        });
    }

    filterForm.addEventListener('submit', (event) => {
        event.preventDefault();
        loadActivity(new URLSearchParams(new FormData(filterForm)).toString());
    });

    if (clearLink) {
        clearLink.addEventListener('click', (event) => {
            event.preventDefault();
            loadActivity(new URL(clearLink.href).searchParams.toString());
        });
    }
}

function loadActivity(queryString) {
    const url = `/groups/dashboard?${queryString}`;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then((response) => response.text())
        .then((html) => {
            const parser = new DOMParser();
            const nextDocument = parser.parseFromString(html, 'text/html');
            const nextActivityCard = nextDocument.querySelector('.activity-card');
            const currentActivityCard = document.querySelector('.activity-card');

            if (!nextActivityCard || !currentActivityCard) {
                window.location.href = url;
                return;
            }

            currentActivityCard.innerHTML = nextActivityCard.innerHTML;
            window.history.replaceState({}, '', url);
            initializeActivityFilters();
        })
        .catch(() => {
            window.location.href = url;
        });
}

initializeActivityFilters();
