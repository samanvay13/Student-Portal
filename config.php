<?php

$servername = "root";
$password = "root";
$db_name = "test";

$db = new PDO('mysql:host=localhost; dbname='.$db_name.';charset=utf8', $servername, $password);
$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)

?>