<?php
session_start();
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', trim($request_uri, '/'));
switch ($uri_segments[0]) {
    case '':
        require __DIR__ . '/accueil.php';
        break;
    case 'modeles':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $_GET['page'] = $id;  
        }
        require __DIR__ . '/tous_les_modeles.php';
        break;
    case 'recherche':
        require __DIR__ . '/tous_les_modeles.php';
        break;
    case 'ajouter':
        require __DIR__ . '/formulaire.php';
        break;
    case 'modifier':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $_GET['modele'] = $id;
            require __DIR__ . '/formulaire.php';
        }
        break;
    case 'detail':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $_GET['modele'] = $id;
            require __DIR__ . '/modele.php';
        }
        break;
    case 'marque':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $_GET['marque'] = $id;
            require __DIR__ . '/marque.php';
        }
        break;
    case 'connexion':
        require __DIR__ . '/requetes/connexion.php';
        break;
    case 'deconnexion':
        require __DIR__ . '/requetes/deconnexion.php';
        break;
    case 'administrateur':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $_GET['select'] = $id;
            require __DIR__ . '/admin.php';
        }else {
            require __DIR__ . '/admin.php';
        }
        break;
    default:
        echo "Page non trouvée";
        break;
}

?>

