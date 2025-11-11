<?php 
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "indicador_pro";

$mysqli = new mysqli($host, $user, $pass, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}