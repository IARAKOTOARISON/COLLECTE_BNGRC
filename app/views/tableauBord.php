<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <title>Accueil - BNGRC</title>
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
                    <h1 class="fw-bold mb-4">Tableau de Bord - Collecte BNGRC</h1>
                    
                    <!-- Statistiques globales -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Villes affectées</h5>
                                    <h2 class="display-4"><?= count(array_unique(array_column($aboutVille, 'ville'))) ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">Total des dons collectés</h5>
                                    <h2 class="display-4">
                                        <?= array_sum(array_map(fn($d) => $d['quantite'] ?? $d['montant'], $aboutVille)) ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <h5 class="card-title">Besoins en attente</h5>
                                    <h2 class="display-4">
                                        <?= array_sum(array_map(fn($d) => $d['reste'] ?? 0, $aboutVille)) ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des villes -->
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h4 class="mb-0">Liste des Villes et Besoins</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ville</th>
                                            <th scope="col">Besoins</th>
                                            <th scope="col">Quantité Nécessaire</th>
                                            <th scope="col">Dons Reçus</th>
                                            <th scope="col">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($aboutVille as $besoin): ?>
                                            <?php
                                                $ville = htmlspecialchars($besoin['ville']);
                                                $produit = htmlspecialchars($besoin['produit']);
                                                $quantite = $besoin['quantite'] ?? null;
                                                $montant = $besoin['montant'] ?? null;
                                                $reste = $besoin['reste'] ?? 0;
                                                $recu = ($quantite ?? $montant) - $reste;
                                                $progress = ($quantite ?? $montant) > 0 ? round(($recu / ($quantite ?? $montant)) * 100) : 0;

                                                $progressClass = match(true) {
                                                    $progress >= 100 => 'bg-success',
                                                    $progress >= 50 => 'bg-warning',
                                                    default => 'bg-danger',
                                                };

                                                $statut = match(true) {
                                                    $progress >= 100 => 'Complet',
                                                    $progress >= 50 => 'En cours',
                                                    default => 'Urgent',
                                                };
                                            ?>
                                            <tr>
                                                <th scope="row"><?= $i++ ?></th>
                                                <td><strong><?= $ville ?></strong></td>
                                                <td><span class="badge bg-info"><?= $produit ?></span></td>
                                                <td>
                                                    <?= $quantite ? $quantite . ' unités' : $montant . ' Ar' ?>
                                                </td>
                                                <td>
                                                    <span class="text-<?= $progressClass ?> fw-bold"><?= $recu ?> <?= $quantite ? 'unités' : 'Ar' ?></span>
                                                    <div class="progress mt-1" style="height: 8px;">
                                                        <div class="progress-bar <?= $progressClass ?>" role="progressbar" style="width: <?= $progress ?>%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $progressClass ?>"><?= $statut ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
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
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
    
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
