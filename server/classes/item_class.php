<?php
// model/Item.php

class Item
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function updateStatus($itemId, $newStatus)
    {
        $isApproved = ($newStatus === 'approved') ? 1 : 0;

        $query = "UPDATE found_items SET status = :status, is_approved = :is_approved WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':is_approved', $isApproved, PDO::PARAM_INT);
        $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getAllItems()
    {
        $query = "SELECT * FROM found_items ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Optional: only approved items
    public function getApprovedItems()
    {
        $query = "SELECT * FROM found_items WHERE is_approved = 1 ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
