class EmployeeData {
    constructor() {
        this.baseUrl = "/api/data-master/users";
        this.tableBody = document.querySelector("table tbody");
        this.init();
    }

    init() {
        this.loadEmployees();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Event listener untuk pencarian
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.addEventListener(
                "input",
                this.debounce(() => {
                    const searchValue = searchInput.value;
                    this.loadEmployees(searchValue);
                }, 300)
            );
        }

        // Event listener untuk filter
        const filterBtn = document.querySelector(".btn-footer.btn-success");
        if (filterBtn) {
            filterBtn.addEventListener("click", () => {
                this.applyFilters();
            });
        }
    }

    async loadEmployees(search = "") {
        try {
            this.showLoading();

            // Build URL dengan parameter search
            let url = this.baseUrl;
            if (search) {
                url += `?search=${encodeURIComponent(search)}`;
            }

            const response = await fetch(url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.status === "success") {
                this.renderEmployees(result.data);
            } else {
                throw new Error(
                    result.message || "Failed to load employee data"
                );
            }
        } catch (error) {
            console.error("Error loading employees:", error);
            this.showError("Gagal memuat data karyawan: " + error.message);
        }
    }

    renderEmployees(employees) {
        if (!this.tableBody) {
            console.error("Table body element not found");
            return;
        }

        if (!employees || employees.length === 0) {
            this.tableBody.innerHTML = `
                <tr class="text-center overtime-data">
                    <td colspan="6" class="py-4">
                        <div class="text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-people mb-2" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0 4z"/>
                            </svg>
                            <p>Tidak ada data karyawan</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        this.tableBody.innerHTML = employees
            .map((employee, index) => {
                // Handle data yang mungkin tidak lengkap
                const profile = employee.profiles || {};
                const status = employee.status || "active";
                const statusText = status === "active" ? "Aktif" : "Non Aktif";
                const statusClass =
                    status === "active" ? "badge-success" : "badge-danger";
                const nip = profile.nip || profile.employee_id || 0;
                const position = employee.role
                    ? this.capitalizeFirstLetter(employee.role)
                    : "-";

                return `
                <tr class="text-center overtime-data">
                    <td>${index + 1}</td>
                    <td>${nip}</td>
                    <td>${this.escapeHtml(employee.name || "N/A")}</td>
                    <td>${position}</td>
                    <td>
                        <span class="badge badge-pill ${statusClass} badge-custom">${statusText}</span>
                    </td>
                    <td>
                        <div class="overtime-action space-td">
                            <button type="button" class="btn btn-secondary btn-sm btn-edit"
                                data-employee-id="${employee.id}">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm btn-delete"
                                data-employee-id="${employee.id}"
                                data-employee-name="${this.escapeHtml(
                                    employee.name || "N/A"
                                )}">
                                Hapus
                            </button>
                            <button type="button" class="btn btn-primary btn-sm btn-detail"
                                data-employee-id="${employee.id}">
                                Rincian
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            })
            .join("");

        // Attach event listeners to buttons
        this.attachButtonEvents();
    }

    attachButtonEvents() {
        // Edit button
        document.querySelectorAll(".btn-edit").forEach((button) => {
            button.addEventListener("click", (e) => {
                const employeeId = e.target.getAttribute("data-employee-id");
                this.editEmployee(employeeId);
            });
        });

        // Delete button
        document.querySelectorAll(".btn-delete").forEach((button) => {
            button.addEventListener("click", (e) => {
                const employeeId = e.target.getAttribute("data-employee-id");
                const employeeName =
                    e.target.getAttribute("data-employee-name");
                this.openDeleteModal(employeeId, employeeName);
            });
        });

        // Detail button
        document.querySelectorAll(".btn-detail").forEach((button) => {
            button.addEventListener("click", (e) => {
                const employeeId = e.target.getAttribute("data-employee-id");
                this.viewEmployeeDetail(employeeId);
            });
        });
    }

    editEmployee(employeeId) {
        // Redirect to edit page - sesuaikan dengan route Anda
        window.location.href = `/hrd/employees/${employeeId}/edit`;
    }

    openDeleteModal(employeeId, employeeName) {
        // Update modal content
        const modal = document.getElementById("deleteModal");
        if (!modal) {
            console.error("Delete modal not found");
            return;
        }

        const modalText = modal.querySelector(".modal-text .modal-text");

        if (modalText) {
            modalText.innerHTML = `
                Yakin ingin menghapus data karyawan <strong>"${employeeName}"</strong>?<br>
                Tindakan ini tidak dapat dibatalkan.
            `;
        }

        // Store employee ID for deletion
        modal.setAttribute("data-employee-id", employeeId);

        // Show modal
        modal.classList.add("show");
    }

    async confirmDelete() {
        if (this.isDeleting) {
            console.log("Delete operation already in progress");
            return;
        }

        const modal = document.getElementById("deleteModal");
        const employeeId = modal.getAttribute("data-employee-id");
        const employeeName = modal.getAttribute("data-employee-name");

        if (!employeeId) {
            this.showError("ID karyawan tidak ditemukan");
            return;
        }

        try {
            this.isDeleting = true;
            this.showLoading();

            console.log("Deleting employee with ID:", employeeId);

            const response = await fetch(`${this.baseUrl}/${employeeId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            });

            console.log("Delete response status:", response.status);

            const result = await response.json();
            console.log("Delete response data:", result);

            if (response.status === 200 && result.status === "success") {
                this.showSuccess(
                    result.message ||
                        `Karyawan ${employeeName} berhasil dihapus`
                );
                this.closeDeleteModal();
                this.loadEmployees();
            } else {
                throw new Error(
                    result.message || `HTTP error! status: ${response.status}`
                );
            }
        } catch (error) {
            console.error("Error deleting employee:", error);
            this.showError("Gagal menghapus data karyawan: " + error.message);
        } finally {
            this.isDeleting = false;
            this.hideLoading();
        }
    }

    closeDeleteModal() {
        const modal = document.getElementById("deleteModal");
        if (modal) {
            modal.classList.remove("show");
            modal.removeAttribute("data-employee-id");
        }
    }

    viewEmployeeDetail(employeeId) {
        window.location.href = `/hrd/employee-details/${employeeId}`;
    }

    applyFilters() {
        // Implement filter logic here
        const statusFilter = document.querySelector('select[name="status"]');
        const employeeFilter = document.querySelector(
            'select[name="employee"]'
        );

        let filters = {};

        if (statusFilter && statusFilter.value) {
            filters.status = statusFilter.value;
        }

        if (employeeFilter && employeeFilter.value) {
            filters.employee = employeeFilter.value;
        }

        // You can extend this to include date filters
        const startDate = document.getElementById("startDate")?.value;
        const endDate = document.getElementById("endDate")?.value;

        if (startDate) filters.start_date = startDate;
        if (endDate) filters.end_date = endDate;

        this.loadEmployeesWithFilters(filters);
        this.closeFilterModal();
    }

    async loadEmployeesWithFilters(filters) {
        try {
            const queryString = new URLSearchParams(filters).toString();
            const url = `${this.baseUrl}?${queryString}`;

            const response = await fetch(url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.status === "success") {
                this.renderEmployees(result.data);
            } else {
                throw new Error(
                    result.message || "Failed to load filtered employee data"
                );
            }
        } catch (error) {
            console.error("Error loading filtered employees:", error);
            this.showError("Gagal memuat data dengan filter: " + error.message);
        }
    }

    closeFilterModal() {
        const modal = document.getElementById("filterModal");
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
        }
    }

    // Utility methods
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    escapeHtml(unsafe) {
        if (!unsafe) return "N/A";
        return unsafe
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    capitalizeFirstLetter(string) {
        if (!string) return "-";
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    showLoading() {
        // Add loading indicator to table
        if (this.tableBody) {
            this.tableBody.innerHTML = `
                <tr class="text-center overtime-data">
                    <td colspan="6" class="py-4">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="ms-2">Memuat data karyawan...</span>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    showSuccess(message) {
        this.showAlert(message, "success");
    }

    showError(message) {
        this.showAlert(message, "danger");
    }

    showAlert(message, type) {
        // Remove existing alerts first
        const existingAlerts = document.querySelectorAll(".alert");
        existingAlerts.forEach((alert) => alert.remove());

        // Create alert element
        const alertDiv = document.createElement("div");
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Insert alert at the top of the section
        const section = document.querySelector(".section-body");
        if (section) {
            section.insertBefore(alertDiv, section.firstChild);
        }

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

// Initialize when document is ready
document.addEventListener("DOMContentLoaded", function () {
    // Cek dulu apakah elemen yang diperlukan ada
    const tableBody = document.querySelector("table tbody");
    if (tableBody) {
        console.log("Initializing EmployeeData...");
        window.employeeData = new EmployeeData();
    } else {
        console.warn("Employee table not found on this page");
    }
});
