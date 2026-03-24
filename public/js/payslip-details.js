let proofModalInstance;
let currentSalaryData = null;

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

function getUrlParameter(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    const regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
    const results = regex.exec(location.search);
    return results === null
        ? ""
        : decodeURIComponent(results[1].replace(/\+/g, " "));
}

async function loadSalaryDetail() {
    const salaryId = getUrlParameter("id");

    console.log("URL:", window.location.href);
    console.log("Salary ID from URL:", salaryId);

    if (!salaryId || salaryId === "null" || salaryId === "") {
        showError(
            "ID slip gaji tidak ditemukan. Silakan kembali ke halaman riwayat."
        );
        return;
    }

    try {
        showLoading();

        const response = await fetch(`/api/salary/detail/${salaryId}`);
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Gagal memuat data");
        }

        if (result.success && result.data) {
            currentSalaryData = result.data;
            displaySalaryDetail(result.data);
        } else {
            throw new Error(result.message || "Data tidak ditemukan");
        }
    } catch (error) {
        console.error("Error loading salary detail:", error);
        showError(error.message);
    }
}

function displaySalaryDetail(data) {
    // Hide loading, show content
    document.getElementById("loading-indicator").classList.add("d-none");
    document.getElementById("detail-content").classList.remove("d-none");

    // Populate form fields
    document.getElementById("employee-name").value =
        data.employee_name || "N/A";
    document.getElementById("salary-amount").value = formatCurrency(
        data.salary_amount || 0
    );
    document.getElementById("salary-date").value = formatDate(data.salary_date);

    // Tampilkan detail gaji
    const grossSalary = Number(data.gross_salary || data.master_salary_amount || 0);
    const netSalary = Number(data.net_salary || data.salary_amount || 0);
    const totalDeductions = Number(
        data.total_deductions ||
        data.pph21 ||
        Math.max(grossSalary - netSalary, 0)
    );

    document.getElementById("gross-salary-display").textContent =
        formatCurrency(grossSalary);
    document.getElementById("pph21-display").textContent =
        formatCurrency(totalDeductions);
    document.getElementById("net-salary-display").textContent =
        formatCurrency(netSalary);

    // Handle transfer proof
    displayTransferProof(data.transfer_proof, data.employee_name);

    // Tambahkan event listener untuk button cetak
    document
        .getElementById("cetak-button")
        .addEventListener("click", function () {
            generatePayslipFromDetail(data);
        });
}

function displayTransferProof(proofPath, employeeName) {
    const proofActions = document.getElementById("proof-actions");
    const viewBtn = document.getElementById("view-proof-btn");

    if (proofPath) {
        // Show action button (hanya Lihat Bukti)
        proofActions.classList.remove("d-none");
    } else {
        proofActions.classList.add("d-none");
    }
}

// Fungsi untuk generate payslip dari detail (MENGGUNAKAN DESAIN SEBELUMNYA)
function generatePayslipFromDetail(data) {
    const formatRupiah = (number) => {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(number);
    };

    const terbilang = (angka) => {
        const bilangan = [
            "",
            "Satu",
            "Dua",
            "Tiga",
            "Empat",
            "Lima",
            "Enam",
            "Tujuh",
            "Delapan",
            "Sembilan",
            "Sepuluh",
        ];
        if (angka <= 10) return bilangan[angka];
        return (
            "# Terbilang: " +
            formatRupiah(angka).replace("Rp", "").trim() +
            " Rupiah #"
        );
    };

    const escapeHtml = (value) =>
        String(value ?? "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;");

    const normalizeItems = (items) =>
        Array.isArray(items)
            ? items
                  .map((item) => ({
                      code: String(item.code || ""),
                      name: String(item.name || item.code || "-"),
                      amount: Number(item.amount || 0),
                  }))
                  .filter((item) => item.amount !== 0)
            : [];

    const grossSalary = Number(data.gross_salary || data.master_salary_amount || 0);
    const netSalary = Number(data.net_salary || data.salary_amount || 0);
    const fallbackPph21 = Number(data.pph21 || 0);

    const earningsRaw = normalizeItems(data.earnings);
    const deductionsRaw = normalizeItems(data.deductions);

    const earnings = earningsRaw.filter((item) => item.code !== "BASIC_SALARY");
    const deductions = [...deductionsRaw];
    if (deductions.length === 0 && fallbackPph21 > 0) {
        deductions.push({
            code: "PPH21",
            name: "PPh 21",
            amount: fallbackPph21,
        });
    }

    let basicSalary = Number(
        data.calculation_facts?.employee?.basic_salary ||
            data.rule_engine_result?.summary?.basic_salary ||
            0
    );
    if (!basicSalary) {
        const extraEarning = earnings.reduce((sum, item) => sum + item.amount, 0);
        basicSalary = Math.max(grossSalary - extraEarning, 0);
    }

    const totalPenghasilan = grossSalary;
    const totalPotongan = Number(
        data.total_deductions ||
            deductions.reduce((sum, item) => sum + item.amount, 0) ||
            fallbackPph21
    );
    const penerimaanBersih = Number(data.net_salary || data.salary_amount || totalPenghasilan - totalPotongan);

    const earningRows = earnings.length
        ? earnings
              .map(
                  (item) => `
                <tr><td>${escapeHtml(item.name)}</td><td>=</td><td>${formatRupiah(item.amount)
                      .replace("Rp", "")
                      .trim()}</td></tr>
            `
              )
              .join("")
        : '<tr><td colspan="3">-</td></tr>';

    const deductionRows = deductions.length
        ? deductions
              .map(
                  (item) => `
                <tr><td>${escapeHtml(item.name)}</td><td>=</td><td>${formatRupiah(item.amount)
                      .replace("Rp", "")
                      .trim()}</td></tr>
            `
              )
              .join("")
        : '<tr><td colspan="3">-</td></tr>';

    const paymentDate = new Date(data.salary_date);
    const bulan = paymentDate.toLocaleDateString("id-ID", { month: "long" });
    const tahun = paymentDate.getFullYear();
    const tanggalCetak = new Date().toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });

    const slipHTML = `
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji Karyawan</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            margin: 50px;
        }
        hr { border-top: 2px solid #000; }
        .text-center { text-align: center; }
        .row { display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 3px 0; vertical-align: top; }
        .border-box {
            border: 1px solid #000;
            padding: 6px;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }
        small { font-size: 0.9rem; }
        .right { text-align: right; }
        @media print {
            body { margin: 20mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <h3><strong>CV Mascitra Teknolog Indonesia</strong></h3>
        <p>Tegal Besar Permai 1, Blk.AB No.9, Kec. Kaliwates, Kabupaten Jember, Jawa Timur 68132</p>
        <hr>
        <h4><strong>SLIP GAJI KARYAWAN</strong></h4>
    </div>

    <div style="margin-top: 30px;">
        <table>
            <tr><td>Periode</td><td>:</td><td><strong>${bulan} ${tahun}</strong></td></tr>
            <tr><td>Nama</td><td>:</td><td><strong>${
                data.employee_name || "N/A"
            }</strong></td></tr>
        </table>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div style="width: 48%;">
            <h4><strong>PENGHASILAN</strong></h4>
            <table>
                <tr><td>Gaji Pokok</td><td>=</td><td>${formatRupiah(basicSalary)
                    .replace("Rp", "")
                    .trim()}</td></tr>
                ${earningRows}
                <tr><td colspan="2"><strong>Total (A)</strong></td><td><strong>${formatRupiah(
                    totalPenghasilan
                )}</strong></td></tr>
            </table>
        </div>

        <div style="width: 48%;">
            <h4><strong>POTONGAN</strong></h4>
            <table>
                ${deductionRows}
                <tr><td colspan="2"><strong>Total (B)</strong></td><td><strong>${formatRupiah(
                    totalPotongan
                )}</strong></td></tr>
            </table>
        </div>
    </div>

    <div class="border-box">
        PENERIMAAN BERSIH (A-B) = ${formatRupiah(penerimaanBersih)}
    </div>

    <div class="text-center" style="margin-top: 10px;">
        <small>Terbilang: ${terbilang(penerimaanBersih)}</small>
    </div>

    <div class="right" style="margin-top: 50px;">
        <p>Jember, ${tanggalCetak}</p>
        <p><strong>CEO Mascitra.com</strong></p>
        <br><br>
        <p><strong><u>Citra Darma Wida</u></strong></p>
    </div>

    <div class="text-center no-print" style="margin-top: 20px;">
        <button onclick="window.close()" style="padding: 10px 20px; background: #dc3545; color: white; border: none; cursor: pointer;">
            Tutup Jendela
        </button>
    </div>
</body>
</html>
    `;

    const printWindow = window.open("", "_blank", "width=800,height=600");
    printWindow.document.open();
    printWindow.document.write(slipHTML);
    printWindow.document.close();
}



function showLoading() {
    document.getElementById("loading-indicator").classList.remove("d-none");
    document.getElementById("detail-content").classList.add("d-none");
    document.getElementById("error-message").classList.add("d-none");
}

function showError(message) {
    document.getElementById("loading-indicator").classList.add("d-none");
    document.getElementById("detail-content").classList.add("d-none");

    const errorMessage = document.getElementById("error-message");
    const errorText = document.getElementById("error-text");

    errorText.textContent = message;
    errorMessage.classList.remove("d-none");
}

function retryLoadData() {
    loadSalaryDetail();
}

// Load data when page is ready
document.addEventListener("DOMContentLoaded", function () {
    loadSalaryDetail();
});
