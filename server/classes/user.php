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
            return false; // Email already exists
        }

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Prepare SQL query with a fixed role value of 'user'
        $sql = "INSERT INTO users (fullName, userName, mobile, email, address, city, password, role, created_at) 
            VALUES (:fullName, :userName, :mobile, :email, :address, :city, :password, 'user', NOW())";

        $stmt = $this->db->prepare($sql);

        // Execute query
        return $stmt->execute([
            ':fullName' => $data['fullName'],
            ':userName' => $data['userName'],
            ':mobile'   => $data['mobile'],
            ':email'    => $data['email'],
            ':address'  => $data['address'],
            ':city'     => $data['city'],
            ':password' => $hashedPassword
        ]);
    }


    // Login user
    public function login($email, $password)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullName'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
