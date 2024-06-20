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

    public function setPassword($password) {
        $this->password = $password;
    }

    public static function saveUsers($users) {
        $usersJson = json_encode($users, JSON_PRETTY_PRINT);
        file_put_contents('../requetes/users.json', $usersJson);
    }

    public static function saveUser($user) {
        $users = self::getUtilisateurs();
        $users[] = $user;
        self::saveUsers($users);
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

    public static function deleteUser($email) {
        $users = self::getUtilisateurs();
        $updatedUsers = [];
        foreach ($users as $user) {
            if ($user['email'] !== $email) {
                $updatedUsers[] = $user;
            }
        }
        self::saveUsers($updatedUsers);
    }
}

