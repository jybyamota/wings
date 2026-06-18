<?php
// Simple image viewer for payment screenshots
if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('No file specified');
}

$filename = basename($_GET['file']); // Prevent directory traversal
$filepath = __DIR__ . '/data/uploads/' . $filename;

// Security check
if (!file_exists($filepath) || strpos(realpath($filepath), realpath(__DIR__ . '/data/uploads/')) !== 0) {
    http_response_code(404);
    exit('File not found');
}

// Determine MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $filepath);
finfo_close($finfo);

// Serve the file
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: public, max-age=3600');
readfile($filepath);
