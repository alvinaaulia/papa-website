let fotoModalInstance;
let deleteModalInstance;
let salaryData = [];
let selectedSalaryId = null;

function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = {
        weekday: "long",
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
    };
    return date.toLocaleDateString("id-ID", options);
}

async function loadSalaryData() {
    try {
        showLoading();

        const response = await fetch("/api/salary/history");
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Gagal memuat data");
        }

        if (result.success && result.data) {
            salaryData = result.data;
            displaySalaryData(salaryData);
        } else {
            throw new Error(result.message || "Data tidak ditemukan");
        }
    } catch (error) {
        console.error("Error loading salary data:", error);
        showError(error.message);
    }
}

async function deleteSalary() {
    if (!selectedSalaryId) {
        showError("ID slip gaji tidak ditemukan");
        return;
    }

    try {
        const response = await fetch(`/api/salary/${selectedSalaryId}`, {
            method: "DELETE",
            headers: {
                Accept: "application/json",
            },
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Gagal menghapus data");
        }

        closeDeleteModal();
        showSuccess("Slip gaji berhasil dihapus");
        loadSalaryData();
    } catch (error) {
        console.error("Error deleting salary:", error);
        showError(error.message);
    }
}

function confirmDelete(salaryId, employeeName) {
    selectedSalaryId = salaryId;

    document.querySelector("#deleteConfirmationModal .modal-title").innerText =
        "Hapus Slip Gaji";
    document.querySelector(
        "#deleteConfirmationModal .modal-text p"
    ).innerHTML = `Yakin ingin menghapus slip gaji milik <strong>${employeeName}</strong>?<br>
        Tindakan ini tidak dapat dibatalkan.`;

    document.getElementById("deleteConfirmationModal").style.display = "flex";
}

function closeDeleteModal() {
    document.getElementById("deleteConfirmationModal").style.display = "none";
}

function showLoading() {
    document.getElementById("loading-indicator").classList.remove("d-none");
    document.getElementById("salary-table").classList.add("d-none");
    document.getElementById("error-message").classList.add("d-none");
    document.getElementById("success-message").classList.add("d-none");
    document.getElementById("empty-state").classList.add("d-none");
}

function showError(message) {
    document.getElementById("loading-indicator").classList.add("d-none");
    document.getElementById("salary-table").classList.add("d-none");
    document.getElementById("empty-state").classList.add("d-none");
    document.getElementById("success-message").classList.add("d-none");

    const errorMessage = document.getElementById("error-message");
    const errorText = document.getElementById("error-text");

    errorText.textContent = message;
    errorMessage.classList.remove("d-none");
}

function showSuccess(message) {
    document.getElementById("error-message").classList.add("d-none");

    const successMessage = document.getElementById("success-message");
    const successText = document.getElementById("success-text");

    successText.textContent = message;
    successMessage.classList.remove("d-none");

    setTimeout(() => {
        successMessage.classList.add("d-none");
    }, 3000);
}

function retryLoadData() {
    loadSalaryData();
}

document.addEventListener("DOMContentLoaded", function () {
    loadSalaryData();
    document
        .getElementById("confirmDeleteBtn")
        .addEventListener("click", deleteSalary);
    document
        .getElementById("refreshButton")
        .addEventListener("click", function () {
            loadSalaryData();
        });
});
