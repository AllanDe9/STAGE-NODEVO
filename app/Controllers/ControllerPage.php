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

    public function afficherAdminDouble($select, $id) {

        if ($select == 'marques') {
            $get['marque'] = $id;
        } else {
            $get['page'] = $id;
        }
        $_GET['select'] = $select;
        ob_start();
        include $this->basePath. '/views/admin.php';
        return ob_get_clean();
    }
    public function afficherAdmin($select) {

        $get['marque'] = null;
        $_GET['select'] = $select;
        ob_start();
        include $this->basePath. '/views/admin.php';
        return ob_get_clean();
    }
    public function afficherAjouter() {

        ob_start();
        include $this->basePath. '/views/formulaire.php';
        return ob_get_clean();
    }
    public function afficherModifier($id) {

        $_GET['modele']= $id;
        ob_start();
        include $this->basePath. '/views/formulaire.php';
        return ob_get_clean();
    }
    public function afficherMarque($id) {

        $_GET["marque"] = $id;
        ob_start();
        include $this->basePath. '/views/marque.php';
        return ob_get_clean();
    }
    public function afficherModele($id) {

        $_GET['modele']= $id;
        ob_start();
        include $this->basePath. '/views/modele.php';
        return ob_get_clean();
    }
    public function afficherTousLesModeles() {

        ob_start();
        include $this->basePath. '/views/tous_les_modeles.php';
        return ob_get_clean();
    }
    public function afficherTousLesModelesPage($id) {

        $_GET['page'] = $id;
        ob_start();
        include $this->basePath. '/views/tous_les_modeles.php';
        return ob_get_clean();
    }
}