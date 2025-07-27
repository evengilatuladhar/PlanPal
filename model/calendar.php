<?php
class Calendar
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
            error_log("Database error in getAll(): " . $e->getMessage());
            return [];
        }
    }

    public function create($title, $event_date, $userid)
    {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table} (title, event_date, user_id, created_date, updated_date)
                VALUES (?, ?, ?, NOW(), NOW())
            ");
            return $stmt->execute([$title, $event_date, $userid]);
        } catch (PDOException $e) {
            error_log("Create Event Error: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $title, $event_date)
    {
        try {
            $stmt = $this->conn->prepare("
                UPDATE {$this->table}
                SET title = ?, event_date = ?, updated_date = NOW()
                WHERE id = ?
            ");
            return $stmt->execute([$title, $event_date, $id]);
        } catch (PDOException $e) {
            error_log("Update Event Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Delete Event Error: " . $e->getMessage());
            return false;
        }
    }
}
