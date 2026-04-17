<?php
/**
 * migrate_uploads.php — One-time migration script
 * 
 * Reads existing files from the uploads/ directory, stores their binary data
 * into the database, and updates the trip_media records.
 * 
 * Run this once locally: http://localhost/travel&tours/migrate_uploads.php
 * Then redeploy to Render.
 */
require_once 'db.php';

header('Content-Type: text/html; charset=utf-8');
echo "<h2>🔄 Migrating uploads to database...</h2>";

// Increase max_allowed_packet for large video files
try {
    $pdo->exec("SET GLOBAL max_allowed_packet = 67108864"); // 64MB
    $pdo->exec("SET SESSION max_allowed_packet = 67108864");
    echo "<p>⚙️ Increased max_allowed_packet to 64MB for large files.</p>";
} catch (Exception $e) {
    echo "<p>⚠️ Could not increase max_allowed_packet: " . $e->getMessage() . "</p>";
}

// Reconnect with the new packet size in effect
try {
    $host    = getenv('DB_HOST') ?: 'localhost';
    $db_name = getenv('DB_NAME') ?: 'travel_db';
    $user    = getenv('DB_USER') ?: 'root';
    $pass    = getenv('DB_PASS') ?: '';
    $charset = 'utf8mb4';
    if (strpos($host, ':') !== false) {
        list($host_name, $port) = explode(':', $host);
        $dsn = "mysql:host=$host_name;port=$port;dbname=$db_name;charset=$charset";
    } else {
        $dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
    }
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    echo "<p>✅ Reconnected to database with new packet size.</p>";
} catch (Exception $e) {
    echo "<p>❌ Reconnection failed: " . $e->getMessage() . "</p>";
    exit;
}

// 1. Add new columns if they don't exist (safe to run multiple times)
try {
    $pdo->exec("ALTER TABLE trip_media ADD COLUMN file_data LONGBLOB DEFAULT NULL");
    echo "<p>✅ Added <code>file_data</code> column.</p>";
} catch (Exception $e) {
    echo "<p>⚡ <code>file_data</code> column already exists — skipping.</p>";
}

try {
    $pdo->exec("ALTER TABLE trip_media ADD COLUMN mime_type VARCHAR(100) DEFAULT NULL");
    echo "<p>✅ Added <code>mime_type</code> column.</p>";
} catch (Exception $e) {
    echo "<p>⚡ <code>mime_type</code> column already exists — skipping.</p>";
}

// 2. Find all trip_media records that still reference filesystem paths
$stmt = $pdo->query("SELECT id, file_path, file_type FROM trip_media WHERE file_data IS NULL");
$records = $stmt->fetchAll();

if (empty($records)) {
    echo "<p>🎉 No files to migrate — all media is already stored in the database!</p>";
    exit;
}

echo "<p>📁 Found <strong>" . count($records) . "</strong> files to migrate:</p><ol>";

$mime_map = [
    'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
    'gif' => 'image/gif', 'webp' => 'image/webp', 'svg' => 'image/svg+xml',
    'mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg',
    'mov' => 'video/quicktime'
];

$success = 0;
$failed = 0;

foreach ($records as $record) {
    $file_path = $record['file_path'];
    $full_path = __DIR__ . '/' . $file_path;
    
    echo "<li><code>$file_path</code> — ";
    
    if (!file_exists($full_path)) {
        echo "❌ File not found on disk. Skipping.</li>";
        $failed++;
        continue;
    }
    
    // Read file and determine MIME
    $file_data = file_get_contents($full_path);
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $mime_type = $mime_map[$extension] ?? ($record['file_type'] === 'video' ? 'video/mp4' : 'image/jpeg');
    
    // Update the database record with binary data
    $update = $pdo->prepare("UPDATE trip_media SET file_data = ?, mime_type = ?, file_path = 'db_stored' WHERE id = ?");
    $update->execute([$file_data, $mime_type, $record['id']]);
    
    $size = round(strlen($file_data) / 1024);
    echo "✅ Migrated ({$size}KB, {$mime_type})</li>";
    $success++;
}

echo "</ol>";
echo "<h3>📊 Migration Complete</h3>";
echo "<p>✅ Success: <strong>$success</strong> | ❌ Failed: <strong>$failed</strong></p>";

if ($success > 0) {
    echo "<p style='color: green; font-weight: bold;'>🎉 Done! Your media is now stored in the database and will persist on cloud deployments.</p>";
    echo "<p>You can now safely redeploy to Render — videos and photos will show up!</p>";
}
?>
