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


// Auto-populate Section 3 form fields when in jumelage mode and user is B
document.addEventListener('DOMContentLoaded', function() {
    console.log("Section 3 script loaded");
    console.log("Jumelage mode:", window.isJumelageMode);
    console.log("Form data available:", !!window.section3FormData && Object.keys(window.section3FormData).length > 0);

    // Check if we're in jumelage mode and have form data to auto-fill
    if (window.isJumelageMode && window.section3FormData && Object.keys(window.section3FormData).length > 0) {
        console.log("Auto-filling section 3 form with user data");
        
        // Loop through all form inputs with data-db-name attribute
        document.querySelectorAll('[data-db-name]').forEach(function(element) {
            const dbName = element.getAttribute('data-db-name');
            
            if (dbName && window.section3FormData[dbName] !== undefined) {
                if (element.type === 'checkbox') {
                    element.checked = window.section3FormData[dbName] === '1' || 
                                     window.section3FormData[dbName] === 'yes' || 
                                     window.section3FormData[dbName] === true;
                } else if (element.type === 'radio') {
                    if (element.value === window.section3FormData[dbName]) {
                        element.checked = true;
                    }
                } else {
                    element.value = window.section3FormData[dbName];
                }
                
                // Save to localStorage to ensure form persistence
                if (element.id) {
                    localStorage.setItem(element.id, element.type === 'checkbox' ? 
                        (element.checked ? 'true' : 'false') : element.value);
                }
                
                // Trigger change event to ensure dependent fields update
                const event = new Event('change', { bubbles: true });
                element.dispatchEvent(event);
            }
        });
        
        // Fill insurance company fields
        if (window.section3FormData['s3_insurance_name']) {
            const insuranceCompanyField = document.querySelector('input[name="insuranceCompanyB"]');
            if (insuranceCompanyField) {
                insuranceCompanyField.value = window.section3FormData['s3_insurance_name'];
                if (insuranceCompanyField.id) {
                    localStorage.setItem(insuranceCompanyField.id, insuranceCompanyField.value);
                }
            }
        }
        
        // Fill insurance contract number
        if (window.section3FormData['s3_insurance_contract']) {
            const contractNumberField = document.querySelector('input[name="policyNumberB"]');
            if (contractNumberField) {
                contractNumberField.value = window.section3FormData['s3_insurance_contract'];
                if (contractNumberField.id) {
                    localStorage.setItem(contractNumberField.id, contractNumberField.value);
                }
            }
        }
        
        // Fill green card number
        if (window.section3FormData['s3_insurance_green_card']) {
            const greenCardField = document.querySelector('input[name="greenCardNumberB"]');
            if (greenCardField) {
                greenCardField.value = window.section3FormData['s3_insurance_green_card'];
                if (greenCardField.id) {
                    localStorage.setItem(greenCardField.id, greenCardField.value);
                }
            }
        }
        
        // Handle validity dates
        if (window.section3FormData['s3_insurance_valid_from']) {
            const validFromField = document.querySelector('input[name="validFromB"]');
            if (validFromField) {
                // Convert timestamp to date string in the format YYYY-MM-DD
                const date = new Date(window.section3FormData['s3_insurance_valid_from'] * 1000);
                const dateStr = date.toISOString().split('T')[0];
                validFromField.value = dateStr;
                if (validFromField.id) {
                    localStorage.setItem(validFromField.id, validFromField.value);
                }
            }
        }
        
        if (window.section3FormData['s3_insurance_valid_to']) {
            const validToField = document.querySelector('input[name="validToB"]');
            if (validToField) {
                // Convert timestamp to date string in the format YYYY-MM-DD
                const date = new Date(window.section3FormData['s3_insurance_valid_to'] * 1000);
                const dateStr = date.toISOString().split('T')[0];
                validToField.value = dateStr;
                if (validToField.id) {
                    localStorage.setItem(validToField.id, validToField.value);
                }
            }
        }
        
        // Fill agency information
        if (window.section3FormData['s3_agency_name']) {
            const agencyNameField = document.querySelector('input[name="agencyNameB"]');
            if (agencyNameField) {
                agencyNameField.value = window.section3FormData['s3_agency_name'];
                if (agencyNameField.id) {
                    localStorage.setItem(agencyNameField.id, agencyNameField.value);
                }
            }
        }
        
        console.log("Section 3 auto-fill complete");
    }
});