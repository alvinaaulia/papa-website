class MasterSalaryAssessment {
    constructor() {
        this.USERS_API_URL = "/api/employees";
        this.GRADES_API_URL = "/api/salary-grades?status=active";
        this.STORAGE_KEY = "pendingMasterSalaryAssessment";

        this.criteriaConfig = [
            {
                id: "score_skill_relevance",
                label: "Relevansi Skill dengan Posisi",
                weight: 0.25,
            },
            {
                id: "score_experience_relevance",
                label: "Relevansi Pengalaman Kerja dengan Posisi",
                weight: 0.25,
            },
            {
                id: "score_education_relevance",
                label: "Kecocokan Pendidikan dengan Posisi",
                weight: 0.15,
            },
            {
                id: "score_work_history_quality",
                label: "Kualitas Riwayat Pekerjaan",
                weight: 0.15,
            },
            {
                id: "score_certification",
                label: "Sertifikasi/Pelatihan Pendukung",
                weight: 0.1,
            },
            {
                id: "score_portfolio",
                label: "Kualitas Portofolio/Project Relevan",
                weight: 0.1,
            },
        ];

        this.gradeOptions = [];

        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => this.init());
        } else {
            this.init();
        }
    }

    async init() {
        this.bindEvents();
        await Promise.all([this.loadEmployees(), this.loadGrades()]);
        this.updatePreview();
    }

    bindEvents() {
        this.criteriaConfig.forEach((criteria) => {
            document.getElementById(criteria.id)?.addEventListener("change", () => {
                this.updatePreview();
            });
        });

        document
            .getElementById("education_level")
            ?.addEventListener("change", () => this.updatePreview());
        document
            .getElementById("years_experience")
            ?.addEventListener("input", () => this.updatePreview());

        document.getElementById("salaryAssessmentForm")?.addEventListener("submit", (e) => {
            e.preventDefault();
            this.handleSubmit();
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
            this.showError(error.message || "Gagal memuat data karyawan");
        }
    }

    async loadGrades() {
        try {
            const response = await fetch(this.GRADES_API_URL, {
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

            if (this.gradeOptions.length === 0) {
                this.showInfo(
                    "Belum ada tier/grade aktif. Silakan atur terlebih dahulu di halaman Tier/Grade Gaji."
                );
            }
        } catch (error) {
            this.showError(error.message || "Gagal memuat tier/grade");
            this.gradeOptions = [];
        }
    }

    populateEmployeeDropdown(users) {
        const select = document.getElementById("id_user");
        if (!select) return;

        select.innerHTML = '<option value="">Pilih karyawan</option>';

        users.forEach((user) => {
            const option = document.createElement("option");
            option.value = user.id;
            option.textContent = user.name;
            select.appendChild(option);
        });
    }

    getEducationBonus(level) {
        const map = {
            "SMA/SMK": 2,
            D3: 4,
            S1: 6,
            S2: 8,
            S3: 10,
        };

        return map[level] ?? 0;
    }

    getExperienceBonus(years) {
        const value = Number(years || 0);

        if (value <= 0) return 0;
        if (value <= 1) return 2;
        if (value <= 3) return 4;
        if (value <= 5) return 6;
        if (value <= 8) return 8;
        return 10;
    }

    computeScore() {
        const criteriaValues = {};
        let weightedNormalized = 0;
        let allCriteriaFilled = true;

        this.criteriaConfig.forEach((criteria) => {
            const value = Number(document.getElementById(criteria.id)?.value || 0);
            if (!value) {
                allCriteriaFilled = false;
            }

            criteriaValues[criteria.id] = {
                label: criteria.label,
                value,
                weight: criteria.weight,
                weighted_point: Number(((value / 5) * criteria.weight * 80).toFixed(2)),
            };

            weightedNormalized += (value / 5) * criteria.weight;
        });

        const educationLevel = document.getElementById("education_level")?.value || "";
        const yearsExperience = Number(document.getElementById("years_experience")?.value || 0);

        const educationBonus = this.getEducationBonus(educationLevel);
        const experienceBonus = this.getExperienceBonus(yearsExperience);

        const baseScore = Math.round(weightedNormalized * 80);
        const finalScore = Math.min(100, baseScore + educationBonus + experienceBonus);

        return {
            criteriaValues,
            baseScore,
            educationBonus,
            experienceBonus,
            finalScore,
            allCriteriaFilled,
            allCvInfoFilled: Boolean(educationLevel) && yearsExperience >= 0,
            educationLevel,
            yearsExperience,
        };
    }

    resolveGrade(score) {
        return (this.gradeOptions || []).find((grade) => {
            return Number(grade.min_score) <= score && Number(grade.max_score) >= score;
        });
    }

    updatePreview() {
        const scoreData = this.computeScore();
        const grade = this.resolveGrade(scoreData.finalScore);

        const canPreview = scoreData.allCriteriaFilled && scoreData.allCvInfoFilled;

        document.getElementById("previewScore").textContent = canPreview
            ? `${scoreData.finalScore}`
            : "-";

        document.getElementById("previewGrade").textContent =
            canPreview && grade
                ? `${grade.grade_code} - ${grade.grade_name}`
                : "-";

        document.getElementById("previewSalary").textContent =
            canPreview && grade
                ? this.formatRupiah(grade.base_salary)
                : "-";

        const breakdown = document.getElementById("previewBreakdown");
        if (!breakdown) return;

        if (!canPreview) {
            breakdown.textContent = "-";
            return;
        }

        breakdown.innerHTML = `
            <div>Skor Kecocokan CV: <b>${scoreData.baseScore}</b></div>
            <div>Bonus Pendidikan: <b>+${scoreData.educationBonus}</b></div>
            <div>Bonus Pengalaman: <b>+${scoreData.experienceBonus}</b></div>
        `;
    }

    validateForm() {
        const requiredInputs = [
            "id_user",
            "position_applied",
            "education_level",
            "years_experience",
            "relevant_skills",
            "education_history",
            "work_history",
        ];

        for (const id of requiredInputs) {
            const element = document.getElementById(id);
            if (!element || !String(element.value || "").trim()) {
                return `Field ${id.replaceAll("_", " ")} wajib diisi.`;
            }
        }

        const yearsExperience = Number(document.getElementById("years_experience")?.value || 0);
        if (yearsExperience < 0 || yearsExperience > 40) {
            return "Lama pengalaman kerja harus di antara 0 sampai 40 tahun.";
        }

        for (const criteria of this.criteriaConfig) {
            const value = Number(document.getElementById(criteria.id)?.value || 0);
            if (!value) {
                return "Semua skor kecocokan CV wajib diisi.";
            }
        }

        return null;
    }

    handleSubmit() {
        this.hideMessages();

        const validationMessage = this.validateForm();
        if (validationMessage) {
            this.showError(validationMessage);
            return;
        }

        const employeeSelect = document.getElementById("id_user");
        const userId = employeeSelect?.value;

        if (!userId) {
            this.showError("Silakan pilih karyawan terlebih dahulu.");
            return;
        }

        const scoreData = this.computeScore();
        const grade = this.resolveGrade(scoreData.finalScore);

        if (!grade) {
            this.showError(
                "Skor tidak menemukan tier/grade aktif. Periksa konfigurasi tier/grade terlebih dahulu."
            );
            return;
        }

        const payload = {
            id_user: userId,
            employee_name: employeeSelect.options[employeeSelect.selectedIndex]?.text || "",
            score: scoreData.finalScore,
            score_breakdown: {
                base_score: scoreData.baseScore,
                education_bonus: scoreData.educationBonus,
                experience_bonus: scoreData.experienceBonus,
            },
            criteria: scoreData.criteriaValues,
            cv_profile: {
                position_applied: document.getElementById("position_applied")?.value.trim() || "",
                education_level: document.getElementById("education_level")?.value || "",
                years_experience: Number(document.getElementById("years_experience")?.value || 0),
                relevant_skills: document.getElementById("relevant_skills")?.value.trim() || "",
                education_history: document.getElementById("education_history")?.value.trim() || "",
                work_history: document.getElementById("work_history")?.value.trim() || "",
            },
            grade: {
                id_salary_grade: grade.id_salary_grade,
                grade_code: grade.grade_code,
                grade_name: grade.grade_name,
                base_salary: Number(grade.base_salary || 0),
            },
            assessment_note: document.getElementById("assessment_note")?.value || "",
            generated_at: new Date().toISOString(),
        };

        sessionStorage.setItem(this.STORAGE_KEY, JSON.stringify(payload));
        window.location.href = "/director/data-master-payslip/confirmation";
    }

    formatRupiah(value) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(Number(value || 0));
    }

    showError(message) {
        const element = document.getElementById("assessmentError");
        if (!element) return;
        element.textContent = message;
        element.style.display = "block";
    }

    showInfo(message) {
        const element = document.getElementById("assessmentInfo");
        if (!element) return;
        element.textContent = message;
        element.style.display = "block";
    }

    hideMessages() {
        const error = document.getElementById("assessmentError");
        const info = document.getElementById("assessmentInfo");

        if (error) {
            error.style.display = "none";
            error.textContent = "";
        }

        if (info) {
            info.style.display = "none";
            info.textContent = "";
        }
    }
}

let masterSalaryAssessment;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        masterSalaryAssessment = new MasterSalaryAssessment();
        window.masterSalaryAssessment = masterSalaryAssessment;
    });
} else {
    masterSalaryAssessment = new MasterSalaryAssessment();
    window.masterSalaryAssessment = masterSalaryAssessment;
}
