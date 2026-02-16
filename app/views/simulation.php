<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <title>Simulation de Dispatch Manuel - BNGRC</title>
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
                        <h1 class="fw-bold">Simulation de Dispatch Manuel</h1>
                    </div>

                    <!-- Informations sur le système -->
                    <div class="alert alert-info">
                        <h5 class="alert-heading">Mode de Dispatch Manuel</h5>
                        <p class="mb-0">
                            Sélectionnez un besoin, puis un don correspondant, saisissez la quantité à attribuer et ajoutez-le manuellement aux distributions proposées. 
                            Répétez l'opération pour chaque affectation souhaitée, puis validez l'ensemble du dispatch.
                        </p>
                    </div>


                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h6 class="text-warning">Besoins Non Satisfaits</h6>
                                    <h2 class="display-6" id="nbBesoins">12</h2>
                                    <small class="text-muted">En attente d'affectation</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="text-success">Dons Disponibles</h6>
                                    <h2 class="display-6" id="nbDons">15</h2>
                                    <small class="text-muted">Non encore affectés</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h6 class="text-primary">Distributions Proposées</h6>
                                    <h2 class="display-6" id="nbDistributions">0</h2>
                                    <small class="text-muted">Affectations créées</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau 1: Besoins non satisfaits -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-danger text-white">
                            <h4 class="mb-0">1. Besoins Non Satisfaits</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="tableBesoins">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sélection</th>
                                            <th>Ville</th>
                                            <th>Produit</th>
                                            <th>Quantité Demandée</th>
                                            <th>Date Besoin</th>
                                            <th>Priorité</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-besoin-id="1">
                                            <td><input type="radio" name="besoinSelect" value="1" class="form-check-input"></td>
                                            <td><strong>Antananarivo</strong></td>
                                            <td>Eau potable</td>
                                            <td>500 litres</td>
                                            <td>2026-02-10</td>
                                            <td><span class="badge bg-danger">Critique</span></td>
                                        </tr>
                                        <tr data-besoin-id="2">
                                            <td><input type="radio" name="besoinSelect" value="2" class="form-check-input"></td>
                                            <td><strong>Mahajanga</strong></td>
                                            <td>Riz</td>
                                            <td>300 kg</td>
                                            <td>2026-02-11</td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                        </tr>
                                        <tr data-besoin-id="3">
                                            <td><input type="radio" name="besoinSelect" value="3" class="form-check-input"></td>
                                            <td><strong>Sambava</strong></td>
                                            <td>Couvertures</td>
                                            <td>200 unités</td>
                                            <td>2026-02-12</td>
                                            <td><span class="badge bg-danger">Critique</span></td>
                                        </tr>
                                        <tr data-besoin-id="4">
                                            <td><input type="radio" name="besoinSelect" value="4" class="form-check-input"></td>
                                            <td><strong>Toamasina</strong></td>
                                            <td>Médicaments</td>
                                            <td>50 kits</td>
                                            <td>2026-02-12</td>
                                            <td><span class="badge bg-secondary">Normal</span></td>
                                        </tr>
                                        <tr data-besoin-id="5">
                                            <td><input type="radio" name="besoinSelect" value="5" class="form-check-input"></td>
                                            <td><strong>Antsiranana</strong></td>
                                            <td>Vêtements</td>
                                            <td>250 lots</td>
                                            <td>2026-02-13</td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                        </tr>
                                        <tr data-besoin-id="6">
                                            <td><input type="radio" name="besoinSelect" value="6" class="form-check-input"></td>
                                            <td><strong>Ambositra</strong></td>
                                            <td>Kits d'hygiène</td>
                                            <td>200 unités</td>
                                            <td>2026-02-13</td>
                                            <td><span class="badge bg-danger">Critique</span></td>
                                        </tr>
                                        <tr data-besoin-id="7">
                                            <td><input type="radio" name="besoinSelect" value="7" class="form-check-input"></td>
                                            <td><strong>Manakara</strong></td>
                                            <td>Huile</td>
                                            <td>200 litres</td>
                                            <td>2026-02-14</td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                        </tr>
                                        <tr data-besoin-id="8">
                                            <td><input type="radio" name="besoinSelect" value="8" class="form-check-input"></td>
                                            <td><strong>Taolagnaro</strong></td>
                                            <td>Tentes</td>
                                            <td>100 unités</td>
                                            <td>2026-02-14</td>
                                            <td><span class="badge bg-secondary">Normal</span></td>
                                        </tr>
                                        <tr data-besoin-id="9">
                                            <td><input type="radio" name="besoinSelect" value="9" class="form-check-input"></td>
                                            <td><strong>Antsirabe</strong></td>
                                            <td>Lampes torches</td>
                                            <td>100 unités</td>
                                            <td>2026-02-15</td>
                                            <td><span class="badge bg-danger">Critique</span></td>
                                        </tr>
                                        <tr data-besoin-id="10">
                                            <td><input type="radio" name="besoinSelect" value="10" class="form-check-input"></td>
                                            <td><strong>Morondava</strong></td>
                                            <td>Jerrycans</td>
                                            <td>120 unités</td>
                                            <td>2026-02-15</td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                        </tr>
                                        <tr data-besoin-id="11">
                                            <td><input type="radio" name="besoinSelect" value="11" class="form-check-input"></td>
                                            <td><strong>Fianarantsoa</strong></td>
                                            <td>Bois de construction</td>
                                            <td>50 m³</td>
                                            <td>2026-02-16</td>
                                            <td><span class="badge bg-secondary">Normal</span></td>
                                        </tr>
                                        <tr data-besoin-id="12">
                                            <td><input type="radio" name="besoinSelect" value="12" class="form-check-input"></td>
                                            <td><strong>Toliara</strong></td>
                                            <td>Tôles ondulées</td>
                                            <td>150 unités</td>
                                            <td>2026-02-11</td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau 2: Dons disponibles -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">2. Dons Disponibles</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="tableDons">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sélection</th>
                                            <th>Donateur</th>
                                            <th>Type</th>
                                            <th>Produit</th>
                                            <th>Quantité/Montant</th>
                                            <th>Date Don</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-don-id="1">
                                            <td><input type="radio" name="donSelect" value="1" class="form-check-input"></td>
                                            <td>Jean Rakoto</td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Riz</td>
                                            <td>250 kg</td>
                                            <td>2026-02-16</td>
                                        </tr>
                                        <tr data-don-id="2">
                                            <td><input type="radio" name="donSelect" value="2" class="form-check-input"></td>
                                            <td>ONG Solidarité</td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>500,000 Ar</td>
                                            <td>2026-02-16</td>
                                        </tr>
                                        <tr data-don-id="3">
                                            <td><input type="radio" name="donSelect" value="3" class="form-check-input"></td>
                                            <td>Marie Rasoa</td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Couvertures</td>
                                            <td>200 unités</td>
                                            <td>2026-02-15</td>
                                        </tr>
                                        <tr data-don-id="4">
                                            <td><input type="radio" name="donSelect" value="4" class="form-check-input"></td>
                                            <td>Entreprise ABC</td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Eau potable</td>
                                            <td>1000 litres</td>
                                            <td>2026-02-15</td>
                                        </tr>
                                        <tr data-don-id="5">
                                            <td><input type="radio" name="donSelect" value="5" class="form-check-input"></td>
                                            <td>Paul Andria</td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>200,000 Ar</td>
                                            <td>2026-02-14</td>
                                        </tr>
                                        <tr data-don-id="6">
                                            <td><input type="radio" name="donSelect" value="6" class="form-check-input"></td>
                                            <td>Association CARE</td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Médicaments</td>
                                            <td>50 kits</td>
                                            <td>2026-02-14</td>
                                        </tr>
                                        <tr data-don-id="7">
                                            <td><input type="radio" name="donSelect" value="7" class="form-check-input"></td>
                                            <td>Hanta Razafy</td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Vêtements</td>
                                            <td>150 lots</td>
                                            <td>2026-02-13</td>
                                        </tr>
                                        <tr data-don-id="8">
                                            <td><input type="radio" name="donSelect" value="8" class="form-check-input"></td>
                                            <td>Fondation Espoir</td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>1,000,000 Ar</td>
                                            <td>2026-02-13</td>
                                        </tr>
                                        <tr data-don-id="9">
                                            <td><input type="radio" name="donSelect" value="9" class="form-check-input"></td>
                                            <td>Koto Rasolofo</td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Tôles ondulées</td>
                                            <td>180 unités</td>
                                            <td>2026-02-12</td>
                                        </tr>
                                        <tr data-don-id="10">
                                            <td><input type="radio" name="donSelect" value="10" class="form-check-input"></td>
                                            <td>Soa Randria</td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>150,000 Ar</td>
                                            <td>2026-02-12</td>
                                        </tr>
                                        <tr data-don-id="11">
                                            <td><input type="radio" name="donSelect" value="11" class="form-check-input"></td>
                                            <td>Croix Rouge</td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Kits d'hygiène</td>
                                            <td>200 unités</td>
                                            <td>2026-02-11</td>
                                        </tr>
                                        <tr data-don-id="12">
                                            <td><input type="radio" name="donSelect" value="12" class="form-check-input"></td>
                                            <td>Rija Andriana</td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Huile</td>
                                            <td>120 litres</td>
                                            <td>2026-02-11</td>
                                        </tr>
                                        <tr data-don-id="13">
                                            <td><input type="radio" name="donSelect" value="13" class="form-check-input"></td>
                                            <td>Église Baptiste</td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>750,000 Ar</td>
                                            <td>2026-02-10</td>
                                        </tr>
                                        <tr data-don-id="14">
                                            <td><input type="radio" name="donSelect" value="14" class="form-check-input"></td>
                                            <td>Lala Rakoto</td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Tentes</td>
                                            <td>100 unités</td>
                                            <td>2026-02-10</td>
                                        </tr>
                                        <tr data-don-id="15">
                                            <td><input type="radio" name="donSelect" value="15" class="form-check-input"></td>
                                            <td>Groupe Ravinala</td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>2,000,000 Ar</td>
                                            <td>2026-02-09</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire d'ajout de distribution -->
                    <div class="card shadow mb-4 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">3. Créer une Affectation</h4>
                        </div>
                        <div class="card-body">
                            <form id="formAffectation">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Besoin sélectionné:</label>
                                            <input type="text" id="besoinSelectionne" class="form-control" readonly placeholder="Sélectionnez un besoin dans le tableau ci-dessus">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Don sélectionné:</label>
                                            <input type="text" id="donSelectionne" class="form-control" readonly placeholder="Sélectionnez un don dans le tableau ci-dessus">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantiteAffecter" class="form-label fw-bold">
                                                <span class="text-danger">*</span> Quantité/Montant à attribuer:
                                            </label>
                                            <input type="number" class="form-control form-control-lg" id="quantiteAffecter" 
                                                   min="1" step="0.01" placeholder="Ex: 250" required>
                                            <div class="form-text">Saisissez la quantité ou le montant à affecter</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Actions:</label>
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    Ajouter à la distribution
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tableau 3: Distributions proposées -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">4. Distributions Proposées</h4>
                            <button class="btn btn-success btn-lg" id="btnConfirmerDispatch" disabled>
                                Confirmer le dispatch
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="emptyMessage" class="alert alert-warning">
                                Aucune distribution créée pour le moment. Sélectionnez un besoin et un don, puis ajoutez-les manuellement.
                            </div>
                            <div class="table-responsive" id="tableDistributionsContainer" style="display: none;">
                                <table class="table table-bordered" id="tableDistributions">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Ville</th>
                                            <th>Besoin (Produit)</th>
                                            <th>Quantité Demandée</th>
                                            <th>Donateur</th>
                                            <th>Don (Produit)</th>
                                            <th>Quantité Affectée</th>
                                            <th>Date Distribution</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="distributionsBody">
                                    </tbody>
                                </table>
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
        // Variables globales pour stocker les sélections
        let besoinSelectionne = null;
        let donSelectionne = null;
        let distributions = [];


        // Gestion de la sélection d'un besoin
        document.querySelectorAll('#tableBesoins input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const besoinId = parseInt(this.value);
                besoinSelectionne = besoins.find(b => b.id === besoinId);
                
                // Mettre à jour l'affichage
                document.getElementById('besoinSelectionne').value = 
                    `${besoinSelectionne.ville} - ${besoinSelectionne.produit} (${besoinSelectionne.quantite})`;
                
                // Surligner la ligne
                document.querySelectorAll('#tableBesoins tbody tr').forEach(tr => tr.classList.remove('table-primary'));
                this.closest('tr').classList.add('table-primary');
            });
        });

        // Gestion de la sélection d'un don
        document.querySelectorAll('#tableDons input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const donId = parseInt(this.value);
                donSelectionne = dons.find(d => d.id === donId);
                
                // Mettre à jour l'affichage
                document.getElementById('donSelectionne').value = 
                    `${donSelectionne.donateur} - ${donSelectionne.produit} (${donSelectionne.quantite})`;
                
                // Surligner la ligne
                document.querySelectorAll('#tableDons tbody tr').forEach(tr => tr.classList.remove('table-success'));
                this.closest('tr').classList.add('table-success');
            });
        });

        // Gestion du formulaire d'ajout
        document.getElementById('formAffectation').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!besoinSelectionne) {
                alert('Veuillez sélectionner un besoin dans le premier tableau.');
                return;
            }
            
            if (!donSelectionne) {
                alert('Veuillez sélectionner un don dans le deuxième tableau.');
                return;
            }
            
            const quantite = document.getElementById('quantiteAffecter').value;
            if (!quantite || quantite <= 0) {
                alert('Veuillez saisir une quantité valide.');
                return;
            }
            
            // Créer la distribution
            const distribution = {
                id: distributions.length + 1,
                besoin: besoinSelectionne,
                don: donSelectionne,
                quantite: quantite,
                date: new Date().toISOString().split('T')[0]
            };
            
            distributions.push(distribution);
            
            // Ajouter la ligne au tableau
            ajouterLigneDistribution(distribution);
            
            // Réinitialiser le formulaire
            document.getElementById('formAffectation').reset();
            document.getElementById('besoinSelectionne').value = '';
            document.getElementById('donSelectionne').value = '';
            document.querySelectorAll('#tableBesoins input[type="radio"]:checked').forEach(r => r.checked = false);
            document.querySelectorAll('#tableDons input[type="radio"]:checked').forEach(r => r.checked = false);
            document.querySelectorAll('#tableBesoins tbody tr').forEach(tr => tr.classList.remove('table-primary'));
            document.querySelectorAll('#tableDons tbody tr').forEach(tr => tr.classList.remove('table-success'));
            
            besoinSelectionne = null;
            donSelectionne = null;
            
            // Mettre à jour les statistiques
            mettreAJourStatistiques();
            
            // Afficher un message de succès
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
                <strong>Succès!</strong> La distribution a été ajoutée. Continuez à créer d'autres affectations ou cliquez sur "Confirmer le dispatch".
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.card.border-primary .card-body').appendChild(alertDiv);
            
            // Auto-supprimer l'alerte après 3 secondes
            setTimeout(() => alertDiv.remove(), 3000);
        });

        // Fonction pour ajouter une ligne au tableau des distributions
        function ajouterLigneDistribution(distribution) {
            const tbody = document.getElementById('distributionsBody');
            const tr = document.createElement('tr');
            tr.setAttribute('data-id', distribution.id);
            
            tr.innerHTML = `
                <td><strong>${distribution.besoin.ville}</strong></td>
                <td>${distribution.besoin.produit}</td>
                <td>${distribution.besoin.quantite}</td>
                <td>${distribution.don.donateur}</td>
                <td>${distribution.don.produit}</td>
                <td><strong class="text-success">${distribution.quantite}</strong></td>
                <td>${distribution.date}</td>
                <td>
                    <button class="btn btn-danger btn-sm btn-supprimer" onclick="supprimerDistribution(${distribution.id})">
                        Supprimer
                    </button>
                </td>
            `;
            
            tbody.appendChild(tr);
            
            // Afficher le tableau
            document.getElementById('emptyMessage').style.display = 'none';
            document.getElementById('tableDistributionsContainer').style.display = 'block';
        }

        // Fonction pour supprimer une distribution
        function supprimerDistribution(id) {
            if (!confirm('Voulez-vous vraiment supprimer cette distribution?')) {
                return;
            }
            
            // Supprimer de la liste
            distributions = distributions.filter(d => d.id !== id);
            
            // Supprimer la ligne
            document.querySelector(`#distributionsBody tr[data-id="${id}"]`).remove();
            
            // Mettre à jour les statistiques
            mettreAJourStatistiques();
            
            // Si plus de distributions, cacher le tableau
            if (distributions.length === 0) {
                document.getElementById('emptyMessage').style.display = 'block';
                document.getElementById('tableDistributionsContainer').style.display = 'none';
            }
        }

        // Fonction pour mettre à jour les statistiques
        function mettreAJourStatistiques() {
            document.getElementById('nbDistributions').textContent = distributions.length;
            
            // Activer/désactiver le bouton de confirmation
            document.getElementById('btnConfirmerDispatch').disabled = distributions.length === 0;
        }

        // Confirmer le dispatch
        document.getElementById('btnConfirmerDispatch').addEventListener('click', function() {
            if (distributions.length === 0) {
                alert('Aucune distribution à confirmer.');
                return;
            }
            
            if (!confirm(`Confirmer l'enregistrement de ${distributions.length} distribution(s)?`)) {
                return;
            }
            
            // Ici, vous enverriez les données au serveur
            console.log('Distributions à enregistrer:', distributions);
            
            // Afficher un message de succès
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <strong>Succès!</strong> ${distributions.length} distribution(s) ont été confirmées et enregistrées dans la base de données.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.container-fluid').firstChild);
            
            // Réinitialiser
            distributions = [];
            document.getElementById('distributionsBody').innerHTML = '';
            document.getElementById('emptyMessage').style.display = 'block';
            document.getElementById('tableDistributionsContainer').style.display = 'none';
            mettreAJourStatistiques();
            
            // Scroller vers le haut
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>

</html>
