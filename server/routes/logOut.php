<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verify CSRF token if implemented
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logOut'])) {
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
    echo "<script>alert('Logged out successfully'); window.location.href='../../frontend/pages/login.html';</script>";
    exit;
} else {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}
