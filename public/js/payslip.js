let employeesData = [];

$(document).ready(function() {
    initializePayslipForm();
});

function initializePayslipForm() {
    $('.select2').select2();
    loadEmployees();

    $('#id_master_salary').on('change', function() {
        const selectedId = $(this).val();
        updateSalaryDetails(selectedId);
    });

    $('#transfer_proof').on('change', function(e) {
        const file = e.target.files[0];
        handleFileSelection(file);
    });

    $('#changeFile').on('click', function() {
        $('#transfer_proof').click();
    });

    $('#removeFile').on('click', function() {
        resetFileInput();
    });

    $('#payslipForm').on('submit', function(e) {
        e.preventDefault();
        submitForm();
    });

    const today = new Date().toISOString().split('T')[0];
    $('#salary_date').val(today);
}

function handleFileSelection(file) {
    const fileInfo = $('#fileInfo');
    // const uploadIcon = $('#uploadIcon');
    // const filePreview = $('#filePreview');
    const imagePreview = $('#imagePreview');
    const pdfPreview = $('#pdfPreview');

    if (file) {
        const fileSize = (file.size / 1024 / 1024).toFixed(2); 
        if (fileSize > 2) {
            showFileError('File terlalu besar (Max 2MB)');
            return;
        }

        const fileType = file.type;
        const fileName = file.name;
        const isImage = fileType.startsWith('image/');
        const isPDF = fileType === 'application/pdf' || fileName.toLowerCase().endsWith('.pdf');

        if (!isImage && !isPDF) {
            showFileError('Format file tidak didukung. Gunakan PDF atau gambar.');
            return;
        }

        showFilePreview();
        resetPreviews();

        displayFilePreview(file, isImage, isPDF, imagePreview, pdfPreview);

        fileInfo.html(`<span class="text-success">File: ${fileName} (${fileSize} MB)</span>`);
        clearFileError();
    } else {
        resetFileInput();
    }
}

function showFileError(message) {
    $('#fileInfo').html(`<span class="text-danger">${message}</span>`);
    $('#transfer_proof').val('');
    $('#transfer_proof').addClass('is-invalid');
    $('#transfer_proof-error').text(message).show();
}

function clearFileError() {
    $('#transfer_proof').removeClass('is-invalid');
    $('#transfer_proof-error').hide();
}

function showFilePreview() {
    $('#uploadIcon').addClass('hidden');
    $('#filePreview').removeClass('hidden');
}

function resetPreviews() {
    $('#imagePreview').addClass('hidden');
    $('#pdfPreview').hide();
}

function displayFilePreview(file, isImage, isPDF, imagePreview, pdfPreview) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        if (isImage) {
            imagePreview.attr('src', e.target.result);
            imagePreview.removeClass('hidden');
        } else if (isPDF) {
            pdfPreview.attr('src', e.target.result);
            pdfPreview.show();
        }
    };
    
    reader.readAsDataURL(file);
}

function resetFileInput() {
    $('#transfer_proof').val('');
    $('#uploadIcon').removeClass('hidden');
    $('#filePreview').addClass('hidden');
    $('#fileInfo').html('');
    clearFileError();
}

function loadEmployees() {
    $.ajax({
        url: '/api/master-salary', 
        type: 'GET',
        headers: getAjaxHeaders(),
        success: function(response) {
            console.log('Active employees data:', response);
            if (response.success && response.data && response.data.length > 0) {
                employeesData = response.data;
                populateEmployeeSelect(response.data);
            } else {
                showNoEmployeesOption();
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Ada Karyawan Aktif',
                    text: 'Tidak ada data karyawan dengan status gaji aktif',
                    confirmButtonColor: '#d51c48'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading active employees:', error);
            handleAjaxError(xhr, 'Gagal memuat data karyawan aktif');
        }
    });
}

function getAjaxHeaders() {
    return {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    };
}

function populateEmployeeSelect(employees) {
    const select = $('#id_master_salary');
    select.empty();
    select.append('<option value="" disabled selected>Pilih Karyawan</option>');

    const activeEmployees = employees.filter(function(employee) {
        return employee.status === 'active' || employee.status === 'Active';
    });

    if (activeEmployees.length === 0) {
        select.append('<option value="" disabled>Tidak ada karyawan aktif</option>');
        select.prop('disabled', true);
        return;
    }

    activeEmployees.forEach(function(employee) {
        const userName = employee.employee_name || employee.user?.name || 'Unknown User';
        // const position = employee.position || 'Karyawan';
        // const netSalary = employee.net_salary ? formatRupiah(employee.net_salary) : 'Belum diatur';

        select.append(new Option(
            `${userName}`,
            employee.id_master_salary
        ));
    });

    select.prop('disabled', false);
    select.trigger('change');
}

function showNoEmployeesOption() {
    console.warn('No employees data found');
    $('#id_master_salary').html(
        '<option value="" disabled selected>Tidak ada karyawan tersedia</option>'
    );
}

function handleAjaxError(xhr, defaultMessage) {
    const errorMessage = xhr.responseJSON?.message || defaultMessage;
    
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMessage,
        confirmButtonColor: '#d51c48'
    });
}

function updateSalaryDetails(masterSalaryId) {
    if (!masterSalaryId) {
        resetSalaryInfo();
        return;
    }

    const employee = employeesData.find(emp => emp.id_master_salary == masterSalaryId);

    if (employee) {
        displaySalaryInfo(employee);
    } else {
        resetSalaryInfo();
        console.error('Employee data not found for ID:', masterSalaryId);
    }
}

function displaySalaryInfo(employee) {
    const netSalary = employee.net_salary || employee.salary_amount;
    const pph21 = employee.pph21 || 0;
    const grossSalary = employee.salary_amount || 0;
    
    const formattedGross = grossSalary ? formatRupiah(grossSalary) : '0';
    const formattedPPh21 = pph21 ? formatRupiah(pph21) : '0';
    const formattedNet = netSalary ? formatRupiah(netSalary) : '0';
  
    $('#salary_amount').val(formattedNet);
    
    $('#salaryDetails').removeClass('d-none').html(`
        <div class="alert alert-primary" style="padding: 2rem;">
            <h6>Detail Gaji:</h6>
            <div class="row">
                <div class="col-4">
                    <small>Gaji Kotor:</small>
                    <div class="font-weight-bold">${formattedGross}</div>
                </div>
                <div class="col-4">
                    <small>PPh 21:</small>
                    <div class="font-weight-bold">- ${formattedPPh21}</div>
                </div>
                <div class="col-4">
                    <small>Take Home Pay (Net):</small>
                    <div class="font-weight-bold">${formattedNet}</div>
                </div>
            </div>
        </div>
    `);
    
    if (!employee.salary_amount || employee.salary_amount <= 0) {
        showSalaryError(employee);
    } else {
        clearSalaryError();
    }
}

function showSalaryError(employee) {
    $('#salary_amount').addClass('is-invalid');
    $('#salary_amount-error').text('Gaji belum diatur pada data master. Silakan atur gaji terlebih dahulu.').show();

    Swal.fire({
        icon: 'warning',
        title: 'Gaji Belum Diatur',
        text: `Karyawan ${employee.user?.name} belum memiliki nominal gaji yang diatur. Silakan periksa data master gaji.`,
        confirmButtonColor: '#d51c48'
    });
}

function clearSalaryError() {
    $('#salary_amount').removeClass('is-invalid');
    $('#salary_amount-error').hide();
}

function resetSalaryInfo() {
    $('#salary_amount').val('');
    $('#salaryDetails').addClass('d-none').html('');
    clearSalaryError();
}

function formatRupiah(amount) {
    if (!amount) return '0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

function validateForm() {
    resetFormErrors();

    const masterSalaryId = $('#id_master_salary').val();
    if (!masterSalaryId) {
        showFieldError('id_master_salary', 'Pilih karyawan terlebih dahulu');
        return false;
    }

    const employee = employeesData.find(emp => emp.id_master_salary == masterSalaryId);
    if (!employee || !employee.net_salary || employee.net_salary <= 0) {
        showFieldError('salary_amount', 'Karyawan belum memiliki nominal gaji bersih (net) yang valid');
        
        Swal.fire({
            icon: 'error',
            title: 'Gaji Tidak Valid',
            text: 'Karyawan yang dipilih belum memiliki nominal gaji bersih yang valid. Silakan periksa data master gaji.',
            confirmButtonColor: '#d51c48'
        });
        return false;
    }

    const transferProof = $('#transfer_proof')[0].files[0];
    if (!transferProof) {
        showFieldError('transfer_proof', 'File bukti transfer wajib diupload');
        return false;
    }

    return true;
}

function resetFormErrors() {
    $('.invalid-feedback').text('').hide();
    $('.form-control').removeClass('is-invalid');
    $('select').removeClass('is-invalid');
}

function showFieldError(fieldName, message) {
    $(`#${fieldName}`).addClass('is-invalid');
    $(`#${fieldName}-error`).text(message).show();
}

function setLoadingState(isLoading) {
    const submitBtn = $('#submitBtn');
    const submitText = $('#submitText');
    const submitSpinner = $('#submitSpinner');
    
    submitBtn.prop('disabled', isLoading);
    submitText.text(isLoading ? 'Menyimpan...' : 'Buat Slip');
    isLoading ? submitSpinner.removeClass('d-none') : submitSpinner.addClass('d-none');
}

function prepareFormData() {
    const formData = new FormData($('#payslipForm')[0]);
    const employee = employeesData.find(emp => emp.id_master_salary == $('#id_master_salary').val());

    const netSalary = employee.net_salary || employee.salary_amount;
    formData.set('salary_amount', netSalary.toString());

    console.log('Form data:', {
        id_master_salary: $('#id_master_salary').val(),
        net_salary: netSalary,
        salary_date: $('#salary_date').val()
    });

    return formData;
}

function submitForm() {
    if (!validateForm()) {
        return;
    }

    setLoadingState(true);

    const formData = prepareFormData();

    $.ajax({
        url: '/api/salary',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: getAjaxHeaders(),
        success: function(response) {
            console.log('Success response:', response);
            showSuccessMessage(response);
        },
        error: function(xhr) {
            handleSubmitError(xhr);
        },
        complete: function() {
            setLoadingState(false);
        }
    });
}

function showSuccessMessage(response) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        html: `
            <p>${response.message || 'Slip gaji berhasil ditambahkan'}</p>
            <div class="text-left mt-3">
                <small>Karyawan: ${response.data.employee_name}</small><br>
                <small>Net Salary: ${formatRupiah(response.data.net_salary)}</small><br>
                <small>Tanggal: ${new Date(response.data.salary_date).toLocaleDateString('id-ID')}</small>
            </div>
        `,
        confirmButtonColor: '#d51c48',
        showCancelButton: false,
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('payslipForm');
            const redirectUrl = form.getAttribute('data-redirect-url');
            
            if (redirectUrl) {
                window.location.href = redirectUrl;
            } else {
                window.location.href = '/hrd/payslip';
            }
        }
    });
}

function handleSubmitError(xhr) {
    const response = xhr.responseJSON;
    console.error('Error response:', response);

    if (xhr.status === 422 && response.errors) {
        Object.keys(response.errors).forEach(function(key) {
            showFieldError(key, response.errors[key][0]);
        });

        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.'
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: response?.message || 'Terjadi kesalahan saat menyimpan data'
        });
    }
}