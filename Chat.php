<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

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
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .dark-mode .navbar, .dark-mode .card {
            background-color: #1f1f1f !important;
            color: white;
        }
        .dark-mode .chat-box {
            background-color: #222;
            color: white;
            border: 1px solid #444;
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
                    <button class="btn btn-secondary me-2" id="toggle-dark-mode">🌙 Mode Sombre</button>
                </li>
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
    <div class="chat-box mt-3 p-3" id="chat-box" style="height: 400px; overflow-y: auto; border: 1px solid #ddd; background: #fff;">
        <!-- Messages chargés via AJAX -->
    </div>

    <!-- Formulaire d'envoi -->
    <form id="chat-form" method="post" class="mt-3">
        <div class="input-group">
            <input type="text" id="message" name="message" class="form-control" placeholder="Écrire un message..." required>
            <button class="btn btn-primary" type="submit">Envoyer</button>
        </div>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script Mode Sombre + AJAX -->
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

    setInterval(loadMessages, 2000);
    loadMessages();

    // Mode sombre
    const darkModeButton = document.getElementById("toggle-dark-mode");
    const body = document.body;

    // Vérifie si le mode sombre est activé
    if (localStorage.getItem("darkMode") === "enabled") {
        body.classList.add("dark-mode");
    }

    darkModeButton.addEventListener("click", function() {
        body.classList.toggle("dark-mode");

        if (body.classList.contains("dark-mode")) {
            localStorage.setItem("darkMode", "enabled");
        } else {
            localStorage.setItem("darkMode", "disabled");
        }
    });
});
</script>

</body>
</html>
