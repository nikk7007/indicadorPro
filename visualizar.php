<?php 
include 'header.php';
if (!isset($_SESSION))
    session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
}

if (isset($_GET['date']) && !is_numeric($_GET['date'])) $_GET['date'] = 0;

include 'config/funcs.php';
prepareRegistros($_SESSION['id']);

if (count($_POST) > 0) {
    var_dump($_POST);
    foreach($_POST as $id => $val) {
        $val = trim($val);
        $val = str_replace(['R$', ' ', '.'], '', $val);
        $val = str_replace(',', '.', $val);
        $centavos = (float)$val * 100;
        $centavos = intval(round($centavos));
        saveInfos($id, $centavos, $_SESSION['id']);
    }
}

$oneDay = 24 * 3600;
$edit_date = date("Y-m-d",time());
if (isset($_GET['date']) && intval($_GET['date']) > 0) {
    $edit_date = date("Y-m-d", time() - $_GET['date'] * $oneDay);
}
$data = getData($_SESSION['id'], $edit_date, 5);

?>

<main>

    <form id="viewer" method="post">
        <section class="card">
            <?php 
            if(!isset($_GET['date']) || intval($_GET['date']) < 0) {
                $link = 1;
                $link2 = 0;
            } else {
                $link = $_GET['date'] + 1;
                $link2 = max(0, $_GET['date'] - 1);
            } ?>
            <a href="visualizar.php?date=<?= $link ?>">
                <img src="assets/arrow.svg" alt="">
            </a>
            <table>
                <tr>
                    <?php 
                    foreach($data[0] as $val){
                        echo "<th>$val</th>";
                    }
                    unset($data[0]);
                    ?>
                </tr>
                <?php 
                foreach($data[1] as $key => $val) {
                ?>
                <tr>
                    <?php 
                    $id = $val["id"];
                    $name = $val["name"];
                    $unit = $val["unit"];

                    echo "<td>$name</td>";
                    foreach ($val["data"] as $day => $v) {
                        if ($day == $edit_date) {
                            $value = $unit == 'real' ? contabilNum($v) : $v;
                            echo "<td><input 
                                type='text' 
                                inputmode='numeric' 
                                name='$id'
                                value='$value'
                                data-unit='$unit'></td>";
                                continue;
                        }

                        echo "<td>";
                        if ($unit == "real") echo "R$" . contabilNum($v);
                        if ($unit == "porcentagem") echo $v."%";
                        echo "</td>";
                    }
                    ?>
                </tr>
                <?php 
                }
                ?>
            </table>
            <a href="visualizar.php?date=<?= $link2 ?>">
                <img src="assets/arrow.svg" alt="">
            </a>
        </section>
        <section class="buttons">
            <button type="button"><span>Editar</span></button>
            <button type="submit"><span>Salvar</span></button>
        </section>
    </form>

</main>
<script>

const inputs = document.querySelectorAll("#viewer table input[data-unit='real']");

inputs.forEach(input => {
    input.addEventListener('keydown', e => {
        formatContabil(input, e.key, e);
    })

    input.addEventListener('paste', e => {
        console.log(e)
        formatContabil(input);
    })

})

function formatContabil(input, key="", event=null) {
    if (event != null) {
        event.preventDefault()
    }

    
    let number = String(input.value)
    number = number.replace(/[.,a-zA-Z]/g, "");
    number = number.replace(/^0+(?=\d)/, "")

    if ("0123456789".includes(key)){
        number = String(number.concat(key));
    } else if (key == "Backspace") {
        number = number.split("")
        number.pop()
        number = number.join("")
    }

    let formatedNum = "";
    
    if (number.length == 0) {
        number = "0";
    }
    if (number.length < 3) {
        for (i = 0; i < 4-number.length; i++){
            number = "0".concat(number);
        }
    }
    for (i = number.length - 1; i >= 0; i--) {
        formatedNum += number[i];
        if (i == number.length - 2) {
            formatedNum += ",";
        } else if ((i - number.length + 2) % 3 == 0 && 
                    i < number.length - 3 && 
                    i != 0) {
            formatedNum += ".";
        }
    }

    formatedNum = formatedNum.split('').reverse().join('');
    input.value = formatedNum;
    return formatedNum;
}

</script>
</body>
</html>