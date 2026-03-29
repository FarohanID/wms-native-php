<?php

class ProductModel {
    private $db; // Properti untuk menyimpan koneksi database
    private $table = 'products'; // Nama tabel di database

    // Konstruktor untuk menginisialisasi koneksi database
    public function __construct($database) {
        $this->db = $database->getHandler();
    }

    // Fungsi untuk mengambil semua produk
    public function getAllProducts() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Fungsi untuk menambah produk baru (Prepared Statements)
    public function addProduct($data) {
        $query = "INSERT INTO " . $this->table . " (sku, product_name, category, price, unit) 
                  VALUES (:sku, :product_name, :category, :price, :unit)";
        
        $stmt = $this->db->prepare($query);
        
        // Binding data agar aman dari SQL Injection
        $stmt->bindParam(':sku', $data['sku']);
        $stmt->bindParam(':product_name', $data['product_name']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':unit', $data['unit']);

        return $stmt->execute();
    }

    // Tambahkan fungsi ini di dalam class ProductModel
    public function updateStock($id, $newStock) {
        $query = "UPDATE " . $this->table . " SET stock = :stock WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':stock', $newStock);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Tambahkan fungsi analisis
    public function getTotalAssetValue() {
        $query = "SELECT SUM(stock * price) as total_value FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch()['total_value'];
    }

    // Fungsi untuk menghitung total produk
    public function getTotalProducts() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch()['total'];
    }

    // Fungsi untuk mengambil produk berdasarkan ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Menghitung produk yang stoknya 0
    public function getOutOfStockCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE stock <= 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Menghitung produk dengan informasi tidak lengkap (misal deskripsi atau kategori kosong)
    public function getIncompleteDataCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE description IS NULL OR description = '' 
                  OR category IS NULL OR category = ''";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    return $stmt->fetch()['total'];
    }

    // Fungsi untuk mengupdate harga produk dan menyimpan riwayat perubahan harga
    public function updatePrice($id, $oldPrice, $newPrice) {
        try {
            $this->db->beginTransaction();

            // 1. Update harga di tabel utama
            $queryUpdate = "UPDATE " . $this->table . " SET price = :price WHERE id = :id";
            $stmtUpdate = $this->db->prepare($queryUpdate);
            $stmtUpdate->execute([':price' => $newPrice, ':id' => $id]);

            // 2. Simpan jejak ke tabel history
            $queryHistory = "INSERT INTO price_history (product_id, old_price, new_price) 
                            VALUES (:product_id, :old_price, :new_price)";
            $stmtHistory = $this->db->prepare($queryHistory);
            $stmtHistory->execute([
                ':product_id' => $id,
                ':old_price' => $oldPrice,
                ':new_price' => $newPrice
            ]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Fungsi untuk menyimpan transaksi COD (Multi-Item)
    public function getPriceHistory($productId) {
        $query = "SELECT * FROM price_history WHERE product_id = :id ORDER BY change_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $productId);
        $stmt->execute();
    return $stmt->fetchAll();
    }
}