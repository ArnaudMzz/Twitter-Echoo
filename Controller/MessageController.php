<?php
session_start();
require_once '../Model/User.php';
require_once '../Model/Database.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../View/Login.php');
    exit();
}

$id_sender = $_SESSION['user']['id_user'];
$id_receiver = $_POST['id_receiver'] ?? null;
$content = $_POST['content'] ?? null;

if (!$id_receiver || !$content) {
    echo json_encode(['status' => 'error', 'message' => 'Destinataire ou contenu manquant.']);
    exit();
}

if (User::sendMessage($id_sender, $id_receiver, $content)) {
    // Récupérer le message nouvellement créé pour l'afficher dynamiquement
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT * FROM Message WHERE id_sender = :id_sender AND id_receiver = :id_receiver AND content = :content ORDER BY created_at DESC LIMIT 1");
    $stmt->bindParam(':id_sender', $id_sender);
    $stmt->bindParam(':id_receiver', $id_receiver);
    $stmt->bindParam(':content', $content);
    $stmt->execute();
    $message = $stmt->fetch();

    if ($message) {
        echo json_encode([
            'status' => 'success',
            'message' => [
                'content' => $message['content'],
                'created_at' => date('d/m/Y H:i', strtotime($message['created_at'])),
                'sender_username' => $_SESSION['user']['username']
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Message envoyé, mais impossible de récupérer les détails.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Impossible d\'envoyer le message. Assurez-vous que vous vous suivez mutuellement.']);
}
exit();