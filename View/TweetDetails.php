<?php
session_start();
require_once '../Model/Tweet.php';
require_once '../Model/Response.php';
require_once '../Model/Database.php';

if (!isset($_SESSION['user'])) {
    header('Location: Login.php');
    exit();
}

$id_tweet = $_GET['id_tweet'] ?? null;

if (!$id_tweet) {
    die("Erreur : Aucun tweet spécifié.");
}

// Récupérer le tweet
$db = Database::getConnection();
$stmt = $db->prepare("SELECT Tweet.*, User.username FROM Tweet JOIN User ON Tweet.id_user = User.id_user WHERE id_tweet = :id_tweet");
$stmt->bindParam(':id_tweet', $id_tweet);
$stmt->execute();
$tweet = $stmt->fetch();

if (!$tweet) {
    die("Erreur : Tweet non trouvé.");
}

// Récupérer les réponses au tweet
$comments = Response::getCommentsByTweet($id_tweet);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répondre au tweet de <?= htmlspecialchars($tweet['username']) ?></title>
    <link rel="stylesheet" href="./assets/Home.css">
</head>
<body>
    <div class="container">
        <h1>Répondre au tweet de <?= htmlspecialchars($tweet['username']) ?></h1>
        <div class="tweet">
            <strong><?= htmlspecialchars($tweet['username']) ?>:</strong>
            <?= preg_replace_callback(
                '/(#|@)(\w+)/',
                function ($matches) {
                    $type = $matches[1]; // # ou @
                    $value = $matches[2]; // Le mot après # ou @
                    if ($type === '#') {
                        return '<a href="Hashtag.php?tag=' . urlencode($value) . '" class="hashtag">#' . htmlspecialchars($value) . '</a>';
                    } else {
                        return '<a href="Profile.php?username=' . urlencode($value) . '" class="mention">@' . htmlspecialchars($value) . '</a>';
                    }
                },
                htmlspecialchars($tweet['content'])
            ) ?>
            <?php if (!empty($tweet['image_url'])) : ?>
                <img src="<?= htmlspecialchars($tweet['image_url']) ?>" alt="Image tweet" style="max-width:200px;">
            <?php endif; ?>
        </div>

        <h2>Réponses</h2>
        <?php if (empty($comments)) : ?>
            <p>Aucune réponse trouvée.</p>
        <?php else : ?>
            <?php foreach ($comments as $comment) : ?>
                <div class="comment">
                    <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
                    <?= preg_replace_callback(
                        '/(#|@)(\w+)/',
                        function ($matches) {
                            $type = $matches[1]; // # ou @
                            $value = $matches[2]; // Le mot après # ou @
                            if ($type === '#') {
                                return '<a href="Hashtag.php?tag=' . urlencode($value) . '" class="hashtag">#' . htmlspecialchars($value) . '</a>';
                            } else {
                                return '<a href="Profile.php?username=' . urlencode($value) . '" class="mention">@' . htmlspecialchars($value) . '</a>';
                            }
                        },
                        htmlspecialchars($comment['content'])
                    ) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <h2>Répondre</h2>
        <form method="POST" action="../Controller/ResponseController.php">
            <input type="hidden" name="id_tweet" value="<?= $id_tweet ?>">
            <textarea name="content" placeholder="Ajouter un commentaire..." required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>
</body>
</html>