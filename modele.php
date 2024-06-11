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
                    <li><a href="/recherche">Rechercher</a></li>
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
            echo '<a href="modele.php?modele='.$modele['num_modele'].'">';
            if (empty($modele['url_photo'])) {
                echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . $modele['nom_modele'] . '">';
            }
            else {
                echo '<img src="' . $modele['url_photo'].'" alt="' . $modele['nom_modele'] . '">';
            }
            echo '</a><h2>' . $marque['nom_marque'] . '</h2>';
            echo '<h2>' . $modele['nom_modele'] . '</h2>';
            echo '<p>' . $modele['annee_debut'] . '</p>';
            echo '<p>' . $modele['annee_fin'] . '</p>';
            echo '<p>' . $modele['puissance_max'] . '</p>';
            echo '<p>' . $modele['puissance_min'] . '</p>';
            echo '<p>' . $modele['prix_actuel'] . '</p>';
            echo '<p>' . $modele['prix_neuf'] . '</p>';
            echo '<p>' . $modele['nbr_produit'] . '</p>';
            echo '<p>' . $modele['description'] . '</p>';
            echo '<a href="/modifier/'.$modele['num_modele'].'">Modifier</a>';
            echo '</div>';
        }else{
            header("Location: /");
            exit();
        }
        ?>
    
    </main>
    <footer>
        <div id="bouton-admin" class="bouton-admin" tabindex="0">
            <img src="../image/icone-admin.svg">
        </div>
        <div id="admin" class="admin">
            <h2>Administration</h2>
            <?php
            session_start();
            if (isset($_SESSION['user'])) {
                $user = $_SESSION['user'];
                echo 'Bonjour '.$user['prenom'].' '.$user['nom'];
                echo '<a href="/administrateur">Page Admin</a>';
                echo '<a href="/deconnexion">Deconnexion</a>';
            }else{
                echo '<a href="/connexion">Connexion</a>';
            }
            ?>
        </div>
    </footer>
</body>
</html>