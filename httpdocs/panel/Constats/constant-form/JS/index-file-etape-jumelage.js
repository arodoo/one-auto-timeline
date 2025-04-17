/**
 * index-file-etape-jumelage.js - Handles UI modifications for jumelage mode
 */

// Store original values to restore later if needed
const originalTotalSections = totalSections;
let originalNavigateSection = null; // We'll capture the original function when initializing

// Define the sections sequence for jumelage mode
const jumelageSections = [3, 4, 7];

// Initialize jumelage mode
function initJumelageMode() {
    // Map of original section numbers to displayed section numbers in jumelage mode
    const sectionMapping = {
        3: 1, // Section 3 becomes Section 1
        4: 2, // Section 4 becomes Section 2
        7: 3  // Section 7 becomes Section 3
    };
    
    // Note: Banner is now added via PHP in index-file-etape.php, so we don't need to add it here
    // Remove the addJumelageBanner() call
    
    // Hide navigation items for sections not needed in jumelage mode
    document.querySelectorAll('.nav-wizard .nav-link').forEach(navLink => {
        const sectionId = navLink.id;
        const sectionNumber = parseInt(sectionId.replace('section-', ''));
        
        if (!jumelageSections.includes(sectionNumber)) {
            navLink.style.display = 'none';
        }
    });
    
    // Store the original navigate function before we override it
    originalNavigateSection = window.navigateSection;
    
    // Override the navigate function with a completely new one for jumelage mode
    window.navigateSection = function(direction) {
        // Find current index in jumelage sections
        const currentIndex = jumelageSections.indexOf(parseInt(currentSection));
        
        if (currentIndex === -1) {
            loadSection(`/panel/Constats/constant-form/FormHandler/Section_${jumelageSections[0]}.php`, jumelageSections[0]);
            return;
        }
        
        let nextIndex;
        if (direction === 'next') {
            nextIndex = currentIndex + 1;
            if (nextIndex >= jumelageSections.length) {
                return; // We're at the last section, don't navigate further
            }
        } else if (direction === 'prev') {
            nextIndex = currentIndex - 1;
            if (nextIndex < 0) {
                return; // We're at the first section, don't navigate back
            }
        }
        
        const nextSection = jumelageSections[nextIndex];
        loadSection(`/panel/Constats/constant-form/FormHandler/Section_${nextSection}.php`, nextSection);
    };
    
    // Complete replacement of the updateNavigationButtons function for jumelage mode
    window.updateNavigationButtons = function(sectionNumber) {
        const prevButton = document.querySelector('button[onclick="navigateSection(\'prev\')"]');
        const nextButton = document.querySelector('button[onclick="navigateSection(\'next\')"]');
        
        // Find the index of the current section in our jumelage sections array
        const currentIndex = jumelageSections.indexOf(parseInt(sectionNumber));
        
        // First button (Précédent)
        if (currentIndex <= 0) {
            prevButton.disabled = true;
            prevButton.classList.add('disabled');
        } else {
            prevButton.disabled = false;
            prevButton.classList.remove('disabled');
        }
        
        // Last button (Suivant)
        if (currentIndex >= jumelageSections.length - 1) {
            nextButton.disabled = true;
            nextButton.classList.add('disabled');
        } else {
            nextButton.disabled = false;
            nextButton.classList.remove('disabled');
        }
    };
    
    // Override updateActiveSectionStyle to handle the translation between real sections and display numbers
    const originalUpdateActiveSectionStyle = window.updateActiveSectionStyle;
    window.updateActiveSectionStyle = function(sectionNumber) {
        document.querySelectorAll('.nav-wizard .nav-link').forEach(link => {
            link.classList.remove('done');
        });
        
        const currentNav = document.querySelector(`#section-${sectionNumber}`);
        if (currentNav) {
            currentNav.classList.add('done');
        }
    };
    
    // We don't need to call loadSection here since it's handled by the document.ready function now
    
    // But we should update navigation buttons for the current section
    const sectionToActivate = jumelageSections[0]; // Start with first jumelage section
    window.updateNavigationButtons(sectionToActivate);
}

// Check if jumelage mode is active - renamed to avoid conflict with global constant
function checkJumelageMode() {
    return document.body.classList.contains('jumelage-mode');
}

// Function to disable certain fields in jumelage mode
function disableNonEditableFields() {
    // This will depend on the specific fields that need to be disabled
    // For now, we'll disable fields that should only be filled by User A
    // We'll implement this as needed
}

// Initialize on document ready - this might be too late, so we'll also call it immediately
document.addEventListener('DOMContentLoaded', function() {
    if (document.body.classList.contains('jumelage-mode')) {
        initJumelageMode();
    }
});

// Call init immediately in case DOM is already loaded
if (document.body.classList.contains('jumelage-mode')) {
    initJumelageMode();
}

// Export functions for external use
window.jumelageMode = {
    init: initJumelageMode,
    isActive: checkJumelageMode  // Use the renamed function here
};
