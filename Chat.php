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
    <div class="chat-box" id="
