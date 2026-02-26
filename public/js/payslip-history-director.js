let fotoModalInstance;
let salaryData = [];

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

function displaySalaryData(data) {
    const tableBody = document.getElementById("salary-table-body");
    const tableContainer = document.getElementById("salary-table");
    const emptyState = document.getElementById("empty-state");
    const loadingIndicator = document.getElementById("loading-indicator");

    loadingIndicator.classList.add("d-none");

    if (data.length === 0) {
        tableContainer.classList.add("d-none");
        emptyState.classList.remove("d-none");
        return;
    }

    tableContainer.classList.remove("d-none");
    emptyState.classList.add("d-none");

    tableBody.innerHTML = "";

    data.forEach((salary, index) => {
        const row = document.createElement("tr");
        row.className = "text-center";

        const formattedDate = formatDate(salary.salary_date);
        const formattedAmount = formatCurrency(salary.salary_amount);

        const hasProof =
            salary.transfer_proof !== null && salary.transfer_proof !== "";
        const proofButtonText = hasProof ? "Buka foto" : "Tidak ada";
        const proofButtonClass = hasProof ? "evidence" : "text-muted";
        const proofButtonStyle = hasProof
            ? "cursor: pointer; text-decoration: none"
            : "cursor: default; text-decoration: none";

        row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${salary.employee_name}</td>
                    <td>${formattedDate}</td>
                    <td>${formattedAmount}</td>
                    <td>
                        <a href="#" class="${proofButtonClass}"
                           style="${proofButtonStyle}"
                           ${
                               hasProof
                                   ? `onclick="openFotoModal('${salary.transfer_proof}', '${salary.employee_name}')"`
                                   : 'onclick="return false;"'
                           }>
                            ${proofButtonText}
                        </a>
                    </td>
                    <td>
                        <button type="button" class="btn btn-info btn-cetak"
                                onclick="handleCetakClick(${index})">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </td>
                `;

        tableBody.appendChild(row);
    });
}

// PERBAIKAN: Fungsi untuk menangani klik button cetak
function handleCetakClick(index) {
    if (salaryData && salaryData[index]) {
        // Ambil data detail untuk mendapatkan informasi gaji lengkap
        fetchSalaryDetailForPrint(salaryData[index].id_salary);
    } else {
        console.error("Data tidak ditemukan untuk index:", index);
        alert("Data tidak ditemukan untuk dicetak");
    }
}

async function fetchSalaryDetailForPrint(salaryId) {
    try {
        const response = await fetch(`/api/salary/detail/${salaryId}`);
        const result = await response.json();

        if (response.ok && result.success && result.data) {
            generatePayslipFromDetail(result.data);
        } else {
            // Fallback: gunakan data yang ada jika tidak bisa fetch detail
            const salary = salaryData.find((s) => s.id_salary === salaryId);
            if (salary) {
                generatePayslipFromDetail(salary);
            } else {
                throw new Error("Data tidak ditemukan");
            }
        }
    } catch (error) {
        console.error("Error fetching salary detail:", error);
        alert("Gagal memuat data untuk dicetak");
    }
}

function showLoading() {
    document.getElementById("loading-indicator").classList.remove("d-none");
    document.getElementById("salary-table").classList.add("d-none");
    document.getElementById("error-message").classList.add("d-none");
    document.getElementById("empty-state").classList.add("d-none");
}

function showError(message) {
    document.getElementById("loading-indicator").classList.add("d-none");
    document.getElementById("salary-table").classList.add("d-none");
    document.getElementById("empty-state").classList.add("d-none");

    const errorMessage = document.getElementById("error-message");
    const errorText = document.getElementById("error-text");

    errorText.textContent = message;
    errorMessage.classList.remove("d-none");
}

function retryLoadData() {
    loadSalaryData();
}

// PERBAIKAN: Fungsi generate payslip dengan data yang sesuai
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

    // PERBAIKAN: Gunakan data yang benar
    const gajiPokok =
        data.gross_salary ||
        data.master_salary_amount ||
        data.salary_amount ||
        0;
    const potonganPph = data.pph21 || Math.round(gajiPokok * 0.04);
    const totalPenghasilan = gajiPokok;
    const totalPotongan = potonganPph;
    const penerimaanBersih =
        data.net_salary || data.salary_amount || gajiPokok - potonganPph;

    const now = new Date();
    const bulan = now.toLocaleDateString("id-ID", { month: "long" });
    const tahun = now.getFullYear();
    const tanggalCetak = now.toLocaleDateString("id-ID", {
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
            <tr><td>Tanggal Pembayaran</td><td>:</td><td><strong>${new Date(
                data.salary_date
            ).toLocaleDateString("id-ID", {
                day: "numeric",
                month: "long",
                year: "numeric",
            })}</strong></td></tr>
        </table>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div style="width: 48%;">
            <h4><strong>PENGHASILAN</strong></h4>
            <table>
                <tr><td>Gaji Pokok</td><td>=</td><td>${formatRupiah(gajiPokok)
                    .replace("Rp", "")
                    .trim()}</td></tr>
                <tr><td>Tj. Jabatan</td><td>=</td><td>-</td></tr>
                <tr><td>Tj. Konsumsi</td><td>=</td><td>-</td></tr>
                <tr><td>Tj. Harian</td><td>=</td><td>-</td></tr>
                <tr><td>Bonus Target</td><td>=</td><td>-</td></tr>
                <tr><td colspan="2"><strong>Total (A)</strong></td><td><strong>${formatRupiah(
                    totalPenghasilan
                )}</strong></td></tr>
            </table>
        </div>

        <div style="width: 48%;">
            <h4><strong>POTONGAN</strong></h4>
            <table>
                <tr><td>PPh 21</td><td>=</td><td>${formatRupiah(potonganPph)
                    .replace("Rp", "")
                    .trim()}</td></tr>
                <tr><td>Asuransi</td><td>=</td><td>-</td></tr>
                <tr><td>Potongan Lainnya</td><td>=</td><td>-</td></tr>
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

// function openFotoModal(proofPath, employeeName) {
//     const modalEl = document.getElementById("fotoModal");
//     const imageEl = document.getElementById("transfer-proof-image");
//     const noProofEl = document.getElementById("no-proof-message");
//     const downloadLink = document.getElementById("download-proof-link");
//     const modalTitle = document.getElementById("fotoModalLabel");

//     modalTitle.textContent = `Bukti Transfer - ${employeeName}`;

//     if (proofPath) {
//         console.log("Original proofPath:", proofPath);

//         const storageUrl = `{{ Storage::url('') }}${proofPath}`;

//         console.log("Final image URL:", storageUrl);

//         imageEl.src = storageUrl;
//         imageEl.classList.remove("d-none");
//         noProofEl.classList.add("d-none");

//         downloadLink.href = storageUrl;
//         downloadLink.classList.remove("d-none");

//         imageEl.onerror = function () {
//             console.error("Failed to load image:", storageUrl);
//             imageEl.classList.add("d-none");
//             noProofEl.classList.remove("d-none");
//             downloadLink.classList.add("d-none");

//             showError(
//                 "Gagal memuat bukti transfer. File mungkin tidak ditemukan."
//             );
//         };

//         imageEl.onload = function () {
//             console.log("Image loaded successfully:", storageUrl);
//         };
//     } else {
//         imageEl.classList.add("d-none");
//         noProofEl.classList.remove("d-none");
//         downloadLink.classList.add("d-none");
//     }

//     fotoModalInstance = new bootstrap.Modal(modalEl);
//     fotoModalInstance.show();
// }

// function closeFotoModal() {
//     if (fotoModalInstance) {
//         fotoModalInstance.hide();
//     }
// }

document
    .getElementById("refreshButton")
    ?.addEventListener("click", function () {
        loadSalaryData();
    });

document.addEventListener("DOMContentLoaded", function () {
    loadSalaryData();
});
