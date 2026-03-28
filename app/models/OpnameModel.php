<?php
class OpnameModel {
    private $db;
    private $table = 'stock_opname';

    public function __construct($database) {
        $this->db = $database->getHandler();
    }

    public function createOpname($data) {
        $difference = $data['physical_stock'] - $data['system_stock'];
        $query = "INSERT INTO " . $this->table . " (product_id, system_stock, physical_stock, difference, note) 
                  VALUES (:product_id, :system_stock, :physical_stock, :difference, :note)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':system_stock', $data['system_stock']);
        $stmt->bindParam(':physical_stock', $data['physical_stock']);
        $stmt->bindParam(':difference', $difference);
        $stmt->bindParam(':note', $data['note']);
        return $stmt->execute();
    }

        public function getAllHistory() {
        $query = "SELECT so.*, p.product_name, p.sku 
                FROM " . $this->table . " so
                JOIN products p ON so.product_id = p.id 
                ORDER BY so.opname_date DESC";
                
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}