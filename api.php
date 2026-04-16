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
            $media_stmt = $pdo->prepare("SELECT file_path, file_type FROM trip_media WHERE trip_id = ?");
            $media_stmt->execute([$trip['id']]);
            $trip['media'] = $media_stmt->fetchAll();
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

    // Create uploads directory if it doesn't exist
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    try {
        $pdo->beginTransaction();

        // 1. Insert Trip
        $stmt = $pdo->prepare("INSERT INTO trips (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);
        $trip_id = $pdo->lastInsertId();

        // 2. Process Files
        $files = $_FILES['media'];
        $file_count = count($files['name']);

        for ($i = 0; $i < $file_count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

            $file_name = $files['name'][$i];
            $tmp_name = $files['tmp_name'][$i];
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Generate unique filename
            $new_filename = uniqid() . '.' . $extension;
            $target_path = $upload_dir . $new_filename;

            // Determine file type
            $video_exts = ['mp4', 'webm', 'ogg', 'mov'];
            $file_type = in_array($extension, $video_exts) ? 'video' : 'image';

            if (move_uploaded_file($tmp_name, $target_path)) {
                $stmt_media = $pdo->prepare("INSERT INTO trip_media (trip_id, file_path, file_type) VALUES (?, ?, ?)");
                $stmt_media->execute([$trip_id, $target_path, $file_type]);
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

