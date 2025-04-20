<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/CategoryController.php';
require_once __DIR__ . '/CourseController.php';

// Basic routing
$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = $uri[4] ?? null;
$id = isset($uri[1]) ? (int) $uri[1] : null;

switch ($resource) {
    case 'categories':
        $controller = new CategoryController($mysqli);
        break;

    case 'courses':
        $controller = new CourseController($mysqli);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Resource not found']);
        exit;
}

// Handle methods
switch ($method) {
    case 'GET':
        if ($id) {
            $controller->getById($id);
        } else {
            $controller->getAll();
        }
        break;

    case 'POST':
        $controller->create();
        break;

    case 'PUT':
        if ($id) {
            $controller->update($id);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
        }
        break;

    case 'DELETE':
        if ($id) {
            $controller->delete($id);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}