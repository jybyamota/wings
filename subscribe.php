<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

if ($email === false) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$dataDir = __DIR__ . '/data';

if (!is_dir($dataDir) && !mkdir($dataDir, 0755, true) && !is_dir($dataDir)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Unable to save subscription.']);
    exit;
}

$file = $dataDir . '/subscribers.txt';
$line = date('c') . "\t" . $email . PHP_EOL;

if (file_put_contents($file, $line, FILE_APPEND | LOCK_EX) === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Unable to save subscription.']);
    exit;
}

echo json_encode(['ok' => true, 'message' => 'Thank you for subscribing!']);
