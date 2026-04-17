<?php
/**
 * migrate_to_cloud.php — One-time script to migrate local uploads to the cloud database.
 * 
 * This connects DIRECTLY to TiDB Cloud and stores local file data as LONGBLOB.
 * Run this once locally: http://localhost/travel&tours/migrate_to_cloud.php
 * 
 * After running, the cloud database will have all media stored and the
 * deployed Render site will show the videos/photos.
 */

header('Content-Type: text/html; charset=utf-8');
echo "<h1>☁️ Cloud Database Migration</h1>";
echo "<p>Connecting to TiDB Cloud and migrating local files...</p>";

// TiDB Cloud credentials (from Render env vars)
$cloud_host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$cloud_port = 4000;
$cloud_db   = 'test';
$cloud_user = 'NRZH33omS9xPWgz.root';
$cloud_pass = '9YVJYxBGy8KbHTpL';

try {
    $cloud_pdo = new PDO(
        "mysql:host=$cloud_host;port=$cloud_port;dbname=$cloud_db;charset=utf8mb4",
        $cloud_user,
        $cloud_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::MYSQL_ATTR_SSL_CA => '',
        ]
    );
    echo "<p>✅ Connected to TiDB Cloud successfully!</p>";
} catch (Exception $e) {
    // Try with SSL
    try {
        $cloud_pdo = new PDO(
            "mysql:host=$cloud_host;port=$cloud_port;dbname=$cloud_db;charset=utf8mb4",
            $cloud_user,
            $cloud_pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
        echo "<p>✅ Connected to TiDB Cloud (SSL mode)!</p>";
    } catch (Exception $e2) {
        echo "<p>❌ Failed to connect to TiDB Cloud: " . $e2->getMessage() . "</p>";
        exit;
    }
}

// Increase packet size
try {
    $cloud_pdo->exec("SET SESSION max_allowed_packet = 67108864");
    echo "<p>⚙️ Set max_allowed_packet to 64MB</p>";
} catch (Exception $e) {
    echo "<p>⚠️ Could not set max_allowed_packet (continuing anyway): " . $e->getMessage() . "</p>";
}

// Step 1: Ensure the tables exist
echo "<h2>📋 Step 1: Ensuring tables exist...</h2>";

try {
    $cloud_pdo->exec("
        CREATE TABLE IF NOT EXISTS trips (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✅ trips table ready</p>";
} catch (Exception $e) {
    echo "<p>⚡ trips table: " . $e->getMessage() . "</p>";
}

try {
    $cloud_pdo->exec("
        CREATE TABLE IF NOT EXISTS trip_media (
            id INT AUTO_INCREMENT PRIMARY KEY,
            trip_id INT NOT NULL,
            file_path VARCHAR(255) NOT NULL DEFAULT 'db_stored',
            file_type ENUM('image', 'video') NOT NULL,
            file_data LONGBLOB DEFAULT NULL,
            mime_type VARCHAR(100) DEFAULT NULL,
            FOREIGN KEY (trip_id) REFERENCES trips(id) ON DELETE CASCADE
        )
    ");
    echo "<p>✅ trip_media table ready (with file_data LONGBLOB)</p>";
} catch (Exception $e) {
    echo "<p>⚡ trip_media table: " . $e->getMessage() . "</p>";
}

// Add columns if they don't exist (in case table already exists without them)
try {
    $cloud_pdo->exec("ALTER TABLE trip_media ADD COLUMN file_data LONGBLOB DEFAULT NULL");
    echo "<p>✅ Added file_data column</p>";
} catch (Exception $e) {
    echo "<p>⚡ file_data column already exists</p>";
}
try {
    $cloud_pdo->exec("ALTER TABLE trip_media ADD COLUMN mime_type VARCHAR(100) DEFAULT NULL");
    echo "<p>✅ Added mime_type column</p>";
} catch (Exception $e) {
    echo "<p>⚡ mime_type column already exists</p>";
}

// Also ensure reviews and bookings tables exist
try {
    $cloud_pdo->exec("
        CREATE TABLE IF NOT EXISTS reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            route VARCHAR(150) NOT NULL,
            rating TINYINT NOT NULL DEFAULT 5,
            message TEXT NOT NULL,
            photo VARCHAR(255) DEFAULT NULL,
            approved TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✅ reviews table ready</p>";
} catch (Exception $e) {
    echo "<p>⚡ reviews: " . $e->getMessage() . "</p>";
}

// Step 2: Read trips from local DB and migrate them + their files
echo "<h2>📁 Step 2: Migrating local trips and media to cloud...</h2>";

// Connect to local DB
try {
    $local_pdo = new PDO("mysql:host=localhost;dbname=travel_db;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "<p>✅ Connected to local database</p>";
} catch (Exception $e) {
    echo "<p>❌ Cannot connect to local DB: " . $e->getMessage() . "</p>";
    exit;
}

// Check if cloud already has trips
$cloud_trip_count = $cloud_pdo->query("SELECT COUNT(*) as cnt FROM trips")->fetch()['cnt'];
echo "<p>☁️ Cloud currently has <strong>$cloud_trip_count</strong> trips</p>";

// Get all local trips
$local_trips = $local_pdo->query("SELECT * FROM trips ORDER BY id")->fetchAll();
echo "<p>💻 Local database has <strong>" . count($local_trips) . "</strong> trips</p>";

if (empty($local_trips)) {
    echo "<p>No local trips to migrate!</p>";
    exit;
}

$mime_map = [
    'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
    'gif' => 'image/gif', 'webp' => 'image/webp',
    'mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg',
    'mov' => 'video/quicktime'
];

// Clear existing cloud data and re-import
if ($cloud_trip_count > 0) {
    echo "<p>🗑️ Clearing existing cloud trips to do a fresh import...</p>";
    $cloud_pdo->exec("DELETE FROM trip_media");
    $cloud_pdo->exec("DELETE FROM trips");
    $cloud_pdo->exec("ALTER TABLE trips AUTO_INCREMENT = 1");
    $cloud_pdo->exec("ALTER TABLE trip_media AUTO_INCREMENT = 1");
}

echo "<ol>";
foreach ($local_trips as $trip) {
    echo "<li><strong>{$trip['title']}</strong> — ";
    
    // Insert trip into cloud
    $stmt = $cloud_pdo->prepare("INSERT INTO trips (title, description, created_at) VALUES (?, ?, ?)");
    $stmt->execute([$trip['title'], $trip['description'], $trip['created_at']]);
    $cloud_trip_id = $cloud_pdo->lastInsertId();
    
    // Get media for this trip from local DB
    $media_stmt = $local_pdo->prepare("SELECT * FROM trip_media WHERE trip_id = ?");
    $media_stmt->execute([$trip['id']]);
    $media_items = $media_stmt->fetchAll();
    
    $migrated = 0;
    foreach ($media_items as $media) {
        $file_data = null;
        $mime_type = null;
        
        // Try to get data from local DB first (if already migrated locally)
        if (!empty($media['file_data'])) {
            $file_data = $media['file_data'];
            $mime_type = $media['mime_type'] ?? null;
        } else {
            // Read from filesystem
            $file_path = __DIR__ . '/' . $media['file_path'];
            if (file_exists($file_path)) {
                $file_data = file_get_contents($file_path);
                $ext = strtolower(pathinfo($media['file_path'], PATHINFO_EXTENSION));
                $mime_type = $mime_map[$ext] ?? ($media['file_type'] === 'video' ? 'video/mp4' : 'image/jpeg');
            }
        }
        
        if (!$mime_type) {
            $mime_type = $media['file_type'] === 'video' ? 'video/mp4' : 'image/jpeg';
        }
        
        if ($file_data) {
            $insert_media = $cloud_pdo->prepare(
                "INSERT INTO trip_media (trip_id, file_path, file_type, file_data, mime_type) VALUES (?, 'db_stored', ?, ?, ?)"
            );
            $insert_media->execute([$cloud_trip_id, $media['file_type'], $file_data, $mime_type]);
            $migrated++;
        }
    }
    
    echo "Migrated <strong>$migrated</strong> media files</li>";
}
echo "</ol>";

// Step 3: Seed reviews if not present
echo "<h2>⭐ Step 3: Checking reviews...</h2>";
$review_count = $cloud_pdo->query("SELECT COUNT(*) as cnt FROM reviews")->fetch()['cnt'];
if ($review_count == 0) {
    $cloud_pdo->exec("INSERT INTO reviews (name, route, rating, message, approved) VALUES
        ('Maria C.', 'Manila to Tagaytay Trip', 5, 'Kuya was very accommodating and patient. The vehicle was super clean and cold. We felt safe the entire trip to Tagaytay. Will definitely book again!', 1),
        ('James R.', 'Manila to Baguio (3-Day Trip)', 5, 'Best driver experience in the Philippines! He knew all the hidden spots in Baguio. Very professional and the vehicle was spotless. Highly recommended for families.', 1),
        ('Sarah K.', 'NAIA Airport Transfer', 5, 'Airport pickup was on time even though our flight was delayed. Very understanding and flexible. The ride to our hotel in Makati was smooth and comfortable.', 1)
    ");
    echo "<p>✅ Seeded 3 starter reviews</p>";
} else {
    echo "<p>⚡ Reviews already exist ($review_count found) — skipping seed</p>";
}

// Step 4: Verify
echo "<h2>✅ Step 4: Verification</h2>";
$final_trips = $cloud_pdo->query("SELECT COUNT(*) as cnt FROM trips")->fetch()['cnt'];
$final_media = $cloud_pdo->query("SELECT COUNT(*) as cnt FROM trip_media WHERE file_data IS NOT NULL")->fetch()['cnt'];
$final_reviews = $cloud_pdo->query("SELECT COUNT(*) as cnt FROM reviews")->fetch()['cnt'];

echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
echo "<tr><th>Item</th><th>Count</th></tr>";
echo "<tr><td>Trips</td><td><strong>$final_trips</strong></td></tr>";
echo "<tr><td>Media Files (with data)</td><td><strong>$final_media</strong></td></tr>";
echo "<tr><td>Reviews</td><td><strong>$final_reviews</strong></td></tr>";
echo "</table>";

echo "<h2 style='color:green;'>🎉 Migration Complete!</h2>";
echo "<p>Your deployed site at <a href='https://travel-tours-u9f0.onrender.com/travels.php' target='_blank'>https://travel-tours-u9f0.onrender.com/travels.php</a> should now show all your videos and photos!</p>";
echo "<p><strong>Note:</strong> You can delete this file after migration is complete.</p>";
?>
