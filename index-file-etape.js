let currentSection = 1;
// Remove the totalSections declaration since it's coming from PHP

// Section Loading Functions
function loadSection(section, sectionNumber) {
    const sectionContent = document.getElementById('section-content');
    const buttons = document.querySelectorAll('button.btn');
    applyTransition(sectionContent, buttons, () => {
        $.ajax({
            url: section,
            method: 'GET',
            success: function(data) {
                $('#section-content').html(data);
                currentSection = sectionNumber;
                removeTransition(sectionContent, buttons);
                loadSectionData(sectionNumber);
                limitCharactersInForm();
                updateNavigationButtons(sectionNumber);
                updateActiveSectionStyle(sectionNumber); // Add this line
            },
            error: function() {
                alert('Failed to load section');
                removeTransition(sectionContent, buttons);
            }
        });
    });
}

function updateNavigationButtons(sectionNumber) {
    const prevButton = document.querySelector('button[onclick="navigateSection(\'prev\')"]');
    const nextButton = document.querySelector('button[onclick="navigateSection(\'next\')"]');
    
    if (sectionNumber === 1) {
        prevButton.disabled = true;
        prevButton.classList.add('disabled');
    } else {
        prevButton.disabled = false;
        prevButton.classList.remove('disabled');
    }
    
    if (sectionNumber === totalSections) { // Use totalSections instead of hardcoded 13
        nextButton.disabled = true;
        nextButton.classList.add('disabled');
    } else {
        nextButton.disabled = false;
        nextButton.classList.remove('disabled');
    }
}

function applyTransition(sectionContent, buttons, callback) {
    sectionContent.classList.add('hidden');
    buttons.forEach(button => button.classList.add('hidden'));
    setTimeout(callback, 500); // Faster transition duration
}

function removeTransition(sectionContent, buttons) {
    sectionContent.classList.remove('hidden');
    buttons.forEach(button => button.classList.remove('hidden'));
}

function navigateSection(direction) {
    let nextSection = currentSection;
    if (direction === 'next') {
        nextSection = (nextSection === 8) ? 10 : nextSection + 1;
    } else if (direction === 'prev') {
        nextSection = (nextSection === 10) ? 8 : nextSection - 1;
    }

    if (nextSection < 1 || nextSection > totalSections) { // Use totalSections here too
        return;
    }

    loadSection(`/panel/Constats/constant-form/FormHandler/Section_${nextSection}.php`, nextSection);
}

// Local Storage Functions
function isValidJSON(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function initializeLocalStorage() {
    Object.keys(inputs).forEach(id => {
        const item = localStorage.getItem(id);
        if (!isValidJSON(item)) {
            const data = {
                id: id,
                key: inputs[id].key,
                value: '',
                dbName: inputs[id].dbName,
                table: 'constats_main'  // Default table
            };
            // Set correct table based on section
            if (id.startsWith('sc2-')) {
                data.table = 'constats_vehicle_a';
            } else if (id.startsWith('sc3-')) {
                data.table = 'constats_vehicle_b';
            }
            localStorage.setItem(id, JSON.stringify(data));
        }
    });
}

function loadSectionData(sectionNumber) {
    Object.keys(inputs).forEach(id => {
        if (id.startsWith(`sc${sectionNumber}-`)) {
            const element = document.getElementById(id);
            const item = localStorage.getItem(id);
            if (element) {
                if (item && isValidJSON(item)) {
                    const data = JSON.parse(item);
                    setupElement(element, data, id);
                } else {
                    const data = { id: id, key: inputs[id].key, value: '' };
                    setupElement(element, data, id);
                }
            }
        }
    });
}

function setupElement(element, data, id) {
    if (element.type === 'radio') {
        element.checked = data.value === element.value;
        element.addEventListener('change', (e) => {
            if (e.target.checked) {
                document.querySelectorAll(`input[name="${element.name}"]`).forEach(radio => {
                    if (radio.id !== id) {
                        localStorage.removeItem(radio.id);
                    }
                });
                localStorage.setItem(id, JSON.stringify({
                    id: data.id,
                    key: inputs[id].key,
                    value: e.target.value,
                    dbName: inputs[id].dbName,
                    table: inputs[id].table
                }));
            }
        });
    } else if (element.type === 'checkbox') {  // Add checkbox handling
        element.checked = data.value === element.value;
        element.addEventListener('change', (e) => {
            localStorage.setItem(id, JSON.stringify({
                id: data.id,
                key: inputs[id].key,
                value: e.target.checked ? element.value : '',
                dbName: inputs[id].dbName,
                table: inputs[id].table
            }));
        });
    } else {
        element.value = data.value;
        element.addEventListener('input', (e) => {
            localStorage.setItem(id, JSON.stringify({
                id: data.id,
                key: inputs[id].key,
                value: e.target.value,
                dbName: inputs[id].dbName,
                table: inputs[id].table
            }));
        });
    }
}

// Canvas Functions
function loadCanvasForCurrentSection(sectionNumber) {
    const canvasId = `sc${sectionNumber}-canvas`;
    const savedStrokes = localStorage.getItem(`meta-${canvasId}-strokes`);
    if (savedStrokes) {
        const strokes = JSON.parse(savedStrokes);
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        if (typeof resizeCanvas === 'function') {
            resizeCanvas();
        }
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        if (typeof drawGrid === 'function') {
            drawGrid();
        }
        strokes.forEach(stroke => {
            ctx.beginPath();
            ctx.moveTo(stroke[0][0], stroke[0][1]);
            stroke.forEach(([x, y]) => {
                ctx.lineTo(x, y);
                ctx.stroke();
            });
        });
    }

    const savedSketch = localStorage.getItem(canvasId);
    if (savedSketch) {
        const data = JSON.parse(savedSketch);
        const img = new Image();
        img.src = data.value;
        img.onload = () => {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        };
    }
}

// Form Validation Functions
function limitCharactersInForm() {
    const inputs = document.querySelectorAll('input[type="text"][data-maxlength]');
    inputs.forEach((input, index) => {
        const maxLength = input.getAttribute('data-maxlength');
        const messageId = input.id + '-message';
        let message = document.getElementById(messageId);

        if (!message) {
            message = document.createElement('span');
            message.id = messageId;
            message.className = 'message';
            input.parentNode.appendChild(message);
        }

        input.addEventListener('input', function () {
            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength);
                message.textContent = `Vous avez atteint la limite de ${maxLength} caractères.`;
            } else {
                message.textContent = '';
            }
        });
    });
}

function updateActiveSectionStyle(sectionNumber) {
    // Remove 'done' class from all nav links
    document.querySelectorAll('.nav-wizard .nav-link').forEach(link => {
        link.classList.remove('done');
    });
    
    // Add 'done' class to current section
    const currentNav = document.querySelector(`#section-${sectionNumber}`);
    if (currentNav) {
        currentNav.classList.add('done');
    }
}

// Initialize the first section on document ready
$(document).ready(function() {
    // Check if we're in jumelage mode
    const isJumelage = document.body.classList.contains('jumelage-mode');
    
    if (isJumelage) {
        // In jumelage mode, start with Section 3 (displayed as Section 1 to User B)
        loadSection('/panel/Constats/constant-form/FormHandler/Section_3.php', 3);
    } else {
        // Normal mode - start with Section 1
        loadSection('/panel/Constats/constant-form/FormHandler/Section_1.php', 1);
    }
    initializeLocalStorage();
    loadSectionData(isJumelage ? 3 : 1);
});