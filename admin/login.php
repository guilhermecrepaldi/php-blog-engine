<?php
require_once '../config.php';

// Logout
if (isset($_GET['sair'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        if (password_verify($senha, $usuario['senha_hash'])) {
            $_SESSION['logado'] = true;
            header('Location: index.php');
            exit;
        }
    }

    $erro = 'Email ou senha inválidos.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <?php if ($erro): ?>
            <p style="color: red;"><?= $erro ?></p>
        <?php endif; ?>

        <form method="post">
            <p>
                <label>Email:<br>
                <input type="email" name="email" required size="40">
                </label>
            </p>
            <p>
                <label>Senha:<br>
                <input type="password" name="senha" required size="40">
                </label>
            </p>
            <p><button type="submit">Entrar</button></p>
        </form>
    </div>
</body>
</html>
