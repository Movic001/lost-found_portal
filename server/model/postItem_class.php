<?php
//if session is not started, start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../config/db.php');  // Include the database configuration file

require_once(__DIR__ . '/../includes/auth.php'); // Include the class file

class FoundItem
{
    private $db;

    public function __construct($dbConn)
    {
        $this->db = $dbConn;
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

            $stmt = $this->db->prepare($sql);

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
        // Check if the file is an image and get its properties
        $imageName = basename($image["name"]);
        //$targetFile = $targetDir . basename($image["name"]);
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
        // Check if file already exists (optional)
        // if (file_exists($targetFile)) {
        //     echo "Sorry, file already exists.";
        //     return false;
        // }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            return false;
        }

        // Try to upload the file
        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            return $imageName; // ✅ return just the filename
            //return $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    }

    // Method to get all found items from the database
    public function getAllApprovedItems()
    {
        $query = "SELECT * FROM found_items WHERE is_approved = 1 ORDER BY date_found DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Method to get all found items for admin review
    public function searchApprovedItems($keyword)
    {
        $query = "SELECT * FROM found_items 
                  WHERE is_approved = 1 AND (item_name LIKE :keyword OR category LIKE :keyword)
                  ORDER BY date_found DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    // method to get only approved items 
    public function getAllItems()
    {
        $stmt = $this->db->query("SELECT * FROM found_items WHERE status = 'approved' ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search only approved items
    public function searchItems($searchTerm)
    {
        $stmt = $this->db->prepare("SELECT * FROM found_items  WHERE status = 'approved'  AND (item_name LIKE :searchTerm OR category LIKE :searchTerm)");
        $stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
