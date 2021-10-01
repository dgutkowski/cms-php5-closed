<?php

function registration_form($answer = "", $answer_type = "not")
{

	if($answer_type == "suc" && $answer_type != FALSE) $hide_form = TRUE;
	
	if(!empty($answer)) $string = "<div class='".$answer_type."'>".$answer."</div>\n";
	
	if(@$hide_form != TRUE)
	{
		$string = "      <form action='' method='post' class='form-register'>\n";
		$string.= "        <table class='table0 table-register'>\n";
		$string.= "          <tr><th colspan='2'>Rejestracja</th></tr>\n";
		$string.= "          <tr><td class='reg-col1'>Nick <span class='red'>*</span></td><td><input type='text' value='' name='r1' class='w70'/><span class='text-tiny'> np. Gracz</span></td></tr>\n";
		$string.= "          <tr><td class='reg-col1'>Has³o <span class='red'>*</span></td><td><input type='password' value='' name='r2' class='w70'/></td></tr>\n";
		$string.= "          <tr><td class='reg-col1'>Potwierd¼ has³o <span class='red'>*</span></td><td><input type='password' value='' name='r3' class='w70'/></td></tr>\n";
		$string.= "          <tr><td class='reg-col1'>e-Mail <span class='red'>*</span></td><td><input type='text' value='' name='r4' class='w50'/></td></tr>\n";
		$string.= "          <tr><td class='reg-col1'>Klan</td><td><input type='text' value='' name='r5' class='w50'/><span class='text-tiny'> np. KLAN</span></td></tr>\n";
		$string.= "          <tr><td class='reg-col1'>&nbsp;</td><td><span class='text-tiny'><b class='red'>*</b> - pola wymagane<br>Rejestruj±c siê zgadzasz siê na przestrzeganie regulaminu portalu</span></td></tr>\n";
		$string.= "          <tr><td class='reg-col1'><input type='hidden' name='send' value='register'/></td><td><input type='submit' class='submit w50' value='Zarejestruj siê'/></td></tr>\n";
		$string.= "        </table>\n";
		$string.= "      </form>\n";
	}
	return $string;

}

function log_user_in()
{
	$login = $_POST["login"];
	$pass  = $_POST["password"];
	if(!isset($_SESSION["logged-in"])) $_SESSION["logged-in"] = FALSE;
	if($_SESSION["logged-in"] != 1 && $_POST['send'] == "login")
	{
		if(!empty($_POST["login"]) && !empty($_POST["password"]))
		{  
	      $sql	=	"SELECT `login`,`id` FROM ".DBPREFIX."users WHERE 
				`login` = '".htmlspecialchars($login)."' AND 
	  			`password` = '".md5(htmlspecialchars($pass))."' AND 
	  			`active` = '1'";
	  			
	    	$query = mysql_query($sql);
	      
	      // Sprawdzanie warunku
	      
	      if(@mysql_num_rows($query))
	      {
				$session = mysql_fetch_assoc($query);
	        
				$_SESSION['logged-in']	= 1;
				$_SESSION['sid']	= session_id();
				$_SESSION['login']	= $session['login']; 
				$_SESSION['userid'] = $session['id'];
	      
				$sql = "SELECT `last_ip` FROM `".DBPREFIX."users` WHERE `login` = '".$_SESSION['login']."' AND `id` = '".$_SESSION['userid']."' LIMIT 1;";
	      			
				$query = mysql_query($sql);
				$_SESSION['lastip'] = mysql_fetch_row($query);
	        
				$sqltext = "UPDATE `".DBPREFIX."users` SET `last_ip` = '".USER_IP."', `last_seen` = '".DATETIME."' WHERE `login` = '".$_SESSION['login']."' AND `id` = '".$_SESSION['userid']."' LIMIT 1;";
				mysql_query($sqltext);
				
				return 1;
			}
	      else return 3;
		}
    	else return 2;
  }
  return FALSE;
}

function log_user_out()
{
	if($_SESSION["logged-in"] == 1)
	{
		if($_GET["logout"] == "yes")
		{
			$_SESSION["logged-in"] = 0;
			session_unset();
			return TRUE;
		}
	}
	else return FALSE;
}
?>
