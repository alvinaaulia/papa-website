class AddEmployee {
    constructor() {
        this.baseUrl = '/api/data-master/users/add-users';
        this.form = document.getElementById('addEmployeeForm');
        this.submitBtn = document.getElementById('submitBtn');
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.handleSubmit();
            });
        }

        if (this.submitBtn) {
            this.submitBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.handleSubmit();
            });
        }
    }

    async handleSubmit() {
        console.log('Handle submit dipanggil');
        
        // Get form data
        const formData = this.getFormData();
        console.log('Form data:', formData);
        
        // Validate form
        if (!this.validateForm(formData)) {
            console.log('Form validation failed');
            return;
        }

        try {
            this.showLoading();
            console.log('Mengirim data ke API...');
            
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            console.log('API Response:', result);

            if (result.status === 'success') {
                this.showSuccess('Karyawan berhasil ditambahkan!');
                setTimeout(() => {
                    window.location.href = '/hrd/data-employees';
                }, 2000);
            } else {
                // Handle validation errors from backend
                if (result.errors) {
                    this.showBackendValidationErrors(result.errors);
                } else {
                    throw new Error(result.message || 'Failed to create employee');
                }
            }
        } catch (error) {
            console.error('Error adding employee:', error);
            this.showError('Gagal menambahkan karyawan: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    getFormData() {
        const formData = {
            name: document.querySelector('input[name="name"]')?.value.trim(),
            email: document.querySelector('input[name="email"]')?.value.trim(),
            role: document.querySelector('select[name="role"]')?.value
        };
        console.log('Form data extracted:', formData);
        return formData;
    }

    validateForm(data) {
        this.clearErrors();

        let isValid = true;

        // Validate name
        if (!data.name) {
            this.showFieldError('input[name="name"]', 'Nama lengkap wajib diisi');
            isValid = false;
        }

        // Validate email
        if (!data.email) {
            this.showFieldError('input[name="email"]', 'Email wajib diisi');
            isValid = false;
        } else if (!this.isValidEmail(data.email)) {
            this.showFieldError('input[name="email"]', 'Format email tidak valid');
            isValid = false;
        }

        // Validate role
        if (!data.role) {
            this.showFieldError('select[name="role"]', 'Jabatan wajib dipilih');
            isValid = false;
        }

        return isValid;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    showFieldError(selector, message) {
        const field = document.querySelector(selector);
        if (field) {
            field.classList.add('is-invalid');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            
            // Insert after field
            field.parentNode.appendChild(errorDiv);
        }
    }

    showBackendValidationErrors(errors) {
        this.clearErrors();
        
        Object.keys(errors).forEach(field => {
            const errorMessage = errors[field][0];
            let selector = '';
            
            switch(field) {
                case 'name':
                    selector = 'input[name="name"]';
                    break;
                case 'email':
                    selector = 'input[name="email"]';
                    break;
                case 'role':
                    selector = 'select[name="role"]';
                    break;
            }
            
            if (selector) {
                this.showFieldError(selector, errorMessage);
            }
        });
    }

    clearErrors() {
        // Remove error classes
        document.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid');
        });
        
        // Remove error messages
        document.querySelectorAll('.invalid-feedback').forEach(element => {
            element.remove();
        });
    }

    showLoading() {
        if (this.submitBtn) {
            this.submitBtn.disabled = true;
            this.submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Mengirim...
            `;
        }
    }

    hideLoading() {
        if (this.submitBtn) {
            this.submitBtn.disabled = false;
            this.submitBtn.innerHTML = 'Kirim';
        }
    }

    showSuccess(message) {
        this.showAlert(message, 'success');
    }

    showError(message) {
        this.showAlert(message, 'danger');
    }

    showAlert(message, type) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.marginBottom = '20px';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Insert alert before the form
        const card = document.querySelector('.card');
        if (card) {
            card.parentNode.insertBefore(alertDiv, card);
        }
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('AddEmployee initialized');
    window.addEmployee = new AddEmployee();
});