<?php
// Database configuration - Uses Environment Variables for Cloud Deployment (Render)
// Local XAMPP defaults are used as fallbacks
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'travel_db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // In production, don't show full error details to users
     if (getenv('DB_HOST')) {
         die("Database Connection Error.");
     }
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
