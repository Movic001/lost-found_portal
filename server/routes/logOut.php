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
    $alertTitle = "Logged out successfully";
    $alertText = "";
    $alertIcon = "success";
    $redirectUrl = '../../frontend/pages/login.html';

    include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
    exit;
} else {
    // header("HTTP/1.1 400 Bad Request");
    // echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    // exit;
    $alertTitle = "HTTP/1.1 400 Bad Request";
    $alertText = "Invalid request";
    $alertIcon = "error";
    $redirectUrl = '../../frontend/pages/login.html';

    include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
    exit;
}
