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
        <a href="/ajouter"></a><div class="bouton-ajouter"><p>Ajouter un modèle +</p></div></a>
        <div class="onglet-recherche">
            <form method="POST">
                <p>Rechercher un modèle</p>
                <label for="marque">Marque :</label>
                <select id="marque" name="marque">
                    <option>Toutes</option>
                    <?php
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
    <div class="liste-modeles">
    <?php  
    $modelsParPage = 6;
    $totalModels = 0;

    function filter_modeles($modeles, $modele_recherche, $annee_recherche) {
        return array_filter($modeles, function($modele) use ($modele_recherche, $annee_recherche) {
            $matches_modele = $modele_recherche === '' || stripos($modele['nom_modele'], $modele_recherche) !== false;
            $matches_annee = $annee_recherche === '' || ($modele['annee_debut'] == $annee_recherche);
            return $matches_modele && $matches_annee;
        });
    }
    function build_query_string($params) {
        return http_build_query($params);
    }
    $search_params = [
        'marque' => isset($_POST['marque']) ? $_POST['marque'] : (isset($_GET['marque']) ? $_GET['marque'] : 'Toutes'),
        'modele' => isset($_POST['modele']) ? $_POST['modele'] : (isset($_GET['modele']) ? $_GET['modele'] : ''),
        'annee' => isset($_POST['annee']) ? $_POST['annee'] : (isset($_GET['annee']) ? $_GET['annee'] : '')
    ];
           
        $marque_recherche = isset($_POST['marque']) ? $_POST['marque'] : 'Toutes';
        $modele_recherche = isset($_POST['modele']) ? $_POST['modele'] : '';
        $annee_recherche = isset($_POST['annee']) ? $_POST['annee'] : '';
    
    
        
        foreach ($vehicules['marques'] as $marque) {
            if ($marque_recherche === 'Toutes' || $marque['num_marque'] == $marque_recherche) {
                $modeles_filtrés = filter_modeles($marque['modeles'], $modele_recherche, $annee_recherche);
            if (!empty($modeles_filtrés)) {
                $resultats[] = [
                        'num_marque' => $marque['num_marque'],
                        'nom_marque' => $marque['nom_marque'],
                        'modeles' => $modeles_filtrés
                ];
                $totalModels += count($modeles_filtrés);
            }
            }
        }
        
    
        $totalPages = ceil($totalModels / $modelsParPage);

    
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $currentPage = max(1, min($currentPage, $totalPages)); 
        $start = ($currentPage - 1) * $modelsParPage;
        $end = $start + $modelsParPage;

        echo '<div class="row">';
        $modelsDisplayed = 0;
        $count = 0;
        if (!empty($resultats)) {
        foreach ($resultats as $marque) {
            foreach ($marque['modeles'] as $modele) {
                if ($modelsDisplayed >= $start && $modelsDisplayed < $end) {
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

                    echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>'.' - '.'<a href="/detail/'.$modele['num_modele'].'">Voir plus</a>'.' - '.'<a href="/marque/'.$marque['num_marque'].'">Voir la marque</a></p></div>';

                    echo '</div>';
                    $count++;
                }
                $modelsDisplayed++;
            }
        }
        echo '</div>';
        } else {
            echo "<p>Aucun modèle trouvé pour les critères de recherche donnés.</p>";
        }
   
    echo '<div class="pagination">';
    $queryString = build_query_string($search_params);
   
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
