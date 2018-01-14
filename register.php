<?php include "config.php";?>
<?php

$dbhost="localhost"; $dbuser="xxx"; $dbpassword="xxx"; $dbname="xxx";
$polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
mysqli_select_db ($polaczenie, $dbname);

if(!$_SESSION['logged']) {
    // jeśli zostanie naciśnięty przycisk "Zarejestruj"
    if(isset($_POST['login'])) {
		
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		$haslo2 = $_POST['haslo2'];
		
        // sprawdzamy czy wszystkie pola zostały wypełnione
        if(empty($login) || empty($haslo) || empty($haslo2)) {
            echo '
			<center>Musisz wypełnić wszystkie pola.</center>';
         //sprawdzamy czy podane dwa hasła są takie same
        } elseif($haslo != $haslo2) {
            echo '
			<center>Podane hasła różnią się od siebie.</center>';
        // sprawdzamy poprawność adresu email
        } else {
			
            // sprawdzamy czy są jacyś uzytkownicy z takim numerem pesel lub adresem email
            $result = mysqli_query($polaczenie, "SELECT Count(id_uzytkownika) FROM `users` WHERE `login` = '$login'");
            $row = mysqli_fetch_row($result);
            if($row[0] > 0) {
                echo '
				<center>Już istnieje użytkownik o takim nicku.</center>';
            } else {
                // jeśli nie istnieje to kodujemy haslo...
                
                // i wykonujemy zapytanie na dodanie usera
				if($login) { 
				$haslo = md5($haslo);
				
				$query = "INSERT INTO `users` (`login`, `haslo`) VALUES('$login', '$haslo')";
                
				$ins = mysqli_query($polaczenie, $query);
				$nazwa = $login;
				if($ins){
				$dir = "foldery/$nazwa";
				if (!is_dir($dir)) {
				mkdir($dir, 0777, true);
				}
				echo '
				<center>Konto zostało założone poprawnie. <br>
				[<a href="/z7/login.php">Zaloguj się</a>]</center>';}}
                
            }
        }
    }
 
    // wyświetlamy formularz
    echo '
		Utwórz konto<br>
		Masz już konto?       <a href="/z7/login.php"> Zaloguj się</a><br><br><br>
		<form method="post" action="register.php" id="form1">
		
			Login:
            <input id="name" type="text" value="" name="login" placeholder="Login" required><br><br>

			Hasło:
            <input id="password" type="password" value="" name="haslo" placeholder="Hasło" required><br><br>

			Powtórz hasło:
            <input id="password" type="password" value="" name="haslo2" placeholder="Powtórz hasło" required><br><br>
			
			<button type="submit" form="form1">Utwórz konto</button><br>
			
    </form>';
		
}else {
   echo '
    Jesteś już zalogowany, więc nie możesz stworzyć nowego konta.<br><br>
	[<a href="index.php">Powrót</a>]';}

 
?>