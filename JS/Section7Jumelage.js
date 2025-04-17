function handleCanvasJumelageState() {
    const jumelageEmail = localStorage.getItem('meta-sc3-jumelage_email');

    if (jumelageEmail) {
        // Show and update banner
        const banner = document.querySelector('.jumelage-info-message');
        banner.style.display = 'block';
        banner.querySelector('.jumelage-email').textContent = jumelageEmail;

        // Find the canvas and its associated buttons for conductor B
        const canvasB = document.querySelector('canvas[data-db-name="s7_signature_b"]');
        if (canvasB) {
            // Disable canvas
            canvasB.style.pointerEvents = 'none';
            canvasB.style.opacity = '0.5';

            // Disable associated buttons
            const canvasId = canvasB.id;
            const clearBtn = document.getElementById(`clear-${canvasId}`);
            const deleteBtn = document.getElementById(`delete-${canvasId}`);
            const saveBtn = document.getElementById(`saveToLocal-${canvasId}`);

            if (clearBtn) {
                clearBtn.disabled = true;
                clearBtn.style.opacity = '0.5';
            }
            if (deleteBtn) {
                deleteBtn.disabled = true;
                deleteBtn.style.opacity = '0.5';
            }
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.style.opacity = '0.5';
            }
        }
    }
}
