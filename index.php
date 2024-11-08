<?php 
include './back-end/db/conexao.php';

// Verifica se foi selecionada uma letra
$letra = isset($_GET['letra']) ? $_GET['letra'] : '';

// Consulta com filtro de letra e ordem alfabética
$sql = "SELECT * FROM termos WHERE termo LIKE '{$letra}%' ORDER BY termo ASC";
$result = $conn->query($sql);

// Consulta para obter todas as letras do alfabeto
$alfabeto = range('A', 'Z');

// Informações do usuário (como exemplo, pode ser dinâmico se estiver vindo do banco de dados)
$usuario_nome = 'MARCIA CRISTINA MANGANARO FERRO'; // Pode ser dinâmico com dados do banco
$usuario_foto = './images/marcia.jpg'; // Substitua pela URL da foto do usuário
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dicionário Matemático</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos do botão */
.tooltip-btn {
    position: relative;
    margin: 5px;
}

/* Estilos do tooltip */
.tooltip-text {
    visibility: hidden;
    background-color: #ff4500;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 5px;
    position: absolute;
    top: 125%;
    left: -25px;
    transform: translateX(-50%);
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 12px;
}

/* Exibe o tooltip ao passar o mouse */
.tooltip-btn:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}
    </style>
</head>

<body class="bg-light">
    <!-- Barra de navegação fixa no topo -->
    <!-- Barra de navegação fixa no topo -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
<div class="container-fluid">
    <a class="navbar-brand" style="color: white;" href="#"><?php echo "Guia Matemático" ?>   </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul> 
      <a href="./back-end/index.php">
      <button class="btn btn-sm btn-laranja tooltip-btn" type="button">
    <i class="bi bi-gear-fill"></i>
    <span class="tooltip-text">Administrar termos</span>
</button>
</a>
    </div>
  </div>
</nav>


    <div class="container mt-5 pt-5">

        <!-- Sidebar -->
        <div class="row mb-4" id="sibebar">
            <div class="col-md-3">
                <div class="list-group">
                    <h5 class="text-center">Filtrar por Letra</h5>
                    <form method="GET" action="index.php">
                        <select name="letra" class="form-select mb-3" onchange="this.form.submit()">
                            <option value="">Ver Todos</option>
                            <?php foreach ($alfabeto as $letra_alphabet): ?>
                                <option value="<?= $letra_alphabet ?>" <?= $letra == $letra_alphabet ? 'selected' : '' ?>><?= $letra_alphabet ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Feed de Termos -->
            <div class="col-md-9">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <div class='card mb-4 shadow-sm border-0'>
                            <div class='card-body'>
                                <!-- Autor do Post -->
                                <div class='d-flex align-items-center mb-3'>
                                    <img src='{$usuario_foto}' alt='Foto de Perfil' class='rounded-circle' width='40' height='40'>
                                    <div class='ms-3'>
                                        <h6 class='m-0'>{$usuario_nome}</h6>
                                    </div>
                                </div>
                                <!-- Título e Definição -->
                                <h5 class='card-title'>{$row['termo']}</h5>
                                <p class='card-text'>{$row['definicao']}</p>";

                        // Verificar e exibir a imagem
                        if (!empty($row['imagem'])) {
                            $imagem_path = './back-end/uploads/' . basename($row['imagem']); // Caminho da imagem
                            if (file_exists($imagem_path)) {
                                echo "<img src='{$imagem_path}' alt='{$row['termo']}' class='img-fluid mb-3' style='max-height: 200px;'>";
                            } else {
                                echo "<p class='alert alert-warning'>Imagem não encontrada.</p>";
                            }
                        }

                        echo "</div></div>";
                    }
                } else {
                    echo "<p class='alert alert-info'>Nenhum termo encontrado para a letra '$letra'.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <button onclick="scrollToTop()" id="backToTopBtn" title="Voltar ao topo" class="btn btn-laranja">
    <i class="bi bi-arrow-up"></i>
</button>

    <!-- Rodapé -->
    <footer class=" text-white text-center py-4">
        <p>&copy; 2024 Dicionário Matemático</p>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
