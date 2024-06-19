<?php

namespace App\Controllers;

class User {
    private $nom;
    private $prenom;
    private $email;
    private $password;

    public function __construct($nom, $prenom, $email, $password) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
    }

    public static function getUtilisateurs() {
        $jsonData = file_get_contents('../requetes/users.json');
        return json_decode($jsonData, true);
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function __toString() {
        return sprintf("Utilisateur(nom: %s, prenom: %s)", $this->nom, $this->prenom);
    }

    public static function afficherUtilisateur() {
        $utilisateurs = [];
        $tableauUsers = self::getUtilisateurs();
        foreach ($tableauUsers as $userData) {
            $utilisateur = new User(
                $userData['nom'],
                $userData['prenom'],
                $userData['email'],
                $userData['password']
            );
            $utilisateurs[] = $utilisateur;
        }
        echo "<ul>";
        foreach ($utilisateurs as $utilisateur) {
            echo "<li>";
            echo htmlspecialchars($utilisateur->getNom()) . ' ' . htmlspecialchars($utilisateur->getPrenom()) . ' - ';
            echo htmlspecialchars($utilisateur->getEmail());
            echo '<a href=/administrateur/users?delete='.$utilisateur->getEmail().'>Supprimer</a>';
            echo "</li>";
        }
        echo "</ul>";
    }
    public static function saveUser($user) {
        $users = self::getUtilisateurs();
        $users[] = $user;
        self::saveUsers($users);
    }
    
    
    public static function saveUsers($users) {
        $usersJson = json_encode($users, JSON_PRETTY_PRINT);
        file_put_contents('../requetes/users.json', $usersJson);
    }
    
    public static function findEmail($email) {
        $users = self::getUtilisateurs();
    
        foreach ($users as $user) {
            if ($user['email'] == $email) {
                return $user;
            }
        }
        return null;
    }
    public static function saveUtilisateur(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $existingUser = self::findEmail($email);

            if ($existingUser) {
                $error = 'Un utilisateur avec cet email existe déjà';
                $_SESSION['error'] = $error; 
            } else {
                $newUser = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ];
                self::saveUser($newUser);
                header("Location: " . $_SERVER['REQUEST_URI']);
                }
        }
    }

    public static function errorSave() {
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
        unset($_SESSION['error']);
        return $error; 
    }

    public static function Login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = self::findEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header('Location: /administrateur');
                exit();
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        }   return $error;
    }
    public static function Supprimer() {
        if (isset($_GET['delete'])) {
            $emailToDelete = $_GET['delete'];
            $users = self::getUtilisateurs();
            $updatedUsers = [];
           
            foreach ($users as $user) {
                if ($user['email'] !== $emailToDelete) {
                    $updatedUsers[] = $user;
                }
            }            
            self::saveUsers($updatedUsers);
            header('Location: /administrateur/users');
            exit();
        }
    }
}