<?php
ob_start();
?>
<?php
// dane do połączenia z bazą danych
define('DBHOST', 'localhost');
define('DBUSER', 'xxx');
define('DBPASS', 'xxx');
define('DBNAME', 'xxx'); 

$polaczenie = mysqli_connect(DBHOST, DBUSER, DBPASS) or die('<h2>ERROR</h2> Baza danych MySQL nie odpowiada');

function db_connect() {
    // wybór bazy danych
	global $polaczenie;
    mysqli_select_db($polaczenie, DBNAME) or die('<h2>ERROR</h2> Nie można połączyć z bazą danych');
	mysqli_query($polaczenie, 'SET NAMES utf8;');
}
 
function db_close() {
	global $polaczenie;
    mysqli_close($polaczenie);
}

function clear($text) {
    // jeśli serwer automatycznie dodaje slashe to je usuwamy
	global $polaczenie;
    if(get_magic_quotes_gpc()) {
        $text = stripslashes($text);
    }
    $text = trim($text); // usuwamy białe znaki na początku i na końcu
    $text = mysqli_real_escape_string($polaczenie, $text); // filtrujemy tekst aby zabezpieczyć się przed sql injection
    $text = htmlspecialchars($text); // dezaktywujemy kod html
    return $text;
}


function check_login() {
    if(!$_SESSION['logged']) {
        die('<br>To jest strefa tylko dla użytkowników.<br>
        [<a href="/z7/login.php">Logowanie</a>] [<a href="/z7/register.php">Zarejestruj się</a>]');
    }
}

// funkcja na pobranie danych usera
function get_user_data($user_id = -1) {
	global $polaczenie;
    // jeśli nie podamy id usera to podstawiamy id aktualnie zalogowanego
    if($user_id == -1) {
        $user_id = $_SESSION['id_uzytkownik'];
    }
    $result = mysqli_query($polaczenie, "SELECT * FROM `users` WHERE `id_uzytkownika` = '{$user_id}' LIMIT 1");
    if(mysqli_num_rows($result) == 0) {
        return false;
    }
    return mysqli_fetch_assoc($result);
}



// startujemy sesje
session_start();
 
// jeśli nie ma jeszcze sesji "logged" i "user_id" to wypełniamy je domyślnymi danymi
if(!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
    $_SESSION['id_uzytkownika'] = -1;
}




?>
<?php
ob_end_flush();
?>