<?php
$host = 'localhost';
$db_name = 'lost_found_portal';
$username = 'root';
$password = 'admin';

try {
    $db = new PDO(
        "mysql:host={$host};dbname={$db_name}",
        $username,
        $password
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "DataBase Connected successfully!";
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

//create table if not exists users

// create a table for users
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100) NOT NULL,
    userName VARCHAR(100) NOT NULL,
    mobile VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    address VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute the query
try {
    $db->exec($sql_users);
    //set an alert message to be displayed on the page
    // echo "<script>alert('users Table created successfully!');</script>";
} catch (PDOException $e) {
    echo "<br>failed to create table: " . $e->getMessage();
}



// Modify the table to include unique question and answer
$sql = "CREATE TABLE IF NOT EXISTS found_items  (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    location_found VARCHAR(255) NOT NULL,
    date_found DATE NOT NULL,
    person_name VARCHAR(100) NOT NULL,
    contact_info VARCHAR(100) NOT NULL,
    image_path VARCHAR(255),
    unique_question VARCHAR(255) NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute the query
try {
    $db->exec($sql);
    //set an alert message to be displayed on the page
    //echo "<script>alert('Table found_items created successfully!');</script>";
} catch (PDOException $e) {
    echo "<br>failed to create table: " . $e->getMessage();
}
