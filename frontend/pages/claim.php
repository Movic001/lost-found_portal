<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>claim item</title>
</head>

<body>
    <form action="/server/routes/claimRoute.php" method="POST">
        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
        <textarea name="description" required placeholder="Describe the lost item in detail"></textarea>
        <input type="text" name="location_lost" required placeholder="Where did you lose it?">

        <p>Security Question: <?= htmlspecialchars($item['security_question']) ?></p>
        <input type="text" name="security_answer" required placeholder="Answer to security question">

        <button type="submit">Submit Claim</button>
    </form>


</body>

</html>