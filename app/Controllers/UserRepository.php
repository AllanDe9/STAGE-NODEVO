<?php

namespace App\Controllers;

class UserRepository {
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
    public static function getUtilisateurs() {
        $jsonData = file_get_contents('../requetes/users.json');
        return json_decode($jsonData, true);
    }
}