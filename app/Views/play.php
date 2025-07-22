<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
   <!-- HTML -->
<div class="w-1/4 mx-auto mt-24 bg-white border rounded-lg shadow p-4 dark:bg-black dark:shadow-white shadow-md">
    <div class="flex flex-col justify-center items-center">
        <img class="rounded-lg aspect-square w-64"
            src="https://is1-ssl.mzstatic.com/image/thumb/Music116/v4/d5/37/f0/d537f0d1-5cfd-ce67-d7ac-0c4151f63f70/23UMGIM17915.rgb.jpg/1200x1200bb.jpg" />
        <p class="mt-2 font-semibold text-md text-gray-600"><?= htmlspecialchars($song['title']) ?></p>
        <p class="font-semibold text-xs text-gray-400 mt-2"><?= htmlspecialchars($song['artist']) ?></p>
    </div>

    <div class="flex flex-col justify-center items-center my-4">
        <input type="range" value="00" class="rounded-lg overflow-hidden appearance-none bg-gray-200 h-1 w-full" />
        <div class="flex justify-between w-3/5 items-center my-2">
            <!-- Previous Button -->
            <button class="aspect-square bg-white flex justify-center items-center rounded-full p-2 shadow-lg dark:bg-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#816cfa" fill-rule="evenodd"
                        d="M7 6a1 1 0 0 1 2 0v4l6.4-4.8A1 1 0 0 1 17 6v12a1 1 0 0 1-1.6.8L9 14v4a1 1 0 1 1-2 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Play Button -->
            <button onclick="togglePlay()" id="playButton" class="aspect-square bg-white flex justify-center items-center rounded-full p-2 shadow-lg dark:bg-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 512 512">
                    <path fill="#816cfa"
                        d="M133 440a35.37 35.37 0 0 1-17.5-4.67c-12-6.8-19.46-20-19.46-34.33V111c0-14.37 7.46-27.53 19.46-34.33a35.13 35.13 0 0 1 35.77.45l247.85 148.36a36 36 0 0 1 0 61l-247.89 148.4A35.5 35.5 0 0 1 133 440" />
                </svg>
            </button>

            <!-- Next Button -->
            <button class="aspect-square bg-white flex justify-center items-center rounded-full p-2 shadow-lg dark:bg-gray-800">
                <svg class="rotate-180" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#816cfa" fill-rule="evenodd"
                        d="M7 6a1 1 0 0 1 2 0v4l6.4-4.8A1 1 0 0 1 17 6v12a1 1 0 0 1-1.6.8L9 14v4a1 1 0 1 1-2 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Trình phát nhạc -->
    <audio id="audioPlayer">
        <source src="<?= htmlspecialchars($song['file']) ?>" type="audio/mpeg">
        Trình duyệt của bạn không hỗ trợ audio.
    </audio>

    <?php
        $musicPath = __DIR__ . '/../../../public/uploads/music/' . $song['file'];
        if (!file_exists($musicPath)) {
            echo "<p style='color:red; text-align:center;'>File nhạc không tồn tại: " . htmlspecialchars($song['file']) . "</p>";
        }
    ?>
</div>

<!-- JavaScript để phát nhạc -->
<script>
    function togglePlay() {
        const audio = document.getElementById('audioPlayer');
        const btn = document.getElementById('playButton');
        const playIcon = `<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" viewBox=\"0 0 512 512\"><path fill=\"#816cfa\" d=\"M133 440a35.37 35.37 0 0 1-17.5-4.67c-12-6.8-19.46-20-19.46-34.33V111c0-14.37 7.46-27.53 19.46-34.33a35.13 35.13 0 0 1 35.77.45l247.85 148.36a36 36 0 0 1 0 61l-247.89 148.4A35.5 35.5 0 0 1 133 440\"/></svg>`;
        const pauseIcon = `<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" viewBox=\"0 0 24 24\"><path fill=\"#816cfa\" d=\"M6 4h4v16H6zm8 0h4v16h-4z\"/></svg>`;

        if (audio.paused) {
            audio.play();
            btn.innerHTML = pauseIcon;
        } else {
            audio.pause();
            btn.innerHTML = playIcon;
        }
    }

    // Đổi icon khi nhạc kết thúc
    document.addEventListener('DOMContentLoaded', function() {
        const audio = document.getElementById('audioPlayer');
        const btn = document.getElementById('playButton');
        const playIcon = `<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" viewBox=\"0 0 512 512\"><path fill=\"#816cfa\" d=\"M133 440a35.37 35.37 0 0 1-17.5-4.67c-12-6.8-19.46-20-19.46-34.33V111c0-14.37 7.46-27.53 19.46-34.33a35.13 35.13 0 0 1 35.77.45l247.85 148.36a36 36 0 0 1 0 61l-247.89 148.4A35.5 35.5 0 0 1 133 440\"/></svg>`;
        audio.addEventListener('ended', function() {
            btn.innerHTML = playIcon;
        });
    });
</script>


</body>

</html>