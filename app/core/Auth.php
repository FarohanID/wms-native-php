<?php

class Auth {
    private $db;

    public function __construct($db) {
        $this->db = $db->getHandler();
    }

    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // Verifikasi password hash
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    }

    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?url=login");
            exit;
        }
    }
}