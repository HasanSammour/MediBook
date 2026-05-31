/**
 * MediBook - Main JavaScript File
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // MOBILE MENU TOGGLE (Hamburger for all pages)
    // ============================================
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Simple toggle - check current display
            if (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') {
                mobileMenu.style.display = 'block';
            } else {
                mobileMenu.style.display = 'none';
            }
        });
    }
    
    // ============================================
    // MOBILE SIDEBAR TOGGLE (For Dashboard Pages)
    // ============================================
    const sidebarToggle = document.getElementById('mobileSidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            sidebar.classList.toggle('active');
        });
    }
    
    // ============================================
    // Close mobile menu when clicking outside
    // ============================================
    document.addEventListener('click', function(event) {
        if (mobileMenu && mobileMenuBtn) {
            if (mobileMenu.style.display === 'block') {
                if (!mobileMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    mobileMenu.style.display = 'none';
                }
            }
        }
    });
});