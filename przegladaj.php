<?php include "config.php";?>
<head>

<title>Kurkiewicz</title>

<body>
<?php
$dbhost="localhost"; $dbuser="xxx"; $dbpassword="xxx"; $dbname="xxx";
$polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
mysqli_select_db ($polaczenie, $dbname);


$podkatalog = $_GET['ik'];
 
if($_SESSION['logged']) {
	

echo'
<h3><b>Wysyłanie plików</b></h3>
[<a href="/z7/wyslij1.php?idu='.$_SESSION['id_uzytkownika'].'&ik='.$podkatalog.'">Wyślij plik</a>]<br><br>';


?>

<?php

$iduz = $_SESSION['id_uzytkownika']; 

$result = mysqli_query($polaczenie, "SELECT login FROM users WHERE id_uzytkownika='$iduz' LIMIT 1");
$row = mysqli_fetch_assoc($result);
$nazwa = $row['login'];

  if ($handle = opendir("foldery/$nazwa/$podkatalog")) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != ".." && is_file("foldery/$nazwa/$podkatalog/$file")) {
        $thelist .= '<li><a href="foldery/'.$nazwa.'/'.$podkatalog.'/'.$file.'" download>'.$file.'</a></li>';
      }
    }
    closedir($handle);
  }
?>
<?php
echo'
<h4>Lista plików w katalogu <i>"'.$podkatalog.'"</i>:</h4>
<ul>'; echo $thelist; echo'</ul>';}
?>
</body>

</head>


