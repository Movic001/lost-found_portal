<?php
//check if the session is started, if not, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../server/config/db.php';          // Your DB connection
require_once '../../server/classes/postItem_class.php';  // The item class

// Initialize the database connection
$database = new Database();
$db = $database->connect();


if (!isset($item)) {
    die("Unauthorized access. This page must be accessed through the claim route.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>claim item</title>
</head>

<body>
    <form action="../../server/routes/claimRoute.php" method="POST">
        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['id']) ?>">

        <label for="description">Describe the lost item:</label><br>
        <textarea id="description" name="description" required placeholder="Describe the lost item in detail"></textarea><br>

        <label for="location_lost">Where did you lose it?</label><br>
        <input type="text" id="location_lost" name="location_lost" required placeholder="Location lost"><br>

        <p><strong>Security Question:</strong> <?= htmlspecialchars($item['unique_question']) ?></p>

        <label for="security_answer">Answer:</label><br>
        <input type="text" id="security_answer" name="security_answer" required placeholder="Answer to security question"><br>

        <button type="submit" name="claim_request">Submit Claim</button>

    </form>



</body>

</html>