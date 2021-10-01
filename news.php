<?php
require_once("maincore.php");
$actual = "news.php";
add_title("Nowo¶ci");
require_once(BASEDIR.STYLE."tpl.start.php");
if(@$_GET['action'] != "read" AND empty($_GET['mode']))
{
	// fixing actived and archived amount of news
	
	$sql = "SELECT * FROM ".DBPREFIX."news WHERE `date_start` <= '".DATETIME."'";
	$query = mysql_query($sql);
	$amount = mysql_num_rows($query);
	
	// fixing top news id
	
	if( @check_int($_GET['start']) == FALSE OR @empty($_GET['start']) OR @$_GET['start'] < 0 ) $start = 0;
	else $start = $_GET['start'];
	
	// fixing next and prev ids of pages of news
	
	$next=$start+5;
	$prev=$start-5;
	if($prev<0)$prev=0;
	
	// initiation
	
	$sql = "SELECT * FROM `".DBPREFIX."news` WHERE `date_start` <= '".DATETIME."' AND `date_end` >= '".DATETIME."' ORDER BY `date_start` DESC LIMIT $start , 5";
	$query = mysql_query($sql);
	if(mysql_num_rows($query) > 0)
	{
		while($news = mysql_fetch_assoc($query))
		{
			$lang = explode(";",$news['languages']);
			$author = db_get_data("login","users","id",$news['author']);
			if(!empty($news['text_ext']))$ext = " | <a href='news.php?action=read&id=".$news['id']."'>Czytaj wiêcej</a>"; else $ext = "&nbsp;";
			if(empty($news['text_ext'])) 
			{
	    		if( $news['allow_rating'] == 1 OR $news['allow_comment'] == 1 )$ext = " | <a href='news.php?action=read&id=".$news['id']."'>Skomentuj / Oceñ</a>";
	  		}
	  		$date = @split("[ :.]",$news['date_start']);
	  		if (@$date[3] == 00 AND @$date[4] == 00 AND @$date[5] == 00) $news['date_start'] = $date[0];
	  		
	  		// printing
	  		
	  		echo "<div class='post'>\n";
			echo "<div class='post-title'><h2>".$news['title']."</h2></div>\n";
			echo "<div class='post-info'>Napisany przez ".$author." ".$news['date_start']."</div>\n";
			echo "<div class='post-content'>".Codes($news['text'])."</div>\n";
			
				$sql2 = "SELECT * FROM `".DBPREFIX."comments` WHERE `item_id` = '".$news['id']."' AND `item_type` = 'N'";
				$query2 = mysql_query($sql2);
				$i = mysql_num_rows($query2);
				if(!isset($i)) $i = 0;
				$sql2 = "SELECT * FROM `".DBPREFIX."ratings` WHERE `item_id` = '".$news['id']."' AND `item_type` = 'N'";
				$query2 = mysql_query($sql2);
				$rated = 0;
				$r = 0;
				if(mysql_num_rows($query2) > 0)
				{
		    		while($vote = mysql_fetch_assoc($query2))
		    		{
	       			$rated = $rated+$vote['vote'];
	       			$r++;
					}
		  		}
		  		$rated = @round($rated/$r,2); if($rated == 0) $rated = "Brak ocen";
		  		$rated = " | Ocena: ".$rated. " (".$r.")";
		  		
			echo "<div class='post-footer text-tiny'>".$i." komentarzy".$rated." ".$ext."</div>\n";
			echo "</div>\n";
			
			// fixing var~s
			
			unset($ext); unset($r); unset($i); unset($rated);
		}
	}
	
	// printing news navigation
	
	$sql = "SELECT * FROM `".DBPREFIX."news` WHERE `date_end` < '".DATETIME."'";
	if($amount>$next OR $start>$prev OR mysql_num_rows(mysql_query($sql)) > 0)
	{
		echo "<div class='post-footer text-tiny' style='text-align:center;'>";
		if($start>$amount) echo "<a href='news.php'>[&#171;&#171;]</a>";
		if($prev<$start) echo "<a href='news.php?start=$prev'>[&#171;]</a>";
		
		$pages = 0;
		while($pages*5<$amount)
		{
			$pages++;
			$page_=$pages*5-5;
			$start_n=$start+15;
			$start_p=$start-15;
			if($page_ > $start_p AND $page_ < $start_n)
			{
				if($start == $page_)
				{  echo "[".$pages."] ";  }
				else {  echo "<a href='news.php?start=".$page_."'>[".$pages."]</a> ";  }
			}
		}
		if($amount>$next)
			echo "<a href='news.php?start=$next'>[&#187;]</a>";
		// if ( mysql_num_rows(mysql_query($sql)) > 0 ) echo "  <a href='news.php?mode=archive'>[Archiwum]</a>";
		echo "</div>\n";
	}
}
if(@$_GET['action'] == "read")
{
	if(check_int($_GET['id']) == TRUE)
	{

		// add vote & comment

		if( @$_POST['send'] == "vote" )
		{
			$sql = "SELECT * FROM `".DBPREFIX."ratings` WHERE `item_id` = '".$_GET['id']."' AND `item_type` = 'N' AND `user` = '".@db_get_data("id","users","login",$_SESSION['login'])."' LIMIT 1";
			$query = mysql_query($sql);
			if(mysql_num_rows($query)) { $result = "Ju¿ ocenia³e¶ ten artyku³"; }
			else
			{
				$sql = "INSERT INTO `".DBPREFIX."ratings` VALUES(NULL, '".$_GET['id']."', 'N', '".$_POST['vote']."', '".@db_get_data("id","users","login",$_SESSION['login'])."', '".DATETIME."', '".USER_IP."');";
				mysql_query($sql);
			}
		}
		if( @$_POST['send'] == "comment" )
		{
			$sql = "SELECT * FROM `".DBPREFIX."comments` WHERE `item_id` = '".$_GET['id']."' AND `item_type` = 'N' AND `user` = '".@db_get_data("id","users","login",$_SESSION['login'])."' ORDER BY `date` DESC LIMIT 0, 1";
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
				$sql = "INSERT INTO `".DBPREFIX."comments` VALUES(NULL,'".$_GET['id']."', 'N', '".$msg."', '".@db_get_data("id","users","login",$_SESSION['login'])."', '".DATETIME."', '".USER_IP."');";
				mysql_query($sql);
			}
		}

		// printing news

		$sql = "SELECT * FROM ".DBPREFIX."news WHERE `id` = ".$_GET['id']." LIMIT 1";
		$query = mysql_query($sql);
		if(mysql_num_rows($query) > 0)
		{
			while($news = mysql_fetch_assoc($query))
			{
				$lang = explode(";",$news['languages']);
				$author = @db_get_data("login","users","id",$news['author']);
				if(!empty($news['text_ext']))$ext = " | <a href='news.php?action=read&id=".$news['id']."'>Czytaj wiêcej</a>"; else $ext = "";
				$date = @split("[ :.]",$news['date_start']);
				if (@$date[3] == 00 AND @$date[4] == 00 AND @$date[5] == 00) $news['date_start'] = $date[0];
				
				
				
				echo "<div class='post'>\n";
				echo "<div class='post-title'><h2>".$news['title']."</h2></div>\n";
				echo "<div class='post-info'>Napisany przez ".$author." ".$news['date_start']."</div>\n";
				echo "<div class='post-content'>".Codes($news['text'])."<br/>".Codes($news['text_ext'])."</div>\n";
				
				if( $news['allow_comment'] == 1 OR $news['allow_rating'] == 1 )
				{
					echo "<div class='post-footer text-tiny'>";
					
					// :: fixing count of comments & rates
					
					$sql2 = "SELECT * FROM `".DBPREFIX."comments` WHERE `item_id` = '".$news['id']."' AND `item_type` = 'N' ORDER BY `date` DESC";
					$query2 = mysql_query($sql2);
					$i = mysql_num_rows($query2);
					
					$sql2 = "SELECT * FROM `".DBPREFIX."ratings` WHERE `item_id` = '".$news['id']."' AND `item_type` = 'N'";
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
					
					if( $news['allow_comment'] == 1 )echo $i." komentarzy";
					if( $news['allow_comment'] == 1 AND $news['allow_rating'] == 1 )echo " | ";
					if( $news['allow_rating'] == 1 )
					{
						if( $rated == FALSE AND mysql_num_rows($query2) == 0 ) echo "Brak ocen";
						else echo "Ocena: ".$rated." (".$r.")";
					}
					echo " | <a href='news.php'>Powrót</a></div>\n";
					
					// comments
			
					if( $news['allow_comment'] == 1 )
					{
						if( session_check() == TRUE )
						{
			 				echo "<a name='comment'></a><form class='form-style1' action='' method='post'>\n";
							echo "<input type='text' name='comment' class='w50' value=''/>\n";
							echo "<input type='hidden' name='send' value='comment'/><input type='submit' class='submit' value='Skomentuj'/>\n";
							echo "</form>\n";
				 		}
				 		else echo "<div class='comment'><div class='comment-head'>Zaloguj siê aby móc komentowaæ</div></div>";
					}
					if( $news['allow_rating'] == 1 )
					{
			    			if( session_check() == TRUE )
			    			{
			      				echo form_rating();
			  			}
			  			else echo "<div class='comment'><div class='comment-head'>Zaloguj siê aby móc oceniæ artyku³</div></div>";
			  		}
					
					// printing list of comments
	  		
	  				if(isset($result))echo "<div class='err'>".$result."</div>\n";
			  		
			  		$sql2 = "SELECT * FROM `".DBPREFIX."comments` WHERE `item_id` = '".$_GET['id']."' AND `item_type` = 'N' ORDER BY `date` DESC";
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
				
				echo "</div>\n";
				
				unset($r); unset($i); unset($rated);
			}
		}
		else
		{
    			echo "<div class='err'>";
			echo "Nie znaleziono wybranej wiadomo¶ci, <a href='news.php'>spróbuj ponownie</a>";
			echo "</div>\n";
  		}
	}
}
require_once(BASEDIR.STYLE."tpl.end.php");
?>
