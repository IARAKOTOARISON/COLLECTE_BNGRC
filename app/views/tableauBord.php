<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $base = isset($baseUrl) ? rtrim($baseUrl, '/') : ''; ?>
    <link href="<?= htmlspecialchars($base) ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($base) ?>/assets/css/style.css" rel="stylesheet">
    <title>Accueil - BNGRC</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/../../public/includes/header.php'; ?>
    
    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
           
            <nav class="col-md-3 col-lg-2 bg-dark text-white p-3">
                <?php include __DIR__ . '/../../public/includes/menu.php'; ?>
            </nav>
            
            <main class="col-md-9 col-lg-10 p-4">
                <div class="container-fluid">
                    <h1 class="fw-bold mb-4">📊 Tableau de Bord - Collecte BNGRC</h1>
                    
                    <!-- Statistiques globales principales -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Villes Affectées</h6>
                                    <h2 class="display-4"><?= $stats['total_villes'] ?? 0 ?></h2>
                                    <small>Zones d'intervention</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Total Besoins</h6>
                                    <h2 class="display-4"><?= $stats['total_besoins'] ?? 0 ?></h2>
                                    <small>Demandes enregistrées</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Total Dons</h6>
                                    <h2 class="display-4"><?= $stats['total_dons'] ?? 0 ?></h2>
                                    <small>Contributions reçues</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Distributions</h6>
                                    <h2 class="display-4"><?= $stats['total_distributions'] ?? 0 ?></h2>
                                    <small>Affectations effectuées</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques détaillées -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h6 class="text-success">Quantité Distribuée</h6>
                                    <h3 class="display-6"><?= number_format($stats['quantite_totale_distribuee'] ?? 0, 0, ',', ' ') ?></h3>
                                    <small class="text-muted">Unités livrées</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="text-warning">Quantité Restante</h6>
                                    <h3 class="display-6"><?= number_format($stats['quantite_totale_restante'] ?? 0, 0, ',', ' ') ?></h3>
                                    <small class="text-muted">Besoins non couverts</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="text-primary">Taux de Satisfaction</h6>
                                    <h3 class="display-6"><?= $stats['taux_satisfaction'] ?? 0 ?>%</h3>
                                    <div class="progress mt-2" style="height: 10px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: <?= $stats['taux_satisfaction'] ?? 0 ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Répartition des statuts -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Répartition des Besoins par Statut</h5>
                                    <div class="row text-center mt-3">
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded">
                                                <span class="badge bg-success mb-2" style="font-size: 1.2rem;">Complets</span>
                                                <h2 class="display-6"><?= $stats['besoins_complets'] ?? 0 ?></h2>
                                                <small class="text-muted">100% satisfaits</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded">
                                                <span class="badge bg-warning text-dark mb-2" style="font-size: 1.2rem;">En Cours</span>
                                                <h2 class="display-6"><?= $stats['besoins_en_cours'] ?? 0 ?></h2>
                                                <small class="text-muted">50-99% satisfaits</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded">
                                                <span class="badge bg-danger mb-2" style="font-size: 1.2rem;">Urgents</span>
                                                <h2 class="display-6"><?= $stats['besoins_urgents'] ?? 0 ?></h2>
                                                <small class="text-muted">&lt;50% satisfaits</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des villes -->
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h4 class="mb-0">Détail des Besoins par Ville et Produit</h4>
                        </div>
                        <div class="card-body">
                            <?php if (empty($aboutVille)): ?>
                                <div class="alert alert-info">
                                    <strong>Aucun besoin enregistré</strong> pour le moment.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Ville</th>
                                                <th scope="col">Produit</th>
                                                <th scope="col">Qté Demandée</th>
                                                <th scope="col">Qté Distribuée</th>
                                                <th scope="col">Qté Restante</th>
                                                <th scope="col">Progression</th>
                                                <th scope="col">Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($aboutVille as $index => $besoin): ?>
                                                <?php
                                                    $progressClass = match($besoin['statut']) {
                                                        'Complet' => 'bg-success',
                                                        'En cours' => 'bg-warning',
                                                        'Urgent' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                    
                                                    $badgeClass = match($besoin['statut']) {
                                                        'Complet' => 'bg-success',
                                                        'En cours' => 'bg-warning text-dark',
                                                        'Urgent' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                ?>
                                                <tr>
                                                    <th scope="row"><?= $index + 1 ?></th>
                                                    <td><strong><?= htmlspecialchars($besoin['ville']) ?></strong></td>
                                                    <td><span class="badge bg-info"><?= htmlspecialchars($besoin['produit']) ?></span></td>
                                                    <td><?= number_format($besoin['quantite'], 0, ',', ' ') ?></td>
                                                    <td>
                                                        <span class="text-success fw-bold">
                                                            <?= number_format($besoin['quantite_distribuee'], 0, ',', ' ') ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-danger fw-bold">
                                                            <?= number_format($besoin['reste'], 0, ',', ' ') ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="fw-bold"><?= $besoin['progression'] ?>%</span>
                                                            <div class="progress flex-grow-1" style="height: 10px; min-width: 80px;">
                                                                <div class="progress-bar <?= $progressClass ?>" 
                                                                     role="progressbar" 
                                                                     style="width: <?= $besoin['progression'] ?>%"
                                                                     aria-valuenow="<?= $besoin['progression'] ?>" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?= $badgeClass ?>"><?= $besoin['statut'] ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Légende -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Légende des statuts</h5>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span><span class="badge bg-success">Complet</span> - 100% des besoins couverts</span>
                                        <span><span class="badge bg-warning">En cours</span> - 50-99% des besoins couverts</span>
                                        <span><span class="badge bg-danger">Urgent</span> - Moins de 50% des besoins couverts</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
    
    <?php include __DIR__ . '/../../public/includes/footer.php'; ?>
    
    <script src="<?= htmlspecialchars($base) ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
