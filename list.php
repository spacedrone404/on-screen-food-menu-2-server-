<?php
header('Content-Type: application/json');

// Add CORS headers for Vercel frontend
header('Access-Control-Allow-Origin: *'); // Replace * with your Vercel domain in production
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Use environment variables from Railway (Private Network)
$databaseUrl = getenv('DATABASE_URL'); // Provided by Railway for Postgres service

if ($databaseUrl) {
    // Parse DATABASE_URL (e.g., postgresql://postgres:PASSWORD@postgres.railway.internal:5432/railway)
    $dbParams = parse_url($databaseUrl);
    $host = $dbParams['host'] ?? 'postgres.railway.internal';
    $port = $dbParams['port'] ?? '5432';
    $dbname = ltrim($dbParams['path'], '/') ?? 'railway';
    $user = $dbParams['user'] ?? 'postgres';
    $password = $dbParams['pass'] ?? 'NBYeLnVnzvXbktTRLIlNPeUUMhFdaTDz';
} else {
    // Fallback for local testing
    $host = getenv('PGHOST') ?: 'localhost';
    $port = getenv('PGPORT') ?: '5432';
    $dbname = getenv('PGDATABASE') ?: 'railway';
    $user = getenv('PGUSER') ?: 'postgres';
    $password = getenv('PGPASSWORD') ?: '';
}

try {
    // Connect using parsed credentials
    $dbh = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Better error handling

    // Handle GET request (fetching data)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['category'])) {
        $category = $_GET['category'];
        
        $stmt = $dbh->prepare('SELECT id, code, weight, title, description, price FROM dinnermenus WHERE category = :category');
        $stmt->execute([':category' => $category]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    }
    
    // Handle POST request (updating data)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['id'])) {
            $stmt = $dbh->prepare('
                UPDATE dinnermenus 
                SET code = :code, 
                    category = :category,
                    title = :title,
                    description = :description,
                    weight = :weight,
                    price = :price
                WHERE id = :id
            ');
            
            $result = $stmt->execute([
                ':id' => $input['id'],
                ':code' => $input['code'],
                ':category' => $input['category'],
                ':title' => $input['title'],
                ':description' => $input['description'],
                ':weight' => $input['weight'],
                ':price' => $input['price']
            ]);
            
            echo json_encode([
                'success' => $result,
                'error' => $result ? null : 'Update failed'
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No ID provided']);
        }
    }
    
    // Handle DELETE request (deleting data)
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['id'])) {
            $stmt = $dbh->prepare('DELETE FROM dinnermenus WHERE id = :id');
            $result = $stmt->execute([':id' => $input['id']]);
            
            echo json_encode([
                'success' => $result,
                'error' => $result ? null : 'Delete failed'
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No ID provided']);
        }
    }
    
} catch (PDOException $e) {
    http_response_code(500); // Set proper HTTP status code for errors
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>