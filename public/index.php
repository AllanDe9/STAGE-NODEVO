<?php
session_start();
require_once __DIR__ . '/../autoload.php';

use App\Controllers\ModeleController;
use App\Controllers\ModelesController;
use App\Controllers\MarqueController;
use App\Controllers\AdminController;
use App\Controllers\FormController;

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', trim($request_uri, '/'));

switch ($uri_segments[0]) {
    case '':
        require __DIR__ . '../../views/accueil.php';
        break;
    case 'modeles':
        $modelesController = new ModelesController();
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $modelesController->afficherTousLesModelesPage($id);
        } else {
            $modelesController->afficherTousLesModeles();
        }
        break;
    case 'recherche':
        $modelesController = new ModelesController();
        $modelesController->afficherTousLesModeles();
        break;
    case 'ajouter':
        $FormController = new FormController();
        $FormController->afficherAjouter();
        break;
    case 'modifier':
        $FormController = new FormController();
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $FormController->afficherModifier($id);
        } else {
            $FormController->afficherAjouter();
        }
        break;
    case 'detail':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $modeleController = new ModeleController();
            $modeleController->afficherModele($id);
        }
        break;
    case 'marque':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $marqueController = new MarqueController();
            $marqueController->afficherMarque($id);
        }
        break;
    case 'connexion':
        require __DIR__ . '/../requetes/connexion.php';
        break;
    case 'deconnexion':
        require __DIR__ . '/../requetes/deconnexion.php';
        break;
    case 'administrateur':
        $adminController = new AdminController();
        if (isset($uri_segments[1])) {
            $select = $uri_segments[1];
            if (isset($uri_segments[2])) {
                $id = $uri_segments[2];
                $adminController->afficherAdminMarque($select, $id);
            } else {
                $adminController->afficherAdmin($select);
            }    
        } else {
            $adminController->afficherMenuAdmin();
        }
        break;
    default:
        echo "Page non trouvée";
        break;
}
?>
