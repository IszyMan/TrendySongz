(() => {
    function showFeedback(element, messages, state) {
        element.textContent = messages.join('\n');
        element.dataset.state = state;
        element.setAttribute(
            'role',
            state === 'error' ? 'alert' : 'status'
        );
        element.hidden = false;
        element.focus();
    }

    function requestError(status) {
        const messages = {
            419: 'Your session expired. Refresh this page and try again.',
            429: 'Too many attempts. Please wait before trying again.',
            403: 'This action could not be verified. Refresh the page and try again.',
            404: 'This comment is no longer available.'
        };

        return messages[status]
            || 'We could not confirm the action. Please try again later.';
    }

    document.querySelectorAll('[data-ts-comment-form]').forEach((form) => {
        if (form.dataset.tsReady === 'true') {
            return;
        }

        form.dataset.tsReady = 'true';

        const button = form.querySelector('button[type="submit"]');
        const feedback = form.querySelector('[data-ts-comment-feedback]');

        let busy = false;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (busy || !form.reportValidity()) {
                return;
            }

            busy = true;
            button.disabled = true;

            const originalText = button.textContent;

            button.textContent = 'Submitting…';
            form.setAttribute('aria-busy', 'true');

            feedback.hidden = true;
            feedback.textContent = '';

            form.querySelectorAll('[aria-invalid]').forEach((field) => {
                field.removeAttribute('aria-invalid');
            });

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json().catch(() => ({}));

                if (response.status === 422) {
                    const errors = data.errors || {};

                    Object.keys(errors).forEach((name) => {
                        const field = form.elements.namedItem(name);

                        if (field && field.setAttribute) {
                            field.setAttribute('aria-invalid', 'true');
                        }
                    });

                    const messages = Object.values(errors).flat();

                    showFeedback(
                        feedback,
                        messages.length
                            ? messages
                            : ['Please check your comment details.'],
                        'error'
                    );

                    return;
                }

                if (!response.ok || data.success !== true) {
                    showFeedback(
                        feedback,
                        [requestError(response.status)],
                        'error'
                    );

                    return;
                }

                form.reset();

                showFeedback(feedback, [data.message], 'success');

                // The comment stays hidden until approved.
            } catch (error) {
                showFeedback(
                    feedback,
                    [
                        'We could not confirm submission. Please check your connection before trying again.'
                    ],
                    'error'
                );
            } finally {
                busy = false;
                button.disabled = false;
                button.textContent = originalText;
                form.removeAttribute('aria-busy');
            }
        });
    });

    document.querySelectorAll('.ts-comment-like').forEach((button) => {
        if (button.dataset.tsReady === 'true') {
            return;
        }

        button.dataset.tsReady = 'true';

        const container = button.closest('.ts-comment-reactions');
        const count = container.querySelector('.ts-comment-like-count');
        const errorMessage = container.querySelector('.ts-comment-like-error');

        button.addEventListener('click', async () => {
            if (button.disabled) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');

            errorMessage.hidden = true;
            errorMessage.textContent = '';

            try {
                const response = await fetch(button.dataset.likeUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': button.dataset.csrf
                    }
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok || data.success !== true) {
                    errorMessage.textContent = requestError(response.status);
                    errorMessage.hidden = false;

                    return;
                }

                count.textContent = new Intl.NumberFormat().format(
                    Number(data.likes_count)
                );

                button.classList.add('ts-has-liked');
                button.setAttribute('aria-pressed', 'true');
                button.setAttribute('aria-label', 'You liked this comment');
            } catch (error) {
                errorMessage.textContent =
                    'Could not confirm your like. Please check your connection.';

                errorMessage.hidden = false;
            } finally {
                button.removeAttribute('aria-busy');

                button.disabled =
                    button.getAttribute('aria-pressed') === 'true';
            }
        });
    });
})();