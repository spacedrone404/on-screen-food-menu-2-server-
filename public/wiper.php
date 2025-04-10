<?php
$dsn = 'pgsql:host=localhost;port=5432;dbname=menus;user=postgres;password=';
try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $input = json_decode(file_get_contents('php://input'), true);
        $code = $input['code'];

        $stmt = $pdo->prepare('DELETE FROM "dinnermenus" WHERE code = :code');
        $stmt->execute(['code' => $code]);

        $affectedRows = $stmt->rowCount();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'affectedRows' => $affectedRows]);
    }
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Error connecting to the database: ' . $e->getMessage()
    ]);
}
?>