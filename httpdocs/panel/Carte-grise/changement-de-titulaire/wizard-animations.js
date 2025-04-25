import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';
document.addEventListener('DOMContentLoaded', function () {
    const timeline = document.getElementById('timeline');

    function updateTimeline() {
        const formState = formProcess.getEntireState();
        let progress = 10;

        //case etatVehicule occasion
        if (formState.etatVehicule === 'occasion') {
            if (formState.etatVehicule) {
                progress = 32;
            }

            if (formState.immatriculation) {
                progress = 49;
            }

            if (formState.dateCirculation) {
                progress = 66;
            }

            if (formState.techChange) {
                progress = 83;
            }

            if (formState.email) {
                progress = 100;
            }
        }

        //case etatVehicule neuf
        if (formState.etatVehicule === 'neuf') {
            if (formState.etatVehicule) {
                progress = 25;
            }
            if (formState.genre) {
                progress = 50;
            }
            if (formState.techChange) {
                progress = 75;
            }
            if (formState.email) {
                progress = 100;
            }
        }

        //case etatVehicule étranger
        if (formState.etatVehicule === 'etranger') {
            if (formState.etatVehicule) {
                progress = 25;
            }
            if (formState.genre) {
                progress = 50;
            }
            if (formState.techChange) {
                progress = 75;
            }
            if (formState.email) {
                progress = 100;
            }
        }

        timeline.style.width = `${progress}%`;
    }

    // Add observer to formProcess
    formProcess.addObserver(() => {
        updateTimeline();
    });

    updateTimeline();
});
