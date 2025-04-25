function startTimer(duration, display, button) {
    var timer = duration, minutes, seconds;
    var interval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            clearInterval(interval);
            display.parentElement.style.display = 'none';
            display.parentElement.parentElement.style.display = 'none';
            button.disabled = false;
            localStorage.removeItem('timer');
            localStorage.removeItem('buttonDisabled');
        } else {
            localStorage.setItem('timer', timer);
        }
    }, 100);
}

function handleTimer(seconds) {
    var display = document.querySelector('#timer #clock span'),
        button = document.querySelector('#bouton');
    if (display) {
        button.disabled = true;
        localStorage.setItem('buttonDisabled', true);
        display.parentElement.style.display = 'block';
        display.parentElement.parentElement.style.display = 'block';
        startTimer(seconds, display, button);
    } else {
        console.error('Element #timer #clock span not found');
    }
}

function handleTimerOnLoad() {
    var display = document.querySelector('#timer #clock span'),
        button = document.querySelector('#bouton');
    if (display) {
        var remainingTime = localStorage.getItem('timer');
        var buttonDisabled = localStorage.getItem('buttonDisabled');
        if (remainingTime > 0) {
            button.disabled = true;
            display.parentElement.style.display = 'block';
            display.parentElement.parentElement.style.display = 'block';
            startTimer(remainingTime, display, button);
        } else if (buttonDisabled) {
            button.disabled = true;
            display.parentElement.style.display = 'block';
            display.parentElement.parentElement.style.display = 'block';
        }
    } else {
        console.error('Element #timer #clock span not found');
    }
}

window.onload = function () {
    handleTimerOnLoad();
};