<?php

namespace App\Controllers;

class ModeleController {
    public function __construct() {
       
    }

    public function afficherModele($id) {
        
        $_GET['modele']= $id;
        require __DIR__ . '/../../views/modele.php';
    }
}
?>
