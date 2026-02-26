class PayslipGenerator {
    constructor() {
        this.formatRupiah = this.formatRupiah.bind(this);
        this.getTerbilang = this.getTerbilang.bind(this);
        this.generatePayslipHTML = this.generatePayslipHTML.bind(this);
    }

    formatRupiah(number) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(number);
    }

    getTerbilang(angka) {
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
            "# " +
            this.formatRupiah(angka).replace("Rp", "").trim() +
            " Rupiah #"
        );
    }

    generatePayslipHTML(data) {
        const {
            userId,
            userName,
            salaryAmount,
            position = "UI/UX Designer",
            employeeStatus = "Karyawan Tetap",
            allowances = {},
            deductions = {},
        } = data;

        // Hitung komponen gaji
        const gajiPokok = parseInt(salaryAmount) || 5000000;
        const tunjanganJabatan = allowances.position || 0;
        const tunjanganKonsumsi = allowances.consumption || 0;
        const tunjanganHarian = allowances.daily || 0;
        const bonusTarget = allowances.bonus || 0;

        const potonganPph = deductions.tax || Math.round(gajiPokok * 0.04);
        const asuransi = deductions.insurance || 0;

        const totalPenghasilan =
            gajiPokok +
            tunjanganJabatan +
            tunjanganKonsumsi +
            tunjanganHarian +
            bonusTarget;
        const totalPotongan = potonganPph + asuransi;
        const penerimaanBersih = totalPenghasilan - totalPotongan;

        // Dapatkan bulan dan tahun saat ini
        const now = new Date();
        const bulan = now.toLocaleDateString("id-ID", { month: "long" });
        const tahun = now.getFullYear();
        const tanggalCetak = now.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });

        return `
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji Karyawan - ${userName}</title>
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
            <tr><td>Nama</td><td>:</td><td><strong>${userName}</strong></td></tr>
            <tr><td>Jabatan</td><td>:</td><td><strong>${position}</strong></td></tr>
            <tr><td>Status Karyawan</td><td>:</td><td><strong>${employeeStatus}</strong></td></tr>
        </table>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div style="width: 48%;">
            <h4><strong>PENGHASILAN</strong></h4>
            <table>
                <tr><td>Gaji Pokok</td><td>=</td><td>${this.formatRupiah(
                    gajiPokok
                )
                    .replace("Rp", "")
                    .trim()}</td></tr>
                ${
                    tunjanganJabatan
                        ? `<tr><td>Tj. Jabatan</td><td>=</td><td>${this.formatRupiah(
                              tunjanganJabatan
                          )
                              .replace("Rp", "")
                              .trim()}</td></tr>`
                        : ""
                }
                ${
                    tunjanganKonsumsi
                        ? `<tr><td>Tj. Konsumsi</td><td>=</td><td>${this.formatRupiah(
                              tunjanganKonsumsi
                          )
                              .replace("Rp", "")
                              .trim()}</td></tr>`
                        : ""
                }
                ${
                    tunjanganHarian
                        ? `<tr><td>Tj. Harian</td><td>=</td><td>${this.formatRupiah(
                              tunjanganHarian
                          )
                              .replace("Rp", "")
                              .trim()}</td></tr>`
                        : ""
                }
                ${
                    bonusTarget
                        ? `<tr><td>Bonus Target</td><td>=</td><td>${this.formatRupiah(
                              bonusTarget
                          )
                              .replace("Rp", "")
                              .trim()}</td></tr>`
                        : ""
                }
                <tr><td colspan="2"><strong>Total (A)</strong></td><td><strong>${this.formatRupiah(
                    totalPenghasilan
                )}</strong></td></tr>
            </table>
        </div>

        <div style="width: 48%;">
            <h4><strong>POTONGAN</strong></h4>
            <table>
                ${
                    potonganPph
                        ? `<tr><td>PPh 21</td><td>=</td><td>${this.formatRupiah(
                              potonganPph
                          )
                              .replace("Rp", "")
                              .trim()}</td></tr>`
                        : ""
                }
                ${
                    asuransi
                        ? `<tr><td>Asuransi</td><td>=</td><td>${this.formatRupiah(
                              asuransi
                          )
                              .replace("Rp", "")
                              .trim()}</td></tr>`
                        : ""
                }
                <tr><td colspan="2"><strong>Total (B)</strong></td><td><strong>${this.formatRupiah(
                    totalPotongan
                )}</strong></td></tr>
            </table>
        </div>
    </div>

    <div class="border-box">
        PENERIMAAN BERSIH (A-B) = ${this.formatRupiah(penerimaanBersih)}
    </div>

    <div class="text-center" style="margin-top: 10px;">
        <small>Terbilang: ${this.getTerbilang(penerimaanBersih)}</small>
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
    }

    openPayslip(data) {
        const htmlContent = this.generatePayslipHTML(data);
        const printWindow = window.open("", "_blank", "width=800,height=600");
        printWindow.document.open();
        printWindow.document.write(htmlContent);
        printWindow.document.close();
    }
}

if (typeof module !== "undefined" && module.exports) {
    module.exports = PayslipGenerator;
} else {
    window.PayslipGenerator = PayslipGenerator;
}
