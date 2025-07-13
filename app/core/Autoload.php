<?php

spl_autoload_register(function ($class) {
    // Các thư mục cần tìm
    $folders = ['app/Controllers', 'app/Models', 'app/Core'];

    foreach ($folders as $folder) {
        $file = __DIR__ . '/../../' . $folder . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // Debug nếu không tìm thấy
    echo "<p style='color:red'>Autoload: Không tìm thấy class <strong>$class</strong></p>";
});
 
define('BASE_URL', '/musicofminh/public');