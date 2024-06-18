<?php
session_start();
require_once dirname(__DIR__) . '/autoload.php';

use App\Controllers\Controller;

$json_data = file_get_contents('../requetes/data.json');
$vehicules = json_decode($json_data, true);

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', trim($request_uri, '/'));
$Controller = new Controller(dirname(__DIR__));

switch ($uri_segments[0]) {
    case '':
        require dirname(__DIR__) . '/views/accueil.php';
        break;
    case 'modeles':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $Controller->afficherTousLesModelesPage($id);
        } else {
            $Controller->afficherTousLesModeles();
        }
        break;
    case 'recherche':
        $Controller->afficherTousLesModeles();
        break;
    case 'ajouter':
        $Controller->afficherAjouter();
        break;
    case 'modifier':
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
            $Controller->afficherModele($id);
        }
        break;
    case 'marque':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
                    $Controller->afficherMarque($id);
        }
        break;
    case 'connexion':
        require dirname(__DIR__) . '/requetes/connexion.php';
        break;
    case 'deconnexion':
        require dirname(__DIR__) . '/requetes/deconnexion.php';
        break;
    case 'administrateur':
        
        if (isset($uri_segments[1])) {
            $select = $uri_segments[1];
            if (isset($uri_segments[2])) {
                $id = $uri_segments[2];
                $Controller->afficherAdminDouble($_GET, $select, $id);
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
