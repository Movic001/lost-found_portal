<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['logOut'])) {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Delete session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    echo "<script>alert('You have logged out successfully!'); window.location.href='../../frontend/pages/login.html';</script>";
    exit;
} else {
    echo "<script>alert('Invalid request.'); window.location.href='../../frontend/pages/login.html';</script>";
    exit;
}
