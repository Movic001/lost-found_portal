<?php
class User
{
    private $db;

    public function __construct($dbConn)
    {
        $this->db = $dbConn;
    }

    // Register user
    public function register($data)
    {
        // Check if email exists
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $data['email']]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            // throw new Exception("Email already exists");
            $alertTitle = "Email already exists";
            $alertText = "Please use a different email.";
            $alertIcon = "error";

            $redirectUrl = '../../frontend/pages/register.html';
            include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
            exit;
        }

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Prepare SQL query with fixed role as 'user'
        $sql = "INSERT INTO users (fullName, userName, mobile, email, address, city, password, role, created_at) 
                VALUES (:fullName, :userName, :mobile, :email, :address, :city, :password, 'user', NOW())";

        $stmt = $this->db->prepare($sql);

        // Execute query
        if (!$stmt->execute([
            ':fullName' => filter_var($data['fullName'], FILTER_SANITIZE_STRING),
            ':userName' => filter_var($data['userName'], FILTER_SANITIZE_STRING),
            ':mobile'   => filter_var($data['mobile'], FILTER_SANITIZE_STRING),
            ':email'    => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
            ':address'  => filter_var($data['address'], FILTER_SANITIZE_STRING),
            ':city'     => filter_var($data['city'], FILTER_SANITIZE_STRING),
            ':password' => $hashedPassword
        ])) {
            throw new Exception("Registration failed");
        }

        return true;
    }

    // Login user
    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => filter_var($email, FILTER_SANITIZE_EMAIL)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->setUserSession($user);
            return true;
        }

        return false;
    }

    // Update user role
    public function updateUserRole($userId, $newRole)
    {
        $allowedRoles = ['user', 'admin'];
        if (!in_array($newRole, $allowedRoles)) {
            // throw new Exception("Invalid role specified");
            $alertTitle = "Invalid role specified";
            $alertText = "Please select a valid role.";
            $alertIcon = "error";
            $redirectUrl = '../../frontend/pages/adminDashboard/pages/adminDashboard.php';

            include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
            exit;
        }

        $stmt = $this->db->prepare("UPDATE users SET role = :role WHERE id = :id");
        return $stmt->execute([
            ':role' => $newRole,
            ':id' => filter_var($userId, FILTER_SANITIZE_NUMBER_INT)
        ]);
    }

    // Get user by ID
    public function getUserById($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => filter_var($userId, FILTER_SANITIZE_NUMBER_INT)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Set user session data
    private function setUserSession($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['fullName'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_mobile'] = $user['mobile'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;
    }

    // Verify if user is admin
    public function isAdmin($userId)
    {
        $user = $this->getUserById($userId);
        return ($user && $user['role'] === 'admin');
    }


    public function verifyUserRole($userId)
    {
        $query = "SELECT role FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['role'] : null;
    }

    // Add to User class
    // public function verifyUserRole($userId)
    // {
    //     $stmt = $this->db->prepare("SELECT role FROM users WHERE id = :id");
    //     $stmt->execute([':id' => $userId]);
    //     return $stmt->fetchColumn();
    // }

    // public function validateSession()
    // {
    //     if (!isset($_SESSION['user_id'])) {
    //         return false;
    //     }

    //     // Verify session matches database
    //     $dbRole = $this->verifyUserRole($_SESSION['user_id']);
    //     if ($dbRole !== $_SESSION['user_role']) {
    //         // Update session if out of sync
    //         $_SESSION['user_role'] = $dbRole;
    //     }

    //     return true;
    // }
    public function validateSession()
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $dbRole = $this->verifyUserRole($_SESSION['user_id']);

        // Optional: reject if session role has been tampered
        if ($_SESSION['user_role'] !== $dbRole) {
            $_SESSION['user_role'] = $dbRole; // Sync from DB
        }

        return true;
    }
}
