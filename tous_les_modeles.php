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
    <div class="ajouter"><a href="/ajouter">Ajouter une voiture</a></div>
    <div class="liste">
        <?php
        $count = 0;
        echo '<div class="row">'; 
        foreach ($vehicules['marques'] as $marque) {
            foreach ($marque['modeles'] as $modele) {
                if ($count > 0 && $count % 3 == 0) {
                    echo '</div><div class="row">'; 
                }
                echo '<div class="modele">';
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
                echo '<a href="/modifier/'.$modele['num_modele'].'">Modifier</a>';

                echo '</div>';
                $count++;
            }
        }
        echo '</div>'; 
        ?>
    </div>
    </main>
    <footer>
        <div id="bouton-admin" class="bouton-admin" tabindex="0">
            <img src="image/icone-admin.svg">
        </div>
        <div id="admin" class="admin">
            <h2>Administration</h2>
            <a href="/connexion">Connexion</a>
            
            <a href="/administrateur">Page Admin</a>
            <a href="/deconnexion">Deconnexion</a>
        </div>
    </footer>
</body>
</html>
