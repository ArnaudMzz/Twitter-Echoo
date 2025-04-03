<?php
session_start();
require_once '../Model/Tweet.php';
require_once '../Model/Database.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../View/Login.php');
    exit();
}

if (!isset($_SESSION['user']['id_user'])) {
    die("Erreur : ID utilisateur non défini.");
}

$id_user = $_SESSION['user']['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = htmlspecialchars($_POST['content']);

    $image_url = null;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = $target_file;
        }
    }

    $tweet = new Tweet($id_user, $content, $image_url);
    $tweet->save();
}

header('Location: ../View/Home.php');
exit();