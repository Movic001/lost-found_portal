<?php
//database connection
require_once dirname(__DIR__) . '../config/db.php';
require_once dirname(__DIR__) . '/classes/Claim_class.php';

//intialize the database connection
$db = new Database();
$conn = $db->connect(); // ✅ Get the PDO object

class ClaimController
{
    private $claimModel;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->claimModel = new Claim($db);
    }

    public function submitClaim($data)
    {
        $requiredFields = ['item_id', 'description', 'location_lost', 'security_answer', 'user_id'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing field: $field");
            }
        }

        return $this->claimModel->createClaim(
            $data['item_id'],
            $data['user_id'],
            $data['description'],
            $data['location_lost'],
            $data['security_answer']
        );
    }

    public function getPendingClaimsForPoster($posterId)
    {
        $query = "SELECT claims.*, users.fullName AS 
        claimant_name, 
        found_items.item_name,
        found_items.unique_question
          FROM claims
          JOIN users ON claims.user_id = users.id
          JOIN found_items ON claims.item_id = found_items.id
          WHERE found_items.user_id = :poster_id AND claims.status = 'pending'";


        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':poster_id', $posterId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllClaimsForAdmin()
    {
        $query = "SELECT claims.*, users.fullName AS claimant_name, 
                     found_items.item_name,
                     found_items.image_path,
                     found_items.unique_question
              FROM claims
              JOIN users ON claims.user_id = users.id
              JOIN found_items ON claims.item_id = found_items.id
              ORDER BY claims.created_at DESC";

        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateClaimStatus($claimId, $status)
    {
        $query = "UPDATE claims SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $claimId);
        return $stmt->execute();
    }

    public function getClaimById($claimId)
    {
        $query = "SELECT * FROM claims WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $claimId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteClaim($claimId)
    {
        $query = "DELETE FROM claims WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $claimId);
        return $stmt->execute();
    }
}
