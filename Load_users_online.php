<?php
require 'db.php';

$users = $pdo->query("SELECT username FROM users WHERE last_active > (NOW() - INTERVAL 5 MINUTE)")->fetchAll();

foreach ($users as $user) {
    echo "<p>🟢 " . htmlspecialchars($user["username"]) . "</p>";
}
?>
