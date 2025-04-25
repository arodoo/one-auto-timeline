/**
 * User Data Loader - Utility for loading user personal information into forms
 */
class UserDataLoader {
    /**
     * Load user data from the server
     * @returns {Promise} A promise that resolves with the user data
     */
    async loadUserData() {
        try {
            const url = `/panel/Carte-grise/panier-recap/get-membres-data-ajax.php`;
                
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            if (data.status === 200 && data.data) {
                return data.data;
            } else {
                return null;
            }
        } catch (error) {
            return null;
        }
    }

    /**
     * Populate a form with user data
     * @param {Object} userData - The user data to populate the form with
     * @param {string} formPrefix - Prefix for form field IDs
     */
    populateForm(userData, formPrefix = 'fp-') {
        if (!userData) {
            return false;
        }

        // Define mapping between server field names and form field IDs
        const fieldMapping = {
            'civilite': 'civilite',
            'nom': 'nom',
            'prenom': 'prenom',
            'nom_usage': 'nomUsage',
            'complement_adresse': 'complementAdresse',
            'code_postal': 'codePostal',
            'ville': 'ville',
            'pays': 'pays',
            'telephone': 'telephone'
        };

        // Use the mapping to find the correct form field for each data field
        let populated = false;
        Object.entries(userData).forEach(([field, value]) => {
            // Get the corresponding form field ID
            const formFieldId = fieldMapping[field];
            if (!formFieldId) return;
            
            const element = document.getElementById(`${formPrefix}${formFieldId}`);
            
            if (element && value) {
                if (element.tagName === 'SELECT') {
                    // Handle select elements
                    const options = element.options;
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].value === value || options[i].text === value) {
                            element.selectedIndex = i;
                            break;
                        }
                    }
                } else {
                    // Handle input elements
                    element.value = value;
                }
                populated = true;
            }
        });
        
        return populated;
    }
}

// Export as singleton
export default new UserDataLoader();
