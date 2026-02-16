<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost:3306;dbname=db_s2_ETU004231;charset=utf8mb4',
        'ETU004231',
        '95rwEbSe'
    );
    echo "Connexion à la base réussie !<br>";
    var_dump($pdo);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
