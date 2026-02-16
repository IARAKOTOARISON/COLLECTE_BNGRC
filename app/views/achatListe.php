<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <title>Liste des Achats - BNGRC</title>
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
                        <h1 class="fw-bold">Historique des Achats</h1>
                        <a href="/simulation" class="btn btn-secondary">Retour aux besoins restants</a>
                    </div>

                    <!-- Filtre par ville -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="GET" action="/achats/liste" class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <label for="ville" class="col-form-label">Filtrer par ville</label>
                                </div>
                                <div class="col-auto">
                                    <select name="ville" id="ville" class="form-select">
                                        <option value="">-- Toutes --</option>
                                        <?php foreach (($villes ?? []) as $v): ?>
                                            <option value="<?= $v['id'] ?>" <?= (isset($villeFilter) && $villeFilter == $v['id']) ? 'selected' : '' ?>><?= htmlspecialchars($v['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary">Filtrer</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Ville</th>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Montant (sans frais)</th>
                                            <th>Frais</th>
                                            <th>Montant Total</th>
                                            <th>Donateur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($achats)): ?>
                                            <?php foreach ($achats as $i => $a): ?>
                                                <tr>
                                                    <td><?= $i + 1 ?></td>
                                                    <td><?= date('d/m/Y', strtotime($a['dateAchat'])) ?></td>
                                                    <td><?= htmlspecialchars($a['ville_nom']) ?></td>
                                                    <td><?= htmlspecialchars($a['produit_nom']) ?></td>
                                                    <td><?= htmlspecialchars($a['quantiteAchetee']) ?></td>
                                                    <td><?= htmlspecialchars($a['montant_sans_frais']) ?></td>
                                                    <td><?= htmlspecialchars($a['frais']) ?></td>
                                                    <td><strong><?= htmlspecialchars($a['montant_total']) ?></strong></td>
                                                    <td><?= htmlspecialchars($a['donateur_nom'] ?? '') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-4"><em>Aucun achat enregistré.</em></td>
                                            </tr>
                                        <?php endif; ?>
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
</body>

</html>
