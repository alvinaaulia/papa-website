class SalaryGradeManager {
    constructor() {
        this.API_BASE_URL = "/api/salary-grades";
        this.currentDeleteId = null;
        this.gradeData = [];
        this.filteredGradeData = [];

        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        this.bindEvents();
        this.loadGrades();
    }

    bindEvents() {
        document.getElementById("refreshButton")?.addEventListener("click", () => this.loadGrades());

        document.getElementById("gradeSearchInput")?.addEventListener("input", () => {
            this.applyFilters();
        });

        document.getElementById("gradeStatusFilter")?.addEventListener("change", () => {
            this.applyFilters();
        });

        document.getElementById("addGradeForm")?.addEventListener("submit", (e) => {
            e.preventDefault();
            this.addGrade();
        });

        document.getElementById("editGradeForm")?.addEventListener("submit", (e) => {
            e.preventDefault();
            this.updateGrade();
        });

        document.getElementById("gradesTableBody")?.addEventListener("click", (e) => {
            const button = e.target.closest("button");
            if (!button) return;

            if (button.classList.contains("btn-edit")) {
                this.openEditModal({
                    id: button.getAttribute("data-id"),
                    code: button.getAttribute("data-code"),
                    name: button.getAttribute("data-name"),
                    minScore: button.getAttribute("data-min-score"),
                    maxScore: button.getAttribute("data-max-score"),
                    baseSalary: button.getAttribute("data-base-salary"),
                    status: button.getAttribute("data-status"),
                    description: button.getAttribute("data-description") || "",
                });
            }

            if (button.classList.contains("btn-delete")) {
                this.openDeleteModal(
                    button.getAttribute("data-id"),
                    button.getAttribute("data-name")
                );
            }
        });
    }

    async loadGrades() {
        this.showLoading();
        this.hideError();

        try {
            const response = await fetch(`${this.API_BASE_URL}?_t=${Date.now()}`, {
                headers: {
                    Accept: "application/json",
                },
                cache: "no-store",
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || "Gagal memuat data tier/grade");
            }

            this.gradeData = result.data || [];
            this.applyFilters();
        } catch (error) {
            this.showError(error.message || "Terjadi kesalahan saat memuat data");
            this.gradeData = [];
            this.filteredGradeData = [];
            this.renderTable([]);
            this.showEmptyState();
            this.updateSummaryCards([], []);
        } finally {
            this.hideLoading();
        }
    }

    applyFilters() {
        const search = (document.getElementById("gradeSearchInput")?.value || "")
            .toLowerCase()
            .trim();
        const status = document.getElementById("gradeStatusFilter")?.value || "all";

        this.filteredGradeData = this.gradeData.filter((grade) => {
            const code = (grade.grade_code || "").toLowerCase();
            const name = (grade.grade_name || "").toLowerCase();
            const searchMatch = !search || `${code} ${name}`.includes(search);
            const statusMatch = status === "all" || grade.status === status;
            return searchMatch && statusMatch;
        });

        this.renderTable(this.filteredGradeData);
        this.updateSummaryCards(this.gradeData, this.filteredGradeData);

        if (this.filteredGradeData.length === 0) {
            this.showEmptyState();
        } else {
            this.hideEmptyState();
        }
    }

    updateSummaryCards(allData, filteredData) {
        const totalCount = allData.length;
        const activeCount = allData.filter((grade) => grade.status === "active").length;

        const activeGrades = allData.filter((grade) => grade.status === "active");
        const minScore = activeGrades.length
            ? Math.min(...activeGrades.map((grade) => Number(grade.min_score || 0)))
            : null;
        const maxScore = activeGrades.length
            ? Math.max(...activeGrades.map((grade) => Number(grade.max_score || 0)))
            : null;

        const averageSalary = filteredData.length
            ? filteredData.reduce((sum, grade) => sum + Number(grade.base_salary || 0), 0) /
              filteredData.length
            : 0;

        document.getElementById("gradeTotalCount").textContent = String(totalCount);
        document.getElementById("gradeActiveCount").textContent = String(activeCount);
        document.getElementById("gradeCoverage").textContent =
            minScore === null || maxScore === null ? "-" : `${minScore} - ${maxScore}`;
        document.getElementById("gradeAverageSalary").textContent =
            filteredData.length === 0 ? "-" : this.formatRupiah(averageSalary);
    }

    renderTable(data) {
        const tbody = document.getElementById("gradesTableBody");
        if (!tbody) return;

        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = "";
            return;
        }

        tbody.innerHTML = data
            .map((grade, index) => {
                const statusBadge =
                    grade.status === "active"
                        ? '<span class="badge badge-pill badge-success badge-custom">Aktif</span>'
                        : '<span class="badge badge-pill badge-danger badge-custom">Non Aktif</span>';

                const description = grade.description || "";
                const gradeCode = this.escapeHtml(grade.grade_code);
                const gradeName = this.escapeHtml(grade.grade_name);

                return `
                    <tr class="text-center overtime-data">
                        <td>${index + 1}</td>
                        <td><strong>${gradeCode}</strong></td>
                        <td class="text-left">${gradeName}</td>
                        <td>${grade.min_score} - ${grade.max_score}</td>
                        <td class="text-right">${this.formatRupiah(grade.base_salary)}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="d-flex justify-content-center flex-wrap" style="gap: .5rem;">
                                <button type="button"
                                    class="btn btn-secondary btn-sm btn-edit"
                                    data-id="${grade.id_salary_grade}"
                                    data-code="${this.escapeAttribute(grade.grade_code)}"
                                    data-name="${this.escapeAttribute(grade.grade_name)}"
                                    data-min-score="${grade.min_score}"
                                    data-max-score="${grade.max_score}"
                                    data-base-salary="${grade.base_salary}"
                                    data-status="${grade.status}"
                                    data-description="${this.escapeAttribute(description)}">
                                    Edit
                                </button>
                                <button type="button"
                                    class="btn btn-danger btn-sm btn-delete"
                                    data-id="${grade.id_salary_grade}"
                                    data-name="${this.escapeAttribute(grade.grade_name)}">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            })
            .join("");
    }

    async addGrade() {
        this.resetFormErrors("add");
        const form = document.getElementById("addGradeForm");
        const submitButton = document.getElementById("addSubmitButton");
        const formData = new FormData(form);

        try {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            const response = await fetch(this.API_BASE_URL, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
            });

            const result = await response.json();

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    this.displayFormErrors(result.errors, "add");
                    throw new Error("Validasi gagal");
                }
                throw new Error(result.message || "Gagal menyimpan tier/grade");
            }

            this.hideModal("addGradeModal");
            this.showSuccess(result.message || "Tier/grade berhasil ditambahkan");
            form.reset();
            this.loadGrades();
        } catch (error) {
            if (!error.message.includes("Validasi gagal")) {
                this.showError(error.message || "Gagal menyimpan tier/grade");
            }
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = "Simpan";
        }
    }

    openEditModal(data) {
        document.getElementById("edit_grade_id").value = data.id;
        document.getElementById("edit_grade_code").value = data.code;
        document.getElementById("edit_grade_name").value = data.name;
        document.getElementById("edit_min_score").value = data.minScore;
        document.getElementById("edit_max_score").value = data.maxScore;
        document.getElementById("edit_base_salary").value = Number(data.baseSalary || 0);
        document.getElementById("edit_status").value = data.status || "active";
        document.getElementById("edit_description").value = data.description || "";

        this.resetFormErrors("edit");
        this.showModal("editGradeModal");
    }

    async updateGrade() {
        this.resetFormErrors("edit");

        const form = document.getElementById("editGradeForm");
        const submitButton = document.getElementById("editSubmitButton");
        const id = document.getElementById("edit_grade_id").value;
        const formData = new FormData(form);

        try {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            const response = await fetch(`${this.API_BASE_URL}/${id}`, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
            });

            const result = await response.json();

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    this.displayFormErrors(result.errors, "edit");
                    throw new Error("Validasi gagal");
                }
                throw new Error(result.message || "Gagal memperbarui tier/grade");
            }

            this.hideModal("editGradeModal");
            this.showSuccess(result.message || "Tier/grade berhasil diperbarui");
            this.loadGrades();
        } catch (error) {
            if (!error.message.includes("Validasi gagal")) {
                this.showError(error.message || "Gagal memperbarui tier/grade");
            }
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = "Simpan Perubahan";
        }
    }

    openDeleteModal(id, gradeName) {
        this.currentDeleteId = id;
        document.getElementById("deleteGradeName").textContent = gradeName || "grade";
        this.showModal("deleteModal");
    }

    closeDeleteModal() {
        this.currentDeleteId = null;
        this.hideModal("deleteModal");
    }

    confirmDelete() {
        if (!this.currentDeleteId) return;

        const id = this.currentDeleteId;
        this.closeDeleteModal();
        this.deleteGrade(id);
    }

    async deleteGrade(id) {
        try {
            this.showLoading();

            const response = await fetch(`${this.API_BASE_URL}/${id}`, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || "Gagal menghapus tier/grade");
            }

            this.showSuccess(result.message || "Tier/grade berhasil dihapus");
            this.loadGrades();
        } catch (error) {
            this.showError(error.message || "Gagal menghapus tier/grade");
        } finally {
            this.hideLoading();
        }
    }

    displayFormErrors(errors, type) {
        Object.keys(errors).forEach((field) => {
            const errorElement = document.getElementById(`${type}_${field}_error`);
            const inputElement = document.querySelector(`#${type}GradeForm [name='${field}']`);

            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.style.display = "block";
            }

            if (inputElement) {
                inputElement.classList.add("is-invalid");
            }
        });
    }

    resetFormErrors(type) {
        document
            .querySelectorAll(`#${type}GradeForm .invalid-feedback`)
            .forEach((element) => {
                element.style.display = "none";
                element.textContent = "";
            });

        document
            .querySelectorAll(`#${type}GradeForm .is-invalid`)
            .forEach((element) => element.classList.remove("is-invalid"));
    }

    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.classList.add("show");
        modal.style.display = "block";

        const backdrop = document.createElement("div");
        backdrop.className = "modal-backdrop fade show";
        backdrop.setAttribute("data-modal-backdrop", modalId);
        document.body.appendChild(backdrop);
        document.body.classList.add("modal-open");

        modal.querySelectorAll('[data-dismiss="modal"]').forEach((button) => {
            button.onclick = () => this.hideModal(modalId);
        });

        modal.onclick = (e) => {
            if (e.target === modal) {
                this.hideModal(modalId);
            }
        };
    }

    hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.classList.remove("show");
        modal.style.display = "none";

        document
            .querySelectorAll(`.modal-backdrop[data-modal-backdrop='${modalId}']`)
            .forEach((backdrop) => backdrop.remove());

        if (!document.querySelector(".modal.show")) {
            document.body.classList.remove("modal-open");
            document.body.style.overflow = "";
            document.body.style.paddingRight = "";
        }
    }

    formatRupiah(amount) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(Number(amount || 0));
    }

    escapeHtml(text) {
        if (text === null || text === undefined) return "-";
        const div = document.createElement("div");
        div.textContent = String(text);
        return div.innerHTML;
    }

    escapeAttribute(value) {
        if (value === null || value === undefined) return "";
        return String(value)
            .replace(/&/g, "&amp;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }

    showLoading() {
        document.getElementById("loadingIndicator")?.style.setProperty("display", "block");
    }

    hideLoading() {
        document.getElementById("loadingIndicator")?.style.setProperty("display", "none");
    }

    showEmptyState() {
        document.getElementById("emptyState")?.style.setProperty("display", "block");
    }

    hideEmptyState() {
        document.getElementById("emptyState")?.style.setProperty("display", "none");
    }

    showSuccess(message) {
        const success = document.getElementById("successMessage");
        const successText = document.getElementById("successText");
        const error = document.getElementById("errorMessage");

        if (error) error.style.display = "none";

        if (success && successText) {
            successText.textContent = message;
            success.style.display = "block";
            setTimeout(() => this.hideSuccess(), 3500);
        }
    }

    hideSuccess() {
        document.getElementById("successMessage")?.style.setProperty("display", "none");
    }

    showError(message) {
        const error = document.getElementById("errorMessage");
        const errorText = document.getElementById("errorText");
        const success = document.getElementById("successMessage");

        if (success) success.style.display = "none";

        if (error && errorText) {
            errorText.textContent = message;
            error.style.display = "block";
            setTimeout(() => this.hideError(), 5000);
        }
    }

    hideError() {
        document.getElementById("errorMessage")?.style.setProperty("display", "none");
    }
}

let salaryGradeManager;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        salaryGradeManager = new SalaryGradeManager();
        window.salaryGradeManager = salaryGradeManager;
    });
} else {
    salaryGradeManager = new SalaryGradeManager();
    window.salaryGradeManager = salaryGradeManager;
}
