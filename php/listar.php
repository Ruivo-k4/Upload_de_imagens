<?php 
$conexon = mysqli_connect('localhost', 'root', '', 'produtos_cImg_bd');
 
$sql = "SELECT id, nome, preco, imagem FROM produto 
        ORDER BY id DESC"; 
 
$resultado = mysqli_query($conexon, $sql); 
?> 
 
<!DOCTYPE html> 
<html lang="pt-br"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Produtos cadastrados</title> 
</head> 
<body> 
    <h1>Produtos cadastrados</h1> 
 
    <a href="../index.php">Cadastrar novo produto</a>
 
    <?php 
        if (mysqli_num_rows($resultado) > 0) { 
    
            while ($produto = mysqli_fetch_assoc($resultado)) { 
                    echo "<div style='border:1px solid #ccc; 
                                padding:10px; margin:10px; 
                                width:250px;'>"; 
        
                    echo "<h2>" . 
                        htmlspecialchars($produto["nome"]) . 
                        "</h2>"; 
        
                    echo "<p>Preço: R$ " . 
                        number_format($produto["preco"], 2, ",", ".") . 
                        "</p>"; 
        
                    $caminhoImagem = "../uploads/" . $produto["imagem"];
                    echo "<img src='" . 
                    htmlspecialchars($caminhoImagem) . 
                    "' width='180' alt='Imagem do produto'>"; 
                    echo "</div>"; 
                } 
            } else { 
                echo "<p>Nenhum produto cadastrado.</p>"; 
            } 
            mysqli_close($conexon); 
    ?> 
</body> 
</html>