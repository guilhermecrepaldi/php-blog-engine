<?php
require_once '../config.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $conteudo = $_POST['conteudo'] ?? '';

    if (!empty($titulo) && !empty($conteudo)) {
        // Slug simples por enquanto
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', str_replace(' ', '-', $titulo)));

        $stmt = $conn->prepare("INSERT INTO posts (titulo, conteudo, slug) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $titulo, $conteudo, $slug);

        if ($stmt->execute()) {
            $mensagem = 'Post criado com sucesso!';
        } else {
            $mensagem = 'Erro ao criar post: ' . $conn->error;
        }
    } else {
        $mensagem = 'Preencha todos os campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Post - Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Novo Post</h1>
        <p><a href="index.php">&larr; Voltar</a></p>
        <hr>

        <?php if ($mensagem): ?>
            <p style="color: green;"><?= $mensagem ?></p>
        <?php endif; ?>

        <form method="post">
            <p>
                <label>Título:<br>
                <input type="text" name="titulo" required size="60" maxlength="200">
                </label>
            </p>
            <p>
                <label>Conteúdo:<br>
                <textarea name="conteudo" rows="15" cols="80" required></textarea>
                </label>
            </p>
            <p><button type="submit">Salvar</button></p>
        </form>
    </div>
</body>
</html>
