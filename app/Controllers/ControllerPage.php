<?php

namespace App\Controllers;

class ControllerPage {
    public function __construct(private string $basePath) {

    }
    public function afficherMenuAdmin() {

        ob_start();
        include $this->basePath. '/views/admin.php';
        return ob_get_clean();
    }

    public function afficherAdminDouble($select, $id, $Controller) {

        if ($select == 'marques') {
            $get['marque'] = $id;
        } else {
            $get['page'] = $id;
        }
        $_GET['select'] = $select;
        ob_start();
        $dataController = $Controller;
        include $this->basePath. '/views/admin.php';
        return ob_get_clean();
    }
    public function afficherAdmin($select,$Controller) {

        $get['marque'] = null;
        $_GET['select'] = $select;
        ob_start();
        $dataController = $Controller;
        include $this->basePath. '/views/admin.php';
        return ob_get_clean();
    }
    public function afficherAjouter($Controller) {

        ob_start();
        $dataController = $Controller;
        include $this->basePath. '/views/formulaire.php';
        return ob_get_clean();
    }
    public function afficherModifier($id, $Controller) {

        $_GET['modele']= $id;
        ob_start();
        $dataController = $Controller;
        include $this->basePath. '/views/formulaire.php';
        return ob_get_clean();
    }
    public function afficherMarque($id, $Controller) {

        $_GET["marque"] = $id;
        ob_start();
        $dataController = $Controller;
        include $this->basePath. '/views/marque.php';
        return ob_get_clean();
    }
    public function afficherModele($id, $Controller) {

        $_GET['modele']= $id;
        ob_start();
        $dataController = $Controller;
        include $this->basePath. '/views/modele.php';
        return ob_get_clean();
    }
    public function afficherTousLesModeles($Controller) {

        ob_start();
        $dataController = $Controller;
        include $this->basePath. '/views/tous_les_modeles.php';
        return ob_get_clean();
    }
    public function afficherTousLesModelesPage($id, $Controller) {

        $_GET['page'] = $id;
        ob_start();
        $dataController = $Controller;
        include $this->basePath. '/views/tous_les_modeles.php';
        return ob_get_clean();
    }
}