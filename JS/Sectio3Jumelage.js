function getElements() {
    return {
        jumelageRadio: document.getElementById('jumelageRadio'),
        noJumelageRadio: document.getElementById('noJumelageRadio'),
        emailGroup: document.getElementById('jumelageEmailGroup'),
        emailInput: document.getElementById('jumelageEmail'),
        checkBtn: document.getElementById('checkEmailBtn'),
        shareTokenInput: document.getElementById('sc3-input38'),
        sharedWithUserIdInput: document.getElementById('sc3-input39'),
        isSharedInput: document.getElementById('sc3-input37')
    };
}

function toggleJumelageMode(isJumelage, elements) {
    elements.emailGroup.style.opacity = isJumelage ? '1' : '0.5';
    elements.emailInput.disabled = !isJumelage;
    elements.checkBtn.disabled = !isJumelage;

    const banner = document.querySelector('.jumelage-info-message');
    if (banner) {
        banner.style.display = isJumelage ? 'block' : 'none';
    }

    // Toggle form inputs and damage canvas
    document.querySelectorAll('input:not([name="isJumelage"]):not(#jumelageEmail), textarea')
        .forEach(input => input.disabled = isJumelage);

    if (window.damageCanvasInstances?.B) {
        window.damageCanvasInstances.B[isJumelage ? 'disable' : 'enable']();
    }
}

function applyJumelageLockUI(email, elements) {
    elements.noJumelageRadio.disabled = true;
    elements.jumelageRadio.checked = true;
    elements.emailInput.readOnly = true;
    elements.emailInput.disabled = false;
    elements.checkBtn.disabled = true;
    elements.checkBtn.style.pointerEvents = 'none';

    if (email) {
        elements.emailInput.value = email;
        const banner = document.querySelector('.jumelage-info-message');
        if (banner) {
            banner.querySelector('.jumelage-email').textContent = email;
            banner.style.display = 'block';
        }
    }

    elements.emailInput.style.backgroundColor = '#f5f5f5';
    elements.emailInput.style.cursor = 'not-allowed';
}

function handleEmailCheck(elements) {
    const email = elements.emailInput.value;
    const formData = new FormData();
    formData.append('email', email);

    fetch('/panel/Constats/constant-form/Components/CheckIfEmailExistJumelage.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            saveJumelageData(data, email, elements);
            applyJumelageLockUI(email, elements);
        }
        popup_alert(data.message, data.alertStyle, data.alertColor, data.alertIcon);
    })
    .catch(error => {
        console.error('Error:', error);
        popup_alert('Une erreur est survenue lors de la vérification', 'red filledlight', '#ff0000', 'uk-icon-close');
    });
}

function saveJumelageData(data, email, elements) {
    const storageItems = [
        { key: 'sc3-input37', table: 'constats_main', dbName: 'is_shared', value: true },
        { key: 'sc3-input38', table: 'constats_main', dbName: 'share_token', value: data.shareToken },
        { key: 'sc3-input39', table: 'constats_main', dbName: 'shared_with_user_id', value: data.userId.toString() }
    ];

    elements.isSharedInput.value = true;
    elements.shareTokenInput.value = data.shareToken;
    elements.sharedWithUserIdInput.value = data.userId;

    storageItems.forEach(item => {
        localStorage.setItem(item.key, JSON.stringify({
            table: item.table,
            dbName: item.dbName,
            value: item.value
        }));
    });

    localStorage.setItem('meta-sc3-jumelage_email', email);
}

function initJumelage() {
    const elements = getElements();
    if (!Object.values(elements).every(Boolean)) {
        setTimeout(initJumelage, 100);
        return;
    }

    // Event listeners
    elements.jumelageRadio.addEventListener('change', () => toggleJumelageMode(true, elements));
    elements.noJumelageRadio.addEventListener('change', () => toggleJumelageMode(false, elements));
    elements.checkBtn.addEventListener('click', () => handleEmailCheck(elements));

    // Initialize state
    const savedState = {
        isJumelage: JSON.parse(localStorage.getItem('sc3-input37'))?.value,
        email: localStorage.getItem('meta-sc3-jumelage_email'),
        token: JSON.parse(localStorage.getItem('sc3-input38'))?.value,
        userId: JSON.parse(localStorage.getItem('sc3-input39'))?.value
    };

    if (savedState.isJumelage === true || savedState.isJumelage === 'true') {
        elements.jumelageRadio.checked = true;
        if (savedState.email && (savedState.token || savedState.userId)) {
            applyJumelageLockUI(savedState.email, elements);
        }
    } else {
        elements.noJumelageRadio.checked = true;
    }

    toggleJumelageMode(elements.jumelageRadio.checked, elements);
}

// Initialize immediately
initJumelage();