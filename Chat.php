<?php
require 'db.php';
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = htmlspecialchars($_POST["message"]);
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->execute([$_SESSION["user_id"], $message]);
}

$messages = $pdo->query("SELECT users.username, messages.message, messages.sent_at 
                         FROM messages JOIN users ON messages.user_id = users.id 
                         ORDER BY messages.sent_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head><title>Chat</title></head>
<body>
    <h1>Bienvenue, <?php echo $_SESSION["username"]; ?></h1>
    <form method="post">
        <input type="text" name="message" required>
        <button type="submit">Envoyer</button>
    </form>
    <h2>Messages :</h2>
    <?php foreach ($messages as $msg): ?>
        <p><strong><?= $msg["username"] ?>:</strong> <?= $msg["message"] ?> (<?= $msg["sent_at"] ?>)</p>
    <?php endforeach; ?>
    <br><a href="logout.php">Déconnexion</a>
</body>
</html>
