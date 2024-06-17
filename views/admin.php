<?php
$user = $_SESSION['user'];
if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit();
}
$json_data = file_get_contents('../requetes/data.json');
$vehicules = json_decode($json_data, true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Cars</title>
    <link rel="stylesheet"  href="../../style.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="/administrateur">
                <h1>Vintage Cars - Administrateur</h1>
            </a>
            <nav>
                <ul>
                    <li><a href="/">Retour au site</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
    <div class=" accueil-admin">
    <div class="header-admin">
                <?php 
                $user = $_SESSION['user'];
                echo '<h3>Bonjour '.$user['prenom'].' '.$user['nom'].'</h3>';
                echo '<a href="/deconnexion">- Deconnexion -</a>';
                ?>
            </div>
            <a href="/administrateur/modeles">Gestion Modeles</a>
            <a href="/administrateur/marques">Gestion Marques</a>
            <a href="/administrateur/users">Gestion Administrateur</a>

        </div>
    <?php 
    if (isset($_GET['select'])) {
        if ($_GET['select'] == "marques") {
            ?>
            <div class="admin-marques">
                <div class="liste-marques">
                    <ul>
                    <?php 
                        foreach ($vehicules['marques'] as $marque) {
                            echo '<a href="/administrateur/marques/'.$marque['num_marque'].'"><li>'.$marque['nom_marque'].'</li></a>';
                        }
                          ?>
                    </ul>
                </div>
                <?php 
                    if (isset($_GET['marque'])) {

                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            
                        $num_marque = $_POST['num_marque'];
                        $nom_marque = $_POST['nom_marque'];
                        $url_logo_marque = $_POST['url_logo_marque'];
                        $descri_marque = $_POST['descri_marque'];
                        
                    
                        
                       
                        $marque_modifiee = null;
                        foreach ($vehicules['marques'] as &$marque) {
                            if ($marque['num_marque'] == $num_marque) {
                                $marque['nom_marque'] = $nom_marque;
                                $marque['url_logo_marque'] = $url_logo_marque;
                                $marque['descri_marque'] = $descri_marque;
                                $marque_modifiee = $marque;
                                break;
                            }
                        }
                        
                        
                        if (!$marque_modifiee) {
                            echo "<p>Erreur : Marque non trouvée.</p>";
                            exit;
                        }
                        
                        
                        $new_json = json_encode($vehicules, JSON_PRETTY_PRINT);
                        file_put_contents('../requetes/data.json', $new_json);
                        
                      
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit;
                    }

                        $num_marque = $_GET['marque'];
                        

                        $marque = null;
                        foreach ($vehicules['marques'] as $m) {
                            if ($m['num_marque'] == $num_marque) {
                                $marque = $m;
                                break;
                            }
                        }
                        
                        if (!$marque) {
                            echo "<p>Marque non trouvée.</p>";
                            exit;
                        }
                        ?>
                    <div class="ajouter-marque">
                        <a href="/administrateur/marques">< Retour</a>
                        <div>
                            <?php 
                            echo'<img src='.$marque['url_logo_marque'].'>';
                            echo '<h1>'.$marque['nom_marque'].'</h1>';
                            ?>
                        </div>
                        <form method="post">
                            <input type="hidden" name="num_marque" value="<?php echo $marque['num_marque']; ?>">

                            <label for="nom_marque">Nom de la marque :</label><br>
                            <input type="text" id="nom_marque" name="nom_marque" value="<?php echo $marque['nom_marque']; ?>" required><br><br>

                            <label for="url_logo_marque">URL du logo :</label><br>
                            <input type="text" id="url_logo_marque" name="url_logo_marque" value="<?php echo $marque['url_logo_marque']; ?>"><br><br>

                            <label for="descri_marque">Description :</label><br>
                            <textarea id="descri_marque" name="descri_marque" rows="4" required><?php echo $marque['descri_marque']; ?></textarea><br><br>

                          <input type="submit" id="submit" value="Enregistrer les modifications">
                        </form>
                    </div>
                <?php    
                } else {
                ?>
                <div class="ajouter-marque">

                    <?php

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $nom_marque = $_POST['nom_marque'];
                        $url_logo_marque = $_POST['url_logo_marque'];
                        $descri_marque = $_POST['descri_marque'];


                        $next_num_marque = 1;
                        foreach ($vehicules['marques'] as $marque) {
                            while($marque['num_marque'] == $next_num_marque){
                                $next_num_marque = $next_num_marque + 1;
                            }
                        }

                        $nouvelle_marque = [
                            'num_marque' => $next_num_marque,
                            'nom_marque' => $nom_marque,
                            'url_logo_marque' => $url_logo_marque,
                            'descri_marque' => $descri_marque,
                            'modeles' => [] 
                        ];

                        $vehicules['marques'][] = $nouvelle_marque;

                        $new_json_data = json_encode($vehicules, JSON_PRETTY_PRINT);
                        file_put_contents('../requetes/data.json', $new_json_data);

                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit;
                    }
                    ?>
                    <h2>Ajouter une nouvelle marque</h2>
                    <form method="post">
                        <label for="nom_marque">Nom de la marque :</label><br>
                        <input type="text" id="nom_marque" name="nom_marque" required><br><br>

                        <label for="url_logo_marque">URL du logo :</label><br>
                        <input type="text" id="url_logo_marque" name="url_logo_marque" required><br><br>

                        <label for="descri_marque">Description :</label><br>
                        <textarea id="descri_marque" name="descri_marque" rows="4" required></textarea><br><br>

                        <input type="submit" value="Ajouter">
                    </form>
                
                </div>
                <?php } ?>
            </div>
            <?php
        }
        if ($_GET['select'] == "modeles") {
            ?>
            <div class="admin-modeles">
            <div class="liste-modeles-admin">
                <?php
                 $modelsParPage = 9;
                 $totalModels = 0;

                 foreach ($vehicules['marques'] as $marque) {
                    $totalModels += count($marque['modeles']);
                }

                $totalPages = ceil($totalModels / $modelsParPage);
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                $currentPage = max(1, min($currentPage, $totalPages)); 
                $start = ($currentPage - 1) * $modelsParPage;
                $end = $start + $modelsParPage;

                $modelsDisplayed = 0;
                $count = 0;
                echo '<div class="row">'; 
                foreach ($vehicules['marques'] as $marque) {
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
        
                            echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>'.' - '.'<a href="/detail/'.$modele['num_modele'].'">Voir plus</a>'.' - '.'<a href="?delete='.$modele['num_modele'].'">Supprimer</a></p></div>';
        
                            echo '</div>';
                            $count++;
                        }
                        $modelsDisplayed++;
                        }
                    }
            echo '</div>'; 
            echo '<div class="pagination">';
            echo '<div class="bouton-pagination">';
        
            if ($currentPage > 1) {
                echo '<a href="/administrateur/modeles/' . ($currentPage - 1) . '"><div class="deplacement"><</div></a>';
            } else {
                echo '<div class="deplacement" id="gris"><</div>';
            }

        
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $currentPage) {
                    echo '<div class="num_page" id="page_select">' . $i . '</div>'; 
                } else {
                    echo '<a href="/administrateur/modeles/' . $i . '"><div class="num_page">' . $i . '</div></a>';
                }
            }

            
            if ($currentPage < $totalPages) {
                echo '<a href="/administrateur/modeles/' . ($currentPage + 1) . '"><div class="deplacement">></div></a>';
            } else {
                echo '<div class="deplacement" id="gris">></div>';
            }

            echo '</div>';
            echo '</div>';
                ?>
        </div>
        </div>
        <?php
           if (isset($_GET['delete'])) {
            $numModele = intval($_GET['delete']);
        
            foreach ($vehicules['marques'] as &$marque) {
                foreach ($marque['modeles'] as $index => $modele) {
                    if ($modele['num_modele'] == $numModele) {
                    
                        array_splice($marque['modeles'], $index, 1);
                        break;
                    }
                }
            }
            file_put_contents('../requetes/data.json', json_encode($vehicules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $urlWithoutQuery = strtok($_SERVER["REQUEST_URI"], '?');
            ?>
             <script>
                window.location.href = "<?php echo $urlWithoutQuery; ?>";
            </script>
            <?php
        }
        }
        if ($_GET['select'] == "users") {
            ?>
            <div class="admin-users">
                <div class="liste-users">
                    <?php
                    $jsonData = file_get_contents('../requetes/users.json');
                    $listeusers = json_decode($jsonData, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        echo "<ul>";
                        foreach ($listeusers as $unuser) {
                            echo "<li>";
                            echo htmlspecialchars($unuser['nom']).' '.htmlspecialchars($unuser['prenom']).' - ';
                            echo htmlspecialchars($unuser['email']);
                            echo "<a href='?delete=".$unuser['email']."'>Supprimer</a>";
                            echo "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "Erreur lors de la lecture des données JSON.";
                    }
                    ?>
                </div>
                <div class="ajouter-users">
                <?php
                    require __DIR__ . '/../requetes/fonctions.php';

                    $error = '';

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $nom = $_POST['nom'];
                        $prenom = $_POST['prenom'];
                        $email = $_POST['email'];
                        $password = $_POST['password'];

                        $existingUser = findEmail($email);

                        if ($existingUser) {
                            $error = 'Un utilisateur avec cet email existe déjà';
                        } else {
                            $newUser = [
                                'nom' => $nom,
                                'prenom' => $prenom,
                                'email' => $email,
                                'password' => password_hash($password, PASSWORD_DEFAULT)
                            ];
                            saveUser($newUser);
                            header("Location: " . $_SERVER['REQUEST_URI']);

                        }
                    }
                    ?>
                        <h2>Inscription</h2>
                        <form method="post">
                            <label for="nom">Nom :</label>
                            <input type="text" id="nom" name="nom" required>
                            <br>
                            <label for="prenom">Prénom :</label>
                            <input type="text" id="prenom" name="prenom" required>
                            <br>
                            <label for="email">Email :</label>
                            <input type="email" id="email" name="email" required>
                            <br>
                            <label for="password">Mot de passe :</label>
                            <input type="password" id="password" name="password" required>
                            <br>
                            <button type="submit" id="submit">S'inscrire</button>
                        </form>
                        <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            </div>
            <?php
        }
    }
        ?>
    </main>
</body>
</html>