<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <title>Liste des Dons - BNGRC</title>
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
                        <h1 class="fw-bold">Liste des Dons Reçus</h1>
                        <a href="/dons/formulaire" class="btn btn-success">
                            Enregistrer un nouveau don
                        </a>
                    </div>

                    <!-- Filtres -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="filtreType" class="form-label fw-bold">Filtrer par type</label>
                                    <select class="form-select" id="filtreType">
                                        <option value="">Tous les types</option>
                                        <option value="nature">Don en nature</option>
                                        <option value="argent">Don en argent</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filtreVille" class="form-label fw-bold">Filtrer par ville</label>
                                    <select class="form-select" id="filtreVille">
                                        <option value="">Toutes les villes</option>
                                        <option value="Antananarivo">Antananarivo</option>
                                        <option value="Toamasina">Toamasina</option>
                                        <option value="Mahajanga">Mahajanga</option>
                                        <option value="Fianarantsoa">Fianarantsoa</option>
                                        <option value="Toliara">Toliara</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filtreDateDebut" class="form-label fw-bold">Date début</label>
                                    <input type="date" class="form-control" id="filtreDateDebut">
                                </div>
                                <div class="col-md-3">
                                    <label for="filtreDateFin" class="form-label fw-bold">Date fin</label>
                                    <input type="date" class="form-control" id="filtreDateFin">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Total Dons</h6>
                                    <h2 class="display-5">68</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Dons en Nature</h6>
                                    <h2 class="display-5">52</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Dons en Argent</h6>
                                    <h2 class="display-5">16</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Montant Total</h6>
                                    <h2 class="display-6">4.5M Ar</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des dons -->
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">Liste complète des dons reçus</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Donateur</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Quantité/Montant</th>
                                            <th scope="col">Ville Destinataire</th>
                                            <th scope="col">Statut</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>2026-02-16</td>
                                            <td><strong>Jean Rakoto</strong></td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Riz</td>
                                            <td>250 kg</td>
                                            <td>Antananarivo</td>
                                            <td><span class="badge bg-success">Distribué</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>2026-02-16</td>
                                            <td><strong>ONG Solidarité</strong></td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>500,000 Ar</td>
                                            <td>Non spécifié</td>
                                            <td><span class="badge bg-secondary">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>2026-02-15</td>
                                            <td><strong>Marie Rasoa</strong></td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Couvertures</td>
                                            <td>100 unités</td>
                                            <td>Toliara</td>
                                            <td><span class="badge bg-success">Distribué</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>2026-02-15</td>
                                            <td><strong>Entreprise ABC</strong></td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Eau potable</td>
                                            <td>1000 litres</td>
                                            <td>Mahajanga</td>
                                            <td><span class="badge bg-primary">En cours</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>2026-02-14</td>
                                            <td><strong>Paul Andria</strong></td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>200,000 Ar</td>
                                            <td>Fianarantsoa</td>
                                            <td><span class="badge bg-success">Distribué</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">6</th>
                                            <td>2026-02-14</td>
                                            <td><strong>Association CARE</strong></td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Médicaments</td>
                                            <td>50 kits</td>
                                            <td>Toamasina</td>
                                            <td><span class="badge bg-success">Distribué</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">7</th>
                                            <td>2026-02-13</td>
                                            <td><strong>Hanta Razafy</strong></td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Vêtements</td>
                                            <td>150 lots</td>
                                            <td>Antsiranana</td>
                                            <td><span class="badge bg-success">Distribué</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">8</th>
                                            <td>2026-02-13</td>
                                            <td><strong>Fondation Espoir</strong></td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>1,000,000 Ar</td>
                                            <td>Non spécifié</td>
                                            <td><span class="badge bg-secondary">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">9</th>
                                            <td>2026-02-12</td>
                                            <td><strong>Koto Rasolofo</strong></td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Tôles ondulées</td>
                                            <td>80 unités</td>
                                            <td>Morondava</td>
                                            <td><span class="badge bg-primary">En cours</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">10</th>
                                            <td>2026-02-12</td>
                                            <td><strong>Soa Randria</strong></td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>150,000 Ar</td>
                                            <td>Antsirabe</td>
                                            <td><span class="badge bg-success">Distribué</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">11</th>
                                            <td>2026-02-11</td>
                                            <td><strong>Croix Rouge</strong></td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Kits d'hygiène</td>
                                            <td>200 unités</td>
                                            <td>Ambositra</td>
                                            <td><span class="badge bg-success">Distribué</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">12</th>
                                            <td>2026-02-11</td>
                                            <td><strong>Rija Andriana</strong></td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Huile</td>
                                            <td>120 litres</td>
                                            <td>Manakara</td>
                                            <td><span class="badge bg-primary">En cours</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">13</th>
                                            <td>2026-02-10</td>
                                            <td><strong>Église Baptiste</strong></td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>750,000 Ar</td>
                                            <td>Sambava</td>
                                            <td><span class="badge bg-success">Distribué</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">14</th>
                                            <td>2026-02-10</td>
                                            <td><strong>Lala Rakoto</strong></td>
                                            <td><span class="badge bg-info">Nature</span></td>
                                            <td>Tentes</td>
                                            <td>30 unités</td>
                                            <td>Taolagnaro</td>
                                            <td><span class="badge bg-secondary">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">15</th>
                                            <td>2026-02-09</td>
                                            <td><strong>Groupe Ravinala</strong></td>
                                            <td><span class="badge bg-warning text-dark">Argent</span></td>
                                            <td>Don financier</td>
                                            <td>2,000,000 Ar</td>
                                            <td>Non spécifié</td>
                                            <td><span class="badge bg-secondary">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">Voir</button>
                                                <button class="btn btn-sm btn-warning">Modifier</button>
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
