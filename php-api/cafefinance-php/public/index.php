<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../Controller/HealthController.php';
require_once __DIR__ . '/../Controller/UserController.php';

header('Content-Type: application/json');

function json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$routeKey = "{$method} {$path}";
$routes = require __DIR__ . '/../routes/api.php';

try {
    if (!isset($routes[$routeKey])) {
        json_response([
            'status' => 'error',
            'message' => 'Rota nao encontrada',
        ], 404);
    }

    $db = Database::connect();
    $user = new User($db);
    $controllers = [
        'health' => new HealthController($db),
        'users' => new UserController($user),
    ];

    json_response($routes[$routeKey]($controllers));
} catch (Throwable $error) {
    json_response([
        'status' => 'error',
        'message' => 'Erro ao processar a requisicao',
        'detail' => $error->getMessage(),
    ], 500);
}
