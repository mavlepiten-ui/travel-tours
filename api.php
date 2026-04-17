<?php
require_once 'db.php';
require_once 'config.php';

// Set headers for JSON response
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch all trips with their associated media
    try {
        $stmt = $pdo->query("SELECT * FROM trips ORDER BY created_at DESC");
        $trips = $stmt->fetchAll();
        
        foreach ($trips as &$trip) {
            $media_stmt = $pdo->prepare("SELECT id, file_type FROM trip_media WHERE trip_id = ?");
            $media_stmt->execute([$trip['id']]);
            $media_rows = $media_stmt->fetchAll();

            // Build media array with serve_media.php URLs instead of filesystem paths
            $trip['media'] = array_map(function($m) {
                return [
                    'file_path' => 'serve_media.php?id=' . $m['id'],
                    'file_type' => $m['file_type']
                ];
            }, $media_rows);
        }
        
        echo json_encode($trips);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($method === 'POST') {
    // Handle upload
    $password = $_POST['password'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    // Securely check password against the hash in config.php
    if (!password_verify($password, $admin_password_hash)) {
        http_response_code(403);
        echo json_encode(['error' => 'Incorrect secret password! Access Denied.']);
        exit;
    }

    // Check for media uploads
    if (!isset($_FILES['media']) || empty($_FILES['media']['name'][0])) {
        http_response_code(400);
        echo json_encode(['error' => 'No media files uploaded.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Insert Trip
        $stmt = $pdo->prepare("INSERT INTO trips (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);
        $trip_id = $pdo->lastInsertId();

        // 2. Process Files — store binary data directly in the database
        $files = $_FILES['media'];
        $file_count = count($files['name']);

        for ($i = 0; $i < $file_count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

            $file_name = $files['name'][$i];
            $tmp_name = $files['tmp_name'][$i];
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Determine file type and MIME
            $video_exts = ['mp4', 'webm', 'ogg', 'mov'];
            $file_type = in_array($extension, $video_exts) ? 'video' : 'image';

            // Map extension to MIME type
            $mime_map = [
                'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
                'gif' => 'image/gif', 'webp' => 'image/webp', 'svg' => 'image/svg+xml',
                'mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg',
                'mov' => 'video/quicktime'
            ];
            $mime_type = $mime_map[$extension] ?? ($file_type === 'video' ? 'video/mp4' : 'image/jpeg');

            // Read the file binary data
            $file_data = file_get_contents($tmp_name);

            if ($file_data !== false) {
                $stmt_media = $pdo->prepare(
                    "INSERT INTO trip_media (trip_id, file_path, file_type, file_data, mime_type) VALUES (?, ?, ?, ?, ?)"
                );
                // file_path is kept for backward compat but won't be used for serving
                $stmt_media->execute([$trip_id, 'db_stored', $file_type, $file_data, $mime_type]);
            }
        }

        $pdo->commit();
        echo json_encode(['success' => 'Trip and media uploaded successfully!']);

    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Upload failed: ' . $e->getMessage()]);
    }
}
?>
