<?php
require_once 'db.php';
require_once 'config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// GET — Public: fetch approved reviews | Admin: fetch pending reviews
if ($method === 'GET') {
    $mode = $_GET['mode'] ?? 'public';
    
    try {
        if ($mode === 'pending') {
            $stmt = $pdo->query("SELECT id, name, route, rating, message, photo, created_at FROM reviews WHERE approved = 0 ORDER BY created_at DESC");
        } elseif ($mode === 'approved') {
            $stmt = $pdo->query("SELECT id, name, route, rating, message, photo, created_at FROM reviews WHERE approved = 1 ORDER BY created_at DESC");
        } else {
            $stmt = $pdo->query("SELECT name, route, rating, message, photo, created_at FROM reviews WHERE approved = 1 ORDER BY created_at DESC LIMIT 20");
        }
        $reviews = $stmt->fetchAll();
        echo json_encode($reviews);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// POST — Submit a new review or admin actions
if ($method === 'POST') {
    $action = $_POST['action'] ?? 'submit';

    // --- Admin: Approve a review ---
    if ($action === 'approve') {
        $password = $_POST['password'] ?? '';
        $reviewId = intval($_POST['review_id'] ?? 0);

        if (!password_verify($password, $admin_password_hash)) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid admin password.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE reviews SET approved = 1 WHERE id = ?");
            $stmt->execute([$reviewId]);
            echo json_encode(['success' => 'Review approved!']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // --- Admin: Delete a review ---
    if ($action === 'delete') {
        $password = $_POST['password'] ?? '';
        $reviewId = intval($_POST['review_id'] ?? 0);

        if (!password_verify($password, $admin_password_hash)) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid admin password.']);
            exit;
        }

        try {
            // Delete the photo file if exists
            $stmt = $pdo->prepare("SELECT photo FROM reviews WHERE id = ?");
            $stmt->execute([$reviewId]);
            $review = $stmt->fetch();
            if ($review && $review['photo'] && file_exists($review['photo'])) {
                unlink($review['photo']);
            }

            $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->execute([$reviewId]);
            echo json_encode(['success' => 'Review deleted.']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // --- Public: Submit a new review ---
    $name = trim($_POST['name'] ?? '');
    $route = trim($_POST['route'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($route) || empty($message)) {
        http_response_code(400);
        echo json_encode(['error' => 'Please fill in all fields.']);
        exit;
    }

    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }

    // Handle optional photo upload
    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $fileType = $_FILES['photo']['type'];
        $fileSize = $_FILES['photo']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['error' => 'Only JPG, PNG, WebP, and GIF images are allowed.']);
            exit;
        }

        if ($fileSize > 5 * 1024 * 1024) { // 5MB max
            http_response_code(400);
            echo json_encode(['error' => 'Image must be under 5MB.']);
            exit;
        }

        $uploadDir = 'uploads/reviews/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = 'review_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $photoPath = $uploadDir . $filename;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to upload image.']);
            exit;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (name, route, rating, message, photo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $route, $rating, $message, $photoPath]);
        echo json_encode(['success' => 'Thank you for your review! It will appear after approval.']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to submit review: ' . $e->getMessage()]);
    }
}
?>
