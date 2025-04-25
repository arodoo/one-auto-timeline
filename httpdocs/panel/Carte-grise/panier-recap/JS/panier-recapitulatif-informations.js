import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';


function updateImmatriculation(){
    const immatriculation = formProcess.getImmatriculation() || 'BB-123-BB';
    document.getElementById('immatriculation-value').innerText = immatriculation;
    document.getElementById('immatriculation-display').innerText = immatriculation;
}

function updateMontantCommandeFrais() {
    const montantCommandeFrais = Math.ceil((formProcess.getMontantCommandeFrais() || 0) * 100) / 100;
    const montantCommandeNoFrais = Math.ceil((formProcess.getMontantCommandeSansFrais() || 0) * 100) / 100;
    const taxes = Math.ceil((montantCommandeFrais - montantCommandeNoFrais) * 100) / 100;
    document.getElementById('montant-commande-no-frais-value').innerText = montantCommandeNoFrais + ' €';
    document.getElementById('montant-commande-frais-value').innerText = taxes + ' €';
    document.getElementById('montant-commande-frais-value-display').innerText = montantCommandeFrais + ' €';
}

formProcess.addObserver(() =>{
    updateImmatriculation();
    updateMontantCommandeFrais();
});

updateImmatriculation();
updateMontantCommandeFrais();