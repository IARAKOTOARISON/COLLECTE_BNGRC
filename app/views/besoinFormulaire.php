<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <title>Saisie des Besoins - BNGRC</title>
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
                        <h1 class="fw-bold">Saisie des Besoins par Ville</h1>
                        <a href="/besoins/liste" class="btn btn-secondary">
                            Voir la liste des besoins
                        </a>
                    </div>
                    
                    <!-- Formulaire de saisie -->
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0">Enregistrer un nouveau besoin</h4>
                                </div>
                                <div class="card-body">
                                    <form id="formBesoin" method="POST" action="/besoins/ajouter">
                                        
                                        <!-- Sélection de la ville -->
                                        <div class="mb-3">
                                            <label for="ville" class="form-label fw-bold">
                                                <span class="text-danger">*</span> Ville affectée
                                            </label>
                                            <select class="form-select form-select-lg" id="ville" name="ville" required>
                                                <option value="" selected disabled>-- Sélectionnez une ville --</option>
                                                <?php if (!empty($villes) && is_array($villes)): ?>
                                                    <?php foreach ($villes as $v): ?>
                                                        <?php
                                                            // compatibilité champs: id/nom ou ID/NOM
                                                            $vid = $v['id'] ?? $v['ID'] ?? null;
                                                            $vnom = $v['nom'] ?? $v['NOM'] ?? $v['name'] ?? null;
                                                        ?>
                                                        <?php if ($vid !== null && $vnom !== null): ?>
                                                            <option value="<?= htmlspecialchars($vid) ?>"><?= htmlspecialchars($vnom) ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <!-- Fallback statique si la table ville est vide ou indisponible -->
                                                    <option value="1">Antananarivo</option>
                                                    <option value="2">Toamasina</option>
                                                    <option value="3">Mahajanga</option>
                                                <?php endif; ?>
                                            </select>
                                            <div class="form-text">Choisissez la ville qui a besoin d'assistance</div>
                                        </div>

                                        <!-- Sélection du produit -->
                                        <div class="mb-3">
                                            <label for="produit" class="form-label fw-bold">
                                                <span class="text-danger">*</span> Type de produit nécessaire
                                            </label>
                                            <select class="form-select form-select-lg" id="produit" name="produit" required>
                                                <option value="" selected disabled>-- Sélectionnez un produit --</option>
                                                <?php if (!empty($types) && is_array($types)): ?>
                                                    <?php foreach ($types as $t): ?>
                                                        <?php
                                                            // compatibilité champs: id/nom/label
                                                            $tid = $t['id'] ?? $t['ID'] ?? null;
                                                            $tnom = $t['nom'] ?? $t['NOM'] ?? $t['name'] ?? null;
                                                        ?>
                                                        <?php if ($tnom !== null): ?>
                                                            <!-- on met la valeur à $tnom (texte) pour garder compatibilité avec le mapping JS existant -->
                                                            <option value="<?= htmlspecialchars(strtolower($tnom)) ?>"><?= htmlspecialchars($tnom) ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <!-- Fallback statique -->
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
                                                <?php endif; ?>
                                            </select>
                                            <div class="form-text">Sélectionnez le type de produit dont la ville a besoin</div>
                                        </div>

                                        <!-- Quantité -->
                                        <div class="mb-3">
                                            <label for="quantite" class="form-label fw-bold">
                                                <span class="text-danger">*</span> Quantité nécessaire
                                            </label>
                                            <input type="number" class="form-control form-control-lg" id="quantite" 
                                                   name="quantite" min="1" step="1" required placeholder="Exemple: 100">
                                            <div class="form-text">Indiquez la quantité nécessaire (nombre entier positif)</div>
                                        </div>

                                        <!-- Date -->
                                        <div class="mb-3">
                                            <label for="date" class="form-label fw-bold">
                                                <span class="text-danger">*</span> Date du besoin
                                            </label>
                                            <input type="date" class="form-control form-control-lg" id="date" 
                                                   name="date" required value="<?php echo date('Y-m-d'); ?>">
                                            <div class="form-text">Date d'enregistrement du besoin (par défaut: aujourd'hui)</div>
                                        </div>

                                        <!-- Priorité -->
                                        <div class="mb-3">
                                            <label for="priorite" class="form-label fw-bold">
                                                Niveau de priorité
                                            </label>
                                            <select class="form-select form-select-lg" id="priorite" name="priorite">
                                                <option value="normal">Normal</option>
                                                <option value="urgent" selected>Urgent</option>
                                                <option value="critique">Critique</option>
                                            </select>
                                            <div class="form-text">Évaluez le niveau d'urgence de ce besoin</div>
                                        </div>

                                        <!-- Notes/Commentaires -->
                                        <div class="mb-4">
                                            <label for="notes" class="form-label fw-bold">
                                                Notes additionnelles (optionnel)
                                            </label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                      placeholder="Ajoutez des informations supplémentaires sur ce besoin..."></textarea>
                                        </div>

                                        <!-- Boutons -->
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                                <strong>Enregistrer le besoin</strong>
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
        // Script pour afficher l'unité correspondant au produit sélectionné
        document.getElementById('produit').addEventListener('change', function() {
            const quantiteInput = document.getElementById('quantite');
            const produit = this.value;
            
            // Map des unités par produit
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
        document.getElementById('formBesoin').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Récupération des valeurs
            const ville = document.getElementById('ville').options[document.getElementById('ville').selectedIndex].text;
            const produit = document.getElementById('produit').options[document.getElementById('produit').selectedIndex].text;
            const quantite = document.getElementById('quantite').value;
            
            // Simulation d'enregistrement
            alert(`Besoin enregistré avec succès!\n\nVille: ${ville}\nProduit: ${produit}\nQuantité: ${quantite}`);
            
            // Dans une vraie application, on enverrait les données au serveur ici
            // this.submit();
        });
    </script>
</body>

</html>
