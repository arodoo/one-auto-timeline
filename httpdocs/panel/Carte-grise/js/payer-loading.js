export function showAndHideLoading() {
    var loadingScreen = document.getElementById('loading-screen');
    loadingScreen.style.display = 'flex';

    setTimeout(function () {
        loadingScreen.style.display = 'none';
    }, 1000);
}

export function showPayerLoading() {
    var loadingScreen = document.getElementById('loading-screen');
    loadingScreen.style.display = 'flex';
    document.body.classList.add('loading');
}

export function hidePayerLoading() {
    var loadingScreen = document.getElementById('loading-screen');
    loadingScreen.style.display = 'none';
    document.body.classList.remove('loading');
}
export function showPanierRecap() {
    showAndHideLoading();
    document.getElementById('main-content').style.display = 'none';
    document.getElementById('panier-recap-content').style.display = 'block';
    setTimeout(function () {
        window.scrollTo(0, 0);
    }, 1000);
}

export function showMainContent() {
    showAndHideLoading();
    document.getElementById('main-content').style.display = 'block';
    document.getElementById('panier-recap-content').style.display = 'none';
    window.scrollTo(0, 0);
}