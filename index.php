<?php include "config.php";?>
<head>

<title>Kurkiewicz</title>

<body>
<?php
$dbhost="localhost"; $dbuser="xxx"; $dbpassword="xxx"; $dbname="xxx";
$polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
mysqli_select_db ($polaczenie, $dbname);


$user_data = get_user_data();
$date = $_GET['date']; 

 if($_SESSION['logged']) {
       echo' [<a href="/z7/logout.php">Wyloguj</a>] &nbsp';
    }
 else{
	  echo'Logowanie:<br>
	  [<a href="/z7/login.php">Logowanie</a>] <br>
	   [<a href="/z7/register.php">Rejestracja</a>]<br><br>';
 }
 
if($_SESSION['logged']) {
	

echo'Ostatnie błędne zalogowanie: '; echo $date; 
echo'<br><br> 
<h3><b>Wysyłanie plików</b></h3>
[<a href="/z7/wyslij.php?idu='.$_SESSION['id_uzytkownika'].'">Wyślij plik</a>]<br><br>';


?>

<?php

$iduz = $_SESSION['id_uzytkownika']; 

$result = mysqli_query($polaczenie, "SELECT login FROM users WHERE id_uzytkownika='$iduz' LIMIT 1");
$row = mysqli_fetch_assoc($result);
$nazwa = $row['login'];

  if ($handle = opendir("foldery/$nazwa/")) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != ".." && is_file("foldery/$nazwa/$file")) {
        $thelist .= '<li><a href="foldery/'.$nazwa.'/'.$file.'" download>'.$file.'</a></li>';
      }
	  else if ($file != "." && $file != ".."){
        $thelist1 .= '<li><a href="przegladaj.php?ik='.$file.'">'.$file.'</a></li>';
      }
    }
    closedir($handle);
  }
?>
<?php
echo'
<h4>Lista  moich plików:</h4>
<ul>'; echo $thelist; echo'</ul>

<h4>Lista  moich katalogów:</h4>
<ul>'; echo $thelist1; echo'</ul>

<h4>Stwórz nowy katalog</h4>
 <form action="nowy_folder.php?idu='.$_SESSION['id_uzytkownika'].'" method="POST" 
 ENCTYPE="multipart/form-data">
 Nazwa katalogu: <input type="input" name="katalog"/>
 <input type="submit" value="Stwórz katalog"/>
</form>';}
?>
</body>

</head>


