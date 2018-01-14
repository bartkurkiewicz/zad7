<?php
$dbhost="localhost"; $dbuser="xxx"; $dbpassword="xxx"; $dbname="xxx";
$polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
mysqli_select_db ($polaczenie, $dbname);

$idu = $_GET['idu']; 
$podkatalog = $_GET['ik'];

$result = mysqli_query($polaczenie, "SELECT login FROM users WHERE id_uzytkownika='$idu' LIMIT 1");
$row = mysqli_fetch_assoc($result);
$nazwa = $row['login'];
 if (is_uploaded_file($_FILES['plik']['tmp_name']))
 {
 echo 'Odebrano plik: '.$_FILES['plik']['name'].'<br/>';echo $nazwa;
 if(move_uploaded_file($_FILES['plik']['tmp_name'],
 $_SERVER['DOCUMENT_ROOT']."/z7/foldery/$nazwa/$podkatalog/".$_FILES['plik']['name'])){
 chmod($_SERVER['DOCUMENT_ROOT']."/z7/foldery/$nazwa/$podkatalog/".$_FILES['plik']['name'], 0644);};
 
echo "<script> window.location.assign('/z7/index.php'); </script>";
 }
 else {echo 'Błąd przy przesyłaniu danych!';}
?>

