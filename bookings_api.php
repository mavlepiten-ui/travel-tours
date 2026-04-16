<?php
require_once 'db.php';
require_once 'config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// GET — fetch bookings
if ($method === 'GET') {
    $status = $_GET['status'] ?? 'all';
    try {
        if ($status === 'all') {
            $stmt = $pdo->query("SELECT * FROM bookings ORDER BY created_at DESC");
        } else {
            $stmt = $pdo->prepare("SELECT * FROM bookings WHERE status = ? ORDER BY created_at DESC");
            $stmt->execute([$status]);
        }
        echo json_encode($stmt->fetchAll());
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// POST — update booking status or delete
if ($method === 'POST') {
    $password = $_POST['password'] ?? '';
    if (!password_verify($password, $admin_password_hash)) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid admin password.']);
        exit;
    }

    $action = $_POST['action'] ?? '';
    $bookingId = intval($_POST['booking_id'] ?? 0);

    if ($action === 'update_status') {
        $newStatus = $_POST['status'] ?? 'confirmed';
        $allowed = ['new', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($newStatus, $allowed)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid status.']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
            $stmt->execute([$newStatus, $bookingId]);
            echo json_encode(['success' => 'Booking status updated to ' . $newStatus . '.']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    } elseif ($action === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt->execute([$bookingId]);
            echo json_encode(['success' => 'Booking deleted.']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>
