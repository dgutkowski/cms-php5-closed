<?php

if( $_GET['option'] == "faq" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	// fixing user id

	session_check();
	
	// fixing last item id
	
	$sql = "SELECT `id` FROM `".DBPREFIX."faq` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];
	
	// new item
	
	if( @$_POST['send'] == "new" AND check_int($_POST['form3']) == TRUE)
	{
   		$new['id'] = $last_id+1;
   		$new['text'] = addslashes($_POST['form1']);
		$new['answer'] = addslashes($_POST['form2']);
		$new['order'] = $_POST['form3'];
		
		if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form3']) ) $result = "Brak potrzebnych danych";
		else
		{ 
			$sql = "INSERT INTO `".DBPREFIX."faq` VALUES('".$new['id']."','".$new['text']."','".$new['answer']."','".$new['order']."');";
			mysql_query($sql);
			$result = "Pytanie zosta³o dodane";
		}
	}
	
	// edit item
	
	if( @$_POST['send'] == "edit" AND check_int($_POST['choosed_id']) == TRUE )
	{
   		$sql = "SELECT * FROM `".DBPREFIX."faq` WHERE `id` = '".$_POST['choosed_id']."' LIMIT 1";
	   	$query = mysql_query($sql);
	   	if( mysql_num_rows($query) > 0 )
	   	{
			$data = mysql_fetch_assoc($query);
	   	   
	    		$edit['id'] = $_POST['choosed_id'];
	    		$edit['text'] = stripslashes($data['question']);
	    		$edit['answer'] = stripslashes($data['answer']);
	    		$edit['order'] = $data['order'];
 		}
		$send_new = "update";
		$action = "Edycja";
		$result = "Wczytano poprawnie";
	}
	
	// update item
	
	if( @$_POST['send'] == "update" )
	{
 		if( check_int($_POST['edited_id']) == TRUE AND check_int($_POST['form3']) == TRUE )
 		{
	  		$upt['text'] = addslashes($_POST['form1']);
			$upt['answer'] = addslashes($_POST['form2']);
			$upt['order'] = $_POST['form3'];
			
			if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form3']) AND check_int($_POST['form3']) == FALSE) $result = "Brak potrzebnych danych";
			else
			{ 
   				$sql = "UPDATE `".DBPREFIX."faq` SET `question` = '".$upt['text']."', `answer` = '".$upt['answer']."', `order` = '".$upt['order']."' WHERE `id` = '".$_POST['edited_id']."'";
		   		$result = "Pytanie zosta³o zaaktualizowane";
	   		}
		}
   		mysql_query($sql);
	}
	
	// form
	
	if(!empty($result))
	{
		echo "<div class='suc'>".$result."</div>\n";
	}
	
	if( @strlen($edit['text']) > 50 ) $table_info = substr($edit['text'],0,50)."...";
	elseif( isset($edit['text']) ) $table_info = $edit['text'];
	
	echo "<table class='table0 w100 form-style1'>\n";
	echo "<tr><th colspan='2'>Zarz±dzanie pytaniami FAQ</th></tr>\n";
	echo "<tr>\n<td colspan='2' align='left'><h6>"; if(isset($edit['id'])) echo "Edytujesz: <i>".$table_info."</i>"; else echo "Nowy"; echo "<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td>\n</tr>\n";
	echo "<tr>";
	echo "<form class='' action='' method='post'>\n";
	echo "<td valign='top' width='150'>Pytanie</td><td><textarea style='height:75px' name='form1'>".@$edit['text']."</textarea></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td valign='top'>Odpowied¼</td><td><textarea style='height:75px' name='form2'>".@$edit['answer']."</textarea></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Kolejno¶æ</td><td><input class='text' type='text' style='width:50px;' name='form3' value='".@$edit['order']."'/><span class='text-tiny'> Je¿eli kolejno¶æ == 0 to pytanie nie zostanie pokazane</span></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>";
	if( @$_POST['send'] == "new" OR @$_POST['send'] == "update" ) { $send_type = "new"; }
	if( @!empty($_POST['send']) && @$_POST['send'] == "edit" ) echo "<input type='hidden' name='edited_id' value='".$edit['id']."'/>";
	if( isset($send_new) )
	{
		$send_type = $send_new;
	}
	else $send_type = "new";
	echo "<input type='hidden' name='send' value='".$send_type."'/></td><td><input class='submit' type='submit' value='Wy¶lij'/></td>";
	echo "</tr>\n";
	echo "</form>\n";
	
	// form:list of existing items

	echo "<form action='' method='post'>\n";
	echo "<tr><td colspan='2' align='left'><h6>Wybierz ju¿ istniej±cy:</h6></td></tr>\n";
	echo "<tr>\n";
	echo "<td align='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
	echo "<td>\n";
	
	$sql = "SELECT `id`,`question`,`order` FROM `".DBPREFIX."faq` ORDER BY `order` DESC";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0);
	{
		echo "  <select name='choosed_id' class='select'>\n";
		while($faq = mysql_fetch_assoc($query))
		{
			echo "    <option value='".$faq['id']."'>[ ".$faq['order']." ] ".stripslashes($faq['question'])."</option>\n";
		}
		echo "  </select>\n";
	}
	
	echo "</td>\n";
	echo "</tr>\n";
	echo "</form>\n";
	echo "</table>\n\n";
}

?>
