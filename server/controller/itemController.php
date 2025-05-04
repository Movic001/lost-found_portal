<?php
// controller/ItemController.php
require_once '../model/item_class.php';

class ItemController
{
    private $itemModel;

    public function __construct($db)
    {
        $this->itemModel = new Item($db);
    }

    public function approveItem($id)
    {
        // Assuming you have a method in your Item model to update the status of an item
        $this->itemModel->updateStatus($id, 'approved');
        header("Location: ../../frontend/pages/adminDashboard/pages/adminDashboard.php");
        exit;
    }

    public function rejectItem($id)
    {
        $this->itemModel->updateStatus($id, 'rejected');
        header("Location: ../../frontend/pages/adminDashboard/pages/adminDashboard.php");
        exit;
    }
}
