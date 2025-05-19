<?php
//if session is not started, start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../config/db.php');  // Include the database configuration file

require_once(__DIR__ . '/../includes/auth.php'); // Include the class file

//initialize the database connection
$database = new Database();
$db = $database->connect(); // ✅ Use $db for database connection

class FoundItem
{
    // ✅ Use $conn instead of $db to avoid confusion and ensure correct usage
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db; // ✅ Assign db connection to $conn
    }

    // Method to post found item into the database
    public function postFoundItem($data, $image)
    {
        try {
            // Handle image upload and return image path
            $imagePath = $this->uploadImage($image);

            // Prepare SQL statement to insert the item details into the database
            $sql = "INSERT INTO found_items (user_id, item_name, category, description, location_found, date_found, person_name, contact_info, image_path, unique_question, created_at)
                VALUES (:user_id, :item_name, :category, :description, :location_found, :date_found, :person_name, :contact_info, :image_path, :unique_question, NOW())";

            // ✅ FIXED: use $this->conn instead of $this->db
            $stmt = $this->conn->prepare($sql);

            // Execute SQL query with form data
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':item_name' => $data['item_name'],
                ':category' => $data['category'],
                ':description' => $data['description'],
                ':location_found' => $data['location_found'],
                ':date_found' => $data['date_found'],
                ':person_name' => $data['person_name'],
                ':contact_info' => $data['contact_info'],
                ':image_path' => $imagePath,
                ':unique_question' => $data['unique_question']
            ]);

            return true; // Return true on success
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Return false if an error occurs
        }
    }

    // Method to handle image upload
    public function uploadImage($image)
    {
        $targetDir = __DIR__ . '/../../frontend/uploads/';

        // Check if the directory exists, if not create it
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageName = basename($image["name"]);
        $targetFile = $targetDir . $imageName; // Use the original name for the file
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        if (getimagesize($image["tmp_name"]) === false) {
            echo "File is not an image.";
            return false;
        }

        // Check file size (limit to 500KB)
        if ($image["size"] > 500000) {
            echo "Sorry, your file is too large.";
            return false;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            return false;
        }

        // Try to upload the file
        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            return $imageName; // ✅ Only filename is returned to store in DB
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    }

    // Method to get all approved items
    public function getAllApprovedItems()
    {
        $query = "SELECT * FROM found_items WHERE is_approved = 1 ORDER BY date_found DESC";

        // ✅ FIXED: using $this->conn for prepare
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search approved items by keyword
    public function searchApprovedItems($keyword)
    {
        $query = "SELECT * FROM found_items 
                  WHERE is_approved = 1 AND (item_name LIKE :keyword OR category LIKE :keyword)
                  ORDER BY date_found DESC";

        // ✅ FIXED: using $this->conn for prepare
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':keyword' => '%' . $keyword . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get only approved items
    public function getAllItems()
    {
        // ✅ FIXED: using $this->conn->query directly
        $stmt = $this->conn->query("SELECT * FROM found_items WHERE status = 'approved' ORDER BY created_at DESC");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search only approved items
    public function searchItems($searchTerm)
    {
        // ✅ FIXED: using $this->conn for prepare
        $stmt = $this->conn->prepare("SELECT * FROM found_items WHERE status = 'approved' AND (item_name LIKE :searchTerm OR category LIKE :searchTerm)");
        $stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Method to get a specific item by ID
    // ✅ FIXED: using $this->conn for prepare
    // ✅ FIXED: added is_approved check to ensure only approved items are fetched
    public function getItemById($item_id)
    {
        $sql = "SELECT * FROM found_items WHERE id = :item_id AND is_approved = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // returns item data or false
    }

    // Method to update item status
    public function getItemsByUserId($userId)
    {
        $query = "SELECT * FROM found_items WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function countClaimedItemsByUser($userId)
    {
        $query = "SELECT COUNT(*) as total FROM found_items WHERE user_id = :user_id AND status = 'claimed'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    public function countUnclaimedItemsByUser($userId)
    {
        $query = "SELECT COUNT(*) as total FROM found_items WHERE user_id = :user_id AND status = 'available'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
}
