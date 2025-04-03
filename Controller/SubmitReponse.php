<?php
session_start();
require_once '../Model/Response.php';
require_once '../Model/Database.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../View/Login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tweet = $_POST['id_tweet'];
    $id_user = $_SESSION['user']['id_user'];
    $content = $_POST['content'];

    $response = new Response($id_tweet, $id_user, $content);
    if ($response->save()) {
        echo '<div class="response-item">';
        echo '<p><strong>' . htmlspecialchars($_SESSION['user']['username']) . ':</strong> ' . htmlspecialchars($content) . '</p>';
        echo '<p class="response-date">' . date('Y-m-d H:i:s') . '</p>';
        echo '</div>';
    } else {
        echo 'Erreur lors de l\'enregistrement de la réponse.';
    }
}