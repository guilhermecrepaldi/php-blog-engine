<?php
require_once '../config.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscar post
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo 'Post não encontrado.';
    echo '<a href="index.php">Voltar</a>';
    exit;
}

$post = $result->fetch_assoc();
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $conteudo = $_POST['conteudo'] ?? '';

    if (!empty($titulo) && !empty($conteudo)) {
        $stmt = $conn->prepare("UPDATE posts SET titulo = ?, conteudo = ? WHERE id = ?");
        $stmt->bind_param("ssi", $titulo, $conteudo, $id);

        if ($stmt->execute()) {
            $mensagem = 'Post atualizado com sucesso!';
            // Recarrega os dados
            $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $post = $result->fetch_assoc();
        } else {
            $mensagem = 'Erro ao atualizar: ' . $conn->error;
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
    <title>Editar Post - Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Post</h1>
        <p><a href="index.php">&larr; Voltar</a></p>
        <hr>

        <?php if ($mensagem): ?>
            <p style="color: green;"><?= $mensagem ?></p>
        <?php endif; ?>

        <form method="post">
            <p>
                <label>Título:<br>
                <input type="text" name="titulo" required size="60" maxlength="200" value="<?= htmlspecialchars($post['titulo']) ?>">
                </label>
            </p>
            <p>
                <label>Conteúdo:<br>
                <textarea name="conteudo" rows="15" cols="80" required><?= htmlspecialchars($post['conteudo']) ?></textarea>
                </label>
            </p>
            <p><button type="submit">Atualizar</button></p>
        </form>
    </div>
</body>
</html>
