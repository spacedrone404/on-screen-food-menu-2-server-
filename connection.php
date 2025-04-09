<?php
try {
    $dsn = 'pgsql:host=localhost;port=5432;dbname=menus';
    $username = 'postgres'; 
    $password = '';

    $pdo = new PDO($dsn, $username, $password);
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Error while connecting: " . $e->getMessage();
}