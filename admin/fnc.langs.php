<?php

if( $_GET['option'] == "languages" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	// session check

	session_check();
	
	// fixing last item id & vars
	
	$sql = "SELECT `id` FROM `".DBPREFIX."reg_countries` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];
	
	// new item
	
	if( @$_POST['send'] == "new" )
	{
   		$new['id'] = $last_id+1;
   		$new['name'] = addslashes($_POST['form1']);
		$new['lang'] = addslashes($_POST['form2']);
		$new['code'] = addslashes($_POST['form3']);
		$new['charset'] = addslashes($_POST['form4']);
		$new['set'] = $_POST['set'];
		
		if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form3']) ) $result = "Brak potrzebnych danych";
		else
		{ 
			$sql = "INSERT INTO `".DBPREFIX."reg_countries` VALUES('".$new['id']."','".$new['name']."','".$new['lang']."','".$new['code']."','".$new['set']."','".$new['charset']."');";
			mysql_query($sql);
			$result = "Dodano poprawnie do bazy";
		}
	}
	
	// edit item
	
	if( @$_POST['send'] == "edit" AND check_int($_POST['choosed_id']) == TRUE )
	{
   		$sql = "SELECT * FROM `".DBPREFIX."reg_countries` WHERE `id` = '".$_POST['choosed_id']."' LIMIT 1";
	   	$query = mysql_query($sql);
	   	if( mysql_num_rows($query) > 0 )
	   	{
			$data = mysql_fetch_assoc($query);
	    		$edit['id'] = $_POST['choosed_id'];
	    		$edit['name'] = stripslashes($data['name']);
	    		$edit['lang'] = stripslashes($data['lang']);
	    		$edit['code'] = stripslashes($data['code']);
	    		$edit['charset'] = stripslashes($data['charset']);
	    		$edit['set'] = $data['set'];	
 		}
		$send_new = "update";
		$action = "Edycja";
		$result = "Wczytano poprawnie";
	}
	
	// update item
	
	if( @$_POST['send'] == "update" )
	{
 		if( check_int($_POST['edited_id']) == TRUE)
 		{
   			$upt['name'] = addslashes($_POST['form1']);
			$upt['lang'] = addslashes($_POST['form2']);
			$upt['code'] = addslashes($_POST['form3']);
			$upt['charset'] = addslashes($_POST['form4']);
			$upt['set'] = $_POST['set'];
			
			if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form3']) ) $result = "Brak potrzebnych danych";
			else
			{ 
   				$sql = "UPDATE `".DBPREFIX."reg_countries` SET `name` = '".$upt['name']."', `lang` = '".$upt['lang']."', `code` = '".$upt['code']."', `set` = '".$upt['set']."', `charset` = '".$upt['charset']."' WHERE `id` = '".$_POST['edited_id']."'";
		   		$result = "Dane zosta³y zaaktualizowane";
   			}
		}
   		mysql_query($sql);
	}
	
	// finish
	
	if( !empty($edit['code']) ) $table_info = "Edytujesz: ".$edit['code']; else $table_info = "Nowy";
	
	if(!empty($result))
	{
		echo "<div class='suc'>".$result."</div>\n";
	}
	echo "<table class='table0 w100 form-style1'>\n";
	echo "<tr><th colspan='2'>Zarz±dzanie zbiorem flag i jêzyków</th></tr>\n";
	echo "<tr><td colspan='2' align='left'><h6>".$table_info."<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td></tr>\n";
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td width='150'>Kraj</td><td><input class='text' type='text' name='form1' value='".@$edit['name']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td >Jêzyk</td><td><input class='text' type='text' name='form2' value='".@$edit['lang']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Kod</td><td><input class='w25' type='text' name='form3' value='".@$edit['code']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Znakowanie</td><td><input class='w25' type='text' name='form4' value='".@$edit['charset']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Ustawiony</td><td>\n";
	echo "<select class='select' name='set' style='width:150px;'>\n";
	echo "<option value='0'"; if( @$edit['set'] == 0 ) echo " selected='selected'"; echo ">Normalny</option>\n";
	echo "<option value='1'"; if( @$edit['set'] == 1 ) echo " selected='selected'"; echo ">T³umaczenie serwisu</option>\n";
	echo "<option value='9'"; if( @$edit['set'] == 9 ) echo " selected='selected'"; echo ">G³ówny</option>\n";
	echo "</select>\n";
	echo "</td>";
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
	echo "</form>\n";
	echo "</tr>\n";
	
	echo "<tr><td colspan='2'><h6>Wybierz ju¿ istniej±cy:</h6></td></tr>\n";
	echo "<tr>\n";
	echo "<form action='' method='post'>\n";
	echo "<td align='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
	echo "<td>\n";
	
	$sql = "SELECT * FROM `".DBPREFIX."reg_countries` ORDER BY `name` ASC";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0);
	{
		echo "  <select name='choosed_id' class='select'>\n";
		while($lang = mysql_fetch_assoc($query))
		{
			echo "    <option value='".$lang['id']."'>".$lang['code']." | ".$lang['lang']."</option>\n";
		}
		echo "  </select>\n";
	}
	echo "</td>\n";
	echo "</form>\n";
	echo "</tr>\n";
	echo "</table>\n";
}

?>
