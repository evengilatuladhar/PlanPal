<?php
class calendar
{
    private $conn;
    private $table = "tbl_events";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($userid)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id = :userid ORDER BY event_date DESC, created_date DESC");
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    public function getUpcoming($userid, $limit = 5)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = :userid 
            AND event_date >= CURDATE()
            ORDER BY event_date ASC
            LIMIT :limit
        ");
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($title, $event_date, $userid)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (title, event_date, user_id, created_date, updated_date)
            VALUES (?, ?, ?, NOW(), NOW())
        ");
        return $stmt->execute([$title, $event_date, $userid]);
    }

    public function update($id, $title, $event_date)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET title = ?, event_date = ?, updated_date = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$title, $event_date, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
