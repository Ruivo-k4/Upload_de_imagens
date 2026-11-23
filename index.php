<?php 
session_start();

$conexon = mysqli_connect('localhost', 'root', '', 'produtos_cImg_bd');

if (!$conexon) {
    die
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo'])) {
    $img = $_FILES['imagem-form'];
    $
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
                <input type="file" name="imagem-form" id="" class="form__in-file">
                <input type="text" name="name" id="" class="form__in-text" placeholder="Nome: ">
                <input type="text" name="preco" id="" class="form__in-text" placeholder="Preco: ">
                <button type="submit">Enviar</button>
            </form>
        </main>
    </div>
</body>
</html>