<?php
class ExitModel {
    private $db;
    private $table = 'product_exits';

    public function __construct($database) {
        $this->db = $database->getHandler();
    }

    public function createExit($data) {
        $query = "INSERT INTO " . $this->table . " (product_id, quantity, receiver, reason) 
                  VALUES (:product_id, :quantity, :receiver, :reason)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':receiver', $data['receiver']);
        $stmt->bindParam(':reason', $data['reason']);
        return $stmt->execute();
    }

    public function getAllExits() {
        $query = "SELECT pe.*, p.product_name, p.sku 
                FROM " . $this->table . " pe
                JOIN products p ON pe.product_id = p.id 
                ORDER BY pe.exit_date DESC";
              
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}