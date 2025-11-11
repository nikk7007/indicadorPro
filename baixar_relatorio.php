<?php 
include "header.php";

if (isset($_POST['start'])) {
    include "config/funcs.php";
    $start = $_POST['start'];
    $end = $_POST['end'];
    reportGenerator2($_SESSION['id'], $start, $end);
}