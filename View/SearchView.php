<?php
require_once '../Model/Database.php';
require_once '../Controller/SearchController.php';

$pdo = new PDO('mysql:host=localhost;dbname=my_twitter', 'administrateur', 'nono');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$controller = new SearchController($pdo);
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$controller->search($query);
?>
