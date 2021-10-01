<?php

if( $_GET['option'] == "download-cat" AND session_check_usrlevel() >= SPECIAL_LVL )
{	
	// fixing user id

	session_check();
	
	// fixing last item id

	$sql = "SELECT `id` FROM `".DBPREFIX."files_cat` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];
	
	// add item
	
	if( @$_POST['send'] == "new" AND check_int($_POST['form2']) == TRUE)
	{
   		$new['id'] = $last_id+1;
	   	$new['name'] = addslashes($_POST['form1']);
		$new['order'] = $_POST['form2'];
		
		$sql = "INSERT INTO `".DBPREFIX."files_cat` VALUES('".$new['id']."','".$new['name']."','".$new['order']."');";
		mysql_query($sql);
		$result = "Kategoria zosta³a dodana";
	}
	
	// edit item
	
	if( @$_POST['send'] == "edit" AND check_int($_POST['choosed_id']) == TRUE )
	{
   		$sql = "SELECT * FROM `".DBPREFIX."files_cat` WHERE `id` = '".$_POST['choosed_id']."' LIMIT 1";
	   	$query = mysql_query($sql);
	   	if( mysql_num_rows($query) > 0 )
	   	{
			$data = mysql_fetch_assoc($query);
	    		$edit['id'] = $_POST['choosed_id'];
	    		$edit['name'] = stripslashes($data['name']);
	    		$edit['order'] = $data['order'];
			$send_new = "update";
			$action = "Edycja";
			$result = "Wczytano poprawnie";			
 		}
	}
	
	// update item
	
	if( @$_POST['send'] == "update" )
	{
 		if( check_int($_POST['edited_id']) == TRUE AND check_int($_POST['form2']) == TRUE )
 		{
	  		$upt['name'] = addslashes($_POST['form1']);
			$upt['order'] = $_POST['form2'];
			
   			$sql = "UPDATE `".DBPREFIX."files_cat` SET `name` = '".$upt['name']."', `order` = '".$upt['order']."' WHERE `id` = '".$_POST['edited_id']."'";
	   		$result = "Kategoria zosta³a zaaktualizowana";
		}
   		mysql_query($sql);
	}
	
	if(!empty($result))
	{
		echo "<div class='suc'>".$result."</div>\n";
	}
	
	if( @strlen($edit['name']) > 50 ) $table_info = substr($edit['name'],0,50)."...";
	elseif( isset( $edit['name']) ) $table_info = $edit['name'];
	
	echo "<table class='table0 w100 form-style1'>\n";
	echo "<tr><th colspan='2'>Zarz±dzanie kategoriami pobierania</th></tr>\n";
	echo "<tr>\n<td colspan='2' align='left'><h6>"; if(isset($edit['id'])) echo "Edytujesz: <i>".$table_info."</i>"; else echo "Nowy"; echo "<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td>\n</tr>\n";
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td width='150'>Nazwa</td><td><input class='text' type='text' name='form1' value='".@$edit['name']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Kolejno¶æ</td><td><input class='p50' type='text' name='form2' value='".@$edit['order']."'/><span class='text-tiny'> Je¶li == 0 to nie zostanie pokazane</span></td>";
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
	echo "<td class='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
	echo "<td>\n";
	
	$sql = "SELECT * FROM `".DBPREFIX."files_cat` ORDER BY `id` ASC";
	$query = mysql_query($sql);
	if( mysql_num_rows($query) > 0 );
	{
		echo "  <select name='choosed_id' class='select'>\n";
		while($cat = mysql_fetch_assoc($query))
		{
			echo "    <option value='".$cat['id']."'>[".$cat['order']."] :: ".$cat['name']."</option>\n";
		}
		echo "  </select>\n";
	}
	echo "</td>\n";
	echo "</form>\n";
	echo "</tr>\n";
	echo "</table>\n\n";
}

?>
