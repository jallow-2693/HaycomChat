<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Afficher les amis
$stmt = $pdo->prepare("SELECT u.username FROM friends f
    JOIN users u ON u.id = CASE WHEN f.user_id1 = ? THEN f.user_id2 ELSE f.user_id1 END");
$stmt->execute([$user_id]);
$friends = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Amis - HaycomChat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .list-group-item .btn {
            margin-left: 10px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="text-center">Liste des Amis</h2>
    <?php if (empty($friends)): ?>
        <div class="alert alert-info">Vous n'avez pas d'amis pour le moment.</div>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($friends as $friend): ?>
                <li class="list-group-item">
                    <?= htmlspecialchars($friend["username"]) ?>
                    <a href="chat.php?friend_id=<?= $friend["id"] ?>" class="btn btn-primary btn-sm">Discuter</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
