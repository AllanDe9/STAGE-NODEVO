<?php
$user = $_SESSION['user'];
if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit();
}
use App\Controllers\User;
use App\Controllers\Modele;
use App\Controllers\Marque;
use App\Controllers\Catalogue;
use App\Controllers\dataController;
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
            <h3>Bonjour <?php echo $user['prenom']; ?> <?php echo $user['nom']; ?></h3>
            <a href="/deconnexion">- Deconnexion -</a>
        </div>
        <a href="/administrateur/modeles">Gestion Modeles</a>
        <a href="/administrateur/marques">Gestion Marques</a>
        <a href="/administrateur/users">Gestion Administrateur</a>
    </div>
    <?php 
    if (isset($_GET['select'])) {
        if ($_GET['select'] == "marques") {
            $dataController->afficherAdminMarque($get);
        }
        if ($_GET['select'] == "modeles") {
            $dataController->afficherTousModeles($get);
            $dataController->supprimer();
        }
        if ($_GET['select'] == "users") {
            ?>
            <div class="admin-users">
                <div class="liste-users">
                    <?php
                    $dataController->afficherUtilisateur();
                    ?>
                </div>
                <div class="ajouter-users">
                <?php
                    $error = $dataController->saveUtilisateur();
                    $dataController->supprimerUser();
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
                        <p>
                            <?php 
                            echo $error
                            ?>
                        </p>
                </div>
            </div>
            <?php
        }
    }
        ?>
    </main>
</body>
</html>