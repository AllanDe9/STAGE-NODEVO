<?php
require 'fonctions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = findEmail($email);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: /administrateur');
        exit();
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet"  href="../style/style.css">
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
