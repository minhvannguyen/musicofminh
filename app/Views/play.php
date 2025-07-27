<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play song</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .popup-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 400px;
            width: 90%;
            animation: popupSlideIn 0.3s ease-out;
        }
        
        @keyframes popupSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .close-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.1);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .close-btn:hover {
            background: rgba(0, 0, 0, 0.2);
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div class="popup-overlay" id="musicPlayerPopup">
        <div class="popup-content relative">
            <!-- Close Button -->
            <button class="close-btn" onclick="closePlayer()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            
            <!-- Player Content -->
            <div class="p-6">
                <div class="flex flex-col justify-center items-center">
                    <?php 
                    $thumbnailUrl = !empty($song['thumbnail']) 
                        ? BASE_URL . '/' . ltrim($song['thumbnail'], '/') 
                        : 'https://i.pravatar.cc/400?img=' . (rand(1, 70));
                    ?>
                    <img class="rounded-lg aspect-square w-48 mb-4" 
                         src="<?= htmlspecialchars($thumbnailUrl) ?>"
                         onerror="this.onerror=null;this.src='https://i.pravatar.cc/400?img=<?= rand(1, 70) ?>';" />
                    <div class="text-center mb-4">
                        <p class="font-semibold text-lg text-gray-800 mb-1"><?= htmlspecialchars($song['title']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($song['artist']) ?></p>
                    </div>
                </div>

                <div class="flex flex-col justify-center items-center">
                    <div class="w-full flex items-center mb-4">
                        <span id="currentTime" class="text-xs text-gray-500 mr-2">0:00</span>
                        <input type="range" id="progressBar" value="0" min="0" max="100" step="1"
                            class="rounded-lg overflow-hidden appearance-none bg-gray-200 h-1 w-full" />
                        <span id="duration" class="text-xs text-gray-500 ml-2">0:00</span>
                    </div>
                    
                    <div class="flex justify-between w-3/4 items-center mb-4">
                        <!-- Previous Button -->
                        <button id="prevButton"
                            class="aspect-square bg-white flex justify-center items-center rounded-full p-2 shadow-lg hover:bg-gray-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#a855f7" fill-rule="evenodd"
                                    d="M7 6a1 1 0 0 1 2 0v4l6.4-4.8A1 1 0 0 1 17 6v12a1 1 0 0 1-1.6.8L9 14v4a1 1 0 1 1-2 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Play Button -->
                        <button onclick="togglePlay()" id="playButton"
                            class="aspect-square bg-purple-600 flex justify-center items-center rounded-full p-3 shadow-lg hover:bg-purple-700 transition">
                            <!-- Pause Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="white">
                                <path d="M6 4h4v16H6zm8 0h4v16h-4z" />
                            </svg>
                        </button>

                        <!-- Next Button -->
                        <button id="nextButton"
                            class="aspect-square bg-white flex justify-center items-center rounded-full p-2 shadow-lg hover:bg-gray-50 transition">
                            <svg class="rotate-180" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24">
                                <path fill="#a855f7" fill-rule="evenodd"
                                    d="M7 6a1 1 0 0 1 2 0v4l6.4-4.8A1 1 0 0 1 17 6v12a1 1 0 0 1-1.6.8L9 14v4a1 1 0 1 1-2 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex justify-center items-center gap-4">
                        <!-- Like Button -->
                        <?php if (isset($_SESSION['user']['id'])): ?>
                            <button id="likeButton" onclick="toggleLike()"
                                class="like-btn transition-all duration-200 hover:scale-110"
                                data-song-id="<?= $song['id'] ?>"
                                data-liked="<?= isset($isLiked) && $isLiked ? 'true' : 'false' ?>">
                                <svg id="likeIcon"
                                    class="w-6 h-6 <?= isset($isLiked) && $isLiked ? 'text-red-500 fill-current' : 'text-gray-400' ?>"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                            </button>
                        <?php endif; ?>

                        <button id="replayButton"
                            class="px-3 py-1 bg-purple-500 text-white rounded text-sm hover:bg-purple-600 transition">
                            Phát lại: <span id="replayStatus">Tắt</span>
                        </button>

                        <button id="autoPlayBtn"
                            class="px-3 py-1 bg-purple-500 text-white rounded text-sm hover:bg-purple-600 transition">
                            Tự động phát: <span id="autoPlayStatus">Bật</span>
                        </button>
                    </div>
                </div>

                <!-- Audio Player -->
                <audio id="audioPlayer" autoplay>
                    <source src="<?= htmlspecialchars(BASE_URL . '/' . $song['file']) ?>" type="audio/mpeg">
                    Trình duyệt của bạn không hỗ trợ audio.
                </audio>
            </div>
        </div>
    </div>

    <!-- Floating Music Player Button -->
    <div id="floatingPlayerBtn" class="fixed bottom-6 right-6 z-50 hidden">
        <button onclick="showPlayer()" 
                class="bg-purple-600 hover:bg-purple-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 hover:scale-110">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M8 5v14l11-7z"/>
            </svg>
        </button>
        <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
            <span id="songIndicator">♪</span>
        </div>
    </div>

    <!-- Audio Visualizer -->
    <canvas id="audioVisualizer"
        style="position:fixed;left:0;top:0;width:100vw;height:100vh;z-index:9999;pointer-events:none;display:block;"></canvas>

    <!-- JavaScript -->
    <script>
        // Close player function
        function closePlayer() {
            const popup = document.getElementById('musicPlayerPopup');
            popup.style.animation = 'popupSlideOut 0.3s ease-in forwards';
            setTimeout(() => {
                // Hide the popup instead of navigating away
                popup.style.display = 'none';
                // Show the floating button
                document.getElementById('floatingPlayerBtn').classList.remove('hidden');
            }, 300);
        }

        // Function to show the player popup
        function showPlayer() {
            const popup = document.getElementById('musicPlayerPopup');
            popup.style.display = 'flex';
            popup.style.animation = 'popupSlideIn 0.3s ease-out forwards';
            document.getElementById('floatingPlayerBtn').classList.add('hidden');
        }

        // Close on backdrop click
        document.getElementById('musicPlayerPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                closePlayer();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePlayer();
            }
        });

        // Like functionality
        function toggleLike() {
            const likeButton = document.getElementById('likeButton');
            const likeIcon = document.getElementById('likeIcon');
            const songId = likeButton.getAttribute('data-song-id');
            const isCurrentlyLiked = likeButton.getAttribute('data-liked') === 'true';

            // Show loading state
            likeButton.disabled = true;
            likeIcon.classList.add('animate-pulse');

            fetch('<?= BASE_URL ?>/song/toggleLike', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    song_id: songId,
                    action: isCurrentlyLiked ? 'unlike' : 'like'
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        if (data.liked) {
                            likeIcon.classList.remove('text-gray-400');
                            likeIcon.classList.add('text-red-500', 'fill-current');
                            likeButton.setAttribute('data-liked', 'true');
                        } else {
                            likeIcon.classList.remove('text-red-500', 'fill-current');
                            likeIcon.classList.add('text-gray-400');
                            likeButton.setAttribute('data-liked', 'false');
                        }
                    } else {
                        alert(data.message || 'Có lỗi xảy ra!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi thực hiện thao tác!');
                })
                .finally(() => {
                    // Remove loading state
                    likeButton.disabled = false;
                    likeIcon.classList.remove('animate-pulse');
                });
        }

        function formatTime(seconds) {
            const min = Math.floor(seconds / 60);
            const sec = Math.floor(seconds % 60);
            return `${min}:${sec.toString().padStart(2, '0')}`;
        }

        function togglePlay() {
            const audio = document.getElementById('audioPlayer');
            const btn = document.getElementById('playButton');
            const playIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 512 512" fill="white"><path d="M133 440a35.37 35.37 0 0 1-17.5-4.67c-12-6.8-19.46-20-19.46-34.33V111c0-14.37 7.46-27.53 19.46-34.33a35.13 35.13 0 0 1 35.77.45l247.85 148.36a36 36 0 0 1 0 61l-247.89 148.4A35.5 35.5 0 0 1 133 440"/></svg>`;
            const pauseIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="white"><path d="M6 4h4v16H6zm8 0h4v16h-4z"/></svg>`;

            if (audio.paused) {
                audio.play();
                btn.innerHTML = pauseIcon;
            } else {
                audio.pause();
                btn.innerHTML = playIcon;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const audio = document.getElementById('audioPlayer');
            const btn = document.getElementById('playButton');
            const playIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 512 512" fill="white"><path d="M133 440a35.37 35.37 0 0 1-17.5-4.67c-12-6.8-19.46-20-19.46-34.33V111c0-14.37 7.46-27.53 19.46-34.33a35.13 35.13 0 0 1 35.77.45l247.85 148.36a36 36 0 0 1 0 61l-247.89 148.4A35.5 35.5 0 0 1 133 440"/></svg>`;
            const progressBar = document.getElementById('progressBar');
            const currentTimeEl = document.getElementById('currentTime');
            const durationEl = document.getElementById('duration');

            // Audio Visualizer
            const canvas = document.getElementById('audioVisualizer');
            const ctx = canvas.getContext('2d');
            let audioCtx, analyser, src, dataArray, animationId;

            function setupVisualizer() {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    src = audioCtx.createMediaElementSource(audio);
                    analyser = audioCtx.createAnalyser();
                    src.connect(analyser);
                    analyser.connect(audioCtx.destination);
                    analyser.fftSize = 64;
                    dataArray = new Uint8Array(analyser.frequencyBinCount);
                }
            }

            function drawVisualizer() {
                if (!analyser) return;
                analyser.getByteFrequencyData(dataArray);
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                const barWidth = (canvas.width / dataArray.length) * 1.5;
                let x = 0;
                for (let i = 0; i < dataArray.length; i++) {
                    const barHeight = dataArray[i] / 2;
                    ctx.fillStyle = '#a259f7';
                    ctx.fillRect(x, canvas.height - barHeight, barWidth, barHeight);
                    x += barWidth + 1;
                }
                animationId = requestAnimationFrame(drawVisualizer);
            }

            audio.addEventListener('play', function () {
                setupVisualizer();
                audioCtx.resume();
                drawVisualizer();
            });
            audio.addEventListener('pause', function () {
                if (animationId) cancelAnimationFrame(animationId);
            });
            audio.addEventListener('ended', function () {
                if (animationId) cancelAnimationFrame(animationId);
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            });

            // Khi load metadata xong, cập nhật tổng thời lượng
            audio.addEventListener('loadedmetadata', function () {
                progressBar.max = Math.floor(audio.duration);
                durationEl.textContent = formatTime(audio.duration);
            });

            // Khi phát nhạc, cập nhật thanh tiến trình và thời gian hiện tại
            audio.addEventListener('timeupdate', function () {
                progressBar.value = Math.floor(audio.currentTime);
                currentTimeEl.textContent = formatTime(audio.currentTime);
            });

            // Cho phép kéo thanh để tua
            progressBar.addEventListener('input', function () {
                audio.currentTime = progressBar.value;
            });

            // Đổi icon khi nhạc kết thúc
            audio.addEventListener('ended', function () {
                btn.innerHTML = playIcon;
                progressBar.value = 0;
                currentTimeEl.textContent = '0:00';
            });
        });

        // Auto-play and navigation functionality
        const audio = document.getElementById("audioPlayer");
        const autoPlayBtn = document.getElementById("autoPlayBtn");
        const autoPlayStatus = document.getElementById("autoPlayStatus");
        const prevButton = document.getElementById("prevButton");
        const nextButton = document.getElementById("nextButton");
        const replayButton = document.getElementById("replayButton");
        const replayStatus = document.getElementById("replayStatus");

        let autoPlay = true;
        let replay = false;

        // Toggle auto play
        autoPlayBtn.addEventListener("click", () => {
            autoPlay = !autoPlay;
            autoPlayStatus.textContent = autoPlay ? "Bật" : "Tắt";
        });

        // Toggle replay
        replayButton.addEventListener("click", () => {
            replay = !replay;
            replayStatus.textContent = replay ? "Bật" : "Tắt";
            if (audio) {
                audio.loop = replay;
            }
        });

        const currentId = <?= json_encode($currentSong['id']) ?>;
        const songList = <?= json_encode(array_values($songs)) ?>;
        const type = <?= json_encode($type) ?>;

        const index = songList.findIndex(song => song.id == currentId);

        // Auto-play next song when current song ends
        audio.addEventListener("ended", function () {
            if (!autoPlay) return;

            const nextSong = songList[index + 1];

            if (nextSong) {
                window.location.href = `<?= BASE_URL ?>/song/play?id=${nextSong.id}&type=${type}`;
            } else {
                alert("Đã hết danh sách phát.");
            }
        });

        // Next button
        nextButton.addEventListener("click", () => {
            const nextSong = songList[index + 1];
            if (nextSong) {
                window.location.href = `<?= BASE_URL ?>/song/play?id=${nextSong.id}&type=${type}`;
            } else {
                alert("Bạn đang nghe bài cuối cùng trong danh sách.");
            }
        });

        // Previous button
        prevButton.addEventListener("click", () => {
            const prevSong = songList[index - 1];
            if (prevSong) {
                window.location.href = `<?= BASE_URL ?>/song/play?id=${prevSong.id}&type=${type}`;
            } else {
                alert("Bạn đang nghe bài đầu tiên trong danh sách.");
            }
        });

        // Audio visualizer setup
        (function () {
            const audio = document.getElementById('audioPlayer');
            const canvas = document.getElementById('audioVisualizer');
            const ctx = canvas.getContext('2d');
            
            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            let audioCtx, analyser, src, dataArray, animationId;
            const barCount = 256;

            function setupVisualizer() {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    src = audioCtx.createMediaElementSource(audio);
                    analyser = audioCtx.createAnalyser();
                    src.connect(analyser);
                    analyser.connect(audioCtx.destination);
                    analyser.fftSize = 512;
                    dataArray = new Uint8Array(analyser.frequencyBinCount);
                }
            }

            function drawVisualizer() {
                if (!analyser) return;
                analyser.getByteFrequencyData(dataArray);
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.save();
                ctx.beginPath();
                const step = Math.floor(dataArray.length / barCount);
                for (let i = 0; i < barCount; i++) {
                    const value = dataArray[i * step] || 0;
                    const x = (i / (barCount - 1)) * canvas.width;
                    const y = canvas.height - value * 1.5;
                    if (i === 0) {
                        ctx.moveTo(x, y);
                    } else {
                        ctx.lineTo(x, y);
                    }
                }
                ctx.strokeStyle = '#111';
                ctx.lineWidth = 2;
                ctx.stroke();
                ctx.restore();
                animationId = requestAnimationFrame(drawVisualizer);
            }

            audio.addEventListener('play', function () {
                setupVisualizer();
                audioCtx.resume();
                drawVisualizer();
            });
            audio.addEventListener('pause', function () {
                if (animationId) cancelAnimationFrame(animationId);
            });
            audio.addEventListener('ended', function () {
                if (animationId) cancelAnimationFrame(animationId);
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            });
        })();
    </script>

    <style>
        @keyframes popupSlideOut {
            from {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            to {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
        }
    </style>
</body>

</html>