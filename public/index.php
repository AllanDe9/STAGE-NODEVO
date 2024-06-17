<?php
session_start();
require_once __DIR__ . '/../autoload.php';

use App\Controllers\Controller;

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', trim($request_uri, '/'));

switch ($uri_segments[0]) {
    case '':
        require __DIR__ . '../../views/accueil.php';
        break;
    case 'modeles':
        $Controller = new Controller();
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $Controller->afficherTousLesModelesPage($id);
        } else {
            $Controller->afficherTousLesModeles();
        }
        break;
    case 'recherche':
        $Controller = new Controller();
        $Controller->afficherTousLesModeles();
        break;
    case 'ajouter':
        $Controller = new Controller();
        $Controller->afficherAjouter();
        break;
    case 'modifier':
        $Controller = new Controller();
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $Controller->afficherModifier($id);
        } else {
            $Controller->afficherAjouter();
        }
        break;
    case 'detail':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $Controller = new Controller();
            $Controller->afficherModele($id);
        }
        break;
    case 'marque':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $Controller = new Controller();
            $Controller->afficherMarque($id);
        }
        break;
    case 'connexion':
        require __DIR__ . '/../requetes/connexion.php';
        break;
    case 'deconnexion':
        require __DIR__ . '/../requetes/deconnexion.php';
        break;
    case 'administrateur':
        $Controller = new Controller();
        if (isset($uri_segments[1])) {
            $select = $uri_segments[1];
            if (isset($uri_segments[2])) {
                $id = $uri_segments[2];
                $Controller->afficherAdminDouble($select, $id);
            } else {
                $Controller->afficherAdmin($select);
            }    
        } else {
            $Controller->afficherMenuAdmin();
        }
        break;
    default:
        echo "Page non trouvée";
        break;
}

