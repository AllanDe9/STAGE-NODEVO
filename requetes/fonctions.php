<?php
function getUsers() {
    $usersJson = file_get_contents('../users.json');
    return json_decode($usersJson, true);
}

function saveUsers($users) {
    $usersJson = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents('users.json', $usersJson);
}

function findEmail($email) {
    $users = getUsers();

    foreach ($users as $user) {
        if ($user['email'] == $email) {
            return $user;
        }
    }
    return null;
}
?>