@extends('layouts.app-hrd')

@section('title', 'Tambah Pajak')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Tambah Master Pajak</h1>
                    <div class="text-muted">Tambahkan tarif atau kebijakan pajak yang dipakai perusahaan.</div>
                </div>

                <a href="{{ route('hrd.tax') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form id="taxForm">
                                    <div class="form-group">
                                        <label>Kode Pajak</label>
                                        <input type="text" name="tax_code" class="form-control" placeholder="PPH21-EMP" required>
                                        <div class="invalid-feedback" id="error_tax_code"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>Nama Pajak</label>
                                        <input type="text" name="tax_name" class="form-control" placeholder="PPh 21 Pegawai Tetap" required>
                                        <div class="invalid-feedback" id="error_tax_name"></div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Tipe Pajak</label>
                                            <select name="tax_type" class="form-control" required>
                                                <option value="PPH21">PPH21</option>
                                                <option value="BPJS">BPJS</option>
                                                <option value="OTHER">OTHER</option>
                                            </select>
                                            <div class="invalid-feedback" id="error_tax_type"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Metode Perhitungan</label>
                                            <select name="calculation_method" class="form-control" required>
                                                <option value="PERCENTAGE">PERCENTAGE</option>
                                                <option value="FIXED">FIXED</option>
                                                <option value="PROGRESSIVE">PROGRESSIVE</option>
                                                <option value="GROSS_UP">GROSS_UP</option>
                                            </select>
                                            <div class="invalid-feedback" id="error_calculation_method"></div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>Tarif (%)</label>
                                            <input type="number" step="0.0001" name="tax_rate" class="form-control" placeholder="5">
                                            <div class="invalid-feedback" id="error_tax_rate"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Penghasilan Minimum</label>
                                            <input type="number" step="0.01" name="income_min" class="form-control" placeholder="0">
                                            <div class="invalid-feedback" id="error_income_min"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Penghasilan Maksimum</label>
                                            <input type="number" step="0.01" name="income_max" class="form-control" placeholder="60000000">
                                            <div class="invalid-feedback" id="error_income_max"></div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Tanggal Berlaku</label>
                                            <input type="date" name="effective_date" class="form-control">
                                            <div class="invalid-feedback" id="error_effective_date"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Tanggal Berakhir</label>
                                            <input type="date" name="end_date" class="form-control">
                                            <div class="invalid-feedback" id="error_end_date"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="description" class="form-control" rows="4" placeholder="Keterangan kebijakan atau tarif pajak"></textarea>
                                        <div class="invalid-feedback" id="error_description"></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                                            <label class="custom-control-label" for="is_active">Aktif</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="fas fa-save"></i> Simpan Pajak
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('taxForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;

            clearErrors();

            const payload = {
                tax_code: form.tax_code.value.trim(),
                tax_name: form.tax_name.value.trim(),
                tax_type: form.tax_type.value,
                calculation_method: form.calculation_method.value,
                tax_rate: form.tax_rate.value || null,
                income_min: form.income_min.value || null,
                income_max: form.income_max.value || null,
                effective_date: form.effective_date.value || null,
                end_date: form.end_date.value || null,
                description: form.description.value.trim(),
                is_active: form.is_active.checked
            };

            try {
                const response = await fetch("{{ url('/api/hrd/taxes') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        showValidationErrors(result.errors);
                    } else {
                        alert(result.message ?? 'Terjadi kesalahan');
                    }

                    btn.disabled = false;
                    return;
                }

                alert(result.message || 'Data pajak berhasil dibuat');
                window.location.href = "{{ route('hrd.tax') }}";
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan sistem');
                btn.disabled = false;
            }
        });

        function showValidationErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                const errorDiv = document.getElementById(`error_${field}`);

                if (input) {
                    input.classList.add('is-invalid');
                }

                if (errorDiv) {
                    errorDiv.innerText = errors[field][0];
                }
            });
        }

        function clearErrors() {
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');
        }
    </script>
@endpush
