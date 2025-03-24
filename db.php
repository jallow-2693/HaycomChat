<?php
try {
    $pdo = new PDO('mysql:host=sqlxxx.epizy.com;dbname=nom_de_ta_base', 'nom_utilisateur', 'mot_de_passe');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
?>
