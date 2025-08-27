<?php
// Database configuration template
// Copy this file to config.php and update with your actual credentials

$host = 'localhost';
$dbname = 'pokemon_db';
$username = 'root';
$password = 'YOUR_PASSWORD_HERE'; // Replace with your actual MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
