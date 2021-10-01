<?php
require_once("maincore.php");
require_once(BASEDIR.STYLE."tpl.start.php");
require_once("lea.core.php");

score_stats();
score_players_stats_history();

	$sql = "SELECT * FROM `lea_players` WHERE `active` = '1' ORDER BY `score_stats` DESC";
	$query = mysql_query($sql);
	if(@mysql_num_rows($query) > 0)
	{
		echo "<table class='table0 w100'>\n";
		echo "  <tr><th class='lea-lp'>Poz.</th><th class='lea-country'>Kraj</th><th class='lea-nick'>Nick</th><th class='lea-clan'>Klan</th><th class='lea-score'>Punkty</th><th class='lea-games'>W</th><th class='lea-games'>G</th><th class='lea-changes'>Poz.</th><th class='lea-changes'>Pkt.</th></tr>\n";
		$i = 1;
		while($player = mysql_fetch_assoc($query))
		{
			$sql2 = "SELECT `id`, `gamenick`, `clan`, `country` FROM `users` WHERE `id` = '".$player['user_id']."' LIMIT 1";
			$query2 = mysql_query($sql2);
			$user = mysql_fetch_assoc($query2);
			
			$second_row = "";
			if( $i%2 == 0 ) $second_row = " class='row2'";
			
			if(($player['last_stats_position'] - $i) < 0) $pos_change = "<img src='images/icons/league/r.png' title='-".abs($player['last_stats_position'] - $i)."' alt='-".abs($player['last_stats_position'] - $i)."'>";
			if(($player['last_stats_position'] - $i) > 0) $pos_change = "<img src='images/icons/league/g.png' title='+".abs($player['last_stats_position'] - $i)."' alt='+".abs($player['last_stats_position'] - $i)."'>";
			if(($player['last_stats_position'] - $i) == 0) $pos_change = "<img src='images/icons/league/b.png' title='Bez zmian' alt='Bez zmian'>";
			if($player['last_stats_position'] == 0) $pos_change = "<img src='images/icons/league/b.png' title='NOWY' alt='NOWY'>";
			
			echo "  <tr".$second_row.">";
			echo "<td class='center'>".$i."</td>";
			echo "<td class='center'><img src='images/flags/small_".$user['country'].".png' title='".db_get_data("name","reg_countries","code",$user['country'])."' border='0'/></td>";
			echo "<td class='center'><a href='lea.players.php?p_id=".$user['id']."'>".$user['gamenick']."</a></td>";
			echo "<td class='center'><img src='images/clans/".$user['clan'].".gif' alt='".$user['clan']."'/></td>";
			echo "<td class='center'>".$player['score_stats']."</td>";
			echo "<td class='center'>".$player['wins']."</td>";
			echo "<td class='center'>".($player['wins']+$player['lost'])."</td>";
			echo "<td class='center'>".$pos_change."</td>";
			echo "<td class='center'>".($player['score_stats'] - $player['last_stats_score'])."</td>";
			echo "</tr>\n";
			$i = $i+1;
		}
		echo "</table>";
	}
	else echo "<div class='not'>Liga aktualnie nie dzia³a</div>\n";

require_once(BASEDIR.STYLE."tpl.end.php");
?>
