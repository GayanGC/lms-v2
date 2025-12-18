// Auth Module - Handles user authentication and session management
class Auth {
    constructor() {
        this.currentUser = null;
        this.init();
    }

    init() {
        // Check for existing session
        this.checkSession();
        
        // Initialize event listeners
        this.initEventListeners();
    }

    // Check if user is logged in
    checkSession() {
        const userData = localStorage.getItem('currentUser');
        if (userData) {
            this.currentUser = JSON.parse(userData);
            this.updateUIForAuthState(true);
            return true;
        }
        return false;
    }

    // Initialize event listeners for auth-related elements
    initEventListeners() {
        // Login form submission
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }

        // Register form submission
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => this.handleRegister(e));
        }

        // Logout button
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => this.handleLogout(e));
        }

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', (e) => this.togglePasswordVisibility(e));
        });
    }

    // Handle user login
    async handleLogin(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const rememberMe = document.getElementById('remember')?.checked || false;

        // Simple validation
        if (!email || !password) {
            this.showError('Please fill in all fields');
            return;
        }

        // Get users from localStorage or initialize with default users
        const users = JSON.parse(localStorage.getItem('users') || '[]');
        
        // Check if it's a test user
        const testUser = this.checkTestCredentials(email, password);
        let user = testUser || users.find(u => u.email === email && u.password === password);

        if (user) {
            // Update last login
            user.lastLogin = new Date().toISOString();
            
            // Save user data to localStorage
            if (testUser) {
                // Add test user to users array if not exists
                if (!testUser.id) {
                    user.id = Date.now();
                    users.push(user);
                    localStorage.setItem('users', JSON.stringify(users));
                }
            } else {
                // Update existing user
                const index = users.findIndex(u => u.id === user.id);
                if (index !== -1) {
                    users[index] = user;
                    localStorage.setItem('users', JSON.stringify(users));
                }
            }
            
            // Set current user
            this.currentUser = user;
            localStorage.setItem('currentUser', JSON.stringify(user));
            
            // Redirect based on role
            this.redirectToDashboard(user.role);
        } else {
            this.showError('Invalid email or password');
        }
    }

    // Handle user registration
    async handleRegister(e) {
        e.preventDefault();
        
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const email = document.getElementById('email').value.trim();
        const role = document.getElementById('role').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const terms = document.getElementById('terms').checked;

        // Validation
        if (!firstName || !lastName || !email || !role || !password || !confirmPassword) {
            this.showError('Please fill in all fields');
            return;
        }

        if (password !== confirmPassword) {
            this.showError('Passwords do not match');
            return;
        }

        if (password.length < 8) {
            this.showError('Password must be at least 8 characters long');
            return;
        }

        if (!terms) {
            this.showError('You must accept the terms and conditions');
            return;
        }

        // Check if user already exists
        const users = JSON.parse(localStorage.getItem('users') || '[]');
        if (users.some(user => user.email === email)) {
            this.showError('Email already registered');
            return;
        }

        // Create new user
        const newUser = {
            id: Date.now(),
            firstName,
            lastName,
            email,
            role,
            password, // In a real app, password should be hashed
            createdAt: new Date().toISOString(),
            lastLogin: new Date().toISOString()
        };

        // Save user
        users.push(newUser);
        localStorage.setItem('users', JSON.stringify(users));
        
        // Set current user and redirect
        this.currentUser = newUser;
        localStorage.setItem('currentUser', JSON.stringify(newUser));
        
        // Show success message and redirect
        this.showSuccess('Registration successful! Redirecting...');
        setTimeout(() => {
            this.redirectToDashboard(role);
        }, 1500);
    }

    // Handle user logout
    handleLogout(e) {
        e.preventDefault();
        
        // Clear current user
        this.currentUser = null;
        localStorage.removeItem('currentUser');
        
        // Update UI and redirect to login
        this.updateUIForAuthState(false);
        window.location.href = '../login.html';
    }

    // Check test credentials
    checkTestCredentials(email, password) {
        const testUsers = [
            {
                id: 1,
                firstName: 'Admin',
                lastName: 'User',
                email: 'admin@lms.com',
                password: 'admin123',
                role: 'admin',
                createdAt: new Date().toISOString(),
                lastLogin: new Date().toISOString()
            },
            {
                id: 2,
                firstName: 'Instructor',
                lastName: 'User',
                email: 'teacher@lms.com',
                password: 'teacher123',
                role: 'instructor',
                createdAt: new Date().toISOString(),
                lastLogin: new Date().toISOString()
            },
            {
                id: 3,
                firstName: 'Student',
                lastName: 'User',
                email: 'student@lms.com',
                password: 'student123',
                role: 'student',
                createdAt: new Date().toISOString(),
                lastLogin: new Date().toISOString()
            }
        ];

        return testUsers.find(user => user.email === email && user.password === password);
    }

    // Redirect to appropriate dashboard based on role
    redirectToDashboard(role) {
        switch(role) {
            case 'admin':
                window.location.href = 'pages/admin.html';
                break;
            case 'instructor':
                window.location.href = 'pages/instructor.html';
                break;
            case 'student':
            default:
                window.location.href = 'pages/student.html';
        }
    }

    // Toggle password visibility
    togglePasswordVisibility(e) {
        const button = e.currentTarget;
        const input = button.previousElementSibling;
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Show error message
    showError(message) {
        const errorDiv = document.getElementById('errorMessage');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Hide after 5 seconds
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        } else {
            alert(message); // Fallback
        }
    }

    // Show success message
    showSuccess(message) {
        const errorDiv = document.getElementById('errorMessage');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.className = 'alert alert-success';
            errorDiv.style.display = 'block';
            
            // Hide after 3 seconds
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 3000);
        } else {
            alert(message); // Fallback
        }
    }

    // Update UI based on authentication state
    updateUIForAuthState(isLoggedIn) {
        // This would be implemented to show/hide elements based on auth state
        // For example, show user name, hide login/register buttons, etc.
        if (isLoggedIn && this.currentUser) {
            // Update user info in the UI if elements exist
            const userInfoElements = document.querySelectorAll('.user-info');
            userInfoElements.forEach(el => {
                const nameEl = el.querySelector('.user-name');
                const roleEl = el.querySelector('.user-role');
                
                if (nameEl) nameEl.textContent = `${this.currentUser.firstName} ${this.currentUser.lastName}`;
                if (roleEl) roleEl.textContent = this.currentUser.role.charAt(0).toUpperCase() + this.currentUser.role.slice(1);
            });
            
            // Update avatar if it exists
            const avatarElements = document.querySelectorAll('.avatar');
            avatarElements.forEach(avatar => {
                if (!avatar.hasChildNodes()) { // Only update if avatar is empty
                    const initials = `${this.currentUser.firstName.charAt(0)}${this.currentUser.lastName.charAt(0)}`;
                    avatar.textContent = initials;
                }
            });
        }
    }
}

// Initialize auth when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.auth = new Auth();
    
    // Redirect to dashboard if already logged in and on auth pages
    const isAuthPage = window.location.pathname.includes('login.html') || 
                      window.location.pathname.includes('register.html');
                      
    if (isAuthPage && window.auth.currentUser) {
        window.auth.redirectToDashboard(window.auth.currentUser.role);
    }
    
    // Redirect to login if not authenticated and on protected page
    const isProtectedPage = window.location.pathname.includes('pages/');
    if (isProtectedPage && !window.auth.currentUser) {
        window.location.href = '../login.html';
    }
});
