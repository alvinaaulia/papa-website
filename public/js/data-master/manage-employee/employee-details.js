class EmployeeDetail {
  constructor() {
      this.employeeId = this.getEmployeeIdFromUrl();
      this.baseUrl = '/api/data-master/users/show-users';
      this.init();
  }

  init() {
      if (this.employeeId) {
          this.loadEmployeeDetail();
      } else {
          console.error('Employee ID not found in URL');
          this.showError('ID karyawan tidak ditemukan');
      }
  }

  getEmployeeIdFromUrl() {
      // Ambil ID dari URL path (contoh: /hrd/employees/1)
      const pathArray = window.location.pathname.split('/');
      return pathArray[pathArray.length - 1];
  }

  async loadEmployeeDetail() {
      try {
          this.showLoading();
          
          const response = await fetch(`${this.baseUrl}/${this.employeeId}`, {
              method: 'GET',
              headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json'
              }
          });

          if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
          }

          const result = await response.json();
          
          if (result.status === 'success') {
              this.renderEmployeeData(result.data);
          } else {
              throw new Error(result.message || 'Failed to load employee data');
          }
      } catch (error) {
          console.error('Error loading employee detail:', error);
          this.showError('Gagal memuat data karyawan: ' + error.message);
      } finally {
          this.hideLoading();
      }
  }

  renderEmployeeData(employee) {
      const profile = employee.profiles || {};
      
      // Data Karyawan Section
      this.setFieldValue('input[placeholder*="NIP"]', profile.nip || profile.employee_id || '-');
      this.setFieldValue('input[placeholder*="Nama Karyawan"]', employee.name || '-');
      this.setFieldValue('input[placeholder*="Email"]', employee.email || '-');
      this.setFieldValue('input[placeholder*="Jenis Karyawan"]', this.getEmployeeType(employee.role));

      // Form Data Karyawan Section
      this.setFieldValue('input[placeholder*="Jenis Kelamin"]', profile.gender ? this.capitalizeFirstLetter(profile.gender) : '-');
      this.setFieldValue('input[placeholder*="Tempat Lahir"]', profile.birth_place || '-');
      this.setFieldValue('input[type="date"]:not(.experience-date input)', profile.birth_date || '');
      this.setFieldValue('input[placeholder*="NIK"]', profile.nik || '-');
      this.setFieldValue('textarea[name="address"]', profile.address || '-');
      this.setFieldValue('input[placeholder*="Kecamatan"]', profile.district || '-');
      this.setFieldValue('input[placeholder*="Kabupaten"]', profile.regency || '-');
      this.setFieldValue('input[placeholder*="Provinsi"]', profile.province || '-');
      this.setFieldValue('input[placeholder*="No Telepon"]', profile.phone || '-');

      // Pendidikan Terakhir Section
      this.setFieldValue('input[placeholder*="Jurusan"]', profile.major || '-');
      this.setFieldValue('input[placeholder*="Instansi"]', profile.institution || '-');
      this.setFieldValue('input[placeholder*="Tahun Lulus"]', profile.graduation_year || '-');

      // Pengalaman Section
      this.setFieldValue('input[placeholder*="Bidang"]', profile.experience_field || '-');
      this.setFieldValue('input[placeholder*="Nama Perusahaan"]', profile.company_name || '-');
      
      // Set experience dates if available
      const experienceDates = document.querySelectorAll('.experience-date input');
      if (experienceDates.length >= 2) {
          experienceDates[0].value = profile.experience_start_date || '';
          experienceDates[1].value = profile.experience_end_date || '';
      }

      // Update page title dengan nama karyawan
      this.updatePageTitle(employee.name);
  }

  setFieldValue(selector, value) {
      const field = document.querySelector(selector);
      if (field) {
          field.value = value || '';
      }
  }

  getEmployeeType(role) {
      const roleMap = {
          'karyawan': 'Karyawan',
          'project manager': 'Project Manager',
          'hrd': 'HRD',
          'direktur': 'Direktur'
      };
      return roleMap[role] || 'Karyawan';
  }

  capitalizeFirstLetter(string) {
      if (!string) return '-';
      return string.charAt(0).toUpperCase() + string.slice(1);
  }

  updatePageTitle(employeeName) {
      if (employeeName && employeeName !== '-') {
          document.title = `Rincian Data ${employeeName} - HRD`;
          
          // Optional: Update breadcrumb atau heading
          const breadcrumb = document.querySelector('.breadcrumb-item:last-child');
          if (breadcrumb) {
              breadcrumb.textContent = `Rincian ${employeeName}`;
          }
      }
  }

  showLoading() {
      // Tambah loading indicator di form
      const forms = document.querySelectorAll('form');
      forms.forEach(form => {
          const loadingDiv = document.createElement('div');
          loadingDiv.className = 'loading-overlay';
          loadingDiv.innerHTML = `
              <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
                  <span class="ms-2">Memuat data karyawan...</span>
              </div>
          `;
          form.style.position = 'relative';
          form.appendChild(loadingDiv);
      });
  }

  hideLoading() {
      // Hapus loading indicator
      const loadingOverlays = document.querySelectorAll('.loading-overlay');
      loadingOverlays.forEach(overlay => {
          overlay.remove();
      });
  }

  showError(message) {
      // Create error alert
      const alertDiv = document.createElement('div');
      alertDiv.className = 'alert alert-danger alert-dismissible fade show';
      alertDiv.style.margin = '20px';
      alertDiv.innerHTML = `
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;

      // Insert alert at the top of the section body
      const sectionBody = document.querySelector('.section-body');
      if (sectionBody) {
          sectionBody.insertBefore(alertDiv, sectionBody.firstChild);
      }

      // Auto remove after 5 seconds
      setTimeout(() => {
          if (alertDiv.parentNode) {
              alertDiv.remove();
          }
      }, 5000);
  }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
  window.employeeDetail = new EmployeeDetail();
});