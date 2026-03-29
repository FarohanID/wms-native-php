<?php
// 1. Inisialisasi Session (Wajib di paling atas)
session_start();

// Menampilkan error untuk mempermudah debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Namespace Dompdf
use Dompdf\Dompdf;

require_once '../app/config/database.php';
require_once '../app/models/ProductModel.php';
require_once '../app/models/OpnameModel.php';
require_once '../app/models/ExitModel.php';
require_once '../app/models/SalesModel.php';

$db = new Database();
$productModel = new ProductModel($db);
$opnameModel = new OpnameModel($db);
$exitModel = new ExitModel($db);
$salesModel = new SalesModel($db);

$url = isset($_GET['url']) ? $_GET['url'] : 'dashboard';

// --- LOGIKA AUTHENTICATION ---

// A. Proses Login
if ($url == 'auth/process') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $db->getHandler()->prepare($query);
        $stmt->execute([':username' => $user]);
        $userData = $stmt->fetch();

        if ($userData && password_verify($pass, $userData['password'])) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_name'] = $userData['full_name'];
            header("Location: index.php?url=dashboard");
        } else {
            header("Location: index.php?url=login&error=1");
        }
        exit;
    }
}

// B. Proses Logout
if ($url == 'auth/logout') {
    session_destroy();
    header("Location: index.php?url=login");
    exit;
}

// C. Auth Guard: Proteksi Halaman
$publicPages = ['login', 'auth/process'];
if (!isset($_SESSION['user_id']) && !in_array($url, $publicPages)) {
    // Jika belum login, paksa ke halaman login
    include '../app/views/auth/login.php';
    exit;
}

// D. Tampilkan Login Page jika URL-nya 'login'
if ($url == 'login') {
    include '../app/views/auth/login.php';
    exit;
}


// --- TAMPILAN HEADER & NAVIGASI (Hanya muncul jika sudah login) ---

echo "<h1>WMS - Warehouse Management System</h1><hr>";
echo "<nav>
        <a href='index.php?url=dashboard'>Dashboard</a> |
        <a href='index.php?url=inventory'>Inventory</a> | 
        <a href='index.php?url=sales/add'>Transaksi COD</a> | 
        <a href='index.php?url=reports/history'>Laporan Opname</a> |
        <a href='index.php?url=reports/exits'>Laporan Barang Keluar</a> |
        <a href='index.php?url=reports/sales'>Laporan COD</a> |
        <a href='index.php?url=auth/logout' style='color: red;' onclick='return confirm(\"Keluar dari sistem?\")'>Logout (".$_SESSION['user_name'].")</a>
      </nav><hr>";

// PROTEKSI MENU: Hanya Admin yang bisa lihat menu Manajemen User
        if ($_SESSION['role'] == 'admin') {
            echo " <a href='index.php?url=users' style='font-weight:bold; color:blue;'>[Manajemen User]</a> |";
        }

echo " <a href='index.php?url=auth/logout' style='color: red;'>Logout (".$_SESSION['user_name'].")</a>
      </nav><hr>";


// --- ROUTING SYSTEM ---

if ($url == 'dashboard') {
    $totalValue = $productModel->getTotalAssetValue(); 
    $totalItems = $productModel->getTotalProducts(); 
    $outOfStock = $productModel->getOutOfStockCount(); 
    $incomplete = $productModel->getIncompleteDataCount(); 
    $totalRevenue = $salesModel->getTotalSalesRevenue(); 
    $totalOrders = $salesModel->getTotalOrdersCount(); 
    
    echo "<h3>Dashboard Ringkasan</h3>";
    echo "<div style='display: flex; gap: 20px; flex-wrap: wrap;'>
            
            <div style='padding: 20px; border: 2px solid green; border-radius: 8px; min-width: 200px; background: #f0fff0;'>
                <h4 style='color: green;'>Total Pendapatan COD</h4>
                <p style='font-size: 24px; font-weight: bold;'>Rp " . number_format($totalRevenue, 0, ',', '.') . "</p>
                <small>$totalOrders Transaksi Berhasil</small>
            </div>

            <div style='padding: 20px; border: 1px solid #ccc; border-radius: 8px; min-width: 200px;'>
                <h4>Total Item Produk</h4>
                <p style='font-size: 24px; font-weight: bold;'>$totalItems</p>
            </div>

            <div style='padding: 20px; border: 1px solid #ccc; border-radius: 8px; min-width: 200px;'>
                <h4>Total Nilai Aset</h4>
                <p style='font-size: 24px; font-weight: bold; color: blue;'>Rp " . number_format($totalValue, 0, ',', '.') . "</p>
            </div>

            <div style='padding: 20px; border: 1px solid #ccc; border-radius: 8px; min-width: 200px;'>
                <h4>Stok Kosong</h4>
                <p style='font-size: 24px; font-weight: bold; color: " . ($outOfStock > 0 ? 'red' : 'green') . ";'>$outOfStock</p>
                <small>" . ($outOfStock > 0 ? 'Perlu Restock!' : 'Aman') . "</small>
            </div>

            <div style='padding: 20px; border: 1px solid #ccc; border-radius: 8px; min-width: 200px;'>
                <h4>Data Belum Lengkap</h4>
                <p style='font-size: 24px; font-weight: bold; color: orange;'>$incomplete</p>
                <small>Deskripsi/Kategori kosong</small>
            </div>

          </div>";

} elseif ($url == 'inventory') {
    $products = $productModel->getAllProducts();
    echo "<h3>Daftar Stok Gudang</h3>";
    echo "<a href='index.php?url=inventory/add'>+ Tambah Produk</a><br><br>";
    echo "<table border='1' cellpadding='10' cellspacing='0' style='width:100%; border-collapse:collapse;'>
            <tr style='background:#eee;'>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>";
    foreach ($products as $p) {
        $linkOpname = "index.php?url=inventory/opname&id={$p['id']}&name=" . urlencode($p['product_name']) . "&current_stock={$p['stock']}";
        $linkExit = "index.php?url=inventory/exit&id={$p['id']}&name=" . urlencode($p['product_name']) . "&current_stock={$p['stock']}";
        $linkEditPrice = "index.php?url=inventory/edit_price&id={$p['id']}&sku={$p['sku']}&name=" . urlencode($p['product_name']) . "&current_price={$p['price']}";
        $linkHistory = "index.php?url=reports/price_history&id={$p['id']}";

        echo "<tr>
                <td>{$p['sku']}</td>
                <td>{$p['product_name']}</td>
                <td><strong>{$p['stock']}</strong></td>
                <td>
                    <a href='$linkOpname'>Stock Opname</a> | 
                    <a href='$linkExit' style='color: orange;'>Barang Keluar</a> |
                    <a href='$linkEditPrice' style='color: blue;'>Ubah Harga</a> |
                    <a href='$linkHistory' style='color: gray;'>Riwayat</a>
                </td>
              </tr>";
    }
    echo "</table>";

} elseif ($url == 'inventory/add') {
    include '../app/views/inventory/add.php';

} elseif ($url == 'inventory/save') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $productModel->addProduct($_POST);
        header("Location: index.php?url=inventory");
    }

} elseif ($url == 'inventory/opname') {
    include '../app/views/inventory/opname.php';

} elseif ($url == 'inventory/opname_save') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $opnameModel->createOpname($_POST);
        $productModel->updateStock($_POST['product_id'], $_POST['physical_stock']);
        header("Location: index.php?url=inventory");
    }

} elseif ($url == 'inventory/exit') {
    include '../app/views/inventory/exit.php';

} elseif ($url == 'inventory/exit_save') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $exitModel->createExit($_POST);
        $product = $productModel->getById($_POST['product_id']);
        $newStock = $product['stock'] - $_POST['quantity'];
        $productModel->updateStock($_POST['product_id'], $newStock);
        header("Location: index.php?url=inventory");
    }

} elseif ($url == 'sales/add') {
    $invoiceNumber = $salesModel->generateInvoiceNumber();
    $productsRaw = $productModel->getAllProducts();
    
    $productsJSON = array_map(function($p) {
        return [
            'id' => $p['id'],
            'sku' => $p['sku'],
            'product_name' => $p['product_name'],
            'price' => $p['price'],
            'stock' => $p['stock']
        ];
    }, $productsRaw);

    include '../app/views/sales/add.php';

} elseif ($url == 'sales/save') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $headerData = [
            'invoice_number' => $_POST['invoice_number'],
            'customer_name' => $_POST['customer_name'],
            'total_amount' => $_POST['total_amount']
        ];
        
        $itemsData = $_POST['items'];
        $orderId = $salesModel->createSalesOrder($headerData, $itemsData);

        foreach ($itemsData as $item) {
            $product = $productModel->getById($item['product_id']);
            $newStock = $product['stock'] - $item['quantity'];
            $productModel->updateStock($item['product_id'], $newStock);
        }

        header("Location: index.php?url=dashboard");
    }

} elseif ($url == 'reports/history') {
    $history = $opnameModel->getAllHistory();
    include '../app/views/reports/history.php';

} elseif ($url == 'reports/exits') {
    $exits = $exitModel->getAllExits();
    include '../app/views/reports/exits.php';

} elseif ($url == 'reports/sales') {
    $orders = $salesModel->getAllOrders();
    include '../app/views/reports/sales.php';

} elseif ($url == 'reports/sales_detail') {
    $details = $salesModel->getOrderDetails($_GET['id']);
    include '../app/views/reports/sales_detail.php';

} elseif ($url == 'inventory/edit_price') {
    include '../app/views/inventory/edit_price.php';

} elseif ($url == 'inventory/update_price_save') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['product_id'];
        $newPrice = $_POST['new_price'];
        $product = $productModel->getById($id);
        $oldPrice = $product['price'];

        $result = $productModel->updatePrice($id, $oldPrice, $newPrice);
        
        if($result) {
            header("Location: index.php?url=inventory");
        } else {
            echo "Gagal memperbarui harga.";
        }
    }

} elseif ($url == 'reports/price_history') {
    $id = $_GET['id'];
    $product = $productModel->getById($id);
    $history = $productModel->getPriceHistory($id);
    include '../app/views/reports/price_history.php';

} elseif ($url == 'sales/print_pdf') {
    $orderId = $_GET['id'];
    $data = $salesModel->getOrderForInvoice($orderId);

    require_once '../app/libs/dompdf/vendor/autoload.php';

    $dompdf = new Dompdf();

    ob_start();
    include '../app/views/reports/invoice_pdf.php';
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    if (ob_get_length()) ob_end_clean();

    $dompdf->stream("Invoice-" . $data['header']['invoice_number'] . ".pdf", ["Attachment" => 0]);
    exit;
} elseif ($url == 'users') {
    // Hanya boleh diakses admin
    if ($_SESSION['role'] !== 'admin') { die("Akses Ditolak!"); }
    
    $query = "SELECT id, username, full_name, role FROM users";
    $stmt = $db->getHandler()->prepare($query);
    $stmt->execute();
    $allUsers = $stmt->fetchAll();
    
    include '../app/views/auth/users_list.php';

} elseif ($url == 'users/add') {
    if ($_SESSION['role'] !== 'admin') { die("Akses Ditolak!"); }
    include '../app/views/auth/users_add.php';

} elseif ($url == 'users/save') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = $_POST['username'];
        $full = $_POST['full_name'];
        $role = $_POST['role'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Keamanan hash!

        $query = "INSERT INTO users (username, password, full_name, role) VALUES (:u, :p, :f, :r)";
        $stmt = $db->getHandler()->prepare($query);
        $stmt->execute([':u'=>$user, ':p'=>$pass, ':f'=>$full, ':r'=>$role]);
        
        header("Location: index.php?url=users");
    }

} else {
    echo "<h3>Halaman tidak ditemukan</h3>";
}