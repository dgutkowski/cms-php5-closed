<?php
require_once "maincore.php";
require_once (BASEDIR.STYLE."tpl.start.php");

$sql = "SELECT * FROM `".DBPREFIX."users` where `id` = '".$_SESSION['userid']."' LIMIT 1";
$query = mysql_query($sql);
$prf = mysql_fetch_assoc($query);

if(isset($_POST['upt_name']))
{
	if($_POST['upt_name'] == "mail")
	{
	  	if(!empty($_POST['old']) AND $prf['password'] == md5($_POST['old']))
	  	{
			$mail = htmlspecialchars($_POST['mail']);
			$sql = "UPDATE `".DBPREFIX."users` SET `mail` = '".$mail."' WHERE `id` = ".$_SESSION['userid']." LIMIT 1";
			$reply = 2; $reply_info = "Adres e-Mail zosta³ zaaktualizowany poprawnie";
		}
		else { $reply = 1; $reply_info = "Wprowad¼ poprawne dane"; }
	}
	if($_POST['upt_name'] == "pass")
	{
		if(!empty($_POST['old']) AND !empty($_POST['new']) AND !empty($_POST['confirm']))
		{
	   		if($prf['password'] == md5($_POST['old']))
			{
		   		if($_POST['new'] == $_POST['confirm'])
		   		{
 	  				if(strlen($_POST['new']) > 3)
 	  				{
						$pass = md5($_POST['new']);
		     			$sql = "UPDATE `".DBPREFIX."users` SET `password` = '".$pass."' WHERE `id` = ".$_SESSION['userid']." LIMIT 1";
		     			$reply = 2; $reply_info = "Has³o zosta³o zaaktualizowane poprawnie";
					}
					else { $reply = 1; $reply_info = "Wprowadzone has³o jest za krótkie. Has³o musi zawieraæ conajmniej 4 znaki"; }
				}
				else { $reply = 1; $reply_info = "Podane nowe has³o nie zosta³o potwierdzone"; }
			}
			else { $reply = 1; $reply_info = "Podano z³e aktualne has³o"; }
		}
		else { $reply = 1; $reply_info = "Nie uzupe³niono danych"; }
	}
	if($_POST['upt_name'] == "display")
	{
		$v1 = addslashes(htmlspecialchars($_POST['i1']));
		$v2 = addslashes(htmlspecialchars($_POST['i2']));
		$sql = "UPDATE `".DBPREFIX."users` SET `display_mail` = '".$v1."', `display_online` = '".$v2."' WHERE `id` = ".$_SESSION['userid']." LIMIT 1";
		$reply = 2; $reply_info = "Ustawienia u¿ytkownika zosta³y zaaktualizowany poprawnie";
	}
	if($_POST['upt_name'] == "contact")
	{
		if(check_int($_POST['i1']) == TRUE OR check_int($_POST['i2']) == TRUE)
		{
	   	$gg = htmlspecialchars($_POST['i1']);
			$icq = htmlspecialchars($_POST['i2']);
	
			if(check_int($_POST['i1']) == FALSE) $gg = 0;
			if(check_int($_POST['i2']) == FALSE) $icq = 0;
	
			$sql = "UPDATE `".DBPREFIX."users` SET `gg` = '".$gg."', `icq` = '".$icq."' WHERE `id` = ".$_SESSION['userid']." LIMIT 1";
	
			$reply = 2; $reply_info = "Dane kontaktowe zosta³y zaaktualizowany poprawnie";
		}
		else { $reply = 1; $reply_info = "Wpisane numery zawieraj± nie dozwolone znaki"; }
	}
	if($_POST['upt_name'] == "player")
	{
		// $v1 = addslashes(htmlspecialchars($_POST['i1']));
		$v2 = addslashes(htmlspecialchars($_POST['i2']));
		$v3 = addslashes(htmlspecialchars($_POST['i3']));
		$v4 = addslashes(htmlspecialchars($_POST['i4']));
		// `gamenick` = '".$v1."',
		$sql = "UPDATE `".DBPREFIX."users` SET `clan` = '".$v2."', `favrules` = '".$v3."', `usertitle` = '".$v4."' WHERE `id` = ".$_SESSION['userid']." LIMIT 1";
		$reply = 2; $reply_info = "Dane gracza zosta³y zaaktualizowany poprawnie";
	}
	if($_POST['upt_name'] == "person")
	{
		$v1 = addslashes(htmlspecialchars($_POST['i1']));
		$v2 = $_POST['i2a']."-".$_POST['i2b']."-".$_POST['i2c'];
		$v3 = $_POST['i3'];
		$v4 = addslashes(htmlspecialchars($_POST['i4']));
		$v5 = addslashes(htmlspecialchars($_POST['i5']));
		$sql = "UPDATE `".DBPREFIX."users` SET `name` = '".$v1."', `born` = '".$v2."', `gender` = '".$v3."', `intrest` = '".$v4."', `location` = '".$v5."' WHERE `id` = ".$_SESSION['userid']." LIMIT 1";
		$reply = 2; $reply_info = "Dane kontaktowe zosta³y zaaktualizowany poprawnie";
	}
	if(!empty($_POST['upt_name'])) {mysql_query($sql);}
}

$sql = "SELECT * FROM `".DBPREFIX."users` where `id` = '".$_SESSION['userid']."' LIMIT 1";
$query = mysql_query($sql);
$prf = mysql_fetch_assoc($query);
$born = explode("-",$prf['born']);

echo "<h2>Profil</h2>\n";

echo "    <table class='table0 w100'>\n";
echo "    <tr><th class='w50'><a href='profile.php'>Podstawowe dane</a></th><th class='w50'><a href='profile.php?show=1'>Informacje o u¿ytkowniku</a></th></tr>\n";
echo "    </table>\n";

if(isset($reply))
{
	if($reply == 2) echo "      <div class='suc'>".$reply_info."</div>\n";
	if($reply == 1) echo "      <div class='err'>".$reply_info."</div>\n";
}
if(!isset($_GET['show'])) $_GET['show'] = 0;

if($_GET['show'] != 1 AND ($_GET['show'] != 0 OR $_GET['show'] != 1))
{
	echo "    <table class='table0 w100 form-style1'>\n";
	echo "      <tr><td colspan='2'><h6>Dane o twoim koncie</h6></td></tr>\n";
	echo "      <tr><td width='200'>Adres e-Mail przypisany</td><td>".$prf['mail']."</td></tr>\n";
	echo "      <tr><td>Data rejestracji</td><td>".$prf['register_date']."</td></tr>\n";
	echo "      <tr><td>IP rejestracji</td><td>".$prf['register_ip']."</td></tr>\n";
	echo "      <tr><td>IP ostatnio</td><td>".$prf['last_ip']."</td></tr>\n";	
	echo "    <form action='' method='post'>\n";
	echo "      <tr><td colspan='2'><h6>Ustaw nowy adres e-mail</h6></td></tr>\n";
	echo "      <tr><td width='200'>Twój adres e-mail</td><td><input class='w75' type='text' name='mail' value='".$prf['mail']."'/></td></tr>\n";
	echo "      <tr><td width='200'>Has³o</td><td><input class='w75' type='password' name='old' value=''/></td></tr>\n";
	echo "      <tr><td><input type='hidden' name='upt_name' value='mail'/></td><td><input type='submit' class='submit' value='Potwierd¼'/></td></tr>\n";
	echo "    </form>\n";	
	echo "    <form action='' method='post'>\n";
	echo "      <tr><td colspan='2'><h6>Zmieñ has³o</h6></td></tr>\n";
	echo "      <tr><td width='200'>Twoje stare has³o</td><td><input class='w75' type='password' name='old' value=''/></td></tr>\n";
	echo "      <tr><td>Nowe has³o</td><td><input class='w75' type='password' name='new' value=''/></td></tr>\n";
	echo "      <tr><td>Potwierd¼ nowe has³o</td><td><input class='w75' type='password' name='confirm' value=''/></td></tr>\n";
	echo "      <tr><td><input type='hidden' name='upt_name' value='pass'/></td><td><input type='submit' class='submit' value='Potwierd¼'/></td></tr>\n";
	echo "    </form>\n";
	echo "    <form action='' method='post'>\n";
	echo "      <tr><td colspan='2'><h6>Inne</h6></td></tr>\n";
	echo "      <tr><td width='200'>Ukryj adres e-Mail</td><td>".form_option_bool($prf['display_mail'],"i1","Nie","Tak")."</td></tr>\n";
	echo "      <tr><td>Ukryj moj± obecno¶æ on-line</td><td>".form_option_bool($prf['display_online'],"i2","Nie","Tak")."</td></tr>\n";
	echo "      <tr><td><input type='hidden' name='upt_name' value='display'/></td><td><input type='submit' class='submit' value='Potwierd¼'/></td></tr>\n";
	echo "    </form>\n";
	echo "    </table>\n";
}
if($_GET['show'] == 1 AND ($_GET['show'] != 0 OR $_GET['show'] != 1))
{
	echo "    <table class='table0 w100 form-style1'>\n";
	echo "    <form action='' method='post'>\n";
	echo "      <tr><td colspan='2'><h6>Komunikatory</h6></td></tr>\n";
	echo "      <tr><td width='200'>GG</td><td><input class='w50' type='text' name='i1' value='".$prf['gg']."'/></td></tr>\n";
	echo "      <tr><td>ICQ</td><td><input class='w50' type='text' name='i2' value='".$prf['icq']."'/></td></tr>\n";
	echo "      <tr><td><input type='hidden' name='upt_name' value='contact'/></td><td><input type='submit' class='submit' value='Potwierd¼'/></td></tr>\n";
	echo "    </form>\n";	
	echo "    <form action='' method='post'>\n";
	echo "      <tr><td colspan='2'><h6>Dane gracza</h6></td></tr>\n";
	echo "      <tr><td width='200'>Nick</td><td><input disabled='disabled' class='w75' type='text' name='i1' value='".$prf['gamenick']."'/></td></tr>\n";
	echo "      <tr><td>Klan</td><td><input type='text' name='i2' maxlength='8' value='".$prf['clan']."'/></td></tr>\n";
	echo "      <tr><td>Ulubione rules</td><td><input class='w75' type='text' name='i3' value='".$prf['favrules']."'/></td></tr>\n";
	echo "      <tr><td>Tytu³ u¿ytkownika</td><td><input class='w75' type='text' name='i4' value='".$prf['usertitle']."'/></td></tr>\n";
	echo "      <tr><td><input type='hidden' name='upt_name' value='player'/></td><td><input type='submit' class='submit' value='Potwierd¼'/></td></tr>\n";
	echo "    </form>\n";	
	echo "    <form action='' method='post'>\n";
	echo "      <tr><td colspan='2'><h6>Dane personalne</h6></td></tr>\n";
	echo "      <tr><td width='200'>Imiê</td><td><input class='w75' type='text' name='i1' value='".$prf['name']."'/></td></tr>\n";
	echo "      <tr><td>Urodziny</td><td><input type='text' name='i2a' maxlength='4' style='width:50px;text-align:center;' value='".@$born[0]."'/> - <input type='text' name='i2b' maxlength='2' style='width:25px;text-align:center;' value='".@$born[1]."'/> - <input type='text' name='i2c' maxlength='2' style='width:25px;text-align:center;' value='".@$born[2]."'/> <span class='text-tiny'>( RRRR - MM - DD )</span></td></tr>\n";
	echo "      <tr><td>P³eæ</td><td>".form_option_bool($prf['gender'],"i3","Kobieta","Mê¿czyzna")."</td></tr>\n";
	echo "      <tr><td>Zainteresowania</td><td><input class='w75' type='text' name='i4' value='".$prf['intrest']."'/></td></tr>\n";
	echo "      <tr><td>Miejsce zamieszkania</td><td><input class='w75' type='text' name='i5' value='".$prf['location']."'/></td></tr>\n";
	echo "      <tr><td><input type='hidden' name='upt_name' value='person'/></td><td><input type='submit' class='submit' value='Potwierd¼'/></td></tr>\n";
	echo "    </form>\n";
	echo "    </table>\n";
}

require_once (BASEDIR.STYLE."tpl.end.php");
?>
