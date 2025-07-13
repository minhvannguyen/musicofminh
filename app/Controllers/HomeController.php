<?php

class HomeController {
    public function index() {
        echo "<h1>Xin chào, " . ($_SESSION['user']['name'] ?? 'khách') . "</h1>";
        echo '<a href="' . BASE_URL . '/auth/logout">Đăng xuất</a><br>';
        echo '<a href="' . BASE_URL . '/auth/login">Đăng nhập</a>';

    }
}
