// Daily Activity Details JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Daily Activity Details PM page loaded');

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Edit button functionality
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const activityId = this.closest('tr').querySelector('td:first-child').textContent;
            console.log('Edit activity:', activityId);
            // Implement edit functionality here
            alert(`Edit kegiatan dengan ID: ${activityId}`);
        });
    });

    // Delete button functionality
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const activityId = this.closest('tr').querySelector('td:first-child').textContent;
            const activityDate = this.closest('tr').querySelector('td:nth-child(2)').textContent;

            if (confirm(`Apakah Anda yakin ingin menghapus kegiatan pada tanggal ${activityDate}?`)) {
                console.log('Delete activity:', activityId);
                // Implement delete functionality here
                alert(`Kegiatan dengan ID: ${activityId} berhasil dihapus`);
            }
        });
    });

    // Table row click functionality (for viewing details)
    const tableRows = document.querySelectorAll('table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Prevent row click when clicking on action buttons
            if (!e.target.closest('.btn-action')) {
                const activityId = this.querySelector('td:first-child').textContent;
                const activityDate = this.querySelector('td:nth-child(2)').textContent;
                console.log('View activity details:', activityId, activityDate);
                // Implement view details functionality here
            }
        });

        // Add hover effect
        row.addEventListener('mouseenter', function() {
            if (!this.classList.contains('selected')) {
                this.style.cursor = 'pointer';
                this.style.backgroundColor = 'rgba(213, 28, 72, 0.05)';
            }
        });

        row.addEventListener('mouseleave', function() {
            if (!this.classList.contains('selected')) {
                this.style.backgroundColor = '';
            }
        });
    });

    // Pagination functionality
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.textContent.trim();
            console.log('Navigating to page:', page);
            // Pagination logic will be implemented here
        });
    });

    // Print functionality
    const printPage = () => {
        window.print();
    };

    // Add keyboard shortcut for print (Ctrl + P)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            printPage();
        }
    });

    // Status filter functionality (if needed)
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;
            const tableRows = document.querySelectorAll('table tbody tr');

            tableRows.forEach(row => {
                const statusBadge = row.querySelector('.status-badge');
                if (selectedStatus === 'all' || statusBadge.textContent.includes(selectedStatus)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Search functionality
    const searchInput = document.querySelector('input[type="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('table tbody tr');

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
