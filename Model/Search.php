<?php
class SearchModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function searchUsers($query) {
        $stmt = $this->pdo->prepare("SELECT id_user, username, avatar_url FROM User WHERE username LIKE ? LIMIT 10");
        $stmt->execute(["%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchHashtags($query) {
        $stmt = $this->pdo->prepare("SELECT id_hash, name FROM Hashtag WHERE name LIKE ? LIMIT 10");
        $stmt->execute(["%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchTweets($query) {
        $stmt = $this->pdo->prepare("SELECT Tweet.id_tweet, Tweet.content, Tweet.created_at, User.username, User.avatar_url FROM Tweet 
        JOIN User ON Tweet.id_user = User.id_user LEFT JOIN Hash_list ON Tweet.id_tweet = Hash_list.id_tweet LEFT JOIN Hashtag 
        ON Hash_list.id_hash = Hashtag.id_hash WHERE Tweet.content LIKE ? OR Hashtag.name LIKE ? GROUP BY Tweet.id_tweet 
        ORDER BY Tweet.created_at DESC LIMIT 10");

        $stmt->execute(["%$query%", "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
