<?php
session_start();
require_once '../Model/User.php';
require_once '../Model/Database.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../View/Login.php');
    exit();
}

$id_follower = $_SESSION['user']['id_user'];
$id_followed = $_POST['id_followed'] ?? null;

if (!$id_followed) {
    die("Erreur : Aucun utilisateur à suivre spécifié.");
}

if (User::follow($id_follower, $id_followed)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    die("Erreur : Impossible de suivre cet utilisateur.");
}