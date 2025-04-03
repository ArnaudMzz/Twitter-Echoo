<?php
require_once '../Model/Tweet.php';
require_once '../Model/Response.php';
require_once '../Model/Database.php';
require_once '../Model/UserSuggest.php';
require_once '../Model/HashtagSuggest.php';

// Récupération des suggestions
$latestHashtags = HashtagSuggest::getLatestHashtags(4);
$mentionedUsers = UserSuggest::getMentionedUsers(4);
