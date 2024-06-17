<?php

namespace App\Controllers;

class FormController {
    public function __construct() {
        
    }

    public function afficherAjouter() {
     
        require __DIR__ . '/../../views/formulaire.php';
    }
    public function afficherModifier($id) {
     
        $_GET['modele']= $id;
        require __DIR__ . '/../../views/formulaire.php';
    }
}
?>