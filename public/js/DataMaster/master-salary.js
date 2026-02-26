class SalaryManager {
    constructor() {
        this.API_BASE_URL = "/api/master-salary";
        this.USERS_API_URL = "/api/employees";
        this.currentDeleteId = null;

        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        console.log("SalaryManager initializing...");
        this.bindEvents();
        this.loadUsers();
        this.loadSalaries();
        this.initializeRupiahInputs();
    }

    bindEvents() {
        console.log("Binding events...");

        document
            .getElementById("refreshButton")
            ?.addEventListener("click", () => {
                console.log("Refresh button clicked - forcing reload");
                this.forceReloadSalaries();
            });

        document
            .getElementById("addSalaryForm")
            ?.addEventListener("submit", (e) => {
                e.preventDefault();
                this.addSalary();
            });

        document
            .getElementById("editSalaryForm")
            ?.addEventListener("submit", (e) => {
                e.preventDefault();
                this.updateSalary();
            });

        const tableBody = document.getElementById("salariesTableBody");
        if (tableBody) {
            tableBody.addEventListener("click", (e) => {
                console.log("Table clicked:", e.target);

                const button = e.target.closest("button");
                if (!button) return;

                console.log("Button found:", button.classList);

                if (
                    button.classList.contains("btn-edit") ||
                    button.classList.contains("btn-secondary")
                ) {
                    e.preventDefault();
                    e.stopPropagation();

                    const id = button.getAttribute("data-id");
                    const userId = button.getAttribute("data-user-id");
                    const salaryAmount = button.getAttribute("data-salary");
                    const status = button.getAttribute("data-status");

                    console.log("Edit button clicked:", {
                        id,
                        userId,
                        salaryAmount,
                        status,
                    });
                    this.openEditModal(id, userId, salaryAmount, status);
                }

                if (
                    button.classList.contains("btn-delete") ||
                    button.classList.contains("btn-danger")
                ) {
                    e.preventDefault();
                    e.stopPropagation();

                    const id = button.getAttribute("data-id");
                    const userName = button.getAttribute("data-user-name");

                    console.log("Delete button clicked:", { id, userName });
                    this.openDeleteModal(id, userName);
                }
            });
        } else {
            console.error("salariesTableBody not found");
        }

        document
            .getElementById("confirmDeleteButton")
            ?.addEventListener("click", () => {
                this.confirmDelete();
            });

        document
            .getElementById("cancelDeleteButton")
            ?.addEventListener("click", () => {
                this.closeDeleteModal();
            });
    }

    /**
     * Format angka untuk ditampilkan di tabel (dengan Rp)
     */
    formatRupiah(amount) {
        if (amount === null || amount === undefined || amount === '' || amount === 0) {
            return 'Rp 0';
        }

        console.log("formatRupiah input:", amount, "type:", typeof amount);

        let numericAmount;
        if (typeof amount === 'string') {
            numericAmount = parseFloat(amount);
        } else {
            numericAmount = amount;
        }

        console.log("formatRupiah numericAmount:", numericAmount);

        if (isNaN(numericAmount)) {
            return 'Rp 0';
        }

        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(numericAmount);
    }

    /**
     * Format angka untuk input field (tanpa Rp, hanya angka dengan titik)
     */
    formatRupiahInput(amount) {
        if (amount === null || amount === undefined || amount === '' || amount === 0) {
            return '';
        }

        console.log("formatRupiahInput input:", amount, "type:", typeof amount);

        let numericAmount;
        if (typeof amount === 'string') {
            numericAmount = parseFloat(amount);
        } else {
            numericAmount = amount;
        }

        console.log("formatRupiahInput numericAmount:", numericAmount);

        if (isNaN(numericAmount)) {
            return '';
        }

        return numericAmount.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    /**
     * Bersihkan format rupiah (dengan titik) menjadi angka murni
     */
    cleanRupiahFormat(rupiahString) {
        if (!rupiahString) return "0";

        console.log("cleanRupiahFormat input:", rupiahString);
        const clean = rupiahString.toString().replace(/[^\d]/g, '');

        console.log("cleanRupiahFormat output:", clean);
        return clean || "0";
    }

    /**
     * Inisialisasi input rupiah dengan event listeners
    */
    editSalaryInputListener = null;

    initializeRupiahInputs() {
        document.querySelectorAll('.rupiah').forEach((element) => {
            if (element.value) {
                const cleanValue = this.cleanRupiahFormat(element.value);
                if (cleanValue && cleanValue !== '0') {
                    const numericValue = parseInt(cleanValue);
                    if (!isNaN(numericValue)) {
                        element.value = this.formatRupiahInput(numericValue);
                    }
                }
            }

            element.addEventListener('input', (e) => {
                const input = e.target;
                const cursorPosition = input.selectionStart;
                const originalValue = input.value;

                const rawValue = this.cleanRupiahFormat(originalValue);
                input.setAttribute('data-raw-value', rawValue);

                if (rawValue && rawValue !== '0') {
                    const numericValue = parseInt(rawValue);
                    if (!isNaN(numericValue)) {
                        const formatted = this.formatRupiahInput(numericValue);
                        input.value = formatted;

                        const newCursorPosition = cursorPosition + (formatted.length - originalValue.length);
                        input.setSelectionRange(newCursorPosition, newCursorPosition);
                    }
                }

                if (input.id === 'edit_salary_amount') {
                    this.updatePPh21PreviewEdit();
                }
            });

            element.addEventListener('blur', (e) => {
                const input = e.target;
                const rawValue = input.getAttribute('data-raw-value') || this.cleanRupiahFormat(input.value);

                if (rawValue && rawValue !== '0') {
                    const numericValue = parseInt(rawValue);
                    if (!isNaN(numericValue)) {
                        input.value = this.formatRupiahInput(numericValue);
                    }
                }

                if (input.id === 'edit_salary_amount') {
                    this.updatePPh21PreviewEdit();
                }
            });

            element.addEventListener('focus', (e) => {
                const input = e.target;
                const rawValue = this.cleanRupiahFormat(input.value);
                input.value = rawValue;
                input.setAttribute('data-raw-value', rawValue);
            });

            element.addEventListener('keypress', (e) => {
                if ([8, 46, 9, 27, 13].includes(e.keyCode) ||
                    (e.ctrlKey && [65, 67, 86, 88].includes(e.keyCode))) {
                    return;
                }

                if ((e.keyCode < 48 || e.keyCode > 57) &&
                    (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        });
    }

    async loadUsers() {
        try {
            const timestamp = new Date().getTime();
            const url = `${this.USERS_API_URL}?_t=${timestamp}`;

            const response = await fetch(url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "Cache-Control": "no-cache, no-store, must-revalidate",
                    Pragma: "no-cache",
                },
                cache: "no-store",
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(
                    result.message || `HTTP error! status: ${response.status}`
                );
            }

            if (
                result.status === "success" &&
                result.data &&
                result.data.length > 0
            ) {
                this.populateUserDropdown(result.data);
            } else {
                console.warn("No users data found");
                this.showError("Tidak ada data karyawan tersedia");
            }
        } catch (error) {
            console.error("Error loading users:", error);
            this.showError("Gagal memuat data karyawan: " + error.message);
        }
    }

    populateUserDropdown(users) {
        const addUserSelect = document.getElementById("add_user_id");
        const editUserSelect = document.getElementById("edit_user_id");

        if (addUserSelect) {
            addUserSelect.innerHTML =
                '<option value="" disabled selected>Pilih karyawan</option>';

            users.forEach((user) => {
                const option = document.createElement("option");
                option.value = user.id;
                option.textContent = user.name;
                addUserSelect.appendChild(option);
            });

            if (
                typeof $ !== "undefined" &&
                $(addUserSelect).hasClass("select2")
            ) {
                $(addUserSelect).trigger("change.select2");
            }
        }

        if (editUserSelect) {
            editUserSelect.innerHTML =
                '<option value="" disabled selected>Pilih karyawan</option>';

            users.forEach((user) => {
                const option = document.createElement("option");
                option.value = user.id;
                option.textContent = user.name;
                editUserSelect.appendChild(option);
            });

            if (
                typeof $ !== "undefined" &&
                $(editUserSelect).hasClass("select2")
            ) {
                $(editUserSelect).trigger("change.select2");
            }
        }
    }

    async loadSalaries() {
        this.showLoading();
        this.hideError();
        this.hideEmptyState();

        try {
            const tableBody = document.getElementById("salariesTableBody");
            if (tableBody) {
                tableBody.innerHTML = "";
            }

            const timestamp = new Date().getTime();
            const random = Math.random().toString(36).substring(7);
            const url = `${this.API_BASE_URL}?_t=${timestamp}&_r=${random}`;

            console.log("Loading salaries from:", url);

            const response = await fetch(url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "Cache-Control": "no-cache, no-store, must-revalidate",
                    Pragma: "no-cache",
                    Expires: "0",
                },
                cache: "no-store",
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(
                    result.message || `HTTP error! status: ${response.status}`
                );
            }

            console.log("Load salaries result:", result);

            if (result.success && result.data && result.data.length > 0) {
                this.renderTableData(result.data);
            } else {
                this.showEmptyState();
            }
        } catch (error) {
            console.error("Error loading salaries:", error);
            this.showError(
                error.message || "Terjadi kesalahan saat memuat data"
            );
        } finally {
            this.hideLoading();
        }
    }

    async forceReloadSalaries() {
        try {
            const tableBody = document.getElementById("salariesTableBody");
            if (tableBody) {
                tableBody.innerHTML = "";
            }

            this.showLoading();
            this.hideEmptyState();

            const timestamp = new Date().getTime();
            const random = Math.random().toString(36).substring(7);
            const url = `${this.API_BASE_URL}?_t=${timestamp}&_r=${random}`;

            console.log("Force reloading salaries from:", url);

            const response = await fetch(url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "Cache-Control": "no-cache, no-store, must-revalidate",
                    Pragma: "no-cache",
                },
                cache: "no-store",
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(
                    result.message || `HTTP error! status: ${response.status}`
                );
            }

            console.log("Force reload result:", result);

            if (result.success && result.data && result.data.length > 0) {
                this.renderTableData(result.data);
            } else {
                this.showEmptyState();
            }
        } catch (error) {
            console.error("Error in force reload:", error);
            this.showError(
                error.message || "Terjadi kesalahan saat memuat data"
            );
        } finally {
            this.hideLoading();
        }
    }

    renderTableData(data) {
        const tableBody = document.getElementById("salariesTableBody");
        if (!tableBody) {
            console.error("salariesTableBody not found during render");
            return;
        }

        console.log("Rendering table data:", data);

        if (data.length === 0) {
            this.showEmptyState();
            return;
        }

        const tableRows = data.map((item, index) =>
            this.createTableRow(item, index)
        );
        tableBody.innerHTML = tableRows.join("");

        setTimeout(() => {
            const buttons = tableBody.querySelectorAll("button");
            console.log("Buttons after render:", buttons.length);
            buttons.forEach((btn) => {
                console.log(
                    "Button:",
                    btn.classList,
                    btn.getAttribute("data-id")
                );
            });
        }, 100);
    }

    createTableRow(item, index) {
        const statusBadge =
            item.status === "active"
                ? '<span class="badge badge-pill badge-success badge-custom">Aktif</span>'
                : '<span class="badge badge-pill badge-danger badge-custom">Non Aktif</span>';

        const userName = item.user
            ? this.escapeHtml(item.user.name)
            : "Unknown User";

        const formattedGrossSalary = this.formatRupiah(item.salary_amount);
        const formattedPPh21 = item.pph21 ? this.formatRupiah(item.pph21) : 'Rp 0';
        const formattedNetSalary = item.net_salary ? this.formatRupiah(item.net_salary) : this.formatRupiah(item.salary_amount);

        const rawSalary = item.salary_amount.toString();

        return `
        <tr class="text-center overtime-data">
            <td>${index + 1}</td>
            <td class="text-left">${userName}</td>
            <td class="text-right" style="padding: 1rem;">
                <div>${formattedGrossSalary}</div>
                <small class="text-danger">PPh 21: -${formattedPPh21}</small>
                <div class="font-weight-bold text-success">${formattedNetSalary}</div>
            </td>
            <td>${statusBadge}</td>
            <td>
                <div class="text-center d-flex" style="gap: 0.5rem; justify-content: center">
                    <span>
                        <button type="button" class="btn btn-secondary btn-edit"
                            data-id="${item.id_master_salary}"
                            data-user-id="${item.id_user}"
                            data-salary="${rawSalary}"
                            data-status="${item.status}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </span>
                    <span>
                        <button type="button" class="btn btn-danger btn-delete"
                            data-id="${item.id_master_salary}"
                            data-user-name="${userName}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </span>
                </div>
            </td>
        </tr>
        `;
    }

    // perhitungan PPh 21
    calculatePPh21(salary) {
        const grossSalary = typeof salary === 'string' ?
            parseFloat(salary.replace(/[^\d]/g, '')) :
            salary;

        if (isNaN(grossSalary) || grossSalary <= 0) {
            return {
                grossSalary: 0,
                pph21: 0,
                netSalary: 0
            };
        }

        const yearlySalary = grossSalary * 12;
        const ptkp = 54000000;

        let pkp = Math.max(0, yearlySalary - ptkp);
        let pph21Yearly = 0;

        if (pkp > 0) {
            if (pkp <= 60000000) {
                pph21Yearly = pkp * 0.05;
            } else if (pkp <= 250000000) {
                pph21Yearly = (60000000 * 0.05) + ((pkp - 60000000) * 0.15);
            } else if (pkp <= 500000000) {
                pph21Yearly = (60000000 * 0.05) + (190000000 * 0.15) + ((pkp - 250000000) * 0.25);
            } else {
                pph21Yearly = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + ((pkp - 500000000) * 0.30);
            }
        }

        const pph21Monthly = Math.round(pph21Yearly / 12);
        const netSalary = grossSalary - pph21Monthly;

        return {
            grossSalary: grossSalary,
            pph21: pph21Monthly,
            netSalary: netSalary
        };
    }

    showPPh21Preview(grossSalary) {
        const calculation = this.calculatePPh21(grossSalary);

        return `
            <div class="pph21-preview mt-3 p-3 border rounded bg-light">
                <h6 class="mb-3">Preview Perhitungan PPh 21:</h6>
                <div class="row">
                    <div class="col-6">
                        <small>Gaji Pokok:</small>
                        <div class="font-weight-bold">${this.formatRupiah(calculation.grossSalary)}</div>
                    </div>
                    <div class="col-6">
                        <small>PPh 21/bulan:</small>
                        <div class="font-weight-bold text-danger">- ${this.formatRupiah(calculation.pph21)}</div>
                    </div>
                </div>
                <div class="row mt-2 pt-2 border-top">
                    <div class="col-12">
                        <small>Take Home Pay:</small>
                        <div class="font-weight-bold text-success">${this.formatRupiah(calculation.netSalary)}</div>
                    </div>
                </div>
            </div>
        `;
    }

    showPPh21Calculation(salaryValue) {
        const previewContainer = document.getElementById('pph21Preview');
        if (!previewContainer) return;

        const cleanSalary = this.cleanRupiahFormat(salaryValue);
        const numericSalary = parseInt(cleanSalary);

        if (isNaN(numericSalary) || numericSalary <= 0) {
            previewContainer.innerHTML = '';
            return;
        }

        const calculation = this.calculatePPh21(numericSalary);
        previewContainer.innerHTML = this.showPPh21Preview(numericSalary);
    }

    /**
     * Modal form add salary
     */
    async addSalary() {
        const form = document.getElementById("addSalaryForm");
        const formData = new FormData(form);

        const submitBtn = form.querySelector('button[type="submit"]');

        this.resetFormErrors("add");

        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i> Menghitung & Menyimpan...';

            const salaryAmount =
                document.getElementById("add_salary_amount").value;
            const cleanSalary = this.cleanRupiahFormat(salaryAmount);
            const pph21Calculation = this.calculatePPh21(cleanSalary);

            const confirmMessage = `
                Gaji Pokok: ${this.formatRupiah(pph21Calculation.grossSalary)}
                PPh 21/bulan: - ${this.formatRupiah(pph21Calculation.pph21)}
                Take Home Pay: ${this.formatRupiah(pph21Calculation.netSalary)}

                Apakah Anda yakin ingin menyimpan dengan perhitungan ini?
            `;

            if (!confirm(confirmMessage.replace(/<[^>]*>/g, ''))) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = "Simpan";
                return;
            }

            formData.set("salary_amount", cleanSalary);

            console.log("Sending data (add) with PPh21 calculation:", {
                grossSalary: cleanSalary,
                pph21: pph21Calculation.pph21,
                netSalary: pph21Calculation.netSalary
            });

            const response = await fetch(this.API_BASE_URL, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    "Cache-Control": "no-cache, no-store, must-revalidate",
                    Pragma: "no-cache",
                },
                body: formData,
            });

            const result = await response.json();
            console.log("Server response:", result);

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    console.log("Validation errors:", result.errors);
                    this.displayFormErrors(result.errors, "add");
                    throw new Error("Validasi gagal");
                }
                throw new Error(
                    result.message || `HTTP error! status: ${response.status}`
                );
            }

            if (result.success) {
                this.hideModal("addSalaryModal");
                this.showSuccess("Master salary berhasil dibuat dengan perhitungan PPh 21");

                await new Promise((resolve) => setTimeout(resolve, 100));
                await this.forceReloadSalaries();

                form.reset();
                this.initializeRupiahInputs();

                if (
                    typeof $ !== "undefined" &&
                    $("#add_user_id").hasClass("select2")
                ) {
                    $("#add_user_id").val("").trigger("change");
                }
            }
        } catch (error) {
            console.error("Error adding salary:", error);
            if (!error.message.includes("Validasi gagal")) {
                this.showError(error.message || "Gagal membuat master salary");
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = "Simpan";
        }
    }

    /**
     * Edit salary
     */
    async updateSalary() {
        const form = document.getElementById("editSalaryForm");
        const formData = new FormData(form);

        const id = document.getElementById("edit_id").value;

        const submitBtn = form.querySelector('button[type="submit"]');

        this.resetFormErrors("edit");

        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            const salaryAmount =
                document.getElementById("edit_salary_amount").value;
            const cleanSalary = this.cleanRupiahFormat(salaryAmount);
            formData.set("salary_amount", cleanSalary);

            console.log("Sending data (edit):", {
                id: id,
                salaryAmount: salaryAmount,
                cleanSalary: cleanSalary
            });

            const response = await fetch(`${this.API_BASE_URL}/${id}`, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    "Cache-Control": "no-cache, no-store, must-revalidate",
                    Pragma: "no-cache",
                },
                body: formData,
            });

            const result = await response.json();
            console.log("Server response (edit):", result);

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    console.log("Validation errors (edit):", result.errors);
                    this.displayFormErrors(result.errors, "edit");
                    throw new Error("Validasi gagal");
                }
                throw new Error(
                    result.message || `HTTP error! status: ${response.status}`
                );
            }

            if (result.success) {
                this.hideModal("editSalaryModal");
                this.showSuccess("Master salary berhasil diupdate");

                await new Promise((resolve) => setTimeout(resolve, 100));
                await this.forceReloadSalaries();
            }
        } catch (error) {
            console.error("Error updating salary:", error);
            if (!error.message.includes("Validasi gagal")) {
                this.showError(
                    error.message || "Gagal mengupdate master salary"
                );
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = "Simpan Perubahan";
        }
    }

    updatePPh21PreviewEdit() {
        const salaryInput = document.getElementById("edit_salary_amount");
        const previewContainer = document.getElementById('editPph21Preview');

        if (!salaryInput || !previewContainer) return;

        const salaryValue = salaryInput.value;
        const cleanSalary = this.cleanRupiahFormat(salaryValue);
        const numericSalary = parseInt(cleanSalary);

        if (isNaN(numericSalary) || numericSalary <= 0) {
            previewContainer.innerHTML = '';
            return;
        }

        const calculation = this.calculatePPh21(numericSalary);

        previewContainer.innerHTML = `
            <div class="pph21-preview mt-3 p-3 border rounded bg-light">
                <h6 class="mb-3">Preview Perhitungan PPh 21:</h6>
                <div class="row">
                    <div class="col-6">
                        <small>Gaji Pokok:</small>
                        <div class="font-weight-bold">${this.formatRupiah(calculation.grossSalary)}</div>
                    </div>
                    <div class="col-6">
                        <small>PPh 21/bulan:</small>
                        <div class="font-weight-bold text-danger">- ${this.formatRupiah(calculation.pph21)}</div>
                    </div>
                </div>
                <div class="row mt-2 pt-2 border-top">
                    <div class="col-12">
                        <small>Take Home Pay:</small>
                        <div class="font-weight-bold text-success">${this.formatRupiah(calculation.netSalary)}</div>
                    </div>
                </div>
            </div>
        `;
    }

    openEditModal(id, userId, salaryAmount, status) {
        console.log("=== Opening edit modal ===");
        console.log("Salary amount from button:", salaryAmount, "type:", typeof salaryAmount);

        document.getElementById("edit_id").value = id;

        const editUserSelect = document.getElementById("edit_user_id");
        if (editUserSelect) {
            editUserSelect.value = userId;
            if (
                typeof $ !== "undefined" &&
                $(editUserSelect).hasClass("select2")
            ) {
                $(editUserSelect).trigger("change");
            }
        }

        const formattedSalary = this.formatRupiahInput(salaryAmount);
        console.log("Formatted salary for input:", formattedSalary);

        document.getElementById("edit_salary_amount").value = formattedSalary;
        document.getElementById("edit_status").value = status;

        const rawSalary = this.cleanRupiahFormat(salaryAmount.toString());
        document.getElementById("edit_salary_amount").setAttribute('data-raw-value', rawSalary);

        this.updatePPh21PreviewEdit();

        const editSalaryInput = document.getElementById("edit_salary_amount");
        if (editSalaryInput) {
            editSalaryInput.removeEventListener('input', this.editSalaryInputListener);

            this.editSalaryInputListener = () => this.updatePPh21PreviewEdit();
            editSalaryInput.addEventListener('input', this.editSalaryInputListener);
        }

        this.resetFormErrors("edit");
        this.showModal("editSalaryModal");
    }

    /*
    ** Delete confirmation modal
    */
    openDeleteModal(id, userName) {
        console.log("Opening delete modal for:", { id, userName });
        this.currentDeleteId = id;

        const deleteUserNameElement = document.getElementById("deleteUserName");
        if (deleteUserNameElement) {
            deleteUserNameElement.textContent = userName;
        }

        this.showModal("deleteModal");
    }

    closeDeleteModal() {
        this.currentDeleteId = null;
        this.hideModal("deleteModal");
    }

    async deleteSalary(id) {
        try {
            this.showLoading();

            const formData = new FormData();
            formData.append("_method", "DELETE");

            const response = await fetch(`${this.API_BASE_URL}/${id}`, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    "Cache-Control": "no-cache, no-store, must-revalidate",
                    Pragma: "no-cache",
                },
                body: formData,
            });

            const result = await response.json();
            console.log("Delete response:", result);

            if (!response.ok) {
                throw new Error(
                    result.message || `HTTP error! status: ${response.status}`
                );
            }

            if (result.success) {
                this.showSuccess("Master salary berhasil dihapus");

                await new Promise((resolve) => setTimeout(resolve, 100));
                await this.forceReloadSalaries();
            }
        } catch (error) {
            console.error("Error deleting salary:", error);
            this.showError(error.message || "Gagal menghapus master salary");
        } finally {
            this.hideLoading();
        }
    }

    confirmDelete() {
        if (this.currentDeleteId) {
            console.log("Confirming delete for ID:", this.currentDeleteId);
            this.deleteSalary(this.currentDeleteId);
            this.closeDeleteModal();
        } else {
            console.error("No delete ID set!");
        }
    }

    showModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if (!modalElement) {
            console.error(`Modal element with id ${modalId} not found`);
            return;
        }

        modalElement.classList.add("show");
        modalElement.style.display = "block";
        modalElement.setAttribute("aria-hidden", "false");

        const backdrop = document.createElement("div");
        backdrop.className = "modal-backdrop fade show";
        document.body.appendChild(backdrop);
        document.body.classList.add("modal-open");

        const closeButtons = modalElement.querySelectorAll(
            '[data-dismiss="modal"]'
        );
        closeButtons.forEach((btn) => {
            btn.onclick = () => this.hideModal(modalId);
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

        const backdrops = document.querySelectorAll(".modal-backdrop");
        backdrops.forEach((backdrop) => {
            backdrop.remove();
        });

        document.body.classList.remove("modal-open");
        document.body.style.overflow = "";
        document.body.style.paddingRight = "";
    }

    displayFormErrors(errors, formType) {
        Object.keys(errors).forEach((field) => {
            const errorElement = document.getElementById(
                `${formType}${this.capitalizeFirst(field)}Error`
            );
            const inputElement = document.querySelector(`[name="${field}"]`);

            if (errorElement && inputElement) {
                errorElement.textContent = errors[field][0];
                errorElement.style.display = "block";
                inputElement.classList.add("is-invalid");
            }
        });
    }

    resetFormErrors(formType) {
        const errorElements = document.querySelectorAll(
            `#${formType}SalaryForm .invalid-feedback`
        );
        const inputElements = document.querySelectorAll(
            `#${formType}SalaryForm .is-invalid`
        );

        errorElements.forEach((element) => {
            element.style.display = "none";
        });

        inputElements.forEach((element) => {
            element.classList.remove("is-invalid");
        });
    }

    capitalizeFirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    escapeHtml(unsafe) {
        if (unsafe === null || unsafe === undefined) return "-";
        const text = unsafe.toString();
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    showLoading() {
        const loadingElement = document.getElementById("loadingIndicator");
        if (loadingElement) loadingElement.style.display = "block";
    }

    hideLoading() {
        const loadingElement = document.getElementById("loadingIndicator");
        if (loadingElement) loadingElement.style.display = "none";
    }

    hideError() {
        const errorElement = document.getElementById("errorMessage");
        if (errorElement) errorElement.style.display = "none";
    }

    showEmptyState() {
        const emptyState = document.getElementById("emptyState");
        if (emptyState) {
            emptyState.style.display = "block";

            const tableBody = document.getElementById("salariesTableBody");
            if (tableBody) {
                tableBody.innerHTML = "";
            }
        }
    }

    hideEmptyState() {
        const emptyState = document.getElementById("emptyState");
        if (emptyState) emptyState.style.display = "none";
    }

    showSuccess(message) {
        console.log("Success:", message);

        const successElement = document.getElementById("successMessage");
        const successText = document.getElementById("successText");
        const errorElement = document.getElementById("errorMessage");

        if (errorElement) {
            errorElement.style.display = "none";
        }

        if (successElement && successText) {
            successText.textContent = message;
            successElement.style.display = "block";

            setTimeout(() => {
                this.hideSuccess();
            }, 3000);
        } else {
            alert("✅ " + message);
        }
    }

    showError(message) {
        console.error("Error:", message);

        const errorElement = document.getElementById("errorMessage");
        const errorText = document.getElementById("errorText");
        const successElement = document.getElementById("successMessage");

        if (successElement) {
            successElement.style.display = "none";
        }

        if (errorElement && errorText) {
            errorText.textContent = message;
            errorElement.style.display = "block";

            setTimeout(() => {
                this.hideError();
            }, 5000);
        } else {
            alert("❌ " + message);
        }
    }

    hideSuccess() {
        const successElement = document.getElementById("successMessage");
        if (successElement) {
            successElement.style.display = "none";
        }
    }
}

let salaryManager;
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        console.log("DOM fully loaded, initializing SalaryManager...");
        salaryManager = new SalaryManager();
        window.salaryManager = salaryManager;
    });
} else {
    console.log("DOM already ready, initializing SalaryManager...");
    salaryManager = new SalaryManager();
    window.salaryManager = salaryManager;
}

setTimeout(() => {
    if (!salaryManager) {
        console.log("Fallback initialization...");
        salaryManager = new SalaryManager();
        window.salaryManager = salaryManager;
    }
}, 1000);
