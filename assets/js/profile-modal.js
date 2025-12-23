// Reusable Profile Modal Functionality
class ProfileModal {
  constructor() {
    this.currentUser = JSON.parse(localStorage.getItem('user') || localStorage.getItem('currentUser') || 'null');
    this.modalElement = null;
    this.profileForm = null;
    this.editBtn = null;
    this.saveBtn = null;
  }

  // Initialize the profile modal on a page
  init() {
    // Find modal elements
    this.modalElement = document.getElementById('profileModal');
    this.profileForm = document.getElementById('profileForm');
    this.editBtn = document.getElementById('editProfileBtn');
    this.saveBtn = document.getElementById('saveProfileBtn');

    // If elements exist, attach event listeners
    if (this.modalElement && this.profileForm && this.editBtn && this.saveBtn) {
      this.attachEventListeners();
    }

    // Set up profile trigger if it exists
    const profileTrigger = document.getElementById('profileTrigger');
    if (profileTrigger) {
      profileTrigger.addEventListener('click', () => this.showProfileModal());
    }
  }

  // Attach event listeners
  attachEventListeners() {
    this.editBtn.addEventListener('click', () => this.setProfileEditable(true));
    this.saveBtn.addEventListener('click', () => this.saveProfile());
  }

  // Show profile modal
  showProfileModal() {
    if (!this.currentUser) {
      alert('User not found in session');
      return;
    }

    this.populateProfileForm();
    const modal = new bootstrap.Modal(this.modalElement);
    modal.show();
  }

  // Populate profile form with user data
  populateProfileForm() {
    if (!this.currentUser) return;

    document.getElementById('profileId').value = this.currentUser.id;
    document.getElementById('profileName').value = this.currentUser.name || '';
    document.getElementById('profileEmail').value = this.currentUser.email || '';
    document.getElementById('profileRole').value = this.currentUser.role || '';
    document.getElementById('profilePhone').value = this.currentUser.phone || '';
    document.getElementById('profileBio').value = this.currentUser.bio || '';
    
    const photoUrl = this.currentUser.profile_pic || this.currentUser.photo_url || this.currentUser.photo || '';
    const img = document.getElementById('profilePhotoPreview');
    
    if (photoUrl) {
      img.src = photoUrl.startsWith('http') ? photoUrl : `../${photoUrl}`;
    } else {
      img.src = `https://api.dicebear.com/7.x/initials/svg?seed=${encodeURIComponent(this.currentUser.name || 'User')}`;
    }
    
    this.setProfileEditable(false);
  }

  // Set profile form fields editable or not
  setProfileEditable(enabled) {
    const fieldIds = ['profilePhoto', 'profileName', 'profileEmail', 'profileRole', 'profilePhone', 'profileBio'];
    fieldIds.forEach(id => {
      const el = document.getElementById(id);
      if (el) el.disabled = !enabled;
    });
    this.saveBtn.disabled = !enabled;
  }

  // Save profile changes
  async saveProfile() {
    if (!this.currentUser) return;

    const formData = new FormData(this.profileForm);
    formData.append('user_id', this.currentUser.id);

    try {
      const res = await fetch('../backend/update_profile.php', {
        method: 'POST',
        body: formData
      });
      const out = await res.json();
      
      if (out.status === 'success') {
        // Update localStorage user with new fields if returned
        const updated = out.user || {
          id: formData.get('user_id') || this.currentUser.id,
          name: formData.get('name') || this.currentUser.name,
          email: formData.get('email') || this.currentUser.email,
          role: this.currentUser.role,
          phone: formData.get('phone') || this.currentUser.phone,
          bio: formData.get('bio') || this.currentUser.bio,
          profile_pic: out.profile_pic || this.currentUser.profile_pic || ''
        };
        
        // Update localStorage
        localStorage.setItem('user', JSON.stringify({ ...this.currentUser, ...updated }));
        localStorage.setItem('currentUser', JSON.stringify({ ...this.currentUser, ...updated }));
        
        this.setProfileEditable(false);
        alert('Profile updated successfully');
        
        // Update UI elements that might show user info
        this.updateUIElements(updated);
      } else {
        alert(out.message || 'Failed to update profile');
      }
    } catch (err) {
      alert('Network error');
      console.error(err);
    }
  }

  // Update UI elements that display user info
  updateUIElements(updatedUser) {
    // Update welcome text if exists
    const welcomeText = document.getElementById('welcomeText');
    if (welcomeText) {
      welcomeText.textContent = `Welcome, ${updatedUser.name || 'User'}`;
    }
    
    // Update profile initial if exists
    const profileInitial = document.getElementById('profileInitial');
    if (profileInitial) {
      profileInitial.textContent = (updatedUser.name ? updatedUser.name[0] : 'U').toUpperCase();
    }
    
    // Update avatar if exists
    const avatarImg = document.getElementById('avatarImg') || document.getElementById('avatar');
    if (avatarImg) {
      if (updatedUser.profile_pic) {
        avatarImg.src = updatedUser.profile_pic.startsWith('http') ? 
          updatedUser.profile_pic : `../${updatedUser.profile_pic}`;
      } else {
        avatarImg.src = `https://api.dicebear.com/7.x/initials/svg?seed=${encodeURIComponent(updatedUser.name || 'User')}`;
      }
    }
  }
}

// Initialize profile modal when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  const profileModal = new ProfileModal();
  profileModal.init();
});