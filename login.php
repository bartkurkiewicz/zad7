<?php include "config.php";?>
<?php

$dbhost="localhost"; $dbuser="xxx"; $dbpassword="xxx"; $dbname="xxx";
$polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
mysqli_select_db ($polaczenie, $dbname);

$ipaddress = $_SERVER['REMOTE_ADDR'];
$data = date('Y-m-d H:i:s');



class OS_BR{

    private $agent = "";
    private $info = array();

    function __construct(){
        $this->agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL;
        $this->getBrowser();
        $this->getOS();
    }

    function getBrowser(){
        $browser = array("Navigator"            => "/Navigator(.*)/i",
                         "Firefox"              => "/Firefox(.*)/i",
                         "Internet Explorer"    => "/MSIE(.*)/i",
                         "Google Chrome"        => "/chrome(.*)/i",
                         "MAXTHON"              => "/MAXTHON(.*)/i",
                         "Opera"                => "/Opera(.*)/i",
                         );
        foreach($browser as $key => $value){
            if(preg_match($value, $this->agent)){
                $this->info = array_merge($this->info,array("Browser" => $key));
                $this->info = array_merge($this->info,array(
                  "Version" => $this->getVersion($key, $value, $this->agent)));
                break;
            }else{
                $this->info = array_merge($this->info,array("Browser" => "UnKnown"));
                $this->info = array_merge($this->info,array("Version" => "UnKnown"));
            }
        }
        return $this->info['Browser'];
    }

    function getOS(){
        $OS = array("Windows"   =>   "/Windows/i",
                    "Linux"     =>   "/Linux/i",
                    "Unix"      =>   "/Unix/i",
                    "Mac"       =>   "/Mac/i"
                    );

        foreach($OS as $key => $value){
            if(preg_match($value, $this->agent)){
                $this->info = array_merge($this->info,array("Operating System" => $key));
                break;
            }
        }
        return $this->info['Operating System'];
    }

    function getVersion($browser, $search, $string){
        $browser = $this->info['Browser'];
        $version = "";
        $browser = strtolower($browser);
        preg_match_all($search,$string,$match);
        switch($browser){
            case "firefox": $version = str_replace("/","",$match[1][0]);
            break;

            case "internet explorer": $version = substr($match[1][0],0,4);
            break;

            case "opera": $version = str_replace("/","",substr($match[1][0],0,5));
            break;

            case "navigator": $version = substr($match[1][0],1,7);
            break;

            case "maxthon": $version = str_replace(")","",$match[1][0]);
            break;

            case "google chrome": $version = substr($match[1][0],1,10);
        }
        return $version;
    }

    function showInfo($switch){
        $switch = strtolower($switch);
        switch($switch){
            case "browser": return $this->info['Browser'];
            break;

            case "os": return $this->info['Operating System'];
            break;

            case "version": return $this->info['Version'];
            break;

            case "all" : return array($this->info["Version"], 
              $this->info['Operating System'], $this->info['Browser']);
            break;

            default: return "Unkonw";
            break;

        }
    }
}

// using
// create an new instant of OS_BR class
$obj = new OS_BR();
$os = $obj->showInfo('os');
$browser = $obj->showInfo('browser');






// sprawdzamy czy user nie jest przypadkiem zalogowany
if(!$_SESSION['logged']) {
    // jeśli zostanie naciśnięty przycisk "Zaloguj"
    // jeśli zostanie naciśnięty przycisk "Zaloguj"
    if(isset($_POST['login'])) {
        // filtrujemy dane...
        $_POST['login'] = clear($_POST['login']);
        $_POST['haslo'] = clear($_POST['haslo']);
        // i kodujemy hasło
        $_POST['haslo'] = md5($_POST['haslo']);
 
        // sprawdzamy prostym zapytaniem sql czy podane dane są prawidłowe
        $result = mysqli_query($polaczenie, "SELECT us.id_uzytkownika, us.haslo, lo.licznik, lo.bledne FROM users us LEFT JOIN logi lo ON us.id_uzytkownika=lo.id_uzytkownika WHERE login = '{$_POST['login']}' LIMIT 1");
        if(mysqli_num_rows($result) >= 0) {
            // jeśli tak to ustawiamy sesje "logged" na true oraz do sesji "user_id" wstawiamy id usera
            $row = mysqli_fetch_assoc($result);
			$ostatnie = $row['bledne'];
			if($row['haslo']==$_POST['haslo']){
			if($row['licznik']==0 || $row['licznik']==NULL){
			$_SESSION['logged'] = true;
            $_SESSION['id_uzytkownika'] = $row['id_uzytkownika'];
			$uz = $row['id_uzytkownika'];
			;
			mysqli_query($polaczenie, "INSERT INTO `logi` (`id_uzytkownika`, `data`, `licznik`, `ip`, `system`, `przegladarka`) VALUES ('$uz', '$data', 0, '$ipaddress', '$os', '$browser') 
			ON DUPLICATE KEY UPDATE `id_uzytkownika`='$uz', `data`='$data', `licznik`=0, `bledne`=`bledne`, `ip`='$ipaddress', `system`='$os', `przegladarka`='$browser'");
            echo "<script> window.location.assign('/z7/index.php'); </script>";
			}
			else if ($row['haslo']>0 && $row['licznik']<3){
			$_SESSION['logged'] = true;
            $_SESSION['id_uzytkownika'] = $row['id_uzytkownika'];
			$uz = $row['id_uzytkownika'];
			mysqli_query($polaczenie, "INSERT INTO `logi` (`id_uzytkownika`, `data`, `licznik`, `ip`, `system`, `przegladarka`) VALUES ('$uz', '$data', 0, '$ipaddress', '$os', '$browser') 
			ON DUPLICATE KEY UPDATE `id_uzytkownika`='$uz', `data`='$data', `licznik`=0, `bledne`=`bledne`, `ip`='$ipaddress', `system`='$os', `przegladarka`='$browser'");
			echo "<script> window.location.assign('/z7/index.php?date=".$ostatnie."'); </script>";
			}
			else if($row['licznik']>=3){
			echo '
			Przekroczono limit błędnych logowań. Konto zostało zablokowane.';
			}
			}
			else if($row['haslo']!=$_POST['haslo']){
			$uz = $row['id_uzytkownika'];
			mysqli_query($polaczenie, "INSERT INTO `logi` (`id_uzytkownika`, `data`, `bledne`, `licznik`, `ip`, `system`, `przegladarka`) VALUES ('$uz', '$data', '$data', 1, '$ipaddress', '$os', '$browser') 
			ON DUPLICATE KEY UPDATE `id_uzytkownika`='$uz', `data`='$data', `bledne`='$data', `licznik`=`licznik`+1, `bledne`='$data', `ip`='$ipaddress', `system`='$os', `przegladarka`='$browser'");
			echo '
			Podane hasło jest nieprawidłowe.';
			}
        } 
		
		else {
            echo '
			Podany login i/lub hasło jest nieprawidłowe.';
        }
    }
 
    // wyświetlamy komunikat na zalogowanie się
    echo '
		Zaloguj się<br>
		Nie masz jeszcze konta?   </naglowki>    <a href="/z7/register.php"> Utwórz konto</a><br><br><br>
		
		<form method="post" action="login.php" id="form2"><br>
		
		Login:
            <input id="name" type="text" value="'.$_POST['login'].'" name="login" placeholder="Login" required>
		
        Hasło:
            <input id="name" type="password" value="" name="haslo" placeholder="Hasło" required>

		<br>
		<br>
            <button class="button buttonrejestracja" type="submit" form="form2">Zaloguj</button>
		<br>
    </form>
		';
} else {
echo '
Jesteś już zalogowany, więc nie możesz się zalogować ponownie.<br><br>
[<a href="index.php">Powrót</a>]';
}
?>