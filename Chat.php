<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Gestion de l'envoi des messages
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["message"])) {
    $message = htmlspecialchars($_POST["message"]);
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->execute([$_SESSION["user_id"], $message]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - HaycomChat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background: #fff;
        }
    </style>
</head>
<body class="bg-light">

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">HaycomChat</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu -->
<div class="container mt-4">
    <h2 class="text-center">Bienvenue, <?= htmlspecialchars($_SESSION["username"]) ?> !</h2>

    <!-- Boîte de chat -->
    <div class="chat-box mt-3" id="chat-box">
        <!-- Les messages seront chargés ici via AJAX -->
    </div>

    <!-- Formulaire d'envoi de message -->
    <form id="chat-form" method="post" class="mt-3">
        <div class="input-group">
            <input type="text" id="message" name="message" class="form-control" placeholder="Écrire un message..." required>
            <button class="btn btn-primary" type="submit">Envoyer</button>
        </div>
    </form>
</div>

<!-- Script Bootstrap & jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script AJAX pour recharger les messages sans recharger la page -->
<script>
$(document).ready(function() {
    function loadMessages() {
        $.ajax({
            url: "load_messages.php",
            success: function(data) {
                $("#chat-box").html(data);
                $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
            }
        });
    }

    $("#chat-form").submit(function(e) {
        e.preventDefault();
        $.post("chat.php", $(this).serialize(), function() {
            $("#message").val("");
            loadMessages();
        });
    });

    // Charger les messages toutes les 2 secondes
    setInterval(loadMessages, 2000);

    // Charger les messages au démarrage
    loadMessages();
});
</script>

</body>
</html>
