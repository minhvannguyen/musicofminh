<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang đăng ký</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- HTML + PHP ở đây -->

    <div class="bg-gray-100 flex h-screen items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">

            <div class="bg-white shadow-md rounded-md p-6">

                <img class="mx-auto h-12 w-auto" src="https://www.svgrepo.com/show/499664/user-happy.svg" alt="" />

                <h2 class="my-3 text-center text-3xl font-bold tracking-tight text-gray-900 mb-2">
                    Đăng ký tài khoản
                </h2>
                <div class="text-center text-sm text-gray-500 mt-2 mb-10">Đã có tài khoản?<a
                        href="<?= BASE_URL ?>/auth/login" class="text-blue-600 hover:underline">Đăng nhập</a></div>



                <form class="space-y-6" method="POST">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tên đăng nhập</label>
                        <div class="mt-1">
                            <input name="name" type="username" required
                                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input name="email" type="gmail" autocomplete="email-address" required
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                        <div class="mt-1">
                            <input name="password" type="password" autocomplete="password" required
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
                        <div class="mt-1">
                            <input name="confirm_password" type="password" autocomplete="confirm-password" required
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>
                    <?php if (!empty($errors))
                        echo "<p style='color:red'>$errors</p>"; ?>
                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md shadow-xl hover:shadow focus:shadow-md focus:shadow-outline border border-transparent bg-purple-600 py-2 px-4 text-sm font-medium text-white">Đăng
                            ký
                            tài khoản
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>