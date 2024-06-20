<?php
session_start();
require_once dirname(__DIR__) . '/autoload.php';

use App\Controllers\ControllerPage;
use App\Controllers\dataController;

$dataController = new dataController();
$Controller = new ControllerPage(dirname(__DIR__));

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', trim($request_uri, '/'));

switch ($uri_segments[0]) {
    case '':
        $content = include dirname(__DIR__) . '/views/accueil.php';
        break;
    case 'modeles':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $content = $Controller->afficherTousLesModelesPage($id, $dataController);
        } else {
            $content = $Controller->afficherTousLesModeles($dataController);
        }
        break;
    case 'recherche':
        $content = $Controller->afficherTousLesModeles($dataController);
        break;
    case 'ajouter':
        $content = $Controller->afficherAjouter($dataController);
        break;
    case 'modifier':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $content = $Controller->afficherModifier($id, $dataController);
        } else {
            $content = $Controller->afficherAjouter($dataController);
        }
        break;
        case 'detail':
            if (isset($uri_segments[1])) {
                $id = $uri_segments[1];
                $content = $Controller->afficherModele($id, $dataController);
            }
            break;
        case 'marque':
            if (isset($uri_segments[1])) {
                $id = $uri_segments[1];
                $content = $Controller->afficherMarque($id, $dataController);
            }
            break;
        case 'connexion':
            $content = include dirname(__DIR__) . '/requetes/connexion.php';
            break;
        case 'deconnexion':
            $content = include dirname(__DIR__) . '/requetes/deconnexion.php';
            break;
        case 'administrateur':
            if (isset($uri_segments[1])) {
                $select = $uri_segments[1];
                if (isset($uri_segments[2])) {
                    $id = $uri_segments[2];
                    $content = $Controller->afficherAdminDouble($select, $id, $dataController);
                } else {
                    $content = $Controller->afficherAdmin($select, $dataController);
                }  
            }  else {
                $content = $Controller->afficherMenuAdmin();
            }
        break;
    default:
        $content = "Page non trouvée";
        break;
}
echo $content;
