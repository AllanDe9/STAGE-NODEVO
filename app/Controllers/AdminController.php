<?php

namespace App\Controllers;

class AdminController {
    public function __construct() {
        
    }
    public function afficherMenuAdmin() {
     
        require __DIR__ . '/../../views/admin.php';
    }

    public function afficherAdminMarque($select, $id) {
     
        $_GET['marque'] = $id;
        $_GET['select'] = $select;
        require __DIR__ . '/../../views/admin.php';
    }
    public function afficherAdmin($select) {
    
        $_GET['select'] = $select;
        require __DIR__ . '/../../views/admin.php';
    }
}
?>
