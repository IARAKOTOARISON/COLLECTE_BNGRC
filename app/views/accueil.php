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
                <div class="container">
                    <h1 class="fw-bold mb-4 ">Bienvenue sur le site de collecte de BNGRC</h1>                    
                </div>
            </main>
        </div>
    </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
    
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>




</html>