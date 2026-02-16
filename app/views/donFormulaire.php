<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <title>Saisie des Dons - BNGRC</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
    
    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
           
            <nav class="col-md-3 col-lg-2 bg-dark text-white p-3">
                <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/menu.php'; ?>
            </nav>
            
           
            <main class="col-md-9 col-lg-10 p-4">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="fw-bold">Enregistrement des Dons</h1>
                        <a href="/dons/liste" class="btn btn-secondary">
                            Voir la liste des dons
                        </a>
                    </div>
                    
                    <!-- Formulaire de saisie -->
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="card shadow">
                                <div class="card-header bg-success text-white">
                                    <h4 class="mb-0">Enregistrer un nouveau don</h4>
                                </div>
                                <div class="card-body">
                                    <form id="formDon" method="POST" action="/dons/ajouter">
                                        
                                        <!-- Type de don -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">
                                                <span class="text-danger">*</span> Type de don
                                            </label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="typeDon" id="donNature" value="nature" checked>
                                                    <label class="form-check-label fw-bold" for="donNature">
                                                        Don en nature (produits)
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="typeDon" id="donArgent" value="argent">
                                                    <label class="form-check-label fw-bold" for="donArgent">
                                                        Don en argent
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Section Don en Nature -->
                                        <div id="sectionNature" class="border rounded p-4 mb-4 bg-light">
                                            <h5 class="text-success mb-3">Don en Nature</h5>
                                            
                                            <!-- Nom du donateur -->
                                            <div class="mb-3">
                                                <label for="donateurNature" class="form-label fw-bold">
                                                    <span class="text-danger">*</span> Nom du donateur / Organisation
                                                </label>
                                                <input type="text" class="form-control form-control-lg" id="donateurNature" 
                                                       name="donateurNature" placeholder="Nom complet ou organisation">
                                            </div>

                                            <!-- Sélection du produit -->
                                            <div class="mb-3">
                                                <label for="produitNature" class="form-label fw-bold">
                                                    <span class="text-danger">*</span> Type de produit
                                                </label>
                                                <select class="form-select form-select-lg" id="produitNature" name="produitNature">
                                                    <option value="" selected disabled>-- Sélectionnez un produit --</option>
                                                    <optgroup label="Alimentation">
                                                        <option value="riz">Riz (kg)</option>
                                                        <option value="huile">Huile (litres)</option>
                                                        <option value="eau">Eau potable (litres)</option>
                                                        <option value="conserves">Conserves alimentaires</option>
                                                        <option value="lait">Lait en poudre (kg)</option>
                                                        <option value="sucre">Sucre (kg)</option>
                                                    </optgroup>
                                                    <optgroup label="Matériaux de construction">
                                                        <option value="tole">Tôles ondulées (unités)</option>
                                                        <option value="bois">Bois de construction (m³)</option>
                                                        <option value="ciment">Ciment (sacs)</option>
                                                        <option value="clous">Clous (kg)</option>
                                                    </optgroup>
                                                    <optgroup label="Équipements">
                                                        <option value="tente">Tentes (unités)</option>
                                                        <option value="couverture">Couvertures (unités)</option>
                                                        <option value="vetements">Vêtements (lots)</option>
                                                        <option value="lampe">Lampes torches (unités)</option>
                                                        <option value="jerrycan">Jerrycans (unités)</option>
                                                    </optgroup>
                                                    <optgroup label="Santé & Hygiène">
                                                        <option value="medicaments">Médicaments (kits)</option>
                                                        <option value="kit_hygiene">Kits d'hygiène (unités)</option>
                                                        <option value="savon">Savon (unités)</option>
                                                        <option value="desinfectant">Désinfectant (litres)</option>
                                                        <option value="masque">Masques (boîtes)</option>
                                                    </optgroup>
                                                </select>
                                            </div>

                                            <!-- Quantité -->
                                            <div class="mb-3">
                                                <label for="quantiteNature" class="form-label fw-bold">
                                                    <span class="text-danger">*</span> Quantité
                                                </label>
                                                <input type="number" class="form-control form-control-lg" id="quantiteNature" 
                                                       name="quantiteNature" min="1" step="1" placeholder="Exemple: 100">
                                            </div>
                                        </div>

                                        <!-- Section Don en Argent -->
                                        <div id="sectionArgent" class="border rounded p-4 mb-4 bg-light" style="display: none;">
                                            <h5 class="text-success mb-3">Don en Argent</h5>
                                            
                                            <!-- Nom du donateur -->
                                            <div class="mb-3">
                                                <label for="donateurArgent" class="form-label fw-bold">
                                                    <span class="text-danger">*</span> Nom du donateur / Organisation
                                                </label>
                                                <input type="text" class="form-control form-control-lg" id="donateurArgent" 
                                                       name="donateurArgent" placeholder="Nom complet ou organisation">
                                            </div>

                                            <!-- Montant -->
                                            <div class="mb-3">
                                                <label for="montant" class="form-label fw-bold">
                                                    <span class="text-danger">*</span> Montant
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <input type="number" class="form-control" id="montant" 
                                                           name="montant" min="0" step="0.01" placeholder="0.00">
                                                    <span class="input-group-text">Ar</span>
                                                </div>
                                                <div class="form-text">Montant en Ariary (Ar)</div>
                                            </div>
                                        </div>

                                        <!-- Date du don -->
                                        <div class="mb-3">
                                            <label for="dateDon" class="form-label fw-bold">
                                                <span class="text-danger">*</span> Date du don
                                            </label>
                                            <input type="date" class="form-control form-control-lg" id="dateDon" 
                                                   name="dateDon" required value="<?php echo date('Y-m-d'); ?>">
                                            <div class="form-text">Date de réception du don (par défaut: aujourd'hui)</div>
                                        </div>

                                        <!-- Ville destinataire (optionnel) -->
                                        <div class="mb-3">
                                            <label for="villeDestinataire" class="form-label fw-bold">
                                                Ville destinataire (optionnel)
                                            </label>
                                            <select class="form-select form-select-lg" id="villeDestinataire" name="villeDestinataire">
                                                <option value="" selected>-- Non spécifié (à attribuer plus tard) --</option>
                                                <option value="1">Antananarivo</option>
                                                <option value="2">Toamasina</option>
                                                <option value="3">Mahajanga</option>
                                                <option value="4">Fianarantsoa</option>
                                                <option value="5">Toliara</option>
                                                <option value="6">Antsiranana</option>
                                                <option value="7">Antsirabe</option>
                                                <option value="8">Morondava</option>
                                                <option value="9">Ambositra</option>
                                                <option value="10">Manakara</option>
                                                <option value="11">Sambava</option>
                                                <option value="12">Taolagnaro</option>
                                            </select>
                                            <div class="form-text">Si le don est destiné à une ville spécifique</div>
                                        </div>

                                        <!-- Notes/Commentaires -->
                                        <div class="mb-4">
                                            <label for="notesDon" class="form-label fw-bold">
                                                Notes additionnelles (optionnel)
                                            </label>
                                            <textarea class="form-control" id="notesDon" name="notesDon" rows="3" 
                                                      placeholder="Informations supplémentaires sur le don..."></textarea>
                                        </div>

                                        <!-- Boutons -->
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-success btn-lg px-5">
                                                <strong>Enregistrer le don</strong>
                                            </button>
                                            <button type="reset" class="btn btn-secondary btn-lg">
                                                Réinitialiser
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
    
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion du type de don
        const donNature = document.getElementById('donNature');
        const donArgent = document.getElementById('donArgent');
        const sectionNature = document.getElementById('sectionNature');
        const sectionArgent = document.getElementById('sectionArgent');

        donNature.addEventListener('change', function() {
            if (this.checked) {
                sectionNature.style.display = 'block';
                sectionArgent.style.display = 'none';
                
                // Activer les champs de la section nature
                document.getElementById('donateurNature').required = true;
                document.getElementById('produitNature').required = true;
                document.getElementById('quantiteNature').required = true;
                
                // Désactiver les champs de la section argent
                document.getElementById('donateurArgent').required = false;
                document.getElementById('montant').required = false;
            }
        });

        donArgent.addEventListener('change', function() {
            if (this.checked) {
                sectionNature.style.display = 'none';
                sectionArgent.style.display = 'block';
                
                // Désactiver les champs de la section nature
                document.getElementById('donateurNature').required = false;
                document.getElementById('produitNature').required = false;
                document.getElementById('quantiteNature').required = false;
                
                // Activer les champs de la section argent
                document.getElementById('donateurArgent').required = true;
                document.getElementById('montant').required = true;
            }
        });

        // Script pour afficher l'unité correspondant au produit sélectionné
        document.getElementById('produitNature').addEventListener('change', function() {
            const quantiteInput = document.getElementById('quantiteNature');
            const produit = this.value;
            
            const unites = {
                'riz': 'kg',
                'huile': 'litres',
                'eau': 'litres',
                'tole': 'unités',
                'bois': 'm³',
                'ciment': 'sacs',
                'conserves': 'unités',
                'tente': 'unités',
                'couverture': 'unités',
                'medicaments': 'kits',
                'kit_hygiene': 'unités',
                'vetements': 'lots',
                'lait': 'kg',
                'sucre': 'kg',
                'clous': 'kg',
                'lampe': 'unités',
                'jerrycan': 'unités',
                'savon': 'unités',
                'desinfectant': 'litres',
                'masque': 'boîtes'
            };
            
            if (unites[produit]) {
                quantiteInput.placeholder = `Exemple: 100 ${unites[produit]}`;
            }
        });

        // Validation du formulaire
        document.getElementById('formDon').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const typeDon = document.querySelector('input[name="typeDon"]:checked').value;
            let message = 'Don enregistré avec succès!\n\n';
            
            if (typeDon === 'nature') {
                const donateur = document.getElementById('donateurNature').value;
                const produit = document.getElementById('produitNature').options[document.getElementById('produitNature').selectedIndex].text;
                const quantite = document.getElementById('quantiteNature').value;
                message += `Type: Don en nature\nDonateur: ${donateur}\nProduit: ${produit}\nQuantité: ${quantite}`;
            } else {
                const donateur = document.getElementById('donateurArgent').value;
                const montant = document.getElementById('montant').value;
                message += `Type: Don en argent\nDonateur: ${donateur}\nMontant: ${montant} Ar`;
            }
            
            alert(message);
            
            // Dans une vraie application, on enverrait les données au serveur ici
            // this.submit();
        });
    </script>
</body>

</html>
