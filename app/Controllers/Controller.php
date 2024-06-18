<?php

namespace App\Controllers;

class Controller {
    public function __construct(private string $basePath) {

    }
    public function afficherMenuAdmin() {

        require $this->basePath. '/views/admin.php';
    }

    public function afficherAdminDouble($get, $select, $id) {

        if ($select == 'marques') {
            $get['marque'] = $id;
        } else {
            $get['page'] = $id;
        }
        $get['select'] = $select;
        require $this->basePath. '/views/admin.php';
    }
    public function afficherAdmin($select) {

        $_GET['select'] = $select;
        require $this->basePath. '/views/admin.php';
    }
    public function afficherAjouter() {

        require $this->basePath. '/views/formulaire.php';
    }
    public function afficherModifier($id) {

        $_GET['modele']= $id;
        require $this->basePath. '/views/formulaire.php';
    }
    public function afficherMarque($id) {

        $_GET["marque"] = $id;
        require $this->basePath. '/views/marque.php';
    }
    public function afficherModele($id) {

        $_GET['modele']= $id;
        require $this->basePath. '/views/modele.php';
    }
    public function afficherTousLesModeles() {

        require $this->basePath. '/views/tous_les_modeles.php';
    }
    public function afficherTousLesModelesPage($id) {

        $_GET['page'] = $id;
        require $this->basePath. '/views/tous_les_modeles.php';
    }
}