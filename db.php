<?php
// Database configuration - Uses Environment Variables for Cloud Deployment (Render)
// Local XAMPP defaults are used as fallbacks
$host    = getenv('DB_HOST') ?: 'localhost';
$db      = getenv('DB_NAME') ?: 'travel_db';
$user    = getenv('DB_USER') ?: 'root';
$pass    = getenv('DB_PASS') ?: '';
$port    = getenv('DB_PORT') ?: null;
$charset = 'utf8mb4';

// Handle Cloud Database Ports (e.g. TiDB uses :4000)
// Support both DB_PORT env var and host:port format
if ($port) {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
} elseif (strpos($host, ':') !== false) {
    list($host_name, $port) = explode(':', $host);
    $dsn = "mysql:host=$host_name;port=$port;dbname=$db;charset=$charset";
} else {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
}

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Enable SSL for cloud databases (TiDB Cloud requires TLS)
if (getenv('DB_HOST')) {
    // On Linux (Render Docker), the CA bundle is typically here
    $ssl_ca_paths = [
        '/etc/ssl/certs/ca-certificates.crt',   // Debian/Ubuntu (Docker)
        '/etc/pki/tls/certs/ca-bundle.crt',     // RHEL/CentOS
        '/etc/ssl/cert.pem',                     // Alpine
    ];
    
    foreach ($ssl_ca_paths as $ca_path) {
        if (file_exists($ca_path)) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $ca_path;
            break;
        }
    }
    
    // If no CA bundle found, still enable SSL but skip verification
    if (!isset($options[PDO::MYSQL_ATTR_SSL_CA])) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
}

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // In production, don't show full error details
     if (getenv('DB_HOST')) {
         die("Database Connection Error. Please check your credentials.");
     }
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
