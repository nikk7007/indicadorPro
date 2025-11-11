<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>indicador pro</title>

    <?php // Incluindo as fontes do Google ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@100..900&display=swap" rel="stylesheet">

    <?php // Incluindo css ?>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/visualizar.css">
    <link rel="stylesheet" href="css/editar.css">
    <link rel="stylesheet" href="css/relatorio.css">
    <link rel="stylesheet" href="css/delete.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Indicador Pro</h1>
        <?php 
        if (!isset($_SESSION)) {
            session_start();
        } 

        if (isset($_SESSION['id'])){ ?>
            <p>Bem-vindo, <?php echo $_SESSION['name']; ?>!</p>
        <?php } ?>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Início</a></li>
            <li><a href="visualizar.php">Visualizar</a></li>
            <li><a href="editar.php">Editar</a></li>
            <li><a href="relatorio.php">Relatórios</a></li>
            <?php 
            if (isset($_SESSION['id'])){ ?>
                <li><a href="logout.php">Sair</a></li>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>