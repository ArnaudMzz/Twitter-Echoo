<?php
require_once 'Database.php';
class User
{
    private $id;
    private $username;
    private $birthday;
    private $email;
    private $password;
    private $telephone;
    private $avatar_url;
    private $bio;
    private $created_at;

    public function __construct($username = null, $birthday = null, $email = null, $password = null, $telephone = null)
    {
        if ($username !== null) {
            $this->username = $username;
            $this->birthday = $birthday;
            $this->email = $email;
            $this->password = $this->hashPassword($password);
            $this->telephone = $telephone;
            $this->avatar_url = '';
            $this->bio = '';
            $this->created_at = date('Y-m-d H:i:s');
        }
    }

    private function hashPassword($password)
    {
        $salt = bin2hex(random_bytes(16));
        return $salt . 'vive le projet tweetcademy' . hash('ripemd160', $salt . $password);
    }

    public static function verifyPassword($inputPassword, $hashedPassword)
    {
        list($salt, $hash) = explode('vive le projet tweetcademy', $hashedPassword);
        return hash('ripemd160', $salt . $inputPassword) === $hash;
    }

    public function save()
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO User (username, birthday, email, password, telephone) 
                VALUES (:username, :birthday, :email, :password, :telephone)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':birthday', $this->birthday);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':telephone', $this->telephone);

        return $stmt->execute();
    }

    public static function emailExists($email)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function usernameExists($username)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM User WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function passwordCheck($password, $password_conf)
    {
        return $password === $password_conf;
    }

    public static function isEmpty($username, $birthday, $email, $password, $password_conf, $telephone)
    {
        return empty($username) || empty($birthday) || empty($email) || empty($password) || empty($password_conf) || empty($telephone);
    }

    public static function getUserByEmail($email)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateProfile($id_user, $username, $bio, $avatar_url)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE User 
            SET username = :username, bio = :bio, avatar_url = :avatar_url 
            WHERE id_user = :id_user
        ");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':avatar_url', $avatar_url);
        $stmt->bindParam(':id_user', $id_user);
        return $stmt->execute();
    }


    public function updateSecurity($id_user, $email, $telephone)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE User SET email = :email, telephone = :telephone WHERE id_user = :id_user");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':id_user', $id_user);
        return $stmt->execute();
    }

    public function deleteAccount($id_user)
    {
        $db = Database::getConnection();
        $sql = "DELETE FROM User WHERE id_user = :id_user";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function updatePassword($id_user, $current_password, $new_password)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT password FROM User WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $user = $stmt->fetch();

        if (!$user) {
            error_log("Utilisateur non trouvé : $id_user");
            return false;
        }

        error_log("Mot de passe stocké : " . $user['password']);
        error_log("Mot de passe actuel fourni : $current_password");

        if (!User::verifyPassword($current_password, $user['password'])) {
            error_log("Le mot de passe actuel est incorrect.");
            return false;
        }

        $hashed_password = $this->hashPassword($new_password);


        $stmt = $db->prepare("UPDATE User SET password = :password WHERE id_user = :id_user");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id_user', $id_user);

        return $stmt->execute();
    }
    public static function follow($id_follower, $id_followed)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Follower (id_follower, id_followed) VALUES (:id_follower, :id_followed)");
        $stmt->bindParam(':id_follower', $id_follower);
        $stmt->bindParam(':id_followed', $id_followed);
        return $stmt->execute();
    }

    public static function unfollow($id_follower, $id_followed)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM Follower WHERE id_follower = :id_follower AND id_followed = :id_followed");
        $stmt->bindParam(':id_follower', $id_follower);
        $stmt->bindParam(':id_followed', $id_followed);
        return $stmt->execute();
    }

    public static function isFollowing($id_follower, $id_followed)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Follower WHERE id_follower = :id_follower AND id_followed = :id_followed");
        $stmt->bindParam(':id_follower', $id_follower);
        $stmt->bindParam(':id_followed', $id_followed);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    public static function getFollowedUsers($id_user)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT User.* FROM User JOIN Follower ON User.id_user = Follower.id_followed WHERE Follower.id_follower = :id_user");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getFollower($id_user)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT User.* FROM User JOIN Follower ON User.id_user = Follower.id_follower WHERE Follower.id_followed = :id_user");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getFollowing($id_user)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT User.* FROM User JOIN Follower ON User.id_user = Follower.id_followed WHERE Follower.id_follower = :id_user");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getFollowerCount($id_user)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM Follower WHERE id_followed = :id_user");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }

    public static function getFollowingCount($id_user)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM Follower WHERE id_follower = :id_user");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }

    public static function getFollowers($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT u.* FROM User u
                              INNER JOIN Followers f ON u.id_user = f.follower_id
                              WHERE f.followed_id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public static function sendMessage($id_sender, $id_receiver, $content)
    {
        $db = Database::getConnection();

        if (!self::areMutualFollowers($id_sender, $id_receiver)) {
            return false;
        }

        $stmt = $db->prepare("INSERT INTO Message (id_sender, id_receiver, content) VALUES (:id_sender, :id_receiver, :content)");
        $stmt->bindParam(':id_sender', $id_sender);
        $stmt->bindParam(':id_receiver', $id_receiver);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }

    public static function areMutualFollowers($id_user1, $id_user2)
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM Follower WHERE id_follower = :id_user1 AND id_followed = :id_user2");
        $stmt->bindParam(':id_user1', $id_user1);
        $stmt->bindParam(':id_user2', $id_user2);
        $stmt->execute();
        $user1FollowsUser2 = $stmt->fetch() !== false;

        $stmt = $db->prepare("SELECT * FROM Follower WHERE id_follower = :id_user2 AND id_followed = :id_user1");
        $stmt->bindParam(':id_user1', $id_user1);
        $stmt->bindParam(':id_user2', $id_user2);
        $stmt->execute();
        $user2FollowsUser1 = $stmt->fetch() !== false;

        return $user1FollowsUser2 && $user2FollowsUser1;
    }

    public static function getMessages($id_user1, $id_user2)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(" SELECT * FROM Message 
            WHERE (id_sender = ? AND id_receiver = ?) 
            OR (id_sender = ? AND id_receiver = ?) 
            ORDER BY created_at ASC
        ");
        $stmt->execute([$id_user1, $id_user2, $id_user2, $id_user1]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getConversations($id_user)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT DISTINCT u.id_user, u.username, u.bio, u.avatar_url 
            FROM User u 
            JOIN Message m ON (u.id_user = m.id_sender OR u.id_user = m.id_receiver) 
            WHERE (m.id_sender = ? OR m.id_receiver = ?) 
            AND u.id_user != ?
        ");
        $stmt->execute([$id_user, $id_user, $id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}