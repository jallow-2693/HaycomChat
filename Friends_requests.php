<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Envoyer une demande d'ami
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["receiver_id"])) {
    $receiver_id = $_POST["receiver_id"];
    $stmt = $pdo->prepare("INSERT INTO friend_requests (sender_id, receiver_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $receiver_id]);
    echo "<div class='alert alert-success'>Demande envoyée!</div>";
}

// Accepter ou rejeter une demande
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["request_id"])) {
    $request_id = $_GET["request_id"];
    $action = $_GET["action"];

    if ($action == "accept") {
        $stmt = $pdo->prepare("SELECT sender_id, receiver_id FROM friend_requests WHERE id = ?");
        $stmt->execute([$request_id]);
        $request = $stmt->fetch();

        if ($request) {
            $sender_id = $request["sender_id"];
            $receiver_id = $request["receiver_id"];

            // Ajouter la relation d'amitié
            $stmt = $pdo->prepare("INSERT INTO friends (user_id1, user_id2) VALUES (?, ?)");
            $stmt->execute([$sender_id, $receiver_id]);

            // Mettre à jour la demande d'ami
            $stmt = $pdo->prepare("UPDATE friend_requests SET status = 'accepted' WHERE id = ?");
            $stmt->execute([$request_id]);
        }
    } elseif ($action == "reject") {
        $stmt = $pdo->prepare("UPDATE friend_requests SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$request_id]);
    }
}

// Afficher les demandes d'ami
$stmt = $pdo->prepare("SELECT * FROM friend_requests WHERE receiver_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes d'Ami - HaycomChat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="text-center">Demandes d'Ami</h2>

    <h4 class="mt-4">Envoyer une demande d'ami</h4>
    <form method="POST">
        <div class="mb-3">
            <label for="receiver_id" class="form-label">ID de l'utilisateur</label>
            <input type="number" class="form-control" id="receiver_id" name="receiver_id" required>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer la demande</button>
    </form>

    <h4 class="mt-4">Demandes reçues</h4>
    <?php if (empty($requests)): ?>
        <div class="alert alert-info">Aucune demande d'ami en attente.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($requests as $request): ?>
                <?php
                    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
                    $stmt->execute([$request["sender_id"]]);
                    $sender = $stmt->fetch();
                ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($sender["username"]) ?></h5>
                        <p class="card-text">Vous avez une demande d'ami en attente.</p>
                        <a href="demandes_ami.php?action=accept&request_id=<?= $request["id"] ?>" class="btn btn-success">Accepter</a>
                        <a href="demandes_ami.php?action=reject&request_id=<?= $request["id"] ?>" class="btn btn-danger">Rejeter</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
