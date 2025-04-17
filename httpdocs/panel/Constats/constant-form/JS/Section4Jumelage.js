function handleJumelageState() {
    // Wait for DOM to be fully loaded
    const checkboxes = document.querySelectorAll('input[type="checkbox"][data-db-name*="_a"]'); // Changed to target A checkboxes
    if (checkboxes.length === 0) {
        // If checkboxes aren't loaded yet, retry in 100ms
        setTimeout(handleJumelageState, 100);
        return;
    }

    // Disable all A checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.disabled = true;
        // Add visual indication
        checkbox.parentElement.style.opacity = '0.5';
        checkbox.parentElement.style.cursor = 'not-allowed';
    });
}

// Call the function when script is loaded
handleJumelageState();