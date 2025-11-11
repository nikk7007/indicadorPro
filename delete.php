<?php 
include 'header.php';

if (!isset($_GET['id']) || 
    !is_numeric($_GET['id'])) 
    header("Location: editar.php");

include 'config/connect.php';
$stmt = $mysqli->prepare("SELECT * FROM indicadores WHERE id_user = ? AND id = ?");
$stmt->bind_param("ii", $_SESSION['id'], $_GET['id']);
$stmt->execute();
$response = $stmt->get_result();
$stmt->close();
if (!$ind = $response->fetch_assoc()) {
    header("Location: editar.php");
}


if (isset($_POST['delete'])) {
    $stmt = $mysqli->prepare("DELETE FROM indicadores WHERE id = ?");
    $stmt->bind_param("d", $_GET['id']);
    $stmt->execute();
    $stmt->close();
    header("Location: editar.php");
}

if (isset($_POST['cancel'])) {
    header("Location: editar.php");
}
?>

<main>

<form class="card" id="delete" method="post">
    <section class="head">
        <h2>Deletar</h2>
    </section>
    <section class="body"> 
        <p>Você tem certeza?</p>
        <p>Deseja deletar o indicador <?= $ind['name']?>?</p>
        <p>Todos os seus registros serão apagados juntos</p>
        <div class="act">
            <button name="delete"><span>Deletar</span></button>
            <button name="cancel"><span>Cancelar</span></button>
        </div>
    </section>

</form>

</main>