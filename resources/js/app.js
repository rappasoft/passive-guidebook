import './bootstrap';

(async function () {
    async function handler(type) {
        // Fetch the CSRF token from the meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

        // Fetch the link token from the server
        const response = await fetch(`/plaid/createLinkToken`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
        });

        const { link_token } = await response.json();

        const plaid = Plaid.create({
            token: link_token,
            onSuccess: async function (public_token, metadata) {
                await fetch("/plaid/exchangePublicToken", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        public_token: public_token,
                        metadata: metadata,
                        type: type,
                    }),
                });

                location.reload();
            },
            onExit: function (err) {
                if (err) {
                    alert('There was a problem initializing Plaid. Please try again later.');
                }
            }
        });

        plaid.open();
    }

    // Add click event listeners to buttons
    document.querySelectorAll('.plaid-link-account').forEach(element => {
        element.addEventListener('click', () => handler(element.dataset.type));
    });
})();

