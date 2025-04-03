<?php
require_once 'Database.php';

class HashtagSuggest
{
    public static function getLatestHashtags($limit = 4)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT name FROM Hashtag ORDER BY id_hash DESC LIMIT ?");
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
