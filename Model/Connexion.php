<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Database.php';
require_once 'User.php';

class Connexion
{
    private $email;
    private $password;

    public function __construct($email, $password)
    {
        $this->email = trim($email);
        $this->password = $password;
    }

    public function login()
    {
        $response = new stdClass();

        $user = User::getUserByEmail($this->email);

        if ($user && User::verifyPassword($this->password, $user['password'])) {
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'username' => $user['username'],
                'email' => $user['email'],
                'telephone' => $user['telephone'],
                'birthday' => $user['birthday'],
                'password' => $user['password'],
                'avatar_url' => $user['avatar_url'],
                'bio' => $user['bio'],
                'created_at' => $user['created_at']
            ];
            $response->error = false;
            $response->type = 'success';
            echo json_encode($response);
        } else {
            $response->error = true;
            $response->type = 'verify';
            echo json_encode($response);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $connexion = new Connexion($_POST['email'], $_POST['password']);
        $connexion->login();
    } else {
        $response = new stdClass();
        $response->error = true;
        $response->type = 'empty';
        echo json_encode($response);
    }
}