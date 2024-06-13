<?php
$user = $_SESSION['user'];
if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit();
}
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
                            echo '<li>'.$marque['nom_marque'].'</li>';
                        }
                          ?>
                    </ul>
                </div>
                <div class="ajouter-marque">
                    <?php
                    $marques = $vehicules['marques'];
                    $max_num_marque = max(array_column($marques, 'num_marque'));
                    $next_num_marque = $max_num_marque + 1;

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $nom_marque = $_POST['nom_marque'];
                        $num_marque = $_POST['num_marque'];

                        $vehicules['marques'][] = [
                            'num_marque' => $num_marque,
                            'nom_marque' => $nom_marque,
                            'modeles' => []
                        ];

                        file_put_contents('data.json', json_encode($vehicules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                        echo "Marque ajoutée avec succès !";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                    }
                    ?>
                    <h2>Ajouter une nouvelle marque</h2>
                    <form method="post">
                        <label for="nom_marque">Nom de la marque:</label>
                        <input type="text" id="nom_marque" name="nom_marque" required>
                        <input type="hidden" name="num_marque" value="<?php echo $next_num_marque; ?>">
                        <button type="submit">Ajouter</button>
                    </form>
                </div>
            </div>
            <?php
        }
        if ($_GET['select'] == "modeles") {
            ?>
            <div class="admin-modeles">
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
                        echo '<a href="/supprimer/'.$modele['num_modele'].'">Supprimer</a>';

                        echo '</div>';
                        $count++;
                    }
                }
        echo '</div>'; 
        ?>
    </div>
    </div>

            <?php
        }
        if ($_GET['select'] == "users") {
            ?>
            <div class="admin-users">
                <div class="liste-users">
                    <?php
                    $jsonData = file_get_contents('users.json');
                    $listeusers = json_decode($jsonData, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        echo "<ul>";
                        foreach ($listeusers as $unuser) {
                            echo "<li>";
                            echo "Nom: " . htmlspecialchars($unuser['nom']) . "<br>";
                            echo "Prénom: " . htmlspecialchars($unuser['prenom']) . "<br>";
                            echo "Email: " . htmlspecialchars($unuser['email']) . "<br>";
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
                    require __DIR__ . '/requetes/fonctions.php';

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
                            <button type="submit">S'inscrire</button>
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