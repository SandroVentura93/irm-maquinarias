<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=irm_db', 'root', '');
    $stmt = $pdo->query('DESCRIBE ventas');
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($cols, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
