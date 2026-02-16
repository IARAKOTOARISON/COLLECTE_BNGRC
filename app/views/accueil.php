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
                                    <h2 class="display-4">12</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">Total des dons collectés</h5>
                                    <h2 class="display-4">245</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <h5 class="card-title">Besoins en attente</h5>
                                    <h2 class="display-4">87</h2>
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
                                        <tr>
                                            <th scope="row">1</th>
                                            <td><strong>Antananarivo</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Eau potable</span>
                                                <span class="badge bg-info me-1">Nourriture</span>
                                                <span class="badge bg-info">Médicaments</span>
                                            </td>
                                            <td>500 unités</td>
                                            <td>
                                                <span class="text-success fw-bold">350 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 70%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning">En cours</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td><strong>Toamasina</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Couvertures</span>
                                                <span class="badge bg-info">Tentes</span>
                                            </td>
                                            <td>200 unités</td>
                                            <td>
                                                <span class="text-success fw-bold">200 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-success">Complet</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td><strong>Mahajanga</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Vêtements</span>
                                                <span class="badge bg-info me-1">Eau potable</span>
                                                <span class="badge bg-info">Kits d'hygiène</span>
                                            </td>
                                            <td>300 unités</td>
                                            <td>
                                                <span class="text-danger fw-bold">85 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 28%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-danger">Urgent</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td><strong>Fianarantsoa</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Nourriture</span>
                                                <span class="badge bg-info">Médicaments</span>
                                            </td>
                                            <td>400 unités</td>
                                            <td>
                                                <span class="text-warning fw-bold">240 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning">En cours</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td><strong>Toliara</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Eau potable</span>
                                                <span class="badge bg-info">Tentes</span>
                                            </td>
                                            <td>250 unités</td>
                                            <td>
                                                <span class="text-danger fw-bold">50 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-danger">Urgent</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">6</th>
                                            <td><strong>Antsiranana</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Couvertures</span>
                                                <span class="badge bg-info me-1">Vêtements</span>
                                                <span class="badge bg-info">Nourriture</span>
                                            </td>
                                            <td>350 unités</td>
                                            <td>
                                                <span class="text-success fw-bold">280 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 80%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning">En cours</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">7</th>
                                            <td><strong>Antsirabe</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Médicaments</span>
                                                <span class="badge bg-info">Kits d'hygiène</span>
                                            </td>
                                            <td>180 unités</td>
                                            <td>
                                                <span class="text-warning fw-bold">120 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 67%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning">En cours</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">8</th>
                                            <td><strong>Morondava</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Eau potable</span>
                                                <span class="badge bg-info me-1">Nourriture</span>
                                                <span class="badge bg-info">Tentes</span>
                                            </td>
                                            <td>220 unités</td>
                                            <td>
                                                <span class="text-danger fw-bold">45 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-danger">Urgent</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">9</th>
                                            <td><strong>Ambositra</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Vêtements</span>
                                                <span class="badge bg-info">Couvertures</span>
                                            </td>
                                            <td>150 unités</td>
                                            <td>
                                                <span class="text-success fw-bold">150 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-success">Complet</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">10</th>
                                            <td><strong>Manakara</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Médicaments</span>
                                                <span class="badge bg-info me-1">Kits d'hygiène</span>
                                                <span class="badge bg-info">Eau potable</span>
                                            </td>
                                            <td>280 unités</td>
                                            <td>
                                                <span class="text-warning fw-bold">165 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 59%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning">En cours</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">11</th>
                                            <td><strong>Sambava</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Nourriture</span>
                                                <span class="badge bg-info">Tentes</span>
                                            </td>
                                            <td>190 unités</td>
                                            <td>
                                                <span class="text-danger fw-bold">38 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-danger">Urgent</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">12</th>
                                            <td><strong>Taolagnaro</strong></td>
                                            <td>
                                                <span class="badge bg-info me-1">Eau potable</span>
                                                <span class="badge bg-info me-1">Vêtements</span>
                                                <span class="badge bg-info">Couvertures</span>
                                            </td>
                                            <td>320 unités</td>
                                            <td>
                                                <span class="text-success fw-bold">256 unités</span>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 80%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning">En cours</span></td>
                                        </tr>
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