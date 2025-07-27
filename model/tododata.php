<?php
class Todo
{
    private $conn;
    private $table = "tbl_tododata";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY created_date DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getAll_user($userid = '')
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id = :userid ORDER BY created_date DESC");
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll_active($userid = '')
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE status = 1 AND user_id = :userid ORDER BY created_date DESC");
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll_done($userid = '')
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE status=0 AND user_id = :userid ORDER BY created_date DESC");
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($title, $type, $userid)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (title, type, user_id, status, created_date, updated_date)
            VALUES (?, ?, ?, 0, NOW(), NOW())
        ");
        return $stmt->execute([$title, $type, $userid]);
    }

    public function update($id, $title, $type)
    {
        $sql = "UPDATE {$this->table} SET title = ?, type = ?, updated_date = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$title, $type, $id]);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET status = ?, updated_date = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$status, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
