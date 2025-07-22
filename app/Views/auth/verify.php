<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="max-w-md mx-auto border mt-20">
    <form method="POST" class="bg-white shadow-md rounded px-8 py-6">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="otp">OTP:</label>
            <input name="token" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="otp" type="text" placeholder="Enter OTP">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit"  class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                Xác thực
             </button>
            <a class="inline-block align-baseline font-bold text-sm text-teal-500 hover:text-teal-800" href="<?= BASE_URL ?>/auth/forgot">
                Gửi lại OTP
            </a>
        </div>
        <?php if (!empty($error))
                echo "<p style='color:red; margin-top: 10px'>$error</p>"; ?>
    </form>
</div>
</body>
</html>