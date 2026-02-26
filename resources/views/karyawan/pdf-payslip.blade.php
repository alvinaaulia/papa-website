<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-cetak').forEach(button => {
            button.addEventListener('click', () => {
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
            <tr><td>Periode</td><td>:</td><td><strong>September</strong></td></tr>
            <tr><td>Nama</td><td>:</td><td><strong>Anonymous</strong></td></tr>
            <tr><td>Jabatan</td><td>:</td><td><strong>UI/UX Designer</strong></td></tr>
            <tr><td>Status Karyawan</td><td>:</td><td><strong>Karyawan Tetap</strong></td></tr>
        </table>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div style="width: 48%;">
            <h4><strong>PENGHASILAN</strong></h4>
            <table>
                <tr><td>Gaji Pokok</td><td>=</td><td>5.000.000</td></tr>
                <tr><td>Tj. Jabatan</td><td>=</td><td>-</td></tr>
                <tr><td>Tj. Konsumsi</td><td>=</td><td>-</td></tr>
                <tr><td>Tj. Harian</td><td>=</td><td>-</td></tr>
                <tr><td>Bonus Target</td><td>=</td><td>-</td></tr>
                <tr><td colspan="2"><strong>Total (A)</strong></td><td><strong>Rp 5.000.000</strong></td></tr>
            </table>
        </div>

        <div style="width: 48%;">
            <h4><strong>POTONGAN</strong></h4>
            <table>
                <tr><td>PPh 21</td><td>=</td><td>200.000</td></tr>
                <tr><td>Asuransi</td><td>=</td><td>-</td></tr>
                <tr><td colspan="2"><strong>Total (B)</strong></td><td><strong>Rp 200.000</strong></td></tr>
            </table>
        </div>
    </div>

    <div class="border-box">
        PENERIMAAN BERSIH (A-B) = Rp 4.800.000
    </div>

    <div class="text-center" style="margin-top: 10px;">
        <small>Terbilang: # Empat Juta Delapan Ratus Ribu Rupiah #</small>
    </div>

    <div class="right" style="margin-top: 50px;">
        <p>Jember, 1 Oktober 2025</p>
        <p><strong>CEO Mascitra.com</strong></p>
        <br><br>
        <p><strong><u>Citra Darma Wida</u></strong></p>
    </div>
</body>
</html>
                `;

                const printWindow = window.open('', '_blank');
                printWindow.document.open();
                printWindow.document.write(slipHTML);
                printWindow.document.close();
            });
        });
    });
</script>
