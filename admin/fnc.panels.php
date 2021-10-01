<?php

if( $_GET['option'] == "menu" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	session_check();
	
	$sql = "SELECT `id` FROM `".DBPREFIX."panels` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];
	
	if( @$_POST['send'] == "new" AND check_int($_POST['form4']) == TRUE)
	{
   		$new['id'] = $last_id+1;
	   	$new['name'] = addslashes($_POST['form1']);
		$new['content'] = addslashes($_POST['form2']);
		@$new['display'] = $_POST['form3'];
		if ( empty($new['display']) ) $new['display'] = 0;
		$new['order'] = $_POST['form4'];
		
		if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form4']) ) $result = "Brak potrzebnych danych";
		else
		{ 
			$sql = "INSERT INTO `".DBPREFIX."panels` VALUES('".$new['id']."','".$new['name']."','".$new['content']."','".$new['display']."','".$new['order']."');";
			mysql_query($sql);
			$result = "Panel zosta³ dodany";
		}
	}
	if( @$_POST['send'] == "edit" AND check_int($_POST['choosed_id']) == TRUE )
	{
   	$sql = "SELECT * FROM `".DBPREFIX."panels` WHERE `id` = '".$_POST['choosed_id']."' LIMIT 1";
   	$query = mysql_query($sql);
   	if( mysql_num_rows($query) > 0 )
   	{
   	   $data = mysql_fetch_assoc($query);
   	   
    		$edit['id'] = $_POST['choosed_id'];
    		$edit['name'] = stripslashes($data['name']);
    		$edit['content'] = stripslashes($data['content']);
    		$edit['display'] = $data['display'];
    		$edit['order'] = $data['order'];
    			
 		}
		$send_new = "update";
		$action = "Edycja";
		$result = "Wczytano poprawnie";
	}
	if( @$_POST['send'] == "update" )
	{
 		if( check_int($_POST['edited_id']) == TRUE AND check_int($_POST['form4']) == TRUE )
 		{
	  		$upt['name'] = addslashes($_POST['form1']);
			$upt['content'] = addslashes($_POST['form2']);
			@$upt['display'] = $_POST['form3'];
			if ( empty($upt['display']) ) $upt['display'] = 0;
			$upt['order'] = $_POST['form4'];
			
			if( empty($_POST['form1']) OR empty($_POST['form2']) OR empty($_POST['form4']) ) $result = "Brak potrzebnych danych";
			else
			{ 
   				$sql = "UPDATE `".DBPREFIX."panels` SET `name` = '".$upt['name']."', `content` = '".$upt['content']."', `display` = '".$upt['display']."', `order` = '".$upt['order']."' WHERE `id` = '".$_POST['edited_id']."'";
		   		$result = "Panel zosta³ zaaktualizowany";
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
	echo "<tr><th colspan='2'>Zarz±dzanie panelami menu</th></tr>\n";
	echo "<tr>\n<td colspan='2' align='left'><h6>"; if(isset($edit['name'])) echo "Edytujesz: <i>".$edit['name']."</i>"; else echo "Nowy"; echo "<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td>\n</tr>\n";
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td valign='top' width='150'>Nazwa</td><td><input class='text' type='text' name='form1' value='".@$edit['name']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td valign='top'>Zawarto¶æ</td><td><textarea class='textarea' style='height:167px' name='form2'>".@$edit['content']."</textarea></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Poka¿</td><td><input type=checkbox name='form3'"; if( @$edit['display'] == 1 ) echo " checked='checked'"; echo " value='1'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Kolejno¶æ</td><td><input class='p50' type='text' name='form4' value='".@$edit['order']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>";
	if( @$_POST['send'] == "new" OR @$_POST['send'] == "update" ) $send_type = "new";
	if( @!empty($_POST['send']) && @$_POST['send'] == "edit" ) echo "<input type='hidden' name='edited_id' value='".$edit['id']."'/>";
	if( isset($send_new) )
	{
		$send_type = $send_new;
	}
	else $send_type = "new";
	echo "<input type='hidden' name='send' value='".@$send_type."'/></td><td><input class='submit' type='submit' value='Wy¶lij'/></td>";
	echo "</form>\n";
	echo "</tr>\n";	

	echo "<tr><td colspan='2'><h6>Wybierz ju¿ istniej±cy:</h6></td></tr>\n";
	echo "<tr>\n";
	echo "<form class='form-style2' action='' method='post'>\n";
	echo "<td class='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
	echo "<td>\n";
		$sql = "SELECT * FROM `".DBPREFIX."panels` ORDER BY `display` DESC";
		$query = mysql_query($sql);
	if(mysql_num_rows($query)>0);
	{
		echo "  <select name='choosed_id' class='select'>\n";
		while($menu = mysql_fetch_assoc($query))
		{
			if( $menu['display'] == 0 ) $disp = "# ";
			echo "    <option value='".$menu['id']."'>".@$disp.$menu['name']."</option>\n";
		}
		echo "  </select>\n";
	}
	echo "</td>\n";
	echo "</form>\n";
	echo "</tr>\n";
	echo "</table>\n\n";
}

?>
