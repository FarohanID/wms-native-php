<?php
class SalesModel {
    private $db;

    public function __construct($database) {
        $this->db = $database->getHandler();
    }

    // Fungsi untuk meng-generate nomor invoice otomatis
    public function generateInvoiceNumber() {
        $date = date('Ymd');
        $query = "SELECT MAX(invoice_number) as last_invoice FROM orders WHERE invoice_number LIKE 'COD-$date-%'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $last = $stmt->fetch();

        if ($last['last_invoice']) {
            $lastNum = explode('-', $last['last_invoice'])[2];
            $nextNum = str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '001';
        }

        return "COD-$date-$nextNum";
    }

    // Fungsi utama menyimpan pesanan (Multi-Item)
    public function createSalesOrder($headerData, $itemsData) {
        try {
            // Mulai Transaksi (Sangat penting agar data konsisten)
            $this->db->beginTransaction();

            // 1. Simpan ke tabel HEADER (orders)
            $queryHeader = "INSERT INTO orders (invoice_number, customer_name, total_amount) 
                            VALUES (:invoice_number, :customer_name, :total_amount)";
            $stmtHeader = $this->db->prepare($queryHeader);
            $stmtHeader->execute([
                ':invoice_number' => $headerData['invoice_number'],
                ':customer_name' => $headerData['customer_name'],
                ':total_amount' => $headerData['total_amount']
            ]);

            // Ambil ID dari order yang baru saja dimasukkan
            $orderId = $this->db->lastInsertId();

            // 2. Simpan ke tabel DETAIL (order_details) menggunakan perulangan
            $queryDetail = "INSERT INTO order_details (order_id, product_id, quantity, price_at_purchase) 
                            VALUES (:order_id, :product_id, :quantity, :price_at_purchase)";
            $stmtDetail = $this->db->prepare($queryDetail);

            foreach ($itemsData as $item) {
                $stmtDetail->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['product_id'],
                    ':quantity' => $item['quantity'],
                    ':price_at_purchase' => $item['price']
                ]);
            }

            // Jika semua lancar, komit transaksi
            $this->db->commit();
            return $orderId;

        } catch (PDOException $e) {
            // Jika ada error, batalkan semua perubahan
            $this->db->rollBack();
            die("Gagal menyimpan pesanan: " . $e->getMessage());
        }
    }

    public function getAllOrders() {
        $query = "SELECT * FROM orders ORDER BY order_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOrderDetails($orderId) {
        $query = "SELECT od.*, p.product_name, p.sku 
                  FROM order_details od
                  JOIN products p ON od.product_id = p.id 
                  WHERE od.order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOrderForInvoice($orderId) {
        // Ambil Header
        $queryHeader = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->db->prepare($queryHeader);
        $stmt->execute([':id' => $orderId]);
        $header = $stmt->fetch();

        // Ambil Detail Barang
        $queryDetail = "SELECT od.*, p.product_name, p.sku, p.unit 
                        FROM order_details od
                        JOIN products p ON od.product_id = p.id 
                        WHERE od.order_id = :id";
        $stmtDetail = $this->db->prepare($queryDetail);
        $stmtDetail->execute([':id' => $orderId]);
        $details = $stmtDetail->fetchAll();

        return ['header' => $header, 'details' => $details];
    }

    // Menghitung total uang dari seluruh transaksi yang sukses
    public function getTotalSalesRevenue() {
        $query = "SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'Paid'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    // Menghitung jumlah invoice yang sudah dibuat
    public function getTotalOrdersCount() {
        $query = "SELECT COUNT(*) as total FROM orders";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}