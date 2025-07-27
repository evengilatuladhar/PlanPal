<?php
class User
{
    private $conn;
    private $table = "tbl_users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Register a new user
    public function register($name, $email, $username, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (name, email, username, password, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");

        return $stmt->execute([$name, $email, $username, $hashedPassword]);
    }

    // Check login by username/email and password
    public function login($usernameOrEmail, $password)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM {$this->table}
            WHERE username = ? OR email = ?
            LIMIT 1
        ");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user->password)) {
            return $user; // successful login
        }

        return false; // login failed
    }

    public function emailExists($email, $excludeId = 0)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = ? AND id != ?");
        $stmt->execute([$email, $excludeId]);
        return $stmt->fetch() ? true : false;
    }

    public function usernameExists($username, $excludeId = 0)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE username = ? AND id != ?");
        $stmt->execute([$username, $excludeId]);
        return $stmt->fetch() ? true : false;
    }

    public function updateProfileWithoutPassword($id, $name, $email, $username)
    {
        $stmt = $this->conn->prepare("
            UPDATE tbl_users 
            SET name = ?, email = ?, username = ?, updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$name, $email, $username, $id]);
    }

    public function updateProfile($id, $name, $email, $username, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("
            UPDATE tbl_users 
            SET name = ?, email = ?, username = ?, password = ?, updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$name, $email, $username, $hashedPassword, $id]);
    }

    // Check if username/email exists (for validation)
    public function exists($username, $email)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as count FROM {$this->table}
            WHERE username = ? OR email = ?
        ");
        $stmt->execute([$username, $email]);
        $result = $stmt->fetch();
        return $result->count > 0;
    }

    // Get user by ID
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
