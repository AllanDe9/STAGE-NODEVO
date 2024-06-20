<?php
session_start();
require_once dirname(__DIR__) . '/autoload.php';

use App\Controllers\ControllerPage;
use App\Controllers\dataController;

$dataController = new dataController();
$Controller = new ControllerPage(dirname(__DIR__), $dataController);

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', trim($request_uri, '/'));

switch ($uri_segments[0]) {
    case '':
        $content = $Controller->afficherAccueil();
        break;
    case 'accueil':
        $content = $Controller->afficherAccueil();
        break;
    case 'modeles':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $content = $Controller->afficherTousLesModelesPage($id);
        } else {
            $content = $Controller->afficherTousLesModeles();
        }
        break;
    case 'recherche':
        $content = $Controller->afficherTousLesModeles();
        break;
    case 'ajouter':
        $content = $Controller->afficherAjouter();
        break;
    case 'modifier':
        if (isset($uri_segments[1])) {
            $id = $uri_segments[1];
            $content = $Controller->afficherModifier($id);
        } else {
            $content = $Controller->afficherAjouter();
        }
        break;
        case 'detail':
            if (isset($uri_segments[1])) {
                $id = $uri_segments[1];
                $content = $Controller->afficherModele($id);
            }
            break;
        case 'marque':
            if (isset($uri_segments[1])) {
                $id = $uri_segments[1];
                $content = $Controller->afficherMarque($id);
            }
            break;
        case 'connexion':
            $content = $Controller->afficherConnexion();
            break;
        case 'deconnexion':
            $content = $Controller->afficherDeconnexion();
            break;
        case 'administrateur':
            if (isset($uri_segments[1])) {
                $select = $uri_segments[1];
                if (isset($uri_segments[2])) {
                    $id = $uri_segments[2];
                    $content = $Controller->afficherAdminDouble($select, $id);
                } else {
                    $content = $Controller->afficherAdmin($select);
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
