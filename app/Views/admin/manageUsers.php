<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cordes Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cordes-blue': '#1e40af',
                        'cordes-dark': '#1e293b',
                        'cordes-light': '#f8fafc',
                        'cordes-accent': '#3b82f6'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-200 min-h-screen">
    <?php
    $returnUrl = $_GET['return_url'] ?? BASE_URL . '/admin/dashboard';
    ?>

    <!-- Main content -->
    <div class="p-6">

        <div class="mb-6 flex items-center justify-between">
            <div class="flex justify-center items-center mt-4">
                <!-- Nút quay lại -->
                <a href="<?= htmlspecialchars($returnUrl) ?>"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white text-blue-500 hover:bg-blue-600 hover:text-white shadow-xl transition">
                    ←
                </a>

                <h1 class="text-3xl font-bold text-blue-500 ml-4">Quản lý người dùng</h1>
            </div>
            <div class="flex justify-between items-center space-x-4">
                <form method="GET" action="<?= BASE_URL ?>/admin/searchUsers">
                    <div class="relative w-1/6 mt-4">
                        <button type="submit">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 "></i>
                        </button>
                        <input type="text" placeholder="Search..." name="keyword" id="keyword" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
                            class="pl-10 pr-4 py-2 border border-white rounded-2xl outline-none shadow-xl">
                    </div>
                </form>
                <a href="<?= BASE_URL ?>/admin/addUser?return_url=<?= urlencode(BASE_URL . '/admin/manageUsers') ?>"
                    class="px-4 py-2 bg-blue-500 text-white rounded-2xl hover:bg-blue-700 transition shadow-xl">Thêm
                    người dùng</a>
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200 overflow-x-auto mt-6">
            <thead class="bg-yellow-500">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Họ tên
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Role
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Trạng thái
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Chức năng
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full"
                                        src="<?= htmlspecialchars($user['avatar'] ?? 'https://i.pravatar.cc/150') ?>"
                                        alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($user['name']) ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($user['email']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($user['role']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            $isActive = $user['id'] === $_SESSION['user']['id'];
                            $statusClass = $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                <?= $isActive ? 'online' : 'offline' ?>
                            </span>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?= BASE_URL ?>/admin/editUser?id=<?= $user['id'] ?>"
                                class="text-indigo-600 hover:text-indigo-900">Sửa</a>

                            <a href="<?= BASE_URL ?>/admin/deleteUser?id=<?= $user['id'] ?>"
                                class="ml-2 text-red-600 hover:text-red-900"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?')">
                                Xóa
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
        <ol class="flex justify-center text-xs font-medium space-x-1 mt-6">
            <!-- Nút Prev -->
            <?php if ($page > 1): ?>
                <li>
                    <a href="?page=<?= $page - 1 ?>"
                        class="inline-flex items-center justify-center w-8 h-8 border border-gray-100 rounded bg-white hover:bg-yellow-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Các số trang -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li>
                    <a href="?page=<?= $i ?>" class="block w-8 h-8 text-center border border-gray-100 rounded leading-8
                      <?= $page == $i ? 'bg-yellow-500 text-white' : 'bg-white hover:bg-yellow-100' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Nút Next -->
            <?php if ($page < $totalPages): ?>
                <li>
                    <a href="?page=<?= $page + 1 ?>"
                        class="inline-flex items-center justify-center w-8 h-8 border border-gray-100 rounded bg-white hover:bg-yellow-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </li>
            <?php endif; ?>
        </ol>

    </div>
</body>

</html>