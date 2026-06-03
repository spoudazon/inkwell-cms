document.addEventListener('click', (event) => {
    let button = event.target.closest('.copy-link');
    if (!button || !navigator.clipboard) {
        return;
    }

    event.preventDefault();
    navigator.clipboard.writeText(button.dataset.url).then(() => {
        const original = button.textContent;
        button.textContent = 'Copied';
        setTimeout(() => { button.textContent = original; }, 1500);
    });
});
