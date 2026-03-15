<?php
// Database configuration
$host = 'localhost';
$dbname = 'news_crud_php';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create users table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(50),
        avatar VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create articles table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        category VARCHAR(100),
        content TEXT,
        thumbnail VARCHAR(255),
        author_id INT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (author_id) REFERENCES users(id)
    )");

    // Insert default user if not exists
    $userExists = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userExists == 0) {
        $pdo->exec("INSERT INTO users (name, role, avatar) VALUES ('TOCH BRUGN', 'Editor in Chief', './assets/images/TOCHBRUGN.jpg')");
    }

    // Get current user for display
    $currentUser = $pdo->query("SELECT * FROM users LIMIT 1")->fetch();
    
    // Migration: Add author_id column to existing articles table if it doesn't exist
    $columns = $pdo->query("SHOW COLUMNS FROM articles LIKE 'author_id'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE articles ADD COLUMN author_id INT DEFAULT 1 AFTER thumbnail");
    }
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}
?>