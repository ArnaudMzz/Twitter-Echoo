<?php
require_once 'Database.php';

class Tweet
{
    private $id_tweet;
    private $id_user;
    private $content;
    private $image_url;
    private $created_at;

    public function __construct($id_user, $content, $image_url = null)
    {
        $this->id_user = $id_user;
        $this->content = $content;
        $this->image_url = $image_url;
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function save()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Tweet (id_user, content, image_url) VALUES (:id_user, :content, :image_url)");
        $stmt->bindParam(':id_user', $this->id_user);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->execute();

        $this->id_tweet = $db->lastInsertId();

        $this->processHashtags($this->content);
        $this->processMentions($this->content);
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

    private function processMentions($content)
    {
        preg_match_all('/@(\w+)/', $content, $matches);
        $mentions = $matches[1];
    
        $db = Database::getConnection();
        foreach ($mentions as $username) {
            $stmt = $db->prepare("SELECT id_user FROM User WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $mentionedUser = $stmt->fetch();
    
            if ($mentionedUser) {
                $stmt = $db->prepare("INSERT INTO Mention (id_user, id_tweet) VALUES (:id_user, :id_tweet)");
                $stmt->bindParam(':id_user', $mentionedUser['id_user']);
                $stmt->bindParam(':id_tweet', $this->id_tweet);
                $stmt->execute();
            }
        }
    }

    public static function getAllTweets()
    {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT Tweet.*, User.username 
            FROM Tweet 
            JOIN User ON Tweet.id_user = User.id_user 
            ORDER BY Tweet.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTweetsByUser($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Tweet WHERE id_user = :id_user ORDER BY created_at DESC");
        $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
}