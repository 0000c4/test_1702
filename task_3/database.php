<?php
try {
    $pdo = new PDO('sqlite:visitors.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS visits (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ip TEXT NOT NULL,
        city TEXT,
        country TEXT,
        device TEXT,
        timestamp DATETIME
    )");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>