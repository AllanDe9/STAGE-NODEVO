<?php

namespace App\Controllers;

class View {
    public function displayUsers($utilisateurs) {
        echo "<ul>";
        foreach ($utilisateurs as $utilisateur) {
            echo "<li>";
            echo htmlspecialchars($utilisateur['nom']) . ' ' . htmlspecialchars($utilisateur['prenom']) . ' - ';
            echo htmlspecialchars($utilisateur['email']);
            echo '<a href=/administrateur/users?delete=' . htmlspecialchars($utilisateur['email']) . '>Supprimer</a>';
            echo "</li>";
        }
        echo "</ul>";
    }
}

