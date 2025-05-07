<?php
require_once '../models/Song.php';
require_once '../models/Playlist.php';

class PlayerController {
    private $songModel;
    private $playlistModel;

    public function __construct() {
        $this->songModel = new Song();
        $this->playlistModel = new Playlist();
    }

    public function index() {
        $songs = $this->songModel->getAllSongs();
        $playlists = $this->playlistModel->getUserPlaylists($_SESSION['user_id']);
        
        require_once '../views/player.php';
    }

    public function playSong($id) {
        $song = $this->songModel->getSongById($id);
        echo json_encode($song);
    }
}
?>