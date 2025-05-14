<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Claim
{
    private $conn;
    private $table = 'claims';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createClaim($item_id, $user_id, $description, $location_lost, $security_answer)
    {
        $query = "INSERT INTO {$this->table} (item_id, user_id, description, location_lost, security_answer, status) 
                  VALUES (:item_id, :user_id, :description, :location_lost, :security_answer, 'pending')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':location_lost', $location_lost);
        $stmt->bindParam(':security_answer', $security_answer);

        return $stmt->execute();
    }
}
