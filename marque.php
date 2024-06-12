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
    <?php
            $num_marque = isset($_GET['marque']) ? (int) $_GET['marque'] : 0;

            if ($num_marque) {

                $marque = null;
                foreach ($vehicules['marques'] as $m) {
                    if ($m['num_marque'] == $num_marque) {
                        $marque = $m;
                        break;
                    }
                }

                if ($marque) {
                    echo "<div class='marque'>";
                    echo "<h1>" . htmlspecialchars($marque['nom_marque']) . "</h1>";

                   echo '<div class="liste">';
        $count = 0;
        echo '<div class="row">'; 
       
            foreach ($marque['modeles'] as $modele) {
                if ($count > 0 && $count % 3 == 0) {
                    echo '</div><div class="row">'; 
                    session_start();    }
                echo '<div class="modele">';
                echo '<a href="/detail/'.$modele['num_modele'].'">';
                if (empty($modele['url_photo'])) {
                    echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . $modele['nom_modele'] . '">';
                }
                else {
                    echo '<img src="' . $modele['url_photo'].'" alt="' . $modele['nom_modele'] . '">';
                }
                echo '</a><h2>' . $marque['nom_marque'] . '</h2>';
                echo '<h2>' . $modele['nom_modele'] . '</h2>';
                echo '<p>' . $modele['annee_debut'] . '</p>';
                echo '<a href="/modifier/'.$modele['num_modele'].'">Modifier</a>';

                echo '</div>';
                $count++;
            }
        
        echo '</div>'; 

                    echo "</div>";
                } else {
                    echo "<p>Marque non trouvée</p>";
                }
            } else {
                echo "<p>Aucun numéro de marque fourni</p>";
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
