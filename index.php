<?php
require_once 'config.php';

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 5;
$offset = ($pagina - 1) * $por_pagina;

// Total de posts pra paginacao
$total_result = $conn->query("SELECT COUNT(*) as total FROM posts");
$total_posts = $total_result->fetch_assoc()['total'];
$total_paginas = ceil($total_posts / $por_pagina);

// Buscar posts da pagina atual
$stmt = $conn->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5 OFFSET ?");
$stmt->bind_param("i", $offset);
$stmt->execute();
$result = $stmt->get_result();
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

            <!-- Paginacao -->
            <div class="paginacao">
                <?php if ($pagina > 1): ?>
                    <a href="?pagina=<?= $pagina - 1 ?>">&laquo; Anterior</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <?php if ($i == $pagina): ?>
                        <strong><?= $i ?></strong>
                    <?php else: ?>
                        <a href="?pagina=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($pagina < $total_paginas): ?>
                    <a href="?pagina=<?= $pagina + 1 ?>">Próxima &raquo;</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Nenhum post publicado ainda.</p>
        <?php endif; ?>
    </div>
</body>
</html>
