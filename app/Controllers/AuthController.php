<?php
require_once '../vendor/autoload.php'; // nếu dùng Composer
use App\Helpers\EmailSender;

use Google\Client as GoogleClient;
use Google\Service\Oauth2;

class AuthController
{
    private $userModel;
    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $this->userModel = new User();
            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                if($user['role'] === 'admin') {
                    $_SESSION['is_admin'] = true; // Đánh dấu là admin
                    require '../app/Views/admin/dashboard.php';
                exit;
                    
                } else {
                    $_SESSION['is_admin'] = false; // Không phải admin
                    header('Location: ' . BASE_URL . '/home/index');
                exit;
                }
            
            } else {
                $error = "Sai email hoặc mật khẩu!";
                require '../app/Views/auth/login.php';
            }
        } else {
            require '../app/Views/auth/login.php';
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $passwordOrigin = $_POST['password'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $confirmPassword = $_POST['confirm_password'];

            $this->userModel = new User();

            if (strlen($passwordOrigin) < 8 || !preg_match('/\d/', $passwordOrigin)) {
                $error = "mật khẩu phải từ 8 ký tự và chứa ít nhất một chữ số!";
                require '../app/Views/auth/register.php';
                return;
            }
            if ($this->userModel->findByEmail($email)) {
                $error = "Email đã được sử dụng!";
                require '../app/Views/auth/register.php';
                return;
            }
            if ($passwordOrigin !== $confirmPassword) {
                $error = "Mật khẩu nhập lại không khớp!";
                require '../app/Views/auth/register.php';
                return;
            }

            $this->userModel->create($name, $email, $password);
            $message = urlencode("Đăng ký thành công! Vui lòng đăng nhập.");
            header("Location: " . BASE_URL . "/auth/login?message=$message");
            exit;

        } else {
            require '../app/Views/auth/register.php';
        }
    }

    public function logout()
{
    // Nếu có access token
    if (isset($_SESSION['access_token'])) {
        $accessToken = $_SESSION['access_token']['access_token'] ?? null;

        if ($accessToken) {
            // Thu hồi token khỏi Google
            $revokeUrl = 'https://accounts.google.com/o/oauth2/revoke?token=' . $accessToken;
            @file_get_contents($revokeUrl);
        }
    }

    // Xoá session PHP
    session_unset();
    session_destroy();

    // Chuyển về login của bạn
    header("Location: " . BASE_URL . "/auth/login");
    exit;
}


    public function forgot()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $this->userModel = new User();
            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                $error = "Email không tồn tại trong hệ thống.";
                require '../app/Views/auth/forgot.php';
                return;
            }

            // Tạo mã xác nhận
            $token = rand(100000, 999999); // mã 6 số
            $emailSender = new EmailSender();

            if ($emailSender->sendTokenToEmail($email, $token)) {
                // lưu vào session
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_token'] = $token;
                $_SESSION['reset_token_expire'] = time() + 300;

                header("Location: " . BASE_URL . "/auth/verify");
                exit;
            } else {
                $error = "Không thể gửi mã xác nhận đến email. Vui lòng thử lại.";
                require '../app/Views/auth/forgot.php';
            }
        } else {
            require '../app/Views/auth/forgot.php';
        }
    }

    public function verify()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputToken = $_POST['token'] ?? '';

            if (!isset($_SESSION['reset_token']) || time() > $_SESSION['reset_token_expire']) {
                $error = "Mã xác nhận đã hết hạn.";
                require '../app/Views/auth/verify.php';
                return;
            }

            if ($inputToken != $_SESSION['reset_token']) {
                $error = "Mã xác nhận không chính xác.";
                require '../app/Views/auth/verify.php';
                return;
            }

            // Mã đúng → chuyển sang form đặt lại mật khẩu
            header("Location: " . BASE_URL . "/auth/reset");
            exit;
        } else {
            require '../app/Views/auth/verify.php';
        }
    }

    public function reset()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if ($password !== $confirm) {
                $error = "Mật khẩu không khớp.";
                require '../app/Views/auth/reset.php';
                return;
            }

            if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
                $error = "Mật khẩu phải ít nhất 8 ký tự và chứa ít nhất một số.";
                require '../app/Views/auth/reset.php';
                return;
            }

            $email = $_SESSION['reset_email'];
            $$this->userModel = new User();
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $this->userModel->updatePasswordByEmail($email, $hashed);

            unset($_SESSION['reset_email'], $_SESSION['reset_token'], $_SESSION['reset_token_expire']);

            $_SESSION['message'] = "Đổi mật khẩu thành công. Vui lòng đăng nhập.";
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        } else {
            require '../app/Views/auth/reset.php';
        }
    }
    public function googleLogin()
    {
        $client = new GoogleClient();
        $client->setClientId('192448148609-sonhkojml40g0l5ip0g4ki4f491fs8ts.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost/musicofminh/public/auth/googleCallback');
        $client->addScope('email');
        $client->addScope('profile');

        // Dòng này là chìa khoá để tránh "đăng nhập luôn"
        $client->setPrompt('select_account');
        $authUrl = $client->createAuthUrl();
        header('Location: ' . $authUrl);
        exit;
    }

    public function googleCallback()
    {
        $client = new GoogleClient();
        $client->setClientId('192448148609-sonhkojml40g0l5ip0g4ki4f491fs8ts.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost/musicofminh/public/auth/googleCallback');

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token);

            $oauth = new Oauth2($client);
            $googleUser = $oauth->userinfo->get();

            // Bạn có thể dùng $googleUser->email, $googleUser->name, ...
            $_SESSION['user'] = [
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'google_id' => $googleUser->id
            ];

            header('Location: ' . BASE_URL . '/home/index');
            exit;
        } else {
            echo "Không có mã xác thực.";
        }
    }
}
?>