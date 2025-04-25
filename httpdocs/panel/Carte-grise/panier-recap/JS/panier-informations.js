import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';
import userDataLoader from '/panel/Carte-grise/panier-recap/JS/user-data-loader.js';

// Load user data when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listeners for form interaction
    setupFormEventListeners();
    
    // Set up the profile info loading button
    setupProfileInfoButton();
});

// Setup the button to load profile information
function setupProfileInfoButton() {
    // Check periodically for the button as it might be loaded dynamically
    const checkForButton = setInterval(() => {
        const loadProfileBtn = document.getElementById('load-profile-info');
        if (loadProfileBtn) {
            clearInterval(checkForButton);
            
            loadProfileBtn.addEventListener('click', async function() {
                try {
                    // Show loading state
                    loadProfileBtn.disabled = true;
                    loadProfileBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Chargement...';
                    
                    // Get user data
                    const userData = await userDataLoader.loadUserData();
                    
                    if (userData) {
                        // Populate form with user data
                        const populated = userDataLoader.populateForm(userData, 'fp-');
                        
                        if (populated) {
                            // Show success message
                            loadProfileBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Informations chargées';
                            loadProfileBtn.classList.remove('btn-outline-primary');
                            loadProfileBtn.classList.add('btn-success');
                            
                            // Reset button after a delay
                            setTimeout(() => {
                                loadProfileBtn.innerHTML = '<i class="fas fa-user-circle mr-2"></i> Utiliser mes informations personnelles';
                                loadProfileBtn.classList.remove('btn-success');
                                loadProfileBtn.classList.add('btn-outline-primary');
                                loadProfileBtn.disabled = false;
                            }, 3000);
                        } else {
                            handleProfileLoadError(loadProfileBtn);
                        }
                    } else {
                        handleProfileLoadError(loadProfileBtn);
                    }
                } catch (error) {
                    handleProfileLoadError(loadProfileBtn);
                }
            });
        }
    }, 500); // Check every 500ms
    
    // Stop checking after 10 seconds to prevent infinite loop
    setTimeout(() => clearInterval(checkForButton), 10000);
}

function handleProfileLoadError(button) {
    // Show error message
    button.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i> Échec du chargement';
    button.classList.remove('btn-outline-primary');
    button.classList.add('btn-danger');
    
    // Reset button after a delay
    setTimeout(() => {
        button.innerHTML = '<i class="fas fa-user-circle mr-2"></i> Utiliser mes informations personnelles';
        button.classList.remove('btn-danger');
        button.classList.add('btn-outline-primary');
        button.disabled = false;
    }, 3000);
}

function setupFormEventListeners() {
    // Save the form data to the state when the button is clicked
    const payerBtn = document.getElementById('payer-btn-process');
    if (payerBtn) {
        payerBtn.addEventListener('click', function() {
            saveFormProcess();
        });
    }

    // Change the form display based on the selected type of contact
    const particulierRadio = document.getElementById('particulier');
    const entrepriseRadio = document.getElementById('entreprise');
    
    if (particulierRadio) {
        particulierRadio.addEventListener('click', function() {
            document.getElementById('formParticulier').style.display = 'block';
            document.getElementById('formEntreprise').style.display = 'none';
        });
    }
    
    if (entrepriseRadio) {
        entrepriseRadio.addEventListener('click', function() {
            document.getElementById('formParticulier').style.display = 'none';
            document.getElementById('formEntreprise').style.display = 'block';
        });
    }

    // Add a new cotitulaire form
    const ajouterCotitulaireBtn = document.getElementById('ajouter-cotitulaire');
    if (ajouterCotitulaireBtn) {
        ajouterCotitulaireBtn.addEventListener('click', function() {
            fetch('/panel/Carte-grise/panier-recap/ajouter-cotitulaire.php')
                .then(response => response.text())
                .then(data => {
                    const uniqueId = generateUniqueId();
                    const container = document.getElementById('cotitulaires-container');
                    const div = document.createElement('div');
                    div.innerHTML = data.replace(/{{uniqueId}}/g, uniqueId);
                    container.appendChild(div);
                    document.getElementById('recap-container').classList.remove('display-none');
                    attachCotitulaireEventListeners(uniqueId);
                })
                .catch(error => console.error('Error:', error));
        });
    }
}

function generateUniqueId() {
    return '_' + Math.random().toString(36).substr(2, 9);
}

// Handle the attachment of event listeners for the cotitulaire form
function attachCotitulaireEventListeners(uniqueId) {
    document.getElementById(`particulierCotitulaire${uniqueId}`).addEventListener('click', function () {
        document.getElementById(`formParticulierCotitulaire${uniqueId}`).style.display = 'block';
        document.getElementById(`formEntrepriseCotitulaire${uniqueId}`).style.display = 'none';
    });

    document.getElementById(`entrepriseCotitulaire${uniqueId}`).addEventListener('click', function () {
        document.getElementById(`formParticulierCotitulaire${uniqueId}`).style.display = 'none';
        document.getElementById(`formEntrepriseCotitulaire${uniqueId}`).style.display = 'block';
    });
}

// Save the form data to the state
function saveFormProcess() {
    const typeContact = document.querySelector('input[name="typeContact"]:checked').id;
    formProcess.setNouvTitTypeContact(typeContact);

    if (typeContact === 'particulier') {
        formProcess.setNouvTitCivilite(document.getElementById('fp-civilite').value);
        formProcess.setNouvTitNom(document.getElementById('fp-nom').value);
        formProcess.setNouvTitPrenom(document.getElementById('fp-prenom').value);
        formProcess.setNouvTitNomUsage(document.getElementById('fp-nomUsage').value);
        formProcess.setNouvTitComplementAdresse(document.getElementById('fp-complementAdresse').value);
        formProcess.setNouvTitCodePostal(document.getElementById('fp-codePostal').value);
        formProcess.setNouvTitVille(document.getElementById('fp-ville').value);
        formProcess.setNouvTitPays(document.getElementById('fp-pays').value);
        formProcess.setNouvTitTelephone(document.getElementById('fp-telephone').value);
    } else if (typeContact === 'entreprise') {
        formProcess.setNouvTitNom(document.getElementById('fe-nom').value);
        formProcess.setNouvTitPrenom(document.getElementById('fe-prenom').value);
        formProcess.setNouvTitNomUsage(document.getElementById('fe-nomUsage').value);
        formProcess.setNouvTitRaisonSociale(document.getElementById('fe-raisonSociale').value);
        formProcess.setNouvTitSiret(document.getElementById('fe-siret').value);
        formProcess.setNouvTitAdresse(document.getElementById('fe-adresse').value);
        formProcess.setNouvTitComplementAdresse(document.getElementById('fe-complementAdresse').value);
        formProcess.setNouvTitCodePostal(document.getElementById('fe-codePostal').value);
        formProcess.setNouvTitVille(document.getElementById('fe-ville').value);
        formProcess.setNouvTitPays(document.getElementById('fe-pays').value);
        formProcess.setNouvTitTelephone(document.getElementById('fe-telephone').value);
    }

    formProcess.setNouvTitAcceptPolicy(document.getElementById('acceptCGV').checked);

    saveCotitulaires();
    const formData = formProcess.getEntireState();

    fetch('/panel/Carte-grise/panier-recap/panier-informations-store-info-ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (response.status === 400) {
            return response.json().then(data => {
                const invalidFields = data.invalidFields.join(', ');
                popup_alert('Erreur : ' + invalidFields, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                return;
            });
        } else if (response.status === 200) {
            return response.json().then(data => {
                const idCarteGrise = data.id_carte_grise;
                if (idCarteGrise) {
                    return fetch('/function/panier/function_ajout_panier_carte_grise-ajax.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ idaction: idCarteGrise })
                    })
                    .then(response => {
                        if (response.status === 200) {
                            return response.json().then(data => {
                                document.getElementById('loading-screen').style.display = 'flex';
                                setTimeout(() => {
                                    window.location.href = "/Paiement";
                                }, 1500);
                            });
                        } else {
                            throw new Error('Erreur lors de l\'appel AJAX');
                        }
                    });
                }
            });
        } else {
            throw new Error('Erreur de traitement');
        }
    })
    .catch(() => {});
}

function saveCotitulaires() {
    const cotitulaires = document.querySelectorAll('#cotitulaires-container > div');
    cotitulaires.forEach((cotitulaire) => {
        const typeContactCotitulaire = cotitulaire.querySelector('input[name^="typeContactCotitulaire"]:checked');
        if (!typeContactCotitulaire) {
            console.error('Type contact cotitulaire not found');
            return;
        }
        const uniqueId = typeContactCotitulaire.id.replace('particulierCotitulaire', '').replace('entrepriseCotitulaire', '');

        // Ensure the cotitulaire object exists in the state
        if (!formProcess.state.nouvTitCotitulaires[uniqueId]) {
            formProcess.state.nouvTitCotitulaires[uniqueId] = {};
        }

        formProcess.setNouvTitCotTypeContact(uniqueId, typeContactCotitulaire.id);

        if (typeContactCotitulaire.id.includes('particulierCotitulaire')) {
            formProcess.setNouvTitCotNom(uniqueId, cotitulaire.querySelector(`#nomCotitulaire${uniqueId}`)?.value || '');
            formProcess.setNouvTitCotPrenom(uniqueId, cotitulaire.querySelector(`#prenomCotitulaire${uniqueId}`)?.value || '');
            formProcess.setNouvTitCotNomUsage(uniqueId, cotitulaire.querySelector(`#nomUsageCotitulaire${uniqueId}`)?.value || '');
            formProcess.setNouvTitCotRaisonSociale(uniqueId, ''); // Reset to empty
            formProcess.setNouvTitCotNoSiret(uniqueId, ''); // Reset to empty
        } else if (typeContactCotitulaire.id.includes('entrepriseCotitulaire')) {
            formProcess.setNouvTitCotNom(uniqueId, ''); // Reset to empty
            formProcess.setNouvTitCotPrenom(uniqueId, ''); // Reset to empty
            formProcess.setNouvTitCotNomUsage(uniqueId, ''); // Reset to empty
            formProcess.setNouvTitCotRaisonSociale(uniqueId, cotitulaire.querySelector(`#raisonSocialeCotitulaire${uniqueId}`)?.value || '');
            formProcess.setNouvTitCotNoSiret(uniqueId, cotitulaire.querySelector(`#siretCotitulaire${uniqueId}`)?.value || '');
        }
    });
}