<?php 
session_start();

$conexon = mysqli_connect('localhost', 'root', '', 'produtos_cImg_bd');

if (!$conexon) {
    die("Erro na conexão: ".mysqli_connect_error());
}
$sql = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SERVER["REQUEST_METHOD"] != "POST") { 
        die("Acesso inválido."); 
    }
    
    $nome = trim($_POST["name"]); 
    $preco = str_replace(",", ".", $_POST["preco"]); 
    
    if ($nome == "" || !is_numeric($preco) || $preco < 0) { 
        die("Informe um nome e um preço válido."); 
    } 
    
    if (!isset($_FILES["imagem-form"]) || 
        $_FILES["imagem-form"]["error"] !== UPLOAD_ERR_OK) { 
        die("Ocorreu um erro no envio da imagem."); 
    } 
    
    $arquivo = $_FILES["imagem-form"]; 
    $tamanhoMaximo = 2 * 1024 * 1024; // 2 MB 
    
    if ($arquivo["size"] > $tamanhoMaximo) { 
        die("A imagem deve ter no máximo 2 MB."); 
    }

    $informacoesImagem = getimagesize($arquivo["tmp_name"]); 
    
    if ($informacoesImagem === false) { 
        die("O arquivo enviado não é uma imagem válida."); 
    } 
    
    // Permite somente JPEG, PNG e GIF 
    $tiposPermitidos = array( 
        IMAGETYPE_JPEG => "jpg", 
        IMAGETYPE_PNG  => "png", 
        IMAGETYPE_GIF  => "gif" 
    ); 
    
    $tipoImagem = $informacoesImagem[2]; 
    
    if (!array_key_exists($tipoImagem, $tiposPermitidos)) { 
        die("Formato não permitido. Envie JPG, PNG ou GIF."); 
    } 
    
    $extensao = $tiposPermitidos[$tipoImagem]; 
    $novoNome = uniqid("produto_", true) . "." . $extensao; 
    
    $pastaUploads = "uploads/"; 
    $caminhoDestino = $pastaUploads . $novoNome; 
    
    if (!move_uploaded_file($arquivo["tmp_name"], $caminhoDestino)) { 
        die("Não foi possível salvar a imagem no servidor."); 
    } 
    
    $sql = "INSERT INTO produto (nome, preco, imagem) 
            VALUES (?, ?, ?)"; 
    
    $stmt = mysqli_prepare($conexon, $sql);
    
    if (!$stmt) { 
        unlink($caminhoDestino); 
        die("Erro ao preparar o cadastro."); 
    } 
    
    mysqli_stmt_bind_param($stmt, "sds", $nome, $preco, $novoNome); 
    
    if (mysqli_stmt_execute($stmt)) { 
        echo "Produto cadastrado com sucesso!<br>"; 
        echo '<a href="formulario.php">Cadastrar outro</a><br>'; 
        echo '<a href="./php/listar.php">Ver produtos</a>'; 
    } else { 
        unlink($caminhoDestino); 
        echo "Erro ao cadastrar o produto."; 
    } 
    
    mysqli_stmt_close($stmt); 
    mysqli_close($conexon);
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro com imagem</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>Batat</h1>
        </header>
        <main>
            <form method="post" action="index.php" enctype="multipart/form-data">
                <input type="file" accept="image/*" name="imagem-form" id="" class="form__in-file">
                <input type="text" name="name" id="" class="form__in-text" placeholder="Nome: ">
                <input type="number" name="preco" id="form_in-preco" step="0.01" min="0" class="form__in-text" placeholder="Preco: ">
                <button type="submit">Enviar</button>
            </form>
        </main>
    </div>
</body>

<script>
    let preco_in = document.querySelector("#form_in-preco");

    preco_in.addEventListener("input", () => {
        if(preco_in.value < 0) {
            preco_in.value = 0;
        }
    });
</script>
</html>