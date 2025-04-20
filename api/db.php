<?php

declare(strict_types=1);

$host = 'localhost';
$user = 'test_user';
$pass = 'test_password';
$dbName = 'course_catalog';

$mysqli = new mysqli($host, $user, $pass, $dbName);

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}