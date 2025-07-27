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
                    <i class="fas fa-home mr-3 text-cordes-accent group-hover:text-white text-yellow-500"></i>
                    Trang chủ
                </a>
                <a href="<?= BASE_URL ?>/admin/manageUsers?return_url=<?= urlencode( BASE_URL . '/admin/dashboard') ?>"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-users mr-3 text-gray-400 group-hover:text-white text-yellow-500"></i>
                    Người dùng
                </a>
                <a href="<?= BASE_URL ?>/song/manageSongs?return_url=<?= urlencode( BASE_URL . '/admin/dashboard') ?>"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-music mr-3 text-gray-400 group-hover:text-white text-yellow-500"></i>
                    Bài hát
                </a>
                <a href="#"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-box mr-3 text-gray-400 group-hover:text-white text-yellow-500"></i>
                    Bộ siêu tập<nav></nav>
                </a>
                <a href="#"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-white text-yellow-500"></i>
                    Phân tích
                </a>
                <a href="#"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-white text-yellow-500"></i>
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
                        <p class="text-white text-sm font-medium">John Admin</p>
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
                        <h1 class="text-2xl font-semibold text-gray-900">Chào ông chủ Minh</h1>
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
                            <p class="text-3xl font-bold text-gray-900 mt-2">48,291</p>
                            <div class="flex items-center mt-2">
                                <span class="text-green-600 text-sm font-medium flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    12 bài hát
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
                            <p class="text-3xl font-bold text-gray-900 mt-2">15,847</p>
                            <div class="flex items-center mt-2">
                                <span class="text-green-600 text-sm font-medium flex items-center">
                                    <i class="fas fa-user mr-1"></i>
                                    101 người
                                </span>
                                <span class="text-gray-500 text-sm ml-2">... đang truy cập.</span>
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
                            <p class="text-3xl font-bold text-gray-900 mt-2">299,847</p>
                            <div class="flex items-center mt-2">
                                <span class="text-green-600 text-sm font-medium flex items-center">
                                    <i class="fas fa-headphones-alt mr-1"></i>
                                    15 bài hát
                                </span>
                                <span class="text-gray-500 text-sm ml-2"> ...đang phát.</span>
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
                            <p class="text-sm font-medium text-gray-600">Bộ siêu tập</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">1,247</p>
                            <div class="flex items-center mt-2">
                                <span class="text-green-600 text-sm font-medium flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    5 bộ
                                </span>
                                <span class="text-gray-500 text-sm ml-2">tạo mới hôm nay.</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->

                <div class="flex justify-between mb-16  ">
                    <!-- Top Products -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 w-96">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">BXH 100 bài hát</h3>
                            <button class="text-cordes-blue hover:text-cordes-dark text-sm font-medium">Xem tất cả</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">iPhone 15 Pro</p>
                                    <p class="text-sm text-gray-600">Electronics</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$1,299</p>
                                    <p class="text-sm text-green-600">+12%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">MacBook Pro</p>
                                    <p class="text-sm text-gray-600">Computers</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$2,499</p>
                                    <p class="text-sm text-green-600">+8%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">AirPods Pro</p>
                                    <p class="text-sm text-gray-600">Audio</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$249</p>
                                    <p class="text-sm text-green-600">+15%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Apple Watch</p>
                                    <p class="text-sm text-gray-600">Wearables</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$399</p>
                                    <p class="text-sm text-green-600">+6%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Products -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 w-96">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">BXH 100 ca sĩ</h3>
                            <button class="text-cordes-blue hover:text-cordes-dark text-sm font-medium">Xem
                                tất cả</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">iPhone 15 Pro</p>
                                    <p class="text-sm text-gray-600">Electronics</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$1,299</p>
                                    <p class="text-sm text-green-600">+12%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">MacBook Pro</p>
                                    <p class="text-sm text-gray-600">Computers</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$2,499</p>
                                    <p class="text-sm text-green-600">+8%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">AirPods Pro</p>
                                    <p class="text-sm text-gray-600">Audio</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$249</p>
                                    <p class="text-sm text-green-600">+15%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Apple Watch</p>
                                    <p class="text-sm text-gray-600">Wearables</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$399</p>
                                    <p class="text-sm text-green-600">+6%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Products -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 w-96">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">BXH 100 bộ sưu tập</h3>
                            <button class="text-cordes-blue hover:text-cordes-dark text-sm font-medium">Xem tất cả</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">iPhone 15 Pro</p>
                                    <p class="text-sm text-gray-600">Electronics</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$1,299</p>
                                    <p class="text-sm text-green-600">+12%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">MacBook Pro</p>
                                    <p class="text-sm text-gray-600">Computers</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$2,499</p>
                                    <p class="text-sm text-green-600">+8%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">AirPods Pro</p>
                                    <p class="text-sm text-gray-600">Audio</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$249</p>
                                    <p class="text-sm text-green-600">+15%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <img src="https://i.ytimg.com/vi/08JoSCHV9VY/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAC7M1K4hMJbmFQQFppnSNzsZAcRA"
                                    alt="Product" class="w-12 h-12 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Apple Watch</p>
                                    <p class="text-sm text-gray-600">Wearables</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">$399</p>
                                    <p class="text-sm text-green-600">+6%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
            // Thêm script toggle menu thả xuống cho Cài đặt
            var btn = document.getElementById('settingsDropdownBtn');
            var menu = document.getElementById('settingsDropdownMenu');
            if (btn && menu) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    menu.classList.toggle('hidden');
                });
                document.addEventListener('click', function (e) {
                    if (!btn.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>

</html>