<?php

namespace App\Controllers;

class ModelesController {
    public function __construct() {
       
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

