<?php
session_start();
$host = 'localhost';
$db = 'blog';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die('Erro: ' . $conn->connect_error);
$conn->set_charset('utf8');
?>
