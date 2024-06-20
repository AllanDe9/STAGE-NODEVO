<?php
use App\Controllers\dataController;
$error = $dataController->login();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet"  href="style.css">
</head>
<body>
    <div class="connexion">
    <h1>Connexion</h1>
    <form method="post">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <div>
            <button id="bouton-connexion" type="submit">Se connecter</button>
            <a href="/" id="bouton-connexion">Retour</a>
        </div>
    </form>
    <p><?php echo $error; ?></p>
</div>
</body>
</html>
