<?php 


function verifyInfos($email, $password, $username = "Nome") {

    if (empty($email) || empty($password) || empty($username)) {
        return "<p class='error' >Campo não pode estar vazio</p>";
    }

    if (strlen($username) < 3) {
        return "<p class='error' >Nome muito curto</p>";
    }

    if (strlen($username) > 150) {
        return "<p class='error' >Nome muito longo</p>";
    }
    if (strlen($email) > 100) {
        return "<p class='error' >Email muito longo</p>";
    }
    if (strlen($password) > 100) {
        return "<p class='error' >Senha muito longa</p>";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "<p class='error' >Email inválido</p>";
    }

    if (strlen($password) < 6) {
        return "<p class='error' >Senha muito curta</p>";
    }

    return true;
}

function cadastrarUsuario($email, $password, $username) {
    // Função para cadastrar o usuário no banco de dados
    // Esta é uma função de exemplo e deve ser implementada conforme a lógica do seu banco de dados
    include 'config/connect.php';
    // verifica se o email já está cadastrado
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    // se não estiver, insere o novo usuário
    if ($result->num_rows === 0) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (email, password, name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $password, $username);
        $stmt->execute();
        $stmt->close();
        return true;
    }
    return false;
}

function getData($user_id, $last_day, $amount_days) {
    $head = ["Indicadores"];
    $dates = [];
    $oneDay = 24 * 3600;
    for ($i = $amount_days-1; $i >= 0 ; $i--) {
        $day = intval(strtotime($last_day)) - $oneDay * $i;
        $dates[] = date("Y-m-d", $day);
        $head[] = showDate(date("Y-m-d", $day));
    }

    $sql_dates = join("', '", $dates);
    $sql_dates = "'" . $sql_dates . "'";

    include "config/connect.php";
    $stmt = $mysqli->prepare("SELECT data.id as id_data, data.date as date,data.value as value, indicadores.name as name,indicadores.unit as unit FROM data, indicadores WHERE data.id_indicador = indicadores.id AND indicadores.id_user = ? AND data.date IN ($sql_dates) ORDER BY indicadores.id ,data.date;");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $response = $stmt->get_result();
    $stmt->close();
    $body = [];
    while ($row = $response->fetch_assoc()) {
        // name, unit, value, date
        $id = $row['id_data'];
        $name = $row['name'];
        $unit = $row['unit'];
        $value = $row['value']; 
        $date = $row['date'];
        
        $body[$name]["id"] = $id;
        $body[$name]["name"] = $name;
        $body[$name]['unit'] = $unit;
        $body[$name]['data'][$date] = $value;

    }

    foreach($body as $val) {
        if (count($val['data']) != $amount_days) {
            foreach($dates as $d) {
                if (!isset($val['data'][$d])) {
                    $body[$val['name']]['data'][$d] = null;
                }
            }
        }

        ksort($body[$val['name']]['data']);
    }

    return [$head, $body];
}

function prepareRegistros($user_id) {
    include "config/connect.php";
    // puxando data do ultimo registro 
    $stmt = $mysqli->prepare("SELECT data.date FROM data, indicadores WHERE data.id_indicador = indicadores.id AND indicadores.id_user = ? ORDER BY date DESC LIMIT 1;");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Definindo datas
    $oneDay = 24 * 3600;
    if (!$row) $last_record = date("Y-m-d", time() - $oneDay);
    else $last_record = $row['date'];
    $last_record = strtotime($last_record);
    $today = strtotime(date("Y-m-d", time()));

    // Definindo indicadores
    $stmt = $mysqli->prepare("SELECT * FROM indicadores WHERE id_user = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $response = $stmt->get_result();
    $stmt->close();
    
    $insert = "INSERT INTO data (id_indicador, date, value) VALUES ";
    $values = [];
    while ($row = $response->fetch_assoc()){    
        for ($time = $last_record + $oneDay; $time <= $today; $time += $oneDay) {
            $values[] = "(" . $row['id'] .  ", '". date("Y-m-d", $time). "', NULL)";
        }
    }

    if (count($values) == 0) return 0;
    $insert = $insert . join(",", $values);
    $mysqli->query($insert);
    $mysqli->close();
}

function saveInfos($data_id, $val, $user_id) {
    // Verifica se o date_id pertence ao user_id
    include "config/connect.php";
    $stmt = $mysqli->prepare("SELECT * FROM indicadores, data 
                            WHERE data.id_indicador = indicadores.id 
                            AND indicadores.id_user = ?
                            AND data.id = ?");
    $stmt->bind_param("ii", $user_id, $data_id);
    $stmt->execute();
    $response = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if(!$response) return 0;

    $stmt = $mysqli->prepare("UPDATE data SET value = ? WHERE id = ?");
    $stmt->bind_param("ii", $val, $data_id);
    $stmt->execute();
    $stmt->close();
}

function showDate($date) {
    $date = explode("-", $date);
    return $date[2] . "/" . $date[1];
}

function contabilNum ($valorEmCentavos) {
    $valorEmCentavos = intval($valorEmCentavos);
    $valorEmReais = $valorEmCentavos / 100;
    return number_format($valorEmReais, 2, ',', '.');
}

function reportGenerator2($user_id, $start, $end) {
    require "config/connect.php"; // conexão mysqli

    ob_clean();
    // ⚙️ Normaliza datas (caso venham no formato dd/mm/yyyy)
    $startObj = DateTime::createFromFormat('d/m/Y', $start);
    $endObj   = DateTime::createFromFormat('d/m/Y', $end);
    if ($startObj && $endObj) {
        $start = $startObj->format('Y-m-d');
        $end   = $endObj->format('Y-m-d');
    }

    // 🧾 Configura headers para download CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="relatorio.csv"');

    $output = fopen('php://output', 'w');

    // 🧩 Cabeçalho (nome dos indicadores)
    $stmt = $mysqli->prepare("SELECT id, name FROM indicadores WHERE id_user = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $indicadores = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $head = ['Data'];
    foreach ($indicadores as $ind) {
        $head[] = ucfirst(strtolower($ind['name']));
    }
    fputcsv($output, $head, ';');

    // 📊 Busca dados combinados
    $sql = "SELECT data.date AS data_date, data.value AS data_value, data.id_indicador 
            FROM data 
            INNER JOIN indicadores ON indicadores.id = data.id_indicador
            WHERE indicadores.id_user = ? 
            AND data.date BETWEEN ? AND ?
            ORDER BY data.date, data.id_indicador;";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iss", $user_id, $start, $end);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // 🧮 Monta matriz [data][id_indicador] = valor
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[$row['data_date']][$row['id_indicador']] = $row['data_value'] / 100; // converte centavos
    }

    // 🖨️ Gera cada linha do CSV
    foreach ($rows as $data => $indicadoresValores) {
        $linha = [$data];
        foreach ($indicadores as $ind) {
            $linha[] = $indicadoresValores[$ind['id']] ?? ''; // vazio se não houver valor
        }
        fputcsv($output, $linha, ';');
    }

    fclose($output);
    exit; // encerra pra não misturar HTML
}

function normalizarData($date) {
    if (count(explode('/', $date)) > 1) {
        $date = explode('/', $date);
        $date = array_reverse($date);
        $date = join('-', $date);
    }

    return $date;
}