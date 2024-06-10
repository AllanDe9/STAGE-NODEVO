<?php
$json_data = file_get_contents('data.json');
$vehicules = json_decode($json_data, true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Cars</title>
    <link rel="stylesheet"  href="../style/style.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="/">
                <h1>Vintage Cars</h1>
                <img src="../image/car.png">
            </a>
            <nav>
                <ul>
                    <li><a href="/modeles">Tous les modèles</a></li>
                    <li>
                        <a id="bouton" class="bouton" tabindex="0">Marques</a>
                        <div class="menu" id="menu">
                          <?php 
                            foreach ($vehicules['marques'] as $marque) {
                                    echo '<a href="/marque/'.$marque['num_marque'].'">'.$marque['nom_marque'].'</a>';
                            }
                          ?>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <p>Recherche de voitures</p>
        <form method="POST">
            <div class="form-group">
                <label for="marque">Marque :</label>
                <select id="marque" name="marque">
                    <option>Toutes</option>
                    <?php
                        foreach ($vehicules['marques'] as $marque) {
                            echo '<option value="'.$marque['num_marque'].'">'.$marque['nom_marque'].'</option>';
                        }                    
                    ?>              
                </select>
            </div>
            <div class="form-group">
                <label for="modele">Nom du modèle</label>
                <input type="text" id="modele" name="modele">
            </div>
            <div class="form-group">
                <label for="annee">Année</label>
                <input type="number" id="annee" name="annee">
            </div>
            <input type="submit" value="Rechercher">
        </form>
        <?php
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
           
            $marque_recherche = isset($_POST['marque']) ? $_POST['marque'] : 'Toutes';
            $modele_recherche = isset($_POST['modele']) ? $_POST['modele'] : '';
            $annee_recherche = isset($_POST['annee']) ? $_POST['annee'] : '';
            function filter_modeles($modeles, $modele_recherche, $annee_recherche) {
                return array_filter($modeles, function($modele) use ($modele_recherche, $annee_recherche) {
                    $matches_modele = $modele_recherche === '' || stripos($modele['nom_modele'], $modele_recherche) !== false;
                    $matches_annee = $annee_recherche === '' || ($modele['annee_debut'] == $annee_recherche);
                    return $matches_modele && $matches_annee;
                });
            }
        
            $resultats = [];
            foreach ($vehicules['marques'] as $marque) {
                if ($marque_recherche === 'Toutes' || $marque['num_marque'] == $marque_recherche) {
                    $modeles_filtrés = filter_modeles($marque['modeles'], $modele_recherche, $annee_recherche);
                if (!empty($modeles_filtrés)) {
                    $resultats[] = [
                        'nom_marque' => $marque['nom_marque'],
                        'modeles' => $modeles_filtrés
                    ];
                }
            }
        }
        if (!empty($resultats)) {
            foreach ($resultats as $marque) {
                foreach ($marque['modeles'] as $modele) {
                    echo "<div>";
                    echo "<h3>" . htmlspecialchars($marque['nom_marque']) . "</h3>";
                    echo "<h3>" . htmlspecialchars($modele['nom_modele']) . "</h3>";
                    echo "<p>" . htmlspecialchars($modele['annee_debut']) . "</p>";
                    echo "</div>";
                }
            }
        } else {
            echo "<p>Aucun modèle trouvé pour les critères de recherche donnés.</p>";
        }
    }
?>
    </main>
</body>
</html>
