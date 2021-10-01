<?php
require_once("maincore.php");
require_once(BASEDIR.STYLE."tpl.start.php");
require_once("lea.core.php");
score_games();

	$sql = "SELECT * FROM `lea_players` WHERE `active` = '1' ORDER BY `score` DESC";
	$query = mysql_query($sql);
	if(@mysql_num_rows($query) > 0)
	{
		// echo "<h2>Tabela ligowa</h2>\n";
		echo "<table class='table0 w100'>\n";
		echo "  <tr><th class='lea-lp'>Poz.</th><th class='lea-country'>Kraj</th><th class='lea-nick'>Nick</th><th class='lea-clan'>Klan</th><th class='lea-score'>Punkty</th><th class='lea-games'>Mecze</th><th class='lea-perc'>Skt. [%]</th><th>W/G</th></tr>\n";
		$i = 1;
		while($player = mysql_fetch_assoc($query))
		{
			$sql2 = "SELECT `id`, `gamenick`, `clan`, `country` FROM `users` WHERE `id` = '".$player['user_id']."' LIMIT 1";
			$query2 = mysql_query($sql2);
			$user = mysql_fetch_assoc($query2);
			
			$sql3 = "SELECT `winner`, `score` FROM `lea_games` WHERE `player1` = '".$player['user_id']."' OR `player2` = '".$player['user_id']."'";
			$query3 = mysql_query($sql3);
			$win = 0;
			$los = 0;
			$games['total'] = mysql_num_rows($query3);
			while($score = mysql_fetch_assoc($query3))
			{
				if(1 == 1)
				{
					if($score['winner'] == $player['user_id'])
					{
						switch ($score['score'])
						{
							case 1:
								$win = $win+2;
								break;
							case 2:
								$win = $win+2;
								$los = $los+1;
								break;
						}
					}
					else
					{
						switch ($score['score'])
						{
							case 1:
								$los = $los+2;
								break;
							case 2:
								$win = $win+1;
								$los = $los+2;
								break;
						}
					}
				}
				else $games['total'] = $games['total']-1;
			}
			if( $win+$los != 0 ) $games['acc'] = ($win/($win+$los))*100;
			else $games['acc'] = "n/d";
			
			$second_row = "";
			if( $i%2 == 0 ) $second_row = " class='row2'";
			
			echo "  <tr".$second_row."><td class='center'>".$i."</td><td class='center'><img src='images/flags/small_".$user['country'].".png' title='".db_get_data("name","reg_countries","code",$user['country'])."' border='0'/></td><td class='center'><a href='lea.players.php?p_id=".$user['id']."'>".$user['gamenick']."</a></td><td class='center'><img src='images/clans/".$user['clan'].".gif' alt='".$user['clan']."'/></td><td class='center'>".$player['score']."</td></td><td class='center'>".$games['total']."</td></td><td class='center'>".substr($games['acc'],0,5)."</td><td class='center'>".$win."/".($win+$los)."</td></tr>\n";
			$i = $i+1;
		}
		echo "</table>";
	}
	else echo "<div class='not'>Liga aktualnie nie dzia³a</div>\n";

require_once(BASEDIR.STYLE."tpl.end.php");
?>
