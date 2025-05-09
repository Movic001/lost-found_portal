<?php
class FoundItem
{
    private $db;

    public function __construct($dbConn)
    {
        $this->db = $dbConn;
    }
    public function getAllItems()
    {
        $stmt = $this->db->query("SELECT * FROM found_items ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchItems($searchTerm)
    {
        $stmt = $this->db->prepare("SELECT * FROM found_items WHERE item_name LIKE :searchTerm OR category LIKE :searchTerm");
        $stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
