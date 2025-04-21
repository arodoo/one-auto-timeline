document.addEventListener('DOMContentLoaded', async (event) => {
    //Get the cookie value by name
    function getCookie(name) {
        let nameEQ = name + '=';
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    //Set the cookie value
    function setCookie(name, value) {
        let date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        let expires = '; expires=' + date.toUTCString();
        document.cookie = name + '=' + value + expires +
            '; path=/';
    }

    let token = getCookie('remember_me');
    if (token !== null) {

        // Afficher l'écran de chargement si un jeton est présent 
        document.getElementById('loading-screen').style.display = 'flex';
        document.body.classList.add('loading');

        setTimeout(() => {
            fetch('/pop-up/login/valider-jeton.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ token: token })
            })
            .then(response => response.json())
            .then(async data => {
                if (data.valid) {
                    // prolonger la durée de vie du jeton
                    setCookie('remember_me', token); // Re-set the token in the cookie
                    $(location).attr('href', data.retour_lien);
                } else {
                    // Masquer l'écran de chargement en cas d'erreur 
                    setCookie('remember_me', '');
                    document.getElementById('loading-screen').style.display = 'none';
                    document.body.classList.remove('loading');
                }
            }).catch(error => {
                console.error('Error:', error);
                setCookie('remember_me', '');
            });
        }, 2000);
    }
});
