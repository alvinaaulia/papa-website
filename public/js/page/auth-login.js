"use strict";

$(function () {
    const $form = $(".login-form");
    if (!$form.length) {
        return;
    }

    const $email = $("#email");
    const $password = $("#password");
    const $loginButton = $("#login-button");

    function setLoading(isLoading) {
        if (isLoading) {
            $loginButton.prop("disabled", true).text("Loading...");
        } else {
            $loginButton.prop("disabled", false).text("Login");
        }
    }

    function showError(message) {
        const $alert = $("<div>")
            .addClass("alert alert-danger mt-3")
            .text(message);

        $(".login-alert").remove();
        $alert.addClass("login-alert");
        $form.prepend($alert);
    }

    function clearError() {
        $(".login-alert").remove();
    }

    function redirectByRole(role) {
        switch ((role || "").toLowerCase()) {
            case "karyawan":
                window.location.href = "/karyawan/dashboard-employee";
                break;
            case "project manager":
            case "pm":
                window.location.href = "/pm/dashboard-PM";
                break;
            case "hrd":
                window.location.href = "/hrd/dashboard-HRD";
                break;
            case "director":
            case "direktur":
                window.location.href = "/director/dashboard-director";
                break;
            default:
                window.location.href = "/landing-page";
        }
    }

    $form.on("submit", function (e) {
        e.preventDefault();

        clearError();

        const email = $email.val().trim();
        const password = $password.val();

        if (!email || !password) {
            showError("Email dan password wajib diisi.");
            return;
        }

        setLoading(true);

        $.ajax({
            url: "/api/auth/login",
            method: "POST",
            data: {
                email: email,
                password: password,
            },
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        })
            .done(function (response) {
                if (response.token) {
                    localStorage.setItem("auth_token", response.token);
                }
                if (response.user && response.user.role) {
                    redirectByRole(response.user.role);
                } else {
                    redirectByRole(null);
                }
            })
            .fail(function (jqXHR) {
                let message = "Login gagal. Periksa kembali email dan password.";
                const data = jqXHR.responseJSON;
                if (data) {
                    if (data.message) {
                        message = data.message;
                    }
                    if (data.errors) {
                        const first = Object.values(data.errors)[0];
                        message = Array.isArray(first) ? first[0] : first;
                    }
                }
                showError(message);
            })
            .always(function () {
                setLoading(false);
            });
    });
});

