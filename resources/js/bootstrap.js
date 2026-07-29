import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/*
 * Laravel rejects state-changing AJAX requests without a CSRF token (HTTP 419).
 * Both layouts render <meta name="csrf-token">, so pick it up automatically.
 */
const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.warn('CSRF token meta tag not found: POST requests via axios will fail with 419.');
}
