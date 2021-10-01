<?php
if( $_GET['option'] == "download" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	// fixing user id

	session_check();
	
	// fixing last item id

	$sql = "SELECT `id` FROM `".DBPREFIX."files` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];
	
	// add item
	
	if( @$_POST['send'] == "new" AND check_int($_POST['form4']) == TRUE)
	{
		$new['id'] = $last_id+1;
   		$new['name'] = addslashes($_POST['form1']);
   		$new['desc'] = addslashes($_POST['form2']);
		$new['url'] = addslashes($_POST['form3']);
		$new['size'] = $_POST['form4'];
		$new['adds'] = $_SESSION['userid'];
		$new['cat'] = $_POST['form5'];
		$new['direct'] = $_POST['direct'];
		
		if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form3']) ) $result = "Brak potrzebnych danych";
		else
		{
			$sql = "INSERT INTO `".DBPREFIX."files` VALUES('".$new['id']."','".$new['name']."','".$new['desc']."','".$new['url']."','1','".$new['size']."','".DATE_SET."','".$new['adds']."','".$new['cat']."', '".$new['direct']."');";
			mysql_query($sql);
			$result = "Plik zosta³ dodany";
		}
	}
	
	// edit item
	
	if( @$_POST['send'] == "edit" AND check_int($_POST['file_id']) == TRUE )
	{
   		$sql = "SELECT * FROM `".DBPREFIX."files` WHERE `id` = '".$_POST['file_id']."' LIMIT 1";
	   	$query = mysql_query($sql);
	   	if( mysql_num_rows($query) > 0 )
	   	{
	   		$data = mysql_fetch_assoc($query);	   
	    		$edit['id'] = $_POST['file_id'];
	    		$edit['name'] = stripslashes($data['name']);
	    		$edit['desc'] = stripslashes($data['desc']);
	    		$edit['url'] = stripslashes($data['url']);
	    		$edit['size'] = $data['size'];
	    		$edit['cat'] = $data['cat'];
	    		$edit['direct'] = $data['direct'];
			$send_new = "update";
			$action = "Edycja";
			$result = "Wczytano poprawnie";		
		}
		else $result = "B³±d";	
	}
	
	// update item
	
	if( @$_POST['send'] == "update" )
	{
 		if( check_int($_POST['edited_id']) == TRUE AND check_int($_POST['form4']) == TRUE )
 		{
	  		$upt['name'] = addslashes($_POST['form1']);
	  		$upt['desc'] = addslashes($_POST['form2']);
			$upt['url'] = addslashes($_POST['form3']);
			$upt['size'] = $_POST['form4'];
			$upt['cat'] = $_POST['form5'];
			$upt['direct'] = $_POST['direct'];
			
			if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form3']) ) $result = "Brak potrzebnych danych";
			else
			{
   				$sql = "UPDATE `".DBPREFIX."files` SET `name` = '".$upt['name']."', `desc` = '".$upt['desc']."', `url` = '".$upt['url']."', `size` = '".$upt['size']."', `cat` = '".$upt['cat']."', `direct` = '".$upt['direct']."' WHERE `id` = '".$_POST['edited_id']."'";
		   		$result = "Plik zosta³ zaaktualizowany";
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
	echo "<tr><th colspan='2'>Zarz±dzanie dzia³em pobierania</th></tr>\n";
	echo "<tr>\n<td colspan='2' align='left'><h6>"; if(isset($edit['id'])) echo "Edytujesz: <i>".$table_info."</i>"; else echo "Nowy"; echo "<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td>\n</tr>\n";
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td width='150'>Nazwa</td><td><input class='text' type='text' name='form1' value='".@$edit['name']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Opis</td><td><input class='text' type='text' name='form2' value='".@$edit['desc']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Url</td><td><input class='text' type='text' name='form3' value='".@$edit['url']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Wielko¶æ</td><td><input class='p50' type='text' name='form4' value='".@$edit['size']."'/> KB</td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>FTP</td><td>".form_option_bool(@$edit['direct'],"direct","Serwer","Z zewn±trz")."</td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Kategoria</td><td>\n";
	$sql = "SELECT * FROM `".DBPREFIX."files_cat` WHERE `order` > 0";
	$query = mysql_query($sql);
	if( mysql_num_rows($query) > 0 )
	{
		echo "<select name='form5' class='w50'>\n";
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
	echo "<tr><td colspan='2'><h6>Wybierz ju¿ istniej±cy:</h6></td></tr>\n";
	echo "<tr>\n";
	echo "<form action='' method='post'>\n";
	echo "<td class='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
	echo "<td>\n";
	
	$sql = "SELECT * FROM `".DBPREFIX."files_cat` ORDER BY `order` ASC";
	$query = mysql_query($sql);
	if( mysql_num_rows($query) > 0 )
	{
		echo "  <select name='file_id' class='select'>\n";
		
		while($cat = mysql_fetch_assoc($query))
		{
		   echo " <optgroup label='".$cat['name']; if($cat['order'] == 0) echo " (ukryta)"; echo "'>\n";
			$sql2 = "SELECT * FROM `".DBPREFIX."files` WHERE `cat` = '".$cat['id']."' ORDER BY `name` ASC";
			$query2 = mysql_query($sql2);
			if( mysql_num_rows($query2) > 0 )
			{
	    		while($file = mysql_fetch_assoc($query2))
	    		{
       			echo " <option value='".$file['id']."'>".$file['name']." :: [".$file['size']."KB]</option>\n";
				}
	  		}
	  		echo " </optgroup>\n";
		}
		$sql2 = "SELECT * FROM `".DBPREFIX."files` WHERE `cat` = '0' ORDER BY `name` ASC";
		$query2 = mysql_query($sql2);
		if( mysql_num_rows($query2) > 0 )
		{
  			echo "<optgroup label='Ukryte (bez kategorii)'>\n";
			while($file = mysql_fetch_assoc($query2))
			{
				echo "    <option value='".$file['id']."'>".$file['name']." :: [".$file['size']."KB]</option>\n";
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
