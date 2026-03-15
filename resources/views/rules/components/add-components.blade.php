@extends('layouts.app-hrd')

@section('title', 'Tambah Komponen Gaji')

@section('main')
    <div class="main-content">
        <section class="section">

            <div class="section-header">
                <h1>Tambah Komponen Gaji</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-8">

                        <div class="card">
                            <div class="card-body">

                                <form id="componentForm">

                                    {{-- COMPONENT CODE --}}
                                    <div class="form-group">
                                        <label>Kode Komponen</label>
                                        <input type="text" name="component_code" class="form-control"
                                            placeholder="OVERTIME_PAY" required>
                                        <div class="invalid-feedback" id="error_component_code"></div>
                                    </div>

                                    {{-- COMPONENT NAME --}}
                                    <div class="form-group">
                                        <label>Nama Komponen</label>
                                        <input type="text" name="component_name" class="form-control"
                                            placeholder="Overtime Payment" required>
                                        <div class="invalid-feedback" id="error_component_name"></div>
                                    </div>

                                    {{-- COMPONENT TYPE --}}
                                    <div class="form-group">
                                        <label>Tipe Komponen</label>
                                        <select name="component_type" class="form-control" required>
                                            <option value="">Pilih Tipe</option>
                                            <option value="EARNING">Earning</option>
                                            <option value="DEDUCTION">Deduction</option>
                                        </select>
                                        <div class="invalid-feedback" id="error_component_type"></div>
                                    </div>

                                    {{-- DESCRIPTION --}}
                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Pembayaran lembur"></textarea>
                                        <div class="invalid-feedback" id="error_description"></div>
                                    </div>

                                    {{-- IS TAXABLE --}}
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="is_taxable"
                                                name="is_taxable" value="1">
                                            <label class="custom-control-label" for="is_taxable">
                                                Dikenakan Pajak
                                            </label>
                                        </div>
                                    </div>

                                    {{-- IS ACTIVE --}}
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="is_active"
                                                name="is_active" value="1" checked>
                                            <label class="custom-control-label" for="is_active">
                                                Aktif
                                            </label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="fas fa-save"></i> Simpan
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
        document.getElementById('componentForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;

            clearErrors();

            const payload = {
                component_code: form.component_code.value.trim(),
                component_name: form.component_name.value.trim(),
                component_type: form.component_type.value,
                description: form.description.value.trim(),
                is_taxable: form.is_taxable.checked,
                is_active: form.is_active.checked
            };

            try {
                const response = await fetch("{{ url('/api/hrd/components') }}", {
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

                alert('Komponen berhasil dibuat');
                window.location.href = '/hrd/components';

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
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('is-invalid');
            });

            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.innerText = '';
            });
        }
    </script>
@endpush
