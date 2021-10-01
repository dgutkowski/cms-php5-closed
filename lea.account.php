<?php
require_once("maincore.php");
require_once(BASEDIR.STYLE."tpl.start.php");
require_once("lea.core.php");
score_games();

if( session_check() == TRUE AND TRUE == db_get_data("active","lea_players","user_id",$_SESSION['userid']) AND lea_free_date() == FALSE )
{
	$player_id = $_SESSION['userid'];
	if(isset($_POST['send']))
	{
		echo challange_activation($player_id,$_POST['challange_id']);
		echo game_accepting_process($player_id,$_POST['challange_id']);
	}
	

	//lea_clear_stats();
	//lea_games_reset_scored();
	lea_create_challanges(SEASON);
	
	// echo mysql_num_rows(mysql_query("SELECT * FROM ".DBPREFIX."lea_challanges WHERE `season` = '".SEASON."'"));
	
	echo "<table class='table0 w100'>\n";
	echo "  <tr><th colspan='6'>Panel gracza ligi 0</th></tr>\n";
	echo "  <tr><td colspan='6'><h6>Lista aktualnych wyzwañ</h6></td></tr>\n";
	$i = 0;
	$sql = "SELECT * FROM `lea_challanges` WHERE (`player1` = '".$player_id."' OR `player2` = '".$player_id."') AND `actived` = '1' AND `season` = '".SEASON."'";
	$query = mysql_query($sql);
	while( $lista = mysql_fetch_assoc($query) )
	{
		$i = $i + 1;
		$second_row = "";
		if( $i%2 == 0 ) $second_row = " class='row2'";
		echo "        <!-- ".$i." -->\n";
		echo "    <tr".$second_row.">";
		echo "  <td width='120'>".db_get_data("login","users","id",$lista['player1'])."</td>";
		echo "  <td width='120'>".db_get_data("login","users","id",$lista['player2'])."</td>";
		echo "  <td>".substr($lista['nations'],0,9)."</td>";
		echo "  <td width='80'>".substr($lista['start'],0,10)."</td>";
		echo "  <td width='80'>".substr($lista['end'],0,10)."</td>";
		echo "  <td class='lea-challangepw'><a href='lea.msg.php?id=".$lista['id']."'>PW</a></td>";
		echo "    </tr>\n";
		echo "    <tr".$second_row.">";
		game_accepting($player_id,$lista['id']);
		echo "</tr>\n";
	}
	echo "</table>\n\n";
	
		
	echo "<br>\n";
	
	echo "<table class='table0 w100'>\n";
	echo "  <tr><td><h6>Zaaktywuj grê</h6></td></tr>\n";
	echo "  <tr>\n    <td>\n";
	echo print_list_of_opponents($player_id,2);
	echo "    </td>\n  </tr>\n";
	echo "</table>\n\n";
	
	echo "<br>\n";
	echo "<table class='table0 w100'>\n";
	echo "  <tr><td colspan='3'><h6>Lista gier do rozegrania na bie¿±cy sezon</h6></td></tr>\n";
	$i = 0;
	$sql = "SELECT * FROM `lea_challanges` WHERE `player1` = '".$player_id."' AND `actived` = '0'";
	$query = mysql_query($sql);
	while( $lista = mysql_fetch_assoc($query) )
	{
		$i = $i + 1;
		$second_row = "";
		if( $i%2 == 0 ) $second_row = " class='row2'";
		echo "        <!-- ".$i." -->\n";
		echo "    <tr".$second_row.">";
		echo "<td class='center w50'>".db_get_data("gamenick","users","id",$lista['player1'])."</td>";
		echo "<td class='center p50'> vs </td>";
		echo "<td class='center w50'>".db_get_data("gamenick","users","id",$lista['player2'])."</td>";
		echo "</tr>\n";
	}
	$sql = "SELECT * FROM `lea_challanges` WHERE `player2` = '".$player_id."' AND `actived` = '0'";
	$query = mysql_query($sql);
	while( $lista = mysql_fetch_assoc($query) )
	{
		$i = $i + 1;
		$second_row = "";
		if( $i%2 == 0 ) $second_row = " class='row2'";
		echo "        <!-- ".$i." -->\n";
		echo "    <tr".$second_row.">";
		echo "<td class='center'>".db_get_data("gamenick","users","id",$lista['player1'])."</td>";
		echo "<td class='center'> vs </td>";
		echo "<td class='center'>".db_get_data("gamenick","users","id",$lista['player2'])."</td>";
		echo "</tr>\n";
	}
	echo "</table>\n\n";
	
	echo "<br>\n";
	
	echo "<table class='table0 w100'>\n";
	echo "  <tr><td colspan='4'><h6>Lista meczy rozegranych</h6></td></tr>\n";
	$i = 0;
	$sql = "SELECT * FROM `lea_games` WHERE `player1` = '".$player_id."'";
	$query = mysql_query($sql);
	while( $lista = mysql_fetch_assoc($query) )
	{
		$i = $i + 1;
		$second_row = "";
		if( $i%2 == 0 ) $second_row = " class='row2'";
		echo "        <!-- ".$i." -->\n";
		echo "    <tr".$second_row.">";
		echo "<td class='right'>".db_get_data("login","users","id",$lista['winner'])."</td>";
		echo "<td class='center'>";
		switch($lista['score'])
		{
		case 1: echo "2:0"; break;
		case 2: echo "2:1"; break;
		}
		if($lista['player1'] == $lista['winner']) echo "<td class='left'>".db_get_data("login","users","id",$lista['player2'])."</td>";
		elseif($lista['player2'] == $lista['winner']) echo "<td class='left'>".db_get_data("login","users","id",$lista['player1'])."</td>";
		echo "</td>";
		echo "<td width='80'>".substr($lista['date'],0,10)."</td>";
		echo "</tr>\n";
	}
	$sql = "SELECT * FROM `lea_games` WHERE `player2` = '".$player_id."'";
	$query = mysql_query($sql);
	while( $lista = mysql_fetch_assoc($query) )
	{
		$i = $i + 1;
		$second_row = "";
		if( $i%2 == 0 ) $second_row = " class='row2'";
		echo "        <!-- ".$i." -->\n";
		echo "    <tr".$second_row.">";
		echo "<td class='right'>".db_get_data("login","users","id",$lista['winner'])."</td>";
		echo "<td class='center'>";
		switch($lista['score'])
		{
		case 1: echo "2:0"; break;
		case 2: echo "2:1"; break;
		}
		if($lista['player1'] == $lista['winner']) echo "<td class='left'>".db_get_data("login","users","id",$lista['player2'])."</td>";
		elseif($lista['player2'] == $lista['winner']) echo "<td class='left'>".db_get_data("login","users","id",$lista['player1'])."</td>";
		echo "</td>";
		echo "<td width='80'>".substr($lista['date'],0,10)."</td>";
		echo "</tr>\n";
	}
	echo "</table>\n\n";
}
elseif( session_check() == TRUE AND lea_free_date() == FALSE )
{
	echo "<div class='not'>";
	if(@$_POST['sendlea'] == 'register')
	{
		echo lea_application_process();
	}
	else echo "Nie jeste¶ zapisany w lidze<br>Mo¿esz siê wpisaæ do ligi poprzez poni¿szy formularz";
	echo "</div>\n";
	echo lea_application();
}
elseif( lea_free_date() == TRUE )
{
	echo "<div class='not'>Dzi¶ liga ma wolne</div>\n";
}
else
{
	echo "<div class='not'>Zaloguj siê, je¿eli jeste¶ zapisany w lidze</div>\n";
}
require_once(BASEDIR.STYLE."tpl.end.php");
?>
