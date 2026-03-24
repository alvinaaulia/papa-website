class MasterSalaryConfirmation {
    constructor() {
        this.API_BASE_URL = "/api/master-salary";
        this.USERS_API_URL = "/api/employees";
        this.STORAGE_KEY = "pendingMasterSalaryAssessment";
        this.payload = null;

        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        this.payload = this.readPayload();
        this.bindEvents();

        if (!this.payload) {
            this.showError("Data penilaian tidak ditemukan. Silakan isi form penilaian kembali.");
            document.getElementById("saveMasterSalaryButton")?.setAttribute("disabled", "disabled");
            return;
        }

        this.renderSummary();
        this.setDefaultValues();
        this.updatePPh21Preview();
        this.loadEmployees();
    }

    bindEvents() {
        document.getElementById("salaryConfirmationForm")?.addEventListener("submit", (e) => {
            e.preventDefault();
            this.submitMasterSalary();
        });

        const salaryInput = document.getElementById("salary_amount");
        if (salaryInput) {
            salaryInput.addEventListener("focus", () => {
                salaryInput.value = this.cleanRupiahFormat(salaryInput.value);
            });

            salaryInput.addEventListener("blur", () => {
                const raw = this.cleanRupiahFormat(salaryInput.value);
                salaryInput.value = raw && raw !== "0" ? Number(raw).toLocaleString("id-ID") : "";
                this.updatePPh21Preview();
            });

            salaryInput.addEventListener("input", () => this.updatePPh21Preview());
        }

        document.getElementById("id_user")?.addEventListener("change", () => {
            this.syncSelectedEmployeeSummary();
            this.clearFieldError("id_user");
        });
    }

    async loadEmployees() {
        try {
            const response = await fetch(`${this.USERS_API_URL}?_t=${Date.now()}`, {
                headers: {
                    Accept: "application/json",
                },
                cache: "no-store",
            });

            const result = await response.json();
            if (!response.ok || result.status !== "success") {
                throw new Error(result.message || "Gagal memuat data karyawan");
            }

            this.populateEmployeeDropdown(result.data || []);
        } catch (error) {
            this.populateEmployeeDropdown([]);
            this.showError(error.message || "Gagal memuat data karyawan");
        }
    }

    populateEmployeeDropdown(users) {
        const select = document.getElementById("id_user");
        if (!select) return;

        const selectedId = this.payload?.id_user || "";
        select.innerHTML = '<option value="">Pilih karyawan</option>';

        users.forEach((user) => {
            const option = document.createElement("option");
            option.value = user.id;
            option.textContent = user.name;
            select.appendChild(option);
        });

        if (selectedId) {
            const hasSelectedOption = Array.from(select.options).some(
                (option) => option.value === selectedId
            );

            if (!hasSelectedOption) {
                const fallbackOption = document.createElement("option");
                fallbackOption.value = selectedId;
                fallbackOption.textContent = this.payload?.employee_name || "Karyawan terpilih";
                select.appendChild(fallbackOption);
            }

            select.value = selectedId;
        }

        this.syncSelectedEmployeeSummary();
    }

    readPayload() {
        try {
            const raw = sessionStorage.getItem(this.STORAGE_KEY);
            if (!raw) return null;
            return JSON.parse(raw);
        } catch (error) {
            console.error("Failed to parse payload", error);
            return null;
        }
    }

    renderSummary() {
        const cvProfile = this.payload.cv_profile || {};
        const breakdown = this.payload.score_breakdown || {};

        this.setText("summaryEmployee", this.payload.employee_name || "-");
        this.setText("summaryPositionApplied", cvProfile.position_applied || "-");
        this.setText("summaryScore", `${this.payload.score} / 100`);
        this.setText("summaryBaseScore", breakdown.base_score ?? "-");
        this.setText(
            "summaryEducationBonus",
            breakdown.education_bonus !== undefined ? `+${breakdown.education_bonus}` : "-"
        );
        this.setText(
            "summaryExperienceBonus",
            breakdown.experience_bonus !== undefined ? `+${breakdown.experience_bonus}` : "-"
        );
        this.setText(
            "summaryGrade",
            `${this.payload.grade.grade_code} - ${this.payload.grade.grade_name}`
        );
        this.setText("summaryBaseSalary", this.formatRupiah(this.payload.grade.base_salary));

        this.setText("summaryEducationLevel", cvProfile.education_level || "-");
        this.setText(
            "summaryYearsExperience",
            cvProfile.years_experience !== undefined ? `${cvProfile.years_experience} tahun` : "-"
        );
        this.setText("summaryRelevantSkills", cvProfile.relevant_skills || "-");
        this.setText("summaryEducationHistory", cvProfile.education_history || "-");
        this.setText("summaryWorkHistory", cvProfile.work_history || "-");

        const criteriaBody = document.getElementById("criteriaSummaryBody");
        if (criteriaBody) {
            criteriaBody.innerHTML = Object.values(this.payload.criteria || {})
                .map((criteria) => {
                    const weightPercent = Math.round((Number(criteria.weight || 0) * 100));
                    return `
                        <tr>
                            <td>${criteria.label}</td>
                            <td class="text-center">${criteria.value}</td>
                            <td class="text-center">${weightPercent}%</td>
                        </tr>
                    `;
                })
                .join("");
        }

        const note = this.payload.assessment_note || "";
        if (note.trim()) {
            this.setText("summaryNote", note);
            const wrapper = document.getElementById("summaryNoteWrapper");
            if (wrapper) {
                wrapper.style.display = "block";
            }
        }
    }

    setText(id, value) {
        const element = document.getElementById(id);
        if (!element) return;
        element.textContent = value;
    }

    setDefaultValues() {
        const userSelect = document.getElementById("id_user");
        if (userSelect && this.payload?.id_user) {
            userSelect.value = this.payload.id_user;
        }

        const salaryInput = document.getElementById("salary_amount");
        if (salaryInput) {
            salaryInput.value = Number(this.payload.grade.base_salary || 0).toLocaleString("id-ID");
        }

        const now = new Date();
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

        document.getElementById("period_start").value = this.toDateInputValue(firstDay);
        document.getElementById("period_end").value = this.toDateInputValue(lastDay);
    }

    syncSelectedEmployeeSummary() {
        const select = document.getElementById("id_user");
        if (!select) return;

        const selectedOption = select.options[select.selectedIndex];
        const selectedId = select.value || "";
        const selectedName = selectedOption?.text || this.payload?.employee_name || "-";

        if (selectedId) {
            this.payload.id_user = selectedId;
            this.payload.employee_name = selectedName;
        }

        this.setText("summaryEmployee", selectedId ? selectedName : "-");
    }

    toDateInputValue(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        return `${year}-${month}-${day}`;
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

    updatePPh21Preview() {
        const preview = document.getElementById("confirmationPph21Preview");
        const salaryInput = document.getElementById("salary_amount");
        if (!preview || !salaryInput) return;

        const grossSalary = Number(this.cleanRupiahFormat(salaryInput.value));
        if (!grossSalary || grossSalary <= 0) {
            preview.innerHTML = "";
            return;
        }

        const result = this.calculatePPh21(grossSalary);

        preview.innerHTML = `
            <div class="pph21-preview mt-2 p-3 border rounded bg-light">
                <h6 class="mb-2">Simulasi PPh 21</h6>
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

    async submitMasterSalary() {
        this.hideMessages();

        if (!this.payload) {
            this.showError("Data penilaian tidak tersedia.");
            return;
        }

        const userId = document.getElementById("id_user")?.value || "";
        const salaryAmountRaw = this.cleanRupiahFormat(
            document.getElementById("salary_amount")?.value
        );
        const salaryAmount = Number(salaryAmountRaw);
        const periodStart = document.getElementById("period_start")?.value;
        const periodEnd = document.getElementById("period_end")?.value;
        const status = document.getElementById("status")?.value || "active";

        if (!userId) {
            this.setFieldError("id_user", "Silakan pilih karyawan.");
            this.showError("Karyawan wajib dipilih.");
            return;
        }

        if (!salaryAmount || salaryAmount <= 0) {
            this.showError("Nominal gaji tidak valid.");
            return;
        }

        if (!periodStart || !periodEnd) {
            this.showError("Periode mulai dan periode selesai wajib diisi.");
            return;
        }

        if (new Date(periodEnd) < new Date(periodStart)) {
            this.showError("Periode selesai tidak boleh lebih awal dari periode mulai.");
            return;
        }

        const submitButton = document.getElementById("saveMasterSalaryButton");
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        try {
            const formData = new FormData();
            formData.append("id_user", userId);
            formData.append("salary_amount", salaryAmount.toString());
            formData.append("status", status);
            formData.append("tier_grade", this.payload.grade.grade_code);
            formData.append("evaluation_score", String(this.payload.score));
            formData.append("period_start", periodStart);
            formData.append("period_end", periodEnd);
            formData.append("assessment_notes", this.buildAssessmentNotes());

            const response = await fetch(this.API_BASE_URL, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                const errorMessage = result.message || "Gagal menyimpan data master gaji";
                throw new Error(errorMessage);
            }

            sessionStorage.removeItem(this.STORAGE_KEY);
            localStorage.setItem(
                "masterSalaryFlash",
                JSON.stringify({
                    type: "success",
                    message: "Master gaji berhasil dibuat berdasarkan evaluasi CV.",
                })
            );

            this.showSuccess("Data berhasil disimpan. Mengalihkan ke halaman master gaji...");
            setTimeout(() => {
                window.location.href = "/director/data-master-payslip";
            }, 1200);
        } catch (error) {
            this.showError(error.message || "Gagal menyimpan data");
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = "Simpan Data Master Gaji";
        }
    }

    buildAssessmentNotes() {
        const criteriaText = Object.values(this.payload.criteria || {})
            .map((criteria) => `${criteria.label}: ${criteria.value} (bobot ${Math.round(Number(criteria.weight || 0) * 100)}%)`)
            .join("; ");

        const cv = this.payload.cv_profile || {};
        const breakdown = this.payload.score_breakdown || {};

        const cvSummary = [
            `Posisi: ${cv.position_applied || "-"}`,
            `Pendidikan: ${cv.education_level || "-"}`,
            `Pengalaman: ${cv.years_experience ?? "-"} tahun`,
            `Skill Relevan: ${cv.relevant_skills || "-"}`,
            `Riwayat Pendidikan: ${cv.education_history || "-"}`,
            `Riwayat Pekerjaan: ${cv.work_history || "-"}`,
        ].join(" | ");

        const scoreSummary = `Skor Akhir ${this.payload.score}/100 (Skor Kecocokan ${breakdown.base_score ?? 0}, Bonus Pendidikan ${breakdown.education_bonus ?? 0}, Bonus Pengalaman ${breakdown.experience_bonus ?? 0})`;

        const extraNote = this.payload.assessment_note?.trim();
        const base = `[Penilaian CV] ${scoreSummary}. ${cvSummary}. Detail Kriteria: ${criteriaText}`;

        return extraNote ? `${base}. Catatan Tambahan: ${extraNote}` : base;
    }

    formatRupiah(value) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(Number(value || 0));
    }

    cleanRupiahFormat(value) {
        if (!value) return "0";
        return value.toString().replace(/[^\d]/g, "") || "0";
    }

    setFieldError(field, message) {
        const input = document.getElementById(field);
        const error = document.getElementById(`${field}_error`);

        if (input) {
            input.classList.add("is-invalid");
        }

        if (error) {
            error.textContent = message;
            error.style.display = "block";
        }
    }

    clearFieldError(field) {
        const input = document.getElementById(field);
        const error = document.getElementById(`${field}_error`);

        if (input) {
            input.classList.remove("is-invalid");
        }

        if (error) {
            error.textContent = "";
            error.style.display = "none";
        }
    }

    showError(message) {
        const element = document.getElementById("confirmationError");
        if (!element) return;
        element.textContent = message;
        element.style.display = "block";
    }

    showSuccess(message) {
        const element = document.getElementById("confirmationSuccess");
        if (!element) return;
        element.textContent = message;
        element.style.display = "block";
    }

    hideMessages() {
        const errorElement = document.getElementById("confirmationError");
        const successElement = document.getElementById("confirmationSuccess");

        if (errorElement) {
            errorElement.style.display = "none";
            errorElement.textContent = "";
        }

        if (successElement) {
            successElement.style.display = "none";
            successElement.textContent = "";
        }
    }
}

let masterSalaryConfirmation;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        masterSalaryConfirmation = new MasterSalaryConfirmation();
        window.masterSalaryConfirmation = masterSalaryConfirmation;
    });
} else {
    masterSalaryConfirmation = new MasterSalaryConfirmation();
    window.masterSalaryConfirmation = masterSalaryConfirmation;
}
