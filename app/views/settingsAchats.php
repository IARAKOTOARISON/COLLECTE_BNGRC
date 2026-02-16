<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <title>Configuration Achats - BNGRC</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
            <nav class="col-md-3 col-lg-2 bg-dark text-white p-3">
                <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/menu.php'; ?>
            </nav>
            <main class="col-md-9 col-lg-10 p-4">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Configuration des Achats</h1>
                        <a href="/simulation" class="btn btn-secondary">Retour</a>
                    </div>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <div class="card shadow">
                        <div class="card-body">
                            <form method="POST" action="/admin/achats/config/save">
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Pourcentage de frais (%)</label>
                                    <div class="col-sm-3">
                                        <input type="number" step="0.01" min="0" name="frais_percent" class="form-control" value="<?= htmlspecialchars($frais_percent ?? '') ?>" required>
                                    </div>
                                </div>
                                <button class="btn btn-primary">Enregistrer</button>
                            </form>
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
