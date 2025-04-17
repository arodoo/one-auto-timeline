// Only create the handler if it doesn't already exist
if (!window.section2Handler) {
    class Section2DataHandler {
        constructor() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.init());
            } else {
                this.init();
            }
        }

        init() {
            if (window.section2FormData) {
                this.populateFormData(window.section2FormData);
            }
            this.initializeObserver();
            this.initVehicleControls();
        }

        populateFormData(data) {
            console.log('Populating form with data:', data);
            Object.entries(data).forEach(([dbName, value]) => {
                const input = document.querySelector(`[data-db-name="${dbName}"]`);
                if (input) {
                    input.value = value;
                    this.storeInLocalStorage(input.id, dbName, value);
                    console.log(`Set ${dbName} to ${value}`);
                } else {
                    console.log(`Input not found for ${dbName}`);
                }
            });
        }

        storeInLocalStorage(inputId, dbName, value) {
            localStorage.setItem(inputId, JSON.stringify({
                table: 'constats_vehicle_a',
                dbName: dbName,
                value: value
            }));
        }

        initializeObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        console.log('Section is visible, checking for data');
                        if (window.section2FormData) {
                            console.log('Found form data, populating');
                            this.populateFormData(window.section2FormData);
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
    }
    
    // Store the instance globally
    window.section2Handler = new Section2DataHandler();
}
