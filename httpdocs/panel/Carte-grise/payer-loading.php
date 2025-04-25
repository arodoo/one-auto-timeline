<style>
    body.loading {
        overflow: hidden;
    }

    #loading-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgb(255, 255, 255);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 4;
        pointer-events: none;
    }

    .loading-content {
        text-align: center;
    }

    .loading-content img {
        max-width: 200px;
        margin-bottom: 30px;
    }

    .loader {
        display: flex;
        justify-content: center;
    }

    .loader div {
        width: 16px;
        height: 16px;
        margin: 0 4px;
        background: rgb(0, 0, 0);
        border-radius: 50%;
        animation: bounce 1.2s infinite;
    }

    .loader div:nth-child(1) {
        animation-delay: -0.24s;
    }

    .loader div:nth-child(2) {
        animation-delay: -0.12s;
    }

    .loader div:nth-child(3) {
        animation-delay: 0;
    }

    @keyframes bounce {

        0%,
        80%,
        100% {
            transform: scale(0);
        }

        40% {
            transform: scale(1);
        }
    }
</style>

<div id="loading-screen" style="display: none;">
    <div class="loading-content">
        <img src="/images/monespaceauto.png" alt="Logo de l'entreprise">
        <div class="loader">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</div>