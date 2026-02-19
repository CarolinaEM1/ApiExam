<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);



require_once 'services/PasswordService.php';

header("Content-Type: application/json");

$service = new PasswordService();

try {

    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    if ($method === 'GET' && strpos($uri, '/api/password') !== false) {

        $password = $service->generateSingle($_GET);

        echo json_encode([
            "success" => true,
            "data" => ["password" => $password]
        ]);
        exit;
    }

    if ($method === 'POST' && strpos($uri, '/api/passwords') !== false) {

        $data = json_decode(file_get_contents("php://input"), true);

        $passwords = $service->generateMultiple($data);

        echo json_encode([
            "success" => true,
            "data" => $passwords
        ]);
        exit;
    }

    if ($method === 'POST' && strpos($uri, '/api/password/validate') !== false) {

        $data = json_decode(file_get_contents("php://input"), true);

        $result = $service->validatePassword(
            $data['password'],
            $data['requirements'] ?? []
        );

        echo json_encode([
            "success" => true,
            "data" => $result
        ]);
        exit;
    }

    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);

} catch (InvalidArgumentException $e) {

    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => [
            "code" => 400,
            "message" => $e->getMessage()
        ]
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => [
            "code" => 500,
            "message" => "Error interno del servidor"
        ]
    ]);
}
