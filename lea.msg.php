<?php
require_once("maincore.php");
require_once(BASEDIR.STYLE."tpl.start.php");
require_once("lea.core.php");
score_games();

if( session_check() == TRUE AND TRUE == db_get_data("active","lea_players","user_id",$_SESSION['userid']) AND check_int($_GET['id']) == TRUE )
{
	if(@$_POST['send'] == "msg")
	{
		$msg = addslashes(htmlspecialchars($_POST['msg']));
		$sql = "INSERT INTO ".DBPREFIX."lea_msg VALUES('','".$msg."','".@db_get_data("id","users","login",$_SESSION['login'])."','".$_GET['id']."','".DATETIME."','".USER_IP."');";
		mysql_query($sql);
		unset($msg,$sql);
	}
	
	$sql1 = "SELECT * FROM ".DBPREFIX."lea_msg WHERE `challange_id` = '".$_GET['id']."' ORDER BY `date` ASC";
	$query1 = mysql_query($sql1);
	
	$sql2 = "SELECT * FROM ".DBPREFIX."lea_challanges WHERE `id` = '".$_GET['id']."' LIMIT 1";
	$query2 = mysql_query($sql2);
	$game = mysql_fetch_assoc($query2);
	
	echo "<table class='table0 w100'>\n";
	echo "  <tr><th colspan='3'>".db_get_data("gamenick","users","id",$game['player1'])." vs ".db_get_data("gamenick","users","id",$game['player2'])."</th></tr>\n";
	echo "  <tr><td class='center'><h6>".substr($game['start'],0,16)."</h6></td><td class='center'><h6>".$game['nations']."</h6></td><td class='center'><h6>".substr($game['end'],0,16)."</h6></td></tr>\n";
	echo "  <tr><td colspan='3' class='center'><h6><a href='lea.system.php'>Powrót</a></h6></td></tr>\n";
	if(mysql_num_rows($query1) > 0)
	{
		$i = 0;
		while($msg = mysql_fetch_assoc($query1))
		{
			$i = $i+1; $class = "";
			if($i % 2 == 0) $class = " class='row2'";
			echo "    <tr".$class."><td><b>".db_get_data("gamenick","users","id",$msg['author'])."</b></td><td></td><td class='right'>".substr($msg['date'],0,16)."</td></tr>\n";
			echo "    <tr".$class."><td colspan='3'>".Codes(stripslashes($msg['message']))."</td></tr>\n";
 		}
	}
	echo "</table>\n";
	if(($game['accepted'] == 2) AND (@db_get_data("id","users","login",$_SESSION['login']) == $game['player1'] OR @db_get_data("id","users","login",$_SESSION['login']) == $game['player2'] OR session_check_usrlevel() >= SPECIAL_LVL))
	{
		echo "<br>\n";
		echo "<table class='table0 w100 form-style1'>\n";
		echo "  <tr>\n";
		echo "    <form action='' method='post'>\n";
		echo "      <td class='w100'><input type='text' name='msg' class='text'></td><td><input type='hidden' name='send' value='msg'><input type='submit' class='submit' value='OK'></td>\n";
		echo "    </form>\n";
		echo "  </tr>\n";	
		echo "</table>\n";
	}
}
else
{
	echo "<div class='not'>Zaloguj siê</div>\n";
}
require_once(BASEDIR.STYLE."tpl.end.php");
?>
