class PositionManager {
    constructor() {
        this.API_BASE_URL = "/api/master-positions";
        this.currentDeleteId = null;

        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        console.log("PositionManager initializing...");
        this.bindEvents();
        this.loadPositions();
    }

    bindEvents() {
        console.log("Binding events...");

        document
            .getElementById("refreshButton")
            ?.addEventListener("click", () => {
                this.loadPositions();
            });

        document
            .getElementById("addPositionForm")
            ?.addEventListener("submit", (e) => {
                e.preventDefault();
                this.addPosition();
            });

        document
            .getElementById("editPositionForm")
            ?.addEventListener("submit", (e) => {
                e.preventDefault();
                this.updatePosition();
            });

        const tableBody = document.getElementById("positionsTableBody");
        if (tableBody) {
            tableBody.addEventListener("click", (e) => {
                console.log("Table clicked:", e.target);

                const button = e.target.closest("button");
                if (!button) return;

                console.log("Button found:", button.classList);

                if (button.classList.contains("btn-secondary")) {
                    e.preventDefault();
                    e.stopPropagation();

                    const id = button.getAttribute("data-id");
                    const name = button.getAttribute("data-name");
                    const status = button.getAttribute("data-status");

                    console.log("Edit button clicked:", { id, name, status });
                    this.openEditModal(id, name, status);
                }

                if (button.classList.contains("btn-danger")) {
                    e.preventDefault();
                    e.stopPropagation();

                    const id = button.getAttribute("data-id");
                    const name = button.getAttribute("data-name");

                    console.log("Delete button clicked:", { id, name });
                    this.openDeleteModal(id, name);
                }
            });
        } else {
            console.error("positionsTableBody not found");
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

        document
            .getElementById("refreshButton")
            ?.addEventListener("click", () => {
                console.log("Refresh button clicked");
                this.forceReloadPositions();
            });
    }

    async loadPositions() {
        this.showLoading();
        this.hideError();
        this.hideEmptyState();

        try {
            const tableBody = document.getElementById("positionsTableBody");
            if (tableBody) {
                tableBody.innerHTML = "";
            }

            const timestamp = new Date().getTime();
            const random = Math.random().toString(36).substring(7);
            const url = `${this.API_BASE_URL}?_t=${timestamp}&_r=${random}`;

            console.log("Loading positions from:", url);

            const response = await fetch(url, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
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

            console.log("Load positions result:", result);

            if (result.success && result.data && result.data.length > 0) {
                this.renderTableData(result.data);
            } else {
                this.showEmptyState();
            }
        } catch (error) {
            console.error("Error loading positions:", error);
            this.showError(
                error.message || "Terjadi kesalahan saat memuat data"
            );
        } finally {
            this.hideLoading();
        }
    }

    async forceReloadPositions() {
        try {
            const tableBody = document.getElementById("positionsTableBody");
            if (tableBody) {
                tableBody.innerHTML = "";
            }

            this.showLoading();
            this.hideEmptyState();

            const timestamp = new Date().getTime();
            const random = Math.random().toString(36).substring(7);
            const url = `${this.API_BASE_URL}?_t=${timestamp}&_r=${random}`;

            console.log("Force reloading from:", url);

            const response = await fetch(url, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    "Cache-Control": "no-cache, no-store, must-revalidate",
                    Pragma: "no-cache",
                    Expires: "0",
                },
                cache: "no-store",
            });

            if (response.headers.get("cache-control")) {
                console.log(
                    "Cache control header:",
                    response.headers.get("cache-control")
                );
            }

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

    async addPosition() {
        const form = document.getElementById("addPositionForm");
        const formData = new FormData(form);

        const submitBtn = form.querySelector('button[type="submit"]');
        const submitText = document.getElementById("addSubmitText");
        const loadingSpinner = document.getElementById("addLoadingSpinner");

        this.resetFormErrors("add");

        try {
            submitBtn.disabled = true;
            submitText.style.display = "none";
            loadingSpinner.style.display = "inline-block";

            console.log("Sending data:");
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            const response = await fetch(this.API_BASE_URL, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
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
                this.hideModal("addPositionModal");
                this.showSuccess("Jabatan berhasil ditambahkan");
                this.loadPositions();
                form.reset();
            }
        } catch (error) {
            console.error("Error adding position:", error);
            if (!error.message.includes("Validasi gagal")) {
                this.showError(error.message || "Gagal menambahkan jabatan");
            }
        } finally {
            submitBtn.disabled = false;
            submitText.style.display = "inline-block";
            loadingSpinner.style.display = "none";
        }
    }

    renderTableData(data) {
        const tableBody = document.getElementById("positionsTableBody");
        if (!tableBody) {
            console.error("positionsTableBody not found during render");
            return;
        }

        console.log("Rendering table data:", data);
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
            item.status === "onsite"
                ? '<span class="badge badge-pill badge-success badge-custom">On Site</span>'
                : item.status === "online"
                ? '<span class="badge badge-pill badge-primary badge-custom">Online</span>'
                : item.status === "hybrid"
                ? '<span class="badge badge-pill badge-warning badge-custom">Hybrid</span>'
                : '<span class="badge badge-pill badge-secondary badge-custom">Unknown</span>';

        const escapedName = this.escapeHtml(item.position_name);

        console.log(`Creating row ${index}:`, {
            id: item.id_master_position,
            name: escapedName,
            status: item.status,
        });

        return `
            <tr class="text-center">
                <td>${index + 1}</td>
                <td style="text-align: left">${escapedName}</td>
                <td>${statusBadge}</td>
                <td>
                    <div class="overtime-action" style="padding: 1rem 0">
                        <button class="btn btn-secondary btn-custom"
                                data-id="${item.id_master_position}"
                                data-name="${escapedName}"
                                data-status="${item.status}">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-custom"
                                data-id="${item.id_master_position}"
                                data-name="${escapedName}">
                            Hapus
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    async updatePosition() {
        const form = document.getElementById("editPositionForm");
        const formData = new FormData(form);
        const id = document.getElementById("editPositionId").value;

        const submitBtn = form.querySelector('button[type="submit"]');
        const submitText = document.getElementById("editSubmitText");
        const loadingSpinner = document.getElementById("editLoadingSpinner");

        this.resetFormErrors("edit");

        try {
            submitBtn.disabled = true;
            submitText.style.display = "none";
            loadingSpinner.style.display = "inline-block";

            console.log("Sending data (edit):");
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            const response = await fetch(`${this.API_BASE_URL}/${id}`, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
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
                this.hideModal("editPositionModal");
                this.showSuccess("Jabatan berhasil diupdate");
                this.loadPositions();
            }
        } catch (error) {
            console.error("Error updating position:", error);
            if (!error.message.includes("Validasi gagal")) {
                this.showError(error.message || "Gagal mengupdate jabatan");
            }
        } finally {
            submitBtn.disabled = false;
            submitText.style.display = "inline-block";
            loadingSpinner.style.display = "none";
        }
    }

    async deletePosition(id) {
        try {
            this.showLoading();

            const formData = new FormData();
            const response = await fetch(`${this.API_BASE_URL}/${id}`, {
                method: "DELETE",
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
                this.showSuccess("Jabatan berhasil dihapus");

                await new Promise((resolve) => setTimeout(resolve, 100));
                await this.forceReloadPositions();
            }
        } catch (error) {
            console.error("Error deleting position:", error);
            this.showError(error.message || "Gagal menghapus jabatan");
        } finally {
            this.hideLoading();
        }
    }

    openEditModal(id, positionName, status) {
        console.log("Opening edit modal:", { id, positionName, status });

        document.getElementById("editPositionId").value = id;
        document.getElementById("editPositionName").value = positionName;
        document.getElementById("editStatus").value = status;

        this.resetFormErrors("edit");

        this.showModal("editPositionModal");
    }

    openDeleteModal(id, positionName) {
        this.currentDeleteId = id;
        document.getElementById("deletePositionName").textContent =
            positionName;
        this.showModal("deleteModal");
    }

    closeDeleteModal() {
        this.currentDeleteId = null;
        this.hideModal("deleteModal");
    }

    confirmDelete() {
        if (this.currentDeleteId) {
            console.log("Confirming delete for ID:", this.currentDeleteId);
            this.deletePosition(this.currentDeleteId);
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
            `#${formType}PositionForm .invalid-feedback`
        );
        const inputElements = document.querySelectorAll(
            `#${formType}PositionForm .is-invalid`
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

    // showError(message) {
    //     const errorElement = document.getElementById("errorMessage");
    //     const errorText = document.getElementById("errorText");

    //     if (errorElement && errorText) {
    //         errorText.textContent = message;
    //         errorElement.style.display = "block";
    //     }
    // }

    hideError() {
        const errorElement = document.getElementById("errorMessage");
        if (errorElement) errorElement.style.display = "none";
    }

    showEmptyState() {
        const emptyState = document.getElementById("emptyState");
        if (emptyState) emptyState.style.display = "block";
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

    hideError() {
        const errorElement = document.getElementById("errorMessage");
        if (errorElement) {
            errorElement.style.display = "none";
        }
    }
}

let positionManager;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
        console.log("DOM fully loaded, initializing PositionManager...");
        positionManager = new PositionManager();
        window.positionManager = positionManager;
    });
} else {
    console.log("DOM already ready, initializing PositionManager...");
    positionManager = new PositionManager();
    window.positionManager = positionManager;
}

setTimeout(() => {
    if (!positionManager) {
        console.log("Fallback initialization...");
        positionManager = new PositionManager();
        window.positionManager = positionManager;
    }
}, 1000);
