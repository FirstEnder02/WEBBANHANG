<?php
// ============================================================
// CHẨN ĐOÁN LỖI - Đặt file này vào thư mục webbanhang/
// Truy cập: http://localhost/webbanhang/check.php
// XOÁ FILE NÀY SAU KHI KIỂM TRA XONG!
// ============================================================
echo "<style>body{font-family:monospace;padding:20px} .ok{color:green} .err{color:red} .warn{color:orange}</style>";
echo "<h2>🔍 Chẩn đoán WEBBANHANG</h2>";

// 1. PHP version
echo "<h3>1. PHP</h3>";
echo "<span class='ok'>✅ PHP " . phpversion() . "</span><br>";

// 2. mod_rewrite
echo "<h3>2. mod_rewrite</h3>";
if (function_exists('apache_get_modules')) {
    if (in_array('mod_rewrite', apache_get_modules())) {
        echo "<span class='ok'>✅ mod_rewrite đã bật</span><br>";
    } else {
        echo "<span class='err'>❌ mod_rewrite CHƯA bật → Chạy: sudo a2enmod rewrite && sudo systemctl restart apache2</span><br>";
    }
} else {
    echo "<span class='warn'>⚠️ Không kiểm tra được mod_rewrite (không phải Apache hoặc chạy qua CGI)</span><br>";
}

// 3. .htaccess / AllowOverride
echo "<h3>3. .htaccess</h3>";
if (file_exists(__DIR__ . '/.htaccess')) {
    echo "<span class='ok'>✅ File .htaccess tồn tại</span><br>";
} else {
    echo "<span class='err'>❌ Không tìm thấy .htaccess trong " . __DIR__ . "</span><br>";
}

// 4. index.php
echo "<h3>4. index.php</h3>";
if (file_exists(__DIR__ . '/index.php')) {
    echo "<span class='ok'>✅ File index.php tồn tại</span><br>";
} else {
    echo "<span class='err'>❌ Không tìm thấy index.php</span><br>";
}

// 5. Cấu trúc thư mục app/
echo "<h3>5. Thư mục app/</h3>";
$dirs = ['app/config', 'app/controllers', 'app/models', 'app/views'];
foreach ($dirs as $d) {
    if (is_dir(__DIR__ . '/' . $d)) {
        echo "<span class='ok'>✅ $d/</span><br>";
    } else {
        echo "<span class='err'>❌ Thiếu $d/ → Kiểm tra lại cấu trúc thư mục</span><br>";
    }
}

// 6. Kết nối Database
echo "<h3>6. Database</h3>";
require_once __DIR__ . '/app/config/database.php';
try {
    $db = (new Database())->getConnection();
    echo "<span class='ok'>✅ Kết nối database thành công!</span><br>";
} catch (Exception $e) {
    echo "<span class='err'>❌ Lỗi kết nối DB: " . htmlspecialchars($e->getMessage()) . "</span><br>";
    echo "<span class='warn'>→ Kiểm tra lại host/port/username/password trong app/config/database.php</span><br>";
    echo "<span class='warn'>→ Hiện tại đang dùng port <strong>1024</strong> — thông thường MySQL dùng port <strong>3306</strong></span><br>";
}

// 7. Gợi ý
echo "<h3>7. Thông tin server</h3>";
echo "SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "<br>";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "<br>";
echo "PHP SAPI: " . php_sapi_name() . "<br>";
echo "Working dir: " . getcwd() . "<br>";

echo "<hr><p style='color:gray'>⚠️ XOÁ file check.php sau khi kiểm tra xong!</p>";
