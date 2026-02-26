class LeaveTypesManager {
    constructor() {
        this.API_BASE_URL = '/api';
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadLeaveTypes();
    }

    bindEvents() {
        document.getElementById('refreshButton').addEventListener('click', () => {
            this.loadLeaveTypes();
        });

        window.addEventListener('error', (e) => {
            console.error('Global error:', e.error);
            this.showError('Terjadi kesalahan yang tidak terduga');
        });
    }

    async loadLeaveTypes() {
        this.showLoading();
        this.hideError();
        this.hideEmptyState();

        try {
            const response = await fetch(`${this.API_BASE_URL}/leave-type`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || `HTTP error! status: ${response.status}`);
            }

            if (result.success && result.data && result.data.length > 0) {
                this.renderTableData(result.data);
            } else {
                this.showEmptyState();
            }

        } catch (error) {
            console.error('Error loading leave types:', error);
            this.showError(error.message || 'Terjadi kesalahan saat memuat data');
        } finally {
            this.hideLoading();
        }
    }

    renderTableData(data) {
        const tableBody = document.getElementById('leaveTypesTableBody');
        
        const tableRows = data.map((item, index) => this.createTableRow(item, index));
        tableBody.innerHTML = tableRows.join('');
    }

    createTableRow(item, index) {
        const leaveName = this.escapeHtml(item.leave_name || '');
        const description = this.escapeHtml(item.description || '-');

        return `
            <tr class="overtime-data">
                <td class="text-center">${index + 1}</td>
                <td style="text-align: center; font-weight: 500;">${leaveName}</td>
                <td style="text-align: left">${description}</td>
            </tr>
        `;
    }

    escapeHtml(unsafe) {
        if (unsafe === null || unsafe === undefined) return '-';
        return unsafe
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    showLoading() {
        document.getElementById('loadingIndicator').style.display = 'block';
        document.getElementById('leaveTypesTableBody').innerHTML = '';
    }

    hideLoading() {
        document.getElementById('loadingIndicator').style.display = 'none';
    }

    showError(message) {
        const errorElement = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        
        errorText.textContent = message;
        errorElement.style.display = 'block';
    }

    hideError() {
        document.getElementById('errorMessage').style.display = 'none';
    }

    showEmptyState() {
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('leaveTypesTableBody').innerHTML = '';
    }

    hideEmptyState() {
        document.getElementById('emptyState').style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    new LeaveTypesManager();
});