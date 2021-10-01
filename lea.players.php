<?php
require_once("maincore.php");
if(check_int($_GET['p_id']) != TRUE) redirect($config['mainpage']);
add_title("Liga 0 : ".db_get_data("gamenick","users","id",$_GET['p_id']));
require_once(BASEDIR.STYLE."tpl.start.php");

if(check_int($_GET['p_id']) == TRUE)
{
	$id = ($_GET['p_id']);
	$sql =	"SELECT * FROM ".DBPREFIX."users WHERE `active`='1' AND `siteclan_member`='1' AND `id` = '".$id."'";
	$query   =	mysql_query($sql);
	if (@mysql_num_rows($query) > 0)
	{
		$player = mysql_fetch_assoc($query);
		
		$sql2 = "SELECT * FROM ".DBPREFIX."lea_players WHERE `user_id` = '".$id."'";
		$query2   =	mysql_query($sql2);
		$league = mysql_fetch_assoc($query2);
		
		
		if($league['active'] == 1) $league['active'] = "<span class='green'>Aktywny</span>";
		else $league['active'] = "<span class='red'>Nieaktywny</span>";
		if(empty($player['avatar'])) $player['avatar'] = "images/avatars/blank.png";
		if($player['gg'] == 0) $player['gg'] = "n/a";
		if($player['icq'] == 0) $player['icq'] = "n/a";
		
		echo "\n";
		echo "<!-- DANE: ".$player['login']." -->\n";
		echo "\n";
		echo "    <div class='player-head'>\n";
		echo "      <img src='".$player['avatar']."' class='left profile-avatar' width='90' height='90'/>\n";
		echo "      <h4 style='font-style:italic;'>".$player['login']."</h4>\n";
		echo "      <img src='images/clans/".$player['clan'].".gif'/><br/>";
		echo $player['usertitle'];
		echo "    </div>\n";
		echo "    <div class='clear'><br></div>\n";
		
		echo "    <table class='table0 left table-clan'>\n";
		echo "      <tr><th colspan='2'>Liga 0</th></tr>\n";
		echo "      <tr><td width='150'>Aktywny</td><td>".$league['active']."</td></tr>\n";
		echo "      <tr><td width='150'>Zwyciêstw</td><td>".$league['wins']."</td></tr>\n";
		echo "      <tr><td width='150'>Pora¿ek</td><td>".$league['lost']."</td></tr>\n";
		echo "    </table>\n";
		
		echo "    <table class='table0 right table-clan'>\n";
		echo "      <tr><th colspan='2'>Dane kontaktowe</th></tr>\n";
		echo "      <tr><td width='100'>Mail</td><td>".$player['mail']."</td></tr>\n";
		echo "      <tr><td width='100'>GG</td><td>".$player['gg']."</td></tr>\n";
		echo "      <tr><td width='100'>ICQ</td><td>".$player['icq']."</td></tr>\n";
		echo "    </table>\n";
		
		echo "<div class='clear'><br></div>\n";
		
		echo "<table class='table0 w100'>\n";
		echo "<tr><th>Statystyki</th></tr>\n";
		echo "<tr><td><h6>Medale</h6></td></tr>\n";
		
		echo "<tr><td>\n";
		$listof = explode(";",$league['medals']);
		for($i = 0;!empty($listof[$i]);$i++)
		{
			$medal = explode(".",$listof[$i]);
			echo "<div class='center left medal-block'><img src='images/icons/league/".$medal[0].".png'/><div class='clear'><strong>".$medal[1]."</strong></div></div>";
		}
		
		echo "</td></tr>\n";
		echo "</table>\n";
	}
}
	
require_once(BASEDIR.STYLE."tpl.end.php");
?>
