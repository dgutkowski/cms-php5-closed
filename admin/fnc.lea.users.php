<?php

if( $_GET['option'] == "lea.players" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	// fixing user id

	session_check();
	
	if(!empty($_POST['p_id']) AND check_int($_POST['p_id']) == TRUE)
	{
		$p_id = $_POST['p_id'];
		$sql = "SELECT * FROM ".DBPREFIX."lea_players WHERE `active` = '0' AND `user_id` = '".$p_id."' LIMIT 1";
		if(@mysql_num_rows(mysql_query($sql)))
		{
			$sql = "UPDATE ".DBPREFIX."lea_players SET `active` = '1' WHERE `user_id` = '".$p_id."' LIMIT 1";
			mysql_query($sql);
			echo "<div class='not'>Gracz aktywowany poprawnie</div>\n";
		}
		else echo "<div class='err'>Wyst±pi³ b³±d: <b>AD/LU#17</b><br>Skontaktuj siê z g³ównym administratorem</div>\n";
		unset($p_id);
	}
	
	// form
	
	if(!empty($result))
	{
		echo "<div class='suc'>".$result."</div>\n";
	}
	echo "<table class='table0 w75 form-style1'>\n";
	echo "<tr><th colspan='2'>Liga 0 : Gracze</th></tr>\n";
	echo "<tr><td colspan='2' align='left'><h6>"; if(isset($edit['id'])) echo "Edytujesz: <i>article.php?id=".$edit['id']."</i>"; else echo "Nowy"; echo "<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td></tr>\n";
	
	$sql = "SELECT * FROM ".DBPREFIX."lea_players WHERE `active` = '0'";
	$query = mysql_query($sql);
	if(@mysql_num_rows($query) > 0)
	{
		while($player = mysql_fetch_assoc($query))
		{
			echo "<tr>";
			echo "<form action='' method='post'>";
			echo "<td widht='200'>".db_get_data("login","users","id",$player['user_id'])."</td><td><input type='hidden' name='p_id' value='".$player['id']."'><input type='submit' class='submit-bans right' value='Aktywuj'></td>";
			echo "</form>";
			echo "</tr>\n";
		}
	}
	else
	echo "<tr><td colspan='2'><b class='blue'>Brak graczy ubiegaj±cych siê o zapis</b></td></tr>\n";
	echo "</table>\n\n";
	
	// bany
	
	echo "<br>";
	echo "<table class='table0 w75 form-style1'>\n";
	echo "<tr><td colspan='2' align='left'><h6>Banowanie</h6></td></tr>\n";
	echo "<tr><td colspan='2'><b class='blue'>Brak graczy ubiegaj±cych siê o ban :)</b></td></tr>\n";
	echo "</table>\n\n";
}

?>
