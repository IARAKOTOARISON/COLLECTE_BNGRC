<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des villes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e6f2ff;
        }
    </style>
</head>
<body>

<h1>📊 Liste des Villes</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom de la ville</th>
            <th>ID Région</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($listeVille)) : ?>
            <?php foreach ($listeVille as $ville) : ?>
                <tr>
                    <td><?= htmlspecialchars($ville['id']) ?></td>
                    <td><?= htmlspecialchars($ville['nom']) ?></td>
                    <td><?= htmlspecialchars($ville['idRegion']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="3">Aucune ville enregistrée</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
