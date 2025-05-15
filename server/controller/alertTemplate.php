<?php
// alertTemplate.php

// Make sure these variables are set before including this file:
// $alertTitle, $alertText, $alertIcon, $redirectUrl

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?php echo htmlspecialchars($alertTitle); ?></title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script>
        window.onload = function() {
            Swal.fire({
                title: "<?php echo addslashes($alertTitle); ?>",
                text: "<?php echo addslashes($alertText); ?>",
                icon: "<?php echo addslashes($alertIcon); ?>",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo addslashes($redirectUrl); ?>";
                }
            });
        };
    </script>
</body>

</html>