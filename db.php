<?php
$host = 'sql306.epizy.com'; // Remplace par ton hôte MySQL si différent
$dbname = 'if0_38591142_haycomchat'; // Nom complet de ta base de données
$username = 'if0_38591142'; // Nom d’utilisateur MySQL fourni par InfinityFree
$password = 'TON_MOT_DE_PASSE_ICI'; // Mets ici ton vrai mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>
