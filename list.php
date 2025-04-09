<?php
header('Content-Type: application/json');

$host = 'localhost'; 
$dbname = 'menus';   
$user = 'postgres';  
$password = '';      

try {
    $dbh = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    
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
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
