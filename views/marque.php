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
                            $vehicules = data();
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
                    echo "<div class='info-marque'>";
                    echo '<div><img src="' . $marque['url_logo_marque'] . '">';
                    echo "<h1>" . htmlspecialchars($marque['nom_marque']) . "</h1></div>";
                    echo "<p>" . htmlspecialchars($marque['descri_marque']) . "</p>";

                   echo '</div><div class="liste-modeles">';
        $count = 0;
        echo '<div class="row">'; 
       
            foreach ($marque['modeles'] as $modele) {
                if ($count > 0 && $count % 3 == 0) {
                    echo '</div><div class="row">'; 
                    }
                    echo '<div class="modele">';
                  
                    if (empty($modele['url_photo'])) {
                        echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . $modele['nom_modele'] . '">';
                    }
                    else {
                        echo '<img src="' . $modele['url_photo'].'" alt="' . $modele['nom_modele'] . '">';
                    }
                    echo '<div class="info-modele"><p>' . $marque['nom_marque'] .' - '.$modele['nom_modele'] .' - '.$modele['annee_debut'] . '</p></div>';

                    echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>'.' - '.'<a href="/detail/'.$modele['num_modele'].'">Voir plus</a></p></div>';

                    echo '</div>';
                    $count++;
            }
        
        echo '</div>'; 

                    
                } else {
                    echo "<p>Marque non trouvée</p>";
                }
            } else {
                echo "<p>Aucun numéro de marque fourni</p>";
            }
        ?>
    </main>
    <footer>
    <?php include "footer.php" ?>
    </footer>
</body>
</html>
