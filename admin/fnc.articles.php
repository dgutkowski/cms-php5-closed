<?php

if( $_GET['option'] == "articles" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	// fixing user id

	session_check();
	
	// fixing last item id
	
	$sql = "SELECT `id` FROM `".DBPREFIX."articles` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];
	
	// fixing off
	
	// article:send new one
	
	if(@$_POST['send'] == "new")
	{
		$new['id'] = $last_id+1;
   		$new['title'] = htmlspecialchars(addslashes($_POST['form1']));
		$new['content'] = addslashes($_POST['form2']);
		$new['author'] = htmlspecialchars($_SESSION['userid']);
		$new['date_start'] = $_POST['ys']."-".$_POST['ms']."-".$_POST['ds']." ".$_POST['hs'].":".$_POST['is'].":".$_POST['ss'];
		@$new['comment'] = $_POST['form3'];
		@$new['rating'] = $_POST['form4'];
		
		if( empty($_POST['form1']) OR empty($_POST['form2']) ) $result = "Nie podano tytu³u lub podstawowej tre¶ci artyku³u";
		else
		{
			$sql = "INSERT INTO `".DBPREFIX."articles` VALUES('".$new['id']."','".$new['title']."','".$new['content']."','".$new['author']."','".$new['date_start']."','0','".$new['comment']."','".$new['rating']."');";
			mysql_query($sql);
			$result = "Artyku³ zosta³ dodany";
		}
	}
	
	// article:choosed to edit
	
	if( @$_POST['send'] == "edit" AND @check_int($_POST['choosed_id']) == TRUE )
	{
   	$sql = "SELECT * FROM `".DBPREFIX."articles` WHERE `id` = '".$_POST['choosed_id']."' LIMIT 1";
   	$query = mysql_query($sql);
   	if( mysql_num_rows($query) > 0 )
   	{
   	   $data = mysql_fetch_assoc($query);
   	   
    		$edit['id'] = $_POST['choosed_id'];
    		$edit['title'] = stripslashes($data['title']);
    		$edit['content'] = stripslashes($data['content']);
    		@$edit['comment'] = $data['allow_comment'];
    		@$edit['rating'] = $data['allow_rating'];
    		
    		$ys = substr($data['date_start'],0,4); $ms = substr($data['date_start'],5,2);
    		$ds = substr($data['date_start'],8,2); $hs = substr($data['date_start'],11,4);
  			$is = substr($data['date_start'],14,2); $ss = substr($data['date_start'],17,2);	
 		}
		$send_new = "update";
		$action = "Edycja";
		$result = "Wczytano poprawnie";
	}
	
	// article:update
	
	if( @$_POST['send'] == "update" )
	{
		if( @$_POST['delete'] == 1 AND check_int($_POST['edited_id']) == TRUE )
		{
			$sql = "DELETE FROM `".DBPREFIX."articles` WHERE `id` = '".$_POST['edited_id']."' LIMIT 1";
    		$result = "Artyku³ zosta³ usuniêty";
 		}
 		elseif( check_int($_POST['edited_id']) == TRUE )
 		{
			$upt['title'] = htmlspecialchars(addslashes($_POST['form1']));
			$upt['content'] = addslashes($_POST['form2']);
			$upt['date_start'] = $_POST['ys']."-".$_POST['ms']."-".$_POST['ds']." ".$_POST['hs'].":".$_POST['is'].":".$_POST['ss'];
			@$upt['comment'] = $_POST['form3'];
			@$upt['rating'] = $_POST['form4'];
			if( empty($_POST['form1']) OR empty($_POST['form2']) ) $result = "Nie podano tytu³u lub podstawowej tre¶ci artyku³u";
			else
			{
   				$sql = "UPDATE `".DBPREFIX."articles` SET `title` = '".$upt['title']."', `content` = '".$upt['content']."', `date_start` = '".$upt['date_start']."', `allow_comment` = '".$upt['comment']."', `allow_rating` = '".$upt['rating']."' WHERE `id` = '".$_POST['edited_id']."'";
   				$result = "Artyku³ zosta³ zaaktualizowany";
			}
		}
		mysql_query($sql);
	}
	
	// fixing date-time
	
	$ys = date("Y"); $ms = date("m"); $ds = date("d");
	$hs = "00"; $is = "00"; $ss = "00";
	
	if(empty($_POST['send'])) $_POST['send'] = "new";
	
	// form
	
	if(!empty($result))
	{
		echo "<div class='suc'>".$result."</div>\n";
	}
	echo "<table class='table0 w100 form-style1'>\n";
	echo "<tr><th colspan='2'>Zarz±dzanie artyku³ami</th></h2>\n";
	echo "<tr>\n<td colspan='2' align='left'><h6>"; if(isset($edit['id'])) echo "Edytujesz: <i>article.php?id=".$edit['id']."</i>"; else echo "Nowy"; echo "<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td>\n</tr>\n";
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td width='150'>Data ukazania siê</td><td>";
	echo form_select_date("ys",2005,2090,"Y",60,$ys);
	echo form_select_date("ms",1,12,"m",40,$ms);
	echo form_select_date("ds",1,31,"d",40,$ds);
	echo " : ";
	echo form_select_date("hs",0,23,"H",40,$hs);
	echo form_select_date("is",0,59,"i",40,$is);
	echo form_select_date("ss",0,59,"s",40,$ss);
	echo "</td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Tytu³</td><td><input class='text' type='text' name='form1' value='".@$edit['title']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td valign='top'>Zawarto¶æ</td><td><textarea class='textarea' name='form2'>".@$edit['content']."</textarea></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Zezwól na komentarze</td><td><input type=checkbox name='form3'"; if( @$edit['comment'] == 1 ) echo " checked='checked'"; echo " value='1'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Zezwól na ocenianie</td><td><input type=checkbox name='form4'"; if( @$edit['rating'] == 1 ) echo " checked='checked'"; echo " value='1'/></td>";
	echo "</tr>\n";
	if( @$_POST['send'] == "edit" )
  	{
   		echo "<tr>";
		echo "<td>Usuñ</td><td><input type=checkbox name='delete' value='1' /></td>";
 		echo "</tr>\n";
	}
	echo "<tr>";
	echo "<td align='right'>HTML</td><td><img src='images/icons/replies/yes.png'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>";
	if( @$_POST['send'] == "new" OR @$_POST['send'] == "update" ) { echo "<input type='hidden' name='author' value='".$_SESSION['userid']."'/>"; $send_type = "new"; }
	if( @!empty($_POST['send']) && @$_POST['send'] == "edit" ) echo "<input type='hidden' name='edited_id' value='".$edit['id']."'/>";
	if( isset($send_new) )
	{
		$send_type = $send_new;
	}
	else $send_type = "new";
	echo "<input type='hidden' name='send' value='".@$send_type."'/></td><td><input class='submit' type='submit' value='Wy¶lij'/></td>";
	echo "</form>\n";
	echo "</tr>\n";
	
	// form:list of existing items
	
	echo "<form action='' method='post'>\n";
	echo "<tr><td colspan='2' align='left'><h6>Wybierz ju¿ istniej±cy:</h6></td></tr>\n";
	echo "<tr>\n";
	echo "<td align='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
	echo "<td>\n";
	
	$sql = "SELECT `id`,`date_start`,`title` FROM `".DBPREFIX."articles` ORDER BY `date_start` DESC";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0);
	{
		echo "  <select name='choosed_id' class='select'>\n";
		while($art = mysql_fetch_row($query))
		{
			echo "    <option value='".$art[0]."'>[ ".$art[0]." ] ".$art[1]." - ".stripslashes($art[2])."</option>\n";
		}
		echo "  </select>\n";
	}
	
	echo "</td>\n";
	echo "</form>\n";
	echo "</tr>\n";
	echo "</table>\n\n";
}

?>
