<?php
require 'db.php';

$messages = $pdo->query("SELECT users.username, messages.message, messages.sent_at 
                         FROM messages JOIN users ON messages.user_id = users.id 
                         ORDER BY messages.sent_at ASC")->fetchAll();

foreach ($messages as $msg): ?>
    <div class="border-bottom p-2">
        <strong><?= htmlspecialchars($msg["username"]) ?>:</strong> <?= htmlspecialchars($msg["message"]) ?>
        <small class="text-muted float-end"><?= $msg["sent_at"] ?></small>
    </div>
<?php endforeach; ?>
