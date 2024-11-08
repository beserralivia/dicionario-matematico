<?php include 'db/conexao.php'; ?>
<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dicionário Matemático - Back-End</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light shadow-sm">
<div class="container-fluid">
    <a class="navbar-brand" style="color: white;" href="#"><?php echo "Guia Matemático" ?>   </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul> 
      <a href="../index.php">
    <button class="btn btn-sm btn-laranja" style="margin:5px;" type="button">Ir para o site</button>
</a>
<a href="logout.php">
    <button class="btn btn-sm btn-laranja" type="button"><i class="bi bi-box-arrow-right"></i></button>
</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
<?php 
// echo "<h1>Bem-vindo, " . $_SESSION['usuario_nome'] . "</h1>"; 
?>        

    <form method="POST" action="index.php" class="my-3">
    <div class="input-group mb-3">
    <input type="text" name="pesquisa" class="form-control" placeholder="Pesquisar termo..." aria-label="Pesquisar termo">
    <button class="btn btn-primary" type="submit">Pesquisar</button>
</div>
    </form>
    <a href="CRUD/adicionar.php" class="btn btn-success">Adicionar Novo Termo</a>

    <?php
    $sql = "SELECT * FROM termos";
    if (isset($_POST['pesquisa'])) {
        $pesquisa = $_POST['pesquisa'];
        $sql .= " WHERE termo LIKE '%$pesquisa%'";
    }
    $result = $conn->query($sql);

    // Diretório de imagens
    $diretorio_imagens = 'uploads/';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card my-3'>";
            echo "<div class='card-body'>";
            echo "<div class='d-flex justify-content-between align-items-center'>";
            echo "<h2 class='card-title'>{$row['termo']}</h2>";
            echo "<div>";
            echo "<a href='CRUD/editar.php?id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a> ";
            echo "<a href='CRUD/excluir.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Deseja excluir este termo?')\">Excluir</a>";
            echo "</div>";
            echo "</div>";
            echo "<p class='card-text'>{$row['definicao']}</p>";

            // Verificar e exibir a imagem
            if (!empty($row['imagem'])) {
                $imagem_path = $diretorio_imagens . basename($row['imagem']); // Garantir que o caminho da imagem esteja correto
                if (file_exists($imagem_path)) {
                    echo "<img src='{$imagem_path}' alt='{$row['termo']}' class='img-fluid' style='max-height: 200px;'>";
                } else {
                    echo "<p class='alert alert-warning'>Imagem não encontrada.</p>";
                }
            } else {
                echo "<p class='alert alert-warning'>Imagem não disponível.</p>";
            }

            echo "</div></div>";
        }
    } else {
        echo "<p class='alert alert-info'>Nenhum termo encontrado.</p>";
    }
    $conn->close();
    ?>

</div> <button onclick="scrollToTop()" id="backToTopBtn" title="Voltar ao topo" class="btn btn-laranja">
    <i class="bi bi-arrow-up"></i>
</button>
<footer class=" text-white text-center py-4">
        <p>&copy; 2024 Dicionário Matemáticos</p>
    </footer>
    <script>
    // Exibe o botão ao rolar a página
window.onscroll = function() {
    var backToTopBtn = document.getElementById("backToTopBtn");
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        backToTopBtn.style.display = "block";
    } else {
        backToTopBtn.style.display = "none";
    }
};

// Função para rolar até o topo
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dicionário Matemático - Back-End</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" style="color: white;" href="#"><?php echo "Guia Matemático" ?>   </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul> 
                <a href="../index.php">
                    <button class="btn btn-sm btn-laranja" style="margin:5px;" type="button">Ir para o site</button>
                </a>
                <a href="logout.php">
                    <button class="btn btn-sm btn-laranja" type="button"><i class="bi bi-box-arrow-right"></i></button>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <form method="POST" action="index.php" class="my-3">
            <div class="input-group mb-3">
                <input type="text" name="pesquisa" class="form-control" placeholder="Pesquisar termo..." aria-label="Pesquisar termo">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
            </div>
        </form>
        <a href="CRUD/adicionar.php" class="btn btn-success mb-3">Adicionar Novo Termo</a>

        <?php
        $sql = "SELECT * FROM termos";
        if (isset($_POST['pesquisa'])) {
            $pesquisa = $_POST['pesquisa'];
            $sql .= " WHERE termo LIKE '%$pesquisa%'";
        }
        $result = $conn->query($sql);

        // Diretório de imagens
        $diretorio_imagens = 'uploads/';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card my-3'>";
                echo "<div class='card-body'>";
                echo "<div class='d-flex justify-content-between align-items-center'>";
                echo "<h2 class='card-title'>{$row['termo']}</h2>";
                echo "<div>";
                echo "<a href='CRUD/editar.php?id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a> ";
                echo "<a href='CRUD/excluir.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Deseja excluir este termo?')\">Excluir</a>";
                echo "</div>";
                echo "</div>";
                echo "<p class='card-text'>{$row['definicao']}</p>";

                // Verificar e exibir a imagem
                if (!empty($row['imagem'])) {
                    $imagem_path = $diretorio_imagens . basename($row['imagem']);
                    if (file_exists($imagem_path)) {
                        echo "<img src='{$imagem_path}' alt='{$row['termo']}' class='img-fluid' style='max-height: 200px;'>";
                    } else {
                        echo "<p class='alert alert-warning'>Imagem não encontrada.</p>";
                    }
                } else {
                    echo "<p class='alert alert-warning'>Imagem não disponível.</p>";
                }

                echo "</div></div>";
            }
        } else {
            echo "<p class='alert alert-info'>Nenhum termo encontrado.</p>";
        }
        $conn->close();
        ?>

    </div>

    <!-- Botão de voltar ao topo -->
    <button onclick="scrollToTop()" id="backToTopBtn" title="Voltar ao topo" class="btn btn-laranja">
        <i class="bi bi-arrow-up"></i>
    </button>

    <footer class="text-white text-center py-4">
        <p>&copy; 2024 Dicionário Matemáticos</p>
    </footer>

    <script>
        // Exibe o botão ao rolar a página
        window.onscroll = function() {
            var backToTopBtn = document.getElementById("backToTopBtn");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                backToTopBtn.style.display = "block";
            } else {
                backToTopBtn.style.display = "none";
            }
        };

        // Função para rolar até o topo
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
