<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../Model/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    if (!isset($_SESSION['user']['id_user'])) {
        throw new Exception("Utilisateur non connecté.");
    }

    $userId = $_SESSION['user']['id_user'];

    if (!isset($_POST['theme'])) {
        throw new Exception("Données invalides.");
    }

    $theme = trim($_POST['theme']);

    // Mise à jour du thème
    $sql = "UPDATE User SET theme = ? WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $theme, PDO::PARAM_STR);
    $stmt->bindValue(2, $userId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => "Thème mis à jour"]);
    } else {
        echo json_encode(["error" => "Aucune modification effectuée ou utilisateur non trouvé."]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
