<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>admin</title>
</head>
<body>
    Admin
</body>
</html>
