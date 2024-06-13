<?php 
    global $pdo;
    require_once ("conexao.php");
    require_once ("Usuarios.php");

    $usuario = new Usuarios($pdo);

    // Obtém o ID do usuário a partir da URL
    $id = $_GET['id'];

    // Prepara a consulta SQL para buscar os dados do usuário
    $pessoa = $pdo->prepare("SELECT * from usuarios Where id = :id");
    $pessoa->execute([':id' => $id]);
    $dados = $pessoa->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitiza e valida os dados do formulário
        $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $senha = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_SPECIAL_CHARS);
        
        // Chama a função de atualização do usuário
        $atualizar = $usuario->atualizar($id, $nome, $email, $senha);

        // Define a mensagem de sucesso ou erro
        if ($atualizar) {
            $mensagem = "Usuário atualizado com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar o usuário.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h2>Editar Usuário</h2>
        <form method="post">
            <label for="nome">Nome Completo:</label>
            <input type="text" name="nome" id="idnome" value="<?= $dados['nome'] ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="idemail" value="<?= $dados['email'] ?>" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="idsenha" required>

            <input type="submit" value="Atualizar">
        </form>
        <?php if (isset($mensagem)) echo "<p>$mensagem</p>"; ?>
        <button onclick="javascript:window.location.href='index.php'">Voltar para lista</button>
    </main>
</body>
</html>