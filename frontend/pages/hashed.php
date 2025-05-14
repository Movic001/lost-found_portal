<?php
$plainPassword = 'admin123';
$hashedPasswordFromDatabase = '$2y$10$W2nG/7qvF/dKYvIG3o4UmeYgXKkAlRr2iAUPXktQ5oDcBkbGhUqBa'; // Example

if (password_verify($plainPassword, $hashedPasswordFromDatabase)) {
    echo "Password is correct!";
} else {
    echo password_hash('admin123', PASSWORD_BCRYPT);
}
