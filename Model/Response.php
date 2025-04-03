<?php
require_once 'Database.php';

class Response
{
    private $id;
    private $id_tweet;
    private $id_user;
    private $content;
    private $created_at;

    public function __construct($id_tweet, $id_user, $content)
    {
        $this->id_tweet = $id_tweet;
        $this->id_user = $id_user;
        $this->content = $content;
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function save()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Response (id_tweet, id_user, content, created_at) VALUES (:id_tweet, :id_user, :content, :created_at)");
        $stmt->bindParam(':id_tweet', $this->id_tweet);
        $stmt->bindParam(':id_user', $this->id_user);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':created_at', $this->created_at);
        return $stmt->execute();

        $this->id = $db->lastInsertId();

        // Traiter les hashtags
        $this->processHashtags($this->content);
    }

    private function processHashtags($content)
    {
        preg_match_all('/#(\w+)/', $content, $matches);
        $hashtags = $matches[1];

        $db = Database::getConnection();
        foreach ($hashtags as $hashtag) {
            $stmt = $db->prepare("SELECT id_hash FROM Hashtag WHERE name = :name");
            $stmt->bindParam(':name', $hashtag);
            $stmt->execute();
            $existingHashtag = $stmt->fetch();

            if (!$existingHashtag) {
                $stmt = $db->prepare("INSERT INTO Hashtag (name) VALUES (:name)");
                $stmt->bindParam(':name', $hashtag);
                $stmt->execute();
                $id_hash = $db->lastInsertId();
            } else {
                $id_hash = $existingHashtag['id_hash'];
            }

            $stmt = $db->prepare("INSERT INTO Hash_list (id_tweet, id_hash) VALUES (:id_tweet, :id_hash)");
            $stmt->bindParam(':id_tweet', $this->id_tweet);
            $stmt->bindParam(':id_hash', $id_hash);
            $stmt->execute();
        }
    }

    public static function getCommentsByTweet($id_tweet)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT Response.*, User.username FROM Response JOIN User ON Response.id_user = User.id_user WHERE id_tweet = :id_tweet ORDER BY created_at ASC");
        $stmt->bindParam(':id_tweet', $id_tweet);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}