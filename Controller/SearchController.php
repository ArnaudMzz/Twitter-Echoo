<?php
require_once '../Model/Search.php';

class SearchController {
    private $model;

    public function __construct($pdo) {
        $this->model = new SearchModel($pdo);
    }

    public function search($query) {
        if (empty($query)) {
            return json_encode(['users' => [], 'hashtags' => [], 'tweets' => []]);
        }

        $results = [
            'users' => $this->model->searchUsers($query),
            'hashtags' => $this->model->searchHashtags($query),
            'tweets' => $this->model->searchTweets($query)
        ];

        header('Content-Type: application/json');
        echo json_encode($results);
    }
}
?>
