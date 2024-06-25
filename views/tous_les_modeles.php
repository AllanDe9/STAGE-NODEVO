<?php
use App\Controllers\Modele;
use App\Controllers\Marque;
use App\Controllers\Catalogue;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Cars</title>
    <link rel="stylesheet"  href="../style.css">
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
                    <li>
                        <a id="bouton" class="bouton" tabindex="0">Marques</a>
                        <div class="menu" id="menu">
                          <?php 
                            $dataController->afficherMarques();
                          ?>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
    <div class="recherche">
        <a href="/ajouter"><div class="bouton-ajouter"><p>Ajouter un modèle +</p></div></a>
        <div class="onglet-recherche">
            <form method="POST">
                <p>Rechercher un modèle</p>
                <label for="marque">Marque :</label>
                <select id="marque" name="marque">
                    <option>Toutes</option>
                    <?php
                        $vehicules = Catalogue::getVoitures();
                        foreach ($vehicules['marques'] as $marque) {
                            echo '<option value="'.$marque['num_marque'].'">'.$marque['nom_marque'].'</option>';
                        }
                        usort($vehicules['marques'], function($a, $b) {
                            return strcmp($a['nom_marque'], $b['nom_marque']);
                        });
                        foreach ($vehicules['marques'] as $marque) {
                            echo '<option value="'.$marque['num_marque'].'">'.$marque['nom_marque'].'</option>';
                        }
                    ?>              
                </select>
                <label for="modele">Nom du modèle</label>
                <input type="text" id="modele" name="modele">
                <label for="annee">Année</label>
                <input type="text" id="annee" name="annee">
                <input type="submit" value="Rechercher" class="bouton-recherche">
            </form>
        </div>
    </div>
    <?php  
    $dataController->afficherTousModelesRecherche();;
    ?>
    </main>
    <footer>
       <?php include "footer.php" ?>
    </footer>
</body>
</html>
