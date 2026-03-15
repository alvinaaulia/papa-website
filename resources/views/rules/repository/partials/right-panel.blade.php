<div class="panel panel-column">
    <div class="panel mb-3">
        <h3>
            <i class="fas fa-align-left"></i>
            Preview Rule
        </h3>
        <div id="ruleSentencePreview" class="rule-sentence-preview">
            Rule belum dibuat
        </div>
    </div>

    <h3>
        <i class="fas fa-flask"></i>
        Simulasi & Testing
    </h3>

    <div class="muted mb-3">
        Uji aturan dengan data sampel sebelum diaktifkan
    </div>

    <button id="btnRunSim" type="button" class="btn btn-warning w-100"
        style="height:48px;border-radius:12px;font-weight:700">

        <i class="fas fa-play"></i>
        Jalankan Simulasi
    </button>

    <div class="mt-3">
        <h3>
            <i class="fas fa-check-circle"></i>
            Hasil Evaluasi
        </h3>

        <div class="result-card ok">
            <div class="result-title">
                <i class="fas fa-check"></i>
                Sintaks Valid
            </div>
            <div class="muted">
                Sintaks tidak memiliki error
            </div>
        </div>

        <div class="result-card info">
            <div class="result-title">
                <i class="fas fa-database"></i>
                Data Cocok
            </div>
            <div class="muted">
                <span id="matchCount">0</span> tugas memenuhi kondisi
            </div>
        </div>

        <div class="result-card warn">
            <div class="result-title">
                <i class="fas fa-exclamation-triangle"></i>
                Peringatan
            </div>
            <div class="muted">
                Aturan akan mengeksekusi <b><span id="actionCount">1</span> aksi</b>
            </div>
        </div>
    </div>
</div>
