let resetModal;

const resetForm = () => {
    if (!resetModal) {
        resetModal = new bootstrap.Modal(document.getElementById('resetConfirmModal'));
    }
    resetModal.show();
};

const handleReset = () => {
    // First clear storage
    clearConstatStorage();
    
    // Hide modal and wait for animation
    resetModal.hide();
    
    // Wait for modal to finish hiding before reload
    document.getElementById('resetConfirmModal').addEventListener('hidden.bs.modal', () => {
        window.location.reload();
    }, { once: true }); // Use once:true to prevent multiple listeners
};

// Initialize confirmation button handler
document.addEventListener('DOMContentLoaded', () => {
    const confirmResetBtn = document.getElementById('confirmResetBtn');
    if (confirmResetBtn) {
        confirmResetBtn.addEventListener('click', handleReset);
    }
});

window.resetForm = resetForm;