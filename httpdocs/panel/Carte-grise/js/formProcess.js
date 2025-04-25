export class FormProcess {
    constructor() {
        this.state = {
            price: 0,
            process: '',
            etatVehicule: '',
            immatriculation: '',
            genre: '',
            carburant: '',
            dateCirculation: '',
            emissionCO2: '',
            chevauxFiscaux: '',
            leasing: '',
            handicap: '',
            succession: '',
            techChange: '',
            immatriculationDeType123ABC01: '',
            venduEtranger: '',
            email: '',
            codePostal: '',
            acceptPolicy: false,
            nouvTitTypeContact: '',
            nouvTitCivilite: '',
            nouvTitNom: '',
            nouvTitPrenom: '',
            nouvTitNomUsage: '',
            nouvTitComplementAdresse: '',
            nouvTitVille: '',
            nouvTitPays: '',
            nouvTitTelephone: '',
            nouvTitRaisonSociale: '',
            nouvTitSiret: '',
            nouvTitAdresse: '',
            nouvTitCodePostal: '',
            nouvTitAcceptPolicy: false,
            nouvTitCotitulaires: {
                uniqueId: {
                    nouvTitCotTypeContact: '',
                    nouvTitCotNom: '',
                    nouvTitCotPrenom: '',
                    nouvTitCotNomUsage: '',
                    nouvTitCotRaisonSociale: '',
                    nouvTitCotNoSiret: ''
                }
            },
            montant_commande_sans_frais: 0,
            montant_commande_frais: 0,
            commission: 0
        };
        this.observers = [];
    }

    resetFormState() {
        this.state = {
            process: '',
            etatVehicule: '',
            immatriculation: '',
            genre: '',
            carburant: '',
            dateCirculation: '',
            emissionCO2: '',
            chevauxFiscaux: '',
            leasing: '',
            handicap: '',
            succession: '',
            techChange: '',
            immatriculationDeType123ABC01: '',
            venduEtranger: '',
            email: '',
            codePostal: '',
            acceptPolicy: false,
            nouvTitTypeContact: '',
            nouvTitCivilite: '',
            nouvTitNom: '',
            nouvTitPrenom: '',
            nouvTitNomUsage: '',
            nouvTitComplementAdresse: '',
            nouvTitVille: '',
            nouvTitPays: '',
            nouvTitTelephone: '',
            nouvTitRaisonSociale: '',
            nouvTitSiret: '',
            nouvTitAdresse: '',
            nouvTitCodePostal: '',
            nouvTitAcceptPolicy: false,
            nouvTitCotitulaires: {
                uniqueId: {
                    nouvTitCotTypeContact: '',
                    nouvTitCotNom: '',
                    nouvTitCotPrenom: '',
                    nouvTitCotNomUsage: '',
                    nouvTitCotRaisonSociale: '',
                    nouvTitCotNoSiret: ''
                }
            },
            montant_commande_sans_frais: 0,
            montant_commande_frais: 0,
            commission: 0
        };
        this.notifyObservers();
    }

    setState(newState) {
        this.state = newState;
        this.notifyObservers();
    }

    addObserver(observer) {
        this.observers.push(observer);
    }

    notifyObservers() {
        this.observers.forEach(observer => observer());
    }

    setMontantCommandeSansFrais(montant_commande_sans_frais) {
        this.state.montant_commande_sans_frais = montant_commande_sans_frais;
        this.notifyObservers();
    }

    getMontantCommandeSansFrais() {
        return this.state.montant_commande_sans_frais;
    }

    setMontantCommandeFrais(montant_commande_frais) {
        this.state.montant_commande_frais = montant_commande_frais;
        this.notifyObservers();
    }

    getMontantCommandeFrais() {
        return this.state.montant_commande_frais;
    }

    setPrice(price) {
        this.state.price = price;
        this.notifyObservers();
    }

    getPrice() {
        return this.state.price;
    }

    getEntireState() {
        return this.state;
    }

    setProcess(process) {
        this.state.process = process;
        this.notifyObservers();
    }

    getProcess() {
        return this.state.process;
    }

    setEtatVehicule(etatVehicule) {
        this.state.etatVehicule = etatVehicule;
        this.notifyObservers();
    }

    getEtatVehicule() {
        return this.state.etatVehicule;
    }

    setImmatriculation(immatriculation) {
        this.state.immatriculation = immatriculation;
        this.notifyObservers();
    }

    getImmatriculation() {
        return this.state.immatriculation;
    }

    setGenre(genre) {
        this.state.genre = genre;
        this.notifyObservers();
    }

    getGenre() {
        return this.state.genre;
    }

    setCarburant(carburant) {
        this.state.carburant = carburant;
        this.notifyObservers();
    }

    getCarburant() {
        return this.state.carburant;
    }

    setDateCirculation(dateCirculation) {
        this.state.dateCirculation = dateCirculation;
        this.notifyObservers();
    }

    getDateCirculation() {
        return this.state.dateCirculation;
    }

    setEmissionCO2(emissionCO2) {
        this.state.emissionCO2 = emissionCO2;
        this.notifyObservers();
    }

    getEmissionCO2() {
        return this.state.emissionCO2;
    }

    setChevauxFiscaux(chevauxFiscaux) {
        this.state.chevauxFiscaux = chevauxFiscaux;
        this.notifyObservers();
    }

    getChevauxFiscaux() {
        return this.state.chevauxFiscaux;
    }
    setLeasing(leasing) {
        this.state.leasing = leasing;
        this.notifyObservers();
    }

    getLeasing() {
        return this.state.leasing;
    }

    setHandicap(handicap) {
        this.state.handicap = handicap;
        this.notifyObservers();
    }

    getHandicap() {
        return this.state.handicap;
    }

    setSuccession(succession) {
        this.state.succession = succession;
        this.notifyObservers();
    }

    getSuccession() {
        return this.state.succession;
    }

    setTechChange(techChange) {
        this.state.techChange = techChange;
        this.notifyObservers();
    }

    getTechChange() {
        return this.state.techChange;
    }

    setImmatriculationDeType123ABC01(immatriculationDeType123ABC01) {
        this.state.immatriculationDeType123ABC01 = immatriculationDeType123ABC01;
        this.notifyObservers();
    }

    getImmatriculationDeType123ABC01() {
        return this.state.immatriculationDeType123ABC01;
    }

    setVenduEtranger(venduEtranger) {
        this.state.venduEtranger = venduEtranger;
        this.notifyObservers();
    }

    getVenduEtranger() {
        return this.state.venduEtranger;
    }

    setEmail(email) {
        this.state.email = email;
        this.notifyObservers();
    }

    getEmail() {
        return this.state.email;
    }

    setCodePostal(codePostal) {
        this.state.codePostal = codePostal;
        this.notifyObservers();
    }

    getCodePostal() {
        return this.state.codePostal;
    }

    setAcceptPolicy(acceptPolicy) {
        this.state.acceptPolicy = acceptPolicy;
        this.notifyObservers();
    }

    getAcceptPolicy() {
        return this.state.acceptPolicy;
    }

    setNouvTitTypeContact(nouvTitTypeContact) {
        this.state.nouvTitTypeContact = nouvTitTypeContact;
        this.notifyObservers();
    }

    getNouvTitTypeContact() {
        return this.state.nouvTitTypeContact;
    }

    setNouvTitCivilite(nouvTitCivilite) {
        this.state.nouvTitCivilite = nouvTitCivilite;
        this.notifyObservers();
    }

    getNouvTitCivilite() {
        return this.state.nouvTitCivilite;
    }

    setNouvTitNom(nouvTitNom) {
        this.state.nouvTitNom = nouvTitNom;
        this.notifyObservers();
    }

    getNouvTitNom() {
        return this.state.nouvTitNom;
    }

    setNouvTitPrenom(nouvTitPrenom) {
        this.state.nouvTitPrenom = nouvTitPrenom;
        this.notifyObservers();
    }

    getNouvTitPrenom() {
        return this.state.nouvTitPrenom;
    }

    setNouvTitNomUsage(nouvTitNomUsage) {
        this.state.nouvTitNomUsage = nouvTitNomUsage;
        this.notifyObservers();
    }

    getNouvTitNomUsage() {
        return this.state.nouvTitNomUsage;
    }

    setNouvTitComplementAdresse(nouvTitComplementAdresse) {
        this.state.nouvTitComplementAdresse = nouvTitComplementAdresse;
        this.notifyObservers();
    }

    getNouvTitComplementAdresse() {
        return this.state.nouvTitComplementAdresse;
    }

    setNouvTitVille(nouvTitVille) {
        this.state.nouvTitVille = nouvTitVille;
        this.notifyObservers();
    }

    getNouvTitVille() {
        return this.state.nouvTitVille;
    }

    setNouvTitPays(nouvTitPays) {
        this.state.nouvTitPays = nouvTitPays;
        this.notifyObservers();
    }

    getNouvTitPays() {
        return this.state.nouvTitPays;
    }

    setNouvTitTelephone(nouvTitTelephone) {
        this.state.nouvTitTelephone = nouvTitTelephone;
        this.notifyObservers();
    }

    getNouvTitTelephone() {
        return this.state.nouvTitTelephone;
    }

    setNouvTitRaisonSociale(nouvTitRaisonSociale) {
        this.state.nouvTitRaisonSociale = nouvTitRaisonSociale;
        this.notifyObservers();
    }

    getNouvTitRaisonSociale() {
        return this.state.nouvTitRaisonSociale;
    }

    setNouvTitSiret(nouvTitSiret) {
        this.state.nouvTitSiret = nouvTitSiret;
        this.notifyObservers();
    }

    getNouvTitSiret() {
        return this.state.nouvTitSiret;
    }

    setNouvTitAdresse(nouvTitAdresse) {
        this.state.nouvTitAdresse = nouvTitAdresse;
        this.notifyObservers();
    }

    getNouvTitAdresse() {
        return this.state.nouvTitAdresse;
    }

    setNouvTitCodePostal(nouvTitCodePostal) {
        this.state.nouvTitCodePostal = nouvTitCodePostal;
        this.notifyObservers();
    }

    getNouvTitCodePostal() {
        return this.state.nouvTitCodePostal;
    }

    setNouvTitAcceptPolicy(nouvTitAcceptPolicy) {
        this.state.nouvTitAcceptPolicy = nouvTitAcceptPolicy;
        this.notifyObservers();
    }

    getNouvTitAcceptPolicy() {
        return this.state.nouvTitAcceptPolicy;
    }

    // Cotitulaires
    addNouvTitCotitulaire(uniqueId, cotitulaire) {
        this.state.nouvTitCotitulaires[uniqueId] = cotitulaire;
        this.notifyObservers();
    }

    getNouvTitCotitulaires() {
        return this.state.nouvTitCotitulaires;
    }

    setNouvTitCotTypeContact(uniqueId, value) {
        this.state.nouvTitCotitulaires[uniqueId].nouvTitCotTypeContact = value;
        this.notifyObservers();
    }

    getNouvTitCotTypeContact(uniqueId) {
        return this.state.nouvTitCotitulaires[uniqueId].nouvTitCotTypeContact;
    }

    setNouvTitCotNom(uniqueId, value) {
        this.state.nouvTitCotitulaires[uniqueId].nouvTitCotNom = value;
        this.notifyObservers();
    }

    getNouvTitCotNom(uniqueId) {
        return this.state.nouvTitCotitulaires[uniqueId].nouvTitCotNom;
    }

    setNouvTitCotPrenom(uniqueId, value) {
        this.state.nouvTitCotitulaires[uniqueId].nouvTitCotPrenom = value;
        this.notifyObservers();
    }

    getNouvTitCotPrenom(uniqueId) {
        return this.state.nouvTitCotitulaires[uniqueId].nouvTitCotPrenom;
    }

    setNouvTitCotNomUsage(uniqueId, value) {
        this.state.nouvTitCotitulaires[uniqueId].nouvTitCotNomUsage = value;
        this.notifyObservers();
    }

    getNouvTitCotNomUsage(uniqueId) {
        return this.state.nouvTitCotitulaires[uniqueId].nouvTitCotNomUsage;
    }

    setNouvTitCotRaisonSociale(uniqueId, value) {
        this.state.nouvTitCotitulaires[uniqueId].nouvTitCotRaisonSociale = value;
        this.notifyObservers();
    }

    getNouvTitCotRaisonSociale(uniqueId) {
        return this.state.nouvTitCotitulaires[uniqueId].nouvTitCotRaisonSociale;
    }

    setNouvTitCotNoSiret(uniqueId, value) {
        this.state.nouvTitCotitulaires[uniqueId].nouvTitCotNoSiret = value;
        this.notifyObservers();
    }

    getNouvTitCotNoSiret(uniqueId) {
        return this.state.nouvTitCotitulaires[uniqueId].nouvTitCotNoSiret;
    }

    setCommission(commission) {
        this.state.commission = commission;
        this.notifyObservers();
    }

    getCommission() {
        return this.state.commission;
    }
}

// Export the class for use in other files
export default FormProcess;