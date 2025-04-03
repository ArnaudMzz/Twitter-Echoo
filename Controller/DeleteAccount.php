<?php
session_start();
require_once '../Model/User.php';
require_once '../Model/Database.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../View/Login.php');
    exit();
}

$id_user = $_SESSION['user']['id_user'];

$user = new User();
if ($user->deleteAccount($id_user)) {
    session_destroy();
    header('Location: ../View/Login.php?account_deleted=1');
    exit();
} else {
    $_SESSION['errors'] = ["Une erreur s'est produite lors de la suppression du compte."];
    header('Location: ../View/Settings.php');
    exit();
}
?>