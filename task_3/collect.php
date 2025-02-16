<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include 'database.php';

// Получение POST данных
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $ip = filter_var($data['ip'], FILTER_VALIDATE_IP);
    $city = htmlspecialchars($data['city']);
    $country = htmlspecialchars($data['country']);
    $device = htmlspecialchars($data['device']);
    $timestamp = htmlspecialchars($data['timestamp']);

    if ($ip) {
        $stmt = $pdo->prepare("INSERT INTO visits (ip, city, country, device, timestamp) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$ip, $city, $country, $device, $timestamp])) {
            http_response_code(201);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'invalid IP']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'no data received']);
}
?>