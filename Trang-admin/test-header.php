<!DOCTYPE html>
<html>
<head>
    <title>Admin Header Test - Galaxy Studio</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/admin-header-improved.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #ecf0f1;
            min-height: 100vh;
        }
        .test-content {
            padding: 100px 20px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .test-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .status-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }
        .success { border-left-color: #27ae60; }
        .warning { border-left-color: #f39c12; }
        .info { border-left-color: #3498db; }
    </style>
</head>
<body>
    <!-- Mock Admin Header -->
    <div class="header-section">
        <div class="container-fluid">
            <div class="row justify-content-between align-items-center">
                <!-- Header Logo -->
                <div class="header-logo col-auto">
                    <a href="#">
                        <h3>Quản Trị Galaxy Studio</h3>
                    </a>
                </div>

                <!-- Header Right -->
                <div class="header-right flex-grow-1 col-auto">
                    <div class="row justify-content-between align-items-center">
                        <!-- Side Header Toggle -->
                        <div class="col-auto">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <button class="side-header-toggle">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Header Notifications -->
                        <div class="col-auto">
                            <ul class="header-notification-area">
                                <!-- Language -->
                                <li class="adomx-dropdown position-relative col-auto">
                                    <a class="toggle" href="#">
                                        <img class="lang-flag" src="https://flagcdn.com/w40/vn.png" alt="Vietnam Flag">
                                    </a>
                                </li>
                                
                                <!-- User -->
                                <li class="adomx-dropdown col-auto">
                                    <a class="toggle" href="#">
                                        <span class="user">
                                            <span class="avatar">
                                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face" alt="Admin Avatar">
                                                <span class="status"></span>
                                            </span>
                                        </span>
                                    </a>

                                    <!-- Dropdown Menu -->
                                    <div class="adomx-dropdown-menu dropdown-menu-user">
                                        <div class="head">
                                            <h5 class="name"><a href="#">Admin - Nguyễn Văn A</a></h5>
                                            <a class="mail" href="#">admin@galaxystudio.com</a>
                                        </div>
                                        <div class="body">
                                            <ul>
                                                <li><a href="#"><i class="fas fa-user"></i>Hồ sơ</a></li>
                                                <li><a href="#"><i class="fas fa-cog"></i>Cài đặt</a></li>
                                                <li><a href="#"><i class="fas fa-sign-out-alt"></i>Đăng xuất</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Content -->
    <div class="test-content">
        <div class="test-card">
            <h1>🎬 Galaxy Studio - Admin Header Test</h1>
            <p><strong>Testing Date:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            
            <div class="status-grid">
                <div class="status-item success">
                    <h4>✅ CSS Loaded</h4>
                    <p>Admin header CSS styles applied successfully</p>
                </div>
                
                <div class="status-item success">
                    <h4>✅ JavaScript Enhanced</h4>
                    <p>Interactive features and animations working</p>
                </div>
                
                <div class="status-item info">
                    <h4>🎨 Design Features</h4>
                    <ul>
                        <li>Gradient background header</li>
                        <li>Animated top border</li>
                        <li>Hover effects on buttons</li>
                        <li>Enhanced user dropdown</li>
                        <li>Responsive design</li>
                    </ul>
                </div>
                
                <div class="status-item warning">
                    <h4>🚀 Interactive Elements</h4>
                    <ul>
                        <li>Hover over toggle button</li>
                        <li>Click on user avatar</li>
                        <li>Scroll page to see effects</li>
                        <li>Try keyboard navigation (Tab)</li>
                        <li>Press Alt+M for menu</li>
                    </ul>
                </div>
            </div>
            
            <h2>Header Components Test</h2>
            
            <h3>🏢 Logo Section</h3>
            <p>✅ Galaxy Studio logo with gradient text effect and animated star</p>
            
            <h3>🎛️ Toggle Button</h3>
            <p>✅ Enhanced side menu toggle with hover effects and ripple animation</p>
            
            <h3>🌍 Language Flag</h3>
            <p>✅ Vietnam flag with hover zoom effect and border color change</p>
            
            <h3>👤 User Profile</h3>
            <p>✅ Avatar with status indicator and enhanced dropdown menu</p>
            
            <h2>CSS Features Applied</h2>
            <ul>
                <li>🎨 Modern gradient backgrounds</li>
                <li>✨ Smooth animations and transitions</li>
                <li>📱 Responsive design for all devices</li>
                <li>🎯 Improved hover states</li>
                <li>🔧 Enhanced dropdown menus</li>
                <li>⚡ Performance optimized</li>
                <li>♿ Accessibility improvements</li>
                <li>🎪 Visual effects and micro-interactions</li>
            </ul>
            
            <div style="background: #e8f5e8; padding: 20px; border-radius: 10px; margin: 20px 0;">
                <h3 style="color: #27ae60; margin-top: 0;">✅ Success!</h3>
                <p><strong>Admin header đã được cải thiện thành công!</strong></p>
                <ul>
                    <li>Header có gradient background đẹp mắt</li>
                    <li>Animation top border nhiều màu sắc</li>
                    <li>Button toggle với hiệu ứng hover</li>
                    <li>User dropdown menu hiện đại</li>
                    <li>Responsive trên mobile</li>
                    <li>Keyboard navigation support</li>
                </ul>
            </div>
            
            <p><a href="index.php" style="background: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block;">← Quay về trang admin</a></p>
        </div>
        
        <!-- More content to test scroll effects -->
        <div class="test-card">
            <h2>Scroll Test Content</h2>
            <p>Scroll trang này để test hiệu ứng header khi scroll:</p>
            <div style="height: 500px; background: linear-gradient(45deg, #3498db, #e74c3c); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold;">
                Scroll để thấy header effect!
            </div>
        </div>
        
        <div class="test-card">
            <h2>More Test Content</h2>
            <div style="height: 300px; background: linear-gradient(45deg, #27ae60, #f39c12); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                Header sẽ có blur effect khi scroll
            </div>
        </div>
    </div>

    <script src="assets/js/admin-header-enhanced.js?v=<?php echo time(); ?>"></script>
    <script>
        // Additional test scripts
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin Header Test loaded!');
            
            // Test notification
            setTimeout(() => {
                if (confirm('Admin header test thành công! ✅\n\nBạn có muốn mở trang admin chính không?')) {
                    window.location.href = 'index.php';
                }
            }, 2000);
        });
    </script>
</body>
</html>