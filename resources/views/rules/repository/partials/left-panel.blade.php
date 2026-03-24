<div class="panel">
    <h3>
        <i class="fas fa-info-circle"></i>
        Informasi Aturan
    </h3>

    <div class="mb-3">
        <div class="label">Nama Aturan</div>
        <input
            id="ruleName"
            class="form-control"
            placeholder="Contoh: Tunjangan Transportasi" />
        @if (!empty($isEditMode))
            <div class="existing-value">
                <b>Data saat ini:</b> <span id="existingRuleName">{{ $initialRule['name'] ?? '-' }}</span>
            </div>
        @endif
    </div>

    <div class="mb-3">
        <div class="label">Deskripsi</div>
        <textarea
            id="ruleDescription"
            class="form-control"
            placeholder="Deskripsi singkat aturan..."></textarea>
        @if (!empty($isEditMode))
            <div class="existing-value">
                <b>Data saat ini:</b> <span id="existingRuleDescription">{{ $initialRule['description'] ?? '-' }}</span>
            </div>
        @endif
    </div>

    <div class="mb-3">
        <div class="label">Prioritas Eksekusi</div>
        <select id="rulePriority" class="custom-select">
            <option value="NORMAL">Normal</option>
            <option value="HIGH">Tinggi</option>
            <option value="LOW">Rendah</option>
        </select>
        @if (!empty($isEditMode))
            <div class="existing-value">
                <b>Data saat ini:</b>
                <span id="existingRulePriority">{{ data_get($initialRule, 'definition.meta.priority', 'NORMAL') }}</span>
            </div>
        @endif
    </div>

    <div class="mb-3">
        <div class="label">Tanggal Berlaku</div>
        <input
            type="date"
            id="effectiveDate"
            class="form-control" />
        @if (!empty($isEditMode))
            <div class="existing-value">
                <b>Data saat ini:</b>
                <span id="existingEffectiveDate">{{ data_get($initialRule, 'definition.meta.effective_date', '-') }}</span>
            </div>
        @endif
    </div>

    <div class="mb-3">
        <div class="label">Tanggal Berakhir</div>
        <select id="endDateMode" class="custom-select">
            <option value="NONE">Tidak ada batas</option>
            <option value="DATE">Pilih tanggal</option>
        </select>
        <input
            type="date"
            id="endDate"
            class="form-control mt-2 d-none" />
        @if (!empty($isEditMode))
            <div class="existing-value">
                <b>Data saat ini:</b>
                <span id="existingEndDate">{{ data_get($initialRule, 'definition.meta.end_date', 'Tidak ada batas') }}</span>
            </div>
        @endif
    </div>

    <div class="component-section">
        <h3>
            <i class="fas fa-cubes"></i>
            Komponen Aktif
        </h3>
        <div class="component-list">
            @forelse ($components as $component)
            <div class="component-card"
                data-code="{{ $component->component_code }}"
                data-name="{{ strtolower($component->component_name) }}">
                <div class="component-left">
                    <div class="component-icon">
                        <i class="fas fa-cube"></i>
                    </div>
                    <div class="component-info">
                        <div class="component-code">
                            {{ $component->component_code }}
                        </div>
                        <div class="component-name">
                            {{ $component->component_name }}
                        </div>
                    </div>
                </div>
                <span class="badge badge-success">
                    Aktif
                </span>
            </div>

            @empty

            <div class="empty-state">
                Tidak ada komponen aktif
            </div>

            @endforelse

        </div>
        <div class="muted mt-2">
            Komponen ini dapat digunakan pada rule penggajian
        </div>
    </div>
</div>
