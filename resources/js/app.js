import './bootstrap';

(async function () {
    async function handler(endpoint) {
        // Fetch the CSRF token from the meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

        // Fetch the link token from the server
        const response = await fetch(`/plaid/createLinkToken/${endpoint}`, {
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
    document.querySelectorAll('.plaid-link-bank-account').forEach(element => {
        element.addEventListener('click', () => handler('auth'));
    });

    document.querySelectorAll('.plaid-link-cd').forEach(element => {
        element.addEventListener('click', () => handler('cd'));
    });

    document.querySelectorAll('.plaid-link-investment-account').forEach(element => {
        element.addEventListener('click', () => handler('investments'));
    });
})();

