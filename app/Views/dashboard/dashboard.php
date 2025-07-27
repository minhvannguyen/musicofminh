<!DOCTYPE html>
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

<body class="bg-gray-200 min-h-screen">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-xl z-50">
        <div class="flex items-center justify-center h-16 bg-white">
            <div class="flex items-center mr-9 mt-3">
                <div class="flex items-center justify-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/727/727245.png" alt="Logo" class="w-12 h-12">
                </div>
                <span class="font-bold text-2xl text-purple-700 ml-2">MusicOfMinh</span>
            </div>
        </div>

        <nav class="mt-8 px-4">
            <div class="space-y-2">
                <a href="#"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-home mr-3 text-cordes-accent group-hover:text-white text-purple-700"></i>
                    Trang chủ
                </a>
                <a href="<?= BASE_URL ?>/admin/manageUsers?return_url=<?= urlencode( BASE_URL . '/admin/dashboard') ?>"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-users mr-3 text-gray-400 group-hover:text-white text-purple-700"></i>
                    Người dùng
                </a>
                <a href="<?= BASE_URL ?>/song/manageSongs?return_url=<?= urlencode( BASE_URL . '/admin/dashboard') ?>"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-music mr-3 text-gray-400 group-hover:text-white text-purple-700"></i>
                    Bài hát
                </a>
                <a href="#"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-box mr-3 text-gray-400 group-hover:text-white text-purple-700"></i>
                    Bộ siêu tập<nav></nav>
                </a>
                <a href="#"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-white text-purple-700"></i>
                    Phân tích
                </a>
                <a href="#"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-white text-purple-700"></i>
                    Cài đặt
                </a>
            </div>
        </nav>

        <div class="absolute bottom-4 left-4 right-4">
            <div class="bg-gray-800 rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/17003/17003310.png" alt="Admin"
                        class="w-10 h-10 rounded-full">
                    <div>
                        <p class="text-white text-sm font-medium"><?= $_SESSION['user']['name'] ?></p>
                        <p class="text-gray-400 text-xs">Administrator</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64">
        <!-- Top Header -->
        <header class="bg-white shadow-sm rounded-xl border-b border-gray-200 m-6">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between ">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Chào ông chủ <?= strtoupper($_SESSION['user']['name']) ?>
                        </h1>
                        <p class="text-gray-600 text-sm mt-1">chúc ngài một ngày tốt lành ạ!</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <i
                                class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 "></i>
                            <input type="text" placeholder="Search..."
                                class="pl-10 pr-4 py-2 border border-gray-500 rounded-lg focus:ring-2 focus:ring-cordes-accent focus:border-transparent outline-none">
                        </div>
                        <div class="relative">
                            <button
                                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-bell text-2xl text-yellow-500"></i>
                                <span
                                    class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                            </button>
                        </div>
                        <a href="<?= BASE_URL ?>/auth/logout" title="Đăng xuất"
                           class="p-2 text-gray-600 hover:text-white hover:bg-red-500 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Dashboard Content -->
        <main class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Revenue Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tổng số bài hát</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?= $totalSongs ?></p>
                            <div class="flex items-center mt-2">
                                <span class="text-green-600 text-sm font-medium flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <?= $totalNewestSongs ?> bài hát
                                </span>
                                <span class="text-gray-500 text-sm ml-2">tải lên hôm nay.</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-cordes-blue bg-opacity-10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-music text-cordes-blue text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Users Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tổng số người dùng</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?= $totalUsers ?></p>
                            <div class="flex items-center mt-2">
                                <span class="text-green-600 text-md font-bold mr-1">+</span>
                                <span class="text-green-600 text-sm font-medium flex items-center">
                                    <?= $totalNewestUsers ?> Người
                                    <i class="fas fa-user mr-1 ml-1"></i>
                                </span>
                                
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tổng số lượt nghe</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?= $totalViews ?></p>
                            <div class="flex items-center mt-2">
                                <span class="text-green-600 text-sm font-medium flex items-center">
                                <i class="fas fa-user mr-1"></i>
                                    <?= $onlineCount ?> người
                                </span>
                                <span class="text-gray-500 text-sm ml-2">... đang truy cập.</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-headphones text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tổng số lượt thích</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?= $totalLikes ?></p>
                            <div class="flex items-center mt-2">
                                <span class="text-green-600 text-sm font-medium flex items-center">
                                    <i class="fas fa-heart mr-1"></i>
                                    <?= $totalNewestLikes ?> lượt thích
                                </span>
                                <span class="text-gray-500 text-sm ml-2"> mới.</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heart text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->

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
      <style>
  .featured-artists-scroll::-webkit-scrollbar {
    display: none;
  }

  .featured-artists-scroll {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
  }
</style>

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
        <style>
    .genre-scroll::-webkit-scrollbar {
      display: none;
    }
  </style>
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

            <!-- Bottom Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hoạt động diễn ra gần đây</h3>
                        <button class="text-cordes-blue hover:text-cordes-dark text-sm font-medium">Xem tất cả</button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">Người dùng mới đã đăng ký</p>
                                <p class="text-xs text-gray-500">Quangnguyen@email.com • 2 minutes ago</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">tìm kiếm bài hát </p>
                                <p class="text-xs text-gray-500">"phố đêm" • 5 minutes ago</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">Bài hát đã được phát</p>
                                <p class="text-xs text-gray-500"> Hẹn em kiếp sau • 8 minutes ago</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">Người dùng mới truy cập</p>
                                <p class="text-xs text-gray-500">@name: minh @id: 1767 • 12 minutes ago</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-900">Server Status</span>
                            </div>
                            <span class="text-sm text-green-600 font-medium">Online</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-900">Database</span>
                            </div>
                            <span class="text-sm text-green-600 font-medium">Active</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm text-gray-900">API Status</span>
                            </div>
                            <span class="text-sm text-yellow-600 font-medium">Warning</span>
                        </div>
                        <div class="mt-6">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Server Load</span>
                                <span>38%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-cordes-blue h-2 rounded-full" style="width: 38%"></div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Disk Usage</span>
                                <span>68%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-cordes-blue h-2 rounded-full" style="width: 68%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize Chart.js with Cordes styling
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [25000, 32000, 28000, 35000, 42000, 48000],
                    borderColor: '#1e40af',
                    backgroundColor: 'rgba(30, 64, 175, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#1e40af',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            color: '#6b7280',
                            callback: function (value) {
                                return ' + value.toLocaleString();
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 8
                    }
                }
            }
        });

        // Add some interactive functionality
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar navigation active state
            const navLinks = document.querySelectorAll('nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    navLinks.forEach(l => l.classList.remove('bg-gray-700', 'text-white'));
                    navLinks.forEach(l => l.classList.add('text-gray-300'));
                    this.classList.add('bg-gray-700', 'text-white');
                    this.classList.remove('text-gray-300');
                });
            });

            // Set dashboard as active by default
            navLinks[0].classList.add('bg-gray-700', 'text-white');
            navLinks[0].classList.remove('text-gray-300');

            // Notification bell animation
            const bellIcon = document.querySelector('.fa-bell');
            if (bellIcon) {
                setInterval(() => {
                    bellIcon.classList.add('animate-pulse');
                    setTimeout(() => {
                        bellIcon.classList.remove('animate-pulse');
                    }, 1000);
                }, 5000);
            }

            // Stats cards hover effects
            const statsCards = document.querySelectorAll('.hover\\:shadow-md');
            statsCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>

</html>