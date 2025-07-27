<?php
class Note
{
    private $conn;
    private $table = "tbl_notes";
    private $uploadDir;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->uploadDir = __DIR__ . '/../uploads/notes/';
        // Create upload directory if it doesn't exist
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    // Get all notes for a user
    public function getAll($userid)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_date DESC");
        $stmt->execute([$userid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single note by ID
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new note with file upload handling
    public function create($title, $content, $userid, $file = null)
    {
        $fileupload = $file;

        try {
            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table} 
                (title, content, user_id, fileupload, created_date, updated_date)
                VALUES (?, ?, ?, ?, NOW(), NOW())
            ");

            $success = $stmt->execute([$title, $content, $userid, $fileupload]);

            if (!$success) {
                // Optionally remove uploaded file
                if ($fileupload && file_exists($this->uploadDir . $fileupload)) {
                    unlink($this->uploadDir . $fileupload);
                }
                return false;
            }

            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            error_log("Note creation failed: " . $e->getMessage());
            return false;
        }
    }

    // Update an existing note with file handling
    public function update($id, $title, $content, $userid, $file = null)
    {
        try {
            // Get current note data
            $currentNote = $this->getById($id);
            $currentFile = $currentNote['fileupload'] ?? null;
            $newFile = $file ?? $currentFile;

            $stmt = $this->conn->prepare("
                UPDATE {$this->table}
                SET title = ?, content = ?, fileupload = ?, user_id = ?, updated_date = NOW()
                WHERE id = ?
            ");

            return $stmt->execute([$title, $content, $newFile, $userid, $id]);
        } catch (Exception $e) {
            error_log("Note update failed: " . $e->getMessage());
            return false;
        }
    }
    public function deleteFileOnly($id)
    {
        try {
            $note = $this->getById($id);

            if ($note && !empty($note['fileupload'])) {
                $relativePath = $note['fileupload'];
                $fullPath = realpath(__DIR__ . '/../' . $relativePath);
                // print_r($fullPath);

                error_log("Trying to delete file: " . $fullPath);

                if ($fullPath && file_exists($fullPath)) {
                    unlink($fullPath);
                    error_log("File deleted successfully.");
                } else {
                    error_log("File not found at: " . $fullPath);
                }

                // Now clear the DB field
                $stmt = $this->conn->prepare("UPDATE {$this->table} SET fileupload = NULL WHERE id = ?");
                $stmt->execute([$id]);

                if ($stmt->rowCount() === 0) {
                    error_log("DB update executed but no row affected for ID: $id");
                }

                return true;
            }

            return false;
        } catch (Exception $e) {
            error_log("Delete file only failed: " . $e->getMessage());
            return false;
        }
    }


    // Delete a note and its associated file
    public function delete($id)
    {
        try {
            // Get note to delete its file
            $note = $this->getById($id);

            if ($note && $note['fileupload'] && file_exists($this->uploadDir . $note['fileupload'])) {
                unlink($this->uploadDir . $note['fileupload']);
            }

            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Note deletion failed: " . $e->getMessage());
            return false;
        }
    }

    // Get file path for a note
    public function getFilePath($noteId)
    {
        $note = $this->getById($noteId);
        if ($note && $note['fileupload']) {
            return $this->uploadDir . $note['fileupload'];
        }
        return null;
    }
}
