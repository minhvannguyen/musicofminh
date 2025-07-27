<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Music Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Function to generate dynamic avatar based on song title, artist, and genre
    function generateSongAvatar(title, artist, genre) {
      const colors = [
        '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
        '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9',
        '#F8C471', '#82E0AA', '#F1948A', '#85C1E9', '#D7BDE2'
      ];

      // Generate text from title, artist, or genre
      let text = 'MU';
      if (title) {
        text = title.substring(0, 2).toUpperCase();
      } else if (artist) {
        text = artist.substring(0, 2).toUpperCase();
      } else if (genre) {
        text = genre.substring(0, 2).toUpperCase();
      }

      // Generate color based on text
      const color = colors[Math.abs(text.charCodeAt(0) + text.charCodeAt(1)) % colors.length];

      // Add some musical notes based on genre
      let musicalNote = '';
      if (genre) {
        const genreLower = genre.toLowerCase();
        if (genreLower.includes('pop') || genreLower.includes('rock')) {
          musicalNote = '♪';
        } else if (genreLower.includes('rap') || genreLower.includes('hip')) {
          musicalNote = '♫';
        } else if (genreLower.includes('jazz') || genreLower.includes('blues')) {
          musicalNote = '♬';
        } else if (genreLower.includes('edm') || genreLower.includes('dance')) {
          musicalNote = '♩';
        } else if (genreLower.includes('classical') || genreLower.includes('opera')) {
          musicalNote = '♭';
        } else {
          musicalNote = '♪';
        }
      }

      // Add time-based color variation
      const hour = new Date().getHours();
      let timeColor = color;
      if (hour >= 6 && hour < 12) {
        // Morning - brighter colors
        timeColor = color;
      } else if (hour >= 12 && hour < 18) {
        // Afternoon - normal colors
        timeColor = color;
      } else {
        // Evening/Night - darker colors
        timeColor = color;
      }

      return `data:image/svg+xml;base64,${btoa(`
          <svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:${timeColor};stop-opacity:1" />
                <stop offset="100%" style="stop-color:${timeColor}dd;stop-opacity:1" />
              </linearGradient>
            </defs>
            <rect width="100" height="100" fill="url(#grad)" rx="10"/>
            <text x="50" y="45" font-family="Arial, sans-serif" font-size="20" font-weight="bold" text-anchor="middle" fill="white">${text}</text>
            ${musicalNote ? `<text x="50" y="70" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="white">${musicalNote}</text>` : ''}
          </svg>
        `)}`;
    }

    // Function to handle image error and generate fallback avatar
    function handleImageError(img, title, artist, genre) {
      img.onerror = function () {
        this.src = generateSongAvatar(title, artist, genre);
        this.onerror = null; // Prevent infinite loop
      };
    }

    // Function to refresh song sections without page reload
    function refreshSection(sectionType) {
      const button = event.target.closest('button');
      const originalContent = button.innerHTML;

      // Show loading state
      button.innerHTML = `
        <svg class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      `;

      // Make AJAX request to refresh the page data
      fetch('<?= BASE_URL ?>/home/index', {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => response.text())
        .then(html => {
          // Create a temporary div to parse the HTML
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = html;

          // Find the specific section to update using a more reliable method
          let targetSection;
          let sectionTitle;

          if (sectionType === 'suggested') {
            sectionTitle = 'Gợi Ý Bài Hát';
          } else if (sectionType === 'top') {
            sectionTitle = 'Top bài hát';
          } else if (sectionType === 'newest') {
            sectionTitle = 'Bài hát mới nhất';
          }

          // Find target section by looking for the h2 with the specific title
          const sections = document.querySelectorAll('section');
          for (let section of sections) {
            const h2 = section.querySelector('h2');
            if (h2 && h2.textContent.trim() === sectionTitle) {
              targetSection = section;
              break;
            }
          }

          if (targetSection) {
            // Find the corresponding section in the new HTML
            const newSections = tempDiv.querySelectorAll('section');
            for (let newSection of newSections) {
              const newH2 = newSection.querySelector('h2');
              if (newH2 && newH2.textContent.trim() === sectionTitle) {
                // Update only the content div, not the entire section
                const targetContent = targetSection.querySelector('.grid');
                const newContent = newSection.querySelector('.grid');
                if (targetContent && newContent) {
                  targetContent.innerHTML = newContent.innerHTML;
                }
                break;
              }
            }
          }

          // Restore button content
          button.innerHTML = originalContent;
        })
        .catch(error => {
          console.error('Error refreshing section:', error);
          // Restore button content on error
          button.innerHTML = originalContent;
        });
    }


  </script>
</head>

<body class="bg-gray-100 min-h-screen">
  <!-- Header -->
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <div class="flex items-center gap-2">
      <img src="https://cdn-icons-png.flaticon.com/512/727/727245.png" alt="Logo" class="w-8 h-8">
      <span class="font-bold text-xl text-purple-700">MusicOfMinh</span>
    </div>
    <form method="GET" action="<?= BASE_URL ?>/song/searchSongs" class="flex items-center w-1/3">
      <input type="text" placeholder="Tìm kiếm bài hát, nghệ sĩ..." name="keyword" id="keyword"
        value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
        class="w-full px-3 py-1 border rounded-l focus:outline-none">
      <button type="submit"
        class="bg-purple-600 text-white px-4 py-1 rounded-r hover:bg-purple-700 transition">Tìm</button>
    </form>
    <div>
      <?php if (!empty($_SESSION['user'])): ?>
        <a href="<?= BASE_URL ?>/auth/logout"
          class="bg-red-600 text-white px-4 py-1 rounded font-semibold hover:bg-purple-700 transition">Đăng
          xuất</a>
      <?php else: ?>
        <a href="<?= BASE_URL ?>/auth/login"
          class="text-purple-600 font-semibold hover:underline mr-4 hover:text-purple-800 transition">Đăng
          nhập</a>
        <a href="<?= BASE_URL ?>/auth/register"
          class="bg-purple-600 text-white px-4 py-1 rounded font-semibold hover:bg-purple-700 transition">Đăng
          ký</a>
      <?php endif; ?>
    </div>
  </header>

  <!-- Banner quảng cáo giống Zing MP3 Plus -->
  <div class="w-7xl flex justify-center py-4">
    <div class="w-full rounded-xl bg-[#a259f7] flex items-center justify-between px-8 py-6 relative overflow-hidden"
      style="min-height:100px;">
      <!-- Một vài nốt nhạc SVG động ở các vị trí khác nhau -->
      <div class="absolute left-8 bottom-4 z-20 animate-bounce">
        <svg width="28" height="28" viewBox="0 0 32 32" fill="none">
          <path d="M12 28a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-4V8l12-3v13" stroke="#fff" stroke-width="2" fill="none" />
          <circle cx="24" cy="20" r="3" fill="#a259f7" />
        </svg>
      </div>
      <div class="absolute left-20 top-6 z-20 animate-bounce" style="animation-delay:0.3s;">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M7 18a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-2V4l8-2v10" stroke="#fff" stroke-width="1.5" fill="none" />
          <circle cx="15" cy="12" r="1.5" fill="#fff" />
        </svg>
      </div>
      <div class="absolute right-36 top-8 z-20 animate-bounce" style="animation-delay:0.6s;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M10 22a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0-3V6l10-2v12" stroke="#fff" stroke-width="1.5" fill="none" />
          <circle cx="20" cy="16" r="2" fill="#a259f7" />
        </svg>
      </div>
      <!-- Hình ảnh nhạc ngẫu nhiên lấy từ mạng, thay đổi mỗi lần tải trang -->
      <?php
      $musicImages = [
        'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
      ];
      $randImg = $musicImages[array_rand($musicImages)];
      ?>
      <img src="<?= $randImg ?>" alt="Music random"
        class="absolute right-1/2  top-3/2 w-36 h-36 object-cover rounded-full shadow-lg border-4 border-white opacity-80 z-10" />
      <!-- Đĩa nhạc quay -->
      <div class="absolute right-1/2 bottom-4 z-20 animate-spin-slow">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
          <circle cx="20" cy="20" r="18" stroke="#fff" stroke-width="3" fill="#a259f7" />
          <circle cx="20" cy="20" r="6" fill="#fff" />
          <circle cx="20" cy="20" r="2" fill="#a259f7" />
        </svg>
      </div>
      <!-- Sóng nhạc động -->
      <div class="absolute left-1/4 -translate-x-2 top-16 z-20">
        <svg width="80" height="24" viewBox="0 0 80 24" fill="none">
          <rect x="0" y="10" width="6" height="14" rx="3" fill="#fff">
            <animate attributeName="height" values="14;22;14" dur="1s" repeatCount="indefinite" />
            <animate attributeName="y" values="10;2;10" dur="1s" repeatCount="indefinite" />
          </rect>
          <rect x="12" y="6" width="6" height="18" rx="3" fill="#ef4444">
            <animate attributeName="height" values="18;8;18" dur="1s" repeatCount="indefinite" />
            <animate attributeName="y" values="6;16;6" dur="1s" repeatCount="indefinite" />
          </rect>
          <rect x="24" y="12" width="6" height="12" rx="3" fill="#fff">
            <animate attributeName="height" values="12;20;12" dur="1s" repeatCount="indefinite" />
            <animate attributeName="y" values="12;4;12" dur="1s" repeatCount="indefinite" />
          </rect>
          <rect x="36" y="8" width="6" height="16" rx="3" fill="#ef4444">
            <animate attributeName="height" values="16;6;16" dur="1s" repeatCount="indefinite" />
            <animate attributeName="y" values="8;18;8" dur="1s" repeatCount="indefinite" />
          </rect>
          <rect x="48" y="10" width="6" height="14" rx="3" fill="#fff">
            <animate attributeName="height" values="14;22;14" dur="1s" repeatCount="indefinite" />
            <animate attributeName="y" values="10;2;10" dur="1s" repeatCount="indefinite" />
          </rect>
          <rect x="60" y="6" width="6" height="18" rx="3" fill="#ef4444">
            <animate attributeName="height" values="18;8;18" dur="1s" repeatCount="indefinite" />
            <animate attributeName="y" values="6;16;6" dur="1s" repeatCount="indefinite" />
          </rect>
          <rect x="72" y="12" width="6" height="12" rx="3" fill="#fff">
            <animate attributeName="height" values="12;20;12" dur="1s" repeatCount="indefinite" />
            <animate attributeName="y" values="12;4;12" dur="1s" repeatCount="indefinite" />
          </rect>
        </svg>
      </div>
      <style>
        .animate-spin-slow {
          animation: spin 3s linear infinite;
        }

        @keyframes spin {
          100% {
            transform: rotate(360deg);
          }
        }
      </style>
      <div>
        <div class="uppercase text-white font-semibold text-sm mb-1">MusicOfMinh <span
            class="bg-white text-[#a259f7] text-xs font-bold px-2 py-0.5 rounded ml-1">PLUS</span></div>
        <div class="text-yellow-300 font-extrabold text-2xl leading-tight mb-2">Tận hưởng âm nhạc<br>không quảng
          cáo</div>
      </div>
      <div class="flex flex-col items-center">
        <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mb-2">
          <svg width="36" height="36" fill="none" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" fill="#a259f7" /><text x="12" y="16" text-anchor="middle" fill="#fff"
              font-size="12" font-family="Arial" font-weight="bold">AD</text>
            <line x1="16" y1="8" x2="8" y2="16" stroke="red" stroke-width="2" />
          </svg>
        </div>
        <div class="bg-[#5f2eea] text-white font-bold px-4 py-1 rounded-lg shadow text-lg">13K/THÁNG</div>
      </div>
      <!-- Sóng nhạc trang trí -->
      <svg class="absolute right-0 top-0 h-full opacity-20" width="200" height="100" viewBox="0 0 200 100" fill="none">
        <path d="M0 80 Q50 20 100 80 T200 80" stroke="#fff" stroke-width="4" fill="none" />
      </svg>
    </div>
  </div>

  <!-- Main Content -->
  <main class="w-full mx-auto mt-2">
    <!-- Nghệ sĩ nổi bật full width -->
    <?php
    // Mock data nghệ sĩ nổi bật
    $featured_artists = [
      ["name" => "Sơn Tùng M-TP", "img" => "https://i.pravatar.cc/80?img=1"],
      ["name" => "Đen Vâu", "img" => "https://i.pravatar.cc/80?img=2"],
      ["name" => "Mỹ Tâm", "img" => "https://i.pravatar.cc/80?img=3"],
      ["name" => "Jack", "img" => "https://i.pravatar.cc/80?img=4"],
      ["name" => "Hòa Minzy", "img" => "https://i.pravatar.cc/80?img=5"],
      ["name" => "Noo Phước Thịnh", "img" => "https://i.pravatar.cc/80?img=6"],
      ["name" => "Bích Phương", "img" => "https://i.pravatar.cc/80?img=7"],
      ["name" => "Duy mạnh", "img" => "https://i.pravatar.cc/80?img=8"],
      ["name" => "Thái hoàng", "img" => "https://i.pravatar.cc/80?img=9"],
      ["name" => "JustaTee", "img" => "https://i.pravatar.cc/80?img=10"],
      ["name" => "Phương Ly", "img" => "https://i.pravatar.cc/80?img=11"],
      ["name" => "Trúc Nhân", "img" => "https://i.pravatar.cc/80?img=12"],
      ["name" => "Vũ Cát Tường", "img" => "https://i.pravatar.cc/80?img=13"],
      ["name" => "Isaac", "img" => "https://i.pravatar.cc/80?img=14"],
      ["name" => "Khởi My", "img" => "https://i.pravatar.cc/80?img=15"],
    ];
    ?>
    <section class="w-full mx-auto px-0 mb-8">
      <h2 class="text-lg font-bold mb-2 text-purple-700 pl-8">Nghệ sĩ nổi bật</h2>
      <div class="flex gap-4 overflow-x-auto pb-2 px-8 featured-artists-scroll scrollbar-hide relative">
        <?php foreach ($featured_artists as $artist): ?>
          <a href="<?= BASE_URL ?>/song/searchSongs?keyword=<?= urlencode($artist['name']) ?>"
            class="flex flex-col items-center min-w-[100px] hover:scale-105 hover:z-30 duration-200 transition cursor-pointer">
            <img src="<?= htmlspecialchars($artist['img']) ?>"
              class="rounded-full w-16 h-16 border-2 border-purple-400" />
            <div class="text-xs mt-1 text-purple-700 hover:underline">
              <?= htmlspecialchars($artist['name']) ?>
            </div>
          </a>
        <?php endforeach; ?>

      </div>
    </section>

    <!-- Danh sách thể loại -->
    <?php
    // Mock data thể loại
    $genres = [
      "Pop",
      "Ballad",
      "Rap",
      "EDM",
      "Rock",
      "Indie",
      "Kpop",
      "US-UK",
      "Nhạc vàng",
      "R&B",
      "Jazz",
      "Blues",
      "Classical",
      "Dance",
      "Remix",
      "Acoustic",
      "Lo-fi",
      "Rock Ballad",
      "Latin",
      "Country",
      "Funk",
      "Soul",
      "Metal",
      "Opera"
    ];
    ?>
    <section class="w-full mx-auto px-0 mb-8">
      <h2 class="text-lg font-bold mb-2 text-purple-700 pl-8">Thể loại</h2>
      <div class="flex gap-3 overflow-x-auto pb-4 px-8 genre-scroll scrollbar-hide relative items-center "
        style="min-height:56px;">
        <?php foreach ($genres as $genre): ?>
          <a href="<?= BASE_URL ?>/song/searchSongs?keyword=<?= urlencode($genre) ?>"
            class=" min-w-[100px] hover:scale-105 hover:z-30 duration-200 transition cursor-pointer">

            <span
              class="bg-purple-100 mr-6 text-purple-700 px-3 py-1 rounded-full text-md whitespace-nowrap hover:scale-105 hover:z-30 duration-200 transition cursor-pointer hover:bg-[#a259f7] hover:text-white"><?= htmlspecialchars($genre) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Danh sách bài hát mới nhất -->
    <section class="w-full mx-auto mb-8 px-0 pl-8 pr-8 mb-16">
      <div class="flex justify-between items-center mb-4 px-0">
        <h2 class="text-lg font-bold text-purple-700">Bài hát mới nhất</h2>
        <div class="flex items-center gap-3">
          <a href="<?= BASE_URL ?>/song/searchSongs" class="text-purple-600 hover:underline text-sm">Xem tất cả</a>
          <button onclick="refreshSection('newest')"
            class="flex items-center justify-center bg-purple-100 hover:bg-purple-600 text-purple-700 hover:text-white w-10 h-10 rounded-full transition shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                d="M17.65 6.35A7.95 7.95 0 0 0 12 4V1L7 6l5 5V7c1.78 0 3.42.77 4.65 2.02A6.978 6.978 0 0 1 19 12c0 3.87-3.13 7-7 7a7 7 0 0 1-6.93-6H3.02A9 9 0 1 0 21 12c0-2.21-.9-4.21-2.35-5.65z"
                fill="currentColor" />
            </svg>
          </button>
        </div>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
        <?php if (!empty($newestSongs)): ?>
          <?php foreach (array_slice($newestSongs, 0, 8) as $song): ?>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
              <?php
              $thumbnail = $song['thumbnail'] ?? '';
              $thumbnailUrl = !empty($thumbnail)
                ? BASE_URL . '/' . ltrim($thumbnail, '/')
                : 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=400&q=80';
              ?>
              <img src="<?= htmlspecialchars($thumbnailUrl) ?>" class="rounded-t-lg w-full aspect-square object-cover"
                onerror="handleImageError(this, '<?= htmlspecialchars($song['title'] ?? '') ?>', '<?= htmlspecialchars($song['artist'] ?? '') ?>', '<?= htmlspecialchars($song['genre'] ?? '') ?>')" />
              <div class="p-2 flex-1 flex flex-col justify-between">
                <div>
                  <div class="font-semibold text-sm text-gray-800 truncate" title="<?= htmlspecialchars($song['title']) ?>">
                    <?= htmlspecialchars($song['title']) ?>
                  </div>
                  <div class="text-xs text-gray-500 mt-1 truncate" title="<?= htmlspecialchars($song['artist']) ?>">
                    <?= htmlspecialchars($song['artist']) ?>
                  </div>
                </div>
                <a href="<?= BASE_URL ?>/song/play?id=<?= urlencode($song['id']) ?>&type=newest" target="_blank"
                  class="mt-2 bg-purple-600 text-white px-2 py-1 rounded text-center text-xs font-semibold hover:bg-purple-700 transition">Phát</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-full text-center text-gray-400 py-8">Chưa có bài hát nào.</div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Gợi Ý Bài Hát -->
    <section class="w-full mx-auto mb-16 px-0 pl-8 pr-8">
      <div class="flex justify-between items-center mb-4 px-0">
        <h2 class="text-lg font-bold text-purple-700">Gợi Ý Bài Hát</h2>
        <button onclick="refreshSection('suggested')"
          class="flex items-center justify-center bg-purple-100 hover:bg-purple-600 text-purple-700 hover:text-white w-10 h-10 rounded-full transition shadow-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path
              d="M17.65 6.35A7.95 7.95 0 0 0 12 4V1L7 6l5 5V7c1.78 0 3.42.77 4.65 2.02A6.978 6.978 0 0 1 19 12c0 3.87-3.13 7-7 7a7 7 0 0 1-6.93-6H3.02A9 9 0 1 0 21 12c0-2.21-.9-4.21-2.35-5.65z"
              fill="currentColor" />
          </svg>
        </button>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 px-0">
        <?php if (!empty($randomSongs)): ?>
          <?php foreach ($randomSongs as $song): ?>
            <a href="<?= BASE_URL ?>/song/play?id=<?= $song['id'] ?>&type=random" target="_blank">
              <div
                class="flex items-center rounded-xl p-3 gap-3 shadow transition group relative overflow-hidden hover:scale-105 hover:z-30 duration-200 cursor-pointer">
                <div class="relative">
                  <?php
                  $thumbnail = $song['thumbnail'] ?? '';
                  $thumbnailUrl = !empty($thumbnail)
                    ? BASE_URL . '/' . ltrim($thumbnail, '/')
                    : 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=400&q=80';
                  ?>
                  <img src="<?= htmlspecialchars($thumbnailUrl) ?>" class="w-14 h-14 rounded-lg object-cover"
                    onerror="handleImageError(this, '<?= htmlspecialchars($song['title'] ?? '') ?>', '<?= htmlspecialchars($song['artist'] ?? '') ?>', '<?= htmlspecialchars($song['genre'] ?? '') ?>')" />
                  <div
                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-200 flex items-center justify-center">
                    <button class="opacity-0 group-hover:opacity-100 transition duration-200">
                      <svg class="w-8 h-8 text-white bg-black bg-opacity-60 rounded-full p-1" fill="currentColor"
                        viewBox="0 0 20 20">
                        <polygon points="6,4 17,10 6,16" />
                      </svg>
                    </button>
                  </div>
                </div>
                <div class="flex-1">
                  <div
                    class="font-semibold text-[#a259f7] truncate<?= !empty($song['premium']) ? ' flex items-center' : '' ?>">
                    <?= htmlspecialchars($song['title']) ?>
                    <?php if (!empty($song['premium'])): ?>
                      <span class="ml-2 bg-yellow-400 text-xs text-white font-bold px-2 py-0.5 rounded">PREMIUM</span>
                    <?php endif; ?>
                  </div>
                  <div class="text-xs text-[#c084fc] truncate"><?= htmlspecialchars($song['artist']) ?></div>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-full text-center text-gray-400 py-8">Chưa có bài hát gợi ý.</div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Top bài hát -->
    <section class="w-full mx-auto mb-8 px-0 pl-8 pr-8 mb-16">
      <div class="flex justify-between items-center mb-4 px-0">
        <h2 class="text-lg font-bold text-purple-700">Top bài hát</h2>
        <button onclick="refreshSection('top')"
          class="flex items-center justify-center bg-purple-100 hover:bg-purple-600 text-purple-700 hover:text-white w-10 h-10 rounded-full transition shadow-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path
              d="M17.65 6.35A7.95 7.95 0 0 0 12 4V1L7 6l5 5V7c1.78 0 3.42.77 4.65 2.02A6.978 6.978 0 0 1 19 12c0 3.87-3.13 7-7 7a7 7 0 0 1-6.93-6H3.02A9 9 0 1 0 21 12c0-2.21-.9-4.21-2.35-5.65z"
              fill="currentColor" />
          </svg>
        </button>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 px-0">
        <?php if (!empty($topSongs)): ?>
          <?php foreach ($topSongs as $song): ?>
            <a href="<?= BASE_URL ?>/song/play?id=<?= $song['id'] ?>&type=top" target="_blank">
              <div
                class="flex items-center rounded-xl p-3 gap-3 shadow transition group relative overflow-hidden hover:scale-105 hover:z-30 duration-200 cursor-pointer">
                <div class="relative">
                  <?php
                  $thumbnail = $song['thumbnail'] ?? '';
                  $thumbnailUrl = !empty($thumbnail)
                    ? BASE_URL . '/' . ltrim($thumbnail, '/')
                    : 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=400&q=80';
                  ?>
                  <img src="<?= htmlspecialchars($thumbnailUrl) ?>" class="w-14 h-14 rounded-lg object-cover"
                    onerror="handleImageError(this, '<?= htmlspecialchars($song['title'] ?? '') ?>', '<?= htmlspecialchars($song['artist'] ?? '') ?>', '<?= htmlspecialchars($song['genre'] ?? '') ?>')" />
                  <div
                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-200 flex items-center justify-center">
                    <button onclick="playSong(<?= $song['id'] ?>)"
                      class="opacity-0 group-hover:opacity-100 transition duration-200">
                      <svg class="w-8 h-8 text-white bg-black bg-opacity-60 rounded-full p-1" fill="currentColor"
                        viewBox="0 0 20 20">
                        <polygon points="6,4 17,10 6,16" />
                      </svg>
                    </button>
                  </div>
                </div>
                <div class="flex-1">
                  <div
                    class="font-semibold text-[#a259f7] truncate<?= !empty($song['premium']) ? ' flex items-center' : '' ?>">
                    <?= htmlspecialchars($song['title']) ?>
                    <?php if (!empty($song['premium'])): ?>
                      <span class="ml-2 bg-yellow-400 text-xs text-white font-bold px-2 py-0.5 rounded">PREMIUM</span>
                    <?php endif; ?>
                  </div>
                  <div class="text-xs text-[#c084fc] truncate"><?= htmlspecialchars($song['artist']) ?></div>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-full text-center text-gray-400 py-8">Chưa có dữ liệu top bài hát.</div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Bài Hát yêu thích -->
    <section class="w-full mx-auto mb-8 px-0 pl-8 pr-8 mb-16 mt-16">
      <div class="flex justify-between items-center mb-4 px-0">
        <h2 class="text-lg font-bold text-purple-700">Bài Hát yêu thích</h2>
        <a href="<?= BASE_URL ?>/song/searchSongs" class="text-purple-600 hover:underline text-sm">Xem tất cả</a>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
        <?php if (!empty($favoriteSongs)): ?>
          <?php foreach ($favoriteSongs as $song): ?>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
              <?php
              $thumbnail = $song['thumbnail'] ?? '';
              $thumbnailUrl = !empty($thumbnail)
                ? BASE_URL . '/' . ltrim($thumbnail, '/')
                : 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=400&q=80';
              ?>
              <img src="<?= htmlspecialchars($thumbnailUrl) ?>" class="rounded-t-lg w-full aspect-square object-cover"
                onerror="handleImageError(this, '<?= htmlspecialchars($song['title'] ?? '') ?>', '<?= htmlspecialchars($song['artist'] ?? '') ?>', '<?= htmlspecialchars($song['genre'] ?? '') ?>')" />
              <div class="p-3 flex-1 flex flex-col justify-between">
                <div>
                  <div class="font-semibold text-md text-gray-800 truncate" title="<?= htmlspecialchars($song['title']) ?>">
                    <?= htmlspecialchars($song['title']) ?>
                  </div>
                  <div class="text-xs text-gray-500 mt-1 truncate" title="<?= htmlspecialchars($song['artist']) ?>">
                    <?= htmlspecialchars($song['artist']) ?>
                  </div>
                </div>
                <a href="<?= BASE_URL ?>/song/play?id=<?= urlencode($song['id']) ?>&type=favorite" target="_blank"
                  class="mt-3 bg-purple-600 text-white px-3 py-1 rounded text-center font-semibold hover:bg-purple-700 transition">Phát</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-full text-center text-gray-400 py-8">
            <?php if (isset($_SESSION['user']['id'])): ?>
              Bạn chưa có bài hát yêu thích nào.
            <?php else: ?>
              <a href="<?= BASE_URL ?>/auth/login"
                class="bg-purple-600 text-white px-4 py-1 rounded font-semibold hover:bg-purple-700 transition">Đăng
                nhập</a> để xem bài hát yêu
              thích.
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
    <!-- Bài Hát của tôi -->
    <section class="w-full mx-auto mb-8 px-0 pl-8 pr-8 mb-16 mt-16">
      <div class="flex justify-between items-center mb-4 px-0">
        <h2 class="text-lg font-bold text-purple-700">Bài Hát của tôi</h2>
        <div class="flex items-center gap-3">
          <a href="<?= BASE_URL ?>/song/mySongs" class="text-purple-600 hover:underline text-sm">Xem tất cả</a>
          <a href="<?= BASE_URL ?>/song/addSong?return_url=<?= urlencode(BASE_URL . '/song/manageSongs') ?>"
            class="bg-purple-600 text-white px-4 py-1 rounded font-semibold hover:bg-purple-700 transition">Thêm
            bài hát</a>
        </div>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
        <?php if (!empty($mySongs)): ?>
          <?php foreach ($mySongs as $song): ?>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
              <?php
              $thumbnail = $song['thumbnail'] ?? '';
              $thumbnailUrl = !empty($thumbnail)
                ? BASE_URL . '/' . ltrim($thumbnail, '/')
                : 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=400&q=80';
              ?>
              <img src="<?= htmlspecialchars($thumbnailUrl) ?>" class="rounded-t-lg w-full aspect-square object-cover"
                onerror="handleImageError(this, '<?= htmlspecialchars($song['title'] ?? '') ?>', '<?= htmlspecialchars($song['artist'] ?? '') ?>', '<?= htmlspecialchars($song['genre'] ?? '') ?>')" />
              <div class="p-3 flex-1 flex flex-col justify-between">
                <div>
                  <div class="font-semibold text-md text-gray-800 truncate" title="<?= htmlspecialchars($song['title']) ?>">
                    <?= htmlspecialchars($song['title']) ?>
                  </div>
                  <div class="text-xs text-gray-500 mt-1 truncate" title="<?= htmlspecialchars($song['artist']) ?>">
                    <?= htmlspecialchars($song['artist']) ?>
                  </div>
                </div>
                <a href="<?= BASE_URL ?>/song/play?id=<?= urlencode($song['id']) ?>&type=favorite" target="_blank"
                  class="mt-3 bg-purple-600 text-white px-3 py-1 rounded text-center font-semibold hover:bg-purple-700 transition">Phát</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-full text-center text-gray-400 py-8">
            <?php if (isset($_SESSION['user']['id'])): ?>
              Bạn chưa có bài hát nào.
            <?php else: ?>
              <a href="<?= BASE_URL ?>/auth/login"
                class="bg-purple-600 text-white px-4 py-1 rounded font-semibold hover:bg-purple-700 transition">Đăng
                nhập</a> để xem bài hát của bạn.
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer class="mt-12 py-4 text-center text-xs text-gray-400">
    &copy; <?= date('Y') ?> MusicOfMinh. All rights reserved.
  </footer>
  <style>
    /* Hide horizontal scrollbar */
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    /* Hide horizontal scrollbar for genre */
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</body>

</html>