<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../Model/User.php';
require_once '../Model/Database.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../View/Login.php');
    exit();
}


if (!isset($_SESSION['user']['id_user'])) {
    error_log("Erreur : id_user non défini dans la session.");
    header('Location: ../View/Login.php');
    exit();
}

$id_user = $_SESSION['user']['id_user'];
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$email = $_POST['email'] ?? '';
$telephone = $_POST['telephone'] ?? '';

$errors = [];

if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $errors[] = "Tous les champs du mot de passe doivent être remplis.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Les nouveaux mots de passe ne correspondent pas.";
    }
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'adresse e-mail n'est pas valide.";
}

if (empty($errors)) {
    $user = new User();

    if ($user->updateSecurity($id_user, $email, $telephone)) {
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['telephone'] = $telephone;

        if (!empty($current_password) && !empty($new_password)) {
            if ($user->updatePassword($id_user, $current_password, $new_password)) {
                header('Location: ../View/Settings.php?security_success=1&password_success=1');
                exit();
            } else {
                $errors[] = "Le mot de passe actuel est incorrect.";
            }
        } else {
            header('Location: ../View/Settings.php?security_success=1');
            exit();
        }
    } else {
        $errors[] = "Une erreur s'est produite lors de la mise à jour des informations de sécurité.";
    }
}

$_SESSION['errors'] = $errors;
header('Location: ../View/Settings.php');
exit();