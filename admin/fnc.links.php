<?php

if( $_GET['option'] == "links" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	// fixing user id

	session_check();
	
	// fixing last item id

	$sql = "SELECT `id` FROM `".DBPREFIX."links` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];

	// new item
	
	if( @$_POST['send'] == "new" AND check_int($_POST['form3']) == TRUE)
	{
   		$new['id'] = $last_id+1;
   		$new['name'] = addslashes($_POST['form1']);
		$new['url'] = addslashes($_POST['form2']);
		$new['order'] = $_POST['form3'];
		$new['cat'] = $_POST['form4'];
		
		if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form3']) ) $result = "Brak potrzebnych danych";
		else
		{
			$sql = "INSERT INTO `".DBPREFIX."links` VALUES('".$new['id']."','".$new['name']."','".$new['url']."','".$new['order']."','".$new['cat']."');";
			mysql_query($sql);
			$result = "Link zosta³ dodany";
		}
	}

	// edit item
	
	if( @$_POST['send'] == "edit" AND check_int($_POST['choosed_id']) == TRUE )
	{
   		$sql = "SELECT * FROM `".DBPREFIX."links` WHERE `id` = '".$_POST['choosed_id']."' LIMIT 1";
	   	$query = mysql_query($sql);
	   	if( mysql_num_rows($query) > 0 )
	   	{
	   		$data = mysql_fetch_assoc($query);
	   	   
	    		$edit['id'] = $_POST['choosed_id'];
	    		$edit['name'] = stripslashes($data['name']);
	    		$edit['url'] = stripslashes($data['url']);
	    		$edit['order'] = $data['order'];
	    		$edit['cat'] = $data['cat'];
	    			
 		}
		$send_new = "update";
		$result = "Wczytano poprawnie";
	}

	// update item
	
	if( @$_POST['send'] == "update" )
	{
 		if( check_int($_POST['edited_id']) == TRUE AND check_int($_POST['form3']) == TRUE )
 		{
	  		$upt['name'] = addslashes($_POST['form1']);
			$upt['url'] = addslashes($_POST['form2']);
			$upt['order'] = $_POST['form3'];
			$upt['cat'] = $_POST['form4'];
			
			if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form3']) ) $result = "Brak potrzebnych danych";
			else
			{
   				$sql = "UPDATE `".DBPREFIX."links` SET `name` = '".$upt['name']."', `url` = '".$upt['url']."', `order` = '".$upt['order']."', `cat` = '".$upt['cat']."' WHERE `id` = '".$_POST['edited_id']."'";
		   		$result = "Link zosta³ zaaktualizowany";
   			}
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
	echo "<tr><th colspan='2'>Zarz±dzanie ³±czami</th></tr>\n";
	echo "<tr>\n<td colspan='2' align='left'><h6>"; if(isset($edit['id'])) echo "Edytujesz: <i>".$table_info."</i>"; else echo "Nowy"; echo "<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td>\n</tr>\n";
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td width='150'>Nazwa</td><td><input class='text' type='text' name='form1' value='".@$edit['name']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Adres</td><td><input class='text' type='text' name='form2' value='".@$edit['url']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Kolejno¶æ</td><td><input class='p50' type='text' name='form3' value='".@$edit['order']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Kategoria</td><td>\n";
	$sql = "SELECT * FROM `".DBPREFIX."links_cat` WHERE `order` > 0";
	$query = mysql_query($sql);
	if( mysql_num_rows($query) > 0 )
	{
		echo "<select name='form4' class='w50'>\n";
		echo "<option value='0'>Nie pokazuj</option>\n";
		while( $cat = mysql_fetch_assoc($query) )
		{
			echo "<option value='".$cat['id']."'"; if( $cat['id'] == @$edit['cat'] ) echo " selected='selected'"; echo ">".$cat['name']."</option>\n";
		}
		echo "</select>\n";
	}
	echo "</td>\n";
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
	echo "<tr><td colspan='2' align='left'><h6>Wybierz ju¿ istniej±cy:</h6></td></tr>\n";
	echo "<tr>\n";
	echo "<form action='' method='post'>\n";
	echo "<td class='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
	echo "<td>\n";
	$sql = "SELECT * FROM `".DBPREFIX."links_cat` ORDER BY `order` ASC";
	$query = mysql_query($sql);
	if( mysql_num_rows($query) > 0 )
	{
		echo "  <select name='choosed_id' class='select'>\n";
		
		while($cat = mysql_fetch_assoc($query))
		{
		   echo "<optgroup label='".$cat['name']; if($cat['order'] == 0) echo " (ukryta)"; echo "'>\n";
			$sql2 = "SELECT * FROM `".DBPREFIX."links` WHERE `cat` = '".$cat['id']."' ORDER BY `name` ASC";
			$query2 = mysql_query($sql2);
			if( mysql_num_rows($query2) > 0 )
			{
	    		while($link = mysql_fetch_assoc($query2))
	    		{
       			echo "    <option value='".$link['id']."'>".$link['name']." :: [".$link['url']."]</option>\n";
				}
	  		}
	  		echo "</optgroup>\n";
		}
		$sql2 = "SELECT * FROM `".DBPREFIX."links` WHERE `cat` = '0' ORDER BY `name` ASC";
		$query2 = mysql_query($sql2);
		if( mysql_num_rows($query2) > 0 )
		{
  			echo "<optgroup label='Ukryte (bez kategorii)'>\n";
			while($link = mysql_fetch_assoc($query2))
			{
				echo "    <option value='".$link['id']."'>".$link['name']." :: [".$link['url']."]</option>\n";
			}
			echo "</optgroup>\n";
 		}
		echo "  </select>\n";
	}
	echo "</td>\n";
	echo "</form>\n";
	echo "</tr>\n";
	echo "</table>\n\n";
}

?>
