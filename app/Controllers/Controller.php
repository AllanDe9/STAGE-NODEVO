<?php

namespace App\Controllers;

class Controller {
    public function __construct() {
        
    }
    public function afficherMenuAdmin() {
     
        require __DIR__ . '/../../views/admin.php';
    }

    public function afficherAdminDouble($select, $id) {
        
        if ($select == 'marques') {
            $_GET['marque'] = $id;
        } else {
            $_GET['page'] = $id;
        }
        $_GET['select'] = $select;
        require __DIR__ . '/../../views/admin.php';
    }
    public function afficherAdmin($select) {
    
        $_GET['select'] = $select;
        require __DIR__ . '/../../views/admin.php';
    }
    public function afficherAjouter() {
     
        require __DIR__ . '/../../views/formulaire.php';
    }
    public function afficherModifier($id) {
     
        $_GET['modele']= $id;
        require __DIR__ . '/../../views/formulaire.php';
    }
    public function afficherMarque($id) {
   
        $_GET["marque"] = $id;
        require __DIR__ . '/../../views/marque.php';
    }
    public function afficherModele($id) {
        
        $_GET['modele']= $id;
        require __DIR__ . '/../../views/modele.php';
    }
    public function afficherTousLesModeles() {
       
        require __DIR__ . '/../../views/tous_les_modeles.php';
    }
    public function afficherTousLesModelesPage($id) {
       
        $_GET['page'] = $id;
        require __DIR__ . '/../../views/tous_les_modeles.php';
    }
}
?>
