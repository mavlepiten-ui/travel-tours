<?php
/**
 * serve_media.php — Serves media files stored in the database (LONGBLOB).
 * Usage: serve_media.php?id=<trip_media_id>
 * 
 * This replaces the old filesystem-based approach so that media
 * persists across Render (or any ephemeral) deployments.
 */
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo 'Invalid media ID.';
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT file_data, mime_type, file_type FROM trip_media WHERE id = ?");
    $stmt->execute([$id]);
    $media = $stmt->fetch();

    if (!$media || empty($media['file_data'])) {
        http_response_code(404);
        echo 'Media not found.';
        exit;
    }

    $mime = $media['mime_type'] ?: ($media['file_type'] === 'video' ? 'video/mp4' : 'image/jpeg');
    $fileSize = strlen($media['file_data']);

    // Handle range requests for video streaming (scrubbing, seeking)
    if (isset($_SERVER['HTTP_RANGE'])) {
        $range = $_SERVER['HTTP_RANGE'];
        preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);
        $start = intval($matches[1]);
        $end = !empty($matches[2]) ? intval($matches[2]) : $fileSize - 1;

        if ($start > $end || $start >= $fileSize) {
            http_response_code(416); // Range Not Satisfiable
            header("Content-Range: bytes */$fileSize");
            exit;
        }

        $length = $end - $start + 1;
        http_response_code(206); // Partial Content
        header("Content-Range: bytes $start-$end/$fileSize");
        header("Content-Length: $length");
        header("Content-Type: $mime");
        header("Accept-Ranges: bytes");
        header("Cache-Control: public, max-age=86400");
        echo substr($media['file_data'], $start, $length);
    } else {
        // Full file response
        header("Content-Type: $mime");
        header("Content-Length: $fileSize");
        header("Accept-Ranges: bytes");
        header("Cache-Control: public, max-age=86400");
        echo $media['file_data'];
    }
} catch (Exception $e) {
    http_response_code(500);
    echo 'Server error.';
}
?>
