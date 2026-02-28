/**
 * Integrasi API Autentikasi (Sanctum)
 * - Token disimpan di localStorage (auth_token)
 * - Gunakan getAuthHeaders() atau fetchWithAuth() untuk request yang butuh auth
 */
(function () {
    "use strict";

    const TOKEN_KEY = "auth_token";
    const LOGIN_PATH = "/login";

    window.getAuthToken = function () {
        return localStorage.getItem(TOKEN_KEY);
    };

    window.getAuthHeaders = function (extra) {
        const token = window.getAuthToken();
        const headers = {
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        };
        if (token) {
            headers["Authorization"] = "Bearer " + token;
        }
        return Object.assign({}, headers, extra || {});
    };

    /**
     * fetch dengan header Authorization. Jika 401, redirect ke halaman login.
     * @param {string} url
     * @param {RequestInit} options
     * @returns {Promise<Response>}
     */
    window.fetchWithAuth = function (url, options) {
        const opts = options || {};
        opts.headers = window.getAuthHeaders(opts.headers);

        return fetch(url, opts).then(function (response) {
            if (response.status === 401) {
                localStorage.removeItem(TOKEN_KEY);
                window.location.href = LOGIN_PATH;
                return Promise.reject(new Error("Unauthorized"));
            }
            return response;
        });
    };

    /**
     * Logout: panggil API logout lalu hapus token dan redirect ke login.
     */
    window.logoutViaApi = function () {
        const token = window.getAuthToken();
        if (!token) {
            window.location.href = LOGIN_PATH;
            return Promise.resolve();
        }
        return fetch("/api/auth/logout", {
            method: "POST",
            headers: window.getAuthHeaders(),
        })
            .then(function () {
                localStorage.removeItem(TOKEN_KEY);
                window.location.href = LOGIN_PATH;
            })
            .catch(function () {
                localStorage.removeItem(TOKEN_KEY);
                window.location.href = LOGIN_PATH;
            });
    };

    window.clearAuth = function () {
        localStorage.removeItem(TOKEN_KEY);
    };
})();
