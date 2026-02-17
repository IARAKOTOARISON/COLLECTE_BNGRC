<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $base = isset($baseUrl) ? rtrim($baseUrl, '/') : ''; ?>
    <link href="<?= htmlspecialchars($base) ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($base) ?>/assets/css/style.css" rel="stylesheet">
    <title>Besoins restants pour achats - BNGRC</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/../../public/includes/header.php'; ?>

    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
            <nav class="col-md-3 col-lg-2 bg-dark text-white p-3">
                <?php include __DIR__ . '/../../public/includes/menu.php'; ?>
            </nav>

            <main class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="fw-bold">Besoins restants - Proposition d'achat</h1>
                </div>

                <?php if (isset($error) && $error): ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <!-- Section: dons argent disponibles -->
                <div class="card mb-4">
                    <div class="card-header">Dons financiers disponibles</div>
                    <div class="card-body">
                        <?php if (!empty($donsDisponibles)): ?>
                            <ul class="list-group">
                                <?php foreach ($donsDisponibles as $don): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= htmlspecialchars($don['donateur_nom'] ?? 'Don') ?></strong>
                                            <div class="text-muted small"><?= htmlspecialchars($don['dateDon'] ?? '') ?></div>
                                        </div>
                                        <span class="badge bg-warning text-dark">
                                            <?= number_format($don['montant'] ?? 0, 0, ',', ' ') ?> Ar
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted mb-0">Aucun don financier disponible pour le moment.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tableau des besoins avec cases à cocher -->
                <form method="post" action="<?= htmlspecialchars($base) ?>/achats/valider">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">Sélection des besoins</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th></th>
                                            <th>Ville</th>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix Unitaire</th>
                                            <th>Coût estimé</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($besoins)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4">Aucun besoin non satisfait pour le moment.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($besoins as $i => $b): ?>
                                                <?php
                                                    $prix = $b['prixUnitaire'] ?? 0;
                                                    $quantite = $b['quantite'] ?? 0;
                                                    $cout = $prix * $quantite;
                                                ?>
                                                <tr>
                                                    <td><input type="checkbox" name="selected_besoins[]" value="<?= (int)($b['id'] ?? 0) ?>"></td>
                                                    <td><?= htmlspecialchars($b['ville_nom'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($b['produit_nom'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($quantite) ?></td>
                                                    <td><?= number_format($prix, 0, ',', ' ') ?> Ar</td>
                                                    <td><?= number_format($cout, 0, ',', ' ') ?> Ar</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-success">Acheter sélection</button>
                                <a href="<?= htmlspecialchars($base) ?>/achats/proposer" class="btn btn-outline-primary ms-2">Achat automatique prioritaire</a>
                            </div>
                            <div class="text-muted small">Affiche le prix unitaire et le coût total par besoin</div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <?php include __DIR__ . '/../../public/includes/footer.php'; ?>
    <script src="<?= htmlspecialchars($base) ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
