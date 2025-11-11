<?php 
include 'header.php';
if (!isset($_SESSION))
    session_start();

if (!isset($_SESSION['id'])) {
    // mensagem de nao logado
    header("Location: index.php");
}

if (isset($_POST['save'])) {
    unset($_POST['save']);
    include 'config/connect.php';
    foreach($_POST as $key => $val) {
        if (str_starts_with($key, "un")) continue;

        $id = intval($key);
        $name = trim($val);
        $unitKey = 'un' . $id;
        $unit = $_POST[$unitKey] ?? 'real';

        if (!empty($name) && !empty($unit)) {
            $stmt = $mysqli->prepare("UPDATE indicadores SET name = ?, unit = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $unit, $id);

            if (!$stmt->execute()) {
                echo "Erro ao atualizar id=$id: " . $stmt->error . "<br>";
            }

            $stmt->close();
        }
    }
}

if (isset($_POST['add'])) {
    unset($_POST['add']);

    $idUser = $_SESSION['id'];
    $name = $_POST['novoIndicador'];
    $unit = $_POST['novoIndicadorUn'];

    include 'config/connect.php';
    $stmt = $mysqli->prepare("INSERT INTO indicadores (name,unit,id_user) VALUES (?,?,? )");
    $stmt->bind_param("ssi", $name, $unit, $idUser);
    $stmt->execute();
    $id_indicador_add = $mysqli->insert_id;
    $stmt->close();
    
    $today = date("Y-m-d", time());
    $stmt = $mysqli->prepare("INSERT INTO data (id_indicador, date) VALUES (?,?)");
    $stmt->bind_param("is", $id_indicador_add, $today);
    $stmt->execute();
    $stmt->close();
}

?>

<main id="edit">

    <form id="edit-indicador" class="card" method="post">
        <section class="head">
            <h2>Editar</h2>
        </section>
        <section class="body">
            <table>
                <tr>
                    <th></th>
                    <th>Nome</th>
                    <th>Unidade</th>
                </tr>
                <?php 
                include 'config/connect.php';
                $stmt = $mysqli->prepare("SELECT * FROM indicadores WHERE id_user = ?");
                $stmt->bind_param("d", $_SESSION['id']);
                $stmt->execute();
                $response = $stmt->get_result();
                $stmt->close();
                while ($row = $response->fetch_assoc()) {
                ?>  
                <tr>
                    <td><a href="delete.php?id=<?= $row['id'] ?>"><img src="assets/red_close.svg" alt=""></a></td>
                    <td><input 
                    name="<?= $row['id'] ?>" 
                    value="<?= $row['name'] ?>"
                    type="text"></td>
                    <td>
                        <select name="un<?= $row['id'] ?>">
                            <option 
                            value="real"
                            <?php 
                            if ($row['unit'] == 'real') echo "selected"
                            ?>
                            >R$</option>
                            <option 
                            value="porcentagem"
                            <?php 
                            if ($row['unit'] == 'porcentagem') echo "selected"
                            ?>
                            >%</option>
                        </select>
                    </td>
                </tr>
                    
                <?php 
                }
                ?>
            </table>
            <button name="save"><span>Salvar</span></button>
        </section>
    </form>

    <form id="add-indicador" class="card" method="post">
        <section class="head">
            <h2>Adicionar</h2>
        </section>
        <section class="body">
            <table>
                <tr>
                    <th>Nome</th>
                    <th>Unidade</th>
                </tr>
                <tr>
                    <td><input name="novoIndicador" type="text"></td>
                    <td>
                        <select name="novoIndicadorUn">
                            <option value="real">R$</option>
                            <option value="porcentagem">%</option>
                        </select>
                    </td>
                </tr>
            </table>
            <button name="add"><span>Adicionar</span></button>
        </section>
    </form>

</main>