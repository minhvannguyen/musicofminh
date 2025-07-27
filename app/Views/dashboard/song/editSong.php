<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang sửa bài hát</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- HTML + PHP ở đây -->
    <!-- Nút quay lại -->
    <?php
    $returnUrl = $_GET['return_url'] ?? BASE_URL . '/song/manageSongs';
    ?>



    <div class="bg-gray-100 flex h-screen items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">

            <div class="bg-white shadow-md rounded-md p-6">
                <a href="<?= htmlspecialchars($returnUrl) ?>"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white text-blue-500 hover:bg-blue-600 hover:text-white shadow-xl transition">
                    ←
                </a>

                <img class="mx-auto h-12 w-auto" src="https://www.svgrepo.com/show/499664/user-happy.svg" alt="" />

                <h2 class="my-3 text-center text-3xl font-bold tracking-tight text-gray-900 mb-2">
                    Sửa bài hát
                </h2>

                <form class="space-y-6" method="POST" enctype="multipart/form-data">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tên bài hát</label>
                        <div class="mt-1">
                            <input name="title" type="text" required
                                value="<?= htmlspecialchars($song['title'] ?? '') ?>"
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nhạc sĩ</label>
                        <div class="mt-1">
                            <input name="artist" type="text" required
                                value="<?= htmlspecialchars($song['artist'] ?? '') ?>"
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="genre" class="block text-sm font-medium text-gray-700">Thể loại</label>
                        <div class="mt-1">
                            <input name="genre" type="text" required
                                value="<?= htmlspecialchars($song['genre'] ?? '') ?>"
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700">File nhạc (.mp3)</label>
                        <div class="mt-1">
                            <input name="file" type="file" accept=".mp3" 
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700">Hình ảnh đại diện</label>
                        <div class="mt-1">
                            <input name="thumbnail" type="file" 
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>

                    <button type="submit"
                        class="flex w-full justify-center rounded-md shadow-xl hover:shadow focus:shadow-md focus:shadow-outline border border-transparent bg-purple-600 py-2 px-4 text-sm font-medium text-white">
                        Lưu
                    </button>
                    <div class="h-7">
                        <?php if (!empty($errors))
                            echo "<p style='color:red'>$errors</p>"; ?>
                        <?php if (isset($_GET['message'])): ?>
                            <div style='color:green; margin-top: 10px; font-size: 17px;'>
                                <?= htmlspecialchars($_GET['message']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>