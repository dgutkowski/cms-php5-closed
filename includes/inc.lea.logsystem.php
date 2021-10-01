<?php

function lea_application($answer = "", $answer_type = "not")
{
	$hide_form = FALSE;
	if($answer_type == "suc" && $answer_type != FALSE) $hide_form = TRUE;
	
	if(!empty($answer)) $string = "<div class='".$answer_type."'>".$answer."</div>\n";
	
	if(@$hide_form != TRUE)
	{
		$string = "      <form action='' method='post' class='form-register'>\n";
		$string.= "        <table class='table0 table-register'>\n";
		$string.= "          <tr><th colspan='2'>Rejestracja</th></tr>\n";
		$string.= "          <tr><td class='reg-col1'>Podaj swoje has³o dla akceptacji</td><td><input type='password' value='' name='password' class='w70'/></td></tr>\n";
		$string.= "          <tr><td class='reg-col1'>&nbsp;</td><td><span class='text-tiny'>Zapisuj±c siê zgadzasz siê na przestrzeganie regulaminu Ligi</span></td></tr>\n";
		$string.= "          <tr><td class='reg-col1'><input type='hidden' name='sendlea' value='register'/></td><td><input type='submit' class='submit w50' value='Zarejestruj siê'/></td></tr>\n";
		$string.= "        </table>\n";
		$string.= "      </form>\n";
	}
	return $string;
}

function lea_application_process()
{
	$pass = $_POST["password"];
	$p_id = $_SESSION['userid'];
	if(strlen($pass) >= 4)
	{
		$sql = "SELECT * FROM ".DBPREFIX."users WHERE `id` = '".$p_id."' AND `password` = '".md5(htmlspecialchars($pass))."' LIMIT 1";
		$query = mysql_query($sql);
		if(@mysql_num_rows($query))
		{
			$sql = "SELECT * FROM ".DBPREFIX."lea_players WHERE `id` = '".$p_id."' LIMIT 1";
			$query = mysql_query($sql);
			if(@mysql_num_rows($query) == 0)
			{
				$sql = "INSERT INTO ".DBPREFIX."lea_players VALUES('".$p_id."','".$p_id."','0','1','0','0','0','0','0','0','');";
				mysql_query($sql);
				return "Czekaj na potwierdzenie rejestracji\n";
			}
			else return "Wyst±pi³ b³±d: <b>IN/LL#42</b><br>Skontaktuj siê z administratorem\n";
		}
		else return "Brak autoryzacji!";
	}
	else return "Brak autoryzacji!";
}

?>
