(function () {
    async function copyLink(url) {
        if (window.isSecureContext && navigator.clipboard) {
            try {
                await navigator.clipboard.writeText(url);
                return;
            } catch (error) {
                // Try the fallback below.
            }
        }

        const field = document.createElement('textarea');
        field.value = url;
        field.setAttribute('readonly', '');
        field.style.position = 'fixed';
        field.style.opacity = '0';

        const previousFocus = document.activeElement;

        document.body.appendChild(field);

        let copied = false;

        try {
            field.focus();
            field.select();
            field.setSelectionRange(0, field.value.length);
            copied = document.execCommand('copy');
        } finally {
            field.remove();

            if (previousFocus && previousFocus.focus) {
                previousFocus.focus();
            }
        }

        if (!copied) {
            throw new Error('Copy failed');
        }
    }

    document.addEventListener('click', async function (event) {
        const button = event.target.closest('.ts-share-copy');

        if (!button || button.disabled) {
            return;
        }

        const block = button.closest('.ts-share-block');
        const label = button.querySelector('.ts-share-copy-label');
        const status = block.querySelector('.ts-share-status');
        const url = button.dataset.shareUrl;

        button.disabled = true;
        status.textContent = '';

        try {
            await copyLink(url);

            label.textContent = 'Copied!';
            status.textContent = 'Link copied to clipboard.';
        } catch (error) {
            label.textContent = 'Copy link';
            status.textContent = 'Copy the link from the dialog.';
            window.prompt('Copy this link:', url);
        } finally {
            button.disabled = false;

            window.clearTimeout(button.shareResetTimer);

            button.shareResetTimer = window.setTimeout(function () {
                label.textContent = 'Copy link';
                status.textContent = '';
            }, 2500);
        }
    });
})();