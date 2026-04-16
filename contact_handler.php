<?php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(strip_tags($_POST['name'] ?? ''));
    $phone = trim(strip_tags($_POST['phone'] ?? ''));
    $destination = trim(strip_tags($_POST['destination'] ?? ''));
    $date = trim($_POST['date'] ?? '');
    $passengers = intval($_POST['passengers'] ?? 1);
    $requests = trim(strip_tags($_POST['requests'] ?? ''));

    if (empty($name) || empty($phone) || empty($destination) || empty($date)) {
        http_response_code(400);
        echo json_encode(['error' => 'Please fill in all required fields.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (name, phone, destination, travel_date, passengers, requests) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $destination, $date, $passengers, $requests ?: null]);
        echo json_encode(['success' => 'Your booking request has been received! We will contact you shortly.']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to submit booking: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
