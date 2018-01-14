<?php
$dbhost="localhost"; $dbuser="xxx"; $dbpassword="xxx"; $dbname="xxx";
$polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
mysqli_select_db ($polaczenie, $dbname);

$idu = $_GET['idu']; 

$result = mysqli_query($polaczenie, "SELECT login FROM users WHERE id_uzytkownika='$idu' LIMIT 1");
$row = mysqli_fetch_assoc($result);
$nazwa = $row['login'];
 $nazwa1 = $_POST['katalog'];
 $dir = "foldery/$nazwa/$nazwa1";
 if (!is_dir($dir)) {
 mkdir($dir, 0777, true);
 header('Location: /z7/index.php');
 }
 ?>