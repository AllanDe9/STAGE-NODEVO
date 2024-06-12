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