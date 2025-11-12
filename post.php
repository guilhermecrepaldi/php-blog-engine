<?php
require_once 'config.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

$stmt = $conn->prepare("SELECT * FROM posts WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Post não encontrado</h1>';
    echo '<a href="index.php">Voltar</a>';
    exit;
}

$post = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['titulo']) ?> - Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <p><a href="index.php">&larr; Voltar</a></p>
        <article>
            <h1><?= htmlspecialchars($post['titulo']) ?></h1>
            <p class="data"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></p>
            <div class="conteudo">
                <?= nl2br(htmlspecialchars($post['conteudo'])) ?>
            </div>
        </article>
    </div>
</body>
</html>
