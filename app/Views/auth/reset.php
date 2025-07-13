<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <main id="content" role="main" class="w-full  max-w-md mx-auto p-6">
    <div class="mt-7 bg-white  rounded-xl shadow-lg dark:bg-gray-800">
      <div class="p-4 sm:p-7">
        <div class="text-center">
          <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Đổi mật khẩu?</h1>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Đã nhớ ra mật khẩu?
            <a class="text-blue-600 decoration-2 hover:underline font-medium" href="#">
              Đăng nhập ở đây!
            </a>
          </p>
        </div>

        <div class="mt-5">
          <form method="POST" class="grid gap-y-4">
            <div class="grid gap-y-4">
              <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                        <div class="mt-1">
                            <input name="password" type="password" autocomplete="password" required
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Xác nhận Mật khẩu</label>
                        <div class="mt-1">
                            <input name="confirm_password" type="password" autocomplete="confirm-password" required
                                class="px-2 py-3 mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-sky-500 sm:text-sm" />
                        </div>
                    </div>
                    <?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
              <button type="submit" class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-yellow-500 text-gray-100 shadow-xl rounded-lg hover:shadow focus:shadow-md focus:shadow-outline">Đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</form>
</body>
</html>