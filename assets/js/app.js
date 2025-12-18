// Main Application Script
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    initTooltips();
    
    // Initialize sidebar toggle for mobile
    initSidebarToggle();
    
    // Initialize dropdowns
    initDropdowns();
    
    // Initialize modals
    initModals();
    
    // Initialize form validation
    initFormValidation();
    
    // Initialize charts if on dashboard
    if (document.querySelector('.dashboard')) {
        initDashboardCharts();
    }
    
    // Initialize data tables if any
    initDataTables();
    
    // Initialize any other UI components
    initUIComponents();
});

// Initialize tooltips
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Initialize sidebar toggle for mobile
function initSidebarToggle() {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.mobile-menu-btn');
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-open');
            sidebar.classList.toggle('active');
            
            if (document.body.classList.contains('sidebar-open')) {
                document.body.appendChild(overlay);
                overlay.addEventListener('click', closeSidebar);
            } else {
                if (document.body.contains(overlay)) {
                    document.body.removeChild(overlay);
                }
            }
        });
    }
    
    function closeSidebar() {
        document.body.classList.remove('sidebar-open');
        sidebar.classList.remove('active');
        if (document.body.contains(overlay)) {
            document.body.removeChild(overlay);
        }
    }
    
    // Close sidebar when clicking on a menu item on mobile
    const menuItems = document.querySelectorAll('.sidebar-menu .menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });
}

// Initialize dropdowns
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            const menu = this.nextElementSibling;
            
            // Close all other open dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
            
            // Toggle current dropdown
            menu.classList.toggle('show');
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function closeDropdown(e) {
                if (!dropdown.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('show');
                    document.removeEventListener('click', closeDropdown);
                }
            });
        });
    });
}

// Initialize modals
function initModals() {
    // Open modal
    const modalTriggers = document.querySelectorAll('[data-modal]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                
                // Focus on first input if exists
                const input = modal.querySelector('input, select, textarea');
                if (input) input.focus();
                
                // Close on overlay click
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal(modal);
                    }
                });
                
                // Close on escape key
                document.addEventListener('keydown', function closeOnEscape(e) {
                    if (e.key === 'Escape') {
                        closeModal(modal);
                        document.removeEventListener('keydown', closeOnEscape);
                    }
                });
            }
        });
    });
    
    // Close modal buttons
    const closeButtons = document.querySelectorAll('.modal-close, [data-dismiss="modal"]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) closeModal(modal);
        });
    });
    
    function closeModal(modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// Initialize form validation
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
}

// Initialize dashboard charts
function initDashboardCharts() {
    // This is a placeholder. Actual chart initialization is done in each dashboard page
    // as they have different chart requirements
    console.log('Initializing dashboard charts...');
}

// Initialize data tables
function initDataTables() {
    // This would initialize any data tables if DataTables library is included
    // Example:
    // if (typeof $.fn.DataTable === 'function') {
    //     $('.datatable').DataTable();
    // }
}

// Initialize other UI components
function initUIComponents() {
    // Toggle sidebar menu groups
    const menuGroups = document.querySelectorAll('.menu-group');
    menuGroups.forEach(group => {
        const toggle = group.querySelector('.menu-group-toggle');
        if (toggle) {
            toggle.addEventListener('click', function() {
                this.parentElement.classList.toggle('open');
            });
        }
    });
    
    // Toggle password visibility
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Initialize custom file input
    const fileInputs = document.querySelectorAll('.custom-file-input');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Choose file';
            const label = this.nextElementSibling;
            label.textContent = fileName;
        });
    });
    
    // Initialize custom select
    const customSelects = document.querySelectorAll('.custom-select');
    customSelects.forEach(select => {
        const selected = select.querySelector('.select-selected');
        const options = select.querySelector('.select-items');
        
        if (selected && options) {
            selected.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('.select-items').forEach(opt => {
                    if (opt !== options) opt.style.display = 'none';
                });
                options.style.display = options.style.display === 'block' ? 'none' : 'block';
            });
            
            const optionItems = options.querySelectorAll('div');
            optionItems.forEach(option => {
                option.addEventListener('click', function() {
                    selected.textContent = this.textContent;
                    const select = this.closest('.custom-select');
                    const hiddenInput = select.querySelector('select');
                    if (hiddenInput) {
                        hiddenInput.value = this.getAttribute('data-value');
                        hiddenInput.dispatchEvent(new Event('change'));
                    }
                    options.style.display = 'none';
                });
            });
            
            // Close when clicking outside
            document.addEventListener('click', function closeSelect(e) {
                if (!select.contains(e.target)) {
                    options.style.display = 'none';
                }
            });
        }
    });
    
    // Initialize date pickers
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        if (!input.value) {
            const today = new Date().toISOString().split('T')[0];
            input.value = today;
        }
    });
}

// Utility function to format dates
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

// Utility function to format numbers with commas
function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Utility function to debounce function calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add some basic responsive behavior
window.addEventListener('resize', debounce(function() {
    // Handle responsive behavior here
    if (window.innerWidth >= 992) {
        document.body.classList.remove('sidebar-open');
        const overlay = document.querySelector('.sidebar-overlay');
        if (overlay && document.body.contains(overlay)) {
            document.body.removeChild(overlay);
        }
    }
}, 250));

// Add a global error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error || e.message || e);
    // You could show a user-friendly error message here
});

// Add an unhandled promise rejection handler
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled promise rejection:', e.reason);
    // You could show a user-friendly error message here
    e.preventDefault();
});
