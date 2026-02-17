<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $base = isset($baseUrl) ? rtrim($baseUrl, '/') : ''; ?>
    <meta name="base-url" content="<?= htmlspecialchars($base) ?>">
    <link href="<?= htmlspecialchars($base) ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($base) ?>/assets/css/style.css" rel="stylesheet">
    <title>Proposer Achats Automatiques - BNGRC</title>
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="fw-bold">💰 Propositions d'Achats Automatiques</h1>
                        <a href="<?= htmlspecialchars($base) ?>/besoins-restants" class="btn btn-secondary">
                            ← Retour Besoins Restants
                        </a>
                    </div>

                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error) && $error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Résumé des dons en argent disponibles -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6 class="text-uppercase">Dons en Argent Disponibles</h6>
                                    <h2 class="fw-bold"><?= number_format($totalDonsArgent ?? 0, 0, ',', ' ') ?> Ar</h2>
                                    <small><?= count($donsArgent ?? []) ?> don(s) financier(s)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center">
                                    <h6 class="text-uppercase">Frais d'Achat</h6>
                                    <h2 class="fw-bold"><?= number_format($fraisPourcent ?? 10, 1) ?> %</h2>
                                    <small>Frais appliqués à chaque achat</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6 class="text-uppercase">Besoins à Acheter</h6>
                                    <h2 class="fw-bold"><?= count($propositions ?? []) ?></h2>
                                    <small>Besoins non couverts par les dons nature</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des dons en argent -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">💵 Dons Financiers Disponibles</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($donsArgent)): ?>
                                <div class="alert alert-warning mb-0">
                                    <strong>Attention !</strong> Aucun don financier disponible pour effectuer des achats.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Donateur</th>
                                                <th>Montant Restant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($donsArgent as $don): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($don['dateDon'] ?? '')) ?></td>
                                                    <td><?= htmlspecialchars($don['donateur_nom'] ?? 'Anonyme') ?></td>
                                                    <td><span class="badge bg-success"><?= number_format($don['montant_restant'] ?? 0, 0, ',', ' ') ?> Ar</span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Propositions d'achat -->
                    <form method="post" action="<?= htmlspecialchars($base) ?>/achats/auto/valider" id="formAchat">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">🛒 Besoins à Acheter (sélectionnez)</h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-light" id="btnSelectAll">Tout sélectionner</button>
                                    <button type="button" class="btn btn-sm btn-outline-light" id="btnDeselectAll">Tout désélectionner</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (empty($propositions)): ?>
                                    <div class="alert alert-info mb-0">
                                        Aucun besoin non satisfait à acheter pour le moment.
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="tablePropositions">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><input type="checkbox" id="checkAll"></th>
                                                    <th>Date Besoin</th>
                                                    <th>Ville</th>
                                                    <th>Produit</th>
                                                    <th>Quantité</th>
                                                    <th>Prix Unit.</th>
                                                    <th>Coût</th>
                                                    <th>Frais (<?= number_format($fraisPourcent ?? 10, 0) ?>%)</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $totalCout = 0;
                                                $totalFrais = 0;
                                                $totalGeneral = 0;
                                                foreach ($propositions as $p): 
                                                    $totalCout += $p['coutEstime'];
                                                    $totalFrais += $p['frais'];
                                                    $totalGeneral += $p['total'];
                                                ?>
                                                    <tr data-id="<?= (int)$p['idBesoin'] ?>" data-total="<?= $p['total'] ?>">
                                                        <td>
                                                            <input type="checkbox" name="besoin_ids[]" 
                                                                   value="<?= (int)$p['idBesoin'] ?>" 
                                                                   class="check-besoin">
                                                        </td>
                                                        <td><?= date('d/m/Y', strtotime($p['dateBesoin'] ?? '')) ?></td>
                                                        <td><strong><?= htmlspecialchars($p['ville_nom'] ?? '') ?></strong></td>
                                                        <td><?= htmlspecialchars($p['produit_nom'] ?? '') ?></td>
                                                        <td><?= number_format($p['quantite'] ?? 0, 0, ',', ' ') ?></td>
                                                        <td><?= number_format($p['prixUnitaire'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                                        <td><?= number_format($p['coutEstime'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                                        <td><?= number_format($p['frais'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                                        <td><strong><?= number_format($p['total'] ?? 0, 0, ',', ' ') ?> Ar</strong></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot class="table-dark">
                                                <tr>
                                                    <td colspan="6" class="text-end"><strong>TOTAUX :</strong></td>
                                                    <td><?= number_format($totalCout, 0, ',', ' ') ?> Ar</td>
                                                    <td><?= number_format($totalFrais, 0, ',', ' ') ?> Ar</td>
                                                    <td><strong><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <!-- Résumé sélection -->
                                    <div class="alert alert-info mt-3" id="resumeSelection">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Sélectionnés:</strong> <span id="countSelected">0</span> besoin(s)
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Montant total:</strong> <span id="montantSelected">0</span> Ar
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Budget disponible:</strong> 
                                                <span id="budgetRestant" class="<?= $totalDonsArgent > 0 ? 'text-success' : 'text-danger' ?>">
                                                    <?= number_format($totalDonsArgent ?? 0, 0, ',', ' ') ?> Ar
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="<?= htmlspecialchars($base) ?>/besoins-restants" class="btn btn-secondary">Annuler</a>
                                <div>
                                    <?php if (!empty($propositions) && !empty($donsArgent)): ?>
                                        <button type="submit" class="btn btn-success btn-lg" id="btnValider" disabled>
                                            ✅ Valider les Achats Sélectionnés
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-secondary btn-lg" disabled>
                                            Pas de dons disponibles
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <?php include __DIR__ . '/../../public/includes/footer.php'; ?>
    <script src="<?= htmlspecialchars($base) ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
        const totalDonsArgent = <?= json_encode($totalDonsArgent ?? 0) ?>;
        
        const checkboxes = document.querySelectorAll('.check-besoin');
        const checkAll = document.getElementById('checkAll');
        const btnSelectAll = document.getElementById('btnSelectAll');
        const btnDeselectAll = document.getElementById('btnDeselectAll');
        const btnValider = document.getElementById('btnValider');
        const countSelected = document.getElementById('countSelected');
        const montantSelected = document.getElementById('montantSelected');
        const budgetRestant = document.getElementById('budgetRestant');

        function updateSelection() {
            let count = 0;
            let montant = 0;
            
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    count++;
                    const row = cb.closest('tr');
                    montant += parseFloat(row.dataset.total) || 0;
                }
            });

            if (countSelected) countSelected.textContent = count;
            if (montantSelected) montantSelected.textContent = montant.toLocaleString('fr-FR');
            
            const restant = totalDonsArgent - montant;
            if (budgetRestant) {
                budgetRestant.textContent = restant.toLocaleString('fr-FR') + ' Ar';
                budgetRestant.className = restant >= 0 ? 'text-success fw-bold' : 'text-danger fw-bold';
            }

            if (btnValider) {
                btnValider.disabled = count === 0 || montant > totalDonsArgent;
                if (montant > totalDonsArgent) {
                    btnValider.textContent = '❌ Budget insuffisant';
                    btnValider.className = 'btn btn-danger btn-lg';
                } else {
                    btnValider.textContent = '✅ Valider les Achats Sélectionnés';
                    btnValider.className = 'btn btn-success btn-lg';
                }
            }
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateSelection));
        
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateSelection();
            });
        }

        if (btnSelectAll) {
            btnSelectAll.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = true);
                if (checkAll) checkAll.checked = true;
                updateSelection();
            });
        }

        if (btnDeselectAll) {
            btnDeselectAll.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = false);
                if (checkAll) checkAll.checked = false;
                updateSelection();
            });
        }

        // Formulaire
        const form = document.getElementById('formAchat');
        if (form) {
            form.addEventListener('submit', function(e) {
                const selected = document.querySelectorAll('.check-besoin:checked');
                if (selected.length === 0) {
                    e.preventDefault();
                    alert('Veuillez sélectionner au moins un besoin à acheter.');
                    return false;
                }
                
                if (!confirm('Confirmer l\'achat de ' + selected.length + ' besoin(s) ?')) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        // Init
        updateSelection();
    });
    </script>
</body>

</html>
