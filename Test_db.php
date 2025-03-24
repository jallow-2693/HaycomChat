<?php
$host = "sql212.byetcluster.com"; // Ton hôte
$username = "if0_38591142"; // Ton nom d’utilisateur
$password = "ton_mot_de_passe"; // Ton mot de passe
$dbname = "if0_38591142_Hay_comChat_"; // Le nom exact de ta base de données

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
echo "Connexion réussie à la base de données !";
?>
