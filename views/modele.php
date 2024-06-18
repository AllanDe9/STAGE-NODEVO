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
    <div class="detail-modele">
    <?php
        if (isset($_GET['modele'])) {
            $num_modele = (int)$_GET['modele'];
            $modeleTrouve = false;
            foreach ($vehicules['marques'] as $marqueIndex => $marque) {
                foreach ($marque['modeles'] as $modeleIndex => $modele) {
                    if ($modele['num_modele'] == $num_modele) {
                        $modeleTrouve = true;
                        break 2;
                    }
                }
            }
        
            if (!$modeleTrouve) {
                echo 'Modèle non trouvé.';
                echo'<a href="tous_les_modeles.php">Retour</a>';
                exit;
            }
           
            if (empty($modele['url_photo'])) {
                echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . $modele['nom_modele'] . '">';
            }
            else {
                echo '<img src="' . $modele['url_photo'].'" alt="' . $modele['nom_modele'] . '">';
            }
            echo '<div class="caracteristique-modele">';
            echo'<h1>A PROPOS DE CE MODÈLE :</h1></br>';
            echo '<h1>' . $marque['nom_marque'] .' - '.$modele['nom_modele']. '</h1>';
            if ($modele['annee_debut'] == $modele['annee_fin']) {
                echo '<p>' . $modele['annee_debut'] . '</p>';
            } else {
                echo '<p>' . $modele['annee_debut'] .' - '.$modele['annee_fin']. '</p>';
            }
            echo '<h3>Puissance DIN :</h3>';
            if ($modele['puissance_max'] == $modele['puissance_min']) {
                echo '<p>' . $modele['puissance_max'] . 'ch</p>';
            } else {
                echo '<p>' . $modele['puissance_max'] .' ch - '.$modele['puissance_min']. ' ch</p>';
            }
            echo '<h3>Cote du modèle :</h3>';
            echo '<p>' . $modele['prix_actuel'] . ' €</p>';
            echo '<h3>Prix initial :</h3>';
            echo '<p>' . $modele['prix_neuf'] . ' €</p>';
            echo '<h3>Production :</h3>';
            echo '<p>' . $modele['nbr_produit'] . ' véhicules produits</p>';
            echo '<strong>' . $modele['description'] . '</strong>';
            echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>  - <a href="/marque/'.$marque['num_marque'].'">Voir la marque</a></p></div>';
            echo '</div>';
            echo '</div>';
        }else{
            header("Location: /");
            exit();
        }
        ?>
    </div>
    </main>
    <footer>
    <?php include "footer.php" ?>
    </footer>
</body>
</html>