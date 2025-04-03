<?php
require_once '../Model/Response.php';

if (isset($_GET['id_tweet'])) {
    $id_tweet = $_GET['id_tweet'];
    $responses = Response::getCommentsByTweet($id_tweet);

    foreach ($responses as $response) {
        echo '<div class="response-item">';
        echo '<p><strong>' . htmlspecialchars($response['username']) . ':</strong> ' . htmlspecialchars($response['content']) . '</p>';
        echo '<p class="response-date">' . htmlspecialchars($response['created_at']) . '</p>';
        echo '</div>';
    }

    // Ajouter un formulaire de réponse
    echo '<form id="response-form">';
    echo '<textarea maxlength="140" name="content" class="tweet-area" placeholder="Votre réponse..." required></textarea>';
    echo '<input type="hidden" name="id_tweet" value="' . htmlspecialchars($id_tweet) . '">';
    echo '<button type="submit" class="tweet-btn response-btn">Envoyer</button>';
    echo '</form>';
}