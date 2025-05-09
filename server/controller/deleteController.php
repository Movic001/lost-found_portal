<?php
require_once(__DIR__ . '/../config/db.php');

// Initialize the database connection
$database = new Database();
$db = $database->connect();

class DeleteItemController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function deleteItem($itemId, $userId)
    {
        try {
            // Check if item exists and belongs to user
            $stmt = $this->db->prepare("SELECT * FROM found_items WHERE id = :id AND user_id = :user_id");
            $stmt->execute([':id' => $itemId, ':user_id' => $userId]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$item) {
                return false; // Item not found or unauthorized
            }

            // Delete the image file
            if (!empty($item['image_path'])) {
                $imagePath = __DIR__ . '/../../' . $item['image_path'];
                $realImagePath = realpath($imagePath);

                if ($realImagePath && file_exists($realImagePath)) {
                    unlink($realImagePath);
                }
            }

            // Delete item from DB
            $deleteStmt = $this->db->prepare("DELETE FROM found_items WHERE id = :id AND user_id = :user_id");
            return $deleteStmt->execute([':id' => $itemId, ':user_id' => $userId]);
        } catch (PDOException $e) {
            error_log("Delete Error: " . $e->getMessage());
            return false;
        }
    }
}

$deleteItemController = new DeleteItemController($db);
