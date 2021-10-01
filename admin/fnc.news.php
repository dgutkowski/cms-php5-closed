<?php

if( $_GET['option'] == "news" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	// fixing user id

	session_check();
	
	// fixing last item id
	
	$sql = "SELECT `id` FROM `".DBPREFIX."news` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];
	
	// fixing date-time
	
	$ys = date("Y"); $ms = date("m"); $ds = date("d");
	$hs = "00"; $is = "00"; $ss = "00";
	$ye = date("Y",strtotime("+1 years")); $me = date("m"); $de = date("d");
	$he = "00"; $ie = "00"; $se = "00";
	
	// fixing off
	
	// news:send new one
	
	if( @$_POST['send'] == "new" )
	{
		$new['id'] = $last_id+1;
		$new['title'] = htmlspecialchars(addslashes($_POST['form1']));
		$new['text'] = addslashes($_POST['form2']);
		$new['ext'] = addslashes($_POST['form3']);
		$new['author'] = $_POST['author'];
		$new['lang'] = htmlspecialchars(addslashes($_POST['form4']));
		$new['date_start'] = $_POST['ys']."-".$_POST['ms']."-".$_POST['ds']." ".$_POST['hs'].":".$_POST['is'].":".$_POST['ss'];
		$new['date_end'] = $_POST['ye']."-".$_POST['me']."-".$_POST['de']." ".$_POST['he'].":".$_POST['ie'].":".$_POST['se'];
		@$new['comment'] = $_POST['form5'];
		@$new['rating'] = $_POST['form6'];
		
		if( empty($_POST['form1']) OR empty($_POST['form2']) ) $result = "Nie podano tytu³u lub podstawowej tre¶ci";
		else
		{
    		$sql = "INSERT INTO `".DBPREFIX."news` VALUES('".$new['id']."','".$new['title']."','".$new['text']."','".$new['ext']."','".$new['author']."','".$new['lang']."','".$new['date_start']."','".$new['date_end']."','".$new['comment']."','".$new['rating']."');";
			mysql_query($sql);
			$result = "News dodany";
  		}
	}
	
	// news:choosed to edit
	
	if( @$_POST['send'] == "edit" AND check_int($_POST['choosed_id']) == TRUE )
	{
   		$sql = "SELECT * FROM `".DBPREFIX."news` WHERE `id` = '".$_POST['choosed_id']."' LIMIT 1";
	   	$query = mysql_query($sql);
	   	if( @mysql_num_rows($query) > 0 )
   		{
			$data = mysql_fetch_assoc($query);
   	   		
    		$edit['id'] = $_POST['choosed_id'];
    		$edit['title'] = stripslashes($data['title']);
    		$edit['text'] = stripslashes($data['text']);
    		$edit['ext'] = stripslashes($data['text_ext']);
    		$edit['lang'] = stripslashes($data['languages']);
    		@$edit['comment'] = $data['allow_comment'];
    		@$edit['rating'] = $data['allow_rating'];
    		
    		$ys = substr($data['date_start'],0,4); $ms = substr($data['date_start'],5,2);
    		$ds = substr($data['date_start'],8,2); $hs = substr($data['date_start'],11,4);
  			$is = substr($data['date_start'],14,2); $ss = substr($data['date_start'],17,2);
  			
  			$ye = substr($data['date_end'],0,4); $me = substr($data['date_end'],5,2);
    		$de = substr($data['date_end'],8,2); $he = substr($data['date_end'],11,4);
  			$ie = substr($data['date_end'],14,2); $se = substr($data['date_end'],17,2); 		
		}
		$send_new = "update";
		$action = "Edycja";
		$result = "Wczytano poprawnie";
	}
	
	// news:update
	
	if( @$_POST['send'] == "update" )
	{
   		if( @$_POST['delete'] == 1 AND check_int($_POST['edited_id']) == TRUE)
	   	{
    		$sql = "DELETE FROM `".DBPREFIX."news` WHERE `id` = '".$_POST['edited_id']."' LIMIT 1";
    		$result = "News zosta³ usuniêty";
 		}
 		elseif( check_int($_POST['edited_id']) == TRUE )
 		{
	  		$upt['title'] = htmlspecialchars(addslashes($_POST['form1']));
			$upt['text'] = addslashes($_POST['form2']);
			$upt['ext'] = addslashes($_POST['form3']);
			$upt['lang'] = htmlspecialchars($_POST['form4']);
			$upt['date_start'] = $_POST['ys']."-".$_POST['ms']."-".$_POST['ds']." ".$_POST['hs'].":".$_POST['is'].":".$_POST['ss'];
			$upt['date_end'] = $_POST['ye']."-".$_POST['me']."-".$_POST['de']." ".$_POST['he'].":".$_POST['ie'].":".$_POST['se'];
			@$upt['comment'] = $_POST['form5'];
			@$upt['rating'] = $_POST['form6'];
			
			if( empty($_POST['form1']) OR empty($_POST['form2']) ) $result = "Nie podano tytu³u lub podstawowej tre¶ci";
			else
			{
	    		$sql = "UPDATE `".DBPREFIX."news` SET `title` = '".$upt['title']."', `text` = '".$upt['text']."', `text_ext` = '".$upt['ext']."', `languages` = '".$upt['lang']."', `date_start` = '".$upt['date_start']."', `date_end` = '".$upt['date_end']."', `allow_comment` = '".$upt['comment']."', `allow_rating` = '".$upt['rating']."' WHERE `id` = '".$_POST['edited_id']."'";
   				$result = "News zosta³ zaaktualizowany";
	 		}
		}
   		mysql_query($sql);
	}
	
	if(empty($_POST['send'])) $_POST['send'] = "new";
	
	// form
	
	if(!empty($result))
	{
		echo "<div class='suc'>".$result."</div>\n";
	}
	echo "<table class='table0 w100 form-style1'>\n";
	echo "<tr>\n<th colspan='2'>Zarz±dzanie nowinkami</th>\n</tr>\n";
	echo "<tr>\n<td colspan='2' align='left'><h6>"; if(isset($edit['id'])) echo "Edytujesz: <i>".$edit['title']."</i>"; else echo "Nowy"; echo "<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td>\n</tr>\n";
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
	echo "<td>Data archiwizacji</td><td>";
	echo form_select_date("ye",2010,2100,"Y",60,$ye);
	echo form_select_date("me",1,12,"m",40,$me);
	echo form_select_date("de",1,31,"d",40,$de);
	echo " : ";
	echo form_select_date("he",0,23,"H",40,$he);
	echo form_select_date("ie",0,59,"i",40,$ie);
	echo form_select_date("se",0,59,"s",40,$se);
	echo "</td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Tytu³</td><td><input class='text' type='text' name='form1' value='".@$edit['title']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td valign='top'>Tekst</td><td><textarea class='textarea' name='form2'>".@$edit['text']."</textarea></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td valign='top'><a style='cursor: pointer' onclick=rozwin('id-ext')>Rozszerzenie</a></td><td><textarea id='id-ext' class='h' class='textarea' name='form3'>".@$edit['ext']."</textarea></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Jêzyki</td><td><input class='text' type='text' name='form4' value='".@$edit['lang']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Zezwól na komentarze</td><td><input type=checkbox name='form5'"; if( @$edit['comment'] == 1 ) echo " checked='checked'"; echo " value='1'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Zezwól na ocenianie</td><td><input type=checkbox name='form6'"; if( @$edit['rating'] == 1 ) echo " checked='checked'"; echo " value='1'/></td>";
	echo "</tr>\n";
	if( @$_POST['send'] == "edit" )
  	{
   		echo "<tr>";
		echo "<td>Usuñ</td><td><input type=checkbox name='delete' value='1' /></td>";
 		echo "</tr>\n";
  	}
	echo "<tr>";
	echo "<td align='right'>BB Code</td><td><img src='images/icons/replies/yes.png'/></td>";
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
	echo "<input type='hidden' name='send' value='".$send_type."'/></td><td><input class='submit' type='submit' value='Wy¶lij'/></td>";
	echo "</form>\n";
	echo "</tr>\n";	
	
	// form:list of existing items
	
	echo "<form action='' method='post'>\n";
	echo "<tr><td colspan='2' align='left'><h6>Wybierz ju¿ istniej±cy:</h6></td></tr>\n";
	echo "<tr>\n";
	echo "<td align='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
	echo "<td>\n";
	
	$sql = "SELECT `id`,`date_start`,`title` FROM `".DBPREFIX."news` ORDER BY `date_start` DESC";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0);
	{
		echo "  <select name='choosed_id' class='select'>\n";
		while($art = mysql_fetch_row($query))
		{
			echo "    <option value='".$art[0]."'>[ ".$art[0]." ] ".$art[1]." - ".$art[2]."</option>\n";
		}
		echo "  </select>\n";
	}
	
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n\n";
}

?>
