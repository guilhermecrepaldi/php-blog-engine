<?php
require_once '../config.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

function gerarSlug($texto) {
    // Tabela de acentos
    $acentos = [
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c',
        'ñ' => 'n',
        'Á' => 'a', 'À' => 'a', 'Ã' => 'a', 'Â' => 'a', 'Ä' => 'a',
        'É' => 'e', 'È' => 'e', 'Ê' => 'e', 'Ë' => 'e',
        'Í' => 'i', 'Ì' => 'i', 'Î' => 'i', 'Ï' => 'i',
        'Ó' => 'o', 'Ò' => 'o', 'Õ' => 'o', 'Ô' => 'o', 'Ö' => 'o',
        'Ú' => 'u', 'Ù' => 'u', 'Û' => 'u', 'Ü' => 'u',
        'Ç' => 'c',
        'Ñ' => 'n'
    ];
    $texto = strtr($texto, $acentos);
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9-]/', '-', $texto);
    $texto = preg_replace('/-+/', '-', $texto);
    $texto = trim($texto, '-');
    return $texto;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $conteudo = $_POST['conteudo'] ?? '';

    if (!empty($titulo) && !empty($conteudo)) {
        $slug = gerarSlug($titulo);

        // Verifica se slug ja existe
        $check = $conn->prepare("SELECT id FROM posts WHERE slug = ?");
        $check->bind_param("s", $slug);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $slug .= '-' . time();
        }

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
