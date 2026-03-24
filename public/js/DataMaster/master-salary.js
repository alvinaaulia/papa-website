class SalaryManager {
    constructor() {
        this.API_BASE_URL = "/api/master-salary";
        this.GRADES_API_URL = "/api/salary-grades";
        this.currentDeleteId = null;
        this.salaryData = [];
        this.filteredSalaryData = [];

        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        this.bindEvents();
        this.initializeRupiahInput();
        this.loadGradeOptions();
        this.loadSalaries();
        this.showStoredFlash();
    }

    bindEvents() {
        document.getElementById("refreshButton")?.addEventListener("click", () => {
            this.loadSalaries();
        });

        document.getElementById("salarySearchInput")?.addEventListener("input", () => {
            this.applyFilters();
        });

        document.getElementById("salaryStatusFilter")?.addEventListener("change", () => {
            this.applyFilters();
        });

        document.getElementById("editSalaryForm")?.addEventListener("submit", (e) => {
            e.preventDefault();
            this.updateSalary();
        });

        document.getElementById("edit_salary_amount")?.addEventListener("input", () => {
            this.updatePPh21PreviewEdit();
        });

        document.getElementById("edit_salary_amount")?.addEventListener("blur", () => {
            this.updatePPh21PreviewEdit();
        });

        document.getElementById("salariesTableBody")?.addEventListener("click", (e) => {
            const button = e.target.closest("button");
            if (!button) return;

            if (button.classList.contains("btn-edit")) {
                this.openEditModal({
                    id: button.getAttribute("data-id"),
                    userId: button.getAttribute("data-user-id"),
                    userName: button.getAttribute("data-user-name"),
                    salaryAmount: button.getAttribute("data-salary"),
                    status: button.getAttribute("data-status"),
                    tierGrade: button.getAttribute("data-tier-grade") || "",
                    periodStart: button.getAttribute("data-period-start") || "",
                    periodEnd: button.getAttribute("data-period-end") || "",
                });
            }

            if (button.classList.contains("btn-delete")) {
                this.openDeleteModal(
                    button.getAttribute("data-id"),
                    button.getAttribute("data-user-name")
                );
            }
        });
    }

    showStoredFlash() {
        const flash = localStorage.getItem("masterSalaryFlash");
        if (!flash) return;

        try {
            const parsed = JSON.parse(flash);
            if (parsed.type === "success") {
                this.showSuccess(parsed.message || "Data berhasil disimpan");
            } else {
                this.showError(parsed.message || "Terjadi kesalahan");
            }
        } catch (error) {
            console.error("Failed to parse flash message", error);
        }

        localStorage.removeItem("masterSalaryFlash");
    }

    initializeRupiahInput() {
        const input = document.getElementById("edit_salary_amount");
        if (!input) return;

        const formatInput = () => {
            const rawValue = this.cleanRupiahFormat(input.value);
            if (!rawValue || rawValue === "0") {
                input.value = "";
                return;
            }

            input.value = Number(rawValue).toLocaleString("id-ID");
        };

        input.addEventListener("focus", () => {
            input.value = this.cleanRupiahFormat(input.value);
        });

        input.addEventListener("blur", formatInput);
        formatInput();
    }

    formatRupiah(amount) {
        const number = Number(amount || 0);
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(number);
    }

    cleanRupiahFormat(value) {
        if (!value) return "0";
        const clean = value.toString().replace(/[^\d]/g, "");
        return clean || "0";
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

    calculatePPh21(salary) {
        const grossSalary = Number(salary || 0);

        if (grossSalary <= 0) {
            return {
                grossSalary: 0,
                pph21: 0,
                netSalary: 0,
            };
        }

        const yearlySalary = grossSalary * 12;
        const ptkp = 54000000;
        const pkp = Math.max(0, yearlySalary - ptkp);

        let pph21Yearly = 0;

        if (pkp <= 60000000) {
            pph21Yearly = pkp * 0.05;
        } else if (pkp <= 250000000) {
            pph21Yearly = 60000000 * 0.05 + (pkp - 60000000) * 0.15;
        } else if (pkp <= 500000000) {
            pph21Yearly =
                60000000 * 0.05 +
                190000000 * 0.15 +
                (pkp - 250000000) * 0.25;
        } else {
            pph21Yearly =
                60000000 * 0.05 +
                190000000 * 0.15 +
                250000000 * 0.25 +
                (pkp - 500000000) * 0.3;
        }

        const pph21Monthly = Math.round(pph21Yearly / 12);

        return {
            grossSalary,
            pph21: pph21Monthly,
            netSalary: grossSalary - pph21Monthly,
        };
    }

    updatePPh21PreviewEdit() {
        const salaryInput = document.getElementById("edit_salary_amount");
        const preview = document.getElementById("editPph21Preview");
        if (!salaryInput || !preview) return;

        const salary = Number(this.cleanRupiahFormat(salaryInput.value));

        if (!salary || salary <= 0) {
            preview.innerHTML = "";
            return;
        }

        const result = this.calculatePPh21(salary);

        preview.innerHTML = `
            <div class="pph21-preview mt-3 p-3 border rounded bg-light">
                <h6 class="mb-3">Preview Perhitungan PPh 21</h6>
                <div class="d-flex justify-content-between">
                    <span>Gaji Pokok</span>
                    <strong>${this.formatRupiah(result.grossSalary)}</strong>
                </div>
                <div class="d-flex justify-content-between text-danger">
                    <span>PPh 21 / bulan</span>
                    <strong>- ${this.formatRupiah(result.pph21)}</strong>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between text-success">
                    <span>Take Home Pay</span>
                    <strong>${this.formatRupiah(result.netSalary)}</strong>
                </div>
            </div>
        `;
    }

    async loadGradeOptions() {
        try {
            const response = await fetch(`${this.GRADES_API_URL}?status=active`, {
                headers: {
                    Accept: "application/json",
                },
                cache: "no-store",
            });

            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.message || "Gagal memuat tier/grade");
            }

            this.gradeOptions = result.data || [];
            this.populateTierDropdown();
        } catch (error) {
            console.error("Error loading grade options:", error);
            this.gradeOptions = [];
            this.populateTierDropdown();
        }
    }

    populateTierDropdown(selected = "") {
        const select = document.getElementById("edit_tier_grade");
        if (!select) return;

        select.innerHTML = '<option value="">Pilih tier/grade</option>';

        (this.gradeOptions || []).forEach((grade) => {
            const option = document.createElement("option");
            option.value = grade.grade_code;
            option.textContent = `${grade.grade_code} - ${grade.grade_name}`;
            if (selected && selected === grade.grade_code) {
                option.selected = true;
            }
            select.appendChild(option);
        });

        if (selected && !Array.from(select.options).some((opt) => opt.value === selected)) {
            const fallback = document.createElement("option");
            fallback.value = selected;
            fallback.textContent = selected;
            fallback.selected = true;
            select.appendChild(fallback);
        }
    }

    async loadSalaries() {
        this.showLoading();
        this.hideError();

        try {
            const timestamp = Date.now();
            const response = await fetch(`${this.API_BASE_URL}?_t=${timestamp}`, {
                headers: {
                    Accept: "application/json",
                },
                cache: "no-store",
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || "Gagal memuat data master gaji");
            }

            this.salaryData = result.data || [];
            this.updateSummaryCards(this.salaryData);
            this.applyFilters();
        } catch (error) {
            this.showError(error.message || "Terjadi kesalahan saat memuat data");
            this.salaryData = [];
            this.filteredSalaryData = [];
            this.renderTable([]);
            this.showEmptyState();
            this.updateSummaryCards([]);
        } finally {
            this.hideLoading();
        }
    }

    applyFilters() {
        const search = (document.getElementById("salarySearchInput")?.value || "")
            .toLowerCase()
            .trim();
        const statusFilter = document.getElementById("salaryStatusFilter")?.value || "all";

        this.filteredSalaryData = this.salaryData.filter((item) => {
            const name = (item.user?.name || "").toLowerCase();
            const grade = (item.tier_grade || "").toLowerCase();
            const haystack = `${name} ${grade}`;

            const searchMatch = !search || haystack.includes(search);
            const statusMatch = statusFilter === "all" || item.status === statusFilter;

            return searchMatch && statusMatch;
        });

        this.renderTable(this.filteredSalaryData);

        if (this.filteredSalaryData.length === 0) {
            this.showEmptyState();
        } else {
            this.hideEmptyState();
        }
    }

    updateSummaryCards(data) {
        const total = data.length;
        const active = data.filter((item) => item.status === "active").length;
        const inactive = total - active;

        const latestItem = [...data]
            .filter((item) => item.updated_at)
            .sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))[0];

        document.getElementById("salaryTotalCount").textContent = String(total);
        document.getElementById("salaryActiveCount").textContent = String(active);
        document.getElementById("salaryInactiveCount").textContent = String(inactive);
        document.getElementById("salaryLatestUpdate").textContent = latestItem
            ? this.formatDateTime(latestItem.updated_at)
            : "-";
    }

    renderTable(data) {
        const tbody = document.getElementById("salariesTableBody");
        if (!tbody) return;

        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = "";
            return;
        }

        tbody.innerHTML = data
            .map((item, index) => {
                const statusBadge =
                    item.status === "active"
                        ? '<span class="badge badge-pill badge-success badge-custom">Aktif</span>'
                        : '<span class="badge badge-pill badge-danger badge-custom">Non Aktif</span>';

                const userNameRaw = item.user?.name || "-";
                const userName = this.escapeHtml(userNameRaw);
                const tierGrade = this.escapeHtml(item.tier_grade || "-");
                const period = this.formatPeriodRange(item.period_start, item.period_end);

                return `
                    <tr class="text-center overtime-data">
                        <td>${index + 1}</td>
                        <td class="text-left">${userName}</td>
                        <td><span class="badge badge-light border px-2 py-1">${tierGrade}</span></td>
                        <td class="text-right" style="padding: 1rem;">
                            <div>${this.formatRupiah(item.salary_amount)}</div>
                            <small class="text-danger">PPh 21: -${this.formatRupiah(item.pph21 || 0)}</small>
                            <div class="font-weight-bold text-success">${this.formatRupiah(item.net_salary || item.salary_amount)}</div>
                        </td>
                        <td>${period}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="d-flex justify-content-center flex-wrap" style="gap: .5rem;">
                                <button type="button"
                                    class="btn btn-secondary btn-sm btn-edit"
                                    data-id="${item.id_master_salary}"
                                    data-user-id="${this.escapeAttribute(item.id_user)}"
                                    data-user-name="${this.escapeAttribute(userNameRaw)}"
                                    data-tier-grade="${this.escapeAttribute(item.tier_grade || "")}"
                                    data-salary="${this.escapeAttribute(item.salary_amount)}"
                                    data-period-start="${this.escapeAttribute(item.period_start || "")}"
                                    data-period-end="${this.escapeAttribute(item.period_end || "")}"
                                    data-status="${this.escapeAttribute(item.status)}">
                                    Edit
                                </button>
                                <button type="button"
                                    class="btn btn-danger btn-sm btn-delete"
                                    data-id="${item.id_master_salary}"
                                    data-user-name="${this.escapeAttribute(userNameRaw)}">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            })
            .join("");
    }

    formatPeriodRange(start, end) {
        if (!start && !end) return "-";
        if (start && !end) return this.formatDate(start);
        if (!start && end) return this.formatDate(end);
        return `${this.formatDate(start)} s/d ${this.formatDate(end)}`;
    }

    formatDate(value) {
        if (!value) return "-";

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return "-";

        return date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    }

    formatDateTime(value) {
        if (!value) return "-";

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return "-";

        return date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    }

    openEditModal(payload) {
        document.getElementById("edit_id").value = payload.id || "";
        document.getElementById("edit_user_id").value = payload.userId || "";
        document.getElementById("edit_user_name").value = payload.userName || "";
        document.getElementById("edit_salary_amount").value = Number(
            this.cleanRupiahFormat(payload.salaryAmount || 0)
        ).toLocaleString("id-ID");
        document.getElementById("edit_period_start").value = payload.periodStart || "";
        document.getElementById("edit_period_end").value = payload.periodEnd || "";
        document.getElementById("edit_status").value = payload.status || "active";

        this.populateTierDropdown(payload.tierGrade || "");
        this.updatePPh21PreviewEdit();
        this.resetFormErrors();
        this.showModal("editSalaryModal");
    }

    async updateSalary() {
        const id = document.getElementById("edit_id")?.value;
        if (!id) return;

        this.resetFormErrors();

        const form = document.getElementById("editSalaryForm");
        const submitButton = document.getElementById("editSubmitButton");
        const formData = new FormData(form);

        const cleanSalary = this.cleanRupiahFormat(formData.get("salary_amount"));
        formData.set("salary_amount", cleanSalary);

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
                    this.displayFormErrors(result.errors);
                    throw new Error("Validasi gagal");
                }
                throw new Error(result.message || "Gagal menyimpan perubahan");
            }

            this.hideModal("editSalaryModal");
            this.showSuccess(result.message || "Data master gaji berhasil diperbarui");
            this.loadSalaries();
        } catch (error) {
            if (!error.message.includes("Validasi gagal")) {
                this.showError(error.message || "Gagal menyimpan perubahan");
            }
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = "Simpan Perubahan";
        }
    }

    displayFormErrors(errors) {
        Object.keys(errors).forEach((field) => {
            const errorElement = document.getElementById(`edit_${field}_error`);
            const inputElement = document.querySelector(`#editSalaryForm [name='${field}']`);

            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.style.display = "block";
            }

            if (inputElement) {
                inputElement.classList.add("is-invalid");
            }
        });
    }

    resetFormErrors() {
        document
            .querySelectorAll("#editSalaryForm .invalid-feedback")
            .forEach((element) => {
                element.style.display = "none";
                element.textContent = "";
            });

        document
            .querySelectorAll("#editSalaryForm .is-invalid")
            .forEach((element) => element.classList.remove("is-invalid"));
    }

    openDeleteModal(id, userName) {
        this.currentDeleteId = id;
        document.getElementById("deleteUserName").textContent = userName || "karyawan";
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
        this.deleteSalary(id);
    }

    async deleteSalary(id) {
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
                throw new Error(result.message || "Gagal menghapus data");
            }

            this.showSuccess(result.message || "Data berhasil dihapus");
            this.loadSalaries();
        } catch (error) {
            this.showError(error.message || "Gagal menghapus data");
        } finally {
            this.hideLoading();
        }
    }

    showModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if (!modalElement) return;

        modalElement.classList.add("show");
        modalElement.style.display = "block";
        modalElement.setAttribute("aria-hidden", "false");

        const backdrop = document.createElement("div");
        backdrop.className = "modal-backdrop fade show";
        backdrop.setAttribute("data-modal-backdrop", modalId);
        document.body.appendChild(backdrop);
        document.body.classList.add("modal-open");

        modalElement.querySelectorAll('[data-dismiss="modal"]').forEach((button) => {
            button.onclick = () => this.hideModal(modalId);
        });

        modalElement.onclick = (e) => {
            if (e.target === modalElement) {
                this.hideModal(modalId);
            }
        };
    }

    hideModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if (!modalElement) return;

        modalElement.classList.remove("show");
        modalElement.style.display = "none";

        document
            .querySelectorAll(`.modal-backdrop[data-modal-backdrop='${modalId}']`)
            .forEach((backdrop) => backdrop.remove());

        if (!document.querySelector(".modal.show")) {
            document.body.classList.remove("modal-open");
            document.body.style.overflow = "";
            document.body.style.paddingRight = "";
        }
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
        const successElement = document.getElementById("successMessage");
        const successText = document.getElementById("successText");
        const errorElement = document.getElementById("errorMessage");

        if (errorElement) errorElement.style.display = "none";

        if (successElement && successText) {
            successText.textContent = message;
            successElement.style.display = "block";

            setTimeout(() => this.hideSuccess(), 3500);
        }
    }

    hideSuccess() {
        const successElement = document.getElementById("successMessage");
        if (successElement) successElement.style.display = "none";
    }

    showError(message) {
        const errorElement = document.getElementById("errorMessage");
        const errorText = document.getElementById("errorText");
        const successElement = document.getElementById("successMessage");

        if (successElement) successElement.style.display = "none";

        if (errorElement && errorText) {
            errorText.textContent = message;
            errorElement.style.display = "block";

            setTimeout(() => this.hideError(), 5000);
        }
    }

    hideError() {
        const errorElement = document.getElementById("errorMessage");
        if (errorElement) errorElement.style.display = "none";
    }
}

let salaryManager;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        salaryManager = new SalaryManager();
        window.salaryManager = salaryManager;
    });
} else {
    salaryManager = new SalaryManager();
    window.salaryManager = salaryManager;
}
