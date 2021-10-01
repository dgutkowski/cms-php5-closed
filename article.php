<?php
require_once "maincore.php";
if(!empty($_GET['id']) AND check_int($_GET['id']) == TRUE) $article = mysql_fetch_assoc(mysql_query("SELECT `title` FROM `".DBPREFIX."articles` WHERE `id` = '".$_GET['id']."' LIMIT 1"));
add_title(stripslashes($article['title']));
require_once (BASEDIR.STYLE."tpl.start.php");

if( !empty($_GET['id']) AND check_int($_GET['id']) == TRUE )
{

	// add vote & comment

	if( @$_POST['send'] == "vote" )
	{
		$sql = "SELECT * FROM `".DBPREFIX."ratings` WHERE `item_id` = '".$_GET['id']."' AND `item_type` = 'A' AND `user` = '".@db_get_data("id","users","login",$_SESSION['login'])."' LIMIT 1";
		$query = mysql_query($sql);
		if(mysql_num_rows($query)) { $result = "Ju¿ ocenia³e¶ ten artyku³"; }
		else
		{
			$sql = "INSERT INTO `".DBPREFIX."ratings` VALUES(NULL, '".$_GET['id']."', 'A', '".$_POST['vote']."', '".@db_get_data("id","users","login",$_SESSION['login'])."', '".DATETIME."', '".USER_IP."');";
			mysql_query($sql);
		}
	}
	if( @$_POST['send'] == "comment" )
	{
		$sql = "SELECT * FROM `".DBPREFIX."comments` WHERE `item_id` = '".$_GET['id']."' AND `item_type` = 'A' AND `user` = '".@db_get_data("id","users","login",$_SESSION['login'])."' ORDER BY `date` DESC LIMIT 0, 1";
		$query = mysql_query($sql);
		$t = TRUE;
		while( $test = mysql_fetch_assoc($query) )
		{
			if( get_time_difference($test['date']) < $config['antyflood'] )
			{
				$t = FALSE;
				$result = "Musisz odczekaæ ".$config['antyflood']." sekund od ostatniego komentarza";
			}
		}
		if( $t == TRUE )
		{
			$msg = addslashes(htmlspecialchars($_POST['comment']));
			$sql = "INSERT INTO `".DBPREFIX."comments` VALUES(NULL,'".$_GET['id']."', 'A', '".$msg."', '".@db_get_data("id","users","login",$_SESSION['login'])."', '".DATETIME."', '".USER_IP."');";
			mysql_query($sql);
		}
	}
	
	// fixing choosed article

	$sql = "SELECT * FROM `".DBPREFIX."articles` WHERE `id` = '".$_GET['id']."' LIMIT 1";
	$query = mysql_query($sql);	
	if(mysql_num_rows($query) == 1)
	{
		$article = mysql_fetch_assoc($query);
		$author = @db_get_data("login","users","id",$article['author']);
		$date = @split("[ :.]",$article['date_start']);
		if (@$date[3] == 00 AND @$date[4] == 00 AND @$date[5] == 00) $article['date_start'] = $date[0];
		if(empty($author)) $author = "?";
		
		// printing an article
		
		echo "<div class='post-title'><h2>".stripslashes($article['title'])."</h2></div>\n";
		if( $article['allow_comment'] == 1 OR $article['allow_rating'] == 1 OR 1 == 1 )
		{
			echo "<div class='post-info'>Napisany przez ".$author." ".$article['date_start']."</div>\n";
		}
		echo "<div class='post-content'>\n".emots(stripslashes($article['content']))."\n</div>\n";
		if( $article['allow_comment'] == 1 OR $article['allow_rating'] == 1 )
		{
			echo "<div class='post-footer text-tiny'>";
			
			// :: fixing count of comments & rates
			
			$sql2 = "SELECT * FROM `".DBPREFIX."comments` WHERE `item_id` = '".$article['id']."' AND `item_type` = 'A' ORDER BY `date` DESC";
			$query2 = mysql_query($sql2);
			$i = mysql_num_rows($query2);
			
			$sql2 = "SELECT * FROM `".DBPREFIX."ratings` WHERE `item_id` = '".$article['id']."' AND `item_type` = 'A'";
			$query2 = mysql_query($sql2);
			$rated = 0;
			$r = 0;
			
			// :: fixing summary for rating
			
			if(mysql_num_rows($query2) > 0)
			{
				while($vote = mysql_fetch_assoc($query2))
				{
					$rated = $rated+$vote['vote'];
					$r++;
				}
			}
			$rated = @round($rated/$r,2);
			
			if( $article['allow_comment'] == 1 )echo $i." komentarzy";
			if( $article['allow_comment'] == 1 AND $article['allow_rating'] == 1 )echo " | ";
			if( $article['allow_rating'] == 1 )
			{
				if( $rated == FALSE AND mysql_num_rows($query2) == 0 ) echo "Brak ocen";
				else echo "Ocena: ".$rated." (".$r.")";
			}
			echo "<span class='right'>Czytano wcze¶niej ".$article['reads']." razy</span>";
			echo "</div>\n";
			
			// comments
			
			if( $article['allow_comment'] == 1 )
			{
				if( session_check() == TRUE )
				{
	 				echo "<a name='comment'></a><form class='form-style1' action='' method='post'>\n";
					echo "<input type='text' name='comment' value='' class='w50'/>\n";
					echo "<input type='hidden' name='send' value='comment'/><input type='submit' class='submit' value='Skomentuj'/>\n";
					echo "</form>\n";
		 		}
		 		else echo "<div class='comment'><div class='comment-head'>Zaloguj siê aby móc komentowaæ</div></div>";
			}
			if( $article['allow_rating'] == 1 )
			{
	    			if( session_check() == TRUE )
	    			{
	      				echo form_rating();
	  			}
	  			else echo "<div class='comment'><div class='comment-head'>Zaloguj siê aby móc oceniæ artyku³</div></div>";
	  		}
	  		
	  		// printing list of comments
	  		
	  		if(isset($result))echo "<div class='err'>".$result."</div>\n";
	  		
	  		$sql2 = "SELECT * FROM `".DBPREFIX."comments` WHERE `item_id` = '".$_GET['id']."' AND `item_type` = 'A' ORDER BY `date` DESC";
			$query2 = mysql_query($sql2);
			if(mysql_num_rows($query2) > 0)
			{
				while($comment = mysql_fetch_assoc($query2))
				{
					if( empty($comment['ip']) ) $comment['ip'] = "B³±d! Nie znaleziono IP wpisu";
					elseif( session_check_usrlevel() >= SPECIAL_LVL ) $adm_info = "<span class='right'><img src='images/icons/info.png' width='12' height='12' title='".$comment['ip']."'/></span>";
					echo "<div class='comment'>\n";
					echo "<div class='comment-head'>".$comment['date']." skomentowany przez <strong>".@db_get_data("login","users","id",$comment['user'])."</strong>".@$adm_info."</div>\n";
					echo "<div class='comment-text'>".Codes($comment['message'])."</div>\n";
					echo "</div>\n";
				}
			}
  		}
	}
	else
	{
		echo "<div class='err'>";
		echo "Nie znaleziono wybranego artyku³u. Wróæ na <a href='index.php'>stronê g³ówn±</a>";
		echo "</div>\n";
	}
	
	$areads = $article['reads']+1;
	$sql = "UPDATE `".DBPREFIX."articles` SET `reads` = '".$areads."' WHERE `id` = ".$_GET['id']." LIMIT 1";
	mysql_query($sql);
}

require_once (BASEDIR.STYLE."tpl.end.php");
?>
