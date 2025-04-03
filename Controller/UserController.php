<?php
require_once '../Model/User.php';
session_start();
class UserController
{
    public function handleRequest()
    {
        if (isset($_POST['load'])) {
            $this->registerUser();
        }
    }

    private function registerUser()
    {
        $username = $_POST['username'] ?? '';
        $birthday = $_POST['birthday'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_conf = $_POST['password_conf'] ?? '';
        $telephone = $_POST['telephone'] ?? '';

        $validationResult = $this->validateInput($username, $birthday, $email, $password, $password_conf, $telephone);

        if ($validationResult->error) {
            echo json_encode($validationResult);
            return;
        }

        $user = new User($username, $birthday, $email, $password, $telephone);
        if ($user->save() == 'Erreur de connexion à la base de données.') {
            echo json_encode($validationResult);
        } else {
            $validationResult->error = true;
            $validationResult->type = 'database';
            echo json_encode($validationResult);
        }
    }

    private function validateInput($username, $birthday, $email, $password, $password_conf, $telephone)
    {
        $response = new stdClass();

        if (User::isEmpty($username, $birthday, $email, $password, $password_conf, $telephone)) {
            $response->error = true;
            $response->type = 'empty';
        } else if (User::emailExists($email)) {
            $response->error = true;
            $response->type = 'email';
        } else if (User::usernameExists($username)) {
            $response->error = true;
            $response->type = 'username';
        } else if (!User::passwordCheck($password, $password_conf)) {
            $response->error = true;
            $response->type = 'password';
        } else {
            $response->error = false;
            $response->type = 'success';
        }
        return $response; 
    }
}

$controller = new UserController();
$controller->handleRequest();