<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $base = isset($baseUrl) ? rtrim($baseUrl, '/') : ''; ?>
    <meta name="base-url" content="<?= htmlspecialchars($base) ?>">
    <link href="<?= htmlspecialchars($base) ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($base) ?>/assets/css/style.css" rel="stylesheet">
    <title>Récapitulatif - BNGRC</title>
    <style>
        .stat-card {
            transition: transform 0.2s ease-in-out;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .progress {
            height: 25px;
            border-radius: 15px;
        }
        .progress-bar {
            border-radius: 15px;
            font-weight: bold;
        }
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .loader-overlay.active {
            display: flex;
        }
        .loader-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
        }
        .spinner-large {
            width: 60px;
            height: 60px;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/../../public/includes/header.php'; ?>
    
    <!-- Loader/Spinner Overlay -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="loader-content">
            <div class="spinner-border text-primary spinner-large" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-3 mb-0 fw-bold">Actualisation des données...</p>
        </div>
    </div>
    
    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
           
            <nav class="col-md-3 col-lg-2 bg-dark text-white p-3">
                <?php include __DIR__ . '/../../public/includes/menu.php'; ?>
            </nav>
            
            <main class="col-md-9 col-lg-10 p-4">
                <div class="container-fluid">
                    <!-- En-tête avec bouton Actualiser -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="fw-bold">📊 Récapitulatif Global</h1>
                        <button type="button" id="btnActualiser" class="btn btn-primary btn-lg">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status" id="spinnerActualiser"></span>
                            🔄 ACTUALISER (AJAX)
                        </button>
                    </div>

                    <!-- Indicateurs Besoins -->
                    <div class="row mb-4">
                        <!-- Besoins Totaux -->
                        <div class="col-md-4 mb-3">
                            <div class="card stat-card border-primary h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-primary text-uppercase">Besoins Totaux</h6>
                                    <h2 class="display-4 fw-bold" id="besoinsTotaux">
                                        <?= $stats['besoins']['total'] ?? 0 ?>
                                    </h2>
                                    <small class="text-muted">Enregistrés dans le système</small>
                                </div>
                            </div>
                        </div>

                        <!-- Besoins Satisfaits -->
                        <div class="col-md-4 mb-3">
                            <div class="card stat-card border-success h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-success text-uppercase">Besoins Satisfaits</h6>
                                    <h2 class="display-4 fw-bold" id="besoinsSatisfaits">
                                        <?= $stats['besoins']['satisfaits'] ?? 0 ?>
                                    </h2>
                                    <small class="text-muted">Entièrement couverts</small>
                                </div>
                            </div>
                        </div>

                        <!-- Besoins Restants -->
                        <div class="col-md-4 mb-3">
                            <div class="card stat-card border-warning h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-warning text-uppercase">Besoins Restants</h6>
                                    <h2 class="display-4 fw-bold" id="besoinsRestants">
                                        <?= $stats['besoins']['en_attente'] ?? 0 ?>
                                    </h2>
                                    <small class="text-muted">En attente de couverture</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Barres de Progression CSS -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">📈 Progression Globale</h5>
                        </div>
                        <div class="card-body">
                            <!-- Barre Besoins Satisfaits -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">Besoins Satisfaits</span>
                                    <span id="pourcentageBesoins"><?= $stats['besoins']['pourcentage_satisfaits'] ?? 0 ?>%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         id="barreBesoins"
                                         style="width: <?= $stats['besoins']['pourcentage_satisfaits'] ?? 0 ?>%;" 
                                         aria-valuenow="<?= $stats['besoins']['pourcentage_satisfaits'] ?? 0 ?>" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?= $stats['besoins']['pourcentage_satisfaits'] ?? 0 ?>%
                                    </div>
                                </div>
                            </div>

                            <!-- Barre Dons Distribués -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">Dons Distribués</span>
                                    <span id="pourcentageDons"><?= $stats['dons']['pourcentage_distribues'] ?? 0 ?>%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" 
                                         id="barreDons"
                                         style="width: <?= $stats['dons']['pourcentage_distribues'] ?? 0 ?>%;" 
                                         aria-valuenow="<?= $stats['dons']['pourcentage_distribues'] ?? 0 ?>" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?= $stats['dons']['pourcentage_distribues'] ?? 0 ?>%
                                    </div>
                                </div>
                            </div>

                            <!-- Barre Distributions Confirmées -->
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">Distributions Confirmées</span>
                                    <span id="pourcentageDistributions"><?= $stats['distributions']['pourcentage_confirmees'] ?? 0 ?>%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                         id="barreDistributions"
                                         style="width: <?= $stats['distributions']['pourcentage_confirmees'] ?? 0 ?>%;" 
                                         aria-valuenow="<?= $stats['distributions']['pourcentage_confirmees'] ?? 0 ?>" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?= $stats['distributions']['pourcentage_confirmees'] ?? 0 ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques détaillées -->
                    <div class="row mb-4">
                        <!-- Dons -->
                        <div class="col-md-6 mb-3">
                            <div class="card shadow h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">🎁 Statistiques Dons</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Total Dons</span>
                                            <strong id="donsTotal"><?= $stats['dons']['total'] ?? 0 ?></strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Dons Nature</span>
                                            <strong id="donsNature"><?= $stats['dons']['nature'] ?? 0 ?></strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Dons Argent</span>
                                            <strong id="donsArgent"><?= $stats['dons']['argent'] ?? 0 ?></strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Montant Total (Ar)</span>
                                            <strong id="donsMontant"><?= number_format($stats['dons']['valeur_totale'] ?? 0, 0, ',', ' ') ?></strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Distributions -->
                        <div class="col-md-6 mb-3">
                            <div class="card shadow h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">📦 Statistiques Distributions</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Total Distributions</span>
                                            <strong id="distTotal"><?= $stats['distributions']['total'] ?? 0 ?></strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Confirmées</span>
                                            <strong class="text-success" id="distConfirmees"><?= $stats['distributions']['confirmees'] ?? 0 ?></strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>En attente</span>
                                            <strong class="text-warning" id="distEnAttente"><?= $stats['distributions']['en_attente'] ?? 0 ?></strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Quantité Totale</span>
                                            <strong id="distQuantite"><?= number_format($stats['distributions']['quantite_totale'] ?? 0, 0, ',', ' ') ?></strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Achats -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">💰 Statistiques Achats</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <h4 class="fw-bold" id="achatsTotaux"><?= $stats['achats']['total'] ?? 0 ?></h4>
                                            <small class="text-muted">Achats Effectués</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h4 class="fw-bold" id="achatsMontant"><?= number_format($stats['achats']['montant_total'] ?? 0, 0, ',', ' ') ?> Ar</h4>
                                            <small class="text-muted">Montant Total</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h4 class="fw-bold text-danger" id="achatsFrais"><?= number_format($stats['achats']['frais_total'] ?? 0, 0, ',', ' ') ?> Ar</h4>
                                            <small class="text-muted">Frais Appliqués</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h4 class="fw-bold text-success" id="achatsNet"><?= number_format($stats['achats']['montant_net'] ?? 0, 0, ',', ' ') ?> Ar</h4>
                                            <small class="text-muted">Montant Net</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dernière actualisation -->
                    <div class="text-center text-muted mt-4">
                        <small>Dernière actualisation: <span id="derniereActualisation"><?= date('d/m/Y H:i:s') ?></span></small>
                    </div>

                </div>
            </main>
        </div>
    </div>
    
    <?php include __DIR__ . '/../../public/includes/footer.php'; ?>
    
    <script src="<?= htmlspecialchars($base) ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= htmlspecialchars($base) ?>/assets/js/recap.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const baseUrl = '<?= htmlspecialchars($base) ?>';
        const btnActualiser = document.getElementById('btnActualiser');
        const spinnerActualiser = document.getElementById('spinnerActualiser');
        const loaderOverlay = document.getElementById('loaderOverlay');

        // Bouton ACTUALISER (AJAX)
        btnActualiser.addEventListener('click', function() {
            spinnerActualiser.classList.remove('d-none');
            loaderOverlay.classList.add('active');
            btnActualiser.disabled = true;

            fetch(baseUrl + '/api/stats')
                .then(response => response.json())
                .then(data => {
                    spinnerActualiser.classList.add('d-none');
                    loaderOverlay.classList.remove('active');
                    btnActualiser.disabled = false;

                    if (data.success && data.data) {
                        const stats = data.data;

                        // Mettre à jour indicateurs besoins
                        document.getElementById('besoinsTotaux').textContent = stats.besoins?.total || 0;
                        document.getElementById('besoinsSatisfaits').textContent = stats.besoins?.satisfaits || 0;
                        document.getElementById('besoinsRestants').textContent = stats.besoins?.en_attente || 0;

                        // Mettre à jour barres de progression
                        const pctBesoins = stats.besoins?.pourcentage_satisfaits || 0;
                        document.getElementById('pourcentageBesoins').textContent = pctBesoins + '%';
                        document.getElementById('barreBesoins').style.width = pctBesoins + '%';
                        document.getElementById('barreBesoins').textContent = pctBesoins + '%';

                        const pctDons = stats.dons?.pourcentage_distribues || 0;
                        document.getElementById('pourcentageDons').textContent = pctDons + '%';
                        document.getElementById('barreDons').style.width = pctDons + '%';
                        document.getElementById('barreDons').textContent = pctDons + '%';

                        const pctDist = stats.distributions?.pourcentage_confirmees || 0;
                        document.getElementById('pourcentageDistributions').textContent = pctDist + '%';
                        document.getElementById('barreDistributions').style.width = pctDist + '%';
                        document.getElementById('barreDistributions').textContent = pctDist + '%';

                        // Mettre à jour stats dons
                        document.getElementById('donsTotal').textContent = stats.dons?.total || 0;
                        document.getElementById('donsNature').textContent = stats.dons?.nature || 0;
                        document.getElementById('donsArgent').textContent = stats.dons?.argent || 0;
                        document.getElementById('donsMontant').textContent = (stats.dons?.valeur_totale || 0).toLocaleString('fr-FR');

                        // Mettre à jour stats distributions
                        document.getElementById('distTotal').textContent = stats.distributions?.total || 0;
                        document.getElementById('distConfirmees').textContent = stats.distributions?.confirmees || 0;
                        document.getElementById('distEnAttente').textContent = stats.distributions?.en_attente || 0;
                        document.getElementById('distQuantite').textContent = (stats.distributions?.quantite_totale || 0).toLocaleString('fr-FR');

                        // Mettre à jour stats achats
                        document.getElementById('achatsTotaux').textContent = stats.achats?.total || 0;
                        document.getElementById('achatsMontant').textContent = (stats.achats?.montant_total || 0).toLocaleString('fr-FR') + ' Ar';
                        document.getElementById('achatsFrais').textContent = (stats.achats?.frais_total || 0).toLocaleString('fr-FR') + ' Ar';
                        document.getElementById('achatsNet').textContent = (stats.achats?.montant_net || 0).toLocaleString('fr-FR') + ' Ar';

                        // Mettre à jour timestamp
                        const now = new Date();
                        document.getElementById('derniereActualisation').textContent = 
                            now.toLocaleDateString('fr-FR') + ' ' + now.toLocaleTimeString('fr-FR');

                        // Notification succès
                        alert('Données actualisées avec succès !');
                    } else {
                        alert('Erreur lors de la récupération des données');
                    }
                })
                .catch(error => {
                    spinnerActualiser.classList.add('d-none');
                    loaderOverlay.classList.remove('active');
                    btnActualiser.disabled = false;
                    console.error('Erreur:', error);
                    alert('Erreur de connexion');
                });
        });
    });
    </script>
</body>

</html>
