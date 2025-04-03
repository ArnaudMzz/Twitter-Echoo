<?php
session_start();
require_once '../Model/User.php';
require_once '../Model/Database.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../View/Login.php');
    exit();
}

$id_user = $_SESSION['user']['id_user'];
$username = $_POST['username'] ?? null;
$bio = $_POST['bio'] ?? null;
$avatar_url = $_SESSION['user']['avatar_url']; 

if (!empty($_FILES['avatar']['name'])) {
    $target_dir = "../uploads/avatars/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $file_name = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $file_name;

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($file_extension), $allowed_types)) {
        die("Erreur : Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
    }

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
        $avatar_url = $target_file;
    } else {
        die("Erreur : Impossible de téléverser le fichier.");
    }
}

$user = new User();
if ($user->updateProfile($id_user, $username, $bio, $avatar_url)) {
    $_SESSION['user']['username'] = $username;
    $_SESSION['user']['bio'] = $bio;
    $_SESSION['user']['avatar_url'] = $avatar_url;
    header('Location: ../View/Settings.php?profile_success=1');
} else {
    header('Location: ../View/Settings.php?error=1');
}