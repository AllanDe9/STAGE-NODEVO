<?php

namespace App\Controllers;

use App\Controllers\User;
use App\Controllers\View;

class dataController {
    public function afficherUtilisateur() {
        $utilisateurs = User::getUtilisateurs();
        $userView = new View();
        $userView->displayUsers($utilisateurs);
    }

    public function saveUtilisateur() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $existingUser = User::findEmail($email);
            if ($existingUser) {
                $error = 'Un utilisateur avec cet email existe déjà';
            } else {
                $newUser = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ];
                User::saveUser($newUser);
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            }
           
        } 
        return $error;
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = User::findEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header('Location: /administrateur');
                exit();
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        }
        return $error;
    }

    public function supprimerUser() {
        if (isset($_GET['delete'])) {
            $emailToDelete = $_GET['delete'];
            User::deleteUser($emailToDelete);
            header('Location: /administrateur/users');
            exit();
        }
    }
}

