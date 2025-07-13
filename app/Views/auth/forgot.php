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
          <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Quên mật khẩu?</h1>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Đã nhớ ra mật khẩu?
            <a class="text-blue-600 decoration-2 hover:underline font-medium" href="<?= BASE_URL ?>/auth/login">
              Đăng nhập ở đây!
            </a>
          </p>
        </div>

        <div class="mt-5">
          <form method="POST" class="grid gap-y-4">
            <div class="grid gap-y-4">
              <div>
                <label for="email" class="block text-sm font-bold ml-1 mb-2 dark:text-white">Email</label>
                <div class="relative">
                  <input type="email" id="email" name="email" class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-[#d39430] focus:ring-[#d39430] shadow-sm" required aria-describedby="email-error">
                </div>
                <?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
              </div>
              <button type="submit" class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-yellow-500 text-gray-100 shadow-xl rounded-lg hover:shadow focus:shadow-md focus:shadow-outline">Xác nhận Email</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</form>
</body>
</html>