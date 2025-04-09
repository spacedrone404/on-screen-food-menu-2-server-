<?php

$host = 'localhost'; 
$dbname = 'menus';   
$user = 'postgres';  
$password = '';      

try {
    
    $dbh = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    
       
    if (!empty($_GET['category'])) {
        $category = $_GET['category'];
        
        
        $stmt = $dbh->prepare('SELECT weight, title, description, price FROM dinnermenus WHERE category = :category');
        $stmt->execute([':category' => $category]);
                
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
        echo json_encode($results);
    } 
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>