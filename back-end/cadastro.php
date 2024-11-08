<?php
include '../back-end/db/conexao.php';

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $foto_perfil = NULL;

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $foto_nome = $_FILES['foto_perfil']['name'];
        $foto_tmp = $_FILES['foto_perfil']['tmp_name'];
        $foto_destino = 'uploads/' . basename($foto_nome);

        if (move_uploaded_file($foto_tmp, $foto_destino)) {
            $foto_perfil = $foto_destino;
        } else {
            echo "Erro ao fazer upload da foto.";
        }
    }

    $sql = "INSERT INTO usuarios (nome, email, senha, foto_perfil) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $senha, $foto_perfil);

    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff8f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        #main-cadastro {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #ff6600;
        }
        .btn-laranja {
            background-color: #ff6600;
            color: white;
            width: 100%;
        }
        .btn-laranja:hover {
            background-color: #ff4500;
            color: white;

        }
        #a-login {
            color: #ff6600;
        }
    </style>
</head>
<body>

<div id="main-cadastro">
    <h2>Cadastro</h2>
    <form method="POST" action="cadastro.php" enctype="multipart/form-data">
        <div class="mb-3">
            <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" required>
        </div>
        <div class="mb-3">
            <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required>
        </div>
        <div class="mb-3">
            <input type="file" name="foto_perfil" id="foto_perfil" class="form-control" placeholder="Foto">
        </div>
        <button type="submit" class="btn btn-laranja">Cadastrar</button>
    </form>
    <p>Já tem uma conta? <a id="a-login" href="login.php">Faça login</a></p>

</div>

</body>
</html>
