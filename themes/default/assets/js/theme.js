document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }
    let button = event.target.closest('.copy-link');
    if (!button || !navigator.clipboard) {
        return;
    }

    event.preventDefault();
    navigator.clipboard.writeText(button.dataset.url).then(() => {
        const original = button.textContent;
        button.textContent = 'Copied';
        setTimeout(() => { button.textContent = original; }, 1500);
    }).catch(() => {
        // Clipboard write can be rejected (denied permission, insecure
        // context, lost focus); ignore silently rather than leave the
        // rejection unhandled.
    });
});
