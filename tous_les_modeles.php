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
    <div class="recherche">
        <div class="ajouter"><a href="/ajouter">Ajouter une voiture</a></div>
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
    </div>
    <div class="liste-modeles">
        <?php
       
        $modelsParPage = 6;

     
    $totalModels = 0;
    foreach ($vehicules['marques'] as $marque) {
        $totalModels += count($marque['modeles']);
    }
   
    $totalPages = ceil($totalModels / $modelsParPage);

  
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $currentPage = max(1, min($currentPage, $totalPages)); 
    $start = ($currentPage - 1) * $modelsParPage;
    $end = $start + $modelsParPage;

   
    echo '<div class="row">';
    $modelsDisplayed = 0;
    $count = 0;
    foreach ($vehicules['marques'] as $marque) {
        foreach ($marque['modeles'] as $modele) {
            if ($modelsDisplayed >= $start && $modelsDisplayed < $end) {
                if ($count > 0 && $count % 2 == 0) {
                    echo '</div><div class="row">'; 
                }
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
            $modelsDisplayed++;
        }
    }
    echo '</div>';

   
    echo '<div class="pagination">';
    
   
    if ($currentPage > 1) {
        echo '<a href="?page=' . ($currentPage - 1) . '">Page précédente</a>';
    }

   
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            echo '<strong>' . $i . '</strong>'; 
        } else {
            echo '<a href="?page=' . $i . '">' . $i . '</a>';
        }
    }

    
    if ($currentPage < $totalPages) {
        echo '<a href="?page=' . ($currentPage + 1) . '">Page suivante</a>';
    }

    echo '</div>';
    ?>
</div>

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
