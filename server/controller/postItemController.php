<?php
// Include the model
require_once(__DIR__ . '/../classes/postItem_class.php');
include('../../server/includes/auth.php');

class PostItemController
{
    private $foundItem;

    public function __construct($db)
    {
        $this->foundItem = new FoundItem($db);
    }

    // Method to handle the POST request for posting a found item
    public function postFoundItem($data, $image)
    {
        try {
            // Validate form data
            if (empty($data['item_name']) || empty($data['unique_question'])) {
                //throw new Exception("Item name and unique question are required.");
                $alertTitle = "Validation Error";
                $alertText = "Item name and unique question are required.";
                $alertIcon = "warning";
                $redirectUrl = '../../frontend/pages/post_item.html';
                $alertButton = "OK";

                include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
                exit;
            }

            // Call the model to handle database insertion
            $result = $this->foundItem->postFoundItem($data, $image);

            if ($result) {
                // If item posted successfully, include success message
                // echo "<script>alert('Item posted successfully! Wait for an admin to approve your post!'); window.location.href='../../frontend/pages/dashboard.php'; </script>";
                //include('../../frontend/status/post_success_message.html');

                $alertTitle = "Item posted successfully!";
                $alertText = "Wait for an admin to approve your post!";
                $alertIcon = "success";
                $redirectUrl = '../../frontend/pages/dashboard.php';
                $alertButton = "OK";

                include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
                exit;
            } else {
                // throw new Exception("❌ Failed to post item.");
                $alertTitle = "Failed to post item";
                $alertText = "Please try again.";
                $alertIcon = "error";
                $redirectUrl = '../../frontend/pages/post_item.html';
                $alertButton = "OK";

                include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
                exit;
            }
        } catch (Exception $e) {
            // Catch any exceptions and display error message
            // echo "<script>alert('" . $e->getMessage() . "'); window.location.href='../../frontend/pages/post_item.html';</script>";
            $alertTitle = "Error";
            $alertText = "An error occurred: " . $e->getMessage();
            $alertIcon = "error";
            $redirectUrl = '../../frontend/pages/post_item.html';
            $alertButton = "OK";

            include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
            exit;
        }
    }
}
