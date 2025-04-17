// Utility functions
function loadCheckboxState() {
    // Load saved checkbox states from localStorage
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        const key = checkbox.getAttribute('id');
        const savedData = localStorage.getItem(key);
        if (savedData) {
            const data = JSON.parse(savedData);
            checkbox.checked = data.value === 'x';
        }
    });
}

function updateCheckCount() {
    const checkboxesA = Array.from(document.querySelectorAll('input[type="checkbox"][data-db-name*="_a"]'));
    const checkboxesB = Array.from(document.querySelectorAll('input[type="checkbox"][data-db-name*="_b"]'));
    
    const checksA = checkboxesA.filter(cb => cb.checked).length;
    const checksB = checkboxesB.filter(cb => cb.checked).length;
    
    // Update hidden inputs
    document.getElementById('sc4-input35').value = checksA.toString();
    document.getElementById('sc4-input36').value = checksB.toString();
    
    // Update display spans
    document.getElementById('checkCount_A').textContent = checksA;
    document.getElementById('checkCount_B').textContent = checksB;
    
    // Store in localStorage both as utility and form data
    localStorage.setItem('util_count_a', JSON.stringify({
        table: 'constats_main',
        dbName: 's4_check_count_a',
        value: checksA.toString()
    }));
    localStorage.setItem('util_count_b', JSON.stringify({
        table: 'constats_main',
        dbName: 's4_check_count_b',
        value: checksB.toString()
    }));
    
    // Store as regular form inputs for database storage
    localStorage.setItem('sc4-input35', JSON.stringify({
        table: 'constats_main',
        dbName: 's4_check_count_a',
        value: checksA.toString()
    }));
    localStorage.setItem('sc4-input36', JSON.stringify({
        table: 'constats_main',
        dbName: 's4_check_count_b',
        value: checksB.toString()
    }));
}

// Function moved from Section4Jumelage.js
function handleJumelageState() {
    // Check if this is a jumelage constat
    const jumelageEmail = localStorage.getItem('meta-sc3-jumelage_email');
    
    if (jumelageEmail) {
        // Show and update banner
        const banner = document.querySelector('.jumelage-info-message');
        banner.style.display = 'block';
        banner.querySelector('.jumelage-email').textContent = jumelageEmail;

        // Wait for DOM to be fully loaded
        const checkboxes = document.querySelectorAll('input[type="checkbox"][data-db-name*="_b"]');
        if (checkboxes.length === 0) {
            // If checkboxes aren't loaded yet, retry in 100ms
            setTimeout(handleJumelageState, 100);
            return;
        }

        // Disable all B checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.disabled = true;
            // Add visual indication
            checkbox.parentElement.style.opacity = '0.5';
            checkbox.parentElement.style.cursor = 'not-allowed';
        });
    }
}

// Initialize immediately since this script is loaded with the section
(function initialize() {
    loadCheckboxState();
    updateCheckCount();
    handleJumelageState(); // This function is now defined above
    
    // Add change listeners
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateCheckCount);
    });
})();