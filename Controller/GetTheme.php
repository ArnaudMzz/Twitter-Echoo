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

    // Récupérer le thème actuel
    $sql = "SELECT theme FROM User WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || empty($result['theme'])) {
        // Aucun thème enregistré => on assigne un thème par défaut
        $defaultTheme = "light";
        $updateSql = "UPDATE User SET theme = ? WHERE id_user = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindValue(1, $defaultTheme, PDO::PARAM_STR);
        $updateStmt->bindValue(2, $userId, PDO::PARAM_INT);
        $updateStmt->execute();

        echo json_encode(["theme" => $defaultTheme]); // On renvoie "light"
    } else {
        echo json_encode(["theme" => $result['theme']]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
