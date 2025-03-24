<?php
$host = 'sql212.byetcluster.com'; // 
$dbname = 'if0_38591142_haycomchat'; 
$username = 'if0_38591142'; 
$password = '134662693'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>
