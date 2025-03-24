<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Mettre à jour l'activité de l'utilisateur
$pdo->prepare("UPDATE users SET last_active = NOW() WHERE id = ?")->execute([$_SESSION["user_id"]]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - HaycomChat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container { display: flex; }
        .chat-box { flex: 2; height: 400px; overflow-y: auto; border: 1px solid #ddd; background: #fff; padding: 10px; }
        .users-online { flex: 1; padding: 10px; background: #f8f9fa; border-left: 1px solid #ddd; }
        .dark-mode .users-online { background: #222; color: white; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">HaycomChat</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <button class="btn btn-secondary me-2" id="toggle-dark-mode">🌙 Mode Sombre</button>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4 chat-container">
    <!-- Chat général -->
    <div class="chat-box" id="chat-box"></div>

    <!-- Liste des utilisateurs en ligne -->
    <div class="users-online">
        <h5>Utilisateurs en ligne 🟢</h5>
        <div id="users-online"></div>
    </div>
</div>

<form id="chat-form" method="post" class="mt-3">
    <div class="input-group">
        <input type="text" id="message" name="message" class="form-control" placeholder="Écrire un message..." required>
        <button class="btn btn-primary" type="submit">Envoyer</button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    function loadMessages() {
        $.ajax({ url: "load_messages.php", success: function(data) {
            $("#chat-box").html(data);
            $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
        }});
    }

    function loadUsersOnline() {
        $.ajax({ url: "load_users_online.php", success: function(data) {
            $("#users-online").html(data);
        }});
    }

    $("#chat-form").submit(function(e) {
        e.preventDefault();
        $.post("chat.php", $(this).serialize(), function() {
            $("#message").val("");
            loadMessages();
        });
    });

    setInterval(loadMessages, 2000);
    setInterval(loadUsersOnline, 5000);
    loadMessages();
    loadUsersOnline();
});
</script>

</body>
</html>
