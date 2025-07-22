<?php
class SongController
{

    private $songModel;

    public function manageSongs()
    {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        $perPage = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;

        // Lấy danh sách bài hát từ DB
        $this->songModel = new Song();
        if (!$this->songModel) {
            die('Song model not found');
        }

        $songs = $this->songModel->getSongsPaginated($perPage, $offset);
        $totalSongs = $this->songModel->countSongs();
        $totalPages = ceil($totalSongs / $perPage);

        require '../app/Views/dashboard/song/manageSongs.php';
    }

    public function addSong()
    {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        // Nếu là phương thức POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $genre = trim($_POST['genre']);
            $artist = trim($_POST['artist']);

            $thumbnail = $_FILES['thumbnail'] ?? null;
            $file = $_FILES['file'] ?? null;

            // Kiểm tra thông tin cơ bản
            if (empty($title) || empty($genre) || empty($artist)) {
                $errors = "Vui lòng nhập đầy đủ thông tin bài hát.";

            }

            // Kiểm tra thumbnail
            if ($thumbnail && $thumbnail['error'] === UPLOAD_ERR_OK) {
                $thumbType = strtolower(pathinfo($thumbnail['name'], PATHINFO_EXTENSION));
                if (!in_array($thumbType, ['jpg', 'jpeg', 'png'])) {
                    $errors = "Ảnh thumbnail phải là file JPG, JPEG hoặc PNG.";
                }
            } else {
                $errors = "Vui lòng chọn ảnh thumbnail.";
            }

            // Kiểm tra file nhạc
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if ($fileType !== 'mp3') {
                    $errors = "File nhạc phải là file .mp3.";
                }
            } else {
                $errors = "Vui lòng chọn file nhạc.";
            }

            // Nếu có lỗi → trả lại view
            if (!empty($errors)) {
                require '../app/views/dashboard/song/addSong.php';
                return;
            }

            // Lưu file vào thư mục uploads/
            $thumbnailName = uniqid() . '.' . $thumbType;
            $thumbnailPath = 'uploads/image/' . $thumbnailName;
            move_uploaded_file($thumbnail['tmp_name'], $thumbnailPath);

            $fileName = uniqid() . '.' . $fileType;
            $filePath = 'uploads/music/' . $fileName;
            move_uploaded_file($file['tmp_name'], $filePath);

            // Gọi model để lưu vào database
            $this->songModel = new Song();
            $this->songModel->addSong(
                $title,
                $artist,
                $genre,
                $filePath,
                $thumbnailPath

            );

            // Chuyển hướng về danh sách bài hát
            header('Location: ' . BASE_URL . '/song/manageSongs');
            exit;
        }

        // Nếu là GET → hiển thị form
        require '../app/views/dashboard/song/addSong.php';
    }

    public function editSong()
    {
        // Chỉ admin mới được phép
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        // Lấy ID từ query string
        if (!isset($_GET['id'])) {
            die("Thiếu ID bài hát.");
        }

        $this->songModel = new Song();
        $songId = (int) $_GET['id'];
        $song = $this->songModel->findById($songId);

        if (!$song) {
            $error = "Không tìm thấy bài hát.";
            require '../app/views/dashboard/song/editSong.php';
            return;
        }

        // Truyền $song vào view

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $artist = trim($_POST['artist']);
            $genre = trim($_POST['genre']);
            $thumbnail = $_FILES['thumbnail'] ?? null;
            $file = $_FILES['file'] ?? null;
            $errors = '';

            // Kiểm tra thông tin cơ bản
            if (empty($title) || empty($artist) || empty($genre)) {
                $errors = "Vui lòng nhập đầy đủ thông tin bài hát.";
            }

            // Khởi tạo đường dẫn mặc định từ DB (giữ lại nếu không có file mới)
            $filePath = $song['file'] ?? null;
            $thumbnailPath = $song['thumbnail'] ?? null;
            
            // Xử lý file nhạc
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if ($fileType !== 'mp3') {
                    $errors = "File nhạc phải là file .mp3.";
                } else {
                    $fileName = uniqid() . '.' . $fileType;
                    $filePath = 'uploads/music/' . $fileName;
                    move_uploaded_file($file['tmp_name'], $filePath);
                }
            }

            // Xử lý thumbnail
            if ($thumbnail && $thumbnail['error'] === UPLOAD_ERR_OK) {
                $thumbType = strtolower(pathinfo($thumbnail['name'], PATHINFO_EXTENSION));
                if (!in_array($thumbType, ['jpg', 'jpeg', 'png'])) {
                    $errors = "Ảnh thumbnail phải là JPG, JPEG hoặc PNG.";
                } else {
                    $thumbName = uniqid() . '.' . $thumbType;
                    $thumbnailPath = 'uploads/image/' . $thumbName;
                    move_uploaded_file($thumbnail['tmp_name'], $thumbnailPath);
                }
            }

            // Nếu có lỗi → trả lại view
            if (!empty($errors)) {
                require '../app/views/dashboard/song/editSong.php';
                return;
            }

            // Gọi model để cập nhật vào database
            $this->songModel->updateSong(
                $songId,
                $title,
                $artist,
                $genre,
                $filePath,
                $thumbnailPath
            );

            // Chuyển hướng về danh sách bài hát

            $message = urlencode("Cập nhật bài hát thành công!");
            // Chuyển hướng về danh sách bài hát
            header("Location: " . BASE_URL . "/song/editSong?id=" . $songId . "&message=$message");
            exit;
        }
        require_once '../app/views/dashboard/song/editSong.php';

    }
    
    public function deleteSong()
    {
        // Chỉ admin mới được phép
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        // Lấy ID từ query string
        if (!isset($_GET['id'])) {
            die("Thiếu ID bài hát.");
        }

        $this->songModel = new Song();
        $songId = (int) $_GET['id'];
        $song = $this->songModel->findById($songId);

        if (!$song) {
            $error = "Không tìm thấy bài hát.";
            require '../app/views/dashboard/song/manageSongs.php';
            return;
        }

        // Gọi model để xóa bài hát
        $this->songModel->deleteSong($songId);

        // Chuyển hướng về danh sách bài hát
        header('Location: ' . BASE_URL . '/song/manageSongs');
        exit;
    }

    public function searchSongs()
    {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        $keyword = trim($_GET['keyword'] ?? '');
        $perPage = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;

        $this->songModel = new Song();
        if (!$this->songModel) {
            die('Song model not found');
        }

        //  Nếu không phải email → tìm theo tên như bình thường
        $songs = $this->songModel->getSongsPaginated($perPage, $offset, $keyword);
        $totalSongs = $this->songModel->countSongs($keyword);
        $totalPages = ceil($totalSongs / $perPage);

        require '../app/Views/dashboard/song/manageSongs.php';
    }

    public function play() {
        // Chỉ admin mới được phép
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        // Lấy ID từ query string
        if (!isset($_GET['id'])) {
            die("Thiếu ID bài hát.");
        }

        $this->songModel = new Song();
        $songId = (int) $_GET['id'];
        $song = $this->songModel->findById($songId);

        if (!$song) {
            $error = "Không tìm thấy bài hát.";
            require '../app/views/play.php';
            return;
        }

        require_once '../app/views/play.php';

    }

}
?>