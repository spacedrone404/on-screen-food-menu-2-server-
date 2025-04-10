<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$dbUrl = getenv('DATABASE_URL');
if (!$dbUrl) {
    http_response_code(500);
    echo json_encode(['error' => 'No DATABASE_URL']);
    exit;
}

$urlParts = parse_url($dbUrl);
$host = $urlParts['host'] ?? null;
$port = $urlParts['port'] ?? 5432;
$dbname = ltrim($urlParts['path'] ?? '', '/');
$user = $urlParts['user'] ?? null;
$password = $urlParts['pass'] ?? null;

if (!$host || !$dbname || !$user || !$password) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid DATABASE_URL format']);
    exit;
}

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
try {
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/data') {
    $stmt = $conn->query("SELECT * FROM dinnermenus");
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Query failed']);
        exit;
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data ?: []);
} else {
    echo json_encode(['message' => 'API root']);
}
?>