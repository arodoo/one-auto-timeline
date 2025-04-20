// filepath: d:\zProyectos\01One\auto\constant-form\httpdocs\panel\Constats\constant-form\JS\Section_3.js
// Only create the handler if it doesn't already exist
if (!window.section3Handler) {
    class Section3DataHandler {
        constructor() {
            // Save original data immediately to prevent loss
            if (window.section3FormData) {
                this.originalData = JSON.parse(JSON.stringify(window.section3FormData));
            } else {
                this.originalData = null;
            }
            
            // Initialize on DOM ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.init());
            } else {
                this.init();
            }
        }
        
        init() {
            // Restore data if needed
            this.ensureDataAvailable();
            
            // Only proceed if we have data and are in jumelage mode
            if (window.section3FormData && window.isJumelageMode) {
                this.populateFormData(window.section3FormData);
                this.populateSpecialFields(window.section3FormData);
            }
            
            // Initialize vehicle controls
            this.initVehicleControls();
            
            // Setup observer for delayed field loading
            this.initializeObserver();
        }
        
        ensureDataAvailable() {
            if (!window.section3FormData && this.originalData) {
                window.section3FormData = JSON.parse(JSON.stringify(this.originalData));
                return true;
            }
            return !!window.section3FormData;
        }
        
        populateFormData(data) {
            // Get all fields with data-db-name attributes starting with s3_
            const fields = document.querySelectorAll('[data-db-name^="s3_"]');
            
            fields.forEach(input => {
                const dbName = input.getAttribute('data-db-name');
                if (dbName && data[dbName] !== undefined) {
                    if (input.type === 'checkbox') {
                        input.checked = data[dbName] === '1' || 
                                        data[dbName] === 'yes' || 
                                        data[dbName] === true;
                    } else if (input.type === 'radio') {
                        input.checked = input.value === data[dbName];
                    } else {
                        // Set default "France" for country fields if empty
                        if ((dbName === 's3_insured_country' || dbName === 's3_driver_country') && 
                            (!data[dbName] || data[dbName] === '')) {
                            input.value = 'France';
                            this.storeInLocalStorage(input.id, dbName, 'France');
                        } else {
                            input.value = data[dbName];
                            this.storeInLocalStorage(input.id, dbName, data[dbName]);
                        }
                    }
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });
        }
        
        storeInLocalStorage(inputId, dbName, value) {
            if (!inputId) return;
            
            localStorage.setItem(inputId, JSON.stringify({
                table: 'constats_vehicle_b',
                dbName: dbName,
                value: value
            }));
        }
        
        populateSpecialFields(data) {
            // Insurance company name
            if (data.s3_insurance_name) {
                const field = document.querySelector('input[name="insuranceCompanyB"]');
                if (field) {
                    field.value = data.s3_insurance_name;
                    this.storeInLocalStorage(field.id, 's3_insurance_name', data.s3_insurance_name);
                }
            }
            
            // Insurance contract number
            if (data.s3_insurance_contract) {
                const field = document.querySelector('input[name="policyNumberB"]');
                if (field) {
                    field.value = data.s3_insurance_contract;
                    this.storeInLocalStorage(field.id, 's3_insurance_contract', data.s3_insurance_contract);
                }
            }
            
            // Green card number
            if (data.s3_insurance_green_card) {
                const field = document.querySelector('input[name="greenCardNumberB"]');
                if (field) {
                    field.value = data.s3_insurance_green_card;
                    this.storeInLocalStorage(field.id, 's3_insurance_green_card', data.s3_insurance_green_card);
                }
            }
            
            // Insurance validity dates - convert timestamps to YYYY-MM-DD
            if (data.s3_insurance_valid_from) {
                const field = document.querySelector('input[name="validFromB"]');
                if (field) {
                    const date = new Date(data.s3_insurance_valid_from * 1000);
                    const dateStr = date.toISOString().split('T')[0];
                    field.value = dateStr;
                    this.storeInLocalStorage(field.id, 's3_insurance_valid_from', dateStr);
                }
            }
            
            if (data.s3_insurance_valid_to) {
                const field = document.querySelector('input[name="validToB"]');
                if (field) {
                    const date = new Date(data.s3_insurance_valid_to * 1000);
                    const dateStr = date.toISOString().split('T')[0];
                    field.value = dateStr;
                    this.storeInLocalStorage(field.id, 's3_insurance_valid_to', dateStr);
                }
            }
            
            // Agency information
            if (data.s3_agency_name) {
                const field = document.querySelector('input[name="agencyNameB"]');
                if (field) {
                    field.value = data.s3_agency_name;
                    this.storeInLocalStorage(field.id, 's3_agency_name', data.s3_agency_name);
                }
            }
            
            if (data.s3_insurance_agency) {
                const field = document.querySelector('input[name="agencyOfficeB"]');
                if (field) {
                    field.value = data.s3_insurance_agency;
                    this.storeInLocalStorage(field.id, 's3_insurance_agency', data.s3_insurance_agency);
                }
            }
            
            // Driver license fields
            if (data.s3_license_number) {
                const field = document.querySelector('[data-db-name="s3_license_number"]');
                if (field) {
                    field.value = data.s3_license_number;
                    this.storeInLocalStorage(field.id, 's3_license_number', data.s3_license_number);
                }
            }
            
            if (data.s3_license_category) {
                const field = document.querySelector('[data-db-name="s3_license_category"]');
                if (field) {
                    field.value = data.s3_license_category;
                    this.storeInLocalStorage(field.id, 's3_license_category', data.s3_license_category);
                }
            }
            
            // Handle dates that need timestamp conversion
            if (data.s3_license_valid_until) {
                const field = document.querySelector('[data-db-name="s3_license_valid_until"]');
                if (field) {
                    const date = new Date(data.s3_license_valid_until * 1000);
                    const dateStr = date.toISOString().split('T')[0];
                    field.value = dateStr;
                    this.storeInLocalStorage(field.id, 's3_license_valid_until', dateStr);
                }
            }
            
            if (data.s3_driver_birthdate) {
                const field = document.querySelector('[data-db-name="s3_driver_birthdate"]');
                if (field) {
                    const date = new Date(data.s3_driver_birthdate * 1000);
                    const dateStr = date.toISOString().split('T')[0];
                    field.value = dateStr;
                    this.storeInLocalStorage(field.id, 's3_driver_birthdate', dateStr);
                }
            }
            
            // Driver country field
            if (data.s3_driver_country) {
                const field = document.querySelector('[data-db-name="s3_driver_country"]');
                if (field) {
                    field.value = data.s3_driver_country;
                    this.storeInLocalStorage(field.id, 's3_driver_country', data.s3_driver_country);
                }
            }
        }
        
        initializeObserver() {
            // Set up observer for delayed DOM loading
            const observer = new MutationObserver((mutations) => {
                // Check if any form fields have appeared
                const firstField = document.querySelector('[data-db-name^="s3_"]');
                
                if (firstField && this.ensureDataAvailable() && window.isJumelageMode) {
                    this.populateFormData(window.section3FormData);
                    this.populateSpecialFields(window.section3FormData);
                    
                    // Stop observing once we've populated fields
                    observer.disconnect();
                }
            });

            // Start observing the document body for DOM changes
            observer.observe(document.body, { childList: true, subtree: true });
        }
        
        initVehicleControls() {
            const initControls = () => {
                const elements = {
                    motorizedRadio: document.getElementById('motorizedRadioB'),
                    trailerRadio: document.getElementById('trailerRadioB'),
                    moteurSection: document.getElementById('moteurSectionB'),
                    remorqueSection: document.getElementById('remorqueSectionB')
                };

                if (!Object.values(elements).every(el => el)) {
                    setTimeout(initControls, 100);
                    return;
                }

                this.setupVehicleTypeToggle(elements);
            };

            initControls();
        }
        
        setupVehicleTypeToggle(elements) {
            const { motorizedRadio, trailerRadio, moteurSection, remorqueSection } = elements;
            const moteurInputs = moteurSection.querySelectorAll('input');
            const remorqueInputs = remorqueSection.querySelectorAll('input');

            const toggleSections = (showMoteur) => {
                moteurSection.style.opacity = showMoteur ? '1' : '0.5';
                remorqueSection.style.opacity = showMoteur ? '0.5' : '1';
                
                this.toggleInputs(moteurInputs, !showMoteur);
                this.toggleInputs(remorqueInputs, showMoteur);
            };

            motorizedRadio.addEventListener('change', () => toggleSections(true));
            trailerRadio.addEventListener('change', () => toggleSections(false));
            toggleSections(true); // Default to motorized
        }
        
        toggleInputs(inputs, disable) {
            inputs.forEach(input => {
                input.disabled = disable;
                if (disable) {
                    input.value = '';
                    if (input.id) {
                        localStorage.removeItem(input.id);
                    }
                }
            });
        }
    }
    
    // Create global instance to handle Section 3
    window.section3Handler = new Section3DataHandler();
    
    // For backward compatibility, implement the pollForFormFields function
    window.pollForFormFields = function() {
        if (window.section3Handler && window.section3Handler.ensureDataAvailable()) {
            window.section3Handler.populateFormData(window.section3FormData);
            window.section3Handler.populateSpecialFields(window.section3FormData);
            return true;
        }
        return false;
    };
}