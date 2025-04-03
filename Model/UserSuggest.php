<?php
require_once 'Database.php';

class UserSuggest
{
    public static function getMentionedUsers()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT DISTINCT id_user, username, avatar_url FROM User
        LIMIT 4");
        //$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
