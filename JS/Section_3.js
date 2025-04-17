// Initialize the vehicle type controls for Section 3
function initVehicleControlsB() {
    const motorizedRadio = document.getElementById('motorizedRadioB');
    const trailerRadio = document.getElementById('trailerRadioB');
    const moteurSection = document.getElementById('moteurSectionB');
    const remorqueSection = document.getElementById('remorqueSectionB');

    if (!motorizedRadio || !trailerRadio || !moteurSection || !remorqueSection) {
        // Elements not ready yet, try again in 100ms
        setTimeout(initVehicleControlsB, 100);
        return;
    }

    const moteurInputs = moteurSection.querySelectorAll('input');
    const remorqueInputs = remorqueSection.querySelectorAll('input');

    function clearLocalStorageForInputs(inputs) {
        inputs.forEach(input => {
            const id = input.id;
            if (id && localStorage.getItem(id)) {
                localStorage.removeItem(id);
            }
        });
    }

    function toggleSections(showMoteur) {
        moteurSection.style.opacity = showMoteur ? '1' : '0.5';
        remorqueSection.style.opacity = showMoteur ? '0.5' : '1';
        
        moteurInputs.forEach(input => {
            input.disabled = !showMoteur;
            if (!showMoteur) {
                input.value = '';
                clearLocalStorageForInputs(moteurInputs);
            }
        });
        
        remorqueInputs.forEach(input => {
            input.disabled = showMoteur;
            if (showMoteur) {
                input.value = '';
                clearLocalStorageForInputs(remorqueInputs);
            }
        });
    }

    motorizedRadio.addEventListener('change', () => toggleSections(true));
    trailerRadio.addEventListener('change', () => toggleSections(false));

    // Initial state
    toggleSections(true);
}

// Start the initialization
initVehicleControlsB();