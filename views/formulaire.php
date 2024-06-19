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
                    <li><a href="/modeles">Tous les modèles</a></li>
                    <li>
                        <a id="bouton" class="bouton" tabindex="0">Marques</a>
                        <div class="menu" id="menu">
                          <?php 
                           Catalogue::afficherMarques()
                          ?>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
    <?php
    if (isset($_GET['modele'])) {
        Modele::modifierModele();
    } else {
    Modele::ajouterModele();
    ?>
        <form method="post" class="form-vehicules">
        <div class=formulaire>
        <label for="num_marque">Marque :</label>
        <select name="num_marque" id="num_marque" required>
            <?php
            $vehicules = Catalogue::getVoitures();
            foreach ($vehicules['marques'] as $marque) {
                echo "<option value='" . $marque['num_marque'] . "'>" . $marque['nom_marque'] . "</option>";
            }
            ?>
        </select><br>

        <label for="nom_modele">Nom du Modèle :</label>
        <input type="text" id="nom_modele" name="nom_modele" required><br>

        <label for="annee_debut">Année de Début :</label>
        <input type="text" id="annee_debut" name="annee_debut" required><br>

        <label for="annee_fin">Année de Fin :</label>
        <input type="text" id="annee_fin" name="annee_fin" required><br>

        <label for="nbr_produit">Nombre Produit :</label>
        <input type="text" id="nbr_produit" name="nbr_produit" required><br>

        <input type="submit" id="submit" value="Ajouter le Modèle">

        </div><div class=formulaire>

        <div><label for="puissance_max">Puissance Max :</label>
        <input type="text" id="puissance" name="puissance_max"><br>

        <label for="puissance_min">Puissance Min :</label>
        <input type="text" id="puissance" name="puissance_min"><br></div>

        <div><label for="prix_neuf">Prix Neuf :</label>
        <input type="text" id="prix_neuf" name="prix_neuf"><br>

        <label for="prix_actuel">Prix Actuel :</label>
        <input type="text" id="prix_actuel" name="prix_actuel"><br></div>

        <label for="url_photo">URL Photo :</label>
        <input type="text" id="url_photo" name="url_photo"><br>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea><br>

        
    </div>
    </form>
    <?php
    }
    ?>
    </main>
    <footer>
    <?php include "footer.php" ?>
    </footer>
</body>
</html>
