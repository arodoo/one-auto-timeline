const saveConstat = function (mode = 'create') {
    const tableData = {
        constats_main: {},
        constats_vehicle_a: {},
        constats_vehicle_b: {}
    };

    // Check if we're in jumelage mode
    const isJumelageMode = document.body.classList.contains('jumelage-mode');
    const isUpdate = mode === 'update';

    // Canvas mapping configuration
    const canvasMapping = {
        'sc2-input34': { table: 'constats_vehicle_a', dbName: 's2_impact_point' },
        'sc3-input34': { table: 'constats_vehicle_b', dbName: 's3_impact_point' },
        'sc5-canvas': { table: 'constats_main', dbName: 's5_accident_sketch' },
        'sc6-canvas': { table: 'constats_vehicle_a', dbName: 's6_signature_a' },
        'sc7-canvas': { table: 'constats_vehicle_b', dbName: 's7_signature_b' },
        'sc9-canvas': { table: 'constats_main', dbName: 's9_final_sketch' }
    };

    // Process localStorage data
    Object.keys(localStorage).forEach(key => {
        if (key.startsWith('sc')) {
            try {
                const data = JSON.parse(localStorage.getItem(key));

                // Check if this is a canvas input
                if (canvasMapping[key]) {
                    const mapping = canvasMapping[key];
                    tableData[mapping.table][mapping.dbName] = data.value;
                } else if (data.table && data.dbName && data.value) {
                    tableData[data.table][data.dbName] = data.value;
                }
            } catch (e) {
                // Silent error handling
            }
        }
    });

    // If in jumelage mode and it's an update, add the constat_id
    if (isJumelageMode && window.jumelageConstatId) {
        console.log("Jumelage mode detected!");
        console.log("jumelageConstatId:", window.jumelageConstatId);
        tableData.constats_main['id'] = window.jumelageConstatId;
        console.log("Added constat ID to payload:", tableData.constats_main['id']);
    }

    fetch('/panel/Constats/constant-form/index-file-etape-store-info-ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            ...tableData,
            is_jumelage_mode: isJumelageMode,
            is_update: isUpdate
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.unique_id) {
                // Different handling for jumelage mode vs. normal mode
                if (isJumelageMode) {
                    handleJumelageSuccess(data.unique_id);
                } else {
                    // Check if this is a jumelage constat that needs notification
                    const isShared = JSON.parse(localStorage.getItem('sc3-input37'))?.value;
                    const sharedWithUserId = JSON.parse(localStorage.getItem('sc3-input39'))?.value;
                    const shareToken = JSON.parse(localStorage.getItem('sc3-input38'))?.value;

                    if (isShared && sharedWithUserId && shareToken) {
                        // Send notification to User B
                        return sendJumelageNotification(data.unique_id, sharedWithUserId, shareToken)
                            .then(() => {
                                if (isUpdate) {
                                    handleSuccessfulUpdate(data.unique_id);
                                } else {
                                    handleSuccessfulSave(data.unique_id);
                                }
                            });
                    } else {
                        if (isUpdate) {
                            handleSuccessfulUpdate(data.unique_id);
                        } else {
                            handleSuccessfulSave(data.unique_id);
                        }
                    }
                }
            } else {
                handleError(data);
            }
        })
        .catch(error => {
            handleSaveError(error);
        });
};

function sendJumelageNotification(constatId, userBId, shareToken) {
    const formData = new FormData();
    formData.append('constat_id', constatId);
    formData.append('user_b_id', userBId);
    formData.append('share_token', shareToken);
    // No need to send user_a_id - we'll use $id_oo directly in PHP

    return fetch('/panel/Constats/constant-form/Components/NotifyUserJumelage.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => {
            // First check if the response is OK
            if (!response.ok) {
                throw new Error(`Server responded with ${response.status}: ${response.statusText}`);
            }

            // Then try to parse the JSON
            return response.text().then(text => {
                try {
                    // Attempt to parse as JSON
                    return text ? JSON.parse(text) : { success: false, message: 'Empty response' };
                } catch (e) {
                    console.error('JSON Parse error:', e);
                    console.error('Raw response:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        })
        .then(data => {
            if (!data.success) {
                console.error('Notification error:', data.message);
                alert('La notification n\'a pas pu être envoyée à l\'autre conducteur: ' + data.message);
            }
            return data;
        })
        .catch(error => {
            console.error('Notification request failed:', error);
            alert('Erreur lors de l\'envoi de la notification: ' + error.message);
            // Return a standardized error response
            return { success: false, message: error.message };
        });
}

function handleSuccessfulSave(uniqueId) {
    window.clearConstatStorage();
    const pdfUrl = `https://mon-espace-auto.com/Constat-amiable-accident/pdf/${uniqueId}`;
    window.open(pdfUrl, '_blank');
    setTimeout(() => {
        window.location.reload();
    }, 1000); // Delay the reload to ensure the PDF is generated
}

function handleSuccessfulUpdate(uniqueId) {
    window.clearConstatStorage();
    const pdfUrl = `https://mon-espace-auto.com/Constat-amiable-accident/pdf/${uniqueId}`;
    window.open(pdfUrl, '_blank');
    setTimeout(() => {
        window.location.reload();
    }, 1000); // Delay the reload to ensure the PDF is generated
}

function handleJumelageSuccess(uniqueId) {
    window.clearConstatStorage();
    
    // Call the endpoint to clear jumelage session before redirecting
    fetch('/panel/Constats/constant-form/Components/ClearJumelageSession.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Jumelage session cleared successfully');
                
                // Show success message
                alert('Le constat partagé a été complété avec succès! Une copie a été créée dans votre espace.');
                
                // Open PDF in new tab
                const pdfUrl = 'https://mon-espace-auto.com/Constat-amiable-accident/pdf/' + uniqueId;
                window.open(pdfUrl, '_blank');
                
                // Remove jumelage classes from document body
                document.body.classList.remove('jumelage-mode');
                
                // Reset jumelage JavaScript variable
                window.isJumelageMode = false;
                
                // Redirect to clean page state (without jumelage parameters)
                window.location.href = '/Constats';
            } else {
                console.error('Failed to clear jumelage session:', data.message);
                // Fallback to regular reload
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error clearing jumelage session:', error);
            // Fallback to regular reload
            window.location.reload();
        });
}

function handleError(data) {
    console.error('Response data:', data);
    if (data && data.Texte_rapport) {
        // Use popup_alert for displaying the error message
        window.popup_alert(data.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
    } else {
        alert('Erreur: ' + (data.message || 'Erreur inconnue'));
    }
}

function handleSaveError(error) {
    console.error('Save error:', error);
    window.popup_alert("Erreur lors de l'enregistrement. Veuillez réessayer.", "#CC0000 filledlight", "#CC0000", "uk-icon-times");
}

window.saveConstat = saveConstat;