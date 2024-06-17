<?php

namespace App\Controllers;

class MarqueController {
    public function __construct() {
   
    }

    public function afficherMarque($id) {
   
        $_GET["marque"] = $id;
        require __DIR__ . '/../../views/marque.php';
    }
}
?>
