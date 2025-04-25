import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';
document.addEventListener('DOMContentLoaded', function () {
    const timeline = document.getElementById('cdd-timeline');

    function updateTimeline() {
        const formState = formProcess.getEntireState();
        let progress = 25;
            if (formState.immatriculation) {
                progress = 50;
            }

            if (formState.leasing) {
                progress = 75;
            }

            if (formState.email) {
                progress = 100;
            }
        timeline.style.width = `${progress}%`;
    }

    // Add observer to formProcess
    formProcess.addObserver(() => {
        updateTimeline();
    });

    updateTimeline();
});
