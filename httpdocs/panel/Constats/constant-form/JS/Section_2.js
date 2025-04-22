// Only create the handler if it doesn't already exist
if (!window.section2Handler) {
    class Section2DataHandler {
        constructor() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.init());
            } else {
                this.init();
            }
        } init() {
            if (window.section2FormData) {
                this.populateFormData(window.section2FormData);
                this.populateInsuranceFields(window.section2FormData);
                this.populateDriverLicenseFields(window.section2FormData);
            }
            this.initializeObserver();
            this.initVehicleControls();
        } populateFormData(data) {
            Object.entries(data).forEach(([dbName, value]) => {
                const input = document.querySelector(`[data-db-name="${dbName}"]`);
                if (input) {
                    // Set default "France" for country fields if empty
                    if ((dbName === 's2_insured_country' || dbName === 's2_driver_country') && (!value || value === '')) {
                        input.value = 'France';
                        this.storeInLocalStorage(input.id, dbName, 'France');
                    } else {
                        input.value = value;
                        this.storeInLocalStorage(input.id, dbName, value);
                    }
                }
            });
        }

        storeInLocalStorage(inputId, dbName, value) {
            localStorage.setItem(inputId, JSON.stringify({
                table: 'constats_vehicle_a',
                dbName: dbName,
                value: value
            }));
        } initializeObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (window.section2FormData) {
                            this.populateFormData(window.section2FormData);
                            this.populateInsuranceFields(window.section2FormData);
                            this.populateDriverLicenseFields(window.section2FormData);
                        }
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.1 });

            const container = document.querySelector('.container');
            if (container) {
                observer.observe(container);
            } else {
            }
        }

        initVehicleControls() {
            const initControls = () => {
                const elements = {
                    motorizedRadio: document.getElementById('motorizedRadio'),
                    trailerRadio: document.getElementById('trailerRadio'),
                    moteurSection: document.getElementById('moteurSection'),
                    remorqueSection: document.getElementById('remorqueSection')
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
            toggleSections(true);
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

        populateInsuranceFields(data) {
            // Handle insurance company field
            if (data.s2_insurance_name) {
                const insuranceCompanyField = document.querySelector('input[name="insuranceCompanyA"]');
                if (insuranceCompanyField) {
                    insuranceCompanyField.value = data.s2_insurance_name;
                    this.storeInLocalStorage(insuranceCompanyField.id, 'insuranceCompanyA', data.s2_insurance_name);
                }
            }

            // Handle policy number field
            if (data.s2_insurance_contract) {
                const policyField = document.querySelector('input[name="policyNumberA"]');
                if (policyField) {
                    policyField.value = data.s2_insurance_contract;
                    this.storeInLocalStorage(policyField.id, 'policyNumberA', data.s2_insurance_contract);
                }
            }

            // Handle green card number field
            if (data.s2_insurance_green_card) {
                const greenCardField = document.querySelector('input[name="greenCardNumberA"]');
                if (greenCardField) {
                    greenCardField.value = data.s2_insurance_green_card;
                    this.storeInLocalStorage(greenCardField.id, 'greenCardNumberA', data.s2_insurance_green_card);
                }
            }

            // Handle validity dates
            if (data.s2_insurance_valid_from) {
                const validFromField = document.querySelector('[data-db-name="s2_insurance_valid_from"]');
                if (validFromField) {
                    // Convert Unix timestamp to YYYY-MM-DD
                    const date = new Date(data.s2_insurance_valid_from * 1000);
                    const dateStr = date.toISOString().split('T')[0];
                    validFromField.value = dateStr;
                    this.storeInLocalStorage(validFromField.id, 's2_insurance_valid_from', dateStr);
                }
            }

            if (data.s2_insurance_valid_to) {
                const validToField = document.querySelector('[data-db-name="s2_insurance_valid_to"]');
                if (validToField) {
                    // Convert Unix timestamp to YYYY-MM-DD
                    const date = new Date(data.s2_insurance_valid_to * 1000);
                    const dateStr = date.toISOString().split('T')[0];
                    validToField.value = dateStr;
                    this.storeInLocalStorage(validToField.id, 's2_insurance_valid_to', dateStr);
                }
            }
            // Handle agency office information (Agence ou bureau ou courtier)
            if (data.s2_insurance_agency) {
                const agencyOfficeField = document.querySelector('[data-db-name="s2_insurance_agency"]');
                if (agencyOfficeField) {
                    agencyOfficeField.value = data.s2_insurance_agency;
                    this.storeInLocalStorage(agencyOfficeField.id, 's2_insurance_agency', data.s2_insurance_agency);
                }
            }
            // Handle agency name information (Nom de l'agence)
            if (data.s2_agency_name) {
                const agencyNameField = document.querySelector('[data-db-name="s2_agency_name"]');
                if (agencyNameField) {
                    agencyNameField.value = data.s2_agency_name;
                    this.storeInLocalStorage(agencyNameField.id, 's2_agency_name', data.s2_agency_name);
                }
            }
        }

        populateDriverLicenseFields(data) {
            // Handle driver license number
            if (data.s2_license_number) {
                const licenseField = document.querySelector('[data-db-name="s2_license_number"]');
                if (licenseField) {
                    licenseField.value = data.s2_license_number;
                    this.storeInLocalStorage(licenseField.id, 's2_license_number', data.s2_license_number);
                }
            }

            // Handle driver license category
            if (data.s2_license_category) {
                const categoryField = document.querySelector('[data-db-name="s2_license_category"]');
                if (categoryField) {
                    categoryField.value = data.s2_license_category;
                    this.storeInLocalStorage(categoryField.id, 's2_license_category', data.s2_license_category);
                }
            }
            // Handle driver license valid until date - convert from UNIX timestamp to YYYY-MM-DD
            if (data.s2_license_valid_until) {
                const validUntilField = document.querySelector('[data-db-name="s2_license_valid_until"]');
                if (validUntilField) {
                    // Convert Unix timestamp to YYYY-MM-DD
                    const date = new Date(data.s2_license_valid_until * 1000);
                    const dateStr = date.toISOString().split('T')[0];
                    validUntilField.value = dateStr;
                    this.storeInLocalStorage(validUntilField.id, 's2_license_valid_until', dateStr);
                }
            }

            // Handle driver birthdate (Date de naissance) from license data
            if (data.s2_driver_birthdate) {
                const birthDateField = document.querySelector('[data-db-name="s2_driver_birthdate"]');
                if (birthDateField) {
                    // Convert Unix timestamp to YYYY-MM-DD
                    const date = new Date(data.s2_driver_birthdate * 1000);
                    const dateStr = date.toISOString().split('T')[0];
                    birthDateField.value = dateStr;
                    this.storeInLocalStorage(birthDateField.id, 's2_driver_birthdate', dateStr);
                }
            }

            // Handle driver country (Pays) from license data
            if (data.s2_driver_country) {
                const countryField = document.querySelector('[data-db-name="s2_driver_country"]');
                if (countryField) {
                    countryField.value = data.s2_driver_country;
                    this.storeInLocalStorage(countryField.id, 's2_driver_country', data.s2_driver_country);
                }
            }
        }
    }

    // Store the instance globally
    window.section2Handler = new Section2DataHandler();
}
