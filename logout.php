<?php
session_start();
$_SESSION['logged'] = false;
$_SESSION['id_uzytkownik'] = -1;
header('Location: /z7/index.php');
?>