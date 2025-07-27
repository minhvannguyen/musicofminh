<?php

class HomeController
{
    private $songModel;
    public function index()
    {

        $this->songModel = new Song();

        // Danh sách ngẫu nhiên
        $randomSongs = $this->songModel->getRandomSongs(9);

        // Bài hát nhiều lượt xem
        $topSongs = $this->songModel->getTopSongs(9);

        // Nếu đã đăng nhập thì lấy bài yêu thích
        $favoriteSongs = [];
        
        $newestSongs = $this->songModel->getNewestSongs(9);
        if (isset($_SESSION['user']['id'])) {
            $favoriteSongs = $this->songModel->getFavoriteSongsByUser($_SESSION['user']['id'], 9);
        }

        // Nếu đã đăng nhập thì lấy bài hát của bạn
        $mySongs = [];
        
        if (isset($_SESSION['user']['id'])) {
            $mySongs = $this->songModel->getMySongs($_SESSION['user']['id'], 9);
        }

        require_once '../app/Views/home.php';

    }

    public function randomSongs()
    {

        // Lấy danh sách bài hát từ DB
        $this->songModel = new Song();
        if (!$this->songModel) {
            die('Song model not found');
        }

        $songs = $this->songModel->getRandomSongs();

        require '../app/Views/home.php';
    }

    public function topSongs()
    {
        $this->songModel = new Song();

        if (!$this->songModel) {
            die('Không tìm thấy model bài hát');
        }

        $songs = $this->songModel->getTopSongs(); // gọi hàm model

        require '../app/Views/home.php'; // hoặc views/top.php nếu bạn muốn tách view riêng
    }

    public function favoriteSongs()
    {
        $this->songModel = new Song();

        if (!$this->songModel) {
            die('Không tìm thấy model bài hát');
        }

        $userId = $_SESSION['user']['id'] ?? null;

        if (!$userId) {
            die('Bạn cần đăng nhập để xem bài hát yêu thích');
        }

        $songs = $this->songModel->getFavoriteSongsByUser($userId);

        require '../app/Views/home.php'; // hoặc view riêng nếu muốn
    }


}
