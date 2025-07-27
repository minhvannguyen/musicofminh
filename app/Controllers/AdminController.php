<?php
class AdminController
{
    private $songModel;
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->songModel = new Song();
    }
    public function manageUsers()
    {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        $perPage = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;

        // Lấy danh sách user từ DB
        if (!$this->userModel) {
            die('User model not found');
        }

        $users = $this->userModel->getUsersPaginated($perPage, $offset);
        $totalUsers = $this->userModel->countUsers();
        $totalPages = ceil($totalUsers / $perPage);
        require '../app/Views/dashboard/manageUsers.php';
    }

    public function dashboard()
    {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }


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

        //count songs
        $totalSongs = $this->songModel->countSongs();
        $totalNewestSongs = $this->songModel->countNewestSongs();
        $totalUsers = $this->userModel->countUsers();
        $totalNewestUsers = $this->userModel->countNewestUsers();
        $totalViews = $this->songModel->countViews();
        $totalLikes = $this->songModel->countLikes();
        $totalNewestLikes = $this->songModel->countNewestLikes();
        $onlineUser = new OnlineUserController();
        $onlineCount = $onlineUser->track();


        require '../app/Views/dashboard/dashboard.php';
    }


    public function addUser()
    {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $passwordOrigin = $_POST['password'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $confirmPassword = $_POST['confirm_password'];
            $role = $_POST['role'] ?? 'user'; // Mặc định là người dùng

            $this->userModel = new User();

            if (strlen($passwordOrigin) < 8 || !preg_match('/\d/', $passwordOrigin)) {
                $errors = "mật khẩu phải từ 8 ký tự và chứa ít nhất một chữ số!";
                require '../app/views/dashboard/addUser.php';
                return;
            }
            if ($this->userModel->findByEmail($email)) {
                $errors = "Email đã được sử dụng!";
                require '../app/views/dashboard/addUser.php';
                return;
            }
            if ($passwordOrigin !== $confirmPassword) {
                $errors = "Mật khẩu nhập lại không khớp!";
                require '../app/views/dashboard/addUser.php';
                return;
            }

            $this->userModel->create($name, $email, $password, $role);
            $message = urlencode("Đăng ký thành công! Vui lòng đăng nhập.");
            header("Location: " . BASE_URL . "/admin/addUser?message=$message");
            exit;

        } else {
            require '../app/views/dashboard/addUser.php';
        }
    }

    public function editUser()
    {
        // Chỉ admin mới được phép
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        // Lấy ID từ query string
        if (!isset($_GET['id'])) {
            die("Thiếu ID người dùng.");
        }

        $this->userModel = new User();
        $userId = (int) $_GET['id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $errors = "Không tìm thấy người dùng.";
            require '../app/views/dashboard/editUser.php';
            return;
        }

        // Truyền $user vào view
        require_once '../app/views/dashboard/editUser.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $passwordOrigin = $_POST['password'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'] ?? 'user'; // Mặc định là người dùng

            $this->userModel = new User();

            if (strlen($passwordOrigin) < 8 || !preg_match('/\d/', $passwordOrigin)) {
                $errors = "mật khẩu phải từ 8 ký tự và chứa ít nhất một chữ số!";
                require '../app/views/dashboard/editUser.php';
                return;
            }
            $user = $this->userModel->findByEmail($email);
            if (!$user) {
                $errors = "Không tìm thấy người dùng.";
                require '../app/views/dashboard/editUser.php';
                return;
            }
            $this->userModel->update($user['id'], $name, $email, $password, $role);
            $message = urlencode("Cập nhật người dùng thành công!");
            header("Location: " . BASE_URL . "/admin/editUser?id=" . $user['id'] . "&message=$message");
            exit;
        } else {
            require_once '../app/views/dashboard/editUser.php';
        }

    }


    public function deleteUser()
    {
        // Logic to delete a user
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        if (!isset($_GET['id'])) {
            die("Thiếu ID người dùng.");
        }

        $this->userModel = new User();
        $userId = (int) $_GET['id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $errors = "Không tìm thấy người dùng.";
            require '../app/views/dashboard/manageUsers.php';
            return;
        }

        $this->userModel->delete($userId);
        $message = urlencode("Xóa người dùng thành công!");
        header("Location: " . BASE_URL . "/manageUsers?message=$message");
        exit;
    }

    public function searchUsers()
    {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/home/index');
            exit;
        }

        $keyword = trim($_GET['keyword'] ?? '');
        $perPage = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;

        $this->userModel = new User();
        if (!$this->userModel) {
            die('User model not found');
        }

        //  Nếu là email thì tìm theo email và redirect
        if (filter_var($keyword, FILTER_VALIDATE_EMAIL)) {
            $user = $this->userModel->findByEmail($keyword); // viết thêm method này
            if ($user) {
                // Redirect tới trang sửa user theo ID
                header('Location: ' . BASE_URL . '/editUser?id=' . $user['id']);
                exit;
            }
        }

        //  Nếu không phải email → tìm theo tên như bình thường
        $users = $this->userModel->getUsersPaginated($perPage, $offset, $keyword);
        $totalUsers = $this->userModel->countUsers($keyword);
        $totalPages = ceil($totalUsers / $perPage);

        require '../app/Views/dashboard/manageUsers.php';
    }


}