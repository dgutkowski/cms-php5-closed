<?php

/* Header
/*
/* Core file of League 0
/* Created by Daniel Gutkowski
/* for Polish Revolution clan
/* (c) 2012 All rights reserved
/* Integral part of Polish Revolution's website
/*----------------------------------------------*/

define("SCORE1", "10");	$score[0] = FALSE;
define("SCORE2", "7");	$score[1] = FALSE;
define("SCORE3", "3");	$score[2] = FALSE;
define("SCORE4", "1");	$score[3] = FALSE;

/* Content
/*
/*	settings
/*	converting of score
/*	score for table & stats
/*	challange printing
/*  challange accepting
/*	league admin tools
/*
/*----------------------------------------------*/

function lea_free_date($string = "01.03;01.06;01.09;01.12")
{
	$test = explode(";",$string);
	for($i = 0;!empty($test[$i]);$i++)
	{
		if(date("d.m") == $test[$i]) return TRUE;
	}
	return FALSE;
}

function set_season($m = FALSE)
{
	if($m == FALSE) $m = date("m");
	$date = date("Y");
	switch($m)
	{
		case 1: return "1.".$date; break;
		case 2: return "1.".$date; break;
		case 3: return "2.".$date; break;
		case 4: return "2.".$date; break;
		case 5: return "2.".$date; break;
		case 6: return "3.".$date; break;
		case 7: return "3.".$date; break;
		case 8: return "3.".$date; break;
		case 9: return "4.".$date; break;
		case 10: return "4.".$date; break;
		case 11: return "4.".$date; break;
		case 12: return "1.".($date+1); break;
	}
}
define("SEASON", set_season());

// Main config

function lea_getsetting($name)
{
	$sql = "SELECT * FROM `".DBPREFIX."lea_settings` LIMIT 1";	
	$query = @mysql_query($sql);
	
	if(@mysql_num_rows($query) > 0)
	{
		$config = @mysql_fetch_assoc($query);
		return $config[$name];
	}
	else
	{
    	return FALSE;
	}
}

function lea_uptsetting($name,$value)
{
	$sql = "UPDATE `".DBPREFIX."lea_settings` SET `".$name."` = '".$value."' LIMIT 1";	
	$query = @mysql_query($sql);
	
	if(@mysql_num_rows($query) > 0)
	{
		$config = @mysql_fetch_assoc($query);
		return $config[$name];
	}
	else
	{
    	return FALSE;
	}
}

// Convert functions

function score_players($game_id, $winner_id, $looser_id, $score_win, $score_los, $score)
{
	$sql = "SELECT `score`, `wins`, `lost` FROM ".DBPREFIX."lea_players WHERE `user_id` = '".$winner_id."' LIMIT 1";
	$query = mysql_query($sql);
	if(mysql_num_rows($query) == TRUE)
	{
		$result = mysql_fetch_assoc($query);
		$score1 = $result['score'] + $score_win;
		if($score == 1){ $wins1 = $result['wins']+2; $lost1 = $result['lost']; }
		if($score == 2){ $wins1 = $result['wins']+2; $lost1 = $result['lost']+1; }
	}
	else { echo "<div class='err'>B³±d. W przypadku d³u¿szego wystêpowania komunikatu skontaktuj siê z administratorem!</div>"; return FALSE; }
	$sql = "SELECT `score`, `wins`, `lost` FROM ".DBPREFIX."lea_players WHERE `user_id` = '".$looser_id."' LIMIT 1";
	$query = mysql_query($sql);
	if(mysql_num_rows($query) == TRUE)
	{
		$result = mysql_fetch_assoc($query);
		$score2 = $result['score'] + $score_los;
		if($score == 1){ $wins2 = $result['wins']; $lost2 = $result['lost']+2; }
		if($score == 2){ $wins2 = $result['wins']+1; $lost2 = $result['lost']+2; }
	}
	else { echo "<div class='err'>B³±d. W przypadku d³u¿szego wystêpowania komunikatu skontaktuj siê z administratorem!</div>"; return FALSE; }
	$sql2 = "UPDATE ".DBPREFIX."lea_players SET `score` = '".$score1."', `wins` = '".$wins1."', `lost` = '".$lost1."' WHERE `user_id` = '".$winner_id."' LIMIT 1";
	mysql_query($sql2);
			
	$sql2 = "UPDATE ".DBPREFIX."lea_players SET `score` = '".$score2."', `wins` = '".$wins2."', `lost` = '".$lost2."' WHERE `user_id` = '".$looser_id."' LIMIT 1";
	mysql_query($sql2);
		
	$sql3 = "UPDATE ".DBPREFIX."lea_games SET `scored` = '1' WHERE `id` = '".$game_id."' LIMIT 1";
	mysql_query($sql3);
}

function score_players_stats($game_id, $winner_id, $looser_id, $score_win, $score_los)
{
	$sql = "SELECT `score_stats` FROM ".DBPREFIX."lea_players WHERE `user_id` = '".$winner_id."' LIMIT 1";
	$query = mysql_query($sql);
	if(mysql_num_rows($query) == TRUE)
	{
		$result = mysql_fetch_assoc($query);
		$score1 = $result['score_stats'];
	}
	else { echo "<div class='err'>B³±d. W przypadku d³u¿szego wystêpowania komunikatu skontaktuj siê z administratorem!</div>"; return FALSE; }
	$sql = "SELECT `score_stats` FROM ".DBPREFIX."lea_players WHERE `user_id` = '".$looser_id."' LIMIT 1";
	$query = mysql_query($sql);
	if(mysql_num_rows($query) == TRUE)
	{
		$result = mysql_fetch_assoc($query);
		$score2 = $result['score_stats'];
	}
	else { echo "<div class='err'>B³±d. W przypadku d³u¿szego wystêpowania komunikatu skontaktuj siê z administratorem!</div>"; return FALSE; }
	
	$s_diff = $score1 - $score2;
	if(ceil($s_diff*0.04) > 14) $s_diff = 350;
	$s_diffWin = ceil($s_diff*0.04);
	$s_diffLos = ceil($s_diff*0.035);
	$scoreNew_1 = $score1 + $score_win - $s_diffWin;
	$scoreNew_2 = $score2 + $score_los + $s_diffLos;
	if($scoreNew_1 < 0)$scoreNew_1 = 0;
	if($scoreNew_2 < 0)$scoreNew_2 = 0;
	
	$sql2 = "UPDATE ".DBPREFIX."lea_players SET `score_stats` = '".$scoreNew_1."' WHERE `user_id` = '".$winner_id."' LIMIT 1";
	mysql_query($sql2);		
	$sql2 = "UPDATE ".DBPREFIX."lea_players SET `score_stats` = '".$scoreNew_2."' WHERE `user_id` = '".$looser_id."' LIMIT 1";
	mysql_query($sql2);
	$sql3 = "UPDATE ".DBPREFIX."lea_games SET `scored` = '2' WHERE `id` = '".$game_id."' LIMIT 1";
	mysql_query($sql3);
}

// Write a history of each player in his statistics

function score_players_stats_history()
{
	$test = date("Y",strtotime("-1 week"))."-".date("m",strtotime("-1 week"))."-".date("d",strtotime("-1 week"));
	if(lea_getsetting("last_stats_date") <= $test)
	{
		$sql = "SELECT `id`, `score_stats` FROM ".DBPREFIX."lea_players ORDER BY `score_stats` DESC";
		$query = mysql_query($sql);
		if(mysql_num_rows($query) > 0)
		{
			$i = 0;
			while($data = mysql_fetch_assoc($query))
			{
				$i = $i+1;
				$sql2 = "UPDATE ".DBPREFIX."lea_players SET `last_stats_score` = '".$data['score_stats']."', `last_stats_position` = '".$i."' WHERE `id` = ".$data['id']." LIMIT 1";
				mysql_query($sql2);
			}
		}
		lea_uptsetting("last_stats_date",date("Y-m-d"));
	}
	else return FALSE;
}

// Convert a score for actual season

function score_games()
{
	if (TRUE)
	{
		$sql = "SELECT * FROM ".DBPREFIX."lea_games WHERE `scored` = '0' ORDER BY `date` ASC";
		$query = mysql_query($sql);
		if(mysql_num_rows($query) > 0)
		{
			while($game = mysql_fetch_assoc($query))
			{
				if($game['player1'] == $game['winner'])
				{
					if($game['score'] == 1)
					{
						score_players($game['id'],$game['player1'],$game['player2'],SCORE1,SCORE4,$game['score']);
					}
					if($game['score'] == 2)
					{
						score_players($game['id'],$game['player1'],$game['player2'],SCORE2,SCORE3,$game['score']);
					}
				}
				if($game['player2'] == $game['winner'])
				{
					if($game['score'] == 1)
					{
						score_players($game['id'],$game['player2'],$game['player1'],SCORE1,SCORE4,$game['score']);
					}
					if($game['score'] == 2)
					{
						score_players($game['id'],$game['player2'],$game['player1'],SCORE2,SCORE3,$game['score']);
					}
				}
			}
		}
	}
}

// Scoring a relative statistics

function score_stats()
{
	if (date("H") >= 22 OR date("H") <= 10)
	{
		$sql = "SELECT * FROM ".DBPREFIX."lea_games WHERE `scored` = '1' ORDER BY `date` ASC";
		$query = mysql_query($sql);
		if(mysql_num_rows($query) > 0)
		{
			while($game = mysql_fetch_assoc($query))
			{
				if($game['player1'] == $game['winner'])
				{
					if($game['score'] == 1)
					{
						score_players_stats($game['id'],$game['player1'],$game['player2'],21,-12);
					}
					if($game['score'] == 2)
					{
						score_players_stats($game['id'],$game['player1'],$game['player2'],13,-8);
					}
				}
				if($game['player2'] == $game['winner'])
				{
					if($game['score'] == 1)
					{
						score_players_stats($game['id'],$game['player2'],$game['player1'],21,-12);
					}
					if($game['score'] == 2)
					{
						score_players_stats($game['id'],$game['player2'],$game['player1'],13,-8);
					}
				}
			}
		}
	}
}

// Printing a list of opponents to select

function print_list_of_opponents($challanger_id,$date)
{	
	$return = FALSE;
	$sql = "SELECT `id`, `player1`, `player2` FROM ".DBPREFIX."lea_challanges WHERE (`player1` = '".$challanger_id."' OR `player2` = '".$challanger_id."') AND `actived` = '0'";
	$query = mysql_query($sql);
	
	$sql_test = "SELECT `id` FROM ".DBPREFIX."lea_challanges WHERE (`player1` = '".$challanger_id."' OR `player2` = '".$challanger_id."') AND `actived` = '1'";
	$query_test = mysql_query($sql_test);
	if(mysql_num_rows($query_test) < $date)
	{
		$string = "      <form class='form-style1' action='' method='post'>\n";
		$string.= "        <input type='hidden' name='send' value='achallange'/><input type='submit' value='Aktywuj' class='submit right'/>\n";
		$string.= "<select name='challange_id' class='lea-setchallange right'>\n";
		if(mysql_num_rows($query) > 0)
		{
			while($data = mysql_fetch_assoc($query))
			{
				if($challanger_id == $data['player1']) $opponent = $data['player2'];
				if($challanger_id == $data['player2']) $opponent = $data['player1'];
				$sql2 = "SELECT `actived` FROM ".DBPREFIX."lea_challanges WHERE `player1` = '".$opponent."' OR `player2` = '".$opponent."'";
				$query2 = mysql_query($sql2);
				if(mysql_num_rows($query) > 0)
				{
					$i = 0;
					$t = 1;
					while($test = mysql_fetch_assoc($query2))
					{
						if($test['actived'] == 1) $i = $i+1;
						if($i == $date) { $t = 0; break 1; }
					}
				}
				if( $t == 1 )
				{
					$string.= "          <option value='".$data['id']."'>".db_get_data("gamenick","users","id",$challanger_id)." vs ".db_get_data("gamenick","users","id",$opponent)."</option>\n";
					$return = 1;
				}
			}
		}
		$string.= "</select>\n";
		$string.= "      </form>\n";
	}
	if($return == FALSE) $string = "<span class='text-tiny'>Brak mo¿liwo¶ci wyzwañ do aktywacji</span>";
	return $string;
}

function challange_get_opponentid($challanger_id,$id)
{
	$sql = "SELECT * FROM ".DBPREFIX."lea_challanges WHERE `id` = '".$id."' LIMIT 1";
	$query = mysql_query($sql);
	if(mysql_num_rows($query) != FALSE)
	{
		$challange = mysql_fetch_assoc($query);
		if($challange['player1'] == $challanger_id) return $challange['player2'];
		if($challange['player2'] == $challanger_id) return $challange['player1'];
	}
	else return FALSE;
}

function challange_random_nation()
{
	$nation = array("Holandia","Szwajcaria","Austria",
					"Anglia","Prusy","Dania","Szwecja",
					"Piemont","Portugalia","Hiszpania",
					"Francja","Bawaria","Polska","Rosja",
					"Ukraina","Wêgry","Turcja","Algeria",
					"Wenecja");
	return $nation[rand(0,18)];
}

function challange_activation($challanger_id,$id)
{
	if(@$_POST['send'] == "achallange")
	{
		// checking challange existing
		$sql = "SELECT * FROM ".DBPREFIX."lea_challanges WHERE `id` = '".$id."' AND `actived` = '0' LIMIT 1";
		$query = mysql_query($sql);
		if(mysql_num_rows($query) == FALSE) return "<div class='err'>Wyzwanie nie istnieje lub jest ju¿ zaaktywowane</div>\n";
		// checking last game with this player
		else
		{
			$challange = mysql_fetch_assoc($query);
			$sql = "SELECT `date`, `season` FROM ".DBPREFIX."lea_games WHERE (`player1` = '".$challanger_id."' AND `player2` = '".challange_get_opponentid($challanger_id,$id)."') OR (`player1` = '".challange_get_opponentid($challanger_id,$id)."' AND `player2` = '".$challanger_id."') ORDER BY `date` DESC LIMIT 1";
			$query = mysql_query($sql);
			$last = mysql_fetch_assoc($query);
			if($last['date'] >= date("Y-m-d",strtotime("-1 weeks")) AND $last['season'] == $challange['season']) return "<div class='not'>Niedawno gra³e¶ z tym przeciwnikiem w tym sezonie!</div>";
		}
		// update
		$sql = "UPDATE ".DBPREFIX."lea_challanges SET `actived` = '1', `start` = '".DATETIME."', `end` = '".date("Y-m-d H:i:s",strtotime("+1 week"))."', `nations` = '".challange_random_nation()."' WHERE `id` = '".$id."' LIMIT 1";
		mysql_query($sql); return "<div class='not'>Wyzwanie zosta³o dodane poprawnie</div>\n";
	}
}

function game_accepting($challanger_id,$id)
{
	$sql = "SELECT * FROM ".DBPREFIX."lea_challanges WHERE `id` = '".$id."' AND `actived` = '1' LIMIT 1";
	$query = mysql_query($sql);
	if(mysql_num_rows($query) > 0)
	{
		$data = mysql_fetch_assoc($query);
		if($data['accepted'] == 0)
		{
			echo "<td>Ustaw wynik:</td>\n";
			echo "<td colspan='5'><form class='form-style1' action='' method='post'>";
			echo "<input type='submit' class='submit right' value='Zatwierd¼'>";
			echo "<input type='hidden' name='send' value='set'>\n";
			echo "<input type='hidden' name='challange_id' value='".$id."'>\n";
			echo "  <select class='lea-setwinner right' name='winner'>";
			echo "    <option value='".$data['player1']."'>".db_get_data("gamenick","users","id",$data['player1'])."</option>";
			echo "    <option value='".$data['player2']."'>".db_get_data("gamenick","users","id",$data['player2'])."</option>";
			echo "  </select>\n";
			echo "  <select class='lea-setscore right' name='score'>";
			echo "    <option value='1'>2:0</option>";
			echo "    <option value='2'>2:1</option>";
			$test = date("Y-m-d",strtotime("-3 days"));
			echo $test;
			if( $test >= $data['start'] )echo "    <option value='3'>Walkover</option>";
			echo "  </select>\n";
			echo "</form></td>\n";
		}
		if($data['accepted'] == 1)
		{
			echo "<td><b class='red'>WYNIK:</b></td>\n";
			echo "<td>";
			switch($data['score'])
			{
				case 1: echo "2:0";break;
				case 2: echo "2:1";break;
				case 3: echo "WLK";break;
			}
			echo "</td>";
			echo "<td colspan='2'><b class='blue'>".db_get_data("gamenick","users","id",$data['winner'])."</b></td>\n";
			if($data['score_set'] != $challanger_id)
			{
				echo "<td>\n";
				echo "<form class='form-style1' action='' method='post'><input type='hidden' name='send' value='confirm'><input type='hidden' name='challange_id' value='".$data['id']."'><input type='submit' class='submit' value='Tak'></form>\n";
				echo "</td>\n";
				echo "<td>\n";
				echo "<form class='form-style1' action='' method='post'><input type='hidden' name='send' value='cancel'><input type='hidden' name='challange_id' value='".$data['id']."'><input type='submit' class='submit' value='Nie'></form>\n";
				echo "</td>\n";
			}
			elseif($data['score_set'] == $challanger_id)
			{
				echo "<td colspan='2'><i>Czekaj na potwierdzenie</i></td>\n";
			}
			
		}
	}
}

function game_accepting_process($challanger_id,$id)
{
	if(@isset($_POST['send']) AND $_POST['send'] == "set")
	{
		$sql = "UPDATE ".DBPREFIX."lea_challanges SET `accepted` = '1', `winner` = '".$_POST['winner']."', `score` = '".$_POST['score']."', `score_set` = '".$challanger_id."' WHERE `id` = '".$id."' AND `accepted` = '0' LIMIT 1";
		mysql_query($sql);
		switch($_POST['score'])
		{
			case 1: $msg_score = "2:0"; break;
			case 2: $msg_score = "2:1"; break;
			case 3: $msg_score = "WAL"; break;
		}
		$sql = "INSERT INTO ".DBPREFIX."lea_msg VALUES('','Wynik dla ".db_get_data("gamenick","users","id",$_POST['winner'])." : ".$msg_score."','".$challanger_id."','".$id."','".DATETIME."','0');";
		mysql_query($sql);
	}
	if(@isset($_POST['send']) AND $_POST['send'] == "confirm")
	{
		$sql = "SELECT * FROM ".DBPREFIX."lea_challanges WHERE `id` = '".$id."' AND `accepted` = '1' LIMIT 1";
		$query = mysql_query($sql);
		if(mysql_num_rows($query) == 1)
		{
			$new = mysql_fetch_assoc($query);
			$sql = "UPDATE ".DBPREFIX."lea_challanges SET `actived` = '2', `accepted` = '2' WHERE `id` = '".$id."' AND `accepted` = '1' LIMIT 1";
			mysql_query($sql);
			$sql = "INSERT INTO ".DBPREFIX."lea_games VALUES('".$id."','".$new['player1']."','".$new['player2']."','".$new['season']."','".$new['nations']."','".$new['winner']."','".$new['score']."','".$challanger_id."','".DATETIME."','0');";
			mysql_query($sql);
			$sql = "INSERT INTO ".DBPREFIX."lea_msg VALUES('','Potwierdzono','".$challanger_id."','".$id."','".DATETIME."','0');";
			mysql_query($sql);
			return "<div class='suc'>Dodano wynik poprawnie</div>\n";
		}
	}
	if(@isset($_POST['send']) AND $_POST['send'] == "cancel")
	{
		
		$sql = "UPDATE ".DBPREFIX."lea_challanges SET `accepted` = '0', `winner` = '0', `score` = '0', `score_set` = '0' WHERE `id` = '".$id."' AND `accepted` = '1' LIMIT 1";
		mysql_query($sql);
	}
}

function lea_clear_stats($clear_type = 1)
{
	switch($clear_type)
	{
		case 1:$sql = "UPDATE `lea_players` SET `score` = '0', `score_stats` = '0', `wins` = '0', `lost` = '0'";break;
	}
	mysql_query($sql);
}

function lea_games_reset_scored($clear_type = 1)
{
	switch($clear_type)
	{
		case 1:$sql = "UPDATE `lea_games` SET `scored` = '0'";break;
	}
	mysql_query($sql);
}

function lea_create_challanges($season)
{
	$i = 0; $j = 0;
	
	$sql1 = "SELECT * FROM ".DBPREFIX."lea_players WHERE `active` = '1' ";
	$query1 = mysql_query($sql1);
	if(mysql_num_rows($query1) > 0)
	{
		while($player = mysql_fetch_assoc($query1))
		{
			$sql2 = "SELECT * FROM ".DBPREFIX."lea_players WHERE `active` = '1' AND `user_id` != '".$player['user_id']."'";
			$query2 = mysql_query($sql2);
			if(mysql_num_rows($query2) > 0)
			{
				while($opponent = mysql_fetch_assoc($query2))
				{			
					$sql_test = "SELECT `id` FROM ".DBPREFIX."lea_challanges WHERE (`player1` = '".$player['user_id']."' AND `player2` = '".$opponent['user_id']."') AND `season` = '".$season."' LIMIT 1";
					$query_test = mysql_query($sql_test);
					if(mysql_num_rows($query_test) == 0)
					{
						$sql = "INSERT INTO ".DBPREFIX."lea_challanges VALUES('','".$player['user_id']."','".$opponent['user_id']."','".$season."','0','Losowo','".DATETIME."','".date("Y-m-d H:i:s",strtotime("+10 days"))."','0','0','0','0');";
						mysql_query($sql);
						$j = $j + 1;
					}
					else $i = $i + 1;
				}
			}	
		}
	}
	return "<div class='not'><p>Dodano ".$j." wyzwañ</p><p>Istnieje ".$i." wyzwañ których nie by³o mo¿liwo¶ci dodaæ</p></div>";
}
?>
