<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $base = isset($baseUrl) ? rtrim($baseUrl, '/') : ''; ?>
    <link href="<?= htmlspecialchars($base) ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($base) ?>/assets/css/style.css" rel="stylesheet">
    <title>Simulation de Dispatch Manuel - BNGRC</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include $_SERVER['DOCUMENT_ROOT'] . $base . '/includes/header.php'; ?>
    
    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
           
             <nav class="col-md-3 col-lg-2 bg-dark text-white p-3">
                <?php include $_SERVER['DOCUMENT_ROOT'] . $base . '/includes/menu.php'; ?>
            </nav>
            
           
            <main class="col-md-9 col-lg-10 p-4">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="fw-bold"> Simulation de Dispatch Automatique</h1>
                    </div>

                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Succès!</strong> <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error) && $error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erreur!</strong> <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Informations sur le système -->
                    <div class="alert alert-info">
                        <h5 class="alert-heading">Mode de Dispatch Automatique</h5>
                        <p class="mb-0">
                            Le système analyse automatiquement les besoins et les dons disponibles, puis propose un dispatch optimal basé sur :
                            <br><strong>1.</strong> L'ordre chronologique des besoins (les plus anciens en priorité)
                            <br><strong>2.</strong> L'ordre chronologique des dons (les plus anciens utilisés en premier)
                            <br><strong>3.</strong> La correspondance des produits
                            <br><strong>4.</strong> Les quantités disponibles vs requises
                        </p>
                    </div>


                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h6 class="text-warning">Besoins Non Satisfaits</h6>
                                    <h2 class="display-6"><?= $stats['total_besoins'] ?? 0 ?></h2>
                                    <small class="text-muted">En attente d'affectation</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="text-success">Dons Disponibles</h6>
                                    <h2 class="display-6"><?= $stats['total_dons'] ?? 0 ?></h2>
                                    <small class="text-muted">Non encore affectés</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h6 class="text-primary">Distributions Proposées</h6>
                                    <h2 class="display-6"><?= $stats['total_distributions'] ?? 0 ?></h2>
                                    <small class="text-muted">Affectations créées</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="text-info">Taux de Satisfaction</h6>
                                    <h2 class="display-6"><?= $stats['taux_satisfaction'] ?? 0 ?>%</h2>
                                    <small class="text-muted">Besoins couverts</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau 1: Besoins non satisfaits -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-danger text-white">
                            <h4 class="mb-0">1. Besoins Non Satisfaits (par ordre chronologique)</h4>
                        </div>
                        <div class="card-body">
                            <?php if (empty($besoins)): ?>
                                <div class="alert alert-success">
                                    <strong>Excellent !</strong> Tous les besoins ont été satisfaits.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Date Besoin</th>
                                                <th>Ville</th>
                                                <th>Produit</th>
                                                <th>Quantité Demandée</th>
                                                <th>Quantité Restante</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($besoins as $index => $besoin): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= date('d/m/Y', strtotime($besoin['dateBesoin'])) ?></td>
                                                    <td><strong><?= htmlspecialchars($besoin['ville_nom']) ?></strong></td>
                                                    <td><?= htmlspecialchars($besoin['produit_nom']) ?></td>
                                                    <td><?= htmlspecialchars($besoin['quantite']) ?></td>
                                                    <td>
                                                        <span class="badge bg-warning"><?= htmlspecialchars($besoin['quantite_restante']) ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tableau 2: Dons disponibles -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">2. Dons Disponibles (par ordre chronologique)</h4>
                        </div>
                        <div class="card-body">
                            <?php if (empty($dons)): ?>
                                <div class="alert alert-warning">
                                    <strong>Attention !</strong> Aucun don disponible pour le moment.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Date Don</th>
                                                <th>Donateur</th>
                                                <th>Type</th>
                                                <th>Produit</th>
                                                <th>Quantité Disponible</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dons as $index => $don): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= date('d/m/Y', strtotime($don['dateDon'])) ?></td>
                                                    <td><?= htmlspecialchars($don['donateur_nom']) ?></td>
                                                    <td>
                                                        <?php if ($don['type_don'] === 'nature'): ?>
                                                            <span class="badge bg-info">Nature</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning text-dark">Argent</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($don['produit_nom'] ?? 'Don financier') ?></td>
                                                    <td>
                                                        <span class="badge bg-success"><?= number_format($don['quantite_restante'], 0, ',', ' ') ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tableau 3: Distributions proposées automatiquement -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">3. Distributions Proposées Automatiquement</h4>
                            <?php if (!empty($distributions)): ?>
                                <form method="POST" action="<?= htmlspecialchars($base) ?>/simulation/confirmer" style="display: inline;">
                                    <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Confirmer l\'enregistrement de <?= count($distributions) ?> distribution(s) en base de données ?');">
                                        ✓ Confirmer et Enregistrer
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (empty($distributions)): ?>
                                <div class="alert alert-warning">
                                    <strong>Aucune distribution proposée.</strong>
                                    <br>Raisons possibles :
                                    <ul class="mb-0 mt-2">
                                        <li>Aucun besoin non satisfait</li>
                                        <li>Aucun don disponible</li>
                                        <li>Aucune correspondance produit entre besoins et dons</li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-3">
                                    <strong>ℹ️ Information :</strong> Le système a automatiquement matché <strong><?= count($distributions) ?></strong> distribution(s) 
                                    en fonction de l'ordre chronologique et de la disponibilité des produits.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Ville</th>
                                                <th>Produit</th>
                                                <th>Besoin (Date)</th>
                                                <th>Qté Demandée</th>
                                                <th>Donateur</th>
                                                <th>Don (Date)</th>
                                                <th>Qté Disponible</th>
                                                <th>Qté Attribuée</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($distributions as $index => $dist): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><strong><?= htmlspecialchars($dist['ville_nom']) ?></strong></td>
                                                    <td><?= htmlspecialchars($dist['produit_nom']) ?></td>
                                                    <td class="text-muted"><?= date('d/m/Y', strtotime($dist['dateBesoin'])) ?></td>
                                                    <td><?= htmlspecialchars($dist['besoin_quantite_demandee']) ?></td>
                                                    <td><?= htmlspecialchars($dist['donateur_nom']) ?></td>
                                                    <td class="text-muted"><?= date('d/m/Y', strtotime($dist['dateDon'])) ?></td>
                                                    <td><?= htmlspecialchars($dist['don_quantite_disponible']) ?></td>
                                                    <td>
                                                        <strong class="text-success"><?= htmlspecialchars($dist['quantite_attribuee']) ?></strong>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $ratio = ($dist['quantite_attribuee'] / $dist['besoin_quantite_restante']) * 100;
                                                        if ($ratio >= 100) {
                                                            echo '<span class="badge bg-success">Satisfait</span>';
                                                        } elseif ($ratio >= 50) {
                                                            echo '<span class="badge bg-warning text-dark">Partiel</span>';
                                                        } else {
                                                            echo '<span class="badge bg-danger">Insuffisant</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Résumé des distributions -->
                                <div class="alert alert-success mt-3">
                                    <h5 class="alert-heading">📊 Résumé du Dispatch</h5>
                                    <hr>
                                    <p class="mb-0">
                                        <strong><?= count($distributions) ?></strong> distribution(s) proposée(s) automatiquement.
                                        <br>Cliquez sur <strong>"Confirmer et Enregistrer"</strong> pour les enregistrer définitivement en base de données.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . $base . '/includes/footer.php'; ?>
    
    <script src="<?= htmlspecialchars($base) ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
