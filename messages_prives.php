<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"]) || !isset($_GET["user_id"])) {
    header("Location: chat.php");
    exit;
}

$receiver_id = $_GET["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["message"])) {
    $message = htmlspecialchars($_POST["message"]);
    $stmt = $pdo->prepare("INSERT INTO private_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION["user_id"], $receiver_id, $message]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages Privés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Discussion privée</h2>
    <div class="chat-box mt-3 p-3" id="chat-box" style="height: 400px; overflow-y: auto; border: 1px solid #ddd; background: #fff;">
        <!-- Messages chargés via AJAX -->
    </div>
    <form id="chat-form" method="post" class="mt-3">
        <div class="input-group">
            <input type="text" id="message" name="message" class="form-control" placeholder="Écrire un message..." required>
            <button class="btn btn-primary" type="submit">Envoyer</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadMessages() {
        $.ajax({
            url: "load_private_messages.php?user_id=<?= $receiver_id ?>",
            success: function(data) {
                $("#chat-box").html(data);
                $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
            }
        });
    }

    $("#chat-form").submit(function(e) {
        e.preventDefault();
        $.post("messages_prives.php?user_id=<?= $receiver_id ?>", $(this).serialize(), function() {
            $("#message").val("");
            loadMessages();
        });
    });

    setInterval(loadMessages, 2000);
    loadMessages();
});
</script>

</body>
</html>
