<?php
require_once "maincore.php";
add_title($config['clanname']." team");
require_once (BASEDIR.STYLE."tpl.start.php");

if( !isset($_GET['action']) || $_GET['action'] != "show" )
{
	echo "<h2>".$config['clanname']."</h2>\n";
	echo "\n";
	echo "\n";
	echo "<dl>\n";
	
	$sql =	"SELECT `id`, `login`, `country`, `last_date` FROM ".DBPREFIX."users WHERE `active`='1' AND `siteclan_member`='1' ORDER BY `login` ASC";		
	$query = mysql_query($sql);
	if (@mysql_num_rows($query) > 0)
	{
		while($mmbr = mysql_fetch_assoc($query))
		{
  			if(empty($mmbr['country'])) $mmbr['country'] = "blank";
			echo "<li><a href='clan-members.php?action=show&member_id=".$mmbr['id']."'>";
			echo "<img src='images/flags/small_".$mmbr['country'].".png' title='".db_get_data("name","reg_countries","code",$mmbr['country'])."' border='0'/>";
			echo " ".$mmbr['login']."</a>";
			
			echo "</li>\n";
		}
	}
	echo "</dl>\n";
}
elseif( $_GET['action'] == "show" AND check_int($_GET['member_id']) == TRUE)
{
	$id = ($_GET['member_id']);
	$sql =	"SELECT * FROM ".DBPREFIX."users WHERE `active`='1' AND `siteclan_member`='1' AND `id` = '".$id."'";
	$query   =	mysql_query($sql);

	if (@mysql_num_rows($query) > 0)
	{
		$player = mysql_fetch_assoc($query);
		
		if(get_time_difference($player['last_date'])<2678400) $player['active'] = "<span class='green'>Aktywny</span>"; else $player['active'] = "<span class='red'>Nieaktywny</span>";
		$clantime = explode("-",$player['siteclan_date']); if ($clantime[0] == 2004) $clantime[0] = "Brak danych";
		if($player['gg'] == 0) $player['gg'] = "n/a";
		if($player['icq'] == 0) $player['icq'] = "n/a";
		if($player['born'] == 0) $player['born'] = "n/a";
		if(empty($player['avatar'])) $player['avatar'] = "images/avatars/blank.png";
		if(empty($player['name'])) $player['name'] = "n/a";
		if(empty($player['intrest'])) $player['intrest'] = "n/a";
		if(empty($player['favrules'])) $player['favrules'] = "n/a";
		if(empty($player['location'])) $player['location'] = "n/a";
		$player['desc'] = stripslashes($player['desc']);
		
		if($player['display_mail'] == 0) $player['mail'] = "<i>Ukryty</i>\n";
		
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
		echo "      <tr><th colspan='2'>Dane kontaktowe</th></tr>\n";
		echo "      <tr><td width='100'>Mail</td><td>".$player['mail']."</td></tr>\n";
		echo "      <tr><td width='100'>GG</td><td>".$player['gg']."</td></tr>\n";
		echo "      <tr><td width='100'>ICQ</td><td>".$player['icq']."</td></tr>\n";
		echo "    </table>\n";
		
		echo "    <table class='table0 right table-clan'>\n";
		echo "      <tr><th colspan='2'>Dane personalne</th></tr>\n";
		echo "      <tr><td width='100'>Imiê</td><td>".$player['name']."</td></tr>\n";
		echo "      <tr><td width='100'>Urodziny</td><td>".$player['born']."</td></tr>\n";
		echo "      <tr><td width='100'>P³eæ</td><td>".get_gender($player['gender'])."</td></tr>\n";
		echo "      <tr><td width='100'>Zainteresowania</td><td>".$player['intrest']."</td></tr>\n";
		echo "      <tr><td width='100'>Miejscowo¶æ</td><td>".$player['location']."</td></tr>\n";
		echo "    </table>\n";
		
		if($clantime[0] > 2005)
		{
			if($clantime[1] == 1)$clantime = "stycznia ".$clantime[0];
			if($clantime[1] == 2)$clantime = "lutego ".$clantime[0];
			if($clantime[1] == 3)$clantime = "marca ".$clantime[0];
			if($clantime[1] == 4)$clantime = "kwietnia ".$clantime[0];
			if($clantime[1] == 5)$clantime = "maja ".$clantime[0];
			if($clantime[1] == 6)$clantime = "czerwca ".$clantime[0];
			if($clantime[1] == 7)$clantime = "lipca ".$clantime[0];
			if($clantime[1] == 8)$clantime = "sierpnia ".$clantime[0];
			if($clantime[1] == 9)$clantime = "wrze¶nia ".$clantime[0];
			if($clantime[1] == 10)$clantime = "pa¼dziernika ".$clantime[0];
			if($clantime[1] == 11)$clantime = "listopada ".$clantime[0];
			if($clantime[1] == 12)$clantime = "grudnia ".$clantime[0];
		}
		else $clantime = "zawsze"; 
		echo "    <div class='clear-l'><br></div>\n";
		echo "    <table class='table0 left table-clan'>\n";
		echo "      <tr><th colspan='2'>Dane gracza</th></tr>\n";
		echo "      <tr><td width='100'>Nick</td><td>".$player['login']."</td></tr>\n";
		echo "      <tr><td width='100'>Aktywno¶æ</td><td>".$player['active']."</td></tr>\n";
		echo "      <tr><td width='100'>Ulubione rules</td><td>".$player['favrules']."</td></tr>\n";
		echo "      <tr><td width='100'>W klanie od</td><td>".$clantime."</td></tr>\n";
		echo "    </table>\n";
		echo "    <div class='clear-l'><br></div>\n";
		if(0 == 0)
		{
			echo "    <table class='table0 left w100'>\n";
			echo "      <tr><th>Osi±gniêcia</th></tr>\n";
			echo "      <tr><td>".$player['desc']."</td></tr>\n";
			echo "    </table>\n";
		}
	}
}

require_once (BASEDIR.STYLE."tpl.end.php");
?>
