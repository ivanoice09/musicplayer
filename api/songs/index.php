<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../models/Song.php';

$songModel = new Song();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $song = $songModel->getSongById($_GET['id']);
            echo json_encode($song);
        } else {
            $songs = $songModel->getAllSongs();
            echo json_encode($songs);
        }
        break;
        
    case 'POST':
        // Handle song creation
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        break;
}
?>