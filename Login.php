<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: chat.php");
        exit;
    } else {
        echo "Identifiants incorrects.";
    }
}
?>

<form method="post">
    Nom d'utilisateur : <input type="text" name="username" required><br>
    Mot de passe : <input type="password" name="password" required><br>
    <button type="submit">Se connecter</button>
</form>
