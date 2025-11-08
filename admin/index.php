<?php
require_once '../config.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin - Blog</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Painel Administrativo</h1>
        <p><a href="novo.php">+ Novo Post</a> | <a href="../index.php">Ver Blog</a> | <a href="login.php?sair=1">Sair</a></p>
        <hr>

        <?php if ($result->num_rows > 0): ?>
            <table border="1" cellpadding="8" style="width:100%; border-collapse:collapse;">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
                <?php while ($post = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $post['id'] ?></td>
                        <td><?= htmlspecialchars($post['titulo']) ?></td>
                        <td><?= date('d/m/Y', strtotime($post['created_at'])) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $post['id'] ?>">Editar</a> |
                            <a href="excluir.php?id=<?= $post['id'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Nenhum post cadastrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
