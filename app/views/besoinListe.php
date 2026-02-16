<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <title>Liste des Besoins - BNGRC</title>
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
                        <h1 class="fw-bold">Liste des Besoins Enregistrés</h1>
                        <a href="/besoins/formulaire" class="btn btn-primary">
                            Ajouter un nouveau besoin
                        </a>
                    </div>

                    <!-- Filtres -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="filtreVille" class="form-label fw-bold">Filtrer par ville</label>
                                    <select class="form-select" id="filtreVille">
                                        <option value="">Toutes les villes</option>
                                        <option value="Antananarivo">Antananarivo</option>
                                        <option value="Toamasina">Toamasina</option>
                                        <option value="Mahajanga">Mahajanga</option>
                                        <option value="Fianarantsoa">Fianarantsoa</option>
                                        <option value="Toliara">Toliara</option>
                                        <option value="Antsiranana">Antsiranana</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="filtrePriorite" class="form-label fw-bold">Filtrer par priorité</label>
                                    <select class="form-select" id="filtrePriorite">
                                        <option value="">Toutes les priorités</option>
                                        <option value="critique">Critique</option>
                                        <option value="urgent">Urgent</option>
                                        <option value="normal">Normal</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="filtreStatut" class="form-label fw-bold">Filtrer par statut</label>
                                    <select class="form-select" id="filtreStatut">
                                        <option value="">Tous les statuts</option>
                                        <option value="attente">En attente</option>
                                        <option value="partiel">Partiellement satisfait</option>
                                        <option value="satisfait">Satisfait</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Total Besoins</h6>
                                    <h2 class="display-5">45</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Critiques</h6>
                                    <h2 class="display-5">8</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Urgents</h6>
                                    <h2 class="display-5">22</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Satisfaits</h6>
                                    <h2 class="display-5">15</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des besoins -->
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h4 class="mb-0">Liste complète des besoins par ville</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Ville</th>
                                            <th scope="col">Produit</th>
                                            <th scope="col">Quantité Demandée</th>
                                            <th scope="col">Quantité Reçue</th>
                                            <th scope="col">Priorité</th>
                                            <th scope="col">Statut</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>2026-02-16</td>
                                            <td><strong>Antananarivo</strong></td>
                                            <td>Eau potable</td>
                                            <td>500 litres</td>
                                            <td><span class="text-danger">0 litres</span></td>
                                            <td><span class="badge bg-danger">Critique</span></td>
                                            <td><span class="badge bg-warning text-dark">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>2026-02-15</td>
                                            <td><strong>Toliara</strong></td>
                                            <td>Tôles ondulées</td>
                                            <td>150 unités</td>
                                            <td><span class="text-warning">75 unités</span></td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                            <td><span class="badge bg-info">Partiel</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>2026-02-15</td>
                                            <td><strong>Mahajanga</strong></td>
                                            <td>Riz</td>
                                            <td>300 kg</td>
                                            <td><span class="text-danger">0 kg</span></td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                            <td><span class="badge bg-warning text-dark">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>2026-02-14</td>
                                            <td><strong>Fianarantsoa</strong></td>
                                            <td>Médicaments</td>
                                            <td>50 kits</td>
                                            <td><span class="text-success">50 kits</span></td>
                                            <td><span class="badge bg-secondary">Normal</span></td>
                                            <td><span class="badge bg-success">Satisfait</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>2026-02-14</td>
                                            <td><strong>Sambava</strong></td>
                                            <td>Couvertures</td>
                                            <td>200 unités</td>
                                            <td><span class="text-danger">0 unités</span></td>
                                            <td><span class="badge bg-danger">Critique</span></td>
                                            <td><span class="badge bg-warning text-dark">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">6</th>
                                            <td>2026-02-14</td>
                                            <td><strong>Toamasina</strong></td>
                                            <td>Tentes</td>
                                            <td>100 unités</td>
                                            <td><span class="text-success">100 unités</span></td>
                                            <td><span class="badge bg-secondary">Normal</span></td>
                                            <td><span class="badge bg-success">Satisfait</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">7</th>
                                            <td>2026-02-13</td>
                                            <td><strong>Antsiranana</strong></td>
                                            <td>Vêtements</td>
                                            <td>250 lots</td>
                                            <td><span class="text-warning">150 lots</span></td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                            <td><span class="badge bg-info">Partiel</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">8</th>
                                            <td>2026-02-13</td>
                                            <td><strong>Antsirabe</strong></td>
                                            <td>Kits d'hygiène</td>
                                            <td>180 unités</td>
                                            <td><span class="text-danger">0 unités</span></td>
                                            <td><span class="badge bg-danger">Critique</span></td>
                                            <td><span class="badge bg-warning text-dark">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">9</th>
                                            <td>2026-02-12</td>
                                            <td><strong>Morondava</strong></td>
                                            <td>Huile</td>
                                            <td>200 litres</td>
                                            <td><span class="text-warning">120 litres</span></td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                            <td><span class="badge bg-info">Partiel</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">10</th>
                                            <td>2026-02-12</td>
                                            <td><strong>Ambositra</strong></td>
                                            <td>Ciment</td>
                                            <td>150 sacs</td>
                                            <td><span class="text-success">150 sacs</span></td>
                                            <td><span class="badge bg-secondary">Normal</span></td>
                                            <td><span class="badge bg-success">Satisfait</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">11</th>
                                            <td>2026-02-11</td>
                                            <td><strong>Manakara</strong></td>
                                            <td>Eau potable</td>
                                            <td>400 litres</td>
                                            <td><span class="text-warning">200 litres</span></td>
                                            <td><span class="badge bg-danger">Critique</span></td>
                                            <td><span class="badge bg-info">Partiel</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">12</th>
                                            <td>2026-02-11</td>
                                            <td><strong>Taolagnaro</strong></td>
                                            <td>Lampes torches</td>
                                            <td>80 unités</td>
                                            <td><span class="text-success">80 unités</span></td>
                                            <td><span class="badge bg-secondary">Normal</span></td>
                                            <td><span class="badge bg-success">Satisfait</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">13</th>
                                            <td>2026-02-10</td>
                                            <td><strong>Antananarivo</strong></td>
                                            <td>Nourriture</td>
                                            <td>600 kg</td>
                                            <td><span class="text-warning">350 kg</span></td>
                                            <td><span class="badge bg-warning text-dark">Urgent</span></td>
                                            <td><span class="badge bg-info">Partiel</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">14</th>
                                            <td>2026-02-10</td>
                                            <td><strong>Mahajanga</strong></td>
                                            <td>Jerrycans</td>
                                            <td>120 unités</td>
                                            <td><span class="text-danger">0 unités</span></td>
                                            <td><span class="badge bg-danger">Critique</span></td>
                                            <td><span class="badge bg-warning text-dark">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">15</th>
                                            <td>2026-02-09</td>
                                            <td><strong>Toliara</strong></td>
                                            <td>Bois de construction</td>
                                            <td>50 m³</td>
                                            <td><span class="text-success">50 m³</span></td>
                                            <td><span class="badge bg-secondary">Normal</span></td>
                                            <td><span class="badge bg-success">Satisfait</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-success">Modifier</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <nav aria-label="Navigation de la liste">
                                <ul class="pagination justify-content-center mt-3">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Précédent</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Suivant</a>
                                    </li>
                                </ul>
                            </nav>
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
