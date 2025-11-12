<?php
require_once 'config.php';

$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Blog</h1>
        <hr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($post = $result->fetch_assoc()): ?>
                <article>
                    <h2><a href="post.php?slug=<?= $post['slug'] ?>"><?= htmlspecialchars($post['titulo']) ?></a></h2>
                    <p class="data"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></p>
                    <p><?= nl2br(htmlspecialchars(substr($post['conteudo'], 0, 200))) ?>...</p>
                    <a href="post.php?slug=<?= $post['slug'] ?>">Leia mais</a>
                </article>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum post publicado ainda.</p>
        <?php endif; ?>
    </div>
</body>
</html>
