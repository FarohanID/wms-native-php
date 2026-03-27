<?php
// Mengambil URL yang diketik user
$url = isset($_GET['url']) ? $_GET['url'] : 'dashboard';

echo "<h1>WMS System</h1>";
echo "Anda sedang mencoba membuka halaman: <strong>" . htmlspecialchars($url) . "</strong>";

// Nanti di sini kita akan panggil file dari folder app/views/