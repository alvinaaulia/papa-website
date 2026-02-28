class EmployeeModal {
    constructor() {
        this.initModalEvents();
    }

    initModalEvents() {
        // Delete modal events
        this.setupDeleteModal();

        // Filter modal events
        this.setupFilterModal();
    }

    setupDeleteModal() {
        const deleteModal = document.getElementById("deleteModal");

        if (deleteModal) {
            // Close modal when clicking outside
            deleteModal.addEventListener("click", (e) => {
                if (e.target === deleteModal) {
                    this.closeDeleteModal();
                }
            });

            // Close button
            const closeBtn = deleteModal.querySelector(".modal-close");
            if (closeBtn) {
                closeBtn.addEventListener("click", () => {
                    this.closeDeleteModal();
                });
            }

            // Cancel button
            const cancelBtn = deleteModal.querySelector(".btn-secondary");
            if (cancelBtn) {
                cancelBtn.addEventListener("click", () => {
                    this.closeDeleteModal();
                });
            }

            // Confirm delete button
            const confirmBtn = deleteModal.querySelector(".btn-danger");
            if (confirmBtn) {
                confirmBtn.addEventListener("click", () => {
                    if (
                        window.employeeData &&
                        typeof window.employeeData.confirmDelete === "function"
                    ) {
                        window.employeeData.confirmDelete();
                    } else {
                        console.error("EmployeeData not initialized");
                    }
                });
            }
        } else {
            console.warn("Delete modal not found");
        }
    }

    setupFilterModal() {
        const filterModal = document.getElementById("filterModal");

        if (filterModal) {
            // Reset button
            const resetBtn = filterModal.querySelector(".text-danger");
            if (resetBtn) {
                resetBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    this.resetFilters();
                });
            }

            // Cancel button
            const cancelBtn = filterModal.querySelector(".btn-danger");
            if (cancelBtn) {
                cancelBtn.addEventListener("click", () => {
                    this.closeFilterModal();
                });
            }

            // Apply filter button
            const applyBtn = filterModal.querySelector(".btn-success");
            if (applyBtn) {
                applyBtn.addEventListener("click", () => {
                    if (
                        window.employeeData &&
                        typeof window.employeeData.applyFilters === "function"
                    ) {
                        window.employeeData.applyFilters();
                    } else {
                        console.error("EmployeeData not initialized");
                    }
                });
            }
        } else {
            console.warn("Filter modal not found");
        }
    }

    closeDeleteModal() {
        if (
            window.employeeData &&
            typeof window.employeeData.closeDeleteModal === "function"
        ) {
            window.employeeData.closeDeleteModal();
        } else {
            // Fallback
            const modal = document.getElementById("deleteModal");
            if (modal) {
                modal.classList.remove("show");
            }
        }
    }

    closeFilterModal() {
        const modal = document.getElementById("filterModal");
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            } else {
                // Fallback untuk Bootstrap 5
                const bsModal = new bootstrap.Modal(modal);
                bsModal.hide();
            }
        }
    }

    resetFilters() {
        // Reset date inputs
        const startDate = document.getElementById("startDate");
        const endDate = document.getElementById("endDate");

        if (startDate) startDate.value = "";
        if (endDate) endDate.value = "";

        // Reset select inputs
        const selects = document.querySelectorAll("#filterModal select");
        selects.forEach((select) => {
            select.value = "";
            // Trigger change event untuk Select2 jika ada
            if (typeof jQuery !== "undefined" && jQuery.fn.select2) {
                $(select).trigger("change");
            }
        });
    }

    // Static methods untuk inline onclick handlers
    static openFilterModal() {
        const modalEl = document.getElementById("filterModal");
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }
}

// Initialize when document is ready
document.addEventListener("DOMContentLoaded", function () {
    console.log("Initializing EmployeeModal...");
    window.employeeModal = new EmployeeModal();
});
